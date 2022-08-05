<?php

namespace FT\RequestResponse\Headers;

final class ContentLanguage extends AbstractMultiValueHeader
{

    public function __construct(string $language)
    {
        parent::__construct($language);
    }
}
