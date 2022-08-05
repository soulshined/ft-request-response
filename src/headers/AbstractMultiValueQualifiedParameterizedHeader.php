<?php

namespace FT\RequestResponse\Headers;

use FT\RequestResponse\Utils;

abstract class AbstractMultiValueQualifiedParameterizedHeader extends AbstractMultiValueHeader
{
    protected function __construct(string $value)
    {
        parent::__construct($value);

        $this->directives = [];
        foreach ($this->getCommaSeparatedValues() as $value) {
            $parts = preg_split("/;/", $value);

            $i = [
                'directive' => trim(strtolower(array_shift($parts))),
                'params' => new class {}
            ];

            foreach ($parts as $part) {
                $kvp = preg_split("/=/", $part);
                $i['params']->{str_replace("-", "_", strtolower(trim($kvp[0])))} = trim($kvp[1]);
            }

            $this->directives[] = (object)$i;
        }

        $this->count = count($this->directives);
    }

    public function get(string $type): ?object
    {
        return Utils::array_find($this->directives, fn ($i) => $i->directive === strtolower($type));
    }

    public function getAll(string $type) : array {
        return array_filter($this->directives, fn($i) => $i->directive === strtolower($type));
    }

}
