<?php

class ErrorHandler
{
    public static function handleError($exception)
    {
        http_response_code(500);

        echo json_encode([
            "message" => $exception->getMessage(),
        ]);
    }
}
