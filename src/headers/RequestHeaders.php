<?php

namespace FT\RequestResponse\Headers;

use JsonSerializable;

final class RequestHeaders implements JsonSerializable
{
    //region AUTHENTICATION
    public readonly ?Authorization $authorization;
    public readonly ?Authorization $proxy_authorization;
    //endregion AUTHENTICATION

    //region CACHING
    public readonly ?CacheControl $cache_control;
    //endregion CACHING

    //region CLIENT HINTS
    //endregion CLIENT HINTS

    //region USER AGENT CLIENT HINTS
    public readonly ?SecChUa $sec_ch_ua;
    public readonly ?string $sec_ch_ua_arch;
    public readonly ?string $sec_ch_ua_bitness;
    public readonly ?SecChUaFullVersionList $sec_ch_ua_full_version_list;
    public readonly ?bool $sec_ch_ua_mobile;
    public readonly ?string $sec_ch_ua_model;
    public readonly ?SecChUaPlatform $sec_ch_ua_platform;
    public readonly ?string $sec_ch_ua_plaftform_version;
    //endregion USER AGENT CLIENT HINTS

    //region DEVICE CLIENT HINTS
    public readonly ?float $device_memory;
    //endregion DEVICE CLIENT HINTS

    // region NETWORK CLIENT HINTS
    public readonly ?float $downlink;
    public readonly ?ECT $ect;
    public readonly ?int $rtt;
    public readonly ?bool $save_data;
    // endregion NETWORK CLIENT HINTS

    // region CONDITIONALS
    public readonly ?LastModified $last_modified;
    public readonly ?IfMatch $if_match;
    public readonly ?IfNoneMatch $if_none_match;
    public readonly ?IfModifiedSince $if_modified_since;
    public readonly ?IfUnModifiedSince $if_unmodified_since;
    // endregion CONDITIONALS

    // region CONNECTION MANAGEMENT
    public readonly ?Connection $connection;
    public readonly ?KeepAlive $keep_alive;
    // endregion CONNECTION MANAGEMENT

    // region CONTENT NEGOTIATION
    public readonly ?Accept $accept;
    public readonly ?AcceptEncoding $accept_encoding;
    public readonly ?AcceptLanguage $accept_language;
    // endregion CONTENT NEGOTIATION

    // region CONTROLS
    public readonly ?Expect $expect;
    public readonly ?int $max_forwards;
    // endregion CONTROLS

    // region COOKIES
    public readonly ?string $cookie;
    // endregion COOKIES

    // region CORS
    public readonly ?AccessControlRequestHeaders $access_control_request_headers;
    public readonly ?string $origin;
    // endregion CORS

    // region DOWNLOADS
    public readonly ?ContentDisposition $content_disposition;
    // endregion DOWNLOADS

    // region MESSAGE BODY INFORMATION
    public readonly ?int $content_length;
    public readonly ?ContentType $content_type;
    public readonly ?ContentEncoding $content_encoding;
    public readonly ?ContentLanguage $content_language;
    public readonly ?string $content_location;
    // endregion MESSAGE BODY INFORMATION

    // region PROXIES
    public readonly ?Forwarded $forwarded;
    public readonly ?XForwardedFor $x_forwarded_for;
    public readonly ?string $x_forwarded_host;
    public readonly ?string $x_forwarded_proto;
    public readonly ?string $via;
    // endregion PROXIES

    // region REDIRECTS
    // endregion REDIRECTS

    // region REQUEST CONTEXT
    public readonly ?string $from;
    public readonly ?Host $host;
    public readonly ?string $referer;
    public readonly ?string $user_agent;
    // endregion REQUEST CONTEXT

    // region RESPONSE CONTEXT
    // endregion RESPONSE CONTEXT

    // region RANGE REQUESTS
    public readonly ?Range $range;
    public readonly ?IfRange $if_range;
    // endregion RANGE REQUESTS

    // region SECURITY
    public readonly ?int $upgrade_insecure_requests;
    // endregion SECURITY

    // region FETCH METADATA REQUEST HEADERS
    public readonly ?SecFetchSite $sec_fetch_site;
    public readonly ?SecFetchMode $sec_fetch_mode;
    public readonly ?bool $sec_fetch_user;
    public readonly ?SecFetchDest $sec_fetch_dest;
    public readonly ?string $service_worker_navigation_preload;
    // endregion FETCH METADATA REQUEST HEADERS

    // region SERVER SENT EVENTS
    // endregion SERVER SENT EVENTS

