<?php
declare(strict_types=1);

namespace App\Enum;

use App\Traits\EnumValuesTrait;

enum PixKeyType: string
{
    use EnumValuesTrait;

    case EMAIL = 'email';

    public function isEmail(): bool
    {
        return $this === self::EMAIL;
    }
}