<?php

declare(strict_types = 1);

// relative path to the application folder from this file's folder
define('SRC_PATH', '../src');

/**
 * Basic implementation of auto loader for classes in 'src' folder
 *
 * NOTE:
 * Have not solved the issue to just use 'require "../vendor/autoload.php";' instead and still catch
 * autoload exception through Application::abort()
 *
 * @throws Exception
 */
spl_autoload_register(function ($className) {
    $classPath = preg_replace('/^Assessment\b/', SRC_PATH, $className, 1);
    $classFile = str_replace('\\', '/', $classPath) . '.php';
    if (is_file($classFile) && is_readable($classFile)) {
        /**  @noinspection PhpIncludeInspection  */ require $classFile;
    } else {
        throw new Exception('Internal Server Error: autoloader fails', 500);
    }
});

//
// Main function
//
Assessment\Application::run();
