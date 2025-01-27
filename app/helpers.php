<?php

if(!function_exists('throwError')){
    function throwError(string $message, int $statusCode = 500, array $data = []){
        throw new App\Exceptions\CustomException($message, $statusCode, $data);
    }
}
