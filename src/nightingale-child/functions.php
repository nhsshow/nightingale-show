<?php
// Exit if accessed directly
if (!defined("ABSPATH")) {
    exit();
}

/**
 *
 * WARNING: DO NOT MAKE EDITS TO THIS FILE.
 *
 * This file is automatically updated from a central repository on a regular basis.
 * Any direct changes made here WILL be overwritten and lost.
 *
 * If you wish to add theme related customisations, we recommend that you create a child theme under nightingale-show-child.
 * CONTACT: nss.showteam@nhs.scot
 *
 *
 * NHS Scotland SWO Custom Theme Functions:
 *
 * 1. Plugin Update Checker
 * 2. Enqueue Custom Stylesheets
 * 3. Login Page Branding
 * 4. Admin Bar Logo
 * 5. Admin Bar Cleanup
 * 6. Add Link to Admin Bar
 * 7. Remove Greeting
 * 8. Register Secondary Sidebar
 * 9. Enqueue Footer JavaScript
 * 10. Restrict Dashboard Menu
 * 11. Plugin Warning
 * 12. Font Replacement
 * 13. Add Meta Tags
 * 14. Clean Up Dashboard Widgets
 * 15. Disable Comments
*/

//region Load Composer tools - Plugin Update Checker / wp-dependency-installer
require_once get_theme_file_path( 'vendor/autoload.php' );
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

remove_all_actions( 'tgmpa_register' ); // Remove parent dependency management
add_action( 'plugins_loaded', function() use ($plugins) {
	// Setup Update Checker
	$showUpdateChecker = PucFactory::buildUpdateChecker(
		'https://github.com/nhsshow/nightingale-show/releases/latest/download/info.json',
		__FILE__, //Full path to the main plugin file or functions.php.
		'nightingale-show'
	);

	// Setup plugin dependencies
	WP_Dependency_Installer::instance( __DIR__ )->run();
});

//endregion

//region Enqueue "custom.css" file
function enqueue_custom_stylesheets() {
    // Enqueue styles.css first
    wp_enqueue_style('main-styles', get_stylesheet_uri());
    // Enqueue custom.css and set styles.css as a dependency
    wp_enqueue_style('custom-styles', get_stylesheet_directory_uri() . '/custom.css', array('main-styles'));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_stylesheets');
//endregion

//region Login Page Branding - Replace WordPress logo with NHS Scotland logo on login screen
function add_logo_to_login()
    {
        echo '<h1>&nbsp;</h1><style type="text/css">
            h1 a { background-image:url(' .
            get_stylesheet_directory_uri() .
            '/images/nhs-scotland-logo.svg) !important; width:300px !important;}
        </style>';
    }
add_action("login_head", "add_logo_to_login");
//endregion

//region Admin Bar Logo - Replace default logo in admin bar with white NHS Scotland logo
function custom_admin_bar_logo() {
    $custom_logo_url = get_stylesheet_directory_uri() . '/images/nhs-scotland-logo--white.svg';
    ?>
    <style type="text/css">
        #wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon:before {
            background: url('<?php echo esc_url($custom_logo_url); ?>') no-repeat center center !important;
            background-size: contain !important;
            color: transparent !important;
            font-size: 28px;
        }
    </style>
    <?php
}
add_action('wp_before_admin_bar_render', 'custom_admin_bar_logo');
//endregion

//region Admin Bar Cleanup - Remove unnecessary admin bar items
function customize_admin_bar() {
    global $wp_admin_bar;

    // Remove specific admin bar items.
    $wp_admin_bar->remove_node('about');
    $wp_admin_bar->remove_node('comments');
    $wp_admin_bar->remove_node('contribute');
    $wp_admin_bar->remove_node('feedback');
    $wp_admin_bar->remove_node('support');
    $wp_admin_bar->remove_node('learn');
    $wp_admin_bar->remove_node('wporg');
}
add_action('wp_before_admin_bar_render', 'customize_admin_bar');
//endregion

//region Add Link to Admin Bar - Add "SHOW Support" link in admin bar dropdown
function customize_admin_bar_logo_dropdown() {
    global $wp_admin_bar;

    // Add a top-level menu item under the WordPress logo dropdown
    $wp_admin_bar->add_node(array(
        'id'    => 'custom-logo-menu', // Unique ID for the top-level menu item
        'title' => 'SHOW Support', // The title of the menu item
        'href'  => 'https://www.showsupport.scot.nhs.uk', // URL for the menu item
        'parent'=> 'wp-logo', // Parent node ID for the WordPress logo
        'meta'  => array(
            'class' => 'custom-logo-class', // Optional CSS class
            'title' => 'SHOW Support', // Optional title attribute
            'target' => '_blank', // Opens the link in a new window or tab
        ),
    ));

    // Output custom CSS for styling the admin bar items
    echo '<style>
        #wpadminbar #wp-admin-bar-custom-logo-menu > .ab-item {
            background: #0073aa; /* Custom background color for the top-level item */
            color: #fff; /* Custom text color */
        }
        #wpadminbar #wp-admin-bar-custom-logo-menu > .ab-item:hover {
            background: #005177; /* Change background on hover */
        }
    </style>';
}
add_action('admin_bar_menu', 'customize_admin_bar_logo_dropdown', 999);
//endregion

