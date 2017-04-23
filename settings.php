<?php defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );

if(!class_exists('Alloy_Essentials_Settings'))
{
  class Alloy_Essentials_Settings
  {
    public function __construct()
    {
      add_action('admin_init', array(&$this, 'admin_init'));
      add_action('admin_menu', array(&$this, 'add_menu'));
    }

    public function activate()
    {
      update_option('time_format', 'H:i');
      update_option('date_format', 'd/m/Y');
      update_option('timezone_string', 'Europe/Amsterdam');
      //set default settings
      if( get_option('sae_upload_size_limit') === false ) update_option('sae_upload_size_limit', 300);
      if( get_option('sae_clean_admin') === false ) update_option('sae_clean_admin', 1);
      if( get_option('sae_clean_admin_header_comments') === false ) update_option('sae_clean_admin_header_comments', 1);
      if( get_option('sae_clean_admin_header_new_content') === false ) update_option('sae_clean_admin_header_new_content', 1);
      if( get_option('sae_hide_all_comment_stuff') === false ) update_option('sae_hide_all_comment_stuff', 1);
      if( get_option('sae_clean_up_dashboard') === false ) update_option('sae_clean_up_dashboard', 1);

      if( get_option('sae_hide_posts') === false ) update_option('sae_hide_posts', 1);
      if( get_option('sae_hide_links') === false ) update_option('sae_hide_links', 1);
      if( get_option('sae_hide_tools') === false ) update_option('sae_hide_tools', 1);

      if( get_option('sae_hide_post_format') === false ) update_option('sae_hide_post_format', 1);
      if( get_option('sae_hide_post_categories') === false ) update_option('sae_hide_post_categories', 0);
      if( get_option('sae_hide_post_tags') === false ) update_option('sae_hide_post_tags', 1);
      if( get_option('sae_hide_post_featured_image') === false ) update_option('sae_hide_post_featured_image', 0);

      if( get_option('sae_jaap_fix_need_jquery') === false ) update_option('sae_jaap_fix_need_jquery', 0);
      if( get_option('sae_jaap_fix_javascript_test') === false ) update_option('sae_jaap_fix_javascript_test', '');
      if( get_option('sae_jaap_fix_javascript') === false ) update_option('sae_jaap_fix_javascript', '');
    }

    public function admin_init()
    {
      // register your plugin's settings
      // upload size limit
      register_setting( 'sae-plugin-settings-group', 'sae_upload_size_limit' );
      // admin
      register_setting( 'sae-plugin-settings-group', 'sae_clean_admin' );
      // admin header
      register_setting( 'sae-plugin-settings-group', 'sae_clean_admin_header_comments' );
      register_setting( 'sae-plugin-settings-group', 'sae_clean_admin_header_new_content' );
      // comments
      register_setting( 'sae-plugin-settings-group', 'sae_hide_all_comment_stuff' );
      // admin dashboard
      register_setting( 'sae-plugin-settings-group', 'sae_clean_up_dashboard' );
      // admin menu
      register_setting( 'sae-plugin-settings-group', 'sae_hide_posts' );
      register_setting( 'sae-plugin-settings-group', 'sae_hide_links' );
      register_setting( 'sae-plugin-settings-group', 'sae_hide_tools' );
      // post options
      register_setting( 'sae-plugin-settings-group', 'sae_hide_post_format' );
      register_setting( 'sae-plugin-settings-group', 'sae_hide_post_categories' );
      register_setting( 'sae-plugin-settings-group', 'sae_hide_post_tags' );
      register_setting( 'sae-plugin-settings-group', 'sae_hide_post_featured_image' );

      // Jaap Fixes
      register_setting( 'sae-plugin-jaap-settings-group', 'sae_jaap_fix_need_jquery' );
      register_setting( 'sae-plugin-jaap-settings-group', 'sae_jaap_fix_javascript_test' );
      register_setting( 'sae-plugin-jaap-settings-group', 'sae_jaap_fix_javascript' );
    }

    public function add_menu()
    {
      $alert = get_option('sae_jaap_fix_javascript_test') != '' && get_option('sae_jaap_fix_javascript_test') != NULL ? '<span class="settings_alert small"></span>' : '';
      add_options_page(
        'Studio Alloy essentials Settings',
        'Alloy Essentials '.$alert,
        'manage_options',
        'Alloy_Essentials',
        array(&$this, 'plugin_settings_page')
      );
    }

    public function plugin_settings_page()
    {
      if(!current_user_can('manage_options'))
      {
        wp_die(__('You do not have sufficient permissions to access this page.'));
      }
      include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
    }
  }
}
