<?php

namespace ExportComments;

class ExportCommentsException extends \Exception
{

    public function __construct($message = null, $code = 0, ?\Exception $previous = null)
    {
        parent::__construct('Error: ' . $message, $code, $previous);
    }
}
