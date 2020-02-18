<?php
declare(strict_types=1);

namespace Snailweb\KubeMQ;

interface QueueInterface
{
    public function send(MessageInterface $message);
    public function receive(?int $maxNumberOfMessages = null, ?int $waitTimeSeconds = null) : array; // []Message

    // TODO: https://docs.kubemq.io/development/rest.html#queue
//    public function sendBatch(array $messages); // []Message
//    public function peek(?int $maxNumberOfMessages, ?int $waitTimeSeconds) : array; // []Message
//    public function ackAllMessages();
}