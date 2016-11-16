<?php

class ValidationException extends Exception {
	private $_errors;

    public function __construct($errors, $message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    	$this->_errors = $errors;
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function getErrors() {
    	return $this->_errors;
    }
}