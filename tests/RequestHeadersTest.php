<?php

use FT\RequestResponse\Headers\RequestHeaders;
use PHPUnit\Framework\TestCase;

final class RequestHeadersTest extends TestCase
{

    private function build_headers(array $headers) : RequestHeaders {
        return new RequestHeaders($headers);
    }

    /**
    * @test
    */
    public function authorization_test() {
        $headers = $this->build_headers([
            'HTTP_AUTHORIZATION' => 'Basic aoseucnhaoeusncghsaonceuh'
        ]);
        $this->assertNotNull($headers->authorization);
        $this->assertEquals('Basic', $headers->authorization->auth_scheme);
        $this->assertEquals('aoseucnhaoeusncghsaonceuh', $headers->authorization->credentials);
    }

    /**
    * @test
    */
    public function proxy_authorization_test() {
        $headers = $this->build_headers([
            'HTTP_PROXY_AUTHORIZATION' => 'Basic aoseucnhaoeusncghsaonceuh'
        ]);
        $this->assertNotNull($headers->proxy_authorization);
        $this->assertEquals('Basic', $headers->proxy_authorization->auth_scheme);
        $this->assertEquals('aoseucnhaoeusncghsaonceuh', $headers->proxy_authorization->credentials);
    }

    /**
    * @test
    */
    public function cache_control_test() {
        $headers = $this->build_headers([
            'HTTP_CACHE_CONTROL' => 'max-age=604800, s_maxage=604800, no-cache, must-revalidate, no-store, private, must-understand, no-transform, immutable, stale-while-revalidate=86400, stale-if-error=86400'
        ]);
        $this->assertNotNull($headers->cache_control);
        $this->assertEquals(604800, $headers->cache_control->max_age);
        $this->assertNull($headers->cache_control->max_stale);
        $this->assertNull($headers->cache_control->min_fresh);
        $this->assertEquals(604800, $headers->cache_control->s_maxage);
        $this->assertEquals(86400, $headers->cache_control->stale_while_revalidate);
        $this->assertEquals(86400, $headers->cache_control->stale_if_error);
        $this->assertTrue($headers->cache_control->no_cache);
        $this->assertTrue($headers->cache_control->must_revalidate);
        $this->assertFalse($headers->cache_control->proxy_revalidate);
        $this->assertTrue($headers->cache_control->no_store);
        $this->assertTrue($headers->cache_control->private);
        $this->assertFalse($headers->cache_control->public);
        $this->assertTrue($headers->cache_control->must_understand);
        $this->assertTrue($headers->cache_control->immutable);
        $this->assertTrue($headers->cache_control->no_transform);
        $this->assertFalse($headers->cache_control->only_if_cached);
    }

    public function sec_ch_ua_mobile_test() {
        $headers = $this->build_headers([ ]);

        $this->assertNull($headers->sec_ch_ua_mobile);

        $headers = $this->build_headers([
            'HTTP_SEC_CH_UA_MOBILE' => "?1"
        ]);

        $this->assertTrue($headers->sec_ch_ua_mobile);

        $headers = $this->build_headers([
            'HTTP_SEC_CH_UA_MOBILE' => "?0"
        ]);

        $this->assertTrue($headers->sec_ch_ua_mobile);
    }

    /**
    * @test
    */
    public function sec_ch_ua_platform_test() {
        $headers = $this->build_headers([
            'HTTP_SEC_CH_UA_PLATFORM' => 'unknown'
        ]);
        $this->assertTrue($headers->sec_ch_ua_platform->isUnknown());
        $this->assertFalse($headers->sec_ch_ua_platform->isAndroid());
        $this->assertFalse($headers->sec_ch_ua_platform->isChromeOS());
        $this->assertFalse($headers->sec_ch_ua_platform->isChromiumOS());
        $this->assertFalse($headers->sec_ch_ua_platform->isIOS());
        $this->assertFalse($headers->sec_ch_ua_platform->isLinux());
        $this->assertFalse($headers->sec_ch_ua_platform->isMacOS());
        $this->assertFalse($headers->sec_ch_ua_platform->isWindows());

        $headers = $this->build_headers([
            'HTTP_SEC_CH_UA_PLATFORM' => 'TempleOS'
        ]);
        $this->assertTrue($headers->sec_ch_ua_platform->isUnknown());
    }

