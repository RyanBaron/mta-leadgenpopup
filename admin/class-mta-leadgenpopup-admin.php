<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.madtownagency.com
 * @since      1.0.0
 *
 * @package    mta_leadgenpopup
 * @subpackage mta_leadgenpopup/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    mta_leadgenpopup
 * @subpackage mta_leadgenpopup/admin
 * @author     Ryan Baron <ryan@madtownagency.com>
 */
class Mta_Leadgenpopup_Admin {

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * The options group name for the plugin settings.
   *
   * @since    1.0.2
   * @access   private
   * @var      string    options_group    The options group name for the plugin settings.
   */
  private $content;

  /**
   * The options group name for the plugin settings.
   *
   * @since    1.0.2
   * @access   private
   * @var      string    options_group    The options group name for the plugin settings.
   */
  private $page_display;

  /**
   * The options group name for the plugin settings.
   *
   * @since    1.0.2
   * @access   private
   * @var      string    options_group    The options group name for the plugin settings.
   */
  private $post_display;

  /**
   * Initialize the class and set its properties.
   *
   * @since   1.0.0
   * @param   string    $plugin_name    The name of this plugin.
   * @param   string    $version        The version of this plugin.
   * @param   string    $options        The options group name for the plugin settings.
   */
  public function __construct( $plugin_name, $version ) {

    $this->plugin_name  = $plugin_name;
    $this->version      = $version;

    add_action('add_meta_boxes', array($this, 'mta_leadgenpopup_metabox'));
    add_action('save_post', array($this, 'save'));

    add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
    add_action( 'admin_init', array( $this, 'page_init' ) );
  }

  public function get_allowed_field_tags($type = '') {
    switch($type) {
      case 'textarea':
        return array(
          'p' => array(
          'class' => array(),
        ),
          'strong' => array(),
          'br' => array(),
          'em' => array(),
          'span' => array(
          'class' => array()
        ),
          'ul' => array(
          'class' => array()
        ),
          'ol' => array(
          'class' => array()
        ),
          'li' => array(
          'class' => array()
        ),
          'h1' => array(
          'class' => array()
        ),
          'h2' => array(
          'class' => array()
        ),
        );
        break;

      case 'text':
        return array(
          'strong' => array(),
          'em' => array(),
          'i' => array(
          'class' => array(),
          'aria-hidden' => array(),
        ),
          'span' => array(
          'class' => array(),
        ),
        );
        break;

      default:
        return array();
        break;
    }
    return array();
  }


  public function get_select_values($type) {
    switch($type) {
      case 'layout':

        return array(
          'hidden'              => __('Hidden',                   'mta-leadgenpopup'),
          'single-col'          => __('Single Column',            'mta-leadgenpopup'),
          'two-col-text-left'   => __('Two Column - Text Left',   'mta-leadgenpopup'),
          'two-col-text-right'  => __('Two Column - Text Right',  'mta-leadgenpopup'),
        );
        break;

      case 'trigger':
        return array(
          'exit'            => __('Page Exit',                'mta-leadgenpopup'),
          'timer'           => __('Time on Page',             'mta-leadgenpopup'),
          'exit-and-timer'  => __('Page Exit & Time On Page', 'mta-leadgenpopup'),
        );
        break;

      case 'timer':
        return array(
          '1000'    => __('1 Second',     'mta-leadgenpopup'),
          '5000'    => __('5 Seconds',    'mta-leadgenpopup'),
          '10000'   => __('10 Seconds',   'mta-leadgenpopup'),
          '15000'   => __('15 Seconds',   'mta-leadgenpopup'),
          '20000'   => __('20 Seconds',   'mta-leadgenpopup'),
          '25000'   => __('25 Seconds',   'mta-leadgenpopup'),
          '30000'   => __('30 Seconds',   'mta-leadgenpopup'),
          '45000'   => __('45 Seconds',   'mta-leadgenpopup'),
          '60000'   => __('60 Seconds',   'mta-leadgenpopup'),
          '90000'   => __('90 Seconds',   'mta-leadgenpopup'),
          '120000'  => __('120 Seconds',  'mta-leadgenpopup'),
        );
        break;

      case 'text-align':
        return array(
          'align-left'    => __('Left Align',     'mta-leadgenpopup'),
          'align-center'  => __('Right Align',    'mta-leadgenpopup'),
        );
        break;

      case 'form-labels':
        return array(
          'show-labels' => __('Show Labels', 'mta-leadgenpopup'),
          'hide-labels' => __('Hide Labels', 'mta-leadgenpopup'),
        );
        break;
    }
    return array();
  }


  /**
    * Add options page
    */
  public function add_plugin_page() {
    //Add a new top level Lead Gen Popup Page
    add_menu_page(
      'Lead Generation Popup Options',
      'LeadGen Popup',
      'manage_options',
      'mta-leadgen-popup-admin',
      array( $this, 'create_admin_page' )
    );
  }

