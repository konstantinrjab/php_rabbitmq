<?php

namespace App\Exception;

use Interop\Container\Exception\NotFoundException as InteropNotFoundException;
use Exception;

final class ServiceNotFoundException extends Exception implements InteropNotFoundException
{

}