//region Admin Bar Cleanup - Remove "Howdy" greeting from admin bar
function remove_howdy_text($wp_admin_bar) {
    $node = $wp_admin_bar->get_node('my-account');

    if ($node) {
        $wp_admin_bar->add_node(array(
            'id'    => 'my-account',
            'title' => str_replace('Howdy, ', '', $node->title),
            'href'  => $node->href,
            'meta'  => $node->meta
        ));
    }
}
add_action('admin_bar_menu', 'remove_howdy_text', 999);
//endregion

//region Register Sidebar - Add "Secondary" sidebar for "Secondary" template widget areas
function secondary_sidebar_widgets_init()
{
    register_sidebar([
        "name" => "Secondary Sidebar",
        "id" => "secondary_sidebar",
        "before_widget" => '<aside class="widget %2$s">',
        "after_widget" => "</aside>",
        "before_title" => '<h2 class="widget-title">',
        "after_title" => "</h2>",
    ]);
}
add_action("widgets_init", "secondary_sidebar_widgets_init");
//endregion

//region Enqueue Custom JavaScript - Add custom.js file to site footer
function nhsscotland_enqueue_custom_js()
{
    wp_enqueue_script(
        "custom", // Handle for the script.
        get_stylesheet_directory_uri() . "/scripts/custom.js", // URL to the JavaScript file.
        [], // No dependencies for this script.
        false, // No version number specified.
        true // Load the script in the footer.
    );
}
add_action("wp_enqueue_scripts", "nhsscotland_enqueue_custom_js");
//endregion

//region Restrict Dashboard Menu - Hide menu items for non-sysadmin (UserID = 1) users
//NOTE: Admin users still have access to these pages. All this function does is hides the pages. It does not disable the functionality which can be accessed if the full url is known.
function remove_menu_pages()
{
    if (wp_get_current_user()->user_login !== 'sysadmin') {
        //your user id

        //Hide COMMENTS
        remove_menu_page("edit-comments.php"); // Comments

        //Hide APPEARANCE
        remove_submenu_page("themes.php", "themes.php");
        remove_submenu_page("themes.php", "tgmpa-install-plugins");
        remove_submenu_page("themes.php", "theme-editor.php");

        //Hide PLUGINS
        remove_submenu_page("plugins.php", "plugin-editor.php");
        remove_submenu_page("plugins.php", "plugin-install.php");

        //Hide TOOLS
        remove_menu_page("tools.php");
        remove_submenu_page("tools.php", "tools.php");
        remove_submenu_page("tools.php", "import.php");
        remove_submenu_page("tools.php", "export.php");
        remove_submenu_page("tools.php", "export-personal-data.php");
        remove_submenu_page("tools.php", "erase-personal-data.php");

        //Hide SETTINGS
        remove_submenu_page("options-general.php", "options-privacy.php");
        remove_submenu_page("options-general.php", "options-permalink.php");
        remove_submenu_page("options-general.php", "options-media.php");
        remove_submenu_page("options-general.php", "options-discussion.php");
        remove_submenu_page("options-general.php", "options-writing.php");
        remove_submenu_page("options-general.php", "svg-support");
        remove_submenu_page("options-general.php", "mainwp_child_tab");
        remove_submenu_page( 'options-general.php', 'show-settings' ); //If "SHOW Settings" Plugin is activated

        //Hide MegaMenu
        remove_menu_page("maxmegamenu");

        //Hide Maintenance
        remove_menu_page("maintenance");

        //Hide WP Mail SMTP
        remove_menu_page("wp-mail-smtp");
    }
}
add_action("admin_init", "remove_menu_pages");
//endregion

