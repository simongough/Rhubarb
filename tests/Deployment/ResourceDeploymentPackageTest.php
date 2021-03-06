<?php

namespace Rhubarb\Crown\Tests\Deployment;

use Rhubarb\Crown\Deployment\ResourceDeploymentPackage;
use Rhubarb\Crown\Tests\RhubarbTestCase;

class ResourceDeploymentPackageTest extends RhubarbTestCase
{
    public function testAllFilesDeployed()
    {
        $package = new ResourceDeploymentPackage();
        $package->resourcesToDeploy = [__FILE__, __DIR__ . "/../../src/Deployment/Deployable.php"];
        $package->deploy();

        $cwd = getcwd();

        $this->assertFileExists("deployed/" . str_replace($cwd, "", __FILE__));
        $this->assertFileExists("deployed/" . str_replace($cwd, "", __DIR__ . "/../../src/Deployment/Deployable.php"));

        unlink("deployed/" . str_replace($cwd, "", __FILE__));
        unlink("deployed/" . str_replace($cwd, "", __DIR__ . "/../../src/Deployment/Deployable.php"));
    }

    public function testDeploymentUrls()
    {
        $package = new ResourceDeploymentPackage();
        $package->resourcesToDeploy = [__FILE__, __DIR__ . "/../../src/Deployment/Deployable.php"];
        $urls = $package->getDeployedUrls();

        $cwd = getcwd();

        $this->assertEquals(
            [
                "/deployed" . str_replace("\\", "/", str_replace($cwd, "", __FILE__)),
                "/deployed" . str_replace("\\", "/",
                    str_replace($cwd, "", realpath(__DIR__ . "/../../src/Deployment/Deployable.php")))
            ], $urls);
    }
}
