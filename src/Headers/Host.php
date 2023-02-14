<?php

namespace FT\RequestResponse\Headers;

final class Host extends AbstractHeader
{
    public readonly string $host;
    public readonly ?int $port;

    public function __construct(string $host)
    {
        parent::__construct($host);

        $parts = preg_split("/:/", $host);

        $this->host = strtolower($parts[0]);
        if (count($parts) > 1)
            $this->port = (int) $parts[1];
        else $this->port = null;
    }
}
