<?php

namespace Tests\Console;

use Devesharp\Console\Commands\MakeService;
use Devesharp\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Tester\CommandTester;

class MakeServiceTest extends \Tests\TestCase
{
    public function testGetSet()
    {
        $command = app(MakeService::class);
        $command->setLaravel($this->app);

        $tester = new CommandTester($command);

        $tester->execute([
            'name' => 'Name'
        ]);
    }
}
