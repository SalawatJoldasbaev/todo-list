<?php

namespace App\Exceptions;

use App\Traits\JsonRespondController;
use Exception;
use Illuminate\Http\JsonResponse;

class AlreadyExistsException extends Exception
{
    use JsonRespondController;

    public function render(): JsonResponse
    {
        $this->setHTTPStatusCode(409);
        return $this->respondWithError($this->getMessage());
    }
}
