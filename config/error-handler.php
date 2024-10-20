<?php

function errorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        return;
    }

    http_response_code(500);
	header('Content-Type: application/json');

    $message = "Грешка [$errno]: $errstr в $errfile на ред $errline";
    echo json_encode(["status" => "reject", "message" => $message]);
    exit;
}

function exceptionHandler($exception) {
    http_response_code(500);
	header('Content-Type: application/json');

    $message = "Неочаквано изключение: " . $exception->getMessage();
    echo json_encode(["status" => "reject", "message" => $message]);
    exit;
}

set_error_handler("errorHandler");
set_exception_handler("exceptionHandler");