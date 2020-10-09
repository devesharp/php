<?php

if (! function_exists('searchable_string')) {
    /**
     * @param  $string
     * @return string|string[]|null
     */
    function searchable_string($string)
    {
        $string = trim(\DS\Helper::normalizeString($string));
        $string = preg_replace('/\s+/', ' ', $string);

        return $string;
    }
}

if (! function_exists('array_filter_null')) {
    /**
     * @param  array $array
     * @return array
     */
    function array_filter_null(array $array)
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                if (is_array_assoc($value)) {
                    $value = array_filter_null($value);
                } else {
                    $value = array_values(array_filter_null($value));
                }
            }
        }

        return array_filter($array, fn ($value) => null !== $value);
    }
}

if (! function_exists('array_only')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param string $key
     * @param mixed  $default
     * @param mixed  $array
     * @param mixed  $keys
     *
     * @return array|\App\Support\Collection
     */
    function array_only($array, $keys)
    {
        // Converte string em array
        if (is_string($keys)) {
            $keys = explode(',', $keys);
        }

        // Verifica se é Collection ou Array
        $isCollection = false;
        if ($array instanceof \App\Support\Collection) {
            $isCollection = true;
            $array = $array->toArray();
        }

        $arrayDot = \Illuminate\Support\Arr::dot($array);

        $arrayOnly = [];
        foreach ($arrayDot as $key => $value) {
            // Remove valores numéricos das arrays array.0.name
            $keyD = preg_replace('/\.[0-9]+\./', '.', $key);
            $keyD = preg_replace('/\.[0-9]+$/', '', $keyD);
            $keyD = preg_replace('/^[0-9]+\./', '', $keyD);

            // Deixar apenas as keys foram passada
            foreach ($keys as $_key) {
                $_key = str_replace('.*', '', $_key);

                if (
                    $_key === $keyD ||
                    preg_match('/' . $_key . '\.(.*)/', $keyD)
                ) {
                    //                var_dump($keyD);
                    //                if ($_key === $keyD) {
                    $arrayOnly[$key] = $arrayDot[$key];
                }
            }
        }

        // Converte array novamente a original
        $newArray = [];
        foreach ($arrayOnly as $key => $value) {
            \Illuminate\Support\Arr::set($newArray, $key, $value);
        }

        return $isCollection
            ? new \App\Support\Collection($newArray)
            : $newArray;
    }
}

if (! function_exists('array_exclude')) {
    /**
     * @param $array
     * @param $keys
     *
     * @return array|\App\Support\Collection
     */
    function array_exclude($array, $keys)
    {
        // Converte string em array
        if (is_string($keys)) {
            $keys = explode(',', $keys);
        }

        // Verifica se é Collection ou Array
        $isCollection = false;
        if ($array instanceof \App\Support\Collection) {
            $isCollection = true;
            $array = $array->toArray();
        }

        $arrayDot = \Illuminate\Support\Arr::dot($array);

        foreach ($arrayDot as $key => $value) {
            // Remove valores numéricos das arrays array.0.name
            $keyD = preg_replace('/\.[0-9]+\./', '.', $key);
            $keyD = preg_replace('/^[0-9]+\./', '', $keyD);

            // Remove keys foram passada
            foreach ($keys as $_key) {
                $_key = str_replace('.*', '', $_key);

                if (
                    $_key === $keyD ||
                    preg_match('/' . $_key . '\.(.*)/', $keyD)
                ) {
                    unset($arrayDot[$key]);
                }
            }
        }

        // Converte array novamente a original
        $newArray = [];
        foreach ($arrayDot as $key => $value) {
            \Illuminate\Support\Arr::set($newArray, $key, $value);
        }

        return $isCollection
            ? new \App\Support\Collection($newArray)
            : $newArray;
    }
}

if (! function_exists('is_array_assoc')) {
    /**
     * Verificar se array é associativa.
     *
     * @param $arr
     *
     * @return bool
     */
    function is_array_assoc($arr)
    {
        if (! is_array($arr)) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}

if (! function_exists('object_to_array')) {
    /**
     * Converte objeto em array.
     *
     * @param  $object
     * @return array
     */
    function object_to_array($object)
    {
        return json_decode(json_encode($object), true);
    }
}

if (! function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    function env($key, $default = null)
    {
        return Env::get($key, $default);
    }
}

if (! function_exists('trim_spaces')) {
    function trim_spaces($string)
    {
        $string = preg_replace('/\s+/', ' ', $string);

        return trim($string);
    }
}

if (! function_exists('config')) {
    function config($key)
    {
        return \App\Support\Config::get($key);
    }
}

if (! function_exists('is_numeric_array')) {
    function is_numeric_array($value)
    {
        if (! is_array($value)) {
            return false;
        }

        foreach ($value as $v) {
            if (! is_int($v)) {
                return false;
            }
        }

        return true;
    }
}

if (! function_exists('is_numeric_string')) {
    function is_numeric_string($value)
    {
        if (! is_array($value)) {
            return false;
        }

        foreach ($value as $v) {
            if (! is_string($v)) {
                return false;
            }
        }

        return true;
    }
}

if (! function_exists('validator')) {
    /**
     * Gets the value of an validatorironment variable.
     *
     * @param string $key
     * @param mixed  $default
     * @param array  $data
     * @param array  $rules
     *
     * @return Illuminate\Validation\Validator
     */
    function validator(array $data, array $rules)
    {
        $validator = (new \App\Support\Validator())->make($data, $rules);

        $validator->addExtension('numeric_array', function (
            $attribute,
            $value,
            $parameters
        ) {
            if (! is_array($value)) {
                return false;
            }
            foreach ($value as $v) {
                if (! is_int($v)) {
                    return false;
                }
            }

            return true;
        });

        return $validator;
    }
}

if (! function_exists('randomLetters')) {
    /**
     * Gets random letters.
     *
     * @param  int    $size
     * @return string
     */
    function randomLetters(int $size)
    {
        $seed = str_split(
            'abcdefghijklmnopqrstuvwxyz' . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        ); // and any other characters
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $rand = '';
        foreach (array_rand($seed, $size) as $k) {
            $rand .= $seed[$k];
        }

        return $rand;
    }
}

if (! function_exists('only_number')) {
    /**
     * @param $string
     * @return string|string[]|null
     */
    function only_number($string)
    {
        return preg_replace('/[^0-9]/', '', $string);
    }
}
