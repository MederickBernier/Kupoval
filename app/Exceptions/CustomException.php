<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class CustomException extends Exception
{
    protected $statusCode;
    protected $data;

    public function __construct(string $message = 'An error occured', int $statusCode = 500, array $data = []){
        parent::__construct($message);
        $this->statusCode = $statusCode;
        $this->data = $data;

        $this->logError();
    }

    private function logError(){
        Log::channel('custom_exceptions')->error('CustomException occured',[
            'message' => $this->getMessage(),
            'status_code' => $this->getStatusCode(),
            'data' => $this->getData(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'trace' => $this->getTraceAsString(),
        ]);
    }

    public function getStatusCode(){
        return $this->statusCode;
    }

    public function getData(){
        return $this->data;
    }
}
