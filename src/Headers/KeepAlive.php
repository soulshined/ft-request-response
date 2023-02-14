<?php

namespace FT\RequestResponse\Headers;

final class KeepAlive extends AbstractParameterizedHeader
{
    public readonly ?int $timeout;
    public readonly ?int $max;

    public function __construct(string $keepalive)
    {
        parent::__construct($keepalive);

        if ($this->has('timeout'))
            $this->timeout = (int) $this->params->timeout;
        else $this->timeout = null;

        if ($this->has('max'))
            $this->max = (int) $this->params->max;
        else $this->max = null;
    }
}
