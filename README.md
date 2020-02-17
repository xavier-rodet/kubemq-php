# kubemq-php
PHP Client for KubeMQ using [REST API](https://docs.kubemq.io/development/rest.html)

## Installation
composer require snailweb/kubemq

## Requirements

* PHP 7.1+
* CURL

## Features

* Queue
    * ~~peak~~
    * send
    * ~~sendBatch~~
    * receive
    * ~~ackAllMessages~~
* ~~Pub/Sub~~
* ~~RPC~~


## Queue

### Send a message

```php
$queue = new \Snailweb\KubeMQ\Queue('kubemq-host', 9090, 'ClientA', 'Channel');

$message = new \Snailweb\KubeMQ\Message(
    'metadata', // metadata (could be anything: int, string, array, object ...)
    ['field1' => 4, 'field2' => 'info'] // body (could be anything: int, string, array, object ...)
);
// Optional settings here
$message->setExpirationSeconds(5)
->setDelaySeconds(5)
->setMaxReceiveCount(10)
->setMaxReceiveQueue('queue-name');

try {
    $queue->send($message);
} catch(Exception $exception) {
    var_dump($exception);
}
```

### Receive a message

```php
$queue = new \Snailweb\KubeMQ\Queue('kubemq-host', 9090, 'ClientB', 'Channel', 
                                32, // optional default max receive msg for this client 
                                1); // optional default waiting time for this client

try {
    $messages = $queue->receive(32, 1); // optional: we can override defaults of this client
    if(!empty($messages)) {
        var_dump($messages);
    } else { echo "no messages\n"; }
} catch(Exception $exception) {
    var_dump($exception);
}
```