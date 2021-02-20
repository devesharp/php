<?php

namespace Devesharp\Support;

trait ModelGetTable
{
    static function getTableName(): string {
        return (new self())->getTable();
    }
}
