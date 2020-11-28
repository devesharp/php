<?php

namespace Tests\Console;

use Devesharp\Support\Collection;

class MakeServiceTest extends \Tests\TestCase
{
    public function testGetSet()
    {
        $this->artisan('ds:validator ValidatorTest')
            ->execute();
    }
}
