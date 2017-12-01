# PHP Scoop.it Client
PHP API client for the [Scoop.it API](https://www.scoop.it/dev/api/1/intro).

To use this software an API key is required which can be requested at : https://scoop.it.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
$ php composer.phar require humanized/php-scoopit-client "dev-master"
```

or add

```
"humanized/php-scoopit-client": "dev-master"
```

to the ```require``` section of your `composer.json` file.

## Getting Started

```
//Initialise the client with valid scoop.it account credentials 

$config= [ 'base_uri'=>'your-scoopit-account',
           'consumer_key'=>'your-consumer-key',
           'consumer_secret'=>'your-consumer-secret',
           'token'=>'your-token',
           'token_secret'=>'your-consumer-key',
           'consumer_key'=>'your-consumer-key',
         ];
$client=new ScoopitClient($config);

//And away we go!

$response = $client->test();
var_dump($response);
```

