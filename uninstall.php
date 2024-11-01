<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
 
 
delete_option('add219_where');
delete_option('add219_position');
delete_option('add219_fontcolor');
delete_option('add219_backgroundcolor');
delete_option('add219_hideloggedin');