  /**
    * Options page callback
    */
  public function create_admin_page() {
    // Set class property
    $this->content = get_option( 'mta_leadgen_popup_content' );
    $this->page_display = get_option( 'mta_leadgen_popup_page_display' );
    $this->post_display = get_option( 'mta_leadgen_popup_post_display' );
?>

<div class="wrap mta-leadgenpopup-options-page">
  <h1><?php _e("Lead Generation Popup Options", "mta-leadgenpopup"); ?></h1>
  <?php
    //we check if the page is visited by click on the tabs or on the menu button.
    //then we get the active tab.
    $active_tab = "popup-content";
    if(isset($_GET["tab"])) {
      if($_GET["tab"] == "page-display") {
        $active_tab = "page-display";
      } elseif ($_GET["tab"] == "post-display") {
        $active_tab = "post-display";
      } else {
        $active_tab = "popup-content";
      }
    } ?>

  <!-- wordpress provides the styling for tabs. -->
  <h2 class="nav-tab-wrapper">
    <!-- when tab buttons are clicked we jump back to the same page but with a new parameter that represents the clicked tab. accordingly we make it active -->
    <a href="?page=mta-leadgen-popup-admin&tab=popup-content" class="nav-tab <?php if($active_tab == 'popup-content'){echo 'nav-tab-active';} ?> ">
      <?php _e('Popup Content', 'mta-leadgenpopup'); ?>
    </a>
    <a href="?page=mta-leadgen-popup-admin&tab=page-display" class="nav-tab <?php if($active_tab == 'page-display'){echo 'nav-tab-active';} ?>">
      <?php _e('Page Display', 'mta-leadgenpopup'); ?>
    </a>
    <a href="?page=mta-leadgen-popup-admin&tab=post-display" class="nav-tab <?php if($active_tab == 'post-display'){echo 'nav-tab-active';} ?>">
      <?php _e('Post Display', 'mta-leadgenpopup'); ?>
    </a>
  </h2>

  <form method="post" action="options.php">
    <?php
    if($active_tab == 'popup-content') {
      // This prints out all hidden setting fields
      settings_fields( 'mta_leadgen_popup_content_group' );
      do_settings_sections( 'mta-leadgen-popup-settings' );
      do_settings_sections( 'mta-leadgen-popup-content' );
      do_settings_sections( 'mta-leadgen-popup-form' );
    } elseif ($active_tab == 'page-display') {
      // This prints out all hidden setting fields
      settings_fields( 'mta_leadgen_popup_page_display_group' );
      do_settings_sections( 'mta-leadgen-popup-page-display' );
    } elseif ($active_tab == 'post-display') {
      // This prints out all hidden setting fields
      settings_fields( 'mta_leadgen_popup_post_display_group' );
      do_settings_sections( 'mta-leadgen-popup-post-display' );
    }
    submit_button(); ?>
  </form>
</div>
<?php }

  /**
    * Register and add settings
    */
  public function page_init() {
    //////
    // Register mta leadgen popup content settings
    //////
    register_setting(
      'mta_leadgen_popup_content_group', // Option group
      'mta_leadgen_popup_content', // Option name
      array( $this, 'sanitize_content' ) // Sanitize
    );
    //////
    // Create the mta leadgen popup content settings section
    /////
    add_settings_section(
      'popup_settings', // ID
      'Default Popup Settings', // Title
      array( $this, 'print_settings_section_info' ), // Callback
      'mta-leadgen-popup-settings' // Page
    );
    //////
    // Add the mta leadgen popup setting fields
    //////
    add_settings_field(
      'mta_leadgenpopup_layout', // ID
      'Popup Layout', // Title
      array( $this, 'select_layout_callback' ), // Callback
      'mta-leadgen-popup-settings', // Page
      'popup_settings' // Section
    );
    add_settings_field(
      'mta_leadgenpopup_trigger',
      'Popup Trigger',
      array( $this, 'select_trigger_callback' ),
      'mta-leadgen-popup-settings',
      'popup_settings'
    );
    add_settings_field(
      'mta_leadgenpopup_timer',
      'Popup Timer',
      array( $this, 'select_timer_callback' ),
      'mta-leadgen-popup-settings',
      'popup_settings'
    );
    //////
    // Create the mta leadgen popup content content column section
    /////
    add_settings_section(
      'popup_content_col', // ID
      'Default Popup Content Column', // Title
      array( $this, 'print_content_section_info' ), // Callback
      'mta-leadgen-popup-content' // Page
    );
    //////
    // Add the mta leadgen popup content column fields
    //////
    add_settings_field(
      'mta_leadgenpopup_superheadline', // ID
      'Content Super Headline', // Title
      array( $this, 'mta_leadgenpopup_superheadline_callback' ), // Callback
      'mta-leadgen-popup-content', // Page
      'popup_content_col' // Section
    );
    add_settings_field(
      'mta_leadgenpopup_headline',
      'Content Headline',
      array( $this, 'mta_leadgenpopup_headline_callback' ),
      'mta-leadgen-popup-content',
      'popup_content_col'
    );
    add_settings_field(
      'mta_leadgenpopup_subheadline',
      'Content Sub Headline',
      array( $this, 'mta_leadgenpopup_subheadline_callback' ),
      'mta-leadgen-popup-content',
      'popup_content_col'
    );
    add_settings_field(
      'mta_leadgenpopup_text',
      'Content Paragraph Text',
      array( $this, 'mta_leadgenpopup_text_callback' ),
      'mta-leadgen-popup-content',
      'popup_content_col'
    );
    //////
    // Create the mta leadgen popup content form column section
    /////
    add_settings_section(
      'popup_form_col', // ID
      'Popup Form Column Content', // Title
      array( $this, 'print_form_section_info' ), // Callback
      'mta-leadgen-popup-form' // Page
    );
    //////
    // Add the mta leadgen popup form column fields
    //////
    add_settings_field(
      'mta_leadgenpopup_form_header_align', // ID
      'Form Header Align', // Title
      array( $this, 'mta_leadgenpopup_form_header_align_callback' ), // Callback
      'mta-leadgen-popup-form', // Page
      'popup_form_col' // Section
    );
    add_settings_field(
      'mta_leadgenpopup_superheadline',
      'Form Header Super Headline',
      array( $this, 'mta_leadgenpopup_form_superheadline_callback' ),
      'mta-leadgen-popup-form',
      'popup_form_col'
    );
    add_settings_field(
      'mta_leadgenpopup_headline',
      'Form Header Headline',
      array( $this, 'mta_leadgenpopup_form_headline_callback' ),
      'mta-leadgen-popup-form',
      'popup_form_col'
    );
    add_settings_field(
      'mta_leadgenpopup_subheadline',
      'Form Header Sub Headline',
      array( $this, 'mta_leadgenpopup_form_subheadline_callback' ),
      'mta-leadgen-popup-form',
      'popup_form_col'
    );
    add_settings_field(
      'mta_leadgenpopup_form_labels',
      'Form Labels',
      array( $this, 'mta_leadgenpopup_form_labels_callback' ),
      'mta-leadgen-popup-form',
      'popup_form_col'
    );
    add_settings_field(
      'mta_leadgenpopup_gform_id',
      'Gravity Form',
      array( $this, 'mta_leadgenpopup_gform_id_callback' ),
      'mta-leadgen-popup-form',
      'popup_form_col'
    );
    add_settings_field(
      'mta_leadgenpopup_form_footer_align',
      'Form Footer Align',
      array( $this, 'mta_leadgenpopup_form_footer_align_callback' ),
      'mta-leadgen-popup-form',
      'popup_form_col'
    );
    add_settings_field(
      'mta_leadgenpopup_text',
      'Form Footer Paragraph Text',
      array( $this, 'mta_leadgenpopup_form_text_callback' ),
      'mta-leadgen-popup-form',
      'popup_form_col'
    );
    //////
    // Register mta leadgen popup page selection settings
    //////
    register_setting(
      'mta_leadgen_popup_page_display_group', // Option group
      'mta_leadgen_popup_page_display', // Option name
      array( $this, 'sanitize_display' ) // Sanitize
    );
    //////
    // Create the mta leadgen popup page settings section
    /////
    add_settings_section(
      'page_display',
      'Page Selection',
      array( $this, 'print_page_section_info' ),
      'mta-leadgen-popup-page-display'
    );
    //////
    // Add the mta leadgen popup page fields
    //////
    add_settings_field(
      'mta_leadgen_pages', // ID
      'Select Pages', // Title
      array( $this, 'select_pages_callback' ), // Callback
      'mta-leadgen-popup-page-display', // Page
      'page_display' // Section
    );

    //////
    // Register mta leadgen popup post selection settings
    //////
    register_setting(
      'mta_leadgen_popup_post_display_group', // Option group
      'mta_leadgen_popup_post_display', // Option name
      array( $this, 'sanitize_display' ) // Sanitize
    );
    //////
    // Create the mta leadgen popup post settings section
    /////
    add_settings_section(
      'post_display',
      'Post Selection',
      array( $this, 'print_page_section_info' ),
      'mta-leadgen-popup-post-display'
    );
    add_settings_field(
      'mta_leadgen_posts', // ID
      'Select Posts', // Title
      array( $this, 'select_posts_callback' ), // Callback
      'mta-leadgen-popup-post-display', // Page
      'post_display' // Section
    );
  }

