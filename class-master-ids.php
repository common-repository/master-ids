<?php
/**
 * Master IDs.
 *
 * @package   MasterIDs
 * @author    Luis Rock <luisrock@luisrock.com>
 * @license   GPL-2.0+
 * @link      http://luisrock.com
 * @copyright 2014 Luis Rock
 */

/**
 * @package Master IDs
 * @author    Luis Rock <luisrock@luisrock.com>
 */
class MasterIDs {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = '1.0.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'master-ids';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = 'master-ids';

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'init_plugin_admin_menu' ) );


	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		// TODO: Define activation functionality here
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		// TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );


		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}


	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * TODO:
		 *
		 * Change 'Page Title' to the title of your plugin admin page
		 * Change 'Menu Text' to the text for menu item for the plugin settings page
		 * Change 'plugin-name' to the name of your plugin
		 */
		add_options_page(
			__( 'Master IDs', $this->plugin_slug ),
			__( 'Master IDs', $this->plugin_slug ),
			'read',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);
		
	}
	
	public function init_plugin_admin_menu() {

	    register_setting( 'mi-default-settings-group', 'mi-default-post-types' );	 
	    add_settings_section( 'mi-section-one', __('Find what you want now.', 'master-ids'),  array( $this, 'mi_section_one_callback' ), 'master-ids' );

	    add_settings_field( 'mi-default-post-types', __('Choose post type:', 'master-ids'), array( $this, 'mi_default_post_types_callback' ), 'master-ids', 'mi-section-one' );
		add_settings_field( 'mi_default_ids_dropdown', '', array( $this, 'mi_default_ids_dropdown' ), 'master-ids', 'mi-section-one' );
	
		
	}
	
	public function mi_section_one_callback() {
		 echo '<p>' . _e('Let\'s get to work!','master-ids') . '</p>';
	}
	

		
//Choosing a Post Type:

	public function mi_default_post_types_callback() {
	    $setting_value = get_option( 'mi-default-post-types' );
	    // get all of them
	
			$args=array(
			  'public' => true
			);
			$post_types=get_post_types($args); 
			foreach ($post_types  as $post_type ) {
				if ( $post_type != "attachment" ) {
					if ( is_array($setting_value) && in_array($post_type, $setting_value)) {
			    	    echo "<label for='".$post_type."'>".$post_type."</label> <input type='checkbox' checked='checked' name='mi-default-post-types[]' id='$post_type' value='$post_type' /><br/						>";    
			    	}

			    	elseif (!is_array($setting_value) && !empty($setting_value)) {
			    	 	echo "<label for='".$post_type."'>".$post_type."</label> <input type='checkbox' checked='checked' name='mi-default-post-types[]' id='$post_type' value='$post_type' /><br/						>";
			    	 } 

			    	else {
			    	    echo "<label for='".$post_type."'>".$post_type."</label> <input type='checkbox' name='mi-default-post-types[]' id='$post_type' value='$post_type' /><br/>";    

			    	
			    	}

		    	}
		    }
	    }
	
//Showing all posts from the Post Type chosen, with the correspondent IDs


	public function mi_default_ids_dropdown( $post_type ) {
	
		GLOBAL $mi_out;
		GLOBAL $mi_nopost;
		GLOBAL $mi_nochoice;

		$post_type = get_option( 'mi-default-post-types' ); 
		$setting_value = get_option( 'mi-select-course' );

		$posts = get_posts(
		        array(
		            'post_type'  => $post_type,
		            'numberposts' => -1
		        )
		    );

		    if( ! $posts ) {
			
			$mi_nopost = '<div class="error"><p>' . __('There is no post for the Post Type you choose yet.', 'master-ids') . '</p></div>';
			return $mi_nopost;
		}

			if ( !empty($post_type) && $posts ) {

		    //counting posts
			$mi_out = '<p id="countposts">' . __('Result:', 'master-ids') . ' ' . count($posts) . ' posts ' . __('for', 'master-ids');
			$mi_out .= '<div id="boxposts" style="background-color: white; width:25%; padding: 12px;"><ul style="list-style-type:circle; list-style-position:inside;"><li>' . implode( '</li><li>', $post_type ) . '</li></ul></div></p>';
			$mi_out .= '<div><select id="mi_select_post" name="mi_select_post"><option>' . __('POST TITLES AND IDs', 'master-ids') . '</option>';
		
			sort( $posts );
		    foreach( $posts as $p )
		    
			{


			$mi_out .= '<option value="' . get_permalink( $p ) . '">' . esc_html( $p->post_title ) . ' (ID => ' . $p->ID . ')</option>';
		
		    }
		    $mi_out .= '</select></div>';
		
			
			return $mi_out;
			
			}
			
			
			if ( empty($post_type) )  {
				$mi_nochoice = "<div class='error'><p>" . __('You must choose at least one Post Type! Please, try again.', 'master-ids') . "</p></div>";
				return $mi_nochoice;
			}
			
	}


	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
	
		//include_once( 'views/admin.php' );
		
	?><div class="wrap">	    
	    
	    	<p class="description"><?php echo _e('Everything the Master IDs plugin offers is in this page.', 'master-ids')?></p>
	    

	        <form action="options.php" method="POST">
	            <?php settings_fields( 'mi-default-settings-group' ); ?>
	            <?php do_settings_sections( 'master-ids' ); ?>
	            <?php submit_button(__('Go for it', 'master-ids')); ?>
	        </form>
				
	   
		<div class="mi-result"><p><?php 
										GLOBAL $mi_out;
										GLOBAL $mi_nopost;
										GLOBAL $mi_nochoice; 
										echo $mi_out;
										echo $mi_nopost;
										echo $mi_nochoice;?>
										
										</p></div> </div>
	<?php	    
	}

}