//region Plugin Warning - Display warning messages for plugin installations
function showpluginnotice_admin_notices() {

    $current_user = wp_get_current_user();

    if ($current_user->user_login === 'sysadmin') {
        return; // Exit if the user is "sysadmin"
    }

    $screen = get_current_screen();

    if ($screen->id !== "plugin-install") {
        return; // Exit if not on the Add Plugins page
    }

    $first_name = $current_user->user_firstname;
    $username = $current_user->user_login;
    $display_name = !empty($first_name) ? $first_name : $username;
    $user_email = $current_user->user_email;
    ?>
    <div id="showpluginnotice-modal" class="showpluginnotice-modal">
        <div class="showpluginnotice-modal-content">
            <h2 class="warning-heading">PLUGIN INSTALLATION NOTICE</h2>
            <p>Dear <strong><?php echo $display_name; ?></strong>, while plugins can enhance your site, it's important to be aware that they also carry some risks.</p>
            <p>Plugins that are poorly maintained (or those from untrusted sources) may introduce security vulnerabilities, cause conflicts with other plugins, or result in data loss. We recommend reviewing the plugin’s ratings, reviews, and developer credentials carefully before proceeding.</p>
            <p>SHOW does not endorse or guarantee the safety, functionality, or quality of any third-party plugins you may choose to install. Further information can be found on our <a class="warning-link" target="_blank" href="https://www.showsupport.scot.nhs.uk/plugin-policy">Plugin Policy</a> page.</p>
            <p>By proceeding, you confirm that you have the site owner's permission to install and are aware of and accept the associated risks.</p>
            <button id="showpluginnotice-modal-accept" class="showpluginnotice-modal-accept" disabled>I understand the risks (<span id="countdown">10</span>)</button>
        </div>
    </div>
    <div class="notice notice-error">
        <p><strong>Important</strong>: Please refer to SHOW's <a href="https://www.showsupport.scot.nhs.uk/plugin-policy" target="_blank">Plugin Policy</a> page <strong>PRIOR</strong> to installing plugins.</p>
    </div>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('showpluginnotice-modal');
            var acceptButton = document.getElementById('showpluginnotice-modal-accept');
            var countdownElement = document.getElementById('countdown');
            var countdown = 10;

            // Show the modal
            modal.style.display = 'block';

            // Update the countdown every second
            var countdownInterval = setInterval(function() {
                countdown--;
                countdownElement.textContent = countdown;

                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    acceptButton.disabled = false;
                    acceptButton.textContent = "I understand the risks";
                }
            }, 1000);

            // When the user clicks on "Accept" button, close the modal
            acceptButton.onclick = function() {
                modal.style.display = 'none';
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }
        });
    </script>
    <style>
        .showpluginnotice-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .showpluginnotice-modal-content {
            background-color: #172ab7;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 85%;
            max-width: 650px;
            text-align: center;
            border-radius: 10px;
            color: white;
        }
        .showpluginnotice-modal-close {
            color: white;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .showpluginnotice-modal-close:hover,
        .showpluginnotice-modal-close:focus {
            color: #ccc;
            text-decoration: none;
            cursor: pointer;
        }
        .showpluginnotice-modal-accept {
            background-color: #fff;
            color: #172ab7;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            font-weight: bold;
        }
        .showpluginnotice-modal-accept:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .showpluginnotice-modal-accept:hover:enabled {
            background-color: #f2f2f2;
        }

        .warning-heading {
            color: white;
        }

        .warning-link { color: white; }
    </style>
    <?php
}
add_action('admin_notices', 'showpluginnotice_admin_notices');
//endregion