    // region TRANSFER CODING
    public readonly ?TransferEncoding $transfer_encoding;
    public readonly ?TE $te;
    public readonly ?Trailer $trailer;
    // endregion TRANSFER CODING

    // region WEBSOCKETS
    // endregion WEBSOCKETS

    // region OTHER
    public readonly ?Date $date;
    public readonly ?bool $early_data;
    public readonly ?Upgrade $upgrade;
    // endregion OTHER

    public function __construct(array $headers)
    {
        $headers = array_combine(
            array_map(fn ($k) => $this->normalize_name($k), array_keys($headers)),
            array_values($headers)
        );

        if (key_exists('AUTHORIZATION', $headers)) {
            $this->authorization = new Authorization($headers['AUTHORIZATION']);
            unset($headers['AUTHORIZATION']);
        } else $this->authorization = null;

        if (key_exists('PROXY_AUTHORIZATION', $headers)) {
            $this->proxy_authorization = new Authorization($headers['PROXY_AUTHORIZATION']);
            unset($headers['PROXY_AUTHORIZATION']);
        } else $this->proxy_authorization = null;

        if (key_exists('CACHE_CONTROL', $headers)) {
            $this->cache_control = new CacheControl($headers['CACHE_CONTROL']);
            unset($headers['CACHE_CONTROL']);
        } else $this->cache_control = null;

        if (key_exists('SEC_CH_UA', $headers)) {
            $this->sec_ch_ua = new SecChUa($headers['SEC_CH_UA']);
            unset($headers['SEC_CH_UA']);
        } else $this->sec_ch_ua = null;

        if (key_exists('SEC_CH_UA_FULL_VERSION_LIST', $headers)) {
            $this->sec_ch_ua_full_version_list = new SecChUaFullVersionList($headers['SEC_CH_UA_FULL_VERSION_LIST']);
            unset($headers['SEC_CH_UA_FULL_VERSION_LIST']);
        } else $this->sec_ch_ua_full_version_list = null;

        if (key_exists('SEC_CH_UA_MOBILE', $headers)) {
            if ($headers['SEC_CH_UA_MOBILE'] === '?1') $this->sec_ch_ua_mobile = true;
            else if ($headers['SEC_CH_UA_MOBILE'] === '?0') $this->sec_ch_ua_mobile = false;
            else $this->sec_ch_ua_mobile = null;
            unset($headers['SEC_CH_UA_MOBILE']);
        } else $this->sec_ch_ua_mobile = null;

        if (key_exists('SEC_CH_UA_PLATFORM', $headers)) {
            $this->sec_ch_ua_platform = new SecChUaPlatform($headers['SEC_CH_UA_PLATFORM']);
            unset($headers['SEC_CH_UA_PLATFORM']);
        } else $this->sec_ch_ua_platform = null;

        if (key_exists('DEVICE_MEMORY', $headers)) {
            $this->device_memory = (float) $headers['DEVICE_MEMORY'];
            unset($headers['DEVICE_MEMORY']);
        } else $this->device_memory = null;

        if (key_exists('DOWNLINK', $headers)) {
            $this->downlink = (float) $headers['DOWNLINK'];
            unset($headers['DOWNLINK']);
        } else $this->downlink = null;

        if (key_exists('ECT', $headers)) {
            $this->ect = new ECT($headers['ECT']);
            unset($headers['ECT']);
        } else $this->ect = null;

        if (key_exists('RTT', $headers)) {
            $this->rtt = (int) $headers['RTT'];
            unset($headers['RTT']);
        } else $this->rtt = null;

        if (key_exists('SAVE_DATA', $headers)) {
            if (strtolower($headers['SAVE_DATA']) === 'on') $this->save_data = true;
            else if (strtolower($headers['SAVE_DATA']) === 'off') $this->save_data = false;
            else $this->save_data = null;
            unset($headers['SAVE_DATA']);
        } else $this->save_data = null;

        if (key_exists('LAST_MODIFIED', $headers)) {
            $this->last_modified = new LastModified($headers['LAST_MODIFIED']);
            unset($headers['LAST_MODIFIED']);
        } else $this->last_modified = null;

        if (key_exists('IF_MATCH', $headers)) {
            $this->if_match = new IfMatch($headers['IF_MATCH']);
            unset($headers['IF_MATCH']);
        } else $this->if_match = null;

        if (key_exists('IF_NONE_MATCH', $headers)) {
            $this->if_none_match = new IfNoneMatch($headers['IF_NONE_MATCH']);
            unset($headers['IF_NONE_MATCH']);
        } else $this->if_none_match = null;

        if (key_exists('IF_MODIFIED_SINCE', $headers)) {
            $this->if_modified_since = new IfModifiedSince($headers['IF_MODIFIED_SINCE']);
            unset($headers['IF_MODIFIED_SINCE']);
        } else $this->if_modified_since = null;

        if (key_exists('IF_UNMODIFIED_SINCE', $headers)) {
            $this->if_unmodified_since = new IfUnModifiedSince($headers['IF_UNMODIFIED_SINCE']);
            unset($headers['IF_UNMODIFIED_SINCE']);
        } else $this->if_unmodified_since = null;

        if (key_exists('CONNECTION', $headers)) {
            $this->connection = new Connection($headers['CONNECTION']);
            unset($headers['CONNECTION']);
        } else $this->connection = null;

        if (key_exists('KEEP_ALIVE', $headers)) {
            $this->keep_alive = new KeepAlive($headers['KEEP_ALIVE']);
            unset($headers['KEEP_ALIVE']);
        } else $this->keep_alive = null;

        if (key_exists('ACCEPT', $headers)) {
            $this->accept = new Accept($headers['ACCEPT']);
            unset($headers['ACCEPT']);
        } else $this->accept = null;

        if (key_exists('ACCEPT_ENCODING', $headers)) {
            $this->accept_encoding = new AcceptEncoding($headers['ACCEPT_ENCODING']);
            unset($headers['ACCEPT_ENCODING']);
        } else $this->accept_encoding = null;

        if (key_exists('ACCEPT_LANGUAGE', $headers)) {
            $this->accept_language = new AcceptLanguage($headers['ACCEPT_LANGUAGE']);
            unset($headers['ACCEPT_LANGUAGE']);
        } else $this->accept_language = null;

        if (key_exists('EXPECT', $headers)) {
            $this->expect = new Expect($headers['EXPECT']);
            unset($headers['EXPECT']);
        } else $this->expect = null;

        if (key_exists('MAX_FORWARDS', $headers)) {
            $this->max_forwards = (int) $headers['MAX_FORWARDS'];
            unset($headers['MAX_FORWARDS']);
        } else $this->max_forwards = null;

        if (key_exists('ACCESS_CONTROL_REQUEST_HEADERS', $headers)) {
            $this->access_control_request_headers = new AccessControlRequestHeaders($headers['ACCESS_CONTROL_REQUEST_HEADERS']);
            unset($headers['ACCESS_CONTROL_REQUEST_HEADERS']);
        } else $this->access_control_request_headers = null;

        if (key_exists('CONTENT_DISPOSITION', $headers)) {
            $this->content_disposition = new ContentDisposition($headers['CONTENT_DISPOSITION']);
            unset($headers['CONTENT_DISPOSITION']);
        } else $this->content_disposition = null;

        if (key_exists('CONTENT_LENGTH', $headers)) {
            $this->content_length = (int) $headers['CONTENT_LENGTH'];
            unset($headers['CONTENT_LENGTH']);
        } else $this->content_length = null;

        if (key_exists('CONTENT_TYPE', $headers)) {
            $this->content_type = new ContentType($headers['CONTENT_TYPE']);
            unset($headers['CONTENT_TYPE']);
        } else $this->content_type = null;

        if (key_exists('CONTENT_ENCODING', $headers)) {
            $this->content_encoding = new ContentEncoding($headers['CONTENT_ENCODING']);
            unset($headers['CONTENT_ENCODING']);
        } else $this->content_encoding = null;

        if (key_exists('CONTENT_LANGUAGE', $headers)) {
            $this->content_language = new ContentLanguage($headers['CONTENT_LANGUAGE']);
            unset($headers['CONTENT_LANGUAGE']);
        } else $this->content_language = null;

        if (key_exists('FORWARDED', $headers)) {
            $this->forwarded = new Forwarded($headers['FORWARDED']);
            unset($headers['FORWARDED']);
        } else $this->forwarded = null;

        if (key_exists('X_FORWARDED_FOR', $headers)) {
            $this->x_forwarded_for = new XForwardedFor($headers['X_FORWARDED_FOR']);
            unset($headers['X_FORWARDED_FOR']);
        } else $this->x_forwarded_for = null;

        if (key_exists('HOST', $headers)) {
            $this->host = new Host($headers['HOST']);
            unset($headers['HOST']);
        } else $this->host = null;

        if (key_exists('RANGE', $headers)) {
            $this->range = new Range($headers['RANGE']);
            unset($headers['RANGE']);
        } else $this->range = null;

        if (key_exists('IF_RANGE', $headers)) {
            $this->if_range = new IfRange($headers['IF_RANGE']);
            unset($headers['IF_RANGE']);
        } else $this->if_range = null;

        if (key_exists('UPGRADE_INSECURE_REQUESTS', $headers)) {
            $this->upgrade_insecure_requests = (int) $headers['UPGRADE_INSECURE_REQUESTS'];
            unset($headers['UPGRADE_INSECURE_REQUESTS']);
        } else $this->upgrade_insecure_requests = null;

        if (key_exists('SEC_FETCH_SITE', $headers)) {
            $this->sec_fetch_site = new SecFetchSite($headers['SEC_FETCH_SITE']);
            unset($headers['SEC_FETCH_SITE']);
        } else $this->sec_fetch_site = null;

        if (key_exists('SEC_FETCH_MODE', $headers)) {
            $this->sec_fetch_mode = new SecFetchMode($headers['SEC_FETCH_MODE']);
            unset($headers['SEC_FETCH_MODE']);
        } else $this->sec_fetch_mode = null;

        if (key_exists('SEC_FETCH_USER', $headers)) {
            $this->sec_fetch_user = $headers['SEC_FETCH_USER'] === "?1" ? true : null;
            unset($headers['SEC_FETCH_USER']);
        } else $this->sec_fetch_user = null;

        if (key_exists('SEC_FETCH_DEST', $headers)) {
            $this->sec_fetch_dest = new SecFetchDest($headers['SEC_FETCH_DEST']);
            unset($headers['SEC_FETCH_DEST']);
        } else $this->sec_fetch_dest = null;

        if (key_exists('TRANSFER_ENCODING', $headers)) {
            $this->transfer_encoding = new TransferEncoding($headers['TRANSFER_ENCODING']);
            unset($headers['TRANSFER_ENCODING']);
        } else $this->transfer_encoding = null;

        if (key_exists('TRAILER', $headers)) {
            $this->trailer = new Trailer($headers['TRAILER']);
            unset($headers['TRAILER']);
        } else $this->trailer = null;

        if (key_exists('TE', $headers)) {
            $this->te = new TE($headers['TE']);
            unset($headers['TE']);
        } else $this->te = null;

        if (key_exists('DATE', $headers)) {
            $this->date = new Date($headers['DATE']);
            unset($headers['DATE']);
        } else $this->date = null;

        if (key_exists('EARLY_DATA', $headers)) {
            $this->early_data = $headers['EARLY_DATA'] === '1' ? true : null;
            unset($headers['EARLY_DATA']);
        } else $this->early_data = null;

        if (key_exists('UPGRADE', $headers)) {
            $this->upgrade = new Upgrade($headers['UPGRADE']);
            unset($headers['UPGRADE']);
        } else $this->upgrade = null;

        foreach ([
            'SEC_CH_UA_ARCH',
            'SEC_CH_UA_BITNESS',
            'SEC_CH_UA_MODEL',
            'SEC_CH_UA_PLAFTFORM_VERSION',
            'COOKIE',
            'ORIGIN',
            'CONTENT_LOCATION',
            'X_FORWARDED_HOST',
            'X_FORWARDED_PROTO',
            'VIA',
            'FROM',
            'REFERER',
            'USER_AGENT',
            'SERVICE_WORKER_NAVIGATION_PRELOAD'
        ] as $header) {
            if (key_exists($header, $headers)) {
                $this->{strtolower($header)} = $headers[$header];
                unset($headers[$header]);
            } else $this->{strtolower($header)} = null;
        };

        //custom headers or not yet implemented headers
        foreach ($headers as $key => $value)
            $this->{strtolower($key)} = $value;
    }

    public function jsonSerialize(): mixed
    {
        $json = [];
        foreach (get_object_vars($this) as $prop => $value)
            if (isset($value)) {
                if ($value instanceof AbstractHeader)
                    $json[$prop] = $value->__toString();
                else $json[$prop] = $value;
            }

        return $json;
    }

    public function join(string $delimiter) {
        $_ = $this->jsonSerialize();
        return join($delimiter, array_map(fn ($k, $v) => "$k: $v", array_keys($_), array_values($_)));
    }

    public function getByName(string $header) : mixed {
        $header = strtolower($this->normalize_name($header));

        if (property_exists($this, $header))
            return $this->{$header};

        return null;
    }

    public static function normalize_name(string $header) {
        return str_replace("HTTP_", "", strtoupper(str_replace("-", "_", trim($header))));
    }

}
