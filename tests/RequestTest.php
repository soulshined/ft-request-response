<?php

use FT\RequestResponse\Enums\RequestMethods;
use FT\RequestResponse\Request;
use FT\RequestResponse\Tests\LocalServer;
use FT\RequestResponse\User\BasicAuthorizationUser;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

final class RequestTest extends TestCase  {

    private static LocalServer $testServer;
    private array $server_cache = [];

    public static function setUpBeforeClass(): void
    {
        self::$testServer = new LocalServer();
        self::$testServer->start();
    }

    static function tearDownAfterClass(): void
    {
        self::$testServer->stop();
    }

    protected function tearDown(): void
    {
        $_SERVER = $this->server_cache;
        $this->server_cache = [];
    }

    private function getClient() {
        return new Client([
            'base_uri' => self::$testServer->getBaseUrl(),
            'http_errors' => true
        ]);
    }

    /**
     * @test
     */
    public function simple_request_test() {
        $resp = $this->getClient()->get("/");
        $this->assertEquals(200, $resp->getStatusCode());

        $req = $this->transform_to_request($resp);

        $this->assertFalse($req->isHTTPS());
        $this->assertEquals(RequestMethods::GET, $req->METHOD);
        $this->assertTrue($req->isGET());
        $this->assertNull($req->body);
        $this->assertGreaterThan(0, $req->time);
        $this->assertEquals('127.0.0.1', $req->ip);
        $this->assertEquals('HTTP/1.1', $req->protocol);

        //url
        $this->assertEquals('127.0.0.1', $req->url->host);
        // $this->assertEquals(self::$testServer->getPort(), $req->url->port);
        $this->assertEquals('http', $req->url->scheme);
        // $this->assertEquals('127.0.0.1:' . self::$testServer->getPort(), $req->url->authority);
        $this->assertNull($req->url->password);
        $this->assertNull($req->url->user);
        $this->assertNull($req->url->query);
    }

    /**
     * @test
     */
    public function basic_auth_test() {
        $resp = $this->getClient()->get("/", [
            'headers' => [
                'Authorization' => 'Basic Zm9vYmFyOnBAJCR3MHJk'
            ]
        ]);
        $this->assertEquals(200, $resp->getStatusCode());

        $req = $this->transform_to_request($resp);

        $this->assertNull($req->body);
        $this->assertInstanceOf(BasicAuthorizationUser::class, $req->user);
        $this->assertEquals('foobar', $req->user->getUserName());
        $this->assertEquals('p@$$w0rd', $req->user->getPassword());
        // $this->assertEquals('foobar:p@$$w0rd@127.0.0.1:' . self::$testServer->getPort(), $req->url->authority);
    }

    /**
     * @test
     */
    public function complex_request_test() {
        $resp = $this->getClient()->get("/foos/bar?abc[]=123&name=this&abc[]=456#fragment", [
            'headers' => [
                'Content-Type' => 'text/plain',
                'X-Foobar' => 'bazz'
            ]
        ]);
        $this->assertEquals(200, $resp->getStatusCode());

        $req = $this->transform_to_request($resp);

        $this->assertNull($req->body);
        $this->assertEquals('text/plain', $req->headers->content_type->raw);
        $this->assertEquals('bazz', $req->headers->x_foobar);

        //url
        $this->assertEquals('127.0.0.1', $req->url->host);
        // $this->assertEquals(self::$testServer->getPort(), $req->url->port);
        $this->assertEquals('http', $req->url->scheme);
        // $this->assertEquals('127.0.0.1:' . self::$testServer->getPort(), $req->url->authority);
        $this->assertEquals('abc%5B%5D=123&name=this&abc%5B%5D=456', $req->url->query);
        $this->assertNull($req->url->password);
        $this->assertNull($req->url->user);
        $this->assertNull($req->url->fragment);
    }