  /**
    * Sanitize each display settings field as needed
    *
    * @param array $input Contains all settings fields as array keys
    */
  public function sanitize_display( $input ) {

    $new_input = array();

    if( isset( $input['mta_leadgen_pages'] ) )
      $new_input['mta_leadgen_pages'] = $input['mta_leadgen_pages'];

    if( isset( $input['mta_leadgen_posts'] ) )
      $new_input['mta_leadgen_posts'] = $input['mta_leadgen_posts'];

    return $new_input;
  }

  /**
    * Sanitize each content settings field as needed
    *
    * @param array $input Contains all settings fields as array keys
    */
  public function sanitize_content( $input ) {

    $new_input = array();

    /* Arrays of allowed tag for various form input fields */
    $allow_text_only = $this->get_allowed_field_tags();
    $allowed_input_text = $this->get_allowed_field_tags('text');
    $allowed_input_textarea = $this->get_allowed_field_tags('textarea');

    if( isset( $input['mta_leadgenpopup_layout'] ) )
      $new_input['mta_leadgenpopup_layout'] = sanitize_text_field( $input['mta_leadgenpopup_layout'] );

    if( isset( $input['mta_leadgenpopup_trigger'] ) )
      $new_input['mta_leadgenpopup_trigger'] = sanitize_text_field( $input['mta_leadgenpopup_trigger'] );

    if( isset( $input['mta_leadgenpopup_timer'] ) )
      $new_input['mta_leadgenpopup_timer'] = sanitize_text_field( $input['mta_leadgenpopup_timer'] );

    if( isset( $input['mta_leadgenpopup_superheadline'] ) )
      $new_input['mta_leadgenpopup_superheadline'] = wp_kses( $input['mta_leadgenpopup_superheadline'], $allowed_input_text);

    if( isset( $input['mta_leadgenpopup_headline'] ) )
      $new_input['mta_leadgenpopup_headline'] = wp_kses( $input['mta_leadgenpopup_headline'], $allowed_input_text);

    if( isset( $input['mta_leadgenpopup_subheadline'] ) )
      $new_input['mta_leadgenpopup_subheadline'] = wp_kses( $input['mta_leadgenpopup_subheadline'], $allowed_input_text);

    if( isset( $input['mta_leadgenpopup_text'] ) )
      $new_input['mta_leadgenpopup_text'] = wp_kses( $input['mta_leadgenpopup_text'], $allowed_input_textarea);

    if( isset( $input['mta_leadgenpopup_form_header_align'] ) )
      $new_input['mta_leadgenpopup_form_header_align'] = sanitize_text_field( $input['mta_leadgenpopup_form_header_align'] );

    if( isset( $input['mta_leadgenpopup_form_superheadline'] ) )
      $new_input['mta_leadgenpopup_form_superheadline'] = wp_kses( $input['mta_leadgenpopup_form_superheadline'], $allowed_input_text);

    if( isset( $input['mta_leadgenpopup_form_headline'] ) )
      $new_input['mta_leadgenpopup_form_headline'] = wp_kses( $input['mta_leadgenpopup_form_headline'], $allowed_input_text);

    if( isset( $input['mta_leadgenpopup_form_subheadline'] ) )
      $new_input['mta_leadgenpopup_form_subheadline'] = wp_kses( $input['mta_leadgenpopup_form_subheadline'], $allowed_input_text);

    if( isset( $input['mta_leadgenpopup_form_labels'] ) )
      $new_input['mta_leadgenpopup_form_labels'] = sanitize_text_field( $input['mta_leadgenpopup_form_labels'] );

    if( isset( $input['mta_leadgenpopup_gform_id'] ) )
      $new_input['mta_leadgenpopup_gform_id'] = sanitize_text_field( $input['mta_leadgenpopup_gform_id'] );

    if( isset( $input['mta_leadgenpopup_form_text'] ) )
      $new_input['mta_leadgenpopup_form_text'] = wp_kses( $input['mta_leadgenpopup_form_text'], $allowed_input_textarea);

    if( isset( $input['mta_leadgenpopup_form_footer_align'] ) )
      $new_input['mta_leadgenpopup_form_footer_align'] = sanitize_text_field( $input['mta_leadgenpopup_form_footer_align'] );

    return $new_input;
  }

  /**
    * Print the Settings Section text
    */
  public function print_settings_section_info() {
    print '<p>Set the default MTA Lead Generation Popup settings.</p>';
    print '<p>These settings can be overwritten on a per page/post basis by going to the individual page/post admin page.</p>';
  }

