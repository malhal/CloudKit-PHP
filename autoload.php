<?php
/**
 * Created by PhpStorm.
 * User: mh
 * Date: 04/04/2016
 * Time: 13:43
 */

spl_autoload_register(function ($class)
{
    // CloudKit class prefix
    $prefix = 'CloudKit\\';
    // base directory for the namespace prefix
    $base_dir = defined('CLOUDKIT_DIR') ? CLOUDKIT_DIR : __DIR__ . '/CloudKit/';
    // does the class use the namespace prefix?
    $len = strlen( $prefix );
    if ( strncmp($prefix, $class, $len) !== 0 ) {
        // no, move to the next registered autoloader
        return;
    }
    // get the relative class name
    $relative_class = substr( $class, $len );
    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

    // echo $relative_class . '<br/>';
    // if the file exists, require it
    if ( file_exists( $file ) ) {
        require $file;
    }
});

?>