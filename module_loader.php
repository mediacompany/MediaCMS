<?php
/**
 * Load the MediaCompany modules.
 *
 * custom plugins, MediaCompany
 *
 * @since 1.0.0
 *
 */
foreach(glob('modules/*', GLOB_ONLYDIR) as $dir) {
    $dir = str_replace('modules/', '', $dir);
    $filename = ABSPATH.'modules/'.$dir.'/'.$dir.'.php';
    include $filename;
}