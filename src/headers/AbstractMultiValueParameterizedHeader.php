<?php

namespace FT\RequestResponse\Headers;

use FT\RequestResponse\Utils;

abstract class AbstractMultiValueParameterizedHeader extends AbstractMultiValueHeader
{
    protected function __construct(string $value, string $delimiter = ";")
    {
        parent::__construct($value);

        $directives = [];
        foreach ($this->getCommaSeparatedValues() as $value) {
            $params = new class { };
            foreach (preg_split("/\\" . $delimiter . "/", $value) as $subval) {
                $kvp = preg_split("/=/", $subval);
                $key = str_replace("-", "_", strtolower($kvp[0]));
                if (count($kvp) === 2)
                    $params->{$key} = $kvp[1];
                else $params->$key = null;
            }
            $directives[] = (object)['params' => $params];
        }

        $this->directives = $directives;
        $this->count = count($this->directives);
    }

    public function has(string $type): bool
    {
        $type = str_replace("-", "_", strtolower($type));
        return Utils::array_some($this->directives, fn($i) => property_exists($i->params, $type));
    }

    public function get(string $type)
    {
        $type = str_replace("-", "_", strtolower($type));
        $kvp = Utils::array_find($this->directives, fn ($i) => property_exists($i->params, $type));
        if ($kvp === null) return null;

        return $kvp->params->$type;
    }

    public function getAll(string $type): array
    {
        $type = str_replace("-", "_", strtolower($type));
        return array_filter($this->directives, fn ($i) => property_exists($i->params, $type));
    }

}