//region Font Replacement - Change font from Frutiger W01 to Istok Web
function enqueue_replace_font()
{
    // Enqueue the Istok Web font from Google Fonts
    wp_enqueue_style(
        "replace-font",
        'https://fonts.googleapis.com/css2?family=Istok+Web:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet',
        false
    );

    // Add custom CSS to ensure "Istok Web" font-family is used
    $replace_fontcss = "
         body, button, input, select, optgroup, textarea, p, h1, h2, h3, h4, h5, h6, a {
            font-family: \"Istok Web\", sans-serif !important;
        }
    ";
    wp_add_inline_style("replace-font", $replace_fontcss);
}
add_action("wp_enqueue_scripts", "enqueue_replace_font", 20);
//endregion

//region Add Meta Tags - Insert custom meta tags and NHS Scotland logo into <head>
function add_custom_meta_tags()
{
    if (is_single() || is_page()) {
        global $post;

        // Ensure we have a valid post object
        if (!is_object($post)) {
            return;
        }

        $description = get_bloginfo("description");
        $title = get_the_title($post->ID);
        $url = get_permalink($post->ID);
        $default_image =
            get_stylesheet_directory_uri() . "/images/nhs-scotland-logo.svg"; // URL of your default image

        // Get the featured image if available
        $image = $default_image;
        if (has_post_thumbnail($post->ID)) {
            $image = get_the_post_thumbnail_url($post->ID, "full");
        }

        // Print the meta tags
        echo '<meta name="description" content="' .
            esc_attr($description) .
            '">' .
            "\n";
        echo '<meta property="og:title" content="' .
            esc_attr($title) .
            '">' .
            "\n";
        echo '<meta property="og:description" content="' .
            esc_attr($description) .
            '">' .
            "\n";
        echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";
        echo '<meta property="og:image" content="' .
            esc_url($image) .
            '">' .
            "\n";
    }
}
add_action("wp_head", "add_custom_meta_tags");
//endregion

//region Clean Up Dashboard Widgets - Remove unnecessary dashboard widgets
function remove_dashboard_widgets()
{
    remove_meta_box("dashboard_right_now", "dashboard", "normal"); // Right Now
    remove_meta_box("dashboard_recent_comments", "dashboard", "normal"); // Recent Comments
    remove_meta_box("dashboard_incoming_links", "dashboard", "normal"); // Incoming Links
    remove_meta_box("dashboard_plugins", "dashboard", "normal"); // Plugins
    remove_meta_box("dashboard_quick_press", "dashboard", "side"); // Quick Press
    remove_meta_box("dashboard_recent_drafts", "dashboard", "side"); // Recent Drafts
    remove_meta_box("dashboard_primary", "dashboard", "side"); // WordPress blog
    remove_meta_box("dashboard_secondary", "dashboard", "side"); // Other WordPress News
}
add_action("wp_dashboard_setup", "remove_dashboard_widgets");
//endregion

