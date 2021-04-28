<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd4d62a5d25387c3d5c5d16bac9c2dcff
{
    public static $prefixLengthsPsr4 = array (
        'G' => 
        array (
            'Grav\\Plugin\\Viewer3D\\' => 21,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Grav\\Plugin\\Viewer3D\\' => 
        array (
            0 => __DIR__ . '/../..' . '/classes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Grav\\Plugin\\Viewer3dPlugin' => __DIR__ . '/../..' . '/viewer3-d.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd4d62a5d25387c3d5c5d16bac9c2dcff::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd4d62a5d25387c3d5c5d16bac9c2dcff::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitd4d62a5d25387c3d5c5d16bac9c2dcff::$classMap;

        }, null, ClassLoader::class);
    }
}