    /**
    * @test
    */
    public function device_memory_test() {
        $headers = $this->build_headers([
            'HTTP_DEVICE_MEMORY' => '1'
        ]);
        $this->assertEquals(1, $headers->device_memory);
    }

    /**
    * @test
    */
    public function downlink_test()
    {
        $headers = $this->build_headers([
            'HTTP_downlink' => '9.9'
        ]);
        $this->assertEquals(9.9, $headers->downlink);
    }

    /**
    * @test
    */
    public function ect_test() {
        $headers = $this->build_headers([
            'HTTP_ECT' => 'slow-2g'
        ]);

        $this->assertTrue($headers->ect->isSlow2g());
        $this->assertFalse($headers->ect->is2g());
        $this->assertFalse($headers->ect->is3g());
        $this->assertFalse($headers->ect->is4g());
    }

    /**
    * @test
    */
    public function rtt_test() {
        $headers = $this->build_headers([
            'HTTP_RTT' => '1235'
        ]);

        $this->assertEquals(1235, $headers->rtt);
    }

    /**
    * @test
    */
    public function save_data_test() {
        $headers = $this->build_headers([
            'HTTP_SAVE_DATA' => 'foobar'
        ]);

        $this->assertNull($headers->save_data);

        $headers = $this->build_headers([
            'HTTP_SAVE_DATA' => 'off'
        ]);

        $this->assertFalse($headers->save_data);

        $headers = $this->build_headers([
            'HTTP_SAVE_DATA' => 'on'
        ]);

        $this->assertTrue($headers->save_data);
    }

    /**
     * @group single
    * @test
    */
    public function last_modified_test() {
        $headers = $this->build_headers([
            'HTTP_LAST_MODIFIED' => 'Wed, 21 Oct 2015 07:28:00 GMT'
        ]);

        //rss closest | rfc1123 | rfc2822
        $this->assertEquals('Wed, 21 Oct 2015 07:28:00 GMT', $headers->last_modified->date->format(DATE_RFC7231));
    }

    /**
    * @test
    */
    public function if_match_test() {
        $headers = $this->build_headers([
            'HTTP_IF_MATCH' => 'bfc13a64729c4290ef5b2c2730249c88ca92d82d'
        ]);

        $this->assertEquals(1, $headers->if_match->count);
        $this->assertFalse($headers->if_match->has_wildcard());

        $headers = $this->build_headers([
            'HTTP_IF_MATCH' => '"67ab43", "54ed21", "7892dd"'
        ]);

        $this->assertEquals(3, $headers->if_match->count);
        $this->assertFalse($headers->if_match->has_wildcard());

        $headers = $this->build_headers([
            'HTTP_IF_MATCH' => '*'
        ]);

        $this->assertEquals(1, $headers->if_match->count);
        $this->assertTrue($headers->if_match->has_wildcard());
    }

    /**
    * @test
    */
    public function if_none_match_test() {
        $headers = $this->build_headers([
            'HTTP_IF_NONE_MATCH' => 'bfc13a64729c4290ef5b2c2730249c88ca92d82d'
        ]);

        $this->assertEquals(1, $headers->if_none_match->count);
        $this->assertFalse($headers->if_none_match->has_wildcard());

        $headers = $this->build_headers([
            'HTTP_IF_NONE_MATCH' => 'W/"67ab43", "54ed21", "7892dd"'
        ]);

        $this->assertEquals(3, $headers->if_none_match->count);
        $this->assertFalse($headers->if_none_match->has_wildcard());

        $headers = $this->build_headers([
            'HTTP_IF_NONE_MATCH' => '*'
        ]);

        $this->assertEquals(1, $headers->if_none_match->count);
        $this->assertTrue($headers->if_none_match->has_wildcard());
    }

