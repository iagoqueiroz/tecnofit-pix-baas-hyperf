<?php

declare(strict_types=1);

use App\Exception\Handler\AppExceptionHandler;
use App\Exception\Handler\PaymentExceptionHandler;
use App\Exception\Handler\ValidationExceptionHandler;
use Hyperf\HttpServer\Exception\Handler\HttpExceptionHandler;

return [
    'handler' => [
        'http' => [
            HttpExceptionHandler::class,
            ValidationExceptionHandler::class,
            AppExceptionHandler::class,
            PaymentExceptionHandler::class,
        ],
    ],
];
