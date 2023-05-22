<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;

trait JsonRespondController
{
    /**
     * @var int
     */
    protected int $httpStatusCode = 200;

    /**
     * @var int
     */
    protected int $errorCode;

    /**
     * Get HTTP status code of the response.
     *
     * @return int
     */
    public function getHTTPStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    /**
     * Set HTTP status code of the response.
     *
     * @param int $statusCode
     * @return self
     */
    public function setHTTPStatusCode(int $statusCode): static
    {
        $this->httpStatusCode = $statusCode;

        return $this;
    }

    /**
     * Get error code of the response.
     *
     * @return int
     */
    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    /**
     * Set error code of the response.
     *
     * @param int $errorCode
     * @return self
     */
    public function setErrorCode(int $errorCode): static
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * Sends a JSON to the consumer.
     *
     * @param array $data
     * @param array $headers [description]
     * @return JsonResponse
     */
    public function respond(array $data, array $headers = []): JsonResponse
    {
        return response()->json($data, $this->getHTTPStatusCode(), $headers);
    }

    /**
     * Sends a response not found (404) to the request.
     * Error Code = 31.
     *
     * @return JsonResponse
     */
    public function respondNotFound(): JsonResponse
    {
        return $this->setHTTPStatusCode(404)
            ->setErrorCode(31)
            ->respondWithError();
    }

    /**
     * Sends an error when the validator failed.
     * Error Code = 32.
     *
     * @param Validator $validator
     * @return JsonResponse
     */
    public function respondValidatorFailed(Validator $validator): JsonResponse
    {
        return $this->setHTTPStatusCode(422)
            ->setErrorCode(32)
            ->respondWithError($validator->errors()->first(), $validator->errors()->all());
    }

    public function respondValidatorMessage($message): JsonResponse
    {
        return $this->setHTTPStatusCode(422)
            ->setErrorCode(32)
            ->respondWithError([$message]);
    }

    /**
     * Sends an error when the query didn't have the right parameters for
     * creating an object.
     * Error Code = 33.
     *
     * @param string|null $message
     * @return JsonResponse
     */
    public function respondNotTheRightParameters(string $message = null): JsonResponse
    {
        return $this->setHTTPStatusCode(500)
            ->setErrorCode(33)
            ->respondWithError($message);
    }

    /**
     * Sends a response invalid query (http 500) to the request.
     * Error Code = 40.
     *
     * @param string|null $message
     * @return JsonResponse
     */
    public function respondInvalidQuery(string $message = null): JsonResponse
    {
        return $this->setHTTPStatusCode(500)
            ->setErrorCode(40)
            ->respondWithError($message);
    }

    /**
     * Sends an error when the query contains invalid parameters.
     * Error Code = 41.
     *
     * @param string|null $message
     * @return JsonResponse
     */
    public function respondInvalidParameters(string $message = null): JsonResponse
    {
        return $this->setHTTPStatusCode(422)
            ->setErrorCode(41)
            ->respondWithError($message);
    }

    /**
     * Sends a response unauthorized (401) to the request.
     * Error Code = 42.
     *
     * @param string|null $message
     * @return JsonResponse
     */
    public function respondUnauthorized(string $message = null): JsonResponse
    {
        return $this->setHTTPStatusCode(401)
            ->setErrorCode(42)
            ->respondWithError($message);
    }

    /**
     * Sends a response with error.
     *
     * @param array|string|null $message
     * @param array $errors
     * @return JsonResponse
     */
    public function respondWithError(array|string $message = null, array $errors = []): JsonResponse
    {
        return $this->respond([
            'success' => false,
            'code' => $this->getHTTPStatusCode(),
            'message' => $message ?? config('api.error_codes.' . $this->getErrorCode()),
            'payload' => $errors
        ]);
    }

    /**
     * Sends a response that the object has been deleted, and also indicates
     * the id of the object that has been deleted.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function respondObjectDeleted(int $id): JsonResponse
    {
        return $this->respond([
            'data' => [
                'deleted' => true,
                'id' => $id,
            ]
        ]);
    }

    public function respondError($message = '', $errors = [], $code = 422): JsonResponse
    {
        $this->setHTTPStatusCode($code);
        return $this->respond([
            'success' => false,
            'code' => $code,
            'message' => $message,
            'payload' => $errors
        ]);
    }

    public function respondSuccess($message = 'success', $payload = [], $code = 200): JsonResponse
    {
        $this->setHTTPStatusCode($code);
        return $this->respond([
            'success' => true,
            'code' => $code,
            'message' => $message,
            'payload' => $payload
        ]);
    }
}
