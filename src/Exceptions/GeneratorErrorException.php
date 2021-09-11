<?php

namespace Laraneat\Core\Exceptions;

use App\Ship\Abstracts\Exceptions\Exception;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class GeneratorErrorException extends Exception
{
    protected $code = SymfonyResponse::HTTP_BAD_REQUEST;
    protected $message = 'Generator Error.';
}
