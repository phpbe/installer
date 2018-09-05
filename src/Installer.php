<?php

namespace Phpbe\Installer;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class Installer extends LibraryInstaller
{

    public function getPackageBasePath(PackageInterface $package)
    {
        $prettyName = $package->getPrettyName();
        $pos = strpos($prettyName, '-');
        $prefix = '';
        if ($pos !== false) {
            $prefix = substr($prettyName, 0, $pos);
        }

        $pluginDirs = array(
            'phpbe/app-' => 'app',   // APP
            'phpbe/ui-' => 'ui',    // 前端UI
            'phpbe/theme-' => 'theme'  // 主题
        );

        if (!isset($pluginDirs[$prefix])) {
            throw new \InvalidArgumentException('安装失败，包名应该以 "' . implode('"，"', array_keys($pluginDirs)) . '" 等开头');
        }

        $appName = substr($prettyName, $pos);
        $appName = str_replace([
            '-a', '-b', '-c', '-d', '-e', '-f', '-g', '-h', '-i', '-j', '-k', '-l', '-m', '-n', '-o', '-p', '-q', '-r', '-s', '-t', '-u', '-v', '-w', '-x', '-y', '-z'
        ], [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
        ], $appName);

        return $pluginDirs[$prefix] . '/' . $appName;
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

