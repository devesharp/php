<?php

namespace Devesharp\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class MakeAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'ds:all';

    protected $description = 'Command description';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the service.'],
        ];
    }

    public function handle()
    {
        $this->callSilent('ds:validator', [ 'name' => $this->argument('name') ]);
        $this->callSilent('ds:transformer', [ 'name' => $this->argument('name') ]);
        $this->callSilent('ds:policy', [ 'name' => $this->argument('name') ]);
        $this->callSilent('ds:service', [ 'name' => $this->argument('name') ]);
        $this->callSilent('ds:repository', [ 'name' => $this->argument('name') ]);
        $this->callSilent('ds:controller', [ 'name' => $this->argument('name') ]);

        return 0;
    }
}
