<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1aeeda6d757bda6f816500a0cc82ab00
{
    public static $prefixLengthsPsr4 = array (
        'H' => 
        array (
            'HandmadeWeb\\Buildy\\' => 19,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'HandmadeWeb\\Buildy\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'HandmadeWeb\\Buildy\\BuildyBackend' => __DIR__ . '/../..' . '/src/BuildyBackend.php',
        'HandmadeWeb\\Buildy\\BuildyFrontend' => __DIR__ . '/../..' . '/src/BuildyFrontend.php',
        'HandmadeWeb\\Buildy\\BuildyFrontendFilters' => __DIR__ . '/../..' . '/src/BuildyFrontendFilters.php',
        'HandmadeWeb\\Buildy\\Traits\\Helpers' => __DIR__ . '/../..' . '/src/Traits/Helpers.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1aeeda6d757bda6f816500a0cc82ab00::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1aeeda6d757bda6f816500a0cc82ab00::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1aeeda6d757bda6f816500a0cc82ab00::$classMap;

        }, null, ClassLoader::class);
    }
}
