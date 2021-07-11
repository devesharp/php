<?php

namespace Tests\CRUD\Mocks;

use Devesharp\CRUD\DataInterface\DataInterface;

class DataInterfaceStub extends DataInterface
{
    public string $name;
    public int $age;
    public DataInterfaceChildren $coop;
}


class DataInterfaceChildren extends DataInterface
{
    public string $name;
    public int $age;
}
