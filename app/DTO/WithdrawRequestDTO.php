<?php
declare(strict_types=1);

namespace App\DTO;

use App\Enum\WithdrawMethod;
use App\ValueObjects\PixKey;
use DateTimeImmutable;

class WithdrawRequestDTO
{
    public function __construct(
        public readonly string $accountId,
        public readonly WithdrawMethod $method,
        public readonly float $amount,
        public readonly ?DateTimeImmutable $schedule = null,
        public readonly ?PixKey $pix = null,
    ) {}

    public function isScheduled(): bool
    {
        return $this->schedule !== null;
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            accountId: $data['account_id'],
            method: WithdrawMethod::from($data['method']),
            amount: (float) $data['amount'],
            schedule: isset($data['schedule']) ? new DateTimeImmutable($data['schedule']) : null,
            pix: isset($data['pix']) ? PixKey::fromArray($data['pix']) : null,
        );
    }
}