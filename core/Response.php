<?php

class Response
{
	private $statusCode;
	private $data;
	private $errors;
	private $trace;

	const STATUS_OK = 200;
	const STATUS_CREATED = 201;
	const STATUS_BAD_REQUEST = 400;
	const STATUS_UNAUTHORIZED = 401;
	const STATUS_FORBIDDEN = 403;
	const STATUS_NOT_FOUND = 404;
	const STATUS_METHOD_NOT_ALLOWED_ERROR = 409;
	const STATUS_SERVER_ERROR = 500;

	public function __construct($statusCode, $data = null, $errors = null, $trace = null)
	{
		$this->statusCode = $statusCode;
		$this->data = $data;
		$this->errors = $errors;
		$this->trace = $trace;
	}

	public function send()
	{
		http_response_code($this->statusCode);
		header('Content-Type: application/json');

		if (!$this->errors) {
			$response = $this->data;
		} else {
			$response = [
				"errors" => $this->errors,
				"trace" => $this->trace,
			];
		}

		echo json_encode($response);
		exit;
	}

	public static function ok($data = null)
	{
		return new self(self::STATUS_OK, $data);
	}

	public static function created($data = null)
	{
		return new self(self::STATUS_CREATED, $data);
	}

	public static function badRequest($errors, $trace = null)
	{
		return new self(self::STATUS_BAD_REQUEST, null, $errors, $trace);
	}

	public static function unauthorized($error)
	{
		return new self(self::STATUS_UNAUTHORIZED, null, $error);
	}

	public static function forbidden($error)
	{
		return new self(self::STATUS_FORBIDDEN, null, $error);
	}

	public static function notFound($error)
	{
		return new self(self::STATUS_NOT_FOUND, null, $error);
	}

	public static function methodNotAllowed($error)
	{
		return new self(self::STATUS_METHOD_NOT_ALLOWED_ERROR, null, $error);
	}

	public static function serverError($error, $trace = null)
	{
		return new self(self::STATUS_SERVER_ERROR, null, $error, $trace);
	}
}