<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite34088975b9e08d796a84dc24ecb6cdb
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 8,
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
        ),
        'A' => 
        array (
            'AppleMusic\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
        'AppleMusic\\' => 
        array (
            0 => __DIR__ . '/../..' . '/classes',
        ),
    );

    public static $prefixesPsr0 = array (
        'D' => 
        array (
            'Detection' => 
            array (
                0 => __DIR__ . '/..' . '/mobiledetect/mobiledetectlib/namespaced',
            ),
        ),
    );

    public static $classMap = array (
        'Mobile_Detect' => __DIR__ . '/..' . '/mobiledetect/mobiledetectlib/Mobile_Detect.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite34088975b9e08d796a84dc24ecb6cdb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite34088975b9e08d796a84dc24ecb6cdb::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInite34088975b9e08d796a84dc24ecb6cdb::$prefixesPsr0;
            $loader->classMap = ComposerStaticInite34088975b9e08d796a84dc24ecb6cdb::$classMap;

        }, null, ClassLoader::class);
    }
}