  /**
    * Print the Content Section text
    */
  public function print_content_section_info() {
    print '<p>Set the default MTA Lead Generation Popup Content Column.</p>';
    print '<p>These settings can be overwritten on a per page/post basis by going to the individual page/post admin page.</p>';
  }

  /**
    * Print the Form Section text
    */
  public function print_form_section_info() {
    print '<p>Set the default MTA Lead Generation Popup Form Column.</p>';
    print '<p>These settings can be overwritten on a per page/post basis by going to the individual page/post admin page.</p>';
  }

  /**
    * Print the Page Display Section text
    */
  public function print_page_section_info() {
    print '<p>Select the page(s) you want to display the Lead Generation Popup on.</p>';
  }

  /**
    * Print the leadgenpopup_layout select field
    */
  public function select_layout_callback() {
    $options = '<option value="" '. selected( $this->content['mta_leadgenpopup_layout'], '', FALSE) .'></option>';
    foreach( $this->get_select_values('layout') as $key => $label ) {
      $options .= '<option value="'.$key.'" '. selected( $this->content['mta_leadgenpopup_layout'], $key, FALSE) .'>'.$label.'</option>';
    }
    print '<select name="mta_leadgen_popup_content[mta_leadgenpopup_layout]" id="mta_leadgenpopup_layout">' . $options .'</select>';
  }

  /**
    * Print the leadgenpopup_trigger select field
    */
  public function select_trigger_callback() {
    $options = '<option value="" '. selected( $this->content['mta_leadgenpopup_trigger'], '', FALSE) .'></option>';
    foreach( $this->get_select_values('trigger') as $key => $label ) {
      $options .= '<option value="'.$key.'" '. selected( $this->content['mta_leadgenpopup_trigger'], $key, FALSE) .'>'.$label.'</option>';
    }
    print '<select name="mta_leadgen_popup_content[mta_leadgenpopup_trigger]" id="mta_leadgenpopup_trigger">' . $options .'</select>';
  }

  /**
    * Print the leadgenpopup_timer select field
    */
  public function select_timer_callback() {
    $options = '<option value="" '. selected( $this->content['mta_leadgenpopup_timer'], '', FALSE) .'></option>';
    foreach( $this->get_select_values('timer') as $key => $label ) {
      $options .= '<option value="'.$key.'" '. selected( $this->content['mta_leadgenpopup_timer'], $key, FALSE) .'>'.$label.'</option>';
    }
    print '<select name="mta_leadgen_popup_content[mta_leadgenpopup_timer]" id="mta_leadgenpopup_timer">' . $options .'</select>';
  }

  /**
    * Get the settings option array and print one of its values
    */

  public function mta_leadgenpopup_superheadline_callback() {
    printf(
      '<input type="text" id="mta_leadgenpopup_superheadline" name="mta_leadgen_popup_content[mta_leadgenpopup_superheadline]" value="%s" />',
      isset( $this->content['mta_leadgenpopup_superheadline'] ) ? esc_attr( $this->content['mta_leadgenpopup_superheadline']) : ''
    );
    print '<div class="desc">' . __("Allowed Tags", "mta-leadgenpopup") . ' : <em>span(class), i(class), strong and em</em></div>';
  }

  /**
    * Get the settings option array and print one of its values
    */
  public function mta_leadgenpopup_headline_callback() {
    printf(
      '<input type="text" id="mta_leadgenpopup_headline" name="mta_leadgen_popup_content[mta_leadgenpopup_headline]" value="%s" />',
      isset( $this->content['mta_leadgenpopup_headline'] ) ? esc_attr( $this->content['mta_leadgenpopup_headline']) : ''
    );
    print '<div class="desc">' . __("Allowed Tags", "mta-leadgenpopup") . ' : <em>span(class), i(class), strong and em</em></div>';
  }

  /**
    * Get the settings option array and print one of its values
    */
  public function mta_leadgenpopup_subheadline_callback() {
    printf(
      '<input type="text" id="mta_leadgenpopup_subheadline" name="mta_leadgen_popup_content[mta_leadgenpopup_subheadline]" value="%s" />',
      isset( $this->content['mta_leadgenpopup_subheadline'] ) ? esc_attr( $this->content['mta_leadgenpopup_subheadline']) : ''
    );
    print '<div class="desc">' . __("Allowed Tags", "mta-leadgenpopup") . ' : <em>span(class), i(class), strong and em</em></div>';
  }

  /**
    * Get the settings option array and print one of its values
    */
  public function mta_leadgenpopup_text_callback() {
    printf(
      '<textarea id="mta_leadgenpopup_text" name="mta_leadgen_popup_content[mta_leadgenpopup_text]">%s</textarea>',
      isset( $this->content['mta_leadgenpopup_text'] ) ? esc_attr( $this->content['mta_leadgenpopup_text']) : ''
    );
    print '<div class="desc">' . __("Allowed Tags", "mta-leadgenpopup") . ' : <em>p(class), span(class), ul(class), ol(class), li(class), h1(class), h2(class), i(class), strong, em and br</em></div>';
  }

  /**
    * Print the leadgenpopup_form_header_align select field
    */
  public function mta_leadgenpopup_form_header_align_callback() {
    $options = '<option value="" '. selected( $this->content['mta_leadgenpopup_form_header_align'], '', FALSE) .'></option>';
    foreach( $this->get_select_values('text-align') as $key => $label ) {
      $options .= '<option value="'.$key.'" '. selected( $this->content['mta_leadgenpopup_form_header_align'], $key, FALSE) .'>'.$label.'</option>';
    }
    print '<select name="mta_leadgen_popup_content[mta_leadgenpopup_form_header_align]" id="mta_leadgenpopup_form_header_align">' . $options .'</select>';
  }

  /**
    * Get the settings option array and print one of its values
    */
  public function mta_leadgenpopup_form_superheadline_callback() {
    printf(
      '<input type="text" id="mta_leadgenpopup_form_superheadline" name="mta_leadgen_popup_content[mta_leadgenpopup_form_superheadline]" value="%s" />',
      isset( $this->content['mta_leadgenpopup_form_superheadline'] ) ? esc_attr( $this->content['mta_leadgenpopup_form_superheadline']) : ''
    );
    print '<div class="desc">' . __("Allowed Tags", "mta-leadgenpopup") . ' : <em>span(class), i(class), strong and em</em></div>';
  }

