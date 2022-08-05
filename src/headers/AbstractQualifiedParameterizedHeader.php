<?php

namespace FT\RequestResponse\Headers;

abstract class AbstractQualifiedParameterizedHeader extends AbstractHeader
{
    public readonly string $directive;
    public readonly object $params;

    protected function __construct(string $value)
    {
        parent::__construct($value);
        $parts = preg_split("/;/", $value);
        $this->directive = trim(array_shift($parts));

        $params = new class { };
        foreach ($parts as $value) {
            $kvp = preg_split("/=/", $value);
            $key = str_replace("-", "_", strtolower(trim($kvp[0])));
            if (count($kvp) === 2)
                $params->{$key} = $kvp[1];
            else $params->$key = null;
        }

        $this->params = $params;
    }

    public function param_exists(string $prop) : bool {
        $prop = strtolower(str_replace("-", "_", $prop));
        return property_exists($this->params, $prop);
    }
}
