<?php

/*
 *	Copyright 2015 RhubarbPHP
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

namespace Rhubarb\Crown\LoginProviders\Exceptions;

use Exception;
use Rhubarb\Crown\Exceptions\RhubarbException;

/**
 *
 * @author acuthbert
 * @copyright GCD Technologies 2013
 */
class NotLoggedInException extends RhubarbException
{
    public function __construct($privateMessage = "", \Exception $previous = null)
    {
        parent::__construct($privateMessage, $previous);

        $this->publicMessage = "Sorry, you must be logged in to complete this action.";
    }

}