//region Disable support for comments and trackbacks in post types
function disable_comments_for_non_sysadmin() {
    $current_user = wp_get_current_user();
    if ($current_user->user_login !== 'sysadmin') {
        // Disable support for comments and trackbacks in post types
        function disable_comments_post_types_support() {
            $post_types = get_post_types();
            foreach ($post_types as $post_type) {
                if (post_type_supports($post_type, 'comments')) {
                    remove_post_type_support($post_type, 'comments');
                    remove_post_type_support($post_type, 'trackbacks');
                }
            }
        }
        add_action('admin_init', 'disable_comments_post_types_support');

        // Close comments on the front-end
        function disable_comments_status() {
            return false;
        }
        add_filter('comments_open', 'disable_comments_status', 20, 2);
        add_filter('pings_open', 'disable_comments_status', 20, 2);

        // Hide existing comments
        function disable_comments_hide_existing_comments($comments) {
            return array();
        }
        add_filter('comments_array', 'disable_comments_hide_existing_comments', 10, 2);

        // Redirect any user trying to access comments page
        function disable_comments_admin_menu_redirect() {
            global $pagenow;
            if ($pagenow === 'edit-comments.php' || $pagenow === 'comment.php') {
                wp_redirect(admin_url());
                exit;
            }
        }
        add_action('admin_init', 'disable_comments_admin_menu_redirect');

        // Remove comments metabox from dashboard
        function disable_comments_dashboard() {
            remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
        }
        add_action('wp_dashboard_setup', 'disable_comments_dashboard');

        // Remove comments links from admin bar
        function disable_comments_admin_bar() {
            if (is_admin_bar_showing()) {
                remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
            }
        }
        add_action('init', 'disable_comments_admin_bar');
    }
}
add_action('init', 'disable_comments_for_non_sysadmin');
//endregion


//region Fix for NHS Blocks
// NHS Blocks does not work properly with non-Nightingale themes; we do some fixing here.
remove_action( 'init', 'nhsblocks_register_style' );

remove_action( 'wp_enqueue_scripts', 'nhsblocks_enqueue_style' );

