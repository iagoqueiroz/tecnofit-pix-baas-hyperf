<?php

declare(strict_types=1);

use App\Command\WithdrawProcessCommand;
use Hyperf\Crontab\Crontab;

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    'enable' => true,
    'crontab' => [
        (new Crontab())
            ->setName('withdraw_process')
            ->setRule('* * * * *')
            ->setCallback([WithdrawProcessCommand::class, 'handle'])
            ->setMemo('Processa saques agendados pendentes.')
            ->setSingleton(true),
    ],
];