  /**
    * Get the settings option array and print one of its values
    */
  public function mta_leadgenpopup_form_headline_callback() {
    printf(
      '<input type="text" id="mta_leadgenpopup_form_headline" name="mta_leadgen_popup_content[mta_leadgenpopup_form_headline]" value="%s" />',
      isset( $this->content['mta_leadgenpopup_form_headline'] ) ? esc_attr( $this->content['mta_leadgenpopup_form_headline']) : ''
    );
    print '<div class="desc">' . __("Allowed Tags", "mta-leadgenpopup") . ' : <em>span(class), i(class), strong and em</em></div>';
  }

  /**
    * Get the settings option array and print one of its values
    */
  public function mta_leadgenpopup_form_subheadline_callback() {
    printf(
      '<input type="text" id="mta_leadgenpopup_form_subheadline" name="mta_leadgen_popup_content[mta_leadgenpopup_form_subheadline]" value="%s" />',
      isset( $this->content['mta_leadgenpopup_form_subheadline'] ) ? esc_attr( $this->content['mta_leadgenpopup_form_subheadline']) : ''
    );
    print '<div class="desc">' . __("Allowed Tags", "mta-leadgenpopup") . ' : <em>span(class), i(class), strong and em</em></div>';
  }

  /**
    * Print the leadgenpopup_form_labels select field
    */
  public function mta_leadgenpopup_form_labels_callback() {
    $options = '<option value="" '. selected( $this->content['mta_leadgenpopup_form_labels'], '', FALSE) .'></option>';
    foreach( $this->get_select_values('form-labels') as $key => $label ) {
      $options .= '<option value="'.$key.'" '. selected( $this->content['mta_leadgenpopup_form_labels'], $key, FALSE) .'>'.$label.'</option>';
    }
    print '<select name="mta_leadgen_popup_content[mta_leadgenpopup_form_labels]" id="mta_leadgenpopup_form_labels">' . $options .'</select>';
  }

  /**
    * Print the leadgenpopup_gform_id select field
    */
  public function mta_leadgenpopup_gform_id_callback() {
    $select = '<select name="mta_leadgen_popup_content[mta_leadgenpopup_gform_id]" id="mta_leadgenpopup_gform_id">';
    $select .= '<option value="">None</option>';
    $forms = RGFormsModel::get_forms( null, 'title' );
    foreach( $forms as $form ):
    $select .= '<option value="' . $form->id . '" ' . selected( $this->content['mta_leadgenpopup_gform_id'], $form->id, false ) .' >' . $form->title . '</option>';
    endforeach;
    $select .= '</select>';

    print $select;
  }

  /**
    * Print the leadgenpopup_form_footer_align select field
    */
  public function mta_leadgenpopup_form_footer_align_callback() {
    $options = '<option value="" '. selected( $this->content['mta_leadgenpopup_form_footer_align'], '', FALSE) .'></option>';
    foreach( $this->get_select_values('text-align') as $key => $label ) {
      $options .= '<option value="'.$key.'" '. selected( $this->content['mta_leadgenpopup_form_footer_align'], $key, FALSE) .'>'.$label.'</option>';
    }
    print '<select name="mta_leadgen_popup_content[mta_leadgenpopup_form_footer_align]" id="mta_leadgenpopup_form_footer_align">' . $options .'</select>';
  }

  /**
    * Get the settings option array and print one of its values
    */
  public function mta_leadgenpopup_form_text_callback() {
    printf(
      '<textarea id="mta_leadgenpopup_form_text" name="mta_leadgen_popup_content[mta_leadgenpopup_form_text]">%s</textarea>',
      isset( $this->content['mta_leadgenpopup_form_text'] ) ? esc_attr( $this->content['mta_leadgenpopup_form_text']) : ''
    );
    print '<div class="desc">' . __("Allowed Tags", "mta-leadgenpopup") . ' : <em>p(class), span(class), ul(class), ol(class), li(class), h1(class), h2(class), i(class), strong, em and br</em></div>';
  }

  /**
    * Print the mta_leadgen_pages checkbox fields
    */
  public function select_pages_callback() {

    //get all of the pages on the site
    $site_pages = get_pages(
      array(
      'sort_column' => 'post_title',
      'sort_order' => 'ASC'
    )
    );

    //generate the checkbox fields
    foreach( $site_pages as $page ) {
      $title = $page->post_title;
      $slug = $page->post_name;
      $field_value = isset($this->page_display['mta_leadgen_pages'][$slug]) ? $this->page_display['mta_leadgen_pages'][$slug] : 0;

      printf( __( '<div class="meta-input"><label><input type="checkbox" name="mta_leadgen_popup_page_display[mta_leadgen_pages][%1$s]" id="%1$s" value="1" %3$s>%2$s</label></div>' ),
             $slug,
             $title,
             checked( $field_value, '1', FALSE));
    }
  }

  /**
    * Print the mta_leadgen_posts checkbox fields
    */
  public function select_posts_callback() {

    //get all of the pages on the site
    $site_posts = get_posts(
      array(
      'numberposts' => -1,
      'order'       => 'ASC',
      'orderby'     => 'title'
    )
    );

    //generate the checkbox fields
    foreach( $site_posts as $post ) {
      $title = $post->post_title;
      $slug = $post->post_name;
      $field_value = isset($this->post_display['mta_leadgen_posts'][$slug]) ? $this->post_display['mta_leadgen_posts'][$slug] : 0;

      printf( __( '<div class="meta-input"><label><input type="checkbox" name="mta_leadgen_popup_post_display[mta_leadgen_posts][%1$s]" id="%1$s" value="1" %3$s>%2$s</label></div>' ),
             $slug,
             $title,
             checked( $field_value, '1', FALSE));
    }
  }

  /**
   * Adds the meta box container.
   */
  public function mta_leadgenpopup_metabox($post_type) {

    //add the metabox to all pages/posts
    if($post_type == 'post' || $post_type == 'page') {
      add_meta_box('mta_leadgenpopup_section',
                   'Lead Generation Popup',
                   array($this, 'mta_leadgenpopup_page_section_metabox'),
                   $post_type,
                   'normal',
                   'core');
    }
  }

