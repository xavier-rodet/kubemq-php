<?php
declare(strict_types=1);

namespace Snailweb\KubeMQ;

interface MessageInterface
{
    public function getMetadata();
    public function getBody();

    public function setExpirationSeconds(int $expiration) : MessageInterface;
    public function getExpirationSeconds() : int;

    public function setDelaySeconds(int $delay) : MessageInterface;
    public function getDelaySeconds(): int;

    public function setMaxReceiveCount(int $maxReceiveCount) : MessageInterface;
    public function getMaxReceiveCount() : int;

    public function setMaxReceiveQueue(string $maxReceiveQueue) : MessageInterface;
    public function getMaxReceiveQueue() : string;
}