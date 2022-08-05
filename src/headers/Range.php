<?php

namespace FT\RequestResponse\Headers;

final class Range extends AbstractMultiValueHeader
{
    public readonly string $unit;
    public readonly array $ranges;

    public function __construct(string $range)
    {
        parent::__construct($range);

        $parts = $this->getCommaSeparatedValues();

        $first = array_shift($parts);

        [$this->unit, $first_range] = preg_split("/=/", $first);

        array_unshift($parts, $first_range);

        $this->directives = $parts;

        $ranges = [];
        foreach ($parts as $range) {
            if (str_starts_with($range, '-')) {
                $ranges[] = (object)[
                    'start' => (int) $range,
                    'end' => null
                ];
                continue;
            }

            $kvp = preg_split("/\-/", $range, -1, PREG_SPLIT_NO_EMPTY);

            $ranges[] = (object)[
                'start' =>  (int) $kvp[0],
                'end' => count($kvp) > 1 ? $kvp[1] : null
            ];
        }

        $this->ranges = $ranges;
        $this->count = count($this->ranges);
    }
}
