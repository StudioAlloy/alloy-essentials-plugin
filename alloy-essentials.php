<?php defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );
/*
Plugin Name: Alloy Essentials
Plugin URI:
Github Plugin URI: https://github.com/StudioAlloy/studio-alloy-essentials
Description: All the essential settings for a standard Studio Alloy wordpress project
Version: 0.0.2
Author: Studio Alloy
Author URI: https://studioalloy.nl/
License: GPL2
*/
/*
Copyright 2017 Studio Alloy  (email : contact@studioalloy.nl)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if(!class_exists('Alloy_Essentials')) {
  class Alloy_Essentials {
    private $Alloy_Essentials_Settings;

    public function __construct() {
      require_once(sprintf('%s/settings.php', dirname(__FILE__)));
      $this->Alloy_Essentials_Settings = new Alloy_Essentials_Settings();
      add_filter('plugin_action_links_'.plugin_basename( __FILE__ ), array( $this, 'plugin_settings_link' ));
    }

    public function run() {
      //Upload size limit
      add_filter('upload_size_limit', array( $this, 'set_quota_upload_size' ));
      //admin clean up
      add_action( 'wp_before_admin_bar_render', array( $this, 'clean_up_admin_header' ) );
      add_action( 'admin_enqueue_scripts', array( $this, 'load_custom_wp_admin_style' ) );
      remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
      add_filter( 'admin_bar_menu', array( $this, 'remove_howdy' ) );
      if( get_option('sae_clean_up_dashboard') ) {
        add_action( 'wp_dashboard_setup', array( $this, 'clean_up_dashboard' ) );
      }
      if( get_option('sae_clean_admin') ) {
        add_action( 'admin_bar_menu', array( $this, 'clean_up_admin_bar' ), 0 );
        add_filter( 'admin_footer_text', array( $this, 'change_admin_footer' ) );
        add_action( 'login_enqueue_scripts', array( $this, 'change_login_logo' ) );
        add_filter( 'user_contactmethods', array( $this, 'remove_contactmethods' ) );
      }
      //disable all comments
      if( get_option('sae_hide_all_comment_stuff') ) {
        add_action('admin_init', array( $this, 'disable_comments_post_types_support' ));
        add_filter('comments_open', array( $this, 'disable_comments_status' ), 20, 2);
        add_filter('pings_open', array( $this, 'disable_comments_status' ), 20, 2);
        add_filter('comments_array', array( $this, 'disable_comments_hide_existing_comments' ), 10, 2);
        add_action('admin_menu', array( $this, 'disable_comments_admin_menu' ));
        add_action('admin_init', array( $this, 'disable_comments_admin_menu_redirect' ));
        add_action('admin_init', array( $this, 'disable_comments_dashboard' ));
        add_action('init', array( $this, 'disable_comments_admin_bar' ));
      }
      // for everybody exept administrator
      add_action( 'admin_menu', array( $this, 'remove_menus' ) );
      add_action( 'do_meta_boxes', array( $this, 'remove_featured_image' ) );
      //jaap fixes
      // if( get_option('sae_jaap_fix_need_jquery') ) add_action( 'wp_head' , array( $this, 'add_jquery_script' ) );
      // add_action( 'wp_footer', array( $this, 'add_jaap_fix_javascript' ), 100 );
      // add_action( 'wp_footer', array( $this, 'add_jaap_fix_javascript_test' ), 100 );
    }

    //################################################################

    // upload size limit
    public function set_quota_upload_size() {
      $sae_upload_size_limit = esc_attr( get_option('sae_upload_size_limit') );
      if($sae_upload_size_limit <= 0 || $sae_upload_size_limit == '' || $sae_upload_size_limit == NULL) $sae_upload_size_limit = 33555;
      return $sae_upload_size_limit*1024;
    }
    // {END} upload size limit

    //################################################################

    // admin clean up
    public function clean_up_admin_bar( $wp_admin_bar ) {
      $wp_admin_bar->add_menu(array('id' => 'sa-logo',
      'title'  =>  '<img src="//studioalloy.nl/img/alloy-logo.svg" style="height: 20px; width: 20px; margin-top:5px;">' ,
      'href'  => get_site_url(),
    ));

    if(is_admin()) {
      $wp_admin_bar->add_menu(array('id' => 'go-to-homepage',
      'title' => '&#10094; &nbsp; Naar de website',
      'href'  => get_site_url(),
      'meta'  => array( 'class' => 'go-to-homepage' )
    ));
  } else {
    $wp_admin_bar->add_menu(array('id' => 'go-to-dashboard',
    'title' => '&#10094; &nbsp; Naar het dashboard',
    'href'  => get_admin_url(),
    'meta'  => array( 'class' => 'go-to-dashboard' )
  ));
}

}

public function load_custom_wp_admin_style() {
  wp_register_style( 'custom_wp_admin_css', plugin_dir_url( __FILE__ ) . '/assets/css/sae_admin_style.css', false, '1.0.0' );
  wp_enqueue_style( 'custom_wp_admin_css' );
}

public function clean_up_admin_header() {
  global $wp_admin_bar;
  if(get_option('sae_clean_admin')){
    $wp_admin_bar->remove_menu('wp-logo');
    $wp_admin_bar->remove_menu('site-name');
    $wp_admin_bar->remove_menu('updates');
  }
  if(get_option('sae_clean_admin_header_comments') || get_option('sae_hide_all_comment_stuff')) $wp_admin_bar->remove_menu('comments');
  if(get_option('sae_clean_admin_header_new_content')) $wp_admin_bar->remove_menu('new-content');
}

public function change_admin_footer() {
  echo 'Deze website is gemaakt door <a href="//studioalloy.nl" target="_blank">Studio Alloy</a>';
}

public function change_login_logo() { ?>
  <style type="text/css">
  .login h1 a {
    background-image: url(//studioalloy.nl/img/alloy-logo.svg);
  }
  </style>
<?php }

public function remove_contactmethods($contactmethods) {
  unset($contactmethods['aim']);
  unset($contactmethods['website']);
  unset($contactmethods['yim']);
  unset($contactmethods['jabber']);
  return $contactmethods;
}

public function remove_howdy( $wp_admin_bar ) {
  $my_account=$wp_admin_bar->get_node('my-account');
  $newtitle = str_replace( 'Howdy,', '', $my_account->title );
  $wp_admin_bar->add_node( array(
    'id' => 'my-account',
    'title' => $newtitle,
  ) );
}
//{END} admin clean up

//################################################################

// //jaap fixes
//
// public function add_jaap_fix_javascript()
// {
//   $script = get_option('sae_jaap_fix_javascript');
//   if($script != '' || $script != NULL)
//   {
//     echo '<script type="text/javascript">';
//     echo $script;
//     echo '</script>';
//   }
// }

// public function add_jaap_fix_javascript_test()
// {
//   $script = get_option('sae_jaap_fix_javascript_test');
//   if(($script != '' || $script != NULL) && current_user_can( 'manage_options' ))
//   {
//     echo '<script type="text/javascript">';
//     echo $script;
//     echo 'document.body.innerHTML += \'<div style="position:absolute;right:0;bottom:0;width:auto;height:auto;opacity:0.5;z-index:100;background:#f00;color:#fff;font-size: 13px;padding:2px 10px;cursor:pointer;" onclick="this.parentNode.removeChild(this);"> ! Javascript only for admin ! </div>\'';
//     echo '</script>';
//   }
// }

// public function add_jquery_script() {
//   wp_enqueue_script( 'jquery' );
// }

//{END} jaap fixes

//################################################################

//remove stuff only for other than administrators

public function remove_menus() {
  if(!current_user_can( 'manage_options' )) {
    remove_menu_page( 'profile.php' );
    if(get_option('sae_hide_posts')) remove_menu_page( 'edit.php' );
    if(get_option('sae_hide_links')) remove_menu_page( 'link-manager.php' );
    if(get_option('sae_hide_tools')) remove_menu_page( 'tools.php' );
    if(get_option('sae_hide_post_format')) remove_meta_box( 'formatdiv','post','normal' );
    if(get_option('sae_hide_post_categories')) remove_meta_box( 'categorydiv','post','normal' );
    if(get_option('sae_hide_post_tags')) remove_meta_box( 'tagsdiv-post_tag','post','normal' );
  }
}

public function remove_featured_image() {
  if(get_option('sae_hide_post_featured_image')) remove_meta_box( 'postimagediv','post','side' );
}

//{END} remove stuff only for other than administrators

//################################################################

//disable all comments
/*
Code found on : https://www.dfactory.eu/turn-off-disable-comments/
*/
public function disable_comments_post_types_support() {
  $post_types = get_post_types();
  foreach ($post_types as $post_type) {
    if(post_type_supports($post_type, 'comments')) {
      remove_post_type_support($post_type, 'comments');
      remove_post_type_support($post_type, 'trackbacks');
    }
  }
}
// Close comments on the front-end
public function disable_comments_status() {
  return false;
}
// Hide existing comments
public function disable_comments_hide_existing_comments($comments) {
  $comments = array();
  return $comments;
}
// Remove comments page in menu
public function disable_comments_admin_menu() {
  remove_menu_page('edit-comments.php');
  remove_submenu_page('options-general.php', 'options-discussion.php');
}
// Redirect any user trying to access comments page
public function disable_comments_admin_menu_redirect() {
  global $pagenow;
  if ($pagenow === 'edit-comments.php') {
    wp_redirect(admin_url()); exit;
  }
}
// Remove comments metabox from dashboard
public function disable_comments_dashboard() {
  remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
// Remove comments links from admin bar
public function disable_comments_admin_bar() {
  if (is_admin_bar_showing()) remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
}

//{END} disable all comments

//################################################################

// clean up Dashboard
public function clean_up_dashboard() {
  wp_add_dashboard_widget(
    'Alloy_Essentials_widget',
    'Alloy Essentials widget',
    array( $this, 'dashboard_widget_function' )
  );
  // add_filter('screen_options_show_screen', false);
  remove_action( 'welcome_panel', 'wp_welcome_panel' );
  remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
  // remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');
}

public function dashboard_widget_function() {
  global $current_user;
  wp_get_current_user();
  include(sprintf("%s/templates/dashboard_widget.php", dirname(__FILE__)));
}

//{END} clean up Dashboard

//################################################################

public function plugin_settings_link($links) {
  $settings_link = '<a href="options-general.php?page=Alloy_Essentials">' . __( 'Settings' ) . '</a>';
  array_push( $links, $settings_link );
  return $links;
}
}
}

