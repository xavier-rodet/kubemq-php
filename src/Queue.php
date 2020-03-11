<?php
declare(strict_types=1);

namespace Snailweb\KubeMQ;

class Queue implements QueueInterface
{
    const REQUEST_SEND = 'send';
    const REQUEST_RECEIVE = 'receive';

    private $host;
    private $port;
    private $client;
    private $channel;

    private $maxNumberOfMessages;
    private $waitTimeSeconds;

    public function __construct(string $host, int $port, string $client, string $channel, ?int $maxNumberOfMessages = 32, ?int $waitTimeSeconds = 1)
    {
        $this->host = $host;
        $this->port = $port;
        $this->client = $client;
        $this->channel = $channel;
        $this->maxNumberOfMessages = $maxNumberOfMessages;
        $this->waitTimeSeconds = $waitTimeSeconds;
    }

    public function send(MessageInterface $message)
    {
        $options = array(
        //    "Id"  => "",
            "ClientId" => $this->client,
            "Channel" => $this->channel,
            "Metadata" => $this->encode($message->getMetadata()),
            "Body" => $this->encode($message->getBody()),
            "Policy"  => [
                "ExpirationSeconds" => $message->getExpirationSeconds(),
                "DelaySeconds" => $message->getDelaySeconds(),
                "MaxReceiveCount" => $message->getMaxReceiveCount(),
                "MaxReceiveQueue" => $message->getMaxReceiveQueue()
            ]
        //    "Tags" => ['tag1']
        //    "Attributes" =>
        );

        $this->request(self::REQUEST_SEND, $options);
    }

    public function receive(?int $maxNumberOfMessages = null, ?int $waitTimeSeconds = null): array
    {
        $maxNumberOfMessages    = isset($maxNumberOfMessages) ? $maxNumberOfMessages : $this->maxNumberOfMessages;
        $waitTimeSeconds        = isset($waitTimeSeconds) ? $waitTimeSeconds : $this->waitTimeSeconds;

        $options = array(
            //    "RequestID" => "some-request-id",
            "ClientId" => $this->client,
            "Channel" => $this->channel,
            "MaxNumberOfMessages" => $maxNumberOfMessages,
            "WaitTimeSeconds" => $waitTimeSeconds,
            "IsPeak" => false
        );

        $response = $this->request(self::REQUEST_RECEIVE, $options);
        $messages = [];
        if(isset($response->data->Messages)) {
            foreach($response->data->Messages as $message) {
                $messages[] = new Message($this->decode($message->Metadata), $this->decode($message->Body));
            }
        }

        return $messages;
    }

    private function encode($data) {
        return base64_encode(serialize($data));
    }

    private function decode($data) {
        return unserialize(base64_decode($data));
    }

    private function request(string $type, array $options) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://$this->host:$this->port/queue/$type",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($options),
            CURLOPT_HTTPHEADER => array("Content-Type: application/json"),
        ));

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if($error) throw new \Exception($error);

        return json_decode($response);
    }
}
