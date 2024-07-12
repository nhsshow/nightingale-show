<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

//region Plugin Update Checker
require_once get_theme_file_path( 'plugin-update-checker/plugin-update-checker.php' );
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$showUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/nhsshow/nightingale-show/releases/latest/download/info.json',
	__FILE__, //Full path to the main plugin file or functions.php.
	'nightingale-child'
);
//endregion Plugin Update Checker


// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'nightingale-style','nightingale-style','nightingale-page-colours' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

// END ENQUEUE PARENT ACTION




// CUSTOM CODE BELOW

// Replace default logo with NHS Scotland on WP-Login Page
function add_logo_to_login() {
    echo '<h1>&nbsp;</h1><style type="text/css">
        h1 a { background-image:url('.get_stylesheet_directory_uri().'/images/nhs-scotland-logo.svg) !important; width:300px !important;}
    </style>';
}
add_action('login_head', 'add_logo_to_login');



// Register new SECONDARY sidebar for use with Secondary Sidebar template
function secondary_sidebar_widgets_init() {

    register_sidebar( array(
        'name' => 'Secondary Sidebar',
        'id' => 'secondary_sidebar',
        'before_widget' => '<aside class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
        ) );
    }

add_action( 'widgets_init', 'secondary_sidebar_widgets_init' );


// Custom script with no dependencies, enqueued in the footer
add_action('wp_enqueue_scripts', 'nhsscotland_enqueue_custom_js');
function nhsscotland_enqueue_custom_js() {
    wp_enqueue_script('custom', get_stylesheet_directory_uri().'/scripts/custom.js',
    array(), false, true);
}


// Hides WordPress dashboard menu items from users other than sysadmin (UserID = 1).
// Admin users still have access to these pages. All this function does is hides the pages. It does not disable the functionality which can be accessed if the full url is known.
add_action( 'admin_init', 'remove_menu_pages' );
function remove_menu_pages() {

  global $user_ID;

  if ( $user_ID != 1 ) { //your user id

    //Hide COMMENTS
    remove_menu_page('edit-comments.php'); // Comments

    //Hide APPEARANCE
    remove_submenu_page( 'themes.php', 'themes.php' );
    remove_submenu_page( 'themes.php', 'tgmpa-install-plugins' );
    remove_submenu_page( 'themes.php', 'theme-editor.php' );

    //Hide PLUGINS
    remove_submenu_page( 'plugins.php', 'plugin-editor.php' );
    remove_submenu_page( 'plugins.php', 'plugin-install.php' );

    //Hide TOOLS
    remove_menu_page('tools.php');
    remove_submenu_page( 'tools.php', 'tools.php' );
    remove_submenu_page( 'tools.php', 'import.php' );
    remove_submenu_page( 'tools.php', 'export.php' );
    remove_submenu_page( 'tools.php', 'export-personal-data.php' );
    remove_submenu_page( 'tools.php', 'erase-personal-data.php' );

    //Hide SETTINGS
    remove_submenu_page( 'options-general.php', 'options-privacy.php' );
    remove_submenu_page( 'options-general.php', 'options-permalink.php' );
    remove_submenu_page( 'options-general.php', 'options-media.php' );
    remove_submenu_page( 'options-general.php', 'options-discussion.php' );
    remove_submenu_page( 'options-general.php', 'options-writing.php' );
    remove_submenu_page( 'options-general.php', 'svg-support' );
    remove_submenu_page( 'options-general.php', 'mainwp_child_tab' );

    //Hide MegaMenu
    remove_menu_page( 'maxmegamenu' );

    //Hide Maintenance
    remove_menu_page( 'maintenance' );

    //Hide WP Mail SMTP
    remove_menu_page( 'wp-mail-smtp' );
  }
}


// Adds customs styles to the WordPress Dashboard
// Hides the ADD NEW PLUGIN screen and Delete Button from users.
add_action('admin_head', 'hide_add_plugin_screen');

function hide_add_plugin_screen() {

    global $user_ID;
    if ( $user_ID != 1 ) {
        echo '<style>
            .plugin-install-tab-featured {display:none;}
            .plugin-install-tab-featured::before {content: "Please contact nss.showteam@nhs.scot";}
            #setting-error-tgmpa {display: none;}
            .row-actions>.delete {visibility: hidden;}
            }
        </style>';
  }
}


//Replaces Frutiger W01 with Istok Web font
function enqueue_replace_font() {
    // Enqueue the Roboto font from Google Fonts
    wp_enqueue_style('replace-font', 'https://fonts.googleapis.com/css2?family=Istok+Web:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet', false);

    // Add custom CSS to ensure "Istok Web" font-family is used
    $replace_fontcss = "
         body, button, input, select, optgroup, textarea, p, h1, h2, h3, h4, h5, h6, a {
            font-family: 'Istok Web', sans-serif !important;
        }
    ";
    wp_add_inline_style('replace-font', $replace_fontcss);
}

add_action('wp_enqueue_scripts', 'enqueue_replace_font', 20);
