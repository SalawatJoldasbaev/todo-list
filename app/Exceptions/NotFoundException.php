<?php

namespace App\Exceptions;

use App\Traits\JsonRespondController;
use Exception;
use Illuminate\Http\JsonResponse;

class NotFoundException extends Exception
{
    use JsonRespondController;

    public function render(): JsonResponse
    {
        $this->setHTTPStatusCode(404);
        return $this->respondWithError($this->getMessage());
    }
}
