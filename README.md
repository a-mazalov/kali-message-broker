# Kali message broker

<a href="https://packagist.org/packages/a-mazalov/kali-message-broker"><img src="https://img.shields.io/packagist/v/a-mazalov/kali-message-broker" alt="Latest Stable Version"></a>

Messages between services using the rabbitmq broker

## Запуск в dev container VScode
1. Open project
2. F1 -> Dev Containers: Rebuild and Reopen in Container

## Запуск тестов
```sh
composer test
```

## Публикация сообщений в RabbitMQ

1. Необходимо указать подключение к RabbitMQ AMQP

Где ```AMQP_EXCHANGE_NAME``` имя общего обменника

```properties
AMQP_HOST=rabbitmq
AMQP_PORT=5672
AMQP_USER=root
AMQP_PASSWORD=root
AMQP_VHOST=/
AMQP_QUEUE_NAME=
AMQP_EXCHANGE_NAME=incoming_web
```

2. Отправка через Notification

Реализовать класс уведомления с каналом ```RabbitmqChannel``` или воспользоваться существующим классом ```RabbitmqMessageNotification```

```php
    use Kali\MessageBroker\Messages\Data\Test;
    use Kali\MessageBroker\Notifications\RabbitmqMessageNotification;

    $testData = new Test(email: "djoni@google.com", message: "Hello World!");
    $routing_key = "access" // очередь сервиса в который необходимо отправить сообщение. Роутинг настраивается через Exchange в админке RabbitMQ 

    Notification::route("rabbitmq", $routing_key)->notify(
        new RabbitmqMessageNotification("TestJob", $testData->toResource())
    );
```



## Получение сообщений из RabbitMQ

Для получения сообщений из Rabbitmq необходимо подключение и обработчик очереди ```php artisan rabbitmq:consume```

1. Требуется дополнительная установка пакета [laravel-queue-rabbitmq](https://github.com/vyuldashev/laravel-queue-rabbitmq)

2. Необходимо указать подключение к rabbitmq и прослушиваемую очередь ```RABBITMQ_QUEUE```

```properties
RABBITMQ_HOST=rabbitmq
RABBITMQ_PORT=5672
RABBITMQ_USER=root
RABBITMQ_PASSWORD=root
RABBITMQ_VHOST=/
RABBITMQ_QUEUE=access_queue
```

3. Добавить конфигурацию в ```config/queue.php``` (актуальная конфигурация в документации пакета)

```php
        'rabbitmq_consumer' => [
            'driver' => 'rabbitmq',
            'queue' => env('RABBITMQ_QUEUE', 'default'),
            'connection' => PhpAmqpLib\Connection\AMQPLazyConnection::class,

            'hosts' => [
                [
                    'host' => env('RABBITMQ_HOST', '127.0.0.1'),
                    'port' => env('RABBITMQ_PORT', 5672),
                    'user' => env('RABBITMQ_USER', 'guest'),
                    'password' => env('RABBITMQ_PASSWORD', 'guest'),
                    'vhost' => env('RABBITMQ_VHOST', '/'),
                ],
            ],

            'options' => [
                'ssl_options' => [
                    'cafile' => env('RABBITMQ_SSL_CAFILE', null),
                    'local_cert' => env('RABBITMQ_SSL_LOCALCERT', null),
                    'local_key' => env('RABBITMQ_SSL_LOCALKEY', null),
                    'verify_peer' => env('RABBITMQ_SSL_VERIFY_PEER', true),
                    'passphrase' => env('RABBITMQ_SSL_PASSPHRASE', null),
                ],
                'queue' => [
                    'job' => \Kali\MessageBroker\Worker\ConsumeRabbitMQ::class,
                ],
            ],

            /*
             * Set to "horizon" if you wish to use Laravel Horizon.
             */
            'worker' => env('RABBITMQ_WORKER', 'default'),
        ],
```

4. Реализовать класс обработчика сообщений и указать его в конфигурации

```php
    'queue' => [
        'job' => \Kali\MessageBroker\Worker\ConsumeRabbitMQ::class,
    ],
```

5. Выполнить ```php artisan vendor:publish``` и выбрать ```Kali\MessageBroker\Providers\MsgServiceProvider```

6. Заполнить ```config/message.php``` для соотвествия имени входящего сообщения и выполнения требуемой задачи

7. Запустить обработчки ```php artisan rabbitmq:consume rabbitmq_consumer```

> rabbitmq_consumer - имя подключение в ```config/queue.php```