  public function mta_leadgenpopup_page_section_metabox($post) {

    // Add an nonce field so we can check for it later.
    wp_nonce_field('mta_leadgenpopup_nonce_check', 'mta_leadgenpopup_nonce_check_value');

    // Use get_post_meta to retrieve an existing value from the database.
    $layout         = get_post_meta($post->ID, '_mta_leadgenpopup_layout', true);
    $trigger        = get_post_meta($post->ID, '_mta_leadgenpopup_trigger', true);
    $timer          = get_post_meta($post->ID, '_mta_leadgenpopup_timer', true);
    $superheadline  = get_post_meta($post->ID, '_mta_leadgenpopup_superheadline', true);
    $headline       = get_post_meta($post->ID, '_mta_leadgenpopup_headline', true);
    $subheadline    = get_post_meta($post->ID, '_mta_leadgenpopup_subheadline', true);
    $text           = get_post_meta($post->ID, '_mta_leadgenpopup_text', true);

    $form_header_align  = get_post_meta($post->ID, '_mta_leadgenpopup_form_header_align', true);
    $form_footer_align  = get_post_meta($post->ID, '_mta_leadgenpopup_form_footer_align', true);
    $form_labels        = get_post_meta($post->ID, '_mta_leadgenpopup_form_labels', true);
    $form_superheadline = get_post_meta($post->ID, '_mta_leadgenpopup_form_superheadline', true);
    $form_headline      = get_post_meta($post->ID, '_mta_leadgenpopup_form_headline', true);
    $form_subheadline   = get_post_meta($post->ID, '_mta_leadgenpopup_form_subheadline', true);
    $form_text          = get_post_meta($post->ID, '_mta_leadgenpopup_form_text', true);
    $gform_id           = get_post_meta($post->ID, '_mta_leadgenpopup_gform_id',true); ?>

    <div class="bootstrap-wrapper mta-leadgenpopup-meta-wrapper <?php echo $layout ?> <?php echo $trigger; ?>">

      <div class="display-meta-wrapper">

      </div>

      <div class="style-meta-wrapper">
        <div class="meta-input">
          <label for="mta_leadgenpopup_layout"><?php printf( __('Select Popup Layout', 'mta-leadgenpopup') ); ?></label>
          <select name="mta_leadgenpopup_layout" id="mta_leadgenpopup_layout">
            <option value="" <?php selected( $layout, "" ); ?>><?php printf( __('Hidden', 'mta-leadgenpopup') ); ?></option>
            <option value="single-col" <?php selected( $layout, "single-col" ); ?>><?php printf( __('Single Column', 'mta-leadgenpopup') ); ?></option>
            <option value="two-col-text-left" <?php selected( $layout, "two-col-text-left" ); ?> ><?php printf( __('Two Column - Text Left', 'mta-leadgenpopup') ); ?></option>
            <option value="two-col-text-right" <?php selected( $layout, "two-col-text-right" ); ?>><?php printf( __('Two Column - Text Right', 'mta-leadgenpopup') ); ?></option>
          </select>
        </div>

        <div class="meta-input">
          <label for="mta_leadgenpopup_trigger"><?php printf( __('Select Popup Trigger', 'mta-leadgenpopup') ); ?></label>
          <select name="mta_leadgenpopup_trigger" id="mta_leadgenpopup_trigger">
            <option value="" <?php selected( $trigger, "" ); ?>><?php printf( __('None', 'mta-leadgenpopup') ); ?></option>
            <option value="exit" <?php selected( $trigger, "exit" ); ?>><?php printf( __('Page Exit', 'mta-leadgenpopup') ); ?></option>
            <option value="timer" <?php selected( $trigger, "timer" ); ?> ><?php printf( __('Time On Page', 'mta-leadgenpopup') ); ?></option>
            <option value="exit-and-timer" <?php selected( $trigger, "exit-and-timer" ); ?>><?php printf( __('Page Exit & Time On Page', 'mta-leadgenpopup') ); ?></option>
          </select>
        </div>

        <div class="meta-input timer">
          <label for="mta_leadgenpopup_timer"><?php printf( __('Select Popup Timing', 'mta-leadgenpopup') ); ?></label>
          <select name="mta_leadgenpopup_timer" id="mta_leadgenpopup_timer">
            <?php //time values in miliseconds ?>
            <option value="1000" <?php selected( $timer, "1000" ); ?>><?php printf( __('1 Second', 'mta-leadgenpopup') ); ?></option>
            <option value="5000" <?php selected( $timer, "5000" ); ?>><?php printf( __('5 Seconds', 'mta-leadgenpopup') ); ?></option>
            <option value="10000" <?php selected( $timer, "10000" ); ?>><?php printf( __('10 Seconds', 'mta-leadgenpopup') ); ?></option>
            <option value="15000" <?php selected( $timer, "15000" ); ?>><?php printf( __('15 Seconds', 'mta-leadgenpopup') ); ?></option>
            <option value="15000" <?php selected( $timer, "20000" ); ?>><?php printf( __('20 Seconds', 'mta-leadgenpopup') ); ?></option>
            <option value="15000" <?php selected( $timer, "25000" ); ?>><?php printf( __('25 Seconds', 'mta-leadgenpopup') ); ?></option>
            <option value="30000" <?php selected( $timer, "30000" ); ?>><?php printf( __('30 Seconds', 'mta-leadgenpopup') ); ?></option>
            <option value="45000" <?php selected( $timer, "45000" ); ?>><?php printf( __('45 Seconds', 'mta-leadgenpopup') ); ?></option>
            <option value="60000" <?php selected( $timer, "60000" ); ?>><?php printf( __('60 Seconds', 'mta-leadgenpopup') ); ?></option>
            <option value="90000" <?php selected( $timer, "90000" ); ?>><?php printf( __('90 Seconds', 'mta-leadgenpopup') ); ?></option>
            <option value="120000" <?php selected( $timer, "120000" ); ?>><?php printf( __('120 Seconds', 'mta-leadgenpopup') ); ?></option>
          </select>
        </div>
      </div>

      <div class="headline-meta-wrapper"><h4><?php printf( __('Popup Content', 'mta-leadgenpopup') ); ?></h4></div>
      <div class="content-meta-wrapper">
        <div class="meta-input">
          <label for="mta_leadgenpopup_superheadline"><?php printf( __('Super Headline', 'mta-leadgenpopup') ); ?></label>
          <input type="text" name="mta_leadgenpopup_superheadline" id="mta_leadgenpopup_superheadline" value="<?php echo $superheadline; ?>" />
          <div class="desc"><?php printf( __('Allowed Tags', 'mta-leadgenpopup') ); ?>: <em>span(class), i(class), strong and em</em></div>
        </div>
        <div class="meta-input">
          <label for="mta_leadgenpopup_headline"><?php printf( __('Headline', 'mta-leadgenpopup') ); ?></label>
          <input type="text" name="mta_leadgenpopup_headline" id="mta_leadgenpopup_headline" value="<?php echo $headline; ?>" />
          <div class="desc"><?php printf( __('Allowed Tags', 'mta-leadgenpopup') ); ?>: <em>span(class), i(class), strong and em</em></div>
        </div>
        <div class="meta-input">
          <label for="mta_leadgenpopup_subheadline"><?php printf( __('Sub Headline', 'mta-leadgenpopup') ); ?></label>
          <input type="text" name="mta_leadgenpopup_subheadline" id="mta_leadgenpopup_subheadline" value="<?php echo $subheadline; ?>" />
          <div class="desc"><?php printf( __('Allowed Tags', 'mta-leadgenpopup') ); ?>: <em>span(class), i(class), strong and em</em></div>
        </div>
        <div class="meta-input">
          <label for="mta_leadgenpopup_text"><?php printf( __('Paragraph Text', 'mta-leadgenpopup') ); ?></label>
          <textarea name="mta_leadgenpopup_text" id="mta_leadgenpopup_text"><?php echo $text; ?></textarea>
          <div class="desc"><?php printf( __('Allowed Tags', 'mta-leadgenpopup') ); ?>: <em>p(class), br, ul(class), ol(class), li(class), h1(class), h2(class),strong, em, i(class) and span(class)</em></div>
        </div>
      </div>

      <div class="form-meta-wrapper">
        <div class="headline-meta-wrapper"><h4><?php printf( __('Form header', 'mta-leadgenpopup') ); ?></h4></div>
        <div class="meta-input">
          <label for="mta_leadgenpopup_form_header_align"><?php printf( __('Form Header Text Alignment', 'mta-leadgenpopup') ); ?></label>
          <select name="mta_leadgenpopup_form_header_align" id="mta_leadgenpopup_form_header_align">
            <option value="align-left" <?php selected( $form_header_align, "align-left" ); ?>><?php printf( __('Left Align', 'mta-leadgenpopup') ); ?></option>
            <option value="align-center" <?php selected( $form_header_align, "align-center" ); ?>><?php printf( __('Center Align', 'mta-leadgenpopup') ); ?></option>
          </select>
        </div>
        <div class="meta-input">
          <label for="mta_leadgenpopup_form_superheadline"><?php printf( __('Form Super Headline', 'mta-leadgenpopup') ); ?></label>
          <input type="text" name="mta_leadgenpopup_form_superheadline" id="mta_leadgenpopup_form_superheadline" value="<?php echo $form_superheadline; ?>" />
          <div class="desc"><?php printf( __('Allowed Tags', 'mta-leadgenpopup') ); ?>: <em>span(class), i(class), strong and em</em></div>
        </div>
        <div class="meta-input">
          <label for="mta_leadgenpopup_form_headline"><?php printf( __('Form Headline', 'mta-leadgenpopup') ); ?></label>
          <input type="text" name="mta_leadgenpopup_form_headline" id="mta_leadgenpopup_form_headline" value="<?php echo $form_headline; ?>" />
          <div class="desc"><?php printf( __('Allowed Tags', 'mta-leadgenpopup') ); ?>: <em>span(class), i(class), strong and em</em></div>
        </div>
        <div class="meta-input">
          <label for="mta_leadgenpopup_form_subheadline"><?php printf( __('Form Sub Headline', 'mta-leadgenpopup') ); ?></label>
          <input type="text" name="mta_leadgenpopup_form_subheadline" id="mta_leadgenpopup_form_subheadline" value="<?php echo $form_subheadline; ?>" />
          <div class="desc"><?php printf( __('Allowed Tags', 'mta-leadgenpopup') ); ?>: <em>span(class), i(class), strong and em</em></div>
        </div>

        <div class="headline-meta-wrapper"><h4><?php printf( __('Form', 'mta-leadgenpopup') ); ?></h4></div>
        <div class="meta-input">
          <label for="mta_leadgenpopup_form_labels"><?php printf( __('Show/Hide Field Labels', 'mta-leadgenpopup') ); ?></label>
          <select name="mta_leadgenpopup_form_labels" id="mta_leadgenpopup_form_labels">
            <option value="show-labels" <?php selected( $form_labels, "show-labels" ); ?>><?php printf( __('Show Labels', 'mta-leadgenpopup') ); ?></option>
            <option value="hide-labels" <?php selected( $form_labels, "hide-labels" ); ?>><?php printf( __('Hide Labels', 'mta-leadgenpopup') ); ?></option>
          </select>
        </div>

        <?php
        $select = '<select name="mta_leadgenpopup_gform_id" id="mta_leadgenpopup_gform_id">';
        $select .= '<option id="">None</option>';
        $forms = RGFormsModel::get_forms( null, 'title' );
        foreach( $forms as $form ):
        $select .= '<option value="' . $form->id . '" ' . selected( $gform_id, $form->id, false ) .' >' . $form->title . '</option>';
        endforeach;
        $select .= '</select>'; ?>
        <div class="meta-input">
          <label for="mta_leadgenpopup_gform_id"><?php printf( __('Select Popup Gravity Form', 'mta-leadgenpopup') ); ?></label>
          <?php echo $select; ?>
        </div>

        <div class="headline-meta-wrapper"><h4><?php printf( __('Form Footer', 'mta-leadgenpopup') ); ?></h4></div>
        <div class="meta-input">
          <label for="mta_leadgenpopup_form_footer_align"><?php printf( __('Footer Footer Text Alignment', 'mta-leadgenpopup') ); ?></label>
          <select name="mta_leadgenpopup_form_footer_align" id="mta_leadgenpopup_form_footer_align">
            <option value="align-left" <?php selected( $form_footer_align, "align-left" ); ?>><?php printf( __('Left Align', 'mta-leadgenpopup') ); ?></option>
            <option value="align-center" <?php selected( $form_footer_align, "align-center" ); ?>><?php printf( __('Center Align', 'mta-leadgenpopup') ); ?></option>
          </select>
        </div>

        <div class="meta-input">
          <label for="mta_leadgenpopup_form_text"><?php printf( __('Paragraph Text', 'mta-leadgenpopup') ); ?></label>
          <textarea name="mta_leadgenpopup_form_text" id="mta_leadgenpopup_form_text"><?php echo $form_text; ?></textarea>
          <div class="desc"><?php printf( __('Allowed Tags', 'mta-leadgenpopup') ); ?>: <em>p(class), br, ul(class), ol(class), li(class), h1(class), h2(class),strong, em, i(class) and span(class)</em></div>
        </div>
      </div>
    </div>
  <?php
  }

