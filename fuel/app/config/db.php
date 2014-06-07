<?php
/**
 * Use this file to override global defaults.
 *
 * See the individual environment DB configs for specific config information.
 */

return array(
    'default' => array(
        'type' => 'mysqli',
        'connection' => array(
            'hostname' => 'localhost',
            'database' => 'sukima',
            'username' => 'root',
            'password' => 'root',
        ),
    ),
);