remove_action( 'wp_footer', 'nhsblocks_hero_footer' );
function nhsblocks_hero_footer_override() {
	$theme     = wp_get_theme(); // gets the current theme.
	$scriptout = "<script>
        const heroBlock = document.querySelector('.wp-block-nhsblocks-heroblock');
        const tabbedTabs = document.querySelector('.nhsuk-bordered-tabs-container');
        if ((heroBlock)) {
            matches = heroBlock.matches ? heroBlock.matches('.wp-block-nhsblocks-heroblock') : heroBlock.msMatchesSelector('.wp-block-nhsblocks-heroblock');
            if (matches === true) {
                const mainContent = document.querySelector('main');
                const contentInner = document.querySelector('#contentinner');
                const wholeDoc = document.querySelector('body');
                const breadCrumb = document.querySelector('.nhsuk-breadcrumb');
                const articleTitle = document.querySelector('.entry-header');
                const sectionTitle = wholeDoc.querySelector('#nhsuk-tabbed-title');
                const tabbedTabs = document.querySelector('.nhsuk-bordered-tabs-container');
                mainContent.insertBefore(heroBlock, contentInner);
                articleTitle.style.display = 'none';
                mainContent.style.paddingTop = '0';
                if (tabbedTabs) {
                    mainContent.insertBefore(tabbedTabs, contentInner);
                    heroBlock.style.borderBottom = '70px solid white';
                    heroBlock.style.marginBottom = 'none';
                } else {
                    heroBlock.style.marginBottom = '70px';
                }
                if (breadCrumb) {
                    wholeDoc.removeChild(breadCrumb);
                }
                if (sectionTitle) {
                    removeElements(document.querySelectorAll('#nhsuk-tabbed-title'));
                }
            }
        } else if (tabbedTabs) {
            const mainContent = document.querySelector('main');
            const contentInner = document.querySelector('#contentinner');
            const wholeDoc = document.querySelector('body');
            const breadCrumb = document.querySelector('.nhsuk-breadcrumb');
            const articleTitle = document.querySelector('.entry-header');
            const sectionTitle = wholeDoc.querySelector('#nhsuk-tabbed-title');
            mainContent.insertBefore(tabbedTabs, contentInner);
            if (breadCrumb) {
                wholeDoc.removeChild(breadCrumb);
            }
            if (sectionTitle) {
                removeElements(document.querySelectorAll('#nhsuk-tabbed-title'));
            }
            articleTitle.style.marginTop = '20px';
            mainContent.style.paddingTop = '0';
        }
        // Page Link JS
        const careCardWarning = document.querySelector('.nhsuk-care-card.is-style-warning-callout');
        if ((careCardWarning)) {
            const visuallyHidden = careCardWarning.querySelector('.nhsuk-u-visually-hidden');
            jQuery(visuallyHidden).html('Warning advice: ');
        }
        const careCardUrgent = document.querySelector('.nhsuk-care-card.is-style-urgent');
        if ((careCardUrgent)) {
            const visuallyHidden = careCardUrgent.querySelector('.nhsuk-u-visually-hidden');
            jQuery(visuallyHidden).html('Urgent advice: ');
        }
        const careCardImmediate = document.querySelector('.nhsuk-care-card.is-style-immediate');
        if ((careCardImmediate)) {
            const visuallyHidden = careCardImmediate.querySelector('.nhsuk-u-visually-hidden');
            jQuery(visuallyHidden).html('Immediate action required: ');
        }

        (function () {
            let url = window.location.href.split(/[?#]/)[0];
            let pageList = document.querySelectorAll('.nhsuk-contents-list li.nhsuk-contents-list__item');
            for (var i = pageList.length - 1; i >= 0; i--) {
                let nhsList = pageList[i];
                let link = pageList[i].children[0].href;
                if (link === url) {
                    let txt = pageList[i].innerText;
                    pageList[i].innerHTML = txt;
                }
            }
        })();
        // Smooth scroll to link
        jQuery(document).ready(function ($) {
            $('.js-scroll-to').on('click', function (e) {
                e.preventDefault();
                let link = $(this).attr('href');
                $('html, body').animate({
                    scrollTop: $(link).offset().top - 50
                }, 200);
            });
        });
    </script>";
	echo $scriptout;
}

add_action( 'wp_footer', 'nhsblocks_hero_footer_override' );
//endregion Fix for NHS Blocks

//region Override default customizer options
function nightingale_show_customize_register($wp_customize) {
	$wp_customize->remove_setting('header_styles');
	$wp_customize->add_setting(
		'header_styles',
		array(
			'default'           => 'inverted',
			'sanitize_callback' => 'nightingale_sanitize_select',
		)
	);

	$wp_customize->remove_setting('theme_colour');
	$wp_customize->add_setting(
		'theme_colour',
		array(
			'default'           => 'nhs_dark_blue',
			'sanitize_callback' => 'nightingale_sanitize_select',
		)
	);
}
add_action( 'customize_register', 'nightingale_show_customize_register' );
//endregion Override default customizer options

//region Force GraphQL settings
add_filter( 'graphql_setting_field_config', function( $field_config, $field_name, $section ) {
	switch ( $field_name ) {
		// Prevent modification of certain settings
		case 'graphql_endpoint':
		case 'batch_queries_enabled':
		case 'batch_limit':
		case 'query_depth_enabled':
		case 'query_depth_max':
		case 'graphiql_enabled':
		case 'tracing_user_role':
		case 'query_log_user_role':
			$field_config['value'] = $field_config['default'];
			break;

		// Restrict endpoint to authenticated users
		case 'restrict_endpoint_to_logged_in_users':
			$field_config['default'] = 'on';
			$field_config['value']   = $field_config['default'];
			break;

		// Disable GraphiQL IDE Admin Bar link
		case 'show_graphiql_link_in_admin_bar':
			$field_config['default'] = 'off';
			$field_config['value']   = $field_config['default'];
			break;

		// Prevent deletion of GraphQL data on deactivation.
		case 'delete_data_on_deactivate':
			$field_config['default'] = 'off';
			$field_config['value']   = $field_config['default'];
			break;
	}

	return $field_config;

}, 10, 3 );
//endregion Force GraphQL Settings

//region Custom GraphQL hooks
add_action( 'graphql_register_types', function() {
	/**
	 * Root-level field: sidebars(id: String)
	 * Allows querying any sidebar by its registered ID.
	 */
	register_graphql_field( 'RootQuery', 'sidebars', [
		'type'        => 'String',
		'description' => __( 'Render a dynamic sidebar by ID.', 'nightingale' ),
		'args'        => [
			'id' => [
				'type'        => 'String',
				'description' => __( 'The registered sidebar ID.', 'nightingale' ),
			],
		],
		'resolve'     => function( $root, $args ) {
			if ( empty( $args['id'] ) ) {
				return null;
			}
			if ( ! is_registered_sidebar( $args['id'] ) ) {
				return sprintf( '<!-- Sidebar "%s" not registered -->', esc_html( $args['id'] ) );
			}
			ob_start();
			dynamic_sidebar( $args['id'] );
			return ob_get_clean();
		},
	] );

	/**
	 * Add root field to query custom CSS
	 */
	register_graphql_field( 'RootQuery', 'themeCustomCSS', [
		'type' => 'String',
		'description' => __( 'CSS from the Additional CSS section of the Customizer' ),
		'resolve' => function() {
			$css_posts = get_posts( [
				'post_type' => 'custom_css',
				'posts_per_page' => 1,
			] );
			if ( empty( $css_posts ) ) {
				return '';
			}
			return $css_posts[0]->post_content;
		},
	] );

	/**
	 * Add sidebar field to Page/Post type
	 */
	foreach (['Page' => 'sidebar-1', 'Post' => 'sidebar-2'] as $type => $sidebar_id) {
		register_graphql_field( $type, 'sidebar', [
			'type'        => 'String',
			'description' => __( "The sidebar content for this page/post ($sidebar_id).", 'nightingale' ),
			'resolve'     => function( $page ) use ($sidebar_id) {
				ob_start();
				dynamic_sidebar( $sidebar_id );
				return ob_get_clean();
			},
		] );
	}
});
//endregion Custom GraphQL hooks

//region Default WP 2FA options
add_action('init', function() {
	if (!get_option('dad')) {
		if (!get_option('wp_2fa_settings')) {
			$options = [
				'wp_2fa_plugin_version' => '3.0.0',

				'wp_2fa_policy' => [
					'grace-policy'                     => 'use-grace-period',
					'enable_destroy_session'           => FALSE,
					'2fa_settings_last_updated_by'     => '1',
					'limit_access'                     => FALSE,
					'hide_remove_button'               => TRUE,
					'redirect-user-custom-page'        => FALSE,
					'redirect-user-custom-page-global' => FALSE,
					'superadmins-role-add'             => FALSE,
					'superadmins-role-exclude'         => FALSE,
					'separate-multisite-page-url'      => FALSE,
					'backup_codes_enabled'             => 'yes',
					'enable_email'                     => 'enable_email',
					'specify-email_hotp'               => FALSE,
					'enable_totp'                      => 'enable_totp',
					'grace-policy-notification-show'   => 'after-login-notification',
					're-login-2fa-show'                => FALSE,
					'grace-policy-after-expire-action' => 'configure-right-away',
					'included_sites'                   => [],
					'enforced_roles'                   => [0 => 'administrator'],
					'enforced_users'                   => [],
					'excluded_users'                   => [],
					'excluded_roles'                   => [],
					'excluded_sites'                   => [],
					'grace-period'                     => 3,
					'grace-period-denominator'         => 'days',
					'create-custom-user-page'          => 'no',
					'grace-period-expiry-time'         => '1762601206',
					'enforcement-policy'               => 'certain-roles-only',
					'methods_order'                    => [0 => 'totp', 1 => 'email']
				],

				'wp_2fa_settings' => [
					'enable_destroy_session'     => FALSE,
					'limit_access'               => TRUE,
					'enable_rest'                => FALSE,
					'disable_rest'               => FALSE,
					'brute_force_disable'        => FALSE,
					'delete_data_upon_uninstall' => FALSE,
					'method_invalid_setting'     => 'login_block'
				],

				'wp_2fa_dismiss_notice_mail_domain' => '1',
				'wp_2fa_survey_notice_needed'       => '0'
			];
			$options['wp_2fa_settings_hash'] = md5( json_encode( $options['wp_2fa_settings'] ) );
			foreach ($options as $key => $value) {
				update_option($key, $value);
			}
		}
		update_option('wp_2fa_auto_configured', true);
	}
});
//endregion Default WP 2FA options
