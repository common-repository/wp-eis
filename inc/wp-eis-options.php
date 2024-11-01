<?php  

class WP_EIS_Opt extends WP_EIS_DB
{
	private $eis_id;
	protected $options;
	public function __construct()
	{
		$this->wp_eis_name_install();
		$this->wp_eis_items_install();
		$this->plugin_enqueues();
		$this->plugin_ajax();

		$this->options = get_option('wp_eis_settings');
		$this->register_settings_fields();

		WP_EIS_DB::__construct();
	}

	public function wp_eis_page()
	{
		if (function_exists('add_options_page')) 
		{
			add_menu_page(__('WP EIS', 'wp-eis'), __('WP EIS', 'wp-eis'), 'read', 'wp-eis', array('WP_EIS_Opt', 'wp_eis_setting_page'), WP_EIS_URL.'images/wp-eis-ico.png');
			add_submenu_page('wp-eis', __('Settings', 'wp-eis'), __('Settings', 'wp-eis'), 'read', 'wp-eis', array($this, 'wp_eis_settings_page'));
			add_submenu_page('wp-eis', __('Add Slideshow', 'wp-eis'), __('Add Slideshow', 'wp-eis'), 'read', 'wp-eis/add-slide', array($this, 'wp_eis_add_slide_page'));
			add_submenu_page('wp-eis', __('Add Images', 'wp-eis'), __('Add Images', 'wp-eis'), 'read', 'wp-eis/add-images', array($this, 'wp_eis_add_images_page'));
			add_submenu_page('wp-eis', __('ALL Slides', 'wp-eis'), __('ALL Slides', 'wp-eis'), 'read', 'wp-eis/all-slides', array($this, 'wp_eis_all_slides_page'));
			add_submenu_page('wp-eis', __('ALL Images', 'wp-eis'), __('ALL Images', 'wp-eis'), 'read', 'wp-eis/all-images', array($this, 'wp_eis_all_images_page'));
			add_submenu_page('wp-eis', __('About Plugin', 'wp-eis'), __('About Plugin', 'wp-eis'), 'read', 'wp-eis/about', array($this, 'wp_eis_about_page'));
		}
	}

	public function wp_eis_setting_page()
	{
		///
	}

