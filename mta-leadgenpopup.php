<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.madtownagency.com
 * @since             1.0.0
 * @package           mta_leadgenpopup
 *
 * @wordpress-plugin
 * Plugin Name:       MTA Lead Generation Popup
 * Plugin URI:        http://www.madtownagency.com/
 * Description:       This plugin displays a Call to Action popup to users that incorportates a Gravity Form and can be show on exit intent or after a specific period of time.
 * Version:           1.0.2
 * Author:            Ryan Baron
 * Author URI:        http://www.madtownagency.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mta-leadgenpopup
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mta-leadgenpopup-activator.php
 */
function activate_mta_leadgenpopup() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-mta-leadgenpopup-activator.php';
  Mta_Leadgenpopup_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mta-leadgenpopup-deactivator.php
 */
function deactivate_mta_leadgenpopup() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-mta-leadgenpopup-deactivator.php';
  Mta_Leadgenpopup_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mta_leadgenpopup' );
register_deactivation_hook( __FILE__, 'deactivate_mta_leadgenpopup' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mta-leadgenpopup.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mta_leadgenpopup() {

  $plugin = new Mta_leadgenpopup();
  $plugin->run();

}
run_mta_leadgenpopup();
