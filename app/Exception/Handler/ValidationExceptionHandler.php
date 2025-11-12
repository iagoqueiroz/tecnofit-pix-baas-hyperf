<?php

declare(strict_types=1);

namespace App\Exception\Handler;

use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;

class ValidationExceptionHandler extends ExceptionHandler
{
    public function __construct(private HttpResponse $response) {}

    public function handle(Throwable $throwable, ResponseInterface $response): ResponseInterface
    {
        // Interrompe a propagação para que outros handlers não interceptem
        $this->stopPropagation();

        /** @var ValidationException $throwable */
        $errors = $throwable->validator->errors()->toArray();

        $data = [
            'status' => 'error',
            'message' => 'Os campos precisam ser preenchidos corretamente.',
            'errors' => $throwable->errors(),
        ];

        return $this->response
            ->json($data)
            ->withStatus(422);
    }

    public function isValid(Throwable $throwable): bool
    {
        // Só trata ValidationException
        return $throwable instanceof ValidationException;
    }
}
