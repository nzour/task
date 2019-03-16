<?php
/**
 * Created by PhpStorm.
 * User: zaurn
 * Date: 06.03.2019
 * Time: 20:47
 */

namespace App\Exceptions\MyExceptions;


use Throwable;

class NotFoundReportByIdException extends \Exception
{
    protected $message;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }


}