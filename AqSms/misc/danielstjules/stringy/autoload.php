<?php

function classLoaderStringy($class)
{
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);

 //   $path = str_replace("GuzzleHttp/Psr7/", "", $path);
    $path = str_replace("Stringy/", "", $path);

    $file = __DIR__ . '/src/' . $path . '.php';

    if (file_exists($file)) {
       // dd($file);
        require_once $file;
    }
}
spl_autoload_register('classLoaderStringy');