    /**
    * @test
    */
    public function if_modified_since_test() {
        $headers = $this->build_headers([
            'HTTP_IF_MODIFIED_SINCE' => 'Wed, 21 Oct 2015 07:28:00 GMT'
        ]);

        $this->assertEquals('Wed, 21 Oct 2015 07:28:00 GMT', $headers->if_modified_since->date->format(DATE_RFC7231));
    }

    /**
    * @test
    */
    public function if_unmodified_since_test()
    {
        $headers = $this->build_headers([
            'HTTP_IF_UNMODIFIED_SINCE' => 'Wed, 21 Oct 2015 07:28:00 GMT'
        ]);

        $this->assertEquals('Wed, 21 Oct 2015 07:28:00 GMT', $headers->if_unmodified_since->date->format(DATE_RFC7231));
    }

    /**
    * @test
    */
    public function connection_test() {
        $headers = $this->build_headers([
            'HTTP_CONNECTION' => 'keep-alive, accept'
        ]);
        $this->assertTrue($headers->connection->has('accept'));
        $this->assertTrue($headers->connection->has('keep-alive'));
    }

    /**
    * @test
    */
    public function keep_alive_test() {
        $headers = $this->build_headers([
            'HTTP_KEEP_ALIVE' => 'timeout=999'
        ]);

        $this->assertNull($headers->keep_alive->max);
        $this->assertEquals(999, $headers->keep_alive->timeout);

        $headers = $this->build_headers([
            'HTTP_KEEP_ALIVE' => 'timeout=999, max=1000'
        ]);

        $this->assertEquals(999, $headers->keep_alive->timeout);
        $this->assertEquals(1000, $headers->keep_alive->max);
    }

    //test done in RequestTest
    // public function accept_test() {

    // }

    /**
    * @test
    */
    public function accept_encoding_test() {
        $headers = $this->build_headers([
            'ACCEPT_ENCODING' => 'deflate, gzip;q=1.0, *;q=0.5'
        ]);

        $this->assertTrue($headers->accept_encoding->has('deflate'));
        $this->assertTrue($headers->accept_encoding->has('gzip'));
        $this->assertTrue($headers->accept_encoding->has('*'));
        $this->assertTrue($headers->accept_encoding->has_wildcard());
        $this->assertEquals('*', $headers->accept_encoding->getLowestWeight()?->directive);
        $this->assertEquals('gzip', $headers->accept_encoding->getHighestWeight()?->directive);
    }

    /**
    * @test
    */
    public function accept_language_test() {
        $headers = $this->build_headers([
            'ACCEPT_LANGUAGE' => 'fr-CH, fr;q=0.9, en;q=0.8, de;q=0.7, *;q=0.5'
        ]);

        $this->assertTrue($headers->accept_language->has('fr-CH'));
        $this->assertTrue($headers->accept_language->has('fr'));
        $this->assertTrue($headers->accept_language->has('en'));
        $this->assertTrue($headers->accept_language->has('de'));
        $this->assertTrue($headers->accept_language->has('*'));
        $this->assertTrue($headers->accept_language->has_wildcard());
        $this->assertEquals('*', $headers->accept_language->getLowestWeight()?->directive);
        $this->assertEquals('fr', $headers->accept_language->getHighestWeight()?->directive);
    }

    /**
    * @test
    */
    public function expect_test() {
        $headers = $this->build_headers([
            'HTTP_EXPECT' => '100-continue'
        ]);

        $this->assertTrue($headers->expect->is100Continue());
    }

    /**
    * @test
    */
    public function access_control_request_headers_test() {
        $headers = $this->build_headers([
            'ACCESS_CONTROL_REQUEST_HEADERS' => 'X-PINGOTHER, Content-Type'
        ]);

        $this->assertTrue($headers->access_control_request_headers->has('X-pingother'));
        $this->assertTrue($headers->access_control_request_headers->has('Content-Type'));
    }

    /**
    * @test
    */
    public function content_disposition_test() {
        $headers = $this->build_headers([
            'HTTP_CONTENT_DISPOSITION' => 'form-data; name="fieldName"; filename="filename.jpg"'
        ]);

        $this->assertEquals('form-data', $headers->content_disposition->directive);
        $this->assertEquals('"fieldName"', $headers->content_disposition->params->name);
        $this->assertEquals('"filename.jpg"', $headers->content_disposition->params->filename);
    }

