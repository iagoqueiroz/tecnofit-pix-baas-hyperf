<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\WithdrawRequestDTO;
use App\Request\WithdrawRequest;
use App\Services\WithdrawService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Validation\ValidationException;

class WithdrawController
{
    #[Inject]
    protected WithdrawService $withdrawService;

    public function withdraw(WithdrawRequest $request, ResponseInterface $response, string $accountId)
    {
        $withdrawRequestDto = WithdrawRequestDTO::fromRequest([...$request->validated(), 'account_id' => $accountId]);
        $withdraw = $this->withdrawService->proccess($withdrawRequestDto);

        $status = $withdrawRequestDto->isScheduled() ? 'agendado' : 'processado';
        $message = $withdrawRequestDto->isScheduled()
            ? 'Saque agendado com sucesso.'
            : 'Saque realizado com sucesso.';

        return $response->json([
            'status' => $status,
            'message' => $message,
            'withdraw_id' => $withdraw->id,
            'scheduled_for' => $withdraw->scheduled_for ? $withdraw->scheduled_for->toDateTimeString() : null,
        ]);
    }
}

