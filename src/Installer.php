<?php

namespace Phpbe\Installer;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class Installer extends LibraryInstaller
{

    public function getInstallPath(PackageInterface $package)
    {
        $packageType = strtolower($package->getType());

        $vendor = null;
        $appName = null;

        $prettyName = strtolower($package->getPrettyName());
        if (strpos($prettyName, '/') !== false) {
            list($vendor, $appName) = explode('/', $prettyName);
        } else {
            $vendor = '';
            $appName = $prettyName;
        }

        $packageTypeConfigs = array(
            'phpbe-app' => array(
                'installDir' => 'app',
                'stripPrefix' => 'app-',
                'formatAppName' => true
            ),
            'phpbe-ui' => array(
                'installDir' => 'ui',
                'stripPrefix' => 'ui-',
                'formatAppName' => true
            ),
            'phpbe-theme' => array(
                'installDir' => 'theme',
                'stripPrefix' => 'theme-',
                'formatAppName' => false
            )
        );

        $packageTypeConfig = $packageTypeConfigs[$packageType];

        if (strpos($appName, $packageTypeConfig['stripPrefix']) !== 0) {
            $appName = substr($appName, strlen($packageTypeConfig['stripPrefix']));
        }

        if ($packageTypeConfig['formatAppName']) {
            $appName = strtolower(str_replace(array('-', '_'), ' ', $appName));
            $appName = str_replace(' ', '', ucwords($appName));
        }

        return $packageTypeConfig['installDir'] . '/' . $appName;
    }


    public function supports($packageType)
    {
        return in_array($packageType, array(
            'phpbe-app',
            'phpbe-ui',
            'phpbe-theme'
        ));
    }

}

