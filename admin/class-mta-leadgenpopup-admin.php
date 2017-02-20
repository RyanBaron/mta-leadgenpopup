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
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $plugin_name       The name of this plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

    add_action('add_meta_boxes', array($this, 'mta_leadgenpopup_metabox'));
    add_action('save_post', array($this, 'save'));
  }

  /**
   * Adds the meta box container.
   */
  public function mta_leadgenpopup_metabox($post_type) {
    //add the metabox to all pages/posts
    add_meta_box('mta_leadgenpopup_section',
                 'Lead Generation Popup',
                 array($this, 'mta_leadgenpopup_page_section_metabox'),
                 $post_type,
                 'normal',
                 'core');
    /*
    $post_types = array('page');

    //limit meta box to certain post types
    if (in_array($post_type, $post_types)) {
      $post_id = isset($_POST['post_ID']) && is_numeric($_POST['post_ID']) && !empty($_POST['post_ID']) ? $_POST['post_ID'] : 0;
      $post_id = isset($_GET['post']) && is_numeric($_GET['post']) && !empty($_GET['post']) ? $_GET['post'] : $post_id;

      //limit meta box to certain template files. (only updates on page load/reload)
      if($post_id) {
        $template_file = get_post_meta($post_id,'_wp_page_template',TRUE);

        switch($template_file) {
          case 'template-firm-landing-page.php':
            add_meta_box('page-leadgenpopup-section',
                         'Page - Lead Generation Popup',
                         array($this, 'mta_leadgenpopup_page_section_metabox'),
                         $post_type,
                         'normal',
                         'core');
            break;
        }
      }
    }
    */
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
      <div class="desc"><?php printf( __('Allowed Tags', 'mta-leadgenpopup') ); ?>: <em>strong, em, i(class) and span(class)</em></div>
    </div>
    <div class="meta-input">
      <label for="mta_leadgenpopup_headline"><?php printf( __('Headline', 'mta-leadgenpopup') ); ?></label>
      <input type="text" name="mta_leadgenpopup_headline" id="mta_leadgenpopup_headline" value="<?php echo $headline; ?>" />
      <div class="desc"><?php printf( __('Allowed Tags', 'mta-leadgenpopup') ); ?>: <em>strong, em, i(class) and span(class)</em></div>
    </div>
    <div class="meta-input">
      <label for="mta_leadgenpopup_subheadline"><?php printf( __('Sub Headline', 'mta-leadgenpopup') ); ?></label>
      <input type="text" name="mta_leadgenpopup_subheadline" id="mta_leadgenpopup_subheadline" value="<?php echo $subheadline; ?>" />
      <div class="desc"><?php printf( __('Allowed Tags', 'mta-leadgenpopup') ); ?>: <em>strong, em, i(class) and span(class)</em></div>
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
      <div class="desc"><?php printf( __('Allowed Tags', 'mta-leadgenpopup') ); ?>: <em>strong, em, i(class) and span(class)</em></div>
    </div>
    <div class="meta-input">
      <label for="mta_leadgenpopup_form_headline"><?php printf( __('Form Headline', 'mta-leadgenpopup') ); ?></label>
      <input type="text" name="mta_leadgenpopup_form_headline" id="mta_leadgenpopup_form_headline" value="<?php echo $form_headline; ?>" />
      <div class="desc"><?php printf( __('Allowed Tags', 'mta-leadgenpopup') ); ?>: <em>strong, em, i(class) and span(class)</em></div>
    </div>
    <div class="meta-input">
      <label for="mta_leadgenpopup_form_subheadline"><?php printf( __('Form Sub Headline', 'mta-leadgenpopup') ); ?></label>
      <input type="text" name="mta_leadgenpopup_form_subheadline" id="mta_leadgenpopup_form_subheadline" value="<?php echo $form_subheadline; ?>" />
      <div class="desc"><?php printf( __('Allowed Tags', 'mta-leadgenpopup') ); ?>: <em>strong, em, i(class) and span(class)</em></div>
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
    $allow_text_only = array();

    $allowed_input_text = array(
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

    $allowed_input_textarea = array(
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

    /* OK, its safe for us to save the data now. */
    if( isset( $_POST['mta_leadgenpopup_layout'] ) )
      update_post_meta( $post_id, '_mta_leadgenpopup_layout', wp_kses( $_POST['mta_leadgenpopup_layout']), $allow_text_only);

    if( isset( $_POST['mta_leadgenpopup_trigger'] ) )
      update_post_meta( $post_id, '_mta_leadgenpopup_trigger', wp_kses( $_POST['mta_leadgenpopup_trigger']), $allow_text_only);

    if( isset( $_POST['mta_leadgenpopup_timer'] ) )
      update_post_meta( $post_id, '_mta_leadgenpopup_timer', wp_kses( $_POST['mta_leadgenpopup_timer']), $allow_text_only);
    /*
     * ToDo: find out why the wp_kses is prevent the superheadline from saving
     */
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
    /*
     * ToDo: find out why the wp_kses is prevent the superheadline from saving
     */
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

    /*
    print_r($_POST['mta_leadgenpopup_form_superheadline']);
    echo "<br><br>";
    print_r($_POST['mta_leadgenpopup_subheadline']);
    die('kill it here');
    */
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
