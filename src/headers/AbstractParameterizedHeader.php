<?php

namespace FT\RequestResponse\Headers;

abstract class AbstractParameterizedHeader extends AbstractMultiValueHeader
{
    public readonly object $params;

    protected function __construct(string $value)
    {
        parent::__construct($value);

        $params = new class { };
        foreach ($this->getCommaSeparatedValues() as $value) {
            $kvp = preg_split("/=/", $value);
            $key = str_replace("-", "_", strtolower($kvp[0]));
            if (count($kvp) === 2)
                $params->{$key} = $kvp[1];
            else $params->$key = null;
        }

        $this->params = $params;
        $this->directives = [];
        $this->count = count(get_object_vars($this->params));
    }

    public function has(string $type): bool
    {
        $type = str_replace("-", "_", strtolower($type));
        return property_exists($this->params, $type);
    }

    public function get(string $type)
    {
        $type = str_replace("-", "_", strtolower($type));
        return property_exists($this->params, $type) ? $this->params->$type : null;
    }
}
