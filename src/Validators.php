<?php

namespace Devesharp;

use Devesharp\Support\Collection;

class Validators
{
    /**
     * Rules.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Remover dados adicionais.
     *
     * @var bool
     */
    protected $additionalProperties = false;

    /**
     * @param $_data
     * @param $rules
     * @param bool $requiredAll
     * @return Collection
     * @throws \Devesharp\Exception
     */
    public function validate($_data, $rules, $requiredAll = false)
    {
        // Remove valores null
        $_data = array_filter_null($_data);

        if (is_string($rules)) {
            $rules = $this->getValidate($rules);
        }

        $validator = validator($_data, $rules);

        if ($validator->fails()) {
            throw new \Devesharp\Exception('Error data validate', \Devesharp\Exception::DATA_ERROR, null, $validator->errors()->getMessages());
        }

        /*
         * Verifica se deve excluir dados extras
         */
        if ($this->additionalProperties) {
            $data = $_data;
        } else {
            $data = array_only(
                (array) $_data,
                $this->getValidateValues($rules),
            );
        }

        return new Collection($data);
    }

    /**
     * Recuperar validação do contexto.
     *
     * @param $context
     * @param mixed $requiredAll
     *
     * @return mixed
     */
    protected function getValidate($context, $requiredAll = false)
    {
        if (! empty($this->rules[$context]['_extends'])) {
            $rules = $this->getExtendsValidator(
                $this->rules[$context],
                $this->rules[$context]['_extends'],
            );

            return $requiredAll
                ? $this->setAllValidateRequired($rules)
                : $rules;
        }

        return $requiredAll
            ? $this->setAllValidateRequired($this->rules[$context])
            : $this->rules[$context];
    }

    /**
     * Recuperar validação do contexto.
     * E incrementar valores de busca `query`.
     *
     * @param  $context
     * @param  bool        $requiredAll
     * @return array|mixed
     */
    protected function getValidateWithSearch($context, $requiredAll = false)
    {
        if (! empty($this->rules[$context]['_extends'])) {
            $rules = $this->getExtendsValidator(
                $this->rules[$context],
                $this->rules[$context]['_extends'],
            );

            $rules['query'] = 'array';
            $rules['query.limit'] = 'numeric';
            $rules['query.pagination'] = 'numeric';
            $rules['query.offset'] = 'numeric';

            return $requiredAll
                ? $this->setAllValidateRequired($rules)
                : $rules;
        }

        $rules = $this->rules[$context];
        $rules['query'] = 'array';
        $rules['query.limit'] = 'numeric';
        $rules['query.pagination'] = 'numeric';
        $rules['query.offset'] = 'numeric';

        return $requiredAll
            ? $this->setAllValidateRequired($this->rules[$context])
            : $rules;
    }

    /**
     * @param array  $rules
     * @param string $extends
     *
     * @return array
     */
    public function getExtendsValidator(array $rules, string $extends)
    {
        $extend = [];

        if (! empty($this->rules[$extends]['_extends'])) {
            $extend = $this->getExtendsValidator(
                $this->rules[$extends],
                $this->rules[$extends]['_extends'],
            );
        } else {
            $extend = $this->rules[$extends];
        }

        foreach ($rules as $key => $value) {
            if (null === $value) {
                unset($extend[$key]);
                unset($rules[$key]);

                /*
                 * Procura valores de array
                 * se permissions == null
                 * toda sua array deve ser removida
                 * permissions.bb
                 */
                foreach ($extend as $key2 => $value2) {
                    if (0 === strpos($key2, $key . '.')) {
                        unset($extend[$key2]);
                    }
                }

                foreach ($rules as $key2 => $value2) {
                    if (0 === strpos($key2, $key . '.')) {
                        unset($extend[$key2]);
                    }
                }
            }
        }

        return array_merge($extend, $rules);
    }

    /**
     * @param array $rules
     *
     * @return array
     */
    protected function getValidateValues(array $rules)
    {
        return array_keys($rules);
    }

    public function removeRequiredRules(array $array)
    {
        $newArray = [];

        foreach ($array as $key => $value) {
            if ('_extends' === $key) {
                continue;
            }

            $newArray[$key] = str_replace('required|', '', $value);
            $newArray[$key] = str_replace('|required', '', $newArray[$key]);
        }

        return $newArray;
    }

    public function setAllValidateRequired(array $array)
    {
        $newArray = [];

        foreach ($array as $key => $value) {
            if ('_extends' === $key) {
                continue;
            }
            $newArray[$key] = 'required|' . $value;
        }

        return $newArray;
    }

    public function additionalProperties($additionalProperties)
    {
        $this->additionalProperties = $additionalProperties;

        return $this;
    }
}
