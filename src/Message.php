<?php
declare(strict_types=1);

namespace Snailweb\KubeMQ;

class Message implements MessageInterface
{
    private $metadata;
    private $body;

    private $expirationSeconds = 0;
    private $delaySeconds = 0;
    private $maxReceiveCount = 0;
    private $maxReceiveQueue = '';

    public function __construct($metadata, $body)
    {
        $this->metadata = $metadata;
        $this->body = $body;
    }

    public function getMetadata()
    {
        return $this->metadata;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setExpirationSeconds(int $expiration): MessageInterface
    {
        $this->expirationSeconds = $expiration;
        return $this;
    }

    public function getExpirationSeconds(): int
    {
        return $this->expirationSeconds;
    }

    public function setDelaySeconds(int $delay): MessageInterface
    {
        $this->delaySeconds = $delay;
        return $this;
    }

    public function getDelaySeconds(): int
    {
        return $this->delaySeconds;
    }

    public function setMaxReceiveCount(int $maxReceiveCount): MessageInterface
    {
        $this->maxReceiveCount = $maxReceiveCount;
        return $this;
    }

    public function getMaxReceiveCount(): int
    {
        return $this->maxReceiveCount;
    }

    public function setMaxReceiveQueue(string $maxReceiveQueue): MessageInterface
    {
        $this->maxReceiveQueue = $maxReceiveQueue;
        return $this;
    }

    public function getMaxReceiveQueue(): string
    {
        return $this->maxReceiveQueue;
    }
}