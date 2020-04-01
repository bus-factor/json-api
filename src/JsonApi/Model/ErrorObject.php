<?php

/**
 * ErrorObject.php
 *
 * @author    michael.lessnau
 * @since     28.03.2020
 */

declare(strict_types=1);

namespace JsonApi\Model;

use InvalidArgumentException;

/**
 * Class ErrorObject
 */
class ErrorObject extends AbstractObject
{
    private const STATUSES = [
        /*
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        103 => 'Early Hints',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        */
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Too Early',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    ];

    /**
     * @var string|null
     */
    private ?string $code = null;

    /**
     * @var string|null
     */
    private ?string $detail = null;

    /**
     * @var string|null
     */
    private ?string $id = null;

    /**
     * @var LinksObject|null
     */
    private ?LinksObject $links = null;

    /**
     * @var MetaObject|null
     */
    private ?MetaObject $meta = null;

    /**
     * @var ErrorSourceObject|null
     */
    private ?ErrorSourceObject $source = null;

    /**
     * @var int|null
     */
    private ?int $status = null;

    /**
     * @var string|null
     */
    private ?string $title = null;

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @return string|null
     */
    public function getDetail(): ?string
    {
        return $this->detail;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return LinksObject|null
     */
    public function getLinks(): ?LinksObject
    {
        return $this->links;
    }

    /**
     * @return MetaObject|null
     */
    public function getMeta(): ?MetaObject
    {
        return $this->meta;
    }

    /**
     * @return ErrorSourceObject|null
     */
    public function getSource(): ?ErrorSourceObject
    {
        return $this->source;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return mixed|array
     */
    public function jsonSerialize()
    {
        $object = [];

        if (isset($this->id)) {
            $object['id'] = $this->id;
        }

        if (isset($this->links)) {
            $object['links'] = $this->links->jsonSerialize();
        }

        if (isset($this->status)) {
            $object['status'] = $this->status;
        }

        if (isset($this->code)) {
            $object['code'] = $this->code;
        }

        if (isset($this->title)) {
            $object['title'] = $this->title;
        }

        if (isset($this->detail)) {
            $object['detail'] = $this->detail;
        }

        if (isset($this->source)) {
            $object['source'] = $this->source->jsonSerialize();
        }

        if (isset($this->meta)) {
            $object['meta'] = $this->meta->jsonSerialize();
        }

        return $object;
    }

    /**
     * @param string|null $code
     *
     * @return $this
     */
    private function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param string|null $detail
     *
     * @return $this
     */
    private function setDetail(?string $detail): self
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * @param string|null $id
     *
     * @return $this
     */
    private function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param LinksObject|null $links
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    private function setLinks(?LinksObject $links): self
    {
        if (isset($links) && !$links->hasLink('about')) {
            throw new InvalidArgumentException('Missing link: about');
        }

        $this->links = $links;

        return $this;
    }

    /**
     * @param MetaObject|null $meta
     *
     * @return $this
     */
    private function setMeta(?MetaObject $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * @param int|null $status
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function setStatus(?int $status): self
    {
        if (isset($status) && !isset(self::STATUSES[$status])) {
            throw new InvalidArgumentException(
                'Invalid or non-error indicating HTTP status code: ' . $status
            );
        }

        $this->status = $status;

        return $this;
    }

    /**
     * @param ErrorSourceObject|null $source
     *
     * @return $this
     */
    private function setSource(?ErrorSourceObject $source): self
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @param string|null $title
     *
     * @return $this
     */
    private function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function withCode(string $code): self
    {
        return (clone $this)
            ->setCode($code);
    }

    /**
     * @param string $detail
     *
     * @return $this
     */
    public function withDetail(string $detail): self
    {
        return (clone $this)
            ->setDetail($detail);
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function withId(string $id): self
    {
        return (clone $this)
            ->setId($id);
    }

    /**
     * @param LinksObject $links
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function withLinks(LinksObject $links): self
    {
        return (clone $this)
            ->setLinks($links);
    }

    /**
     * @param MetaObject $meta
     *
     * @return $this
     */
    public function withMeta(MetaObject $meta): self
    {
        return (clone $this)
            ->setMeta($meta);
    }

    /**
     * @param ErrorSourceObject $source
     *
     * @return $this
     */
    public function withSource(ErrorSourceObject $source): self
    {
        return (clone $this)
            ->setSource($source);
    }

    /**
     * @param int $status
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function withStatus(int $status): self
    {
        return (clone $this)
            ->setStatus($status);
    }

    /**
     * @param int $title
     *
     * @return $this
     */
    public function withTitle(string $title): self
    {
        return (clone $this)
            ->setTitle($title);
    }

    /**
     * @return $this
     */
    public function withoutCode(): self
    {
        return (clone $this)
            ->setCode(null);
    }

    /**
     * @return $this
     */
    public function withoutDetail(): self
    {
        return (clone $this)
            ->setDetail(null);
    }

    /**
     * @return $this
     */
    public function withoutId(): self
    {
        return (clone $this)
            ->setId(null);
    }

    /**
     * @return $this
     */
    public function withoutLinks(): self
    {
        return (clone $this)
            ->setLinks(null);
    }

    /**
     * @return $this
     */
    public function withoutMeta(): self
    {
        return (clone $this)
            ->setMeta(null);
    }

    /**
     * @return $this
     */
    public function withoutSource(): self
    {
        return (clone $this)
            ->setSource(null);
    }

    /**
     * @return $this
     */
    public function withoutStatus(): self
    {
        return (clone $this)
            ->setStatus(null);
    }

    /**
     * @return $this
     */
    public function withoutTitle(): self
    {
        return (clone $this)
            ->setTitle(null);
    }
}
