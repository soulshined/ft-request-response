<?php

namespace FT\RequestResponse;

use DOMDocument;
use FT\RequestResponse\Enums\RequestBodyTypes;
use FT\RequestResponse\Headers\ContentType;

final class RequestBody {

    public array $files = [];
    public readonly mixed $content;
    public readonly RequestBodyTypes $type;

    public function __construct(public readonly string $raw, ContentType $contentType)
    {
        if ($contentType === null) {
            $this->type = RequestBodyTypes::STRING;
            $this->content = $raw;
            return;
        }

        $content_type_directive = strtolower(trim($contentType->directive));

        if ($content_type_directive === 'application/x-www-form-urlencoded') {
            parse_str(urldecode($raw), $kvps);
            $this->content = $kvps;
            $this->type = RequestBodyTypes::WWW_FORM_URLENCODED;
        }

        else if ($content_type_directive === 'multipart/form-data') {
            $this->content = json_decode(json_encode($_POST));
            $this->type = RequestBodyTypes::MULTIPART_FORMDATA;
            foreach ($_FILES as $file)
                $this->files[] = $file;
        }

        else if (str_contains($content_type_directive, 'json')) {
            $json = json_decode($raw);
            if ($json) {
                $this->content = $json;
                $this->type = RequestBodyTypes::JSON;
            }
            else {
                $this->content = $raw;
                $this->type = RequestBodyTypes::STRING;
            }
        }

        else if (str_contains($content_type_directive, 'xml')) {
            $dom = new DOMDocument();
            if (@$dom->loadXML($raw)) {
                $this->content = $dom;
                $this->type = RequestBodyTypes::XML;
            }
            else {
                $this->content = $raw;
                $this->type = RequestBodyTypes::STRING;
            }
        }

    }

    public function hasFiles() : bool {
        return !empty($files);
    }

    public function isJson() : bool {
        return $this->type === RequestBodyTypes::JSON;
    }

    public function isXML() : bool {
        return $this->type === RequestBodyTypes::XML;
    }

    public function isMultiPartFormData() : bool {
        return $this->type === RequestBodyTypes::MULTIPART_FORMDATA;
    }

    public function isWWWFormUrlencoded() : bool {
        return $this->type === RequestBodyTypes::WWW_FORM_URLENCODED;
    }

    public function isString() : bool {
        return $this->type === RequestBodyTypes::STRING;
    }

}

?>