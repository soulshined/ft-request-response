<?php

use FT\RequestResponse\URL;
use PHPUnit\Framework\TestCase;

final class URLTest extends TestCase {

    /**
     * @test
     */
    public function should_parse_url() {
        $url = new URL("http://www.foobar.com:80/?a=b#toast");

        $this->assertEquals("http://www.foobar.com:80/?a=b#toast", "$url");
        $this->assertEquals('www.foobar.com', $url->host);
        $this->assertEquals('http', $url->scheme);
        $this->assertEquals('80', $url->port);
        $this->assertEquals('/', $url->path);
        $this->assertEquals('www.foobar.com:80', $url->authority);
        $this->assertEquals('a=b', $url->query);
        $this->assertEquals('toast', $url->fragment);
        $this->isNull($url->password);
        $this->isNull($url->user);
    }

}
