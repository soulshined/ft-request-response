<?php

namespace FT\RequestResponse\Headers;

use FT\RequestResponse\Utils;

abstract class AbstractMultiValueHeader extends AbstractHeader
{
    public array $directives;
    public int $count;

    protected function __construct(string $value)
    {
        parent::__construct($value);
        $this->directives = $this->getCommaSeparatedValues();
        $this->count = count($this->directives);
    }

    protected function getCommaSeparatedValues(): array
    {
        $array = preg_split("/,/", $this->raw);
        return array_filter(array_map(fn ($i) => trim($i), $array), fn ($i) => strlen($i) > 0);
    }

    public function has(string $type): bool
    {
        return $this->get($type) !== null;
    }

    public function get(string $type)
    {
        return Utils::array_find($this->directives, fn ($i) => strtolower($i) === strtolower($type));
    }

}