    /**
     * @test
     */
    public function headers_test() {
        $referer = 'https://example.com';
        $cache = "max-age=604800, must-revalidate";
        $host = 'example.com';
        $accept_en = 'deflate, gzip;q=1.0, *;q=0.5';
        $acpt = 'text/*;q=0.3, text/html;q=0.7, text/plain, application/json;q=0.6, text/html;level=1, text/html;level=2;q=0.4, */*;q=0.5';

        $resp = $this->getClient()->get("/", [
            'headers' => [
                'Accept' => $acpt,
                'Referer' => $referer,
                'Cache-Control' => $cache,
                'Host' => $host,
                'Accept-Encoding' => $accept_en,
                'Foo-bar_Custom-bazz' => 'Buzz',
                'Content-Type' => 'multipart/form-data; level=1;boundary=something'
            ]
        ]);
        $this->assertEquals(200, $resp->getStatusCode());

        $req = $this->transform_to_request($resp);

        $this->assertNull($req->body);

        //test supported naming conventions
        $this->assertEquals('Buzz', $req->headers->foo_bar_custom_bazz);

        //content type
        $content = $req->headers->content_type;
        $this->assertEquals('multipart/form-data', $content->directive);
        $this->assertEquals('1', $content->params->level);
        $this->assertEquals('something', $content->params->boundary);

        // accept
        $accept = $req->headers->accept;
        $this->assertEquals($acpt, $accept->raw);
        $this->assertEquals(7, $accept->count);

        $expected_types = [
            '*/*',
            'text/*',
            'text/html',
            'text/plain',
            'application/json'
        ];

        foreach ($expected_types as $type) {
            $this->assertTrue($accept->has($type));
            $this->assertNotNull($accept->get($type));
        }

        $this->assertEquals(0.7, $accept->getHighestWeight()?->weight);
        $this->assertEquals(0.3, $accept->getLowestWeight()?->weight);

        $texthtmls = array_filter($accept->getAll('text/html'), fn($i) => isset($i->params->level) && $i->params->level === '1');

        $this->assertEquals(1, sizeof($texthtmls));
    }

    /**
     * @test
     */
    public function paramaters_test() {
        $resp = $this->getClient()->get("/?abc=123&def=456&g=55&hij[]=1&hij[]=2&k=");
        $this->assertEquals(200, $resp->getStatusCode());

        $req = $this->transform_to_request($resp);

        $this->assertNull($req->body);
        $this->assertEquals('123', $req->parameters->abc);
        $this->assertEquals('456', $req->parameters->def);
        $this->assertEquals(55, $req->parameters->g);
        $this->assertTrue(in_array(1, $req->parameters->hij));
        $this->assertTrue(in_array(2, $req->parameters->hij));
        $this->assertEquals('', $req->parameters->k);
        $this->assertTrue($req->isParameterSet('abc'));
        $this->assertTrue($req->isParameterSet('def'));
        $this->assertTrue($req->isParameterSet('g'));
        $this->assertTrue($req->isParameterSet('hij'));
        $this->assertTrue($req->isParameterSet('k'));
        $this->assertFalse($req->isParameterSetAndNotEmpty('k'));
        $this->assertTrue($req->isParameterSetAndNotEmpty('hij'));
        $this->assertTrue($req->isParameterSetAndNotEmpty('g'));
        $this->assertTrue($req->isParameterSetAndNotEmpty('def'));
        $this->assertTrue($req->isParameterSetAndNotEmpty('abc'));
    }

    private function transform_to_json(ResponseInterface $resp) : array {
        $contents = $resp->getBody()->getContents();
        $result = json_decode($contents, true);

        if (json_last_error() !== JSON_ERROR_NONE)
            $this->fail(json_last_error_msg() . " => " . $contents);

        return $result;
    }

    private function transform_to_request(ResponseInterface $resp) : Request {
        $body = $this->transform_to_json($resp);

        $this->server_cache = $_SERVER;

        $_SERVER = array_merge_recursive([], $body);

        return new Request;
    }
}

?>\