<?php

namespace Aloware\HorizonWildcardConsumer\Commands;

use Aloware\HorizonWildcardConsumer\MasterSupervisor;
use Aloware\HorizonWildcardConsumer\ProvisioningPlan;
use Illuminate\Console\Command;
use Laravel\Horizon\Contracts\MasterSupervisorRepository;

class HorizonWildcardConsumeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'horizon:wildcard-consume {--environment= : The environment name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a master supervisor in the foreground';

    /**
     * Indicates whether the command should be shown in the Artisan command list.
     *
     * @var bool
     */
    protected $hidden = true;

    /**
     * Execute the console command.
     *
     * @param  \Laravel\Horizon\Contracts\MasterSupervisorRepository  $masters
     * @return void
     */
    public function handle(MasterSupervisorRepository $masters)
    {
        if ($masters->find(MasterSupervisor::name())) {
            return $this->comment('A master supervisor is already running on this machine.');
        }

        $master = (new MasterSupervisor)->handleOutputUsing(function ($type, $line) {
            $this->output->write($line);
        });

        $env = $this->option('environment') ?? config('horizon.env') ?? config('app.env');

        $provisioning = ProvisioningPlan::get(MasterSupervisor::name());

        $provisioning->deploy($env);

        $this->info('Horizon started successfully using ' . config('queue.default', 'redis') . ' driver');

        pcntl_async_signals(true);

        pcntl_signal(SIGINT, function () use ($master) {
            $this->line('Shutting down...');

            return $master->terminate();
        });

        $master->monitor($provisioning, $env);
    }
}