<?php

namespace Devesharp\APIDocs;

use Illuminate\Console\Command;
use cebe\openapi\spec\OpenApi;
use cebe\openapi\spec\PathItem;

class Dictionary
{
    protected $apiDic = [];

    protected $replaceBodyKeys = [];

    protected $replaceResponseKeys = [];

    function checkKeyReplace($context, $type, $key, $default = null) {
        if ($type == 'body') {
            return $this->replaceBodyKeys[$context][$key] ?? $default;
        }else if ($type == 'response') {
            return $this->replaceResponseKeys[$context][$key] ?? $default;
        }

        return $default;
    }

    function replaceString($string) {
        return str_replace(array_keys($this->apiDic), array_values($this->apiDic), $string);
    }
}
