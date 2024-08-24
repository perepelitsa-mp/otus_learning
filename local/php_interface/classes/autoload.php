<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

spl_autoload_register(function (string $class): void
{
    if (!str_contains($class, 'Otus')) {
        return;
    }

    var_dump($class);

    $class = str_replace('\\', '/', $class);
    $path = __DIR__ . '/' . $class . '.php';

    var_dump($path);

    if (is_file($path)) {
        require_once $path;
    }
});
