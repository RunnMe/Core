<?php

require __DIR__ . '/../vendor/autoload.php';

spl_autoload_register(function ($class) {
    if (0 === strpos($class, 'Runn\\')) {
        $file = __DIR__ . '/../src/' . str_replace('\\', '/', substr($class, 5)) . '.php';
        if (is_readable($file)) {
            require $file;
        }
    }
});