  /**
   * Save the meta when the post is saved.
   *
   * @param int $post_id The ID of the post being saved.
   */
  public function save($post_id) {
    /*
     * We need to verify this came from the our screen and with
     * proper authorization,
     * because save_post can be triggered at other times.
     */

    // Check if our nonce is set.
    if (!isset($_POST['mta_leadgenpopup_nonce_check_value']))
      return $post_id;

    $nonce = $_POST['mta_leadgenpopup_nonce_check_value'];

    // Verify that the nonce is valid.
    if (!wp_verify_nonce($nonce, 'mta_leadgenpopup_nonce_check'))
      return $post_id;

    // If this is an autosave, our form has not been submitted,
    //     so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
      return $post_id;

    // Check the user's permissions.
    if ('page' == $_POST['post_type']) {
      if (!current_user_can('edit_page', $post_id))
        return $post_id;
    } else {
      if (!current_user_can('edit_post', $post_id))
        return $post_id;
    }

    /* Arrays of allowed tag for various form input fields */
    $allow_text_only = $this->get_allowed_field_tags();
    $allowed_input_text = $this->get_allowed_field_tags('text');
    $allowed_input_textarea = $this->get_allowed_field_tags('textarea');

    /* OK, its safe for us to save the data now. */
    if( isset( $_POST['mta_leadgenpopup_layout'] ) )
      update_post_meta( $post_id, '_mta_leadgenpopup_layout', wp_kses( $_POST['mta_leadgenpopup_layout']), $allow_text_only);

    if( isset( $_POST['mta_leadgenpopup_trigger'] ) )
      update_post_meta( $post_id, '_mta_leadgenpopup_trigger', wp_kses( $_POST['mta_leadgenpopup_trigger']), $allow_text_only);

    if( isset( $_POST['mta_leadgenpopup_timer'] ) )
      update_post_meta( $post_id, '_mta_leadgenpopup_timer', wp_kses( $_POST['mta_leadgenpopup_timer']), $allow_text_only);

    if( isset( $_POST['mta_leadgenpopup_superheadline'] ) )
      update_post_meta( $post_id, '_mta_leadgenpopup_superheadline', wp_kses( $_POST['mta_leadgenpopup_superheadline']), $allow_text_only);

    if( isset( $_POST['mta_leadgenpopup_headline'] ) )
      update_post_meta( $post_id, '_mta_leadgenpopup_headline',wp_kses( $_POST['mta_leadgenpopup_headline'], $allowed_input_text ) );

    if( isset( $_POST['mta_leadgenpopup_subheadline'] ) )
      update_post_meta( $post_id, '_mta_leadgenpopup_subheadline', wp_kses( $_POST['mta_leadgenpopup_subheadline'], $allowed_input_text ) );

    if( isset( $_POST['mta_leadgenpopup_text'] ) )
      update_post_meta( $post_id, '_mta_leadgenpopup_text', wp_kses( $_POST['mta_leadgenpopup_text'], $allowed_input_textarea ) );

    if( isset( $_POST['mta_leadgenpopup_form_header_align'] ) )
      update_post_meta( $post_id, '_mta_leadgenpopup_form_header_align', wp_kses( $_POST['mta_leadgenpopup_form_header_align']), $allow_text_only);

    if( isset( $_POST['mta_leadgenpopup_form_footer_align'] ) )
      update_post_meta( $post_id, '_mta_leadgenpopup_form_footer_align', wp_kses( $_POST['mta_leadgenpopup_form_footer_align']), $allow_text_only);

    if( isset( $_POST['mta_leadgenpopup_form_labels'] ) )
      update_post_meta( $post_id, '_mta_leadgenpopup_form_labels', wp_kses( $_POST['mta_leadgenpopup_form_labels']), $allow_text_only);

    if( isset( $_POST['mta_leadgenpopup_form_superheadline'] ) )
      update_post_meta( $post_id, '_mta_leadgenpopup_form_superheadline', wp_kses( $_POST['mta_leadgenpopup_form_superheadline']), $allow_text_only);

    if( isset( $_POST['mta_leadgenpopup_form_headline'] ) )
      update_post_meta( $post_id, '_mta_leadgenpopup_form_headline',wp_kses( $_POST['mta_leadgenpopup_form_headline'], $allowed_input_text ) );

    if( isset( $_POST['mta_leadgenpopup_form_subheadline'] ) )
      update_post_meta( $post_id, '_mta_leadgenpopup_form_subheadline', wp_kses( $_POST['mta_leadgenpopup_form_subheadline'], $allowed_input_text ) );

    if( isset( $_POST['mta_leadgenpopup_form_text'] ) )
      update_post_meta( $post_id, '_mta_leadgenpopup_form_text', wp_kses( $_POST['mta_leadgenpopup_form_text'], $allowed_input_textarea ) );

    if( isset( $_POST['mta_leadgenpopup_gform_id'] ) )
      update_post_meta( $post_id, '_mta_leadgenpopup_gform_id', $_POST['mta_leadgenpopup_gform_id'] );
  }

  /*
   * Register the stylesheets for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Mta_Leadgenpopup_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Mta_Leadgenpopup_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/styles.min.css', array(), $this->version, 'all' );

  }

  /**
   * Register the JavaScript for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts() {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Mta_Leadgenpopup_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Mta_Leadgenpopup_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    //loading the admin script in the footer
    wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/scripts.min.js', array( 'jquery' ), $this->version, true );

  }

}