    // test done in RequestTest
    // public function content_type_test() {
    // }

    /**
    * @test
    */
    public function content_encoding_test() {
        $headers = $this->build_headers([
            'CONTENT-ENCODING' => 'deflate, gzip, compress'
        ]);

        $this->assertTrue($headers->content_encoding->has('deflate'));
        $this->assertTrue($headers->content_encoding->has('gzip'));
        $this->assertTrue($headers->content_encoding->has('compress'));
    }

    /**
    * @test
    */
    public function content_language_test() {
        $headers = $this->build_headers([
            'CONTENT-LANGUAGE' => 'de-DE, en-CA, en-US'
        ]);

        $this->assertTrue($headers->content_language->has('de-DE'));
        $this->assertTrue($headers->content_language->has('en-CA'));
        $this->assertTrue($headers->content_language->has('en-US'));
    }

    /**
    * @test
    */
    public function forwarded_test() {
        $headers = $this->build_headers([ //single value
            'HTTP-FORWARDED' => 'for=192.0.2.60;proto=http;by=203.0.113.43'
        ]);

        $this->assertTrue($headers->forwarded->has('for'));
        $this->assertTrue($headers->forwarded->has('proto'));
        $this->assertTrue($headers->forwarded->has('by'));
        $this->assertEquals('203.0.113.43', $headers->forwarded->get('by'));

        $headers = $this->build_headers([
            'HTTP-FORWARDED' => 'for=192.0.2.43, for=198.51.100.17, for=192.0.2.60;proto=http;by=203.0.113.43'
        ]);

        $this->assertEquals(3, $headers->forwarded->count);
        $this->assertTrue($headers->forwarded->has('for'));
    }

    /**
    * @test
    */
    public function x_forwarded_for_test() {
        $headers = $this->build_headers([
            'HTTP_X_FORWARDED_FOR' => '203.0.113.195,2001:db8:85a3:8d3:1319:8a2e:370:7348,150.172.238.178'
        ]);

        $this->assertEquals(3, $headers->x_forwarded_for->count);
        $this->assertTrue($headers->x_forwarded_for->has('2001:db8:85a3:8d3:1319:8a2e:370:7348'));
    }

    /**
    * @test
    */
    public function range_test() {
        $headers = $this->build_headers([
            'HTTP_RANGE' => 'bytes=200-1000, 2000-6576, 19000-'
        ]);

        $this->assertEquals('bytes', $headers->range->unit);
        $this->assertEquals(3, $headers->range->count);
        $this->assertTrue($headers->range->has('200-1000'));
        $this->assertEquals(200, $headers->range->ranges[0]->start);
        $this->assertEquals(1000, $headers->range->ranges[0]->end);
        $this->assertEquals(2000, $headers->range->ranges[1]->start);
        $this->assertEquals(6576, $headers->range->ranges[1]->end);
        $this->assertEquals(19000, $headers->range->ranges[2]->start);
        $this->assertNull($headers->range->ranges[2]->end);

        $headers = $this->build_headers([
            'HTTP_RANGE' => 'bytes=-500'
        ]);

        $this->assertEquals('bytes', $headers->range->unit);
        $this->assertEquals(1, $headers->range->count);
        $this->assertEquals(-500, $headers->range->ranges[0]->start);
        $this->assertNull($headers->range->ranges[0]->end);
    }

    /**
    * @test
    */
    public function if_range_test() {
        $headers = $this->build_headers([
            'HTTP_IF_RANGE' => 'Wed, 21 Oct 2015 07:28:00 GMT'
        ]);

        $this->assertTrue($headers->if_range->isDate());
        $this->assertFalse($headers->if_range->isEtag());
        $this->assertEquals('Wed, 21 Oct 2015 07:28:00 GMT', $headers->if_range->value->format(DATE_RFC7231));

        $headers = $this->build_headers([
            'HTTP_IF_RANGE' => 'W/"123456789"'
        ]);

        $this->assertTrue($headers->if_range->isEtag());
        $this->assertFalse($headers->if_range->isDate());
    }

