<?php

declare(strict_types=1);

namespace App\Listener;

use App\Event\WithdrawProcessed;
use FriendsOfHyperf\Mail\Facade\Mail;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Psr\Log\LoggerInterface;

#[Listener]
class SendWithdrawSuccessMail implements ListenerInterface
{
    public function __construct(protected LoggerInterface $logger)
    {
    }

    public function listen(): array
    {
        return [
            WithdrawProcessed::class,
        ];
    }

    /**
     * @param WithdrawProcessed $event
     */
    public function process(object $event): void
    {
        $withdraw = $event->withdraw;
        
        if ($withdraw->method->isPix() && $withdraw->pix->type->isEmail()) {
            $this->logger->info("Enviando email de saque realizado com sucesso para o Withdraw ID: {$withdraw->id}");

            Mail::mailer('smtp')->to($withdraw->pix->key)
                ->send(new \App\Mail\WithdrawNotification($withdraw));
        }
    }
}