if(class_exists('Alloy_Essentials')) {
  register_activation_hook(__FILE__, array('Alloy_Essentials_Settings', 'activate'));
  $Alloy_Essentials = new Alloy_Essentials();
  $Alloy_Essentials->run();
}

function alloy_login_logo() { ?>
  <style type="text/css">
  #login h1 a, .login h1 a {
    background-image: url('//studioalloy.nl/img/alloy-logo.svg');
    height:95px;
    width:320px;
    background-size: 320px 95px;
    background-repeat: no-repeat;
    padding-bottom: 0;
  }
  </style>
<?php }
add_action( 'login_enqueue_scripts', 'alloy_login_logo' );

function alloy_hide_wp_notifications() {
  if (!current_user_can('administrator')) { // checks to see if current user can update plugins
    add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_version_check' );" ), 2 ); // Hide Wordpress version check
    add_filter( 'pre_option_update_core', create_function( '$a', "return null;" ) ); // Hide Wordpress update message
    remove_action( 'admin_notices', 'update_nag', 3 ); // Hide Wordpress update message
    remove_action( 'admin_notices', 'woothemes_updater_notice' ); // Hide WooCommerce update message
    // WooCommerce is really annoying and would not let me hide there messages for non admins, so there is a dirty CSS hack.
    ?> <style type="text/css">
    #message.updated.woocommerce-message {
      display: none;
    }
    </style> <?php
  }
}
add_action( 'admin_head', 'alloy_hide_wp_notifications' );

