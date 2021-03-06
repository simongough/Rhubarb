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

namespace Rhubarb\Crown\LoginProviders;

require_once __DIR__ . "/../Sessions/Session.php";

use Rhubarb\Crown\Exceptions\ImplementationException;
use Rhubarb\Crown\Sessions\Session;

/**
 * The base class for all LoginProviders.
 *
 * Login providers provide the framework for authenticating users and storing that logged in status.
 */
abstract class LoginProvider extends Session
{
    /**
     * Stores the name of the default login provider class
     *
     * @var string
     */
    private static $defaultLoginProviderClassName = "";

    /**
     * Returns True if the user is logged in.
     */
    public function isLoggedIn()
    {
        return (isset($this->LoggedIn) && ($this->LoggedIn));
    }

    /**
     * Logs the user out.
     */
    public function logOut()
    {
        $this->LoggedIn = false;

        $this->onLogOut();

        $this->storeSession();
    }

    /**
     * Forcibly sets the logged in state to true.
     *
     * Used in unit testing and on occasions where you are using non standard ways to
     * validate identity.
     */
    public function forceLogin()
    {
        $this->LoggedIn = true;
        $this->storeSession();
    }

    /**
     * Called when the user has logged out.
     *
     * Used by extending classes to unset session data that should be removed.
     *
     */
    protected function onLogOut()
    {

    }

    /**
     * Returns the default login provider, if one is configured
     *
     * @throws \Rhubarb\Crown\Exceptions\ImplementationException Thrown if no default is configured
     * @see setDefaultLoginProviderClassName()
     * @return LoginProvider
     */
    public static function getDefaultLoginProvider()
    {
        if (self::$defaultLoginProviderClassName == "") {
            throw new ImplementationException("There is no default login provider class name");
        }

        $providerClass = self::$defaultLoginProviderClassName;
        $provider = new $providerClass();

        return $provider;
    }

    public static function getDefaultLoginProviderClassName()
    {
        return self::$defaultLoginProviderClassName;
    }

    public static function setDefaultLoginProviderClassName($className)
    {
        self::$defaultLoginProviderClassName = $className;
    }
}
