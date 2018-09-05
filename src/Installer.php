<?php

namespace Phpbe\Installer;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class Installer extends LibraryInstaller
{

    public function getInstallPath(PackageInterface $package)
    {
        $packageType = strtolower($package->getType());

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

        $appName = null;
        $extra = $package->getExtra();

        // 可以 extra 中指定 APP 名称
        if (!empty($extra['app-name'])) {
            $appName = $extra['app-name'];
        } else {
            $prettyName = strtolower($package->getPrettyName());
            if (strpos($prettyName, '/') !== false) {
                list($vendor, $appName) = explode('/', $prettyName);
            } else {
                $vendor = '';
                $appName = $prettyName;
            }

            if (strpos($appName, $packageTypeConfig['stripPrefix']) === 0) {
                $appName = substr($appName, strlen($packageTypeConfig['stripPrefix']));
            }

            if ($packageTypeConfig['formatAppName']) {
                $appName = strtolower(str_replace(array('-', '_'), ' ', $appName));
                $appName = str_replace(' ', '', ucwords($appName));
            }
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

