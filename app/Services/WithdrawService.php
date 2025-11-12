<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\WithdrawRequestDTO;
use App\Event\WithdrawProcessed;
use App\Exception\PaymentException;
use App\Model\Account;
use App\Model\AccountWithdraw;
use App\Model\AccountWithdrawPix;
use Carbon\Carbon;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Stringable\Str;
use Psr\EventDispatcher\EventDispatcherInterface;

class WithdrawService
{
    #[Inject]
    protected EventDispatcherInterface $dispatcher;

    public function proccess(WithdrawRequestDTO $withdrawRequest): AccountWithdraw
    {
        if ($withdrawRequest->isScheduled()) {
            $this->validateSchedule($withdrawRequest->schedule);
        }

        $withdraw = AccountWithdraw::create([
            'id' => (string) Str::uuid(),
            'account_id' => $withdrawRequest->accountId,
            'method' => $withdrawRequest->method,
            'amount' => $withdrawRequest->amount,
            'scheduled' => $withdrawRequest->isScheduled(),
            'scheduled_for' => $withdrawRequest->isScheduled() ? Carbon::parse($withdrawRequest->schedule) : null,
        ]);

        if ($withdrawRequest->method->isPix()) {
            AccountWithdrawPix::create([
                'account_withdraw_id' => $withdraw->id,
                'type' => $withdrawRequest->pix->type,
                'key' => $withdrawRequest->pix->key
            ]);
        }

        if (!$withdrawRequest->isScheduled()) {
            $this->execute($withdraw);
        }

        return $withdraw;
    }

    public function execute(AccountWithdraw $withdraw): void
    {
        Db::beginTransaction();

        try {
            $accouunt = Account::query()
                ->where('id', $withdraw->account_id)
                ->lockForUpdate()
                ->first();

            if (!$accouunt) {
                $withdraw->markAsFailed('Conta não encontrada.');
                throw PaymentException::accountNotFound();
            }

            $this->validateBalance($accouunt, $withdraw);

            $accouunt->balance -= $withdraw->amount;
            $accouunt->save();

            $withdraw->markAsDone();

            Db::commit();

            $this->dispatcher->dispatch(new WithdrawProcessed($withdraw));

        } catch (PaymentException $e) {
            Db::rollBack();
            $withdraw->markAsFailed($e->getMessage());

            throw $e;
        } catch (\Throwable $e) {
            Db::rollBack();

            throw $e;
        }
    }

    protected function validateSchedule(\DateTimeImmutable $schedule): void
    {
        if ($schedule <= new \DateTimeImmutable()) {
            throw new \InvalidArgumentException('A data informada precisar ser posterior a data e hora atuais.');
        }
    }

    protected function validateBalance(Account $account, AccountWithdraw $withdraw): void
    {
        if ($account->balance < $withdraw->amount) {
            throw PaymentException::insufficientBalance();
        }
    }
}