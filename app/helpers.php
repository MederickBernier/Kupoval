<?php

if(!function_exists('throwError')){
    function throwError(string $message, int $statusCode = 500, array $data = []){
        throw new App\Exceptions\CustomException($message, $statusCode, $data);
    }
}

if(!function_exists('isAllowed')){
    function isAllowed($user){
        if(!$user || $user->role !== 'admin'){
            abort(403, 'Unauthorized access');
        }
        return true;
    }
}
