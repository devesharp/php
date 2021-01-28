<?php

namespace Devesharp\Testing;

use Carbon\Carbon;
use Devesharp\Support\Helpers;

trait TestCase
{
    /**
     * Verifica se todas as keys da $array são iguais da $leftArray
     * $array pode ter mais de keys que $leftArray, só é necessário ter as de $leftArray
     *
     * @param $leftArray
     * @param $array
     * @param array $exclude
     */
    function assertEqualsArrayLeft($leftArray, $array, $exclude = []) {
        $newLeftArray = Helpers::arrayExclude($leftArray, $exclude);

        foreach ($newLeftArray as $key => $item) {
            if ($item instanceof \DateTime) {
                $item = Carbon::make($item);
                $array[$key] = Carbon::make($array[$key]);
            }

            if (is_array($item)) {
                $item = json_encode($item);
            }
            if (is_array($array[$key])) {
                $array[$key] = json_encode($array[$key]);
            }

            $this->assertEquals($item, $array[$key], $key);
        }
    }
}
