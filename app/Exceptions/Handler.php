<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler{
    public function register():void{
        $this->reportable(function(Throwable $e){
            Log::error('Exception occured', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
        });

        $this->renderable(function(\App\Exceptions\CustomException $e, $request){
            return response()->json([
                'error' => $e->getMessage(),
                'data' => $e->getData(),
            ],$e->getStatusCode());
        });
    }
}
