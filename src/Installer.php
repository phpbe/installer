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
                'installRootDir' => 'app',
                'stripPrefix' => 'app-',
                'formatName' => true
            ),
            'phpbe-ui' => array(
                'installRootDir' => 'ui',
                'stripPrefix' => 'ui-',
                'formatName' => true
            ),
            'phpbe-theme' => array(
                'installRootDir' => 'theme',
                'stripPrefix' => 'theme-',
                'formatName' => false
            )
        );

        $packageTypeConfig = $packageTypeConfigs[$packageType];

        $installDirName = null;
        $extra = $package->getExtra();

        // 可以 extra 中指定安装的目录名
        if (!empty($extra['install-dir-name'])) {
            $installDirName = $extra['install-dir-name'];
        } else {
            $prettyName = strtolower($package->getPrettyName());
            if (strpos($prettyName, '/') !== false) {
                list($vendor, $installDirName) = explode('/', $prettyName);
            } else {
                $vendor = '';
                $installDirName = $prettyName;
            }

            if (strpos($installDirName, $packageTypeConfig['stripPrefix']) === 0) {
                $installDirName = substr($installDirName, strlen($packageTypeConfig['stripPrefix']));
            }

            if ($packageTypeConfig['formatName']) {
                $installDirName = strtolower(str_replace(array('-', '_'), ' ', $installDirName));
                $installDirName = str_replace(' ', '', ucwords($installDirName));
            }
        }

        return $packageTypeConfig['installRootDir'] . '/' . $installDirName;
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

