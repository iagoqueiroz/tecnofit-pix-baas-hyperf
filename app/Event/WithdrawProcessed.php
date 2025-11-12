<?php
declare(strict_types=1);

namespace App\Event;

use App\Model\AccountWithdraw;

class WithdrawProcessed
{
    public function __construct(public AccountWithdraw $withdraw)
    {}
}