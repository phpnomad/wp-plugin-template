<?php
/*
 * Plugin Name: Plugin Template
 * Description: Plugin template
 * Author:
 * Author URI:
 * Version: 1.0.0
 */

use PluginNameReplaceMe\Application;

require_once 'vendor/autoload.php';
require_once 'libraries/action-scheduler/action-scheduler.php';

/**
 * Registers the installation process, which runs when the plugin activates.
 */
register_activation_hook(__FILE__, function () {
	(new Application(__FILE__))->install();
});

/**
 * Bootstrap the plugin.
 */
(new Application(__FILE__))->init();