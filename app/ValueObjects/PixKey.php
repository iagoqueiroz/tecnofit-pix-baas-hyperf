<?php
declare(strict_types=1);

namespace App\ValueObjects;

use App\Enum\PixKeyType;

class PixKey
{
    public function __construct(
        public readonly PixKeyType $type,
        public readonly string $key,
    ) {
        $this->validate();
    }

    protected function validate(): void
    {
        if ($this->type === PixKeyType::EMAIL && !filter_var($this->key, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('E-mail inválido.');
        }
    }

    public static function fromArray(array $data): self
    {
        return new self(
            PixKeyType::from($data['type']),
            $data['key'],
        );
    }
}