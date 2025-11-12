<?php
declare(strict_types=1);

namespace App\Enum;

use App\Traits\EnumValuesTrait;

enum WithdrawMethod: string
{
    use EnumValuesTrait;

    case PIX = 'PIX';

    public function isPix(): bool
    {
        return $this === self::PIX;
    }
}