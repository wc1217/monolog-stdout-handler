Monolog Stdout Handler
======================

Provides a handler for [Monolog][1] that sends colored messages to stdout.
Messages may be uncolored with a provided formatter.

Example
-------
How to use the stdout handler:
```php
<?php
use Monolog\Logger;
use Monolog\Handler\StdoutHandler;

$stdoutHandler = new StdoutHandler();
$logger = new Logger('cronjob');
$logger->pushHandler($stdoutHandler);

$logger->error('Hello world!');
```

in hyperf:
`config/autoload/logger.php`
```php
<?php
return [
    'default' => [
        'handlers' => [
            [
                'class' => Monolog\Handler\StdoutHandler::class,
                'constructor' => [
                    'level' => Monolog\Logger::DEBUG,
                ],
                'formatter' => [
                    'class' => Monolog\Formatter\ColorLineFormatter::class,
                    'constructor' => [
                        'format' => null,
                        'dateFormat' => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => false,
                        'ignoreEmptyContextAndExtra' => true,
                    ],
                ],
            ], [
                'class' => Monolog\Handler\StreamHandler::class,
                'constructor' => [
                    'stream' => APP_PATH . '/runtime/logs/hyperf.log',
                    'level' => Monolog\Logger::DEBUG,
                ],
                'formatter' => [
                    'class' => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [
                        'format' => null,
                        'dateFormat' => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ]
        ]
    ],
];
```


  [1]: https://github.com/Seldaek/monolog
