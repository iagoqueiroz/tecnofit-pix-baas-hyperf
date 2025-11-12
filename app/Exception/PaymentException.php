<?php

declare(strict_types=1);

namespace App\Exception;

class PaymentException extends BusinessException
{
    public static function insufficientBalance(): self
    {
        return new self(403, 'Saldo insuficiente');
    }

    public static function accountNotFound(): self
    {
        return new self(404, 'Conta não encontrada');
    }
}