	public function wp_eis_settings_page()
	{
		if (!current_user_can('administrator')) {
			echo '<div class="error-happend">';
				wp_die(__('You do not have sufficient permissions to access this page.', 'wp-eis'));
			echo "</div>";
		}
		?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"><br></div>
			<h2><?php _e('Slideshow Settings', 'wp-eis'); ?></h2>
			<?php if ( isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true'): ?>
				<div id="setting-error-settings_updated" class="updated settings-error"> 
					<p><strong><?php _e('Settings saved.', 'wp-eis'); ?></strong></p>
				</div>
			<?php endif ?>
			<form method="post" action="options.php" class="slideshow-settings">
				<?php settings_fields('wp_eis_settings'); ?>
				<p><?php // _e('Select the user roles that will able to perform in certain actions', 'wp-eis'); ?></p>
				<?php do_settings_sections(__FILE__.'/capability'); ?>
				<p><?php // _e('Change the titles style in the slideshow', 'wp-eis'); ?></p>
				<?php do_settings_sections(__FILE__.'/header'); ?>
				<p><?php // _e('Change the navigation syle in the slideshow', 'wp-eis'); ?></p>
				<?php do_settings_sections(__FILE__.'/navigation'); ?>
				<p><?php // _e('Change the loading syle in the slideshow', 'wp-eis'); ?></p>
				<?php do_settings_sections(__FILE__.'/loading'); ?>
			<p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Settings', 'wp-eis'); ?>">
            </p >
			</form>
		</div>
		<?php
	}

	public function register_settings_fields()
	{
		register_setting('wp_eis_settings', 'wp_eis_settings');

		add_settings_section('wp_eis_capability', __('User Capability', 'wp-eis'), array($this, 'wp_eis_capability_db'), __FILE__.'/capability');
		add_settings_field('eis_add_capability', __('Add Slideshow: ', 'wp-eis'), array($this, 'eis_add_capability_set'), __FILE__.'/capability', 'wp_eis_capability');
		add_settings_field('eis_edit_capability', __('Edit Slideshow: ', 'wp-eis'), array($this, 'eis_edit_capability_set'), __FILE__.'/capability', 'wp_eis_capability');
		add_settings_field('eis_delete_capability', __('Delete Slideshow: ', 'wp-eis'), array($this, 'eis_delete_capability_set'), __FILE__.'/capability', 'wp_eis_capability');
		
		add_settings_section('wp_eis_h_section', __('Header Text Settings', 'wp-eis'), array($this, 'wp_eis_h_section_db'), __FILE__.'/header');
		add_settings_field('eis_h2_font', __('h2 Font Name: ', 'wp-eis'), array($this, 'wp_eis_h2_font_set'), __FILE__.'/header', 'wp_eis_h_section');
		add_settings_field('eis_h2_size', __('h2 Font Size: ', 'wp-eis'), array($this, 'wp_eis_h2_size_set'), __FILE__.'/header', 'wp_eis_h_section');
		add_settings_field('eis_h2_color', __('h2 Font Color: ', 'wp-eis'), array($this, 'wp_eis_h2_color_set'), __FILE__.'/header', 'wp_eis_h_section');
		add_settings_field('eis_h3_font', __('h3 Font Name: ', 'wp-eis'), array($this, 'wp_eis_h3_font_set'), __FILE__.'/header', 'wp_eis_h_section');
		add_settings_field('eis_h3_size', __('h3 Font Size: ', 'wp-eis'), array($this, 'wp_eis_h3_size_set'), __FILE__.'/header', 'wp_eis_h_section');
		add_settings_field('eis_h3_color', __('h3 Font Color: ', 'wp-eis'), array($this, 'wp_eis_h3_color_set'), __FILE__.'/header', 'wp_eis_h_section');
		
		add_settings_section('wp_eis_nav_section', __('Navigation Color Settings', 'wp-eis'), array($this, 'wp_eis_nav_section_db'), __FILE__.'/navigation');	
		add_settings_field('eis_nav_color', __('Navigation color: ', 'wp-eis'), array($this, 'wp_eis_nav_color_set'), __FILE__.'/navigation', 'wp_eis_nav_section');
		add_settings_field('eis_nav_hover_color', __('Navigation hover color: ', 'wp-eis'), array($this, 'wp_eis_nav_hover_color_set'), __FILE__.'/navigation', 'wp_eis_nav_section');
		add_settings_field('eis_nav_current_color', __('Current navigation color: ', 'wp-eis'), array($this, 'wp_eis_nav_current_color_set'), __FILE__.'/navigation', 'wp_eis_nav_section');
		
		add_settings_section('wp_eis_loading_section', __('Loading Settings', 'wp-eis'), array($this, 'wp_eis_loading_section_db'), __FILE__.'/loading');	
		add_settings_field('eis_loading_title', __('Loading Title: ', 'wp-eis'), array($this, 'wp_eis_loading_title_set'), __FILE__.'/loading', 'wp_eis_loading_section');
		add_settings_field('eis_loading_image', __('Loading Image: ', 'wp-eis'), array($this, 'wp_eis_loading_image_set'), __FILE__.'/loading', 'wp_eis_loading_section');
	}

	public function wp_eis_capability_db()
	{
		// I dont' need it
	}

	public function wp_eis_h_section_db()
	{
		// I dont' need it
	}

	public function wp_eis_nav_section_db() 
	{
		// I dont' need it
	}

	public function wp_eis_loading_section_db() 
	{
		// I dont' need it
	}

	public function eis_add_capability_set(){
		global $wp_roles;
		$input  = '';
       	$roles = $wp_roles->get_names();
 		foreach ($roles as $role_key => $role_value) {
 			$value  = isset( $this->options['eis_add_capability'][$role_key] ) ? $this->options['eis_add_capability'][$role_key] : '';
 			$admin_check = ( $role_key == 'administrator' ) ? ' checked="checked" disabled="disabled" ' : '';
 			$checked = ( $value == $role_key ) ? ' checked="checked" ' : '';
 			$input .= '<label for="'.$role_key.'-can-add"><input name="wp_eis_settings[eis_add_capability]['.$role_key.']"  type="checkbox" '.$admin_check.$checked.' id="'.$role_key.'-can-add" value="'.trim( esc_attr($role_key) ).'">'.$role_value.'</label><br />';
 		}
		echo $input;
	}

	public function eis_edit_capability_set(){
		global $wp_roles;
		$input  = '';
       	$roles = $wp_roles->get_names();
 		foreach ($roles as $role_key => $role_value) {
 			$value  = isset( $this->options['eis_edit_capability'][$role_key] ) ? $this->options['eis_edit_capability'][$role_key] : '';
 			$admin_check = ( $role_key == 'administrator' ) ? ' checked="checked" disabled="disabled" ' : '';
 			$checked = ( $value == $role_key ) ? ' checked="checked' : '';
 			$input .= '<label for="'.$role_key.'-can-edit"><input name="wp_eis_settings[eis_edit_capability]['.$role_key.']"  type="checkbox" '.$admin_check.$checked.' id="'.$role_key.'-can-edit" value="'.trim( $role_key ).'">'.$role_value.'</label><br />';
 		}
		echo $input;
	}

	public function eis_delete_capability_set(){
		global $wp_roles;
		$input  = '';
       	$roles = $wp_roles->get_names();
 		foreach ($roles as $role_key => $role_value) {
 			$value  = isset( $this->options['eis_delete_capability'][$role_key] ) ? $this->options['eis_delete_capability'][$role_key] : '';
 			$admin_check = ( $role_key == 'administrator' ) ? ' checked="checked" disabled="disabled" ' : '';
 			$checked = ( $value == $role_key ) ? ' checked="checked' : '';
 			$input .= '<label for="'.$role_key.'-can-delete"><input name="wp_eis_settings[eis_delete_capability]['.$role_key.']"  type="checkbox" '.$admin_check.$checked.' id="'.$role_key.'-can-delete" value="'.trim( $role_key ).'">'.$role_value.'</label><br />';
 		}
		echo $input;
	}

	public function wp_eis_h2_font_set()
	{
		$value  = isset( $this->options['eis_h2_font'] ) ? $this->options['eis_h2_font'] : '';
		$input  = '';
		$input .= '<input name="wp_eis_settings[eis_h2_font]" type="text" id="wp-eis-h2-font" value="'.$value.'" class="regular-text code">';
		$input .= '<p class="description">'.__('Your font name for this title', 'wp-eis').'</p>';
		echo $input;
	}

	public function wp_eis_h2_size_set()
	{
		$value = isset( $this->options['eis_h2_size'] ) ? $this->options['eis_h2_size'] : '';
		$input  = '';
		$input .= '<input name="wp_eis_settings[eis_h2_size]" type="number" id="wp-eis-h2-size" value="'.$value.'" class="small-text code">';
		$input .= '<p class="description">'.__('Font size in pixel', 'wp-eis').'</p>';
		echo $input;
	}

	public function wp_eis_h2_color_set()
	{
		$value = isset( $this->options['eis_h2_color'] ) ? $this->options['eis_h2_color'] : '';
		$input  = '';
    	$input .= '<div>';
		$input .= '<input type="text" name="wp_eis_settings[eis_h2_color]" id="h2-color" class="color" value="'.$value.'" 
					style="background-color: '.$value.'; background-position: initial initial; background-repeat: initial initial;" data-default-color="#B5B5B5" />';
		$input .= '<input type="button" class="pickcolor button-secondary" value="'.__('Select Color', 'wp-eis').'">';
		$input .= '<input type="button" style="margin: 0 5px;" class="defaultcolor button-secondary" value="'.__('Default Color', 'wp-eis').'">';
		$input .= '<div class="colorpicker"></div>';
		$input .= '</div>';
    	echo $input;
	}

	public function wp_eis_h3_font_set()
	{
		$value = isset( $this->options['eis_h3_font'] ) ? $this->options['eis_h3_font'] : '';
		$input  = '';
		$input .= '<input name="wp_eis_settings[eis_h3_font]" type="text" id="wp-eis-h3-font" value="'.$value.'" class="regular-text code">';
		$input .= '<p class="description">'.__('Your font name for this title', 'wp-eis').'</p>';
		echo $input;
	}

	public function wp_eis_h3_size_set()
	{
		$value = isset( $this->options['eis_h3_size'] ) ? $this->options['eis_h3_size'] : '';
		$input  = '';
		$input .= '<input name="wp_eis_settings[eis_h3_size]" type="number" id="wp-eis-h3-size" value="'.$value.'" class="small-text code">';
		$input .= '<p class="description">'.__('Font size in pixel', 'wp-eis').'</p>';
		echo $input;
	}

	public function wp_eis_h3_color_set()
	{
		$value = isset( $this->options['eis_h3_color'] ) ? $this->options['eis_h3_color'] : '';
		$input  = '';
    	$input .= '<div>';
		$input .= '<input type="text" name="wp_eis_settings[eis_h3_color]" id="h3-color" class="color" value="'.$value.'" data-default-color="#000000"
					style="background-color: '.$value.'; background-position: initial initial; background-repeat: initial initial;" />';
		$input .= '<input type="button" class="pickcolor button-secondary" value="'.__('Select Color', 'wp-eis').'">';
		$input .= '<input type="button" style="margin: 0 5px;" class="defaultcolor button-secondary" value="'.__('Default Color', 'wp-eis').'">';
		$input .= '<div class="colorpicker"></div>';
		$input .= '</div>';
    	echo $input;
	}

	public function wp_eis_nav_color_set()
	{
		$value = isset( $this->options['eis_nav_color'] ) ? $this->options['eis_nav_color'] : '';
		$input  = '';
    	$input .= '<div>';
		$input .= '<input type="text" name="wp_eis_settings[eis_nav_color]" id="nav-color" class="color" value="'.$value.'" data-default-color="#666666" 
					style="background-color: '.$value.'; background-position: initial initial; background-repeat: initial initial;" />';
		$input .= '<input type="button" class="pickcolor button-secondary" value="'.__('Select Color', 'wp-eis').'">';
		$input .= '<input type="button" style="margin: 0 5px;" class="defaultcolor button-secondary" value="'.__('Default Color', 'wp-eis').'">';
		$input .= '<div class="colorpicker"></div>';
		$input .= '</div>';
    	echo $input;
	}

	public function wp_eis_nav_hover_color_set()
	{
		$value = isset( $this->options['eis_nav_hover_color'] ) ? $this->options['eis_nav_hover_color'] : '';
		$input  = '';
    	$input .= '<div>';
		$input .= '<input type="text" name="wp_eis_settings[eis_nav_hover_color]" id="nav-hover-color" class="color" value="'.$value.'" data-default-color="#f0f0f0" 
					style="background-color: '.$value.'; background-position: initial initial; background-repeat: initial initial;" />';
		$input .= '<input type="button" class="pickcolor button-secondary" value="'.__('Select Color', 'wp-eis').'">';
		$input .= '<input type="button" style="margin: 0 5px;" class="defaultcolor button-secondary" value="'.__('Default Color', 'wp-eis').'">';
		$input .= '<div class="colorpicker"></div>';
		$input .= '</div>';
    	echo $input;
	}

	public function wp_eis_nav_current_color_set()
	{
		$value = isset( $this->options['eis_nav_current_color'] ) ? $this->options['eis_nav_current_color'] : '';
		$input  = '';
    	$input .= '<div>';
		$input .= '<input type="text" name="wp_eis_settings[eis_nav_current_color]" id="nav-current-color" class="color" value="'.$value.'" data-default-color="#f0f0f0" 
					style="background-color: '.$value.'; background-position: initial initial; background-repeat: initial initial;" />';
		$input .= '<input type="button" class="pickcolor button-secondary" value="'.__('Select Color', 'wp-eis').'">';
		$input .= '<input type="button" style="margin: 0 5px;" class="defaultcolor button-secondary" value="'.__('Default Color', 'wp-eis').'">';
		$input .= '<div class="colorpicker"></div>';
		$input .= '</div>';
    	echo $input;
	}

	public function wp_eis_loading_title_set()
	{
		$value = isset( $this->options['eis_loading_title'] ) ? $this->options['eis_loading_title'] : '';
		$input  = '';
		$input .= '<input name="wp_eis_settings[eis_loading_title]" type="text" id="eis-loading-title" value="'.$value.'" class="regular-text code">';
		$input .= '<p class="description">'.__('Your loading title like Loading...', 'wp-eis').'</p>';
		echo $input;
	}

	public function wp_eis_loading_image_set()
	{
		$value = isset( $this->options['eis_loading_image'] ) ? $this->options['eis_loading_image'] : '';
		$options = array(
			'loading_pt1' => __('Moving Block', 'wp-eis'), 
			'loading_pt2' => __('Flickr', 'wp-eis'), 
			'loading_pt3' => __('Rotating heart', 'wp-eis'), 
			'loading_pt4' => __('Clock with hands', 'wp-eis'), 
			'loading_pt5' => __('Window 8 loader', 'wp-eis'), 
			'loading_pt6' => __('Loading fading lines', 'wp-eis'), 
			'loading_pt7' => __('Twirl', 'wp-eis'), 
			'loading_pt8' => __('Glow in ring', 'wp-eis'),
			'loading_pt9' => __('Solid snake', 'wp-eis'),
			'loading_pt10' => __('Heart arrow', 'wp-eis'),
			'loading_pt11' => __('Ubuntu', 'wp-eis'),
			'loading_pt12' => __('Segments', 'wp-eis'),
			'loading_pt13' => __('Apple', 'wp-eis'),
			'loading_pt14' => __('Android', 'wp-eis'),
			'loading_pt15' => __('Picasa', 'wp-eis')
		);
		$input  = '';
		$input .= '<select name="wp_eis_settings[eis_loading_image]" id="eis-loading-image" class="regular-text code">';
			$input .= '<option value="default">'.__('Select Your Image', 'wp-eis').'</option>';
		foreach ( $options as $option => $name ) {
			$selected = ( $value == $option ) ? 'selected="selected"' : '';
			$input .= '<option value="'.$option.'" '.$selected.'>'.$name.'</option>';
		}
		$input .= '</select>';
		$input .= '<p id="eis-loading-image-preview">';
		if ( !empty( $value ) && $value !== 'default' ) {
			$input .= '<img src="'.WP_EIS_URL.'images/loading/'.$value.'.gif" title="'.$options[$value].'" />';
		}
		$input .= '</p>';
		?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					var loadingImageOpt = $('#eis-loading-image');
					loadingImageOpt.bind('change', function() {
						if( loadingImageOpt.val() !== 'default' ) {
							$('#eis-loading-image-preview').html('<img src="<?php echo WP_EIS_URL.'images/loading/'; ?>' + $(this).val() + '.gif" title="' + $(this).find('option:selected').text() + '" />');	
						} else {
							$('#eis-loading-image-preview').html('');
						}
					});
				});
			</script>
		<?php
		echo $input;

	}


	public function plugin_enqueues()
	{
		if(is_admin()) {
			wp_enqueue_script('jquery');
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');

			wp_enqueue_style('farbtastic');
			wp_enqueue_script('farbtastic');

			wp_enqueue_script('eis-js-init', WP_EIS_URL.'js/eis-admin.js', array( 'jquery' ));
			wp_enqueue_style('eis-css-init', WP_EIS_URL.'css/eis-admin.css');
			if(is_rtl()) {
				wp_enqueue_style('eis-css-init-rtl', WP_EIS_URL.'css/eis-admin-rtl.css');
			}
		}

	}

	public function plugin_ajax()
	{
		add_action('wp_ajax_wp_eis_add_slideshow_ajax', array($this, 'wp_eis_add_slideshow_ajax_process'));
		add_action('wp_ajax_wp_eis_update_slideshow_ajax', array($this, 'wp_eis_update_slideshow_ajax_process'));
		add_action('wp_ajax_wp_eis_delete_slideshow_ajax', array($this, 'wp_eis_delete_slideshow_ajax_process'));

		add_action('wp_ajax_wp_eis_add_images_ajax', array($this, 'wp_eis_add_images_ajax_process'));
		add_action('wp_ajax_wp_eis_update_images_ajax', array($this, 'wp_eis_update_images_ajax_process'));
		add_action('wp_ajax_wp_eis_delete_images_ajax', array($this, 'wp_eis_delete_images_ajax_process'));
	}


	public function wp_eis_add_slideshow_ajax_process()
	{
		global $wpdb;

		$errors = array();
		$name = $_POST['wp_eis_slidename'];
		$animation = $_POST['wp_eis_animation'];
		$autoplay = $_POST['wp_eis_autoplay'];
		$slideshow_interval = $_POST['wp_eis_slideshow_interval'];
		$speed = $_POST['wp_eis_speed'];
		$easing = $_POST['wp_eis_easing'];
		$titlesFactor = $_POST['wp_eis_titlesFactor'];
		$titlesSpeed = $_POST['wp_eis_titlesSpeed'];
		$titlesEasing = $_POST['wp_eis_titlesEasing'];
		$thumbMaxWidth = $_POST['wp_eis_thumbMaxWidth'];


		if( !isset($name) || empty($name) ) {
			$errors['name'] = __('Please Enter the Name of Slideshows', 'wp-eis');
		}
		if( !isset($slideshow_interval) || empty($slideshow_interval) ) {
			$slideshow_interval = 3000;
		}
		if( !isset($speed) || empty($speed) ) {
			$speed = 800;
		}
		if( !isset($titlesFactor) || empty($titlesFactor) ) {
			$titlesFactor = 0.60;
		}
		if( !isset($titlesSpeed) || empty($titlesSpeed) ) {
			$titlesSpeed = 800;
		}
		if( !isset($thumbMaxWidth) || empty($thumbMaxWidth) ) {
			$thumbMaxWidth = 150;
		}

		$get_eis_slidename = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}eis_name WHERE name = '{$name}'");
		if( count( $get_eis_slidename ) > 0 ) {
			$errors['name'] = __('This slidehsow name has been created before', 'wp-eis');
		}

		if( count($errors) == 0 ) {
			$this->insert_data(
				$wpdb->prefix.'eis_name', 
				array(
					"name"  				=> $name, 
					"animation"  			=> $animation, 
					"autoplay"  			=> $autoplay, 
					"slideshow_interval"  	=> $slideshow_interval, 
					"speed"  				=> $speed, 
					"easing"  				=> $easing, 
					"titles_factor"  		=> $titlesFactor, 
					"titles_speed"  		=> $titlesSpeed, 
					"titles_easing"  		=> $titlesEasing, 
					"thumb_max_width"  		=> $thumbMaxWidth 
				), 
				array("%s", "%s", "%s", "%d", "%d", "%s", "%.2f", "%d", "%s", "%d") 
			);	
			echo '<h2 class="success-respond">'.__('Slideshow has been created', 'wp-eis').'</h2>';
		} else {
			if( !empty( $errors['name'] ) ) {
				echo '<h2 class="error-respond">'.$errors['name'].'</h2>';
			} else {
				echo '<h2 class="error-respond">'.__('Error has been occured', 'wp-eis').'</h2>';
			}
		}
		die();	
	}

	public function wp_eis_update_slideshow_ajax_process()
	{
		global $wpdb;
		// if(isset($_POST['wp_eis_add'])) {
		$errors = array();
		$ID_name = $_POST['wp_eis_ID_name'];
		$slidename = $_POST['wp_eis_slidename'];
		$animation = $_POST['wp_eis_animation'];
		$autoplay = $_POST['wp_eis_autoplay'];
		$slideshow_interval = $_POST['wp_eis_slideshow_interval'];
		$speed = $_POST['wp_eis_speed'];
		$easing = $_POST['wp_eis_easing'];
		$titlesFactor = $_POST['wp_eis_titlesFactor'];
		$titlesSpeed = $_POST['wp_eis_titlesSpeed'];
		$titlesEasing = $_POST['wp_eis_titlesEasing'];
		$thumbMaxWidth = $_POST['wp_eis_thumbMaxWidth'];


		if( !isset($slidename) || empty($slidename) ) {
			$errors['slidename'] = __('Please Enter the Name of Slideshows', 'wp-eis');
		}
		if( !isset($slideshow_interval) || empty($slideshow_interval) ) {
			$slideshow_interval = 3000;
		}
		if( !isset($speed) || empty($speed) ) {
			$speed = 800;
		}
		if( !isset($titlesFactor) || empty($titlesFactor) ) {
			$titlesFactor = 0.60;
		}
		if( !isset($titlesSpeed) || empty($titlesSpeed) ) {
			$titlesSpeed = 800;
		}
		if( !isset($thumbMaxWidth) || empty($thumbMaxWidth) ) {
			$thumbMaxWidth = 150;
		}


		if( count($errors) == 0 ) {
			$this->update_data(
				$wpdb->prefix.'eis_name', 
				array(
					"name"  				=> $slidename, 
					"animation"  			=> $animation, 
					"autoplay"  			=> $autoplay, 
					"slideshow_interval"  	=> $slideshow_interval, 
					"speed"  				=> $speed, 
					"easing"  				=> $easing, 
					"titles_factor"  		=> $titlesFactor, 
					"titles_speed"  		=> $titlesSpeed, 
					"titles_easing"  		=> $titlesEasing, 
					"thumb_max_width"  		=> $thumbMaxWidth 
				), 
				array('ID_name' => $ID_name),
				array("%s", "%s", "%s", "%d", "%d", "%s", "%.2f", "%d", "%s", "%d"), 
				array("%d")
			);	
			echo '<h2 class="success-respond">'.__('Slideshow has been Updated', 'wp-eis').'</h2>';
		} else {
			echo '<h2 class="error-respond">'.__('Error has been occured', 'wp-eis').'</h2>';
		}
		die();	
	}

	public function wp_eis_delete_slideshow_ajax_process()
	{
		global $wpdb;
		$id = $_POST['ID'];
		$ids = explode("|", $id);

		for ($i=0; $i < count( $ids ); $i++) { 
			if( isset($ids[$i]) && !empty($ids[$i]) ) {
				$get_eis_names = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}eis_name WHERE ID_name = '{$ids[$i]}'");
				$get_eis_names = $get_eis_names[0];
					$name = $get_eis_names->name;
				$this->delete_data_by_id( $wpdb->prefix.'eis_name', 'ID_name', $ids[$i]);
				echo '<h2 class="success-respond">"'.$name.'" '.__('has been deleted successfully', 'wp-eis').'</h2>'."\n";
			} else {
				echo '<h2 class="error-respond">'.__('Error has been occured for deleting', 'wp-eis').' "'.$name.'"</h2>'."\n";
			}
		}
		die();
	}


	public function wp_eis_add_images_ajax_process()
	{
		global $wpdb;
		// if(isset($_POST['wp_eis_add'])) {
		$errors = array();
		$slide_name = $_POST['slide_name'];
		$attach_id = $_POST['attach_id'];
		$count_input = count( $attach_id );
		$url = $_POST['url'];
		$h2_title = $_POST['h2_title'];
		$h3_title = $_POST['h3_title'];
		$order = $_POST['order'];
		$attach_ids = explode("|", $attach_id);
		$urls = explode("|", $url);
		$h2_titles = explode("|", $h2_title);
		$h3_titles = explode("|", $h3_title);
		$orders = explode("|", $order);
		$get_eis_names = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}eis_name WHERE name = '{$slide_name}'");
		foreach ($get_eis_names as $get_eis_name) {
			$ID_name = $get_eis_name->ID_name;
		}

		if ( !isset($url) && count($url) !== $count_input ) {
			$errors['url'] = __('Please Enter the url of Image', 'wp-eis');
		}
		// if ( !isset($h2_title) && count($h2_title) !== $count_input ) {
		// 	$errors['h2_title'] = __('Please Enter the title of Image', 'wp-eis');
		// }
		// if ( !isset($h3_title) && count($h3_title) !== $count_input ) {
		// 	$errors['h3_title'] = __('Please Enter the title of Image', 'wp-eis');
		// }


		if( count($errors) == 0 ) {
			for ($i=0; $i < count( $attach_ids ); $i++) { 
				$this->insert_data(
					$wpdb->prefix.'eis_items', 
					array(
						"ID_name"  	=> $ID_name, 
						"attach_ID" => $attach_ids[$i], 
						"name"  	=> $slide_name, 
						"title_h2"  => strip_tags( $h2_titles[$i], '<a><span><strong><em><i>'), 
						"title_h3"  => strip_tags( $h3_titles[$i], '<a><span><strong><em><i>'),
						"image"     => $urls[$i],
						"order"     => $orders[$i]
					), 
					array("%d", "%s", "%s", "%s", "%s", "%s", "%d") 
				);	
			}
			echo '<h2 class="success-respond">'.__('Images has been added', 'wp-eis').'</h2>';
		} else {
			echo '<h2 class="error-respond">'.__('Error has been occured', 'wp-eis').'</h2>';
		}
		die();	
	}

	public function wp_eis_update_images_ajax_process() {
		global $wpdb;
		// if(isset($_POST['wp_eis_add'])) {
		$errors = array();
		$ID = $_POST['wp_eis_ID'];
		$slidename = $_POST['wp_eis_slidename'];

		$title_h2 =  preg_replace("/<p[^>]*?>/", "", $_POST['wp_eis_htwo']);
		$title_h2 =  strip_tags( $title_h2, '<a><span><strong><em><i>' );

		$title_h3 =  strip_tags( $_POST['wp_eis_hthree'], '<a><span><strong><em><i>' );

		$image = $_POST['wp_eis_image'];
		$order = $_POST['wp_eis_order'];

		if( !isset($slidename) || empty($slidename) ) {
			$errors['slidename'] = __('Please Enter the Name of Slideshows', 'wp-eis');
		}
		// if( !isset($title_h2) || empty($title_h2) ) {
		// 	$errors['title_h2'] = __('Please Enter the title of Image', 'wp-eis');
		// }
		// if( !isset($title_h3) || empty($title_h3) ) {
		// 	$errors['title_h3'] = __('Please Enter the title of Image', 'wp-eis');
		// }
		if( !isset($image) || empty($image) ) {
			$errors['image'] = __('Please Enter the url of Image', 'wp-eis');
		}
		if( !isset($ID) || empty($ID) ) {
			$errors['ID'] = '';
		}

		if( count($errors) == 0 ) {
			$this->update_data(
				$wpdb->prefix.'eis_items', 
				array(
					"name"  	=> $slidename, 
					"title_h2"  => $title_h2, 
					"title_h3"  => $title_h3,
					"image"     => $image, 
					"order"     => $order
				),
				array('ID' => $ID),
				array("%s", "%s", "%s", "%s", "%s", "%d"),
				array("%d")
			);	
			echo '<h2 class="success-respond">'.__('Image has been Updated', 'wp-eis').'</h2>';
		} else {
			echo '<h2 class="error-respond">'.__('Error has been occured', 'wp-eis').'</h2>';
		}
		die();
	}

	public function wp_eis_delete_images_ajax_process()
	{
		global $wpdb;

		$id = $_POST['ID'];
		$ids = explode("|", $id);

		for ($i=0; $i < count( $ids ); $i++) { 
			if( isset($ids[$i]) && !empty($ids[$i]) ) {
				$get_eis_names = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}eis_items WHERE ID = '{$ids[$i]}'");
				$get_eis_names = $get_eis_names[0];
					$name = $get_eis_names->name;
					$ID = $get_eis_names->ID;
				$this->delete_data_by_id( $wpdb->prefix.'eis_items', 'ID', $ids[$i]);
				echo '<h2 class="success-respond">"'.$name.'::'.$ID.'" '.__('has been deleted successfully', 'wp-eis').'</h2>'."\n";
			} else {
				echo '<h2 class="error-respond">'.__('Error has been occured for deleting', 'wp-eis').' "'.$name.'::'.$ID.'"</h2>'."\n";
			}
		}
		die();
	}

	public function wp_editor_t($content, $id, $name, $class = "titleheidtor", $rows = 3) {
			$settings = array(
				'textarea_name' => $name,
				'editor_class' => $class,
				'textarea_rows' => $rows,
			    'media_buttons' => false,
			    'teeny' => true, 
			    'tinymce' => array(
			        'theme_advanced_buttons1' => 'bold,italic,underline,|,link,unlink',
			        'theme_advanced_buttons2' => '',
			        'theme_advanced_buttons3' => '',
			        'theme_advanced_buttons4' => ''
			    ),
			    'quicktags' => false
			);
			wp_editor( $content, $id, $settings);
		}

	public function wp_eis_add_slide_page()
	{
		global $wp_roles;
		if ( is_user_logged_in() ) {
			$add_capability  = isset( $this->options['eis_add_capability'] ) ? $this->options['eis_add_capability'] : '';
			$admin = array('administrator'=>'Administrator');
			$add_capability = array_merge( $admin, (array)$add_capability );

			$current_user = wp_get_current_user();
			$current_user_role = array_shift( $current_user->roles );
			if( is_array($add_capability) ) {
				if( !array_key_exists($current_user_role, $add_capability) ) {
					echo '<div class="error-happend">';
					wp_die(__('You do not have sufficient permissions to access this page.', 'wp-eis'));
					echo "</div>";
				}
			}
		} else {
			wp_die(__('You do not have sufficient permissions to access this page.', 'wp-eis'));
		}
		?>
		<div class="wrap">
			<div id="icon-tools" class="icon32"><br></div>
			<h2><?php _e('Add New Slideshow', 'wp-eis'); ?></h2>
			<form action="" method="post" id="add-new-slideshow">
				<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><label for="wp-eis-slidename"><?php _e('Name of Slideshow :', 'wp-eis'); ?></label></th>
						<td>
							<input name="wp_eis_slidename" type="text" id="wp-eis-slidename" value="" class="regular-text code">
							<p class="description"><?php _e('Enter A valid name without space, dash or uppercase .In other words something like this eis_slider', 'wp-eis'); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="wp-eis-animation"><?php _e('Animation Type:', 'wp-eis'); ?></label></th>
						<td>
							<select id="wp-eis-animation" name="wp_eis_animation">
								<option value="sides"><?php _e('Sides', 'wp-eis'); ?></option>
								<option value="center"><?php _e('Center', 'wp-eis'); ?></option>
							</select>
							<!-- <input name="wp_eis_animation" type="text" id="wp-eis-animation" value="" class="regular-text code"> -->
							<p class="description"><?php _e('Animation types: <br />"sides" : new slides will slide in from left / right <br /> "center": new slides will appear in the center', 'wp-eis'); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="wp-eis-autoplay"><?php _e('Autoplay :', 'wp-eis'); ?></label></th>
						<td>
							<select id="wp-eis-autoplay" name="wp_eis_autoplay">
								<option value="false"><?php _e('False', 'wp-eis'); ?></option>
								<option value="true"><?php _e('True', 'wp-eis'); ?></option>
							</select>
							<p class="description"><?php _e('If true the slider will automatically slide, and it will only stop if the user clicks on a thumb', 'wp-eis'); ?></p>
						</td>
					</tr>
					<tr valign="top" class="advanced-mode">
						<th scope="row"><label for="wp-eis-slideshow-interval"><?php _e('Slideshow Interval :', 'wp-eis'); ?></label></th>
						<td>
							<input name="wp_eis_slideshow_interval" placeholder="3000" type="number" id="wp-eis-slideshow-interval" value="" class="small-text code">
							<p class="description"><?php _e('Interval for the slideshow are given in milliseconds, higher values indicate slower animations, The default value is 3000', 'wp-eis') ?></p>
						</td>
					</tr>
					<tr valign="top" class="advanced-mode">
						<th scope="row"><label for="wp-eis-speed"><?php _e('Slide Speed :', 'wp-eis'); ?></label></th>
						<td>
							<input name="wp_eis_speed" type="number" placeholder="800" id="wp-eis-speed" value="" class="small-text code">
							<p class="description"><?php _e('Speed for the sliding animation are given in milliseconds, higher values indicate slower animations, The default value is 800', 'wp-eis'); ?></p>
						</td>
					</tr>
					<tr valign="top" class="advanced-mode">
						<th scope="row"><label for="wp-eis-easing"><?php _e('Slide Easing Option :', 'wp-eis'); ?></label></th>
						<td>
							<input name="wp_eis_easing" type="text" id="wp-eis-easing" value="" class="regular-text code">
							<p class="description"><?php _e('Easing for the sliding animation', 'wp-eis'); ?></p>
							<p class="description"><?php _e('An easing function specifies the speed at which the animation progresses at different points within the animation. More easing functions are available in', 'wp-eis'); ?>  <a href="http://gsgd.co.uk/sandbox/jquery/easing/"><?php _e('Easing functions', 'wp-eis'); ?></a></p>
						</td>
					</tr>
					<tr valign="top" class="advanced-mode">
						<th scope="row"><label for="wp-eis-titlesFactor"><?php _e('Titles Factor :', 'wp-eis'); ?></label></th>
						<td>
							<input name="wp_eis_titlesFactor" type="number" placeholder="0.60" step="0.01" name="quantity" min="0.00" max="1.00" id="wp-eis-titlesFactor" value="" class="small-text code">
							<p class="description"><?php _e('Percentage of speed for the titles animation. Speed will be ( speed * titlesFactor ) and The default titlesFactor is 0.60', 'wp-eis'); ?></p>
						</td>
					</tr>
					<tr valign="top" class="advanced-mode">
						<th scope="row"><label for="wp-eis-titlesSpeed"><?php _e('Titles Speed :', 'wp-eis'); ?></label></th>
						<td>
							<input name="wp_eis_titlesSpeed" type="number" placeholder="800" id="wp-eis-titlesSpeed" value="" class="small-text code">
							<p class="description"><?php _e('Titles animation speed are given in milliseconds, higher values indicate slower animations, The default value is 800', 'wp-eis'); ?></p>
						</td>
					</tr>
					<tr valign="top" class="advanced-mode">
						<th scope="row"><label for="wp-eis-titlesEasing"><?php _e('Titles Easing Option :', 'wp-eis'); ?></label></th>
						<td>
							<input name="wp_eis_titlesEasing" type="text" id="wp-eis-titlesEasing" value="" class="regular-text code">
							<p class="description"><?php _e('Titles animation easing', 'wp-eis'); ?></a></p>
							<p class="description"><?php _e('An easing function specifies the speed at which the animation progresses at different points within the animation. More easing functions are available in', 'wp-eis'); ?>  <a href="http://gsgd.co.uk/sandbox/jquery/easing/"><?php _e('Easing functions', 'wp-eis'); ?></a></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="wp-eis-thumbMaxWidth"><?php _e('Thumbnail Max Width :', 'wp-eis'); ?></label></th>
						<td>
							<input name="wp_eis_thumbMaxWidth" type="number" placeholder="150" id="wp-eis-thumbMaxWidth" value="" class="small-text code">
							<p class="description"><?php _e('Maximum width for the thumbs in pixels', 'wp-eis'); ?></p>
							<p class="description"><?php _e('<strong>Tip</strong> : if the slideshow size that will input in the position that you chose is 960 px (for instance) and you have 6 pictures<br /> The thumbanil size should be (960 / 6 = 160 ) 160 px.', 'wp-eis'); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="wp-eis-more-about"><?php _e('For more information about this inputs :', 'wp-eis'); ?></label></th>
						<td><a id="wp-eis-more-about" href="http://tympanus.net/codrops/2011/11/21/elastic-image-slideshow-with-thumbnail-preview"><?php _e('Tympanus : Elastic Image Slideshow with Thumbnail Preview', 'wp-eis'); ?></a></td>
					</tr>
				</tbody>
				</table>
				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Add Slideshow', 'wp-eis'); ?>">
					<input type="submit" name="advanced-mode" id="advanced-mode" class="button" value="<?php _e('Advanced Mode', 'wp-eis'); ?>">
				</p>
				<div class="wp-eis-respond-fixed"></div>
			</form>
		</div>
		<script type="text/javascript">
			jQuery(function($) {
				$('.advanced-mode').hide();
				var oldTxt = $('#advanced-mode').val();
				$('#advanced-mode').bind('click', function(e) {
					e.preventDefault();
					$(this).toggleClass('basic-mode');
					$('.advanced-mode').fadeToggle(500);
					if( $(this).hasClass('basic-mode') ) { 
						$(this).val('<?php _e('Basic Mode', 'wp-eis');?>'); 
					} else {
						$(this).val(oldTxt); 
					}
				});
				$('#add-new-slideshow').bind('submit', function(e) {
					e.preventDefault();
					var slidename = $('#wp-eis-slidename').val(),
						animation = $('#wp-eis-animation').val(),
						autoplay = $('#wp-eis-autoplay').val(),
						slideshowInterval = $('#wp-eis-slideshow-interval').val(),
						speed = $('#wp-eis-speed').val(),
						easing = $('#wp-eis-easing').val(),
						titlesFactor = $('#wp-eis-titlesFactor').val(),
						titlesSpeed = $('#wp-eis-titlesSpeed').val(),
						titlesEasing = $('#wp-eis-titlesEasing').val(),
						thumbMaxWidth = $('#wp-eis-thumbMaxWidth').val(),
						data = $(this).serialize();

					if(slidename == '') {
						$('#wp-eis-slidename').addClass('wp-eis-error').attr('placeholder', '<?php _e('Please enter a valid slide name', 'wp-eis'); ?>');
						$('#wp-eis-slidename').bind('input', function() {
							$(this).removeClass('wp-eis-error');
						});
						return false;
					}
					
					$.ajax({
						type: 'POST',
						data: data + "&action=wp_eis_add_slideshow_ajax",
						url: ajaxurl,
						beforeSend: function() {
							$('.wp-eis-respond-fixed').css({'display': 'inline-block'}).removeClass('success-respond');
							$('.wp-eis-respond-fixed').html('<h2 class="sending-data"><?php _e('Sending your data','wp-eis'); ?></h2>');
						},
						success: function(response) {
							if( response ) {
								$('.wp-eis-respond-fixed').find('h2').remove();
								$('.wp-eis-respond-fixed').html(response);
							} else {
								$('.wp-eis-respond-fixed').html(response).addClass('error-respond')
								.delay(1000).fadeOut(1000, function() {
									$(this).removeClass().html('<a href="" title="<?php _e('Try again', 'wp-eis'); ?>" class="reload button"><?php _e('Try again', 'wp-eis'); ?></a>').fadeIn();	
								});
							}
						}
					});
				});
				$('.reload').bind('click', function() {
					location.reload();
				});
			}); 
		</script>
		<?php
	}

	public function wp_eis_add_images_page()
	{
		global $wp_roles;
		if ( is_user_logged_in() ) {
			$add_capability  = isset( $this->options['eis_add_capability'] ) ? $this->options['eis_add_capability'] : '';
			$admin = array('administrator'=>'Administrator');
			$add_capability = array_merge( $admin, (array)$add_capability );

			$current_user = wp_get_current_user();
			$current_user_role = array_shift( $current_user->roles );
			if( is_array($add_capability) ) {
				if( !array_key_exists($current_user_role, $add_capability) ) {
					echo '<div class="error-happend">';
					wp_die(__('You do not have sufficient permissions to access this page.', 'wp-eis'));
					echo "</div>";
				}
			}
		} else {
			wp_die(__('You do not have sufficient permissions to access this page.', 'wp-eis'));
		}
		wp_enqueue_script( 'wp-ajax-response' );
		wp_enqueue_script('image-edit');
		wp_enqueue_style('imgareaselect');

		wp_enqueue_script('plupload-handlers');
		$title = __('Upload Images into Slide', 'wp-eis');
		$post_id = 0;
		$form_class = 'media-upload-form type-form validate wp-eis-add-images';

		if ( get_user_setting('uploader') || isset( $_GET['browser-uploader'] ) )
			$form_class .= ' html-uploader';
		?>
		<div class="wrap">

			<div id="icon-upload" class="icon32"><br></div>
			<h2><?php echo esc_html( $title ); ?></h2>

			<form enctype="multipart/form-data" method="post" action="<?php echo admin_url('media-new.php'); ?>" class="<?php echo $form_class; ?>" id="file-form">

			<?php media_upload_form(); ?>

			<script type="text/javascript">
			var post_id = <?php echo $post_id; ?>, shortform = 3;
			</script>
			<script type="text/javascript">
				jQuery(function($){
					var preloaded = $(".media-item.preloaded");
					if ( preloaded.length > 0 ) {
						preloaded.each(function(){
							prepareMediaItem({id:this.id.replace(/[^0-9]/g, '')},'');
						});
					}
					updateMediaForm();
					post_id = 0;
					shortform = 1;
				});
			</script>
			<input type="hidden" name="post_id" id="post_id" value="<?php echo $post_id; ?>" />
			<?php wp_nonce_field('media-form'); ?>
			<div id="media-items" class="hide-if-no-js"></div>
			<div class="select-slidename">
				<label for="wp-eis-slidename"><?php _e('Select your slide name :', 'wp-eis');?></label>
				<?php  
					global $wpdb;
					$get_eis_slidenames = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}eis_name");
				?>
				<?php if ( count($get_eis_slidenames) > 0 ): ?>
					<select name="wp_eis_slidename" id="wp-eis-slidename">
							<option value="default"><?php _e('Select your slide name', 'wp-eis'); ?></option>
						<?php foreach ($get_eis_slidenames as $get_eis_slidename): ?>
							<option value="<?php echo trim(esc_attr($get_eis_slidename->name)); ?>"><?php echo $get_eis_slidename->name; ?></option>
						<?php endforeach ?>
					</select>
				<?php endif ?>
			</div>
			<p class="submit">
				<input type="submit" name="add-titles" id="add-titles" class="button savebutton hidden" value="<?php _e('Add Titles', 'wp-eis'); ?>"  />
				<input type="submit" name="add-images" id="add-images" class="button savebutton hidden" value="<?php _e('Add Images', 'wp-eis'); ?>"  />
			</p>
			<div class="wp-eis-respond-fixed"></div>
			</form>
		</div>
		<script type="text/javascript">
			jQuery(function($) {
				$('.wp-eis-add-images .drag-drop-info').html('<?php _e('Drop your images here', 'wp-eis'); ?>');
				$('#add-titles').one('click', function(e) {
					e.preventDefault();
					$('.media-item').each(function() {
						var id = /\d+(?:\.\d+)?/.exec( $(this).find('.urlfield').attr('name') )[0],
							h2Title = '<div class="meta-images-slide meta-images-slide-top"><label for="title-h2-'+ id +'"><?php _e('Title in h2 Tag ( optional ) :', 'wp-eis'); ?></label><p><textarea name="title_h2_'+ id +'"  id="title-h2-'+ id +'" value="" class="large-text code title-h2" /></textarea><span class="description"><?php esc_html_e('You can use these tags <a href=""></a><span></span><em></em><i></i><strong></strong>', 'wp-eis'); ?></span></p></div>',
							h3Title = '<div class="meta-images-slide"><label for="title-h3-'+ id +'"><?php _e('Title in h3 Tag ( optional ) :', 'wp-eis'); ?></label><p><textarea name="title_h3_'+ id +'"  id="title-h3-'+ id +'" value="" class="large-text code title-h3" /></textarea><span class="description"><?php esc_html_e('You can use these tags <a href=""></a><span></span><em></em><i></i><strong></strong>', 'wp-eis'); ?></span></p></div>';
							order = '<div class="meta-images-slide"><label for="order-'+ id +'"><?php _e('Order ( required ) :', 'wp-eis'); ?></label><input type="text" name="order_'+ id +'"  id="order-'+ id +'" value="" class="small-text order-num" /><span class="description order-error"></span</div>';
						$(h2Title).appendTo( $(this) );
						$(h3Title).appendTo( $(this) );
						$(order).appendTo( $(this) );
					});

					$(this).prop('disabled', true);
					$(this).removeClass('checked-btn');
					$('.wp-eis-add-images #plupload-browse-button').prop('disabled', true);
				});
				$('#add-images').bind('click', function(e) {
					e.preventDefault();
					var nameField = new Array(), 
						urlField = new Array(),
						h2Input = new Array(),
						h3Input = new Array(),
						order = new Array(),
						$this = $( this );
					$('.media-item .urlfield').each(function(i) {
						nameField[i] = /\d+(?:\.\d+)?/.exec( $(this).attr('name') )[0];
						urlField[i] = $(this).val();
					});
					$('.media-item').each(function(i) {
						h2Input[i] = $(this).find('textarea.title-h2').val();
						h3Input[i] = $(this).find('textarea.title-h3').val();
						order[i] = $(this).find('input.order-num').val();
					});
					var id = nameField.join("|"),
						url = urlField.join("|")
						h2Titles = h2Input.join("|"),
						h3Titles = h3Input.join("|"),
						orders = order.join("|"),
						slideName = $('#wp-eis-slidename').val();

					if( $('#add-titles').attr('disabled') !== 'disabled') {
						$('#add-titles').addClass('checked-btn');
						return false;
					}

					for (var i = 0, l = nameField.length; i < l ; i++) {
						// if( $('#title-h2-'+ nameField[i]).val() == '') {
						// 	$('#title-h2-'+ nameField[i]).addClass('wp-eis-error').attr('placeholder', '<?php _e('Please enter your title', 'wp-eis');?>');
							
						// 	$('#title-h2-'+ nameField[i]).bind('input', function() {
						// 		$(this).removeClass('wp-eis-error');
						// 	});
						// 	return false;
						// }
						// if( $('#title-h3-'+ nameField[i]).val() == '') {
						// 	$('#title-h3-'+ nameField[i]).addClass('wp-eis-error').attr('placeholder', '<?php _e('Please enter your title', 'wp-eis');?>');
							
						// 	$('#title-h3-'+ nameField[i]).bind('input', function() {
						// 		$(this).removeClass('wp-eis-error');
						// 	});
						// 	return false;
						// }

						if( isNaN( parseInt( $('#order-'+ nameField[i]).val(), 10 ) ) || parseInt( $('#order-'+ nameField[i]).val(), 10 ) < 1) {
							$('#order-'+ nameField[i]).addClass('wp-eis-error');
							$('#order-'+ nameField[i]).next('span.order-error').html('<?php _e('Please enter a numeric value', 'wp-eis');?>');
							
							$('#order-'+ nameField[i]).bind('input', function() {
								$(this).removeClass('wp-eis-error');
							});
							return false;
						} else { $('#order-'+ nameField[i]).next('span.order-error').html(''); }
					};

					if(slideName == '' || slideName == 'default') {
						$('#wp-eis-slidename').addClass('wp-eis-error');
						$('#wp-eis-slidename').bind('change', function() {
							$(this).removeClass('wp-eis-error');
						});
						return false;
					}

					$.ajax({
						type: 'POST',
						data: 'slide_name=' + slideName + '&h2_title=' + encodeURIComponent( h2Titles ) + '&h3_title=' + encodeURIComponent( h3Titles ) + '&order=' + orders + '&attach_id=' + id + '&url=' + url + '&action=wp_eis_add_images_ajax',
						url: ajaxurl,
						beforeSend: function() {
							$this.prop('disabled', true);
							$('.wp-eis-respond-fixed').html('<h2 class="sending-data"><?php _e('Sending your data','wp-eis'); ?></h2>').css({'display': 'inline-block'});
						},
						success: function(response) {
							if( response ) {
								$('.wp-eis-respond-fixed').find('h2').remove();
								$('.wp-eis-respond-fixed').html(response)
								.delay(1000).append('<a href="" title="<?php _e('Refresh', 'wp-eis'); ?>" class="reload button button-primary"><?php _e('Refresh', 'wp-eis'); ?></a>').fadeIn(1000);
							} else {
								$('.wp-eis-respond').html('<h2>' + response + '</h2>').addClass('error-respond')
								.delay(1000).fadeOut(1000, function() {
									$(this).removeClass().html('<a href="" title="<?php _e('Try again', 'wp-eis'); ?>" class="reload button"><?php _e('Try again', 'wp-eis'); ?></a>').fadeIn();	
								});
							}
						}
					});
				});
				$('.reload').bind('click', function() {
					location.reload();
				});
			}); 
		</script>
		<?php
	}

	public function wp_eis_all_slides_page()
	{
		global $wp_roles;
		if ( is_user_logged_in() ) {
			$admin = array('administrator'=>'Administrator');

			$edit_capability  = isset( $this->options['eis_edit_capability'] ) ? $this->options['eis_edit_capability'] : '';
			$edit_capability = array_merge( $admin, (array)$edit_capability );

			$delete_capability  = isset( $this->options['eis_delete_capability'] ) ? $this->options['eis_delete_capability'] : '';
			$delete_capability = array_merge( $admin, (array)$delete_capability );

			$current_user = wp_get_current_user();
			$current_user_role = array_shift( $current_user->roles );

			if( is_array($edit_capability) ) {
				if( !array_key_exists($current_user_role, $edit_capability) ) {
					$edit_authority = false;
					$wp_edi_die = __('You do not have sufficient permissions to edit this item.', 'wp-eis');
					// wp_die(__('You do not have sufficient permissions to access this page.', 'wp-eis'));
				} else{
					$edit_authority = true;
				}
			}

			if( is_array($delete_capability) ) {
				if( !array_key_exists($current_user_role, $delete_capability) ) {
					$delete_authority = false;
					$wp_del_die = __('You do not have sufficient permissions to delete this item.', 'wp-eis');
					// wp_die(__('You do not have sufficient permissions to access this page.', 'wp-eis'));
				} else {
					$delete_authority = true;
				}
			}
		} else {
			wp_die(__('You do not have sufficient permissions to access this page.', 'wp-eis'));
		}

		if ( ( isset($edit_authority) && $edit_authority == false ) && ( isset($delete_authority) && $delete_authority == false ) ) {
			 echo '<div class="error-happend">';
			 wp_die(__('You do not have sufficient permissions to access this page.', 'wp-eis'));
			 echo "</div>";
		}
		?>
		<div class="wrap">
			<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
			<h2><?php _e('Slideshow Lists', 'wp-eis'); ?></h2>
			<p></p>
			<p></p>
			<form action="" method="post" id="all-slideshows">
				<table class="widefat fixed" cellspacing="0">
				<thead>
					<tr>
						<th id="cb" scope="col" class="manage-column column-cb check-column"><input type="checkbox" name="checkAll" value="" style="margin-bottom: 0;display: block;" /></th>
						<th scope="col" class="manage-column column-name" width="10%"><?php _e('ID', 'wp-eis'); ?></th>
						<th scope="col" class="manage-column column-name" width="20%"><?php _e('Slideshow Name', 'wp-eis'); ?></th>
						<th scope="col" class="manage-column column-name" width="35%"><?php _e('Shortcode', 'wp-eis'); ?></th>
						<th scope="col" class="manage-column column-name" width="45%"><?php _e('Theme Function', 'wp-eis'); ?></th>
					</tr>
				</thead>


				<tbody>

					<?php  
						global $wpdb;
						$get_eis_names = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}eis_name");
						$page = $_GET['page']; 
					?>

					<?php if( count($get_eis_names ) > 0 ) : ?>
						<?php foreach($get_eis_names as $get_eis_name) : ?>
							<tr class="" valign="middle">
								<th class="check-column" scope="row" style="vertical-align: middle;"><input type="checkbox" name="column_ID[]" value="<?php echo $get_eis_name->ID_name ; ?>" /></th>
								<td class="column-name" style="vertical-align: middle;"><?php echo $get_eis_name->ID_name ; ?></td>
								<td class="column-name" style="vertical-align: middle;">
									<strong><a class="row-title edit-link" href="?page=<?php echo $page; ?>&action=edit&ID=<?php echo $get_eis_name->ID_name; ?>" title="<?php _e('Edit', 'wp-eis'); ?> “<?php echo $get_eis_name->name ; ?>”"><?php echo $get_eis_name->name ; ?></a></strong>
								<div class="row-actions"><span class="edit"><a href="?page=<?php echo $page; ?>&action=edit&ID=<?php echo $get_eis_name->ID_name; ?>" title="<?php _e('Edit this image', 'wp-eis'); ?>"><?php _e('Edit', 'wp-eis'); ?></a> | </span><span class="trash"><a class="delete-slideshow" title="<?php _e('Delete this Slideshow', 'wp-eis'); ?>" href="?page=<?php echo $page; ?>&action=delete&ID=<?php echo $get_eis_name->ID_name; ?>"><?php _e('Delete', 'wp-eis'); ?></a></span></div>
								</td>
								<td class="column-name" style="vertical-align: middle;">
									<span class="code-align" style="display:block;">[wp_eis id="<?php echo $get_eis_name->ID_name ; ?>"]</span>
									<span class="code-align" style="display:block;">[wp_eis id="<?php echo $get_eis_name->ID_name ; ?>"]<?php _e('Slideshow Title', 'wp-eis'); ?>[/wp_eis]</span>
								</td>
								<td class="column-name" style="vertical-align: middle;"><span class="code-align"><code><?php echo esc_attr("<?php echo do_shortcode('[wp_eis id=\"".$get_eis_name->ID_name."\"]'); ?>") ?></code></span></td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr>
							<td colspan="7"><?php _e('Not Found!', 'wp-eis'); ?></td>
						</tr>
					<?php endif; ?>

				</tbody>

				<tfoot>
					<tr>
						<th id="cb" scope="col" class="manage-column column-cb check-column"><input type="checkbox" name="checkAll" value="" style="margin-bottom: 0;display: block;" /></th>
						<th scope="col" class="manage-column column-name" width="10%"><?php _e('ID', 'wp-eis'); ?></th>
						<th scope="col" class="manage-column column-name" width="20%"><?php _e('Slideshow Name', 'wp-eis'); ?></th>
						<th scope="col" class="manage-column column-name" width="35%"><?php _e('Shortcode', 'wp-eis'); ?></th>
						<th scope="col" class="manage-column column-name" width="45%"><?php _e('Theme Function', 'wp-eis'); ?></th>
					</tr>				
				</tfoot>
				</table>
				<div class="tablenav">
					<div class="alignleft actions">
						<select name="action" id="action">
							<option value="default" selected="selected"><?php _e('Select remove option', 'wp-eis'); ?></option>
							<option value="trash"><?php _e('Remove', 'wp-eis'); ?></option>
						</select>
						<input value="<?php _e('Apply', 'wp-eis'); ?>" name="doaction" id="doaction-remove" class="button-secondary action" type="submit"/>
					</div>
					<br class="clear">
				</div>	
				<div class="wp-eis-respond-fixed"></div>
			</form>

			
			<?php if ( isset($_GET['action']) && $_GET['action'] == 'edit' ): ?>
				<?php if ( isset($_GET['ID']) ): ?>
				<?php if ( isset($edit_authority) && $edit_authority == false ) : ?>
					<?php echo '<div class="error-happend"><p>'.$wp_edi_die.'</p></div>'; ?>
				<?php else: ?>
					<?php 
						$id = $_GET['ID']; 
						$get_eis_name_by_id = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}eis_name WHERE ID_name=$id");
						$get_eis_name_by_id = $get_eis_name_by_id[0];
					?>
					<?php if ( count($get_eis_name_by_id) > 0 ): ?>
						<h1><?php _e('Edit Slideshow', 'wp-eis'); ?></h1>
						<form action="" method="post" id="update-slideshow">
							<table class="form-table">
							<tbody>
								<tr valign="top">
									<th scope="row"><label for="wp-eis-slidename"><?php _e('Name Of Slideshow :', 'wp-eis'); ?></label></th>
									<td>
										<input name="wp_eis_ID_name" type="hidden" id="wp-eis-id-name" value="<?php echo $get_eis_name_by_id->ID_name; ?>" class="regular-text code">
										<input name="wp_eis_slidename" type="text" id="wp-eis-slidename" value="<?php echo $get_eis_name_by_id->name; ?>" class="regular-text code">
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><label for="wp-eis-animation"><?php _e('Animation Type :', 'wp-eis'); ?></label></th>
									<td>
										<select id="wp-eis-animation" name="wp_eis_animation" class="regular-text code">
											<?php $options = array( 'sides' => __('Sides', 'wp-eis'), 'center'=>__('Center', 'wp-eis') ); ?>
											<?php foreach ($options as $option => $value): ?>
												<?php 
													$selected = ( $get_eis_name_by_id->autoplay == $option ) ?
														'selected="selected"' :
														'';
												?>
													<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
											<?php endforeach ?>
										</select>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><label for="wp-eis-autoplay"><?php _e('Autoplay :', 'wp-eis'); ?></label></th>
									<td>
										<select id="wp-eis-autoplay" name="wp_eis_autoplay" class="regular-text code">
											<?php $options = array( 'false' => __('False', 'wp-eis'), 'true'=>__('True', 'wp-eis') ); ?>
											<?php foreach ($options as $option => $value): ?>
												<?php 
													$selected = ( $get_eis_name_by_id->autoplay == $option ) ?
														'selected="selected"' :
														'';
												?>
													<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
											<?php endforeach ?>
										</select>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><label for="wp-eis-slideshow-interval"><?php _e('Slideshow Interval :', 'wp-eis'); ?></label></th>
									<td><input name="wp_eis_slideshow_interval" type="number" id="wp-eis-slideshow-interval" value="<?php echo $get_eis_name_by_id->slideshow_interval; ?>" class="small-text code"></td>
								</tr>
								<tr valign="top">
									<th scope="row"><label for="wp-eis-speed"><?php _e('Slide Speed :', 'wp-eis'); ?></label></th>
									<td><input name="wp_eis_speed" type="number" id="wp-eis-speed" value="<?php echo $get_eis_name_by_id->speed; ?>" class="small-text code"></td>
								</tr>
								<tr valign="top">
									<th scope="row"><label for="wp-eis-easing"><?php _e('Slide Easing Option :', 'wp-eis'); ?></label></th>
									<td><input name="wp_eis_easing" type="text" id="wp-eis-easing" value="<?php echo $get_eis_name_by_id->easing; ?>" class="regular-text code"></td>
								</tr>
								<tr valign="top">
									<th scope="row"><label for="wp-eis-titlesFactor"><?php _e('Titles Factor :', 'wp-eis'); ?></label></th>
									<td><input name="wp_eis_titlesFactor" type="number" step="0.01" name="quantity" min="0.00" max="1.00" id="wp-eis-titlesFactor" value="<?php echo $get_eis_name_by_id->titles_factor; ?>" class="small-text code"></td>
								</tr>
								<tr valign="top">
									<th scope="row"><label for="wp-eis-titlesSpeed"><?php _e('Titles Speed :', 'wp-eis'); ?></label></th>
									<td><input name="wp_eis_titlesSpeed" type="number" id="wp-eis-titlesSpeed" value="<?php echo $get_eis_name_by_id->titles_speed; ?>" class="small-text code"></td>
								</tr>
								<tr valign="top">
									<th scope="row"><label for="wp-eis-titlesEasing"><?php _e('Titles Easing  Option :', 'wp-eis'); ?></label></th>
									<td><input name="wp_eis_titlesEasing" type="text" id="wp-eis-titlesEasing" value="<?php echo $get_eis_name_by_id->titles_easing; ?>" class="regular-text code"></td>
								</tr>
								<tr valign="top">
									<th scope="row"><label for="wp-eis-thumbMaxWidth"><?php _e('Thumbnail Max Width :', 'wp-eis'); ?></label></th>
									<td><input name="wp_eis_thumbMaxWidth" type="number" id="wp-eis-thumbMaxWidth" value="<?php echo $get_eis_name_by_id->thumb_max_width; ?>" class="small-text code"></td>
								</tr>
							</tbody>
							</table>
							<p class="submit">
								<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Update Slideshow', 'wp-eis'); ?>">
								<a href="?page=<?php echo $page; ?>" class="button"><?php _e('Cancel', 'wp-eis'); ?></a>
							</p>
							<div class="wp-eis-respond-fixed-bottom"></div>
						</form>			
					<?php endif ?>
				<?php endif ?>
				<?php endif ?>
			<?php endif ?>
		</div>
		<script type="text/javascript">
		jQuery(function($) {
			<?php if ( ( isset($edit_authority) && $edit_authority == false ) && ( isset($delete_authority) && $delete_authority == false ) ) : ?>
				$('#all-slideshows .edit').bind('click', function(e) {
					e.preventDefault();
					alert('<?php echo isset( $wp_edi_die ) ? $wp_edi_die : ''; ?>');
				});
				$('#all-slideshows .edit-link').bind('click', function(e) {
					e.preventDefault();
					alert('<?php echo isset( $wp_edi_die ) ? $wp_edi_die : ''; ?>');
				});
				$('#all-slideshows .trash').bind('click', function(e) {
					e.preventDefault();
					alert('<?php echo isset( $wp_del_die ) ? $wp_del_die : ''; ?>');
				});
				$('#doaction-remove').bind('click', function(e) {
					e.preventDefault();
					alert('<?php echo isset( $wp_del_die ) ? $wp_del_die : ''; ?>');
				});
			<?php else: ?>
			<?php if ( isset($edit_authority) && $edit_authority !== false ) : ?>
			$('#update-slideshow').bind('submit', function(e) {
				e.preventDefault();
				var name = $('#wp-eis-slidename').val(),
					animation = $('#wp-eis-animation').val(),
					autoplay = $('#wp-eis-autoplay').val(),
					slideshowInterval = $('#wp-eis-slideshow-interval').val(),
					speed = $('#wp-eis-speed').val(),
					easing = $('#wp-eis-easing').val(),
					titlesFactor = $('#wp-eis-titlesFactor').val(),
					titlesSpeed = $('#wp-eis-titlesSpeed').val(),
					titlesEasing = $('#wp-eis-titlesEasing').val(),
					thumbMaxWidth = $('#wp-eis-thumbMaxWidth').val(),
					data = $(this).serialize();

				if(name == '') {
					$('#wp-eis-name').addClass('wp-eis-error').attr('placeholder', '<?php _e('Please enter a valid Slide name', 'wp-eis'); ?>');
					$('#wp-eis-name').bind('input', function() {
						$(this).removeClass('wp-eis-error');
					});
					return false;
				}
				$.ajax({
					type: 'POST',
					data: data + "&action=wp_eis_update_slideshow_ajax",
					url: ajaxurl,
					beforeSend: function() {
						$('.wp-eis-respond-fixed-bottom').css({'display': 'inline-block'}).removeClass('success-respond');
						$('.wp-eis-respond-fixed-bottom').html('<h2 class="sending-data"><?php _e('Sending your data','wp-eis'); ?></h2>');
					},
					success: function(response) {
						if( response ) {
							$('.wp-eis-respond-fixed-bottom').find('h2').remove();
							$('.wp-eis-respond-fixed-bottom').html(response)
							.delay(1000).append('<a href="?page=<?php echo $page; ?>" title="<?php _e('Refresh', 'wp-eis'); ?>" class="reload button button-primary"><?php _e('Refresh now', 'wp-eis'); ?></a>').fadeIn(1000)
							.append('<span class="description desc-refresh"><?php _e('Or, Will be Refreshed after 5 secs', 'wp-eis'); ?></span>');
							setTimeout(function(){
							   window.location = "?page=<?php echo $page; ?>";
							}, 5000);
						} else {
							$('.wp-eis-respond-fixed-bottom').html(response).addClass('error-respond')
							.delay(1000).fadeOut(1000, function() {
								$(this).removeClass().html('<a href="" title="<?php _e('Try again', 'wp-eis'); ?>" class="reload button"><?php _e('Try again', 'wp-eis'); ?></a>').fadeIn();	
							});
						}
					}
				});
			});
			<?php else: ?>
				$('#all-slideshows .edit').bind('click', function(e) {
					e.preventDefault();
					alert('<?php echo isset( $wp_edi_die ) ? $wp_edi_die : ''; ?>');
				});
				$('#all-slideshows .edit-link').bind('click', function(e) {
					e.preventDefault();
					alert('<?php echo isset( $wp_edi_die ) ? $wp_edi_die : ''; ?>');
				});
			<?php endif; ?>
				
			<?php if ( isset($delete_authority) && $delete_authority == true ) : ?>
			$('.delete-slideshow').bind('click', function(e) {
				e.preventDefault();
				var id = $(this).attr('href').match(/ID=[0-9 -()+]+$/)[0],
					agree = confirm('<?php _e('Are you sure?', 'wp-eis'); ?>');
				if(agree) {
					$.ajax({
						type: 'POST',
						data: id + "&action=wp_eis_delete_slideshow_ajax",
						url: ajaxurl,
						beforeSend: function() {
							$('.wp-eis-respond-fixed').css({'display': 'inline-block'}).removeClass('success-respond');
							$('.wp-eis-respond-fixed').html('<h2 class="sending-data"><?php _e('Sending your data','wp-eis'); ?></h2>');
						},
						success: function(response) {
							if( response ) {
								$('.wp-eis-respond-fixed').find('h2').remove();
								$('.wp-eis-respond-fixed').html(response)
								.delay(1000).append('<a href="" title="<?php _e('Refresh', 'wp-eis'); ?>" class="reload button button-primary"><?php _e('Refresh now', 'wp-eis'); ?></a>').fadeIn(1000)
								.append('<span class="description desc-refresh"><?php _e('Or, Will be Refreshed after 10 secs', 'wp-eis'); ?></span>');
								setTimeout(function(){
								   window.location = "?page=<?php echo $page; ?>";
								}, 10000);
							} else {
								$('.wp-eis-respond-fixed').html(response).addClass('error-respond')
								.delay(1000).fadeOut(1000, function() {
									$(this).removeClass().html('<a href="" title="<?php _e('Try again', 'wp-eis'); ?>" class="reload button"><?php _e('Try again', 'wp-eis'); ?></a>').fadeIn();	
								});
							}
						}
					});
				}
			});


			$('#doaction-remove').bind('click', function(e) {
				e.preventDefault();

				var idInput = new Array(),
					idInputs = new Array();

				if( $('#action').val() == 'default' ){
					$('#action').addClass('wp-eis-error');
					$('#action').bind('change', function() {
						$(this).removeClass('wp-eis-error');
					});
					return false;
				}

				$('.check-column input').each(function(i) {
					var that = $(this);
					if( that.is(':checked') ) {
						idInput[i] = that.val();
					}
				});
				
				function filterArray(actual){
					var newArray = new Array();
					for(var i = 0; i<actual.length; i++){
						if (actual[i]){
							newArray.push(actual[i]);
						}
					}
					return newArray;
				}
				
				for (var i = 0, l = idInput.length; i < l; i++) {
					idInputs[i] = idInput[i];
				};
				idInputs = filterArray(idInputs);
				var id = idInputs.join("|"),
					agree = confirm('<?php _e('Are you sure?', 'wp-eis');?>');

				if( idInputs.length >= 2) {
					if(agree) {
						$.ajax({
							type: 'POST',
							data: "ID=" + id + "&action=wp_eis_delete_slideshow_ajax",
							url: ajaxurl,
							beforeSend: function() {
								$('.wp-eis-respond-fixed').css({'display': 'inline-block'}).removeClass('success-respond');
								$('.wp-eis-respond-fixed').html('<h2 class="sending-data"><?php _e('Sending your data','wp-eis'); ?></h2>');
							},
							success: function(response) {
								if( response ) {
									$('.wp-eis-respond-fixed').find('h2').remove();
									$('.wp-eis-respond-fixed').html(response)
									.delay(1000).append('<a href="" title="<?php _e('Refresh', 'wp-eis'); ?>" class="reload button button-primary"><?php _e('Refresh now', 'wp-eis'); ?></a>').fadeIn(1000)
									.append('<span class="description desc-refresh"><?php _e('Or, Will be Refreshed after 10 secs', 'wp-eis'); ?></span>');
									setTimeout(function(){
									   window.location = "?page=<?php echo $page; ?>";
									}, 10000);
								} else {
									$('.wp-eis-respond-fixed').html(response).addClass('error-respond')
									.delay(1000).fadeOut(1000, function() {
										$(this).removeClass().html('<a href="" title="<?php _e('Try again', 'wp-eis'); ?>" class="reload button"><?php _e('Try again', 'wp-eis'); ?></a>').fadeIn();	
									});
								}
							}
						});
					}
				} else if( idInputs.length == 1 ) {
					alert("<?php _e('Please delete this ID by deleting it from the delete link under the Slide Name field', 'wp-eis');?>");
				} else {
					alert("<?php _e('Please select the IDs you want delete theme', 'wp-eis');?>");
				}
			});
			<?php else: ?>		
				$('#all-slideshows .trash').bind('click', function(e) {
					e.preventDefault();
					alert('<?php echo isset( $wp_del_die ) ? $wp_del_die : ''; ?>');
				});
				$('#doaction-remove').bind('click', function(e) {
					e.preventDefault();
					alert('<?php echo isset( $wp_del_die ) ? $wp_del_die : ''; ?>');
				});
			<?php endif; ?>
			<?php endif; ?>
		});
		</script>
		<?php
	}

	public function wp_eis_all_images_page()
	{
		global $wp_roles;
		if ( is_user_logged_in() ) {
			$admin = array('administrator'=>'Administrator');

			$edit_capability  = isset( $this->options['eis_edit_capability'] ) ? $this->options['eis_edit_capability'] : '';
			$edit_capability = array_merge( $admin, (array)$edit_capability );

			$delete_capability  = isset( $this->options['eis_delete_capability'] ) ? $this->options['eis_delete_capability'] : '';
			$delete_capability = array_merge( $admin, (array)$delete_capability );

			$current_user = wp_get_current_user();
			$current_user_role = array_shift( $current_user->roles );
			
			if( is_array($edit_capability) ) {
				if( !array_key_exists($current_user_role, $edit_capability) ) {
					$edit_authority = false;
					$wp_edi_die = __('You do not have sufficient permissions to edit this item.', 'wp-eis');
					// wp_die(__('You do not have sufficient permissions to access this page.', 'wp-eis'));
				} else{
					$edit_authority = true;
				}
			}

			if( is_array($delete_capability) ) {
				if( !array_key_exists($current_user_role, $delete_capability) ) {
					$delete_authority = false;
					$wp_del_die = __('You do not have sufficient permissions to delete this item.', 'wp-eis');
					// wp_die(__('You do not have sufficient permissions to access this page.', 'wp-eis'));
				} else {
					$delete_authority = true;
				}
			}
		} else {
			wp_die(__('You do not have sufficient permissions to access this page.', 'wp-eis'));
		}

		if ( ( isset($edit_authority) && $edit_authority == false ) && ( isset($delete_authority) && $delete_authority == false ) ) {
			 echo '<div class="error-happend">';
			 wp_die(__('You do not have sufficient permissions to access this page.', 'wp-eis'));
			 echo "</div>";
		}

		?>

		<div class="wrap">
			<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
			<h2><?php _e('All Images List', 'wp-eis'); ?></h2>
			<p></p>
			<p></p>
			<form action="" method="post" id="all-images">
				<table class="widefat fixed" cellspacing="0">
				<thead>
					<tr>
						<th id="cb" scope="col" class="manage-column column-cb check-column"><input type="checkbox" name="checkAll" value="" style="margin-bottom: 0;display: block;" /></th>
						<th scope="col" class="manage-column column-name" width="10%"><?php _e('ID', 'wp-eis'); ?></th>
						<th scope="col" class="manage-column column-name" width="30%"><?php _e('Slideshow Name', 'wp-eis'); ?></th>
						<th scope="col" class="manage-column column-name" width="20%"><?php _e('Order', 'wp-eis'); ?></th>
						<th scope="col" class="manage-column column-name" width="50%"><?php _e('Thumbnail', 'wp-eis'); ?></th>
					</tr>
				</thead>


				<tbody>

					<?php  
						global $wpdb;
						$page = $_GET['page']; 
						$get_paged = isset( $_GET['paged'] ) ? $_GET['paged'] : 1; 
						$page_limit = 5;
						$start_from = ( $get_paged == 1 ) ? 0 : ( $get_paged - 1 ) * $page_limit;
						$all_items = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}eis_items");
						$all_items_num = count($all_items );
						$get_eis_items = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}eis_items LIMIT $start_from, $page_limit");
					?>

					<?php if( count($get_eis_items ) > 0 ) : ?>
						<?php foreach( $get_eis_items as $get_eis_item) : ?>
							<tr class="" valign="middle">
								<th class="check-column" scope="row" style="vertical-align: middle;"><input type="checkbox" name="column_ID[]" value="<?php echo $get_eis_item->ID ; ?>" /></th>
								<td class="column-name" style="vertical-align: middle;"><?php echo $get_eis_item->ID ; ?></td>
								<td class="column-name" style="vertical-align: middle;">
									<strong><a class="row-title edit-link" href="?page=<?php echo $page; ?>&paged=<?php echo isset( $get_paged ) ? $get_paged : 1; ?>&action=edit&ID=<?php echo $get_eis_item->ID; ?>" title="<?php _e('Edit', 'wp-eis'); ?> “<?php echo $get_eis_item->name ; ?>”"><?php echo $get_eis_item->name ; ?></a></strong>
								<div class="row-actions"><span class="edit"><a href="?page=<?php echo $page; ?>&paged=<?php echo isset( $get_paged ) ? $get_paged : 1; ?>&action=edit&ID=<?php echo $get_eis_item->ID; ?>" title="<?php _e('Edit this image', 'wp-eis'); ?>"><?php _e('Edit', 'wp-eis'); ?></a> | </span><span class="trash"><a class="image-delete" title="<?php _e('Delete this image', 'wp-eis'); ?>" href="?page=<?php echo $page; ?>&action=delete&ID=<?php echo $get_eis_item->ID; ?>"><?php _e('Delete', 'wp-eis'); ?></a></span></div>
								</td>
								<td class="column-name" style="vertical-align: middle;"><?php echo !empty($get_eis_item->order) ? $get_eis_item->order : $get_eis_item->ID; ?></td>
								<td class="column-name column-item-image" style="vertical-align: middle;"><img src="<?php echo $get_eis_item->image; ?>" /></td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr>
							<td colspan="7"><?php _e('Not Found!', 'wp-eis'); ?></td>
						</tr>
					<?php endif; ?>

				</tbody>

				<tfoot>
					<tr>
						<th id="cb" scope="col" class="manage-column column-cb check-column"><input type="checkbox" name="checkAll" value="" style="margin-bottom: 0;display: block;" /></th>
						<th scope="col" class="manage-column column-name" width="10%"><?php _e('ID', 'wp-eis'); ?></th>
						<th scope="col" class="manage-column column-name" width="30%"><?php _e('Slideshow Name', 'wp-eis'); ?></th>
						<th scope="col" class="manage-column column-name" width="20%"><?php _e('Order', 'wp-eis'); ?></th>
						<th scope="col" class="manage-column column-name" width="50%"><?php _e('Thumbnail', 'wp-eis'); ?></th>
					</tr>
				</tfoot>
				</table>
				<div class="tablenav">
					<div class="alignleft actions">
						<select name="action" id="action">
							<option value="default" selected="selected"><?php _e('Select remove option', 'wp-eis'); ?></option>
							<option value="trash"><?php _e('Remove', 'wp-eis'); ?></option>
						</select>
						<input value="<?php _e('Apply', 'wp-eis'); ?>" name="doaction" id="doaction-remove" class="button-secondary action" type="submit"/>
					</div>
					<?php if ( $all_items_num > $page_limit ): ?>
					<div class="tablenav-pages">
						<span class="displaying-num"><?php echo $all_items_num; ?> <?php _e('items', 'wp-eis'); ?></span>
						<span class="pagination-links">
							<a class="first-page <?php echo ( $get_paged <=1 ) ? "disabled": ""; ?>" title="<?php _e('Go to the first page', 'wp-eis'); ?>" href="?page=<?php echo $page; ?>">«</a>
							<a class="prev-page <?php echo ( $get_paged <=1 ) ? "disabled": ""; ?>" title="<?php _e('Go to the previous page', 'wp-eis'); ?>" href="?page=<?php echo $page; ?>&paged=<?php echo ( $get_paged == 1 ) ? 1 : $get_paged - 1; ?>">‹</a>
							<span class="paging-input"><?php echo $get_paged; ?> <?php _e('of', 'wp-eis'); ?> <span class="total-pages"><?php echo ceil($all_items_num / $page_limit); ?></span></span>
							<a class="next-page <?php echo ( $get_paged >= ceil($all_items_num / $page_limit) ) ? "disabled": ""; ?>" title="<?php _e('Go to the next page', 'wp-eis'); ?>" href="?page=<?php echo $page; ?>&paged=<?php echo ( $get_paged >= ceil($all_items_num / $page_limit) ) ? $get_paged : $get_paged + 1; ?>">›</a>
							<a class="last-page <?php echo ( $get_paged >= ceil($all_items_num / $page_limit) ) ? "disabled": ""; ?>" title="<?php _e('Go to the last page', 'wp-eis'); ?>" href="?page=<?php echo $page; ?>&paged=<?php echo ceil($all_items_num / $page_limit); ?>">»</a>
						</span>
					</div>	
					<?php endif ?>
					<br class="clear">
					
				</div>
				<div class="wp-eis-respond-fixed"></div>	
			</form>

			<?php if ( isset($_GET['action']) && $_GET['action'] == 'edit' ): ?>
				<?php if ( isset($_GET['ID']) ): ?>
				<?php if ( isset($edit_authority) && $edit_authority == false ) : ?>
					<?php echo '<div class="error-happend"><p>'.$wp_edi_die.'</p></div>'; ?>
				<?php else: ?>
				<?php 
					$id = $_GET['ID']; 
					$get_eis_item_by_id = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}eis_items WHERE ID=$id");
					$get_eis_item_by_id = $get_eis_item_by_id[0];
				?>
				<?php if ( count($get_eis_item_by_id) > 0 ): ?>
					<h3><?php _e('Edit image', 'wp-eis'); ?></h3>
					<form action="" method="post" id="update-image">
						<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row"><label for="wp-eis-slidename"><?php _e('Slideshow Name :', 'wp-eis'); ?></label></th>
								<td>
									<input name="wp_eis_ID" type="hidden" id="wp-eis-id" value="<?php echo $get_eis_item_by_id->ID ; ?>" class="regular-text code">
									<?php  
										global $wpdb;
										$get_eis_names = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}eis_name");
										$current_eis_name = $get_eis_item_by_id->name;
									?>
									<?php if ( count($get_eis_names) > 0 ): ?>
										<select name="wp_eis_slidename" id="wp-eis-slidename">
											<?php foreach ($get_eis_names as $get_eis_name): ?>
											<?php  
												$selected = ( $current_eis_name == $get_eis_name->name ) ? 'selected="selected"' : '';
											?>
												<option value="<?php echo trim(esc_attr($get_eis_name->name)); ?>" <?php echo $selected; ?>><?php echo $get_eis_name->name; ?></option>
											<?php endforeach ?>
										</select>
									<?php endif ?>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="wp-eis-htwo"><?php _e('h2 Tag Title :', 'wp-eis'); ?></label></th>
								<td>
									<?php global $wp_version;
										  if (version_compare($wp_version, "3.4") >= 0) { ?>
										<div style="width: 625px;"><?php $this->wp_editor_t( stripslashes($get_eis_item_by_id->title_h2), 'wp-eis-htwo', 'wp_eis_htwo'); ?></div>
									<?php } else { ?>
									   <textarea name="wp_eis_htwo" id="wp-eis-htwo" rows="3" class="large-text code titleheidtor"><?php echo stripslashes($get_eis_item_by_id->title_h2); ?></textarea>
									<?php } ?>
									<!-- <textarea name="wp_eis_htwo" id="wp-eis-htwo" rows="3" class="large-text code titleheidtor"><?php // echo stripslashes($get_eis_item_by_id->title_h2); ?></textarea> -->
									<p class="description"><?php esc_html_e('You can use these tags <a href=""></a><span></span><em></em><i></i><strong></strong>', 'wp-eis'); ?></p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="wp-eis-hthree"><?php _e('h3 Tag Title :', 'wp-eis'); ?></label></th>
								<td>
									<?php if (version_compare($wp_version, "3.4") >= 0) { ?>
										<div style="width: 625px;"><?php $this->wp_editor_t( stripslashes($get_eis_item_by_id->title_h3), 'wp-eis-hthree', 'wp_eis_hthree'); ?></div>
									<?php } else { ?>
									   <textarea name="wp_eis_hthree" id="wp-eis-hthree" rows="3" class="large-text code titleheidtor"><?php echo stripslashes($get_eis_item_by_id->title_h3); ?></textarea>
									<?php } ?>
									<!-- <textarea name="wp_eis_hthree" id="wp-eis-hthree" rows="3" class="large-text code titleheidtor"><?php // echo stripslashes($get_eis_item_by_id->title_h3); ?></textarea> -->
									<p class="description"><?php esc_html_e('You can use these tags <a href=""></a><span></span><em></em><i></i><strong></strong>', 'wp-eis'); ?></p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="wp-eis-image"><?php _e('Image :', 'wp-eis'); ?></label></th>
								<td>
									<span class="eis-upload-image-default"><?php echo WP_EIS_URL.'images/default-preview.jpg'; ?></span>
									<input id="wp-eis-image" class="eis-upload-url" type="text" name="wp_eis_image" value="<?php echo $get_eis_item_by_id->image; ?>" />
									<input id="eis-upload-image" class="eis-upload-button button" type="button" name="upload_button" value="<?php _e('Change', 'wp-eis'); ?>" />
									<span class="eis-remove-image"><?php _e('Remove Image', 'wp-eis'); ?></span>
									<?php 
										if($get_eis_item_by_id->image) {
											echo '<img src="'.$get_eis_item_by_id->image.'" class="eis-upload-image eis-upload-image" />';
										}
									?>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="wp-eis-order"><?php _e('Order :', 'wp-eis'); ?></label></th>
								<td>
									<input name="wp_eis_order" type="text" id="wp-eis-order" value="<?php echo !empty($get_eis_item_by_id->order) ? $get_eis_item_by_id->order : $get_eis_item_by_id->ID; ?>" class="small-text code">
									<span class="description order-error"></span>
								</td>
							</tr>
						</tbody>
						</table>
						<p class="submit">
							<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Update Image', 'wp-eis'); ?>">
							<a href="?page=<?php echo $page; ?>&paged=<?php echo isset( $get_paged ) ? $get_paged : 1; ?>" title="<?php _e('Cancel', 'wp-eis'); ?>" class="button"><?php _e('Cancel', 'wp-eis'); ?></a>
						</p>
						<div class="wp-eis-respond-fixed-bottom"></div>
					</form>
				<?php endif ?>
				<?php endif ?>
				<?php endif ?>
			<?php endif ?>
		</div>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			<?php if ( ( isset($edit_authority) && $edit_authority == false ) && ( isset($delete_authority) && $delete_authority == false ) ) : ?>
				$('#all-images .edit').bind('click', function(e) {
					e.preventDefault();
					alert('<?php echo isset( $wp_edi_die ) ? $wp_edi_die : ''; ?>');
				});
				$('#all-images .edit-link').bind('click', function(e) {
					e.preventDefault();
					alert('<?php echo isset( $wp_edi_die ) ? $wp_edi_die : ''; ?>');
				});
				$('#all-images .trash').bind('click', function(e) {
					e.preventDefault();
					alert('<?php echo isset( $wp_del_die ) ? $wp_del_die : ''; ?>');
				});
				$('#doaction-remove').bind('click', function(e) {
					e.preventDefault();
					alert('<?php echo isset( $wp_del_die ) ? $wp_del_die : ''; ?>');
				});
			<?php else: ?>
			<?php if ( isset($edit_authority) && $edit_authority !== false ) : ?>
			$('.eis-upload-button').click(function() {
		         targetfield = $(this).prev('.eis-upload-url');
		         tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		         return false;
		    });

		    $('.eis-remove-image').bind('click', function(){
		        var defaultImage = $(this).parent().find('.eis-upload-image-default').text();
		        $(this).parent().find('.eis-upload-url').val('');
		        $(this).parent().find('.eis-upload-image').attr('src', defaultImage);
		    });
		    $('#update-image').bind('submit', function(e) {
				e.preventDefault();
				tinyMCE.triggerSave();
				var slidename = $('#wp-eis-slidename').val(),
					title_h2 = $('#wp-eis-htwo').val(),
					title_h3 = $('#wp-eis-hthree').val(),
					image = $('#wp-eis-image').val(),
					thumbnail = $('#wp-eis-thumbnail').val(),
					order = $('#wp-eis-order').val(),
					data = $(this).serialize();

				console.log( data );

				if(slidename == '') {
					$('#wp-eis-slidename').addClass('wp-eis-error');
					$('#wp-eis-slidename').bind('input', function() {
						$(this).removeClass('wp-eis-error');
					});
					return false;
				}
				// if(title_h2 == '') {
				// 	$('#wp-eis-htwo').addClass('wp-eis-error').attr('placeholder', '<?php _e('Please enter your title', 'wp-eis');?>');
				// 	$('#wp-eis-htwo').bind('input', function() {
				// 		$(this).removeClass('wp-eis-error');
				// 	});
				// 	return false;
				// }
				// if(title_h3 == '') {
				// 	$('#wp-eis-hthree').addClass('wp-eis-error').attr('placeholder', '<?php _e('Please enter your title', 'wp-eis');?>');
				// 	$('#wp-eis-hthree').bind('input', function() {
				// 		$(this).removeClass('wp-eis-error');
				// 	});
				// 	return false;
				// }
				if(image == '') {
					$('#wp-eis-image').addClass('wp-eis-error').attr('placeholder', '<?php _e('Please enter an image', 'wp-eis');?>');
					$('#wp-eis-image').bind('input', function() {
						$(this).removeClass('wp-eis-error');
					});
					return false;
				}
				if( isNaN( parseInt( $('#wp-eis-order').val(), 10 ) ) || parseInt( $('#wp-eis-order').val(), 10 ) < 1) {
					$('#wp-eis-order').addClass('wp-eis-error');
					$('#wp-eis-order').next('span.order-error').html('<?php _e('Please enter a numeric value', 'wp-eis');?>');
					
					$('#wp-eis-order').bind('input', function() {
						$(this).removeClass('wp-eis-error');
					});
					return false;
				} else { $('#wp-eis-order').next('span.order-error').html(''); }

				$.ajax({
					type: 'POST',
					data: data + "&action=wp_eis_update_images_ajax",
					url: ajaxurl,
					beforeSend: function() {
						$('.wp-eis-respond-fixed-bottom').css({'display': 'inline-block'}).removeClass('success-respond');
						$('.wp-eis-respond-fixed-bottom').html('<h2 class="sending-data"><?php _e('Sending your data','wp-eis'); ?></h2>');
					},
					success: function(response) {
						if( response ) {
							$('.wp-eis-respond-fixed-bottom').find('h2').remove();
							$('.wp-eis-respond-fixed-bottom').html(response)
							.delay(1000).append('<a href="?page=<?php echo $page; ?>" title="<?php _e('Refresh', 'wp-eis'); ?>" class="reload button button-primary"><?php _e('Refresh now', 'wp-eis'); ?></a>').fadeIn(1000)
							.append('<span class="description desc-refresh"><?php _e('Or, Will be Refreshed after 5 secs', 'wp-eis'); ?></span>');
							setTimeout(function(){
							   window.location = "?page=<?php echo $page; ?>";
							}, 5000);
						} else {
							$('.wp-eis-respond-fixed-bottom').html(response).addClass('error-respond')
							.delay(1000).fadeOut(1000, function() {
								$(this).removeClass().html('<a href="" title="<?php _e('Try again', 'wp-eis'); ?>" class="reload button"><?php _e('Try again', 'wp-eis'); ?></a>').fadeIn();	
							});
						}
					}
				});
			});
			<?php else: ?>
				$('#all-images .edit').bind('click', function(e) {
					e.preventDefault();
					alert('<?php echo isset( $wp_edi_die ) ? $wp_edi_die : ''; ?>');
				});
				$('#all-images .edit-link').bind('click', function(e) {
					e.preventDefault();
					alert('<?php echo isset( $wp_edi_die ) ? $wp_edi_die : ''; ?>');
				});
			<?php endif; ?>

			<?php if ( isset($delete_authority) && $delete_authority !== false ) : ?>
			$('.image-delete').bind('click', function(e) {
				e.preventDefault();
				var id = $(this).attr('href').match(/ID=[0-9 -()+]+$/),
				agree = confirm('<?php _e('Are you sure?', 'wp-eis');?>');

				if(agree) {
					$.ajax({
						type: 'POST',
						data: id+"&action=wp_eis_delete_images_ajax",
						url: ajaxurl,
						beforeSend: function() {
							$('.wp-eis-respond-fixed').css({'display': 'inline-block'}).removeClass('success-respond');
								$('.wp-eis-respond-fixed').html('<h2 class="sending-data"><?php _e('Sending your data','wp-eis'); ?></h2>');
						},
						success: function(response) {
							if( response ) {
								$('.wp-eis-respond-fixed').find('h2').remove();
								$('.wp-eis-respond-fixed').html(response)
								.delay(1000).append('<a href="" title="<?php _e('Refresh', 'wp-eis'); ?>" class="reload button button-primary"><?php _e('Refresh now', 'wp-eis'); ?></a>').fadeIn(1000)
								.append('<span class="description desc-refresh"><?php _e('Or, Will be Refreshed after 10 secs', 'wp-eis'); ?></span>');
								setTimeout(function(){
								   window.location = "?page=<?php echo $page; ?>";
								}, 10000);
							} else {
								$('.wp-eis-respond-fixed').html(response).addClass('error-respond')
								.delay(1000).fadeOut(1000, function() {
									$(this).removeClass().html('<a href="" title="<?php _e('Try again', 'wp-eis'); ?>" class="reload button"><?php _e('Try again', 'wp-eis'); ?></a>').fadeIn();	
								});
							}
						}
					});
				}
			});

			$('#doaction-remove').bind('click', function(e) {
				e.preventDefault();

				var idInput = new Array(),
					idInputs = new Array();

				if( $('#action').val() == 'default' ){
					$('#action').addClass('wp-eis-error');
					$('#action').bind('change', function() {
						$(this).removeClass('wp-eis-error');
					});
					return false;
				}

				$('.check-column input').each(function(i) {
					var that = $(this);
					if( that.is(':checked') ) {
						idInput[i] = that.val();
					}
				});
				
				function filterArray(actual){
					var newArray = new Array();
					for(var i = 0; i<actual.length; i++){
						if (actual[i]){
							newArray.push(actual[i]);
						}
					}
					return newArray;
				}
				
				for (var i = 0, l = idInput.length; i < l; i++) {
					idInputs[i] = idInput[i];
				};
				idInputs = filterArray(idInputs);
				var id = idInputs.join("|"),
					agree = confirm('<?php _e('Are you sure?', 'wp-eis');?>');

				console.log(id);
				console.log(id.length);
				console.log(idInputs.length)

				if( idInputs.length >= 2 ) {
					if(agree) {
						$.ajax({
							type: 'POST',
							data: "ID=" + id + "&action=wp_eis_delete_images_ajax",
							url: ajaxurl,
							beforeSend: function() {
								$('.wp-eis-respond-fixed').css({'display': 'inline-block'}).removeClass('success-respond');
								$('.wp-eis-respond-fixed').html('<h2 class="sending-data"><?php _e('Sending your data','wp-eis'); ?></h2>');
							},
							success: function(response) {
								if( response ) {
									$('.wp-eis-respond-fixed').find('h2').remove();
									$('.wp-eis-respond-fixed').html(response)
									.delay(1000).append('<a href="" title="<?php _e('Refresh', 'wp-eis'); ?>" class="reload button button-primary"><?php _e('Refresh now', 'wp-eis'); ?></a>').fadeIn(1000)
									.append('<span class="description desc-refresh"><?php _e('Or, Will be Refreshed after 10 secs', 'wp-eis'); ?></span>');
									setTimeout(function(){
									   window.location = "?page=<?php echo $page; ?>";
									}, 10000);
								} else {
									$('.wp-eis-respond-fixed').html(response).addClass('error-respond')
									.delay(1000).fadeOut(1000, function() {
										$(this).removeClass().html('<a href="" title="<?php _e('Try again', 'wp-eis'); ?>" class="reload button"><?php _e('Try again', 'wp-eis'); ?></a>').fadeIn();	
									});
								}
							}
						});
					}
				} else if( idInputs.length == 1 ) {
					alert("<?php _e('Please delete this ID by deleting it from the delete link under the Slide Name field', 'wp-eis');?>");
				} else {
					alert("<?php _e('Please select the IDs you want delete theme', 'wp-eis');?>");
				}
			});
			<?php else: ?>
				$('#all-images .trash').bind('click', function(e) {
					e.preventDefault();
					alert('<?php echo isset( $wp_del_die ) ? $wp_del_die : ''; ?>');
				});
				$('#doaction-remove').bind('click', function(e) {
					e.preventDefault();
					alert('<?php echo isset( $wp_del_die ) ? $wp_del_die : ''; ?>');
				});
			<?php endif; ?>
			<?php endif; ?>
		});
		</script>
		<?php
	}

	public function wp_eis_about_page()
	{
		if (!current_user_can('read')) {
			wp_die(__('You do not have sufficient permissions to access this page.', 'wp-eis'));
		}
		?>
		<div class="wrap">
			<div id="icon-index" class="icon32"><br></div>
			<h2><?php _e('About EIS Plugin', 'wp-eis'); ?></h2>
			<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><a href="http://mehral.com"><img src="<?php echo WP_EIS_URL.'images/mehral.png'; ?>" /></a></th>
					<th scope="row"></th>
					<th scope="row"></th>
				</tr>
			</tbody>
			</table>
			<p><?php _e('Hi, This plugin is a responsive slideshow plugin that generated from ', 'wp-eis'); ?> <a href="http://tympanus.net/codrops/2011/11/21/elastic-image-slideshow-with-thumbnail-preview/"><?php _e('tympanus.net', 'wp-eis'); ?></a></p>
			<p><?php _e('This plugin is available in two languages,  Persian & English ', 'wp-eis'); ?></p>
			<p><?php _e('If you want to translate this plugin in your language please send your translating to ', 'wp-eis'); ?><a href="http://blog.mehral.com/plugins-support/"><?php _e('Plugin Support Page', 'wp-eis'); ?></a> </p>
			<p><?php _e('If you have any questions about this plugin, you can ask your questions here', 'wp-eis'); ?>
			<?php 
				if ( get_bloginfo('language') == 'fa-IR' ) {
					$url_support = "http://fablog.mehral.com/go/eis-wordpress-plugin/";
				} else {
					$url_support = "http://blog.mehral.com/projects/eis-wordpress-plugin/";
				}
			?>
			<a href="<?php echo $url_support; ?>"><?php _e('WP EIS Page', 'wp-eis'); ?></a> 
			</p>
			<p><?php _e('At the end if you liked this plugin you can support me by becoming one of my', 'wp-eis'); ?> <a href="http://www.facebook.com/mehral.co"><?php _e('facebook fans', 'wp-eis'); ?></a></p>
			<p><?php _e('Good to see you again', 'wp-eis'); ?> ;-)</p>
		</div>
		<?php
	}
}

?>