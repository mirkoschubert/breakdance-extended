<?php
/*
Plugin Name:  Breakdance Extended
Plugin URI:   https://github.com/mirkoschubert/breakdance-extended
Description:  Extended Features and Elements for the Soflyy Breakdance Builder
Version:      0.5.0
Author:       Mirko Schubert
Author URI:   https://mirkoschubert.de/
License:      GPL 3.0
License URI:  https://tldrlegal.com/license/gnu-general-public-license-v3-(gpl-3)
Text Domain:  bdext
Domain Path:  /lang
*/

if (!defined('ABSPATH'))
  exit();

define('BDEXT_VERSION', '0.5.0');
define('BDEXT_PLUGIN', 'Breakdance Extended');
define('BDEXT_DIR', dirname(plugin_basename(__FILE__)));
define('BDEXT_URL', plugin_dir_url(__FILE__));
define('BDEXT_PATH', plugin_basename(__FILE__));
define('BDEXT_ABSPATH', plugin_dir_path(__FILE__));

require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

use Breakdance\Ext\Core\Plugin;

$plugin = new Plugin();

register_uninstall_hook(__FILE__, ['Breakdance\Ext\Core\Plugin', 'uninstall']);
