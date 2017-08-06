<?php

namespace haringsrob\Icecat\Exceptions;

class InvalidDataSheetException extends \Exception
{

    /**
     * InvalidDataSheetException constructor.
     *
     * @param string $message
     * @param string $serverError
     * @param string $serverCode
     */
    public function __construct($message, $serverError, $serverCode)
    {
        $message = $this->buildErrorMessage($message, $serverError, $serverCode);
        parent::__construct($message);
    }

    /**
     * @param string $message
     * @param string $serverError
     * @param string $serverCode
     *
     * @return string
     */
    private function buildErrorMessage($message, $serverError, $serverCode)
    {
        return $message . ' Code: ' . $serverCode . ' Message:' . $serverError;
    }

}