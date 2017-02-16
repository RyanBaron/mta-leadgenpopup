<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       http://www.madtownagency.com
 * @since      1.0.0
 *
 * @package    mta_leadgenpopup
 */

// If uninstall not called from WordPress, then exit.
//$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$plugin_file = 'mta-leadgenpopup/mta-leadgenpopup.php';
$checked = isset($_REQUEST['checked']) ? $_REQUEST['checked'] : '';

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) || !in_array('mta-leadgenpopup/mta-leadgenpopup.php', $checked)) {
  exit;
}

//an array of all of the fields added by the plugin
$plugin_fields = array(
  '_mta_leadgenpopup_layout',
  '_mta_leadgenpopup_trigger',
  '_mta_leadgenpopup_timer',
  '_mta_leadgenpopup_superheadline',
  '_mta_leadgenpopup_headline',
  '_mta_leadgenpopup_subheadline',
  '_mta_leadgenpopup_text',
  '_mta_leadgenpopup_form_header_align',
  '_mta_leadgenpopup_form_footer_align',
  '_mta_leadgenpopup_form_labels',
  '_mta_leadgenpopup_form_superheadline',
  '_mta_leadgenpopup_form_headline',
  '_mta_leadgenpopup_form_subheadline',
  '_mta_leadgenpopup_form_text',
  '_mta_leadgenpopup_gform_id',
);

//remove all postmeta fields added by our plugin
foreach($plugin_fields as $field) {
  delete_post_meta_by_key($field);
}
