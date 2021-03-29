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
        'HandmadeWeb\\Buildy\\Backend\\BackendLoader' => __DIR__ . '/../..' . '/src/Backend/BackendLoader.php',
        'HandmadeWeb\\Buildy\\Buildy' => __DIR__ . '/../..' . '/src/Buildy.php',
        'HandmadeWeb\\Buildy\\Frontend\\FrontendDirectives' => __DIR__ . '/../..' . '/src/Frontend/FrontendDirectives.php',
        'HandmadeWeb\\Buildy\\Frontend\\FrontendFilters' => __DIR__ . '/../..' . '/src/Frontend/FrontendFilters.php',
        'HandmadeWeb\\Buildy\\Frontend\\FrontendLoader' => __DIR__ . '/../..' . '/src/Frontend/FrontendLoader.php',
        'HandmadeWeb\\Buildy\\PluginLoader' => __DIR__ . '/../..' . '/src/PluginLoader.php',
        'HandmadeWeb\\Buildy\\Traits\\ContentCollector' => __DIR__ . '/../..' . '/src/Traits/ContentCollector.php',
        'HandmadeWeb\\Buildy\\Traits\\ContentRenderer' => __DIR__ . '/../..' . '/src/Traits/ContentRenderer.php',
        'HandmadeWeb\\Buildy\\Traits\\LegacyContentRenderer' => __DIR__ . '/../..' . '/src/Traits/LegacyContentRenderer.php',
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
