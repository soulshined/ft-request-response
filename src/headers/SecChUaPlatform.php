<?php

namespace FT\RequestResponse\Headers;

final class SecChUaPlatform extends AbstractHeader
{

    public function __construct(string $platform)
    {
        parent::__construct($platform);
    }

    public function isAndroid(): bool
    {
        return $this->toLower() === 'android';
    }

    public function isChromeOS(): bool
    {
        return $this->toLower() === 'chrome os';
    }

    public function isChromiumOS(): bool
    {
        return $this->toLower() === 'chromium os';
    }

    public function isIOS(): bool
    {
        return $this->toLower() === 'ios';
    }

    public function isLinux(): bool
    {
        return $this->toLower() === 'linux';
    }

    public function isMacOS(): bool
    {
        return $this->toLower() === 'macOS';
    }

    public function isWindows(): bool
    {
        return $this->toLower() === 'windows';
    }

    public function isUnknown(): bool
    {
        return !$this->isAndroid() &&
            !$this->isChromeOS() &&
            !$this->isChromiumOS() &&
            !$this->isIOS() &&
            !$this->isLinux() &&
            !$this->isMacOS() &&
            !$this->isWindows();
    }
}
