<?php

namespace FT\RequestResponse\Headers;

final class SecFetchDest extends AbstractHeader
{
    public function __construct(string $dest)
    {
        parent::__construct($dest);
    }

    public function shouldIgnore(): bool {
        return !$this->isAudio()
            && !$this->isAudioWorklet()
            && !$this->isDocument()
            && !$this->isEmbed()
            && !$this->isEmpty()
            && !$this->isFont()
            && !$this->isFrame()
            && !$this->isImage()
            && !$this->isManifest()
            && !$this->isObject()
            && !$this->isPaintWorklet()
            && !$this->isReport()
            && !$this->isScript()
            && !$this->isServiceWorker()
            && !$this->isSharedWorker()
            && !$this->isStyle()
            && !$this->isTrack()
            && !$this->isVideo()
            && !$this->isWorker()
            && !$this->isXSLT();
    }

    public function isAudio(): bool
    {
        return $this->toLower() === 'audio';
    }

    public function isAudioWorklet(): bool
    {
        return $this->toLower() === 'audioworklet';
    }

    public function isDocument(): bool
    {
        return $this->toLower() === 'document';
    }

    public function isEmbed(): bool
    {
        return $this->toLower() === 'embed';
    }

    public function isEmpty(): bool
    {
        return $this->toLower() === 'empty';
    }

    public function isFont(): bool
    {
        return $this->toLower() === 'font';
    }

    public function isFrame(): bool
    {
        return $this->toLower() === 'frame';
    }

    public function isIframe(): bool
    {
        return $this->toLower() === 'iframe';
    }

    public function isImage(): bool
    {
        return $this->toLower() === 'image';
    }

    public function isManifest(): bool
    {
        return $this->toLower() === 'manifest';
    }

    public function isObject(): bool
    {
        return $this->toLower() === 'object';
    }

    public function isPaintWorklet(): bool
    {
        return $this->toLower() === 'paintworklet';
    }

    public function isReport(): bool
    {
        return $this->toLower() === 'report';
    }

    public function isScript(): bool
    {
        return $this->toLower() === 'script';
    }

    public function isServiceWorker(): bool
    {
        return $this->toLower() === 'serviceworker';
    }

    public function isSharedWorker(): bool
    {
        return $this->toLower() === 'sharedworker';
    }

    public function isStyle(): bool
    {
        return $this->toLower() === 'style';
    }

    public function isTrack(): bool
    {
        return $this->toLower() === 'track';
    }

    public function isVideo(): bool
    {
        return $this->toLower() === 'video';
    }

    public function isWorker(): bool
    {
        return $this->toLower() === 'worker';
    }

    public function isXSLT(): bool
    {
        return $this->toLower() === 'xslt';
    }
}
