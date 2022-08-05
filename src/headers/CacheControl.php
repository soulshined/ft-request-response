<?php

namespace FT\RequestResponse\Headers;

final class CacheControl extends AbstractParameterizedHeader
{
    public readonly ?int $max_age;
    public readonly ?int $max_stale;
    public readonly ?int $min_fresh;
    public readonly ?int $s_maxage;
    public readonly ?int $stale_while_revalidate;
    public readonly ?int $stale_if_error;
    public readonly bool $no_cache;
    public readonly bool $must_revalidate;
    public readonly bool $proxy_revalidate;
    public readonly bool $no_store;
    public readonly bool $private;
    public readonly bool $public;
    public readonly bool $must_understand;
    public readonly bool $immutable;
    public readonly bool $no_transform;
    public readonly bool $only_if_cached;

    public function __construct(string $cache)
    {
        parent::__construct($cache);

        if ($this->has('max_age'))
            $this->max_age = (int) $this->params->max_age;
        else $this->max_age = null;

        if ($this->has('max_stale'))
            $this->max_stale = (int) $this->params->max_stale;
        else $this->max_stale = null;

        if ($this->has('min_fresh'))
            $this->min_fresh = (int) $this->params->min_fresh;
        else $this->min_fresh = null;

        if ($this->has('s_maxage'))
            $this->s_maxage = (int) $this->params->s_maxage;
        else $this->s_maxage = null;

        if ($this->has('stale_while_revalidate'))
            $this->stale_while_revalidate = (int) $this->params->stale_while_revalidate;
        else $this->stale_while_revalidate = null;

        if ($this->has('stale_if_error'))
            $this->stale_if_error = (int) $this->params->stale_if_error;
        else $this->stale_if_error = null;

        $this->no_cache = $this->has('no_cache');
        $this->must_revalidate = $this->has('must_revalidate');
        $this->proxy_revalidate = $this->has('proxy_revalidate');
        $this->no_store = $this->has('no_store');
        $this->private = $this->has('private');
        $this->public = $this->has('public');
        $this->must_understand = $this->has('must_understand');
        $this->immutable = $this->has('immutable');
        $this->no_transform = $this->has('no_transform');
        $this->only_if_cached = $this->has('only_if_cached');
    }
}