function posp_enqueue_scripts() {
  global $pagenow;

  // if ( is_admin() && ($pagenow == 'post.php' || $pagenow == 'post-new.php') ) {
  ?>
    <script type="text/javascript">
    jQuery(function(){

      if(jQuery( window ).width() > 800) {
        var posp_container = "<div class='posp_container"+(isRtl==1 ? " posp_container_rtl":"")+"'></div>";
        var posp_status = 0;

        jQuery("body").append(posp_container);
        jQuery("#publish").clone().removeAttr("id").appendTo(".posp_container");

        jQuery(window).scroll(function ()
        {
          if (jQuery(window).scrollTop() >= jQuery("#submitdiv").offset().top + jQuery("#submitdiv").height() - 21)
          {
            if (posp_status == 0)
            {
              posp_status = 1;
              jQuery(".posp_container").fadeIn("slow");
              jQuery(".posp_container").css("width", (jQuery(".posp_container input").width() + 47 < 80 ? 82 : jQuery(".posp_container input").width() + 47) );
            }

          } else {
            if (posp_status == 1)
            {
              posp_status = 0;
              jQuery(".posp_container").fadeOut("slow");
            }
          }
        });
      }

      jQuery(".posp_container input").click(function() {
        jQuery(this).addClass("disabled");
        jQuery("#publish").trigger("click");
      });

    });
    </script>
  <?php
// }
}
add_action( 'admin_head', 'posp_enqueue_scripts', 20 );