    /**
    * @test
    */
    public function sec_fetch_site_test() {
        $headers = $this->build_headers([
            'SEC_FETCH_SITE' => 'cross-site'
        ]);

        $this->assertTrue($headers->sec_fetch_site->isCrossSite());
        $this->assertFalse($headers->sec_fetch_site->isSameOrigin());
        $this->assertFalse($headers->sec_fetch_site->isSameSite());
        $this->assertFalse($headers->sec_fetch_site->isNone());
    }

    /**
    * @test
    */
    public function sec_fetch_mode_test() {
        $headers = $this->build_headers([
            'SEC_FETCH_MODE' => 'same-origin'
        ]);

        $this->assertTrue($headers->sec_fetch_mode->isSameOrigin());
        $this->assertFalse($headers->sec_fetch_mode->isCors());
        $this->assertFalse($headers->sec_fetch_mode->isNavigate());
        $this->assertFalse($headers->sec_fetch_mode->isNoCors());
        $this->assertFalse($headers->sec_fetch_mode->isWebSocket());
    }

    /**
    * @test
    */
    public function sec_fetch_dest_test() {
        $headers = $this->build_headers([
            'SEC_FETCH_DEST' => 'embed'
        ]);

        $this->assertTrue($headers->sec_fetch_dest->isEmbed());
        $this->assertFalse($headers->sec_fetch_dest->isAudioWorklet());
        $this->assertFalse($headers->sec_fetch_dest->isDocument());
        $this->assertFalse($headers->sec_fetch_dest->isEmpty());
        $this->assertFalse($headers->sec_fetch_dest->shouldIgnore());

        $headers = $this->build_headers([
            'SEC_FETCH_DEST' => 'foobar'
        ]);

        $this->assertTrue($headers->sec_fetch_dest->shouldIgnore());
    }

    /**
    * @test
    */
    public function transfer_encoding_test() {
        $headers = $this->build_headers([
            'TRANSFER_ENCODING' => 'chunked'
        ]);

        $this->assertTrue($headers->transfer_encoding->has('chunked'));
        $this->assertFalse($headers->transfer_encoding->has('gzip'));

        $headers = $this->build_headers([
            'TRANSFER_ENCODING' => 'chunked, gzip'
        ]);

        $this->assertTrue($headers->transfer_encoding->has('chunked'));
        $this->assertTrue($headers->transfer_encoding->has('gzip'));
    }

    /**
    * @test
    */
    public function te_test() {
        $headers = $this->build_headers([
            'TE' => 'trailers, deflate;q=0.5'
        ]);

        $this->assertTrue($headers->te->has('trailers'));
        $this->assertTrue($headers->te->has('deflate'));
        $this->assertNotNull($headers->te->get('deflate'));
        $this->assertEquals(0.5, $headers->te->getLowestWeight()?->weight);
        $this->assertEquals(0.5, $headers->te->getHighestWeight()?->weight);
    }

    /**
    * @test
    */
    public function trailer_test() {
        $headers = $this->build_headers([
            'TRAILER' => 'Expires'
        ]);

        $this->assertTrue($headers->trailer->has('expires'));
    }

    /**
    * @test
    */
    public function upgrade_test() {
        $headers = $this->build_headers([
            'UPGRADE' => 'HTTP/2.0, SHTTP/1.3, IRC/6.9, RTA/x11'
        ]);

        $this->assertTrue($headers->upgrade->has('http/2.0'));
        $this->assertTrue($headers->upgrade->has('irc/6.9'));
    }

    /**
    * @test
    */
    public function is_header_test() {
        $headers = $this->build_headers([
            'UPGRADE' => 'HTTP/2.0, SHTTP/1.3, IRC/6.9, RTA/x11'
        ]);

        $this->assertNotNull($headers->getByName('http_upgrade'));
        $this->assertNull($headers->getByName('foobar'));
    }

}

?>