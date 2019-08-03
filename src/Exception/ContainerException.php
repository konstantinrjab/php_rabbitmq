<?php

namespace App\Exception;

use Interop\Container\Exception\ContainerException as InteropContainerException;
use Exception;

final class ContainerException extends Exception implements InteropContainerException
{

}
