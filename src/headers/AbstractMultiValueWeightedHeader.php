<?php

namespace FT\RequestResponse\Headers;

abstract class AbstractMultiValueWeightedHeader extends AbstractMultiValueQualifiedParameterizedHeader
{
    public readonly array $weighted_directives;

    protected function __construct(string $value)
    {
        parent::__construct($value);

        $weighted = [];
        foreach ($this->directives as $dir) {
            $dir->weight = null;

            if (isset($dir->params->q)) {
                $dir->weight = (float) $dir->params->q;
                unset($dir->params->q);
                $weighted[] = $dir;
            }
        }

        uasort($weighted, function ($a, $b) {
            if ($a->weight === $b->weight) return 0;

            return $a->weight < $b->weight ? -1 : 1;
        });

        $this->weighted_directives = array_reverse($weighted);
    }

    public function getLowestWeight(): ?object
    {
        if (count($this->weighted_directives) === 0) return null;

        return array_values($this->weighted_directives)[count($this->weighted_directives) - 1];
    }

    public function getHighestWeight(): ?object
    {
        if (count($this->weighted_directives) === 0) return null;

        return array_values($this->weighted_directives)[0];
    }
}
