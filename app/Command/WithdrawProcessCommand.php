<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\AccountWithdraw;
use App\Services\WithdrawService;
use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Psr\Container\ContainerInterface;

#[Command]
class WithdrawProcessCommand extends HyperfCommand
{
    #[Inject]
    protected WithdrawService $withdrawService;

    public function __construct(protected ContainerInterface $container)
    {
        parent::__construct('withdraw:process-scheduled');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Processa saques agendados pendentes.');
    }

    public function handle()
    {
        Db::transaction(function () {
            $this->line('Iniciando processamento de saques agendados...', 'info');

            $withdraws = AccountWithdraw::query()
                ->where('scheduled', true)
                ->where('done', false)
                ->where('scheduled_for', '<=', date('Y-m-d H:i:s'))
                ->get();

            $this->line(sprintf('Encontrados %d saques agendados para processar.', $withdraws->count()), 'info');

            foreach ($withdraws as $withdraw) {
                try {
                    $this->withdrawService->execute($withdraw);
                    $this->line(sprintf('Saque %s processado com sucesso.', $withdraw->id), 'info');
                } catch (\Throwable $e) {
                    $this->line(sprintf('Erro ao processar saque %s: %s', $withdraw->id, $e->getMessage()), 'error');
                }
            }

            $this->line('Processamento de saques agendados concluído.', 'info');
        });
    }
}
