<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit28ff1f8b048924e8e374c38cf499fe68
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'Assessment\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Assessment\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Assessment\\Application' => __DIR__ . '/../..' . '/src/Application.php',
        'Assessment\\Xml\\Parser' => __DIR__ . '/../..' . '/src/Xml/Parser.php',
        'Assessment\\Xml\\Response\\Base' => __DIR__ . '/../..' . '/src/Xml/Response/Base.php',
        'Assessment\\Xml\\Response\\Nack' => __DIR__ . '/../..' . '/src/Xml/Response/Nack.php',
        'Assessment\\Xml\\Response\\Ping' => __DIR__ . '/../..' . '/src/Xml/Response/Ping.php',
        'Assessment\\Xml\\Response\\Reverse' => __DIR__ . '/../..' . '/src/Xml/Response/Reverse.php',
        'Assessment\\Xml\\Validator' => __DIR__ . '/../..' . '/src/Xml/Validator.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit28ff1f8b048924e8e374c38cf499fe68::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit28ff1f8b048924e8e374c38cf499fe68::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit28ff1f8b048924e8e374c38cf499fe68::$classMap;

        }, null, ClassLoader::class);
    }
}
