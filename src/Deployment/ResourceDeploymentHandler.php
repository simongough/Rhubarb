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

namespace Rhubarb\Crown\Deployment;

use Rhubarb\Crown\Exceptions\DeploymentException;

abstract class ResourceDeploymentHandler
{
    private static $resourceDeploymentHandler = null;

    private static $resourceDeploymentHandlerClassName = "Rhubarb\Crown\Deployment\RelocationResourceDeploymentHandler";

    public static function setResourceDeploymentHandlerClassName($resourceDeploymentHandlerClassName)
    {
        self::$resourceDeploymentHandlerClassName = $resourceDeploymentHandlerClassName;
        self::$resourceDeploymentHandler = null;
    }

    public static function getResourceDeploymentHandler()
    {
        if (self::$resourceDeploymentHandler == null) {
            $class = self::$resourceDeploymentHandlerClassName;

            if (!class_exists($class)) {
                throw new DeploymentException("The resource deployment handler class " . $class . " could not be found");
            }

            $handler = new $class();

            if (!($handler instanceof ResourceDeploymentHandler)) {
                throw new DeploymentException("The resource deployment handler class " . $class . " is not a ResourceDeploymentHandler.");
            }

            self::$resourceDeploymentHandler = $handler;
        }

        return self::$resourceDeploymentHandler;
    }


    public function getDeployedResourceUrl($resourceFilePath)
    {
        return "";
    }

    public function deployResource($resourceFilePath)
    {
        return "";
    }

    /**
     * Deploys a string of content by creating a deployment file using the simulatedFilePath
     *
     * @param $resourceContent
     * @param $simulatedFilePath
     */
    public function deployResourceContent($resourceContent, $simulatedFilePath)
    {

    }
}