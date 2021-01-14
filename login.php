<?php
/*
Plugin Name: miniOrange SSO using SAML 2.0
Plugin URI: http://miniorange.com/
Description: (Premium Single-Site)miniOrange SAML 2.0 SSO enables user to perform Single Sign On with any SAML 2.0 enabled Identity Provider.
Version: 12.0.2
Author: miniOrange
Author URI: http://miniorange.com/
*/


include_once dirname( __FILE__ ) . '/mo_login_saml_sso_widget.php';
include_once 'xmlseclibs.php';
use \RobRichards\XMLSecLibs\XMLSecurityKey;
use \RobRichards\XMLSecLibs\XMLSecurityDSig;
use \RobRichards\XMLSecLibs\XMLSecEnc;
require('mo-saml-class-customer.php');
require('mo_saml_settings_page.php');
require('MetadataReader.php');
require('certificate_utility.php');
require_once('mo-saml-plugin-version-update.php');
class saml_mo_login {

	function __construct() {
		add_action( 'admin_menu', array( $this, 'miniorange_sso_menu' ) );
		add_action( 'admin_init', array( $this, 'miniorange_login_widget_saml_save_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'plugin_settings_style' ) );
		register_deactivation_hook(__FILE__, array( $this, 'mo_sso_saml_deactivate'));
		add_action( 'admin_enqueue_scripts', array( $this, 'plugin_settings_script' ) );
		remove_action( 'admin_notices', array( $this, 'mo_saml_success_message') );
		remove_action( 'admin_notices', array( $this, 'mo_saml_error_message') );
		add_action('wp_authenticate', array( $this, 'mo_saml_authenticate' ) );
		add_action('wp', array( $this, 'mo_saml_auto_redirect' ) );
		$widgetObj = new mo_login_wid();
        add_filter('logout_redirect', array($widgetObj, 'mo_saml_logout'), 10 , 3);
        add_action('init', array($widgetObj, 'mo_saml_widget_init'));
		add_action( 'admin_init', 'mo_saml_download' );
		add_action('login_enqueue_scripts', array($this, 'mo_saml_login_enqueue_scripts'));
		add_action('login_form', array( $this, 'mo_saml_modify_login_form' ) );
		add_shortcode( 'MO_SAML_FORM', array($this, 'mo_get_saml_shortcode') );
		add_filter( 'cron_schedules', array($this,'myprefix_add_cron_schedule') );
		add_action( 'metadata_sync_cron_action', array($this, 'metadata_sync_cron_action'));
		register_activation_hook(__FILE__,array($this, 'mo_saml_check_openssl'));
		add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), array($this,'mo_saml_plugin_action_links') );
		add_action( 'admin_init', array( $this, 'default_certificate' ) );
		add_option( 'lcdjkasjdksacl', 'default-certificate');
		add_filter('manage_users_columns', array($this, 'mo_saml_custom_attr_column'));
		add_filter('manage_users_custom_column', array($this ,'mo_saml_attr_column_content'), 10, 3);
        add_action('wp_loaded',array($this,'mo_saml_check_security'));


    }

    function mo_saml_check_security(){
        $request_ur =  preg_match('/[<>"]*<script>(.*?)<\/script>[<>]*/',urldecode_deep($_SERVER['REQUEST_URI']));
        if($request_ur){
            $request_uri =  preg_replace('/[<>"]*<script>(.*?)<\/script>[<>]*/',"",urldecode_deep($_SERVER['REQUEST_URI']));
            wp_redirect($request_uri);
            exit;
        }
    }


	function default_certificate() {
		$cert = file_get_contents(plugin_dir_path(__FILE__) . 'resources' . DIRECTORY_SEPARATOR . 'miniorange_sp_2020.crt');
		$cert_private_key = file_get_contents(plugin_dir_path(__FILE__) . 'resources' . DIRECTORY_SEPARATOR . 'miniorange_sp_2020_priv.key');

		if( !get_option( 'mo_saml_current_cert' ) && !get_option( 'mo_saml_current_cert_private_key' ) )
        {

            update_option( 'mo_saml_current_cert', $cert );
            update_option( 'mo_saml_current_cert_private_key', $cert_private_key );
        }
	}

	function mo_saml_check_openssl(){

	 	if(!mo_saml_is_extension_installed('openssl')){
	 		wp_die('PHP openssl extension is not installed or disabled,please enable it to activate the plugin.');
	 	}
		add_option('Activated_Plugin','Plugin-Slug');

    }


	function myprefix_add_cron_schedule( $schedules ) {
		$schedules['weekly'] = array(
	        'interval' => 604800, // 1 week in seconds
	        'display'  => __( 'Once Weekly' ),
	        );
		$schedules['monthly'] = array(
	        'interval' => 2635200, // 1 week in seconds
	        'display'  => __( 'Once Monthly' ),
	        );
		return $schedules;
	}

	function metadata_sync_cron_action()
	{
		error_log('miniorange : RAN SYNC - ' . time());
		$saml_identity_name = get_option('saml_identity_name');
		$this->upload_metadata(@file_get_contents(get_option('saml_metadata_url_for_sync')));
		update_option('saml_identity_name', $saml_identity_name);
	}

	function  mo_login_widget_saml_options () {
		global $wpdb;
		update_option( 'mo_saml_host_name', 'https://login.xecurify.com' );
		$host_name = get_option('mo_saml_host_name');

		mo_register_saml_sso();
	}

	function mo_saml_success_message() {
		$class = "error";
		$message = get_option('mo_saml_message');
		echo "<div class='" . $class . "'> <p>" . $message . "</p></div>";
	}

	function mo_saml_error_message() {
		$class = "updated";
		$message = get_option('mo_saml_message');
		echo "<div class='" . $class . "'> <p>" . $message . "</p></div>";
	}

	public function mo_sso_saml_deactivate() {

		if(!is_multisite()) {
			//delete all customer related key-value pairs
			do_action( 'mo_saml_flush_cache');
			delete_option('mo_saml_host_name');
			delete_option('mo_saml_new_registration');
			delete_option('mo_saml_admin_phone');
			delete_option('mo_saml_admin_password');
			delete_option('mo_saml_verify_customer');
			delete_option('mo_saml_admin_customer_key');
			delete_option('mo_saml_admin_api_key');
			delete_option('mo_saml_customer_token');
			delete_option('mo_saml_message');
			delete_option('mo_saml_registration_status');
			delete_option('mo_saml_idp_config_complete');
			delete_option('mo_saml_transactionId');
			delete_option('mo_saml_enable_cloud_broker');
			delete_option('vl_check_t');
			delete_option('vl_check_s');
		} else {
			global $wpdb;
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			$original_blog_id = get_current_blog_id();

			do_action( 'mo_saml_flush_cache');
			foreach ( $blog_ids as $blog_id )
			{
				switch_to_blog( $blog_id );
				//delete all your options
				//E.g: delete_option( {option name} );

				delete_option('mo_saml_host_name');
				delete_option('mo_saml_new_registration');
				delete_option('mo_saml_admin_phone');
				delete_option('mo_saml_admin_password');
				delete_option('mo_saml_verify_customer');
				delete_option('mo_saml_admin_customer_key');
				delete_option('mo_saml_admin_api_key');
				delete_option('mo_saml_customer_token');
				delete_option('mo_saml_message');
				delete_option('mo_saml_registration_status');
				delete_option('mo_saml_idp_config_complete');
				delete_option('mo_saml_transactionId');
				delete_option('vl_check_t');
				delete_option('vl_check_s');
			}
			switch_to_blog( $original_blog_id );
		}
	}



	function mo_saml_show_success_message() {
		remove_action( 'admin_notices', array( $this, 'mo_saml_success_message') );
		add_action( 'admin_notices', array( $this, 'mo_saml_error_message') );
	}
	function mo_saml_show_error_message() {
		remove_action( 'admin_notices', array( $this, 'mo_saml_error_message') );
		add_action( 'admin_notices', array( $this, 'mo_saml_success_message') );
	}

    function plugin_settings_style($page) {
        if ( 'toplevel_page_mo_saml_settings' != $page ) {
            return;
        }
        if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'licensing' ){
            wp_enqueue_style( 'mo_saml_bootstrap_css', plugins_url( 'includes/css/bootstrap/bootstrap.min.css', __FILE__ ), array(), '12.0.2', 'all' );
        }


        wp_enqueue_style('mo_saml_admin_settings_jquery_style', plugins_url('includes/css/jquery.ui.css', __FILE__), array(), '12.0.2', 'all');
        wp_enqueue_style('mo_saml_admin_settings_style_tracker', plugins_url('includes/css/progress-tracker.css', __FILE__), array(), '12.0.2', 'all');
        wp_enqueue_style('mo_saml_admin_settings_style', plugins_url('includes/css/style_settings.min.css', __FILE__ ), array(), '12.0.2', 'all' );
        wp_enqueue_style( 'mo_saml_admin_settings_phone_style', plugins_url( 'includes/css/phone.min.css', __FILE__ ), array(), '12.0.2', 'all' );
        wp_enqueue_style( 'mo_saml_wpb-fa', plugins_url( 'includes/css/font-awesome.min.css', __FILE__ ), array(), '12.0.2', 'all' );
    }
    function plugin_settings_script($page) {
        if ( 'toplevel_page_mo_saml_settings' != $page ) {
            return;
        }

        wp_enqueue_script('jquery');
        wp_enqueue_script( 'mo_saml_admin_settings_color_script', plugins_url('includes/js/jscolor/jscolor.js', __FILE__ ), array(), '12.0.2', false);
        wp_enqueue_script( 'mo_saml_admin_bootstrap_script', plugins_url( 'includes/js/bootstrap.js', __FILE__ ), array(), '12.0.2', false );
        wp_enqueue_script( 'mo_saml_admin_settings_script', plugins_url( 'includes/js/settings.min.js', __FILE__ ), array(), '12.0.2', false );
        wp_enqueue_script( 'mo_saml_admin_settings_phone_script', plugins_url('includes/js/phone.min.js', __FILE__ ), array(), '12.0.2', false );

        if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'licensing'){
            wp_enqueue_script( 'mo_saml_modernizr_script', plugins_url( 'includes/js/modernizr.js', __FILE__ ), array(), '12.0.2', false );
            wp_enqueue_script( 'mo_saml_popover_script', plugins_url( 'includes/js/bootstrap/popper.min.js', __FILE__ ), array(), '12.0.2', false );
            wp_enqueue_script( 'mo_saml_bootstrap_script', plugins_url( 'includes/js/bootstrap/bootstrap.min.js', __FILE__ ), array(), '12.0.2', false );
        }


    }

	function mo_saml_activation_message() {
		$class = "updated";
		$message = get_option('mo_saml_message');
		echo "<div class='" . $class . "'> <p>" . $message . "</p></div>";
	}


	function get_empty_strings(){
	    return '';
    }

	/**
	 * Add a column in the Wordpress Users menu for custom attributes
	 */
	function mo_saml_custom_attr_column($columns) {
		$custom_attributes = maybe_unserialize(get_option('mo_saml_custom_attrs_mapping'));

		$attr_in_user_menu = get_option('saml_show_user_attribute');
		$i = 0;
		if(is_array($custom_attributes))
		foreach($custom_attributes as $key => $value){
			if(!empty($key)){
				if(in_array($i, $attr_in_user_menu)){
					$columns[$key] = $key;
				}
			}
			$i++;
		}
		return $columns;
	}

	/**
	 * Populate the User's custom attribute column's field
	 */
	function mo_saml_attr_column_content($output, $column_name, $user_id) {

		$custom_attributes = get_option('mo_saml_custom_attrs_mapping');
		if(is_array($custom_attributes))
		foreach($custom_attributes as $key => $value){
			if($key === $column_name){
				$content = get_user_meta($user_id, $column_name, false);
				if(!empty($content)){
					if(!is_array($content[0])){
						return $content[0];
					} else {
						$result = '';
						foreach($content[0] as $attr_value){
							$result = $result . $attr_value;
							if(next($content[0])){
								$result = $result . ' | ';
							}
						}
						return $result;
					}
				}
			}
		}
        return $output;
	}

	static function mo_check_option_admin_referer($option_name){
	    return (isset($_POST['option']) and $_POST['option']==$option_name and check_admin_referer($option_name));
    }

	function miniorange_login_widget_saml_save_settings(){
		if(current_user_can('manage_options')){
			if(is_admin() && get_option('Activated_Plugin')=='Plugin-Slug') {

				delete_option('Activated_Plugin');
				update_option('mo_saml_message','Go to plugin <b><a href="admin.php?page=mo_saml_settings">settings</a></b> to configure SAML Single Sign On by miniOrange.');
				add_action('admin_notices', array($this, 'mo_saml_activation_message'));
			}
		}



		if ( isset($_POST['option']) && current_user_can( 'manage_options' )){

			if(self::mo_check_option_admin_referer('login_widget_saml_save_settings')){
				if(!mo_saml_is_extension_installed('curl')) {
					update_option( 'mo_saml_message', 'ERROR: PHP cURL extension is not installed or disabled. Save Identity Provider Configuration failed.');
					$this->mo_saml_show_error_message();
					return;
				}

				//validation and sanitization
				$saml_identity_name = '';
				$saml_login_binding_type = '';
				$saml_login_url = '';
				$saml_logout_binding_type = '';
				$saml_logout_url = '';
				$saml_issuer = '';
				$saml_x509_certificate = '';
				$saml_request_signed = '';
				$saml_nameid_format = '';

				if( $this->mo_saml_check_empty_or_null( $_POST['saml_identity_name'] ) || $this->mo_saml_check_empty_or_null( $_POST['saml_login_url'] ) || $this->mo_saml_check_empty_or_null( $_POST['saml_issuer']) || $this->mo_saml_check_empty_or_null( $_POST['saml_nameid_format'] )  ) {
					update_option( 'mo_saml_message', 'All the fields are required. Please enter valid entries.');
					$this->mo_saml_show_error_message();
					return;
				} else if(!preg_match("/^\w*$/", $_POST['saml_identity_name'])) {
					update_option( 'mo_saml_message', 'Please match the requested format for Identity Provider Name. Only alphabets, numbers and underscore is allowed.');
					$this->mo_saml_show_error_message();
					return;
				} else{
					$saml_identity_name = htmlspecialchars(trim( $_POST['saml_identity_name'] ));
					$saml_login_url = htmlspecialchars(trim( $_POST['saml_login_url'] ));
					if(array_key_exists('saml_login_binding_type',$_POST))
						$saml_login_binding_type = htmlspecialchars($_POST['saml_login_binding_type']);

					if(array_key_exists('saml_logout_binding_type',$_POST))
						$saml_logout_binding_type = htmlspecialchars($_POST['saml_logout_binding_type']);
					if(array_key_exists('saml_logout_url', $_POST)) {
						$saml_logout_url = htmlspecialchars(trim( $_POST['saml_logout_url'] ));
					}
					$saml_issuer = htmlspecialchars(trim( $_POST['saml_issuer'] ));
					$saml_identity_provider_guide_name = htmlspecialchars(trim($_POST['saml_identity_provider_guide_name']));
					$saml_x509_certificate = $_POST['saml_x509_certificate'];
					$saml_nameid_format = htmlspecialchars($_POST['saml_nameid_format']);
				}

				update_option('saml_identity_name', $saml_identity_name);
				update_option('saml_login_binding_type', $saml_login_binding_type);
				update_option('saml_login_url', $saml_login_url);
				update_option('saml_logout_binding_type', $saml_logout_binding_type);
				update_option('saml_logout_url', $saml_logout_url);
				update_option('saml_issuer', $saml_issuer);
				update_option('saml_nameid_format', $saml_nameid_format);
				update_option( 'saml_identity_provider_guide_name',$saml_identity_provider_guide_name);

				if(isset($_POST['saml_request_signed'])) {
					update_option('saml_request_signed' , 'checked');
				} else {
					update_option('saml_request_signed' , 'unchecked');
				}
				foreach ($saml_x509_certificate as $key => $value) {
					if(empty($value)){
						unset($saml_x509_certificate[$key]);

					}
					else
					{
						$saml_x509_certificate[$key] = SAMLSPUtilities::sanitize_certificate( $value );
						if(!@openssl_x509_read($saml_x509_certificate[$key])){
							update_option('mo_saml_message', 'Invalid certificate: Please provide a valid certificate.');
							$this->mo_saml_show_error_message();
							delete_option('saml_x509_certificate');
							return;
						}

					}
				}

				if(empty($saml_x509_certificate)){
					update_option("mo_saml_message",'Invalid Certificate:Please provide a certificate');
					$this->mo_saml_show_error_message();
					//delete_option('saml_x509_certificate');
					return;
				}

				update_option('saml_x509_certificate', maybe_serialize( $saml_x509_certificate ) );

				if(isset($_POST['saml_response_signed']))
				{
					update_option('saml_response_signed' , 'checked');
				}
				else
				{
					update_option('saml_response_signed' , 'Yes');
				}
				if(isset($_POST['saml_assertion_signed']))
				{
					update_option('saml_assertion_signed' , 'checked');
				}
				else
				{
					update_option('saml_assertion_signed' , 'Yes');
				}

				if(array_key_exists('enable_iconv',$_POST))
				update_option('mo_saml_encoding_enabled','checked');
				else
				update_option('mo_saml_encoding_enabled','');


					update_option('mo_saml_message', 'Identity Provider details saved successfully.');
					$this->mo_saml_show_success_message();
			}
			//Save Attribute Mapping
			else if(self::mo_check_option_admin_referer('login_widget_saml_attribute_mapping')){

				if(!mo_saml_is_extension_installed('curl')) {
					update_option( 'mo_saml_message', 'ERROR: PHP cURL extension is not installed or disabled. Save Attribute Mapping failed.');
					$this->mo_saml_show_error_message();
					return;
				}

				update_option('saml_am_username', htmlspecialchars(stripslashes($_POST['saml_am_username'])));
				update_option('saml_am_email', htmlspecialchars(stripslashes($_POST['saml_am_email'])));
				update_option('saml_am_first_name', htmlspecialchars(stripslashes($_POST['saml_am_first_name'])));
				update_option('saml_am_last_name', htmlspecialchars(stripslashes($_POST['saml_am_last_name'])));
				update_option('saml_am_group_name', htmlspecialchars(stripslashes($_POST['saml_am_group_name'])));
				update_option('saml_am_display_name', htmlspecialchars(stripslashes($_POST['saml_am_display_name'])));

				$custom_attributes = array();
				$keys = array();
				$values = array();
				$attrs_to_display = array();
				if(isset($_POST['mo_saml_custom_attribute_keys']) && !empty($_POST['mo_saml_custom_attribute_keys']))
					$keys = $_POST['mo_saml_custom_attribute_keys'];
				if(isset($_POST['mo_saml_custom_attribute_values']) && !empty($_POST['mo_saml_custom_attribute_values']))
					$values = $_POST['mo_saml_custom_attribute_values'];
				$count_keys = count($keys);
				if($count_keys > 0){
					$keys = array_map('htmlspecialchars', $keys);
					$values = array_map('htmlspecialchars', $values);
					$index = 0;
					while($index < $count_keys){
						if(isset($_POST['mo_saml_display_attribute_' . $index]) && !empty($_POST['mo_saml_display_attribute_' . $index])){
							array_push($attrs_to_display, $index);
						}
						$index++;
					}
				}
				update_option('saml_show_user_attribute', $attrs_to_display);
				$custom_attributes = array_combine($keys, $values);

				// Filter empty values
				$custom_attributes = array_filter($custom_attributes);

				if(!empty($custom_attributes)){
					// Save the custom attribute mapping if non-empty mapping values are provided
					update_option('mo_saml_custom_attrs_mapping', $custom_attributes);
				} else {
					// Delete custom attribute mapping if empty mapping is provided
					$custom_attributes = get_option('mo_saml_custom_attrs_mapping');
					if(!empty($custom_attributes)){
						delete_option('mo_saml_custom_attrs_mapping');
					}
				}
				update_option('mo_saml_message', 'Attribute Mapping details saved successfully');
				$this->mo_saml_show_success_message();

			}
			else if(self::mo_check_option_admin_referer('clear_attrs_list')){
				delete_option('mo_saml_test_config_attrs');

				update_option('mo_saml_message', 'Attributes list removed successfully');
				$this->mo_saml_show_success_message();
			}
		//Save Role Mapping
			else if(self::mo_check_option_admin_referer('login_widget_saml_role_mapping')){

				if(!mo_saml_is_extension_installed('curl')) {
					update_option( 'mo_saml_message', 'ERROR: PHP cURL extension is not installed or disabled. Save Role Mapping failed.');
					$this->mo_saml_show_error_message();
					return;
				}

				if (isset($_POST['saml_am_default_user_role'])) {
					$default_role = htmlspecialchars($_POST['saml_am_default_user_role']);
					update_option('saml_am_default_user_role', $default_role);
				}

				if(isset($_POST['saml_am_dont_allow_unlisted_user_role'])) {
					update_option('saml_am_default_user_role', false);
					update_option('saml_am_dont_allow_unlisted_user_role', 'checked');
				} else {
					update_option('saml_am_dont_allow_unlisted_user_role', 'unchecked');
				}

				if(isset($_POST['mo_saml_dont_create_user_if_role_not_mapped'])) {
					update_option('mo_saml_dont_create_user_if_role_not_mapped', 'checked');
					update_option('saml_am_default_user_role', false);
					update_option('saml_am_dont_allow_unlisted_user_role', 'unchecked');
				} else {
					update_option('mo_saml_dont_create_user_if_role_not_mapped', 'unchecked');
				}
				if(isset($_POST['mo_saml_dont_update_existing_user_role'])) {
					update_option('saml_am_dont_update_existing_user_role', 'checked');
				} else {
					update_option('saml_am_dont_update_existing_user_role', 'unchecked');
				}

				if(isset($_POST['mo_saml_dont_allow_user_tologin_create_with_given_groups'])) {
					update_option('saml_am_dont_allow_user_tologin_create_with_given_groups', 'checked');
					if(isset($_POST['mo_saml_restrict_users_with_groups'])){
						if(!empty($_POST['mo_saml_restrict_users_with_groups'])){
							update_option('mo_saml_restrict_users_with_groups', htmlspecialchars(stripslashes($_POST['mo_saml_restrict_users_with_groups'])));
						}else{
							update_option('mo_saml_restrict_users_with_groups', '');
						}
					}
				} else {
					update_option('saml_am_dont_allow_user_tologin_create_with_given_groups', 'unchecked');
				}


				$wp_roles = new WP_Roles();
				$roles = $wp_roles->get_names();
				$role_mapping = array();
				foreach ($roles as $role_value => $role_name) {
					$attr = 'saml_am_group_attr_values_' . $role_value;
					$role_mapping[$role_value] = htmlspecialchars(stripslashes($_POST[$attr]));
				}

				update_option('saml_am_role_mapping', $role_mapping);
				update_option('mo_saml_message', 'Role Mapping details saved successfully.');
				$this->mo_saml_show_success_message();
			}

			// Update Domain Restriction
			else if(self::mo_check_option_admin_referer('saml_form_domain_restriction_option')){

				$mo_saml_enable_domain_restriction_login = isset($_POST['mo_saml_enable_domain_restriction_login']) && !empty($_POST['mo_saml_enable_domain_restriction_login']) ? htmlspecialchars($_POST['mo_saml_enable_domain_restriction_login']) : '';

				$mo_saml_allow_deny_user_with_domain = isset($_POST['mo_saml_allow_deny_user_with_domain']) && !empty($_POST['mo_saml_allow_deny_user_with_domain']) ? htmlspecialchars($_POST['mo_saml_allow_deny_user_with_domain']) : 'allow';

				$saml_am_email_domains = isset($_POST['saml_am_email_domains']) && !empty($_POST['saml_am_email_domains']) ? htmlspecialchars($_POST['saml_am_email_domains']) : '';

				update_option('mo_saml_enable_domain_restriction_login', $mo_saml_enable_domain_restriction_login);
				update_option('mo_saml_allow_deny_user_with_domain', $mo_saml_allow_deny_user_with_domain);
				update_option('saml_am_email_domains', $saml_am_email_domains);

				update_option('mo_saml_message', 'Domain Restriction has been saved successfully.');
				$this->mo_saml_show_success_message();
			}
			//Update SP Base URL and SP Entity ID
			else if(self::mo_check_option_admin_referer('mo_saml_update_idp_settings_option')){
				if(isset($_POST['mo_saml_sp_base_url']) && isset($_POST['mo_saml_sp_entity_id'])) {
					$sp_base_url = htmlspecialchars($_POST['mo_saml_sp_base_url']);
					$sp_entity_id = htmlspecialchars($_POST['mo_saml_sp_entity_id']);
					if(substr($sp_base_url, -1) == '/') {
						$sp_base_url = substr($sp_base_url, 0, -1);
					}
					update_option('mo_saml_sp_base_url', $sp_base_url);
					update_option('mo_saml_sp_entity_id', $sp_entity_id);
				}
				update_option('mo_saml_message', 'Settings updated successfully.');
				$this->mo_saml_show_success_message();
			}
		// Upload Metadata or Fetch Metadata
			else if(self::mo_check_option_admin_referer('saml_upload_metadata')){
                if(!preg_match("/^\w*$/", $_POST['saml_identity_metadata_provider'])) {
                    update_option( 'mo_saml_message', 'Please match the requested format for Identity Provider Name. Only alphabets, numbers and underscore is allowed.');
                    $this->mo_saml_show_error_message();
                    return;
                }
				if ( ! function_exists( 'wp_handle_upload' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
				}
				$this->_handle_upload_metadata();
			}

			// Upload Custom Certificates
            if(self::mo_check_option_admin_referer('upgrade_cert')){

                $cert = file_get_contents(plugin_dir_path(__FILE__) . 'resources' . DIRECTORY_SEPARATOR . 'miniorange_sp_2020.crt');
                $cert_private_key = file_get_contents(plugin_dir_path(__FILE__) . 'resources' . DIRECTORY_SEPARATOR . 'miniorange_sp_2020_priv.key');

                update_option( 'mo_saml_current_cert', $cert );
                update_option( 'mo_saml_current_cert_private_key', $cert_private_key );
                update_option('mo_saml_certificate_roll_back_available',true);

                update_option('mo_saml_message', 'Certificate Upgraded successfully');
                $this->mo_saml_show_success_message();
            }

            if(self::mo_check_option_admin_referer('rollback_cert')){

                $cert = file_get_contents(plugin_dir_path(__FILE__) . 'resources' . DIRECTORY_SEPARATOR . 'sp-certificate.crt');
                $cert_private_key = file_get_contents(plugin_dir_path(__FILE__) . 'resources' . DIRECTORY_SEPARATOR . 'sp-key.key');
                update_option( 'mo_saml_current_cert', $cert );
                update_option( 'mo_saml_current_cert_private_key', $cert_private_key );
                update_option('mo_saml_message', 'Certificate Roll-backed successfully');
                delete_option('mo_saml_certificate_roll_back_available');
                $this->mo_saml_show_success_message();

            }

            if(self::mo_check_option_admin_referer('add_custom_certificate')){

                $cert = file_get_contents(plugin_dir_path(__FILE__) . 'resources' . DIRECTORY_SEPARATOR . 'miniorange_sp_2020.crt');
                $cert_private_key = file_get_contents(plugin_dir_path(__FILE__) . 'resources' . DIRECTORY_SEPARATOR . 'miniorange_sp_2020_priv.key');
			if(isset($_POST['submit']) and $_POST['submit'] == "Upload"){

				if( !@openssl_x509_read($_POST['saml_public_x509_certificate']) ) {

					update_option('mo_saml_message', 'Invalid Certificate format. Please enter a valid certificate.');
					$this->mo_saml_show_error_message();
					return;

				}elseif( !@openssl_x509_check_private_key( $_POST['saml_public_x509_certificate'], $_POST['saml_private_x509_certificate'] ) ) {

					update_option('mo_saml_message', 'Invalid Private Key.');
					$this->mo_saml_show_error_message();
					return;

				}elseif( openssl_x509_read($_POST['saml_public_x509_certificate']) && openssl_x509_check_private_key($_POST['saml_public_x509_certificate'],$_POST['saml_private_x509_certificate'] ) )
				{

					$public_cert = $_POST['saml_public_x509_certificate'];
			        $private_cert = $_POST['saml_private_x509_certificate'];

					update_option( 'mo_saml_custom_cert' ,$public_cert );
					update_option( 'mo_saml_custom_cert_private_key', $private_cert );
					update_option( 'mo_saml_current_cert', $public_cert );
					update_option( 'mo_saml_current_cert_private_key', $private_cert );

					update_option('mo_saml_message', 'Custom Certificate updated successfully.');
					$this->mo_saml_show_success_message();

				}
			}
			else if(isset($_POST['submit']) and $_POST['submit'] == "Reset")
			{
				delete_option( 'mo_saml_custom_cert' );
				delete_option( 'mo_saml_custom_cert_private_key' );
				update_option( 'mo_saml_current_cert', $cert );
				update_option( 'mo_saml_current_cert_private_key', $cert_private_key );

				update_option('mo_saml_message', 'Reset Certificate successfully.');
			    $this->mo_saml_show_success_message();
			}
		}

		else if(self::mo_check_option_admin_referer('add_custom_messages')){
			update_option('mo_saml_account_creation_disabled_msg', htmlspecialchars($_POST['mo_saml_account_creation_disabled_msg']));
			update_option('mo_saml_restricted_domain_error_msg', htmlspecialchars($_POST['mo_saml_restricted_domain_error_msg']));
			update_option('mo_saml_message', 'Configuration has been saved successfully.');
			$this->mo_saml_show_success_message();
		}


		// Fixed Relay State
			else if(self::mo_check_option_admin_referer('mo_saml_relay_state_option')){
				$relay_state = isset($_POST['mo_saml_relay_state']) ? htmlspecialchars($_POST['mo_saml_relay_state']) : '';
                $logout_relay_state = isset($_POST['mo_saml_logout_relay_state']) ? htmlspecialchars($_POST['mo_saml_logout_relay_state']) : '';
				update_option('mo_saml_relay_state', $relay_state);
                update_option('mo_saml_logout_relay_state', $logout_relay_state);
				update_option('mo_saml_message', 'Relay State updated successfully.');
				$this->mo_saml_show_success_message();
			}
			if(self::mo_check_option_admin_referer('mo_saml_widget_option')){
				$custom_IDP = htmlspecialchars($_POST['mo_saml_custom_login_text']);
				update_option('mo_saml_custom_login_text', stripcslashes($custom_IDP));

				$custom_greeting_text = htmlspecialchars($_POST['mo_saml_custom_greeting_text']);
				update_option('mo_saml_custom_greeting_text',stripcslashes($custom_greeting_text));

				$custom_greeting_name = htmlspecialchars($_POST['mo_saml_greeting_name']);
				update_option('mo_saml_greeting_name',stripslashes($custom_greeting_name));

				$custom_name = htmlspecialchars($_POST['mo_saml_custom_logout_text']);
				update_option('mo_saml_custom_logout_text', stripcslashes($custom_name));
				update_option('mo_saml_message', 'Widget Settings updated successfully.');
				$this->mo_saml_show_success_message();
			}



		else if( self::mo_check_option_admin_referer('mo_saml_register_customer') ) {	//register the admin to miniOrange

			if(!mo_saml_is_extension_installed('curl')) {
				update_option( 'mo_saml_message', 'ERROR: PHP cURL extension is not installed or disabled. Registration failed.');
				$this->mo_saml_show_error_message();
				return;
			}

			//validation and sanitization
			$email = '';
			$phone = '';
			$password = self::get_empty_strings();
			$confirmPassword = self::get_empty_strings();
			if( $this->mo_saml_check_empty_or_null( $_POST['email'] ) || $this->mo_saml_check_empty_or_null( $_POST['password'] ) || $this->mo_saml_check_empty_or_null( $_POST['confirmPassword'] ) ) {
				update_option( 'mo_saml_message', 'All the fields are required. Please enter valid entries.');
				$this->mo_saml_show_error_message();
				return;
			} else if( strlen( $_POST['password'] ) < 6 || strlen( $_POST['confirmPassword'] ) < 6){
				update_option( 'mo_saml_message', 'Choose a password with minimum length 6.');
				$this->mo_saml_show_error_message();
				return;
			} else if($this->checkPasswordPattern(strip_tags($_POST['password']))){
				update_option('mo_saml_message','Minimum 6 characters should be present. Maximum 15 characters should be present. Only following symbols (!@#.$%^&*-_) should be present.');
				$this->mo_saml_show_error_message();
				return;
			} else{
				$email = sanitize_email( $_POST['email'] );
				if(isset($_POST['phone']))
					$phone = htmlspecialchars( $_POST['phone'] );
				$password = stripslashes(strip_tags( $_POST['password'] ));
				$confirmPassword = stripslashes(strip_tags( $_POST['confirmPassword'] ));
			}

			update_option( 'mo_saml_admin_email', $email );
			update_option( 'mo_saml_admin_phone', $phone );
			if( strcmp( $password, $confirmPassword) == 0 ) {
				update_option( 'mo_saml_admin_password', $password );
				$email = get_option('mo_saml_admin_email');
				$customer = new CustomerSaml();
				$content = $customer->check_customer();
				if(!$content)
					return;
				$content = json_decode($content, true);
				if( strcasecmp( $content['status'], 'CUSTOMER_NOT_FOUND') == 0 ){
					$content = $customer->send_otp_token($email, '');
					if(!$content)
						return;
					$content = json_decode($content, true);
					if(strcasecmp($content['status'], 'SUCCESS') == 0) {
						update_option( 'mo_saml_message', ' A one time passcode is sent to ' . get_option('mo_saml_admin_email') . '. Please enter the otp here to verify your email.');
						update_option('mo_saml_transactionId',$content['txId']);
						update_option('mo_saml_registration_status','MO_OTP_DELIVERED_SUCCESS_EMAIL');
						$this->mo_saml_show_success_message();
					}else{
						update_option('mo_saml_message','There was an error in sending email. Please verify your email and try again.');
						update_option('mo_saml_registration_status','MO_OTP_DELIVERED_FAILURE_EMAIL');
						$this->mo_saml_show_error_message();
					}
				}else{
					$this->get_current_customer();
				}

			} else {
				update_option( 'mo_saml_message', 'Passwords do not match.');
				delete_option('mo_saml_verify_customer');
				$this->mo_saml_show_error_message();
			}

		}
		else if(self::mo_check_option_admin_referer('mo_saml_validate_otp')){

			if(!mo_saml_is_extension_installed('curl')) {
				update_option( 'mo_saml_message', 'ERROR: PHP cURL extension is not installed or disabled. Validate OTP failed.');
				$this->mo_saml_show_error_message();
				return;
			}

			//validation and sanitization
			$otp_token = '';
			if( $this->mo_saml_check_empty_or_null( $_POST['otp_token'] ) ) {
				update_option( 'mo_saml_message', 'Please enter a value in otp field.');
				//update_option('mo_saml_registration_status','MO_OTP_VALIDATION_FAILURE');
				$this->mo_saml_show_error_message();
				return;
			} else{
				$otp_token = htmlspecialchars( $_POST['otp_token'] );
			}

			$customer = new CustomerSaml();
			$content = $customer->validate_otp_token(get_option('mo_saml_transactionId'), $otp_token );
			if(!$content)
				return;
			$content = json_decode($content, true);
			if(strcasecmp($content['status'], 'SUCCESS') == 0) {

				$this->create_customer();
			}else{
				update_option( 'mo_saml_message','Invalid one time passcode. Please enter a valid otp.');
				//update_option('mo_saml_registration_status','MO_OTP_VALIDATION_FAILURE');
				$this->mo_saml_show_error_message();
			}
		}
		else if( self::mo_check_option_admin_referer('mo_saml_verify_customer') ) {	//register the admin to miniOrange

			if(!mo_saml_is_extension_installed('curl')) {
				update_option( 'mo_saml_message', 'ERROR: PHP cURL extension is not installed or disabled. Login failed.');
				$this->mo_saml_show_error_message();
				return;
			}

			//validation and sanitization
			$email = '';
			$password = self::get_empty_strings();
			if( $this->mo_saml_check_empty_or_null( $_POST['email'] ) || $this->mo_saml_check_empty_or_null( $_POST['password'] ) ) {
				update_option( 'mo_saml_message', 'All the fields are required. Please enter valid entries.');
				$this->mo_saml_show_error_message();
				return;
			} else if($this->checkPasswordPattern(strip_tags($_POST['password']))){
				update_option('mo_saml_message','Minimum 6 characters should be present. Maximum 15 characters should be present. Only following symbols (!@#.$%^&*-_) should be present.');
				$this->mo_saml_show_error_message();
				return;
			} else{
				$email = sanitize_email( $_POST['email'] );
				$password = stripslashes(strip_tags( $_POST['password'] ));
			}

			update_option( 'mo_saml_admin_email', $email );
			update_option( 'mo_saml_admin_password', $password );
			$customer = new Customersaml();
			$content = $customer->get_customer_key();
			if(!$content)
				return;
			$customerKey = json_decode( $content, true );
			if( json_last_error() == JSON_ERROR_NONE ) {
				update_option( 'mo_saml_admin_customer_key', $customerKey['id'] );
				update_option( 'mo_saml_admin_api_key', $customerKey['apiKey'] );
				update_option( 'mo_saml_customer_token', $customerKey['token'] );
				if(!empty($customerKey['phone'])){
					update_option( 'mo_saml_admin_phone', $customerKey['phone'] );
				}
				update_option('mo_saml_admin_password', '');
				update_option( 'mo_saml_message', 'Customer retrieved successfully');
				update_option('mo_saml_registration_status' , 'Existing User');
				delete_option('mo_saml_verify_customer');
				if(get_option('sml_lk')){
					$key = get_option('mo_saml_customer_token');
					$code = AESEncryption::decrypt_data(get_option('sml_lk'),$key);
					$content = json_decode($customer->mo_saml_vl($code,false),true);
					update_option('vl_check_t',time());
					if(strcasecmp($content['status'], 'SUCCESS') == 0){

					    $dir = plugin_dir_path( __FILE__ );

					    //home url of the site
					    $home_url = home_url();
                        $home_url = trim($home_url, '/');
                        if (!preg_match('#^http(s)?://#', $home_url)) {
                            $home_url = 'http://' . $home_url;
                        }
                        $urlParts = parse_url($home_url);
                        $domain = preg_replace('/^www\./', '', $urlParts['host']);

                        //upload path of the directory
                        $upload_dir   = wp_upload_dir();
                        $asdjka = $domain . '-' . $upload_dir['basedir'];

                        //Getting hash of the domain and upload path.
                        $dmndsasd = hash_hmac('sha256', $asdjka, '4DHfjgfjasndfsajfHGJ');

                        //finding the position where we need to insert this hash
                        $fdjk = $this->djkasjdksa();
                        $ihfak = round(strlen($fdjk)/rand(2,20));

                        //Adding hashvalue of domain and upload path ($dmndsasd) at the position $ihfak in string $fdjk
                        $fdjk = substr_replace($fdjk, $dmndsasd, $ihfak, 0);

                       //decoding the value and writing it in the file. If file has not write permission then updaing it in the table.
                        $decoded = base64_decode($fdjk);
                        if(is_writable ( $dir . 'license')) {
                            file_put_contents($dir . 'license', $decoded);
                        }else{
							$fdjk = str_rot13($fdjk);
							$opt_val = base64_decode('bGNkamthc2pka3NhY2w=');
                            update_option($opt_val, $fdjk);
                        }
                        update_option('lcwrtlfsaml', true);
						$this->mo_saml_show_success_message();
					}else{
						update_option( 'mo_saml_message','License key for this instance is incorrect. Make sure you have not tampered with it at all. Please enter a valid license key.');
						delete_option('sml_lk');
						$this->mo_saml_show_error_message();
					}
				}
				else
				    $this->mo_saml_show_success_message();

			} else {
				update_option( 'mo_saml_message', 'Invalid username or password. Please try again.');
				$this->mo_saml_show_error_message();
			}
			update_option('mo_saml_admin_password', '');
		} else if( self::mo_check_option_admin_referer('mo_saml_contact_us_query_option') ) {

			if(!mo_saml_is_extension_installed('curl')) {
				update_option( 'mo_saml_message', 'ERROR: PHP cURL extension is not installed or disabled. Query submit failed.');
				$this->mo_saml_show_error_message();
				return;
			}

			// Contact Us query
			$email = sanitize_email($_POST['mo_saml_contact_us_email']);
			$phone = htmlspecialchars($_POST['mo_saml_contact_us_phone']);
			$query = htmlspecialchars($_POST['mo_saml_contact_us_query']);

			if(array_key_exists('send_plugin_config',$_POST)===true) {
		        $plugin_config_json = miniorange_import_export(true, true);
		        $query .= $plugin_config_json;
		        delete_option('send_plugin_config');
	        }else
		        update_option('send_plugin_config','off');

			$customer = new CustomerSaml();
			if ( $this->mo_saml_check_empty_or_null( $email ) || $this->mo_saml_check_empty_or_null( $query ) ) {
				update_option('mo_saml_message', 'Please fill up Email and Query fields to submit your query.');
				$this->mo_saml_show_error_message();
			} else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
				update_option( 'mo_saml_message', 'Please enter a valid email address.' );
				$this->mo_saml_show_error_message();
				return;
			} else {
				$submited = $customer->submit_contact_us( $email, $phone, $query );
				if(!$submited)
					return;

				update_option('mo_saml_message', 'Thanks for getting in touch! We shall get back to you shortly.');
				$this->mo_saml_show_success_message();

			}
		}
		else if( self::mo_check_option_admin_referer('mo_saml_resend_otp_email') ) {

			if(!mo_saml_is_extension_installed('curl')) {
				update_option( 'mo_saml_message', 'ERROR: PHP cURL extension is not installed or disabled. Resend OTP failed.');
				$this->mo_saml_show_error_message();
				return;
			}
			$email = get_option ( 'mo_saml_admin_email' );
			$customer = new CustomerSaml();
			$content = $customer->send_otp_token($email, '');
			if(!$content)
				return;
			$content = json_decode( $content, true );
			if(strcasecmp($content['status'], 'SUCCESS') == 0) {
				update_option( 'mo_saml_message', ' A one time passcode is sent to ' . get_option('mo_saml_admin_email') . ' again. Please check if you got the otp and enter it here.');
				update_option('mo_saml_transactionId',$content['txId']);
				update_option('mo_saml_registration_status','MO_OTP_DELIVERED_SUCCESS_EMAIL');
				$this->mo_saml_show_success_message();
			}else{
				update_option('mo_saml_message','There was an error in sending email. Please click on Resend OTP to try again.');
				update_option('mo_saml_registration_status','MO_OTP_DELIVERED_FAILURE_EMAIL');
				$this->mo_saml_show_error_message();
			}
		} else if( self::mo_check_option_admin_referer('mo_saml_resend_otp_phone') ) {

			if(!mo_saml_is_extension_installed('curl')) {
				update_option( 'mo_saml_message', 'ERROR: PHP cURL extension is not installed or disabled. Resend OTP failed.');
				$this->mo_saml_show_error_message();
				return;
			}
			$phone = get_option('mo_saml_admin_phone');
			$customer = new CustomerSaml();
			$content = $customer->send_otp_token('', $phone, FALSE, TRUE);
			if(!$content)
				return;
			$content = json_decode( $content, true );
			if(strcasecmp($content['status'], 'SUCCESS') == 0) {
				update_option( 'mo_saml_message', ' A one time passcode is sent to ' . $phone . ' again. Please check if you got the otp and enter it here.');
				update_option('mo_saml_transactionId',$content['txId']);
				update_option('mo_saml_registration_status','MO_OTP_DELIVERED_SUCCESS_PHONE');
				$this->mo_saml_show_success_message();
			}else{
				update_option('mo_saml_message','There was an error in sending email. Please click on Resend OTP to try again.');
				update_option('mo_saml_registration_status','MO_OTP_DELIVERED_FAILURE_PHONE');
				$this->mo_saml_show_error_message();
			}
		}
		else if( self::mo_check_option_admin_referer('mo_saml_go_back') ){
			update_option('mo_saml_registration_status','');
			update_option('mo_saml_verify_customer', '');
			delete_option('mo_saml_new_registration');
			delete_option('mo_saml_admin_email');
			delete_option('mo_saml_admin_phone');
			delete_site_option ('sml_lk');
			delete_site_option ('t_site_status');
			delete_site_option ('site_ck_l');
		} else if( self::mo_check_option_admin_referer('mo_saml_register_with_phone_option') ) {
			if(!mo_saml_is_extension_installed('curl')) {
				update_option( 'mo_saml_message', 'ERROR: PHP cURL extension is not installed or disabled. Resend OTP failed.');
				$this->mo_saml_show_error_message();
				return;
			}
			$phone = htmlspecialchars($_POST['phone']);
			$phone = str_replace(' ', '', $phone);
			$phone = str_replace('-', '', $phone);
			update_option('mo_saml_admin_phone', $phone);
			$customer = new CustomerSaml();
			$content = $customer->send_otp_token('', $phone, FALSE, TRUE);
			if(!$content)
				return;
			$content = json_decode( $content, true );
			if(strcasecmp($content['status'], 'SUCCESS') == 0) {
				update_option( 'mo_saml_message', ' A one time passcode is sent to ' . get_option('mo_saml_admin_phone') . '. Please enter the otp here to verify your email.');
				update_option('mo_saml_transactionId',$content['txId']);
				update_option('mo_saml_registration_status','MO_OTP_DELIVERED_SUCCESS_PHONE');
				$this->mo_saml_show_success_message();
			}else{
				update_option('mo_saml_message','There was an error in sending SMS. Please click on Resend OTP to try again.');
				update_option('mo_saml_registration_status','MO_OTP_DELIVERED_FAILURE_PHONE');
				$this->mo_saml_show_error_message();
			}
		}else if(self::mo_check_option_admin_referer('mo_saml_registered_only_access_option')) {
			if(mo_saml_is_sp_configured()) {
				if(array_key_exists('mo_saml_registered_only_access', $_POST)) {
					$enable_redirect = htmlspecialchars($_POST['mo_saml_registered_only_access']);
				} else {
					$enable_redirect = 'false';
				}
				if($enable_redirect == 'true') {
					update_option('mo_saml_registered_only_access', 'true');
				} else {
					update_option('mo_saml_registered_only_access', '');
				}
				update_option( 'mo_saml_message', 'Sign in options updated.');
				$this->mo_saml_show_success_message();
			} else {
				update_option( 'mo_saml_message', 'Please complete '.addLink('Service Provider', add_query_arg( array('tab' => 'save'), $_SERVER['REQUEST_URI'] )).' configuration first.');
				$this->mo_saml_show_error_message();
			}
		}
		else if(self::mo_check_option_admin_referer('mo_saml_redirect_to_wp_login_option')) {
			if(mo_saml_is_sp_configured()) {
				if(array_key_exists('mo_saml_redirect_to_wp_login', $_POST))
					$redirect_wp = htmlspecialchars($_POST['mo_saml_redirect_to_wp_login']);
				else
					$redirect_wp = 'false';

				update_option('mo_saml_redirect_to_wp_login', $redirect_wp);
				update_option( 'mo_saml_message', 'Sign in options updated.');
				$this->mo_saml_show_success_message();
			}
		}
		else if( self::mo_check_option_admin_referer('mo_saml_force_authentication_option')) {
			if(mo_saml_is_sp_configured()) {
				if(array_key_exists('mo_saml_force_authentication', $_POST)) {
					$enable_redirect = htmlspecialchars($_POST['mo_saml_force_authentication']);
				} else {
					$enable_redirect = 'false';
				}
				if($enable_redirect == 'true') {
					update_option('mo_saml_force_authentication', 'true');
				} else {
					update_option('mo_saml_force_authentication', '');
				}
				update_option( 'mo_saml_message', 'Sign in options updated.');
				$this->mo_saml_show_success_message();
			} else {
				update_option( 'mo_saml_message', 'Please complete '.addLink('Service Provider' , add_query_arg( array('tab' => 'save'), $_SERVER['REQUEST_URI'] )) . ' configuration first.');
				$this->mo_saml_show_error_message();
			}
		} else if(self::mo_check_option_admin_referer('mo_saml_enable_rss_access_option')) {

			if(mo_saml_is_sp_configured()) {
				if(array_key_exists('mo_saml_enable_rss_access', $_POST)) {
					$custom_certificate_enabled = htmlspecialchars($_POST['mo_saml_enable_rss_access']);
				} else {
					$custom_certificate_enabled = false;
				}
				if($custom_certificate_enabled == 'true') {
					update_option('mo_saml_enable_rss_access', 'true');
				} else {
					update_option('mo_saml_enable_rss_access', '');
				}
				update_option( 'mo_saml_message', 'RSS Feed option updated.');
				$this->mo_saml_show_success_message();
			} else {
				update_option( 'mo_saml_message', 'Please complete '.addLink('Service Provider' , add_query_arg( array('tab' => 'save'), $_SERVER['REQUEST_URI'] )) . ' configuration first.');
				$this->mo_saml_show_error_message();
			}
		}  else if(self::mo_check_option_admin_referer('mo_saml_enable_login_redirect_option')) {
			if(mo_saml_is_sp_configured()) {
				if(array_key_exists('mo_saml_enable_login_redirect', $_POST)) {
					$enable_redirect = htmlspecialchars($_POST['mo_saml_enable_login_redirect']);
				} else {
					$enable_redirect = 'false';
				}
				if($enable_redirect == 'true') {
					update_option('mo_saml_enable_login_redirect', 'true');
					update_option('mo_saml_allow_wp_signin', 'true');
				} else {
					update_option('mo_saml_enable_login_redirect', '');
					update_option('mo_saml_allow_wp_signin', '');
				}
				update_option( 'mo_saml_message', 'Sign in options updated.');
				$this->mo_saml_show_success_message();
			} else {
				update_option( 'mo_saml_message', 'Please complete '.addLink('Service Provider' , add_query_arg( array('tab' => 'save'), $_SERVER['REQUEST_URI'] )) . ' configuration first.');
				$this->mo_saml_show_error_message();
			}
		} else if(self::mo_check_option_admin_referer('mo_saml_add_sso_button_wp_option')){
			if(mo_saml_is_sp_configured()) {
				if(array_key_exists("mo_saml_add_sso_button_wp", $_POST)) {
					$add_button = htmlspecialchars($_POST['mo_saml_add_sso_button_wp']);
				} else {
					$add_button = 'false';
				}
				update_option('mo_saml_add_sso_button_wp', $add_button);
				update_option('mo_saml_message', 'Sign in options updated.');
				$this->mo_saml_show_success_message();
			} else {
				update_option( 'mo_saml_message', 'Please complete '.addLink('Service Provider' , add_query_arg( array('tab' => 'save'), $_SERVER['REQUEST_URI'] )) . ' configuration first.');
				$this->mo_saml_show_error_message();
			}
		} else if(self::mo_check_option_admin_referer('mo_saml_use_button_as_shortcode_option')){
			if(mo_saml_is_sp_configured()) {
				if(array_key_exists("mo_saml_use_button_as_shortcode", $_POST)) {
					$use_button = htmlspecialchars($_POST['mo_saml_use_button_as_shortcode']);
				} else {
					$use_button = 'false';
				}
				update_option('mo_saml_use_button_as_shortcode', $use_button);
				update_option('mo_saml_message', 'Sign in options updated.');
				$this->mo_saml_show_success_message();
			} else {
				update_option( 'mo_saml_message', 'Please complete '.addLink('Service Provider' , add_query_arg( array('tab' => 'save'), $_SERVER['REQUEST_URI'] )) . ' configuration first.');
				$this->mo_saml_show_error_message();
			}
		} else if(self::mo_check_option_admin_referer('mo_saml_use_button_as_widget_option')){
			if(mo_saml_is_sp_configured()) {
				if(array_key_exists("mo_saml_use_button_as_widget", $_POST)) {
					$use_button = htmlspecialchars($_POST['mo_saml_use_button_as_widget']);
				} else {
					$use_button = 'false';
				}
				update_option('mo_saml_use_button_as_widget', $use_button);
				update_option('mo_saml_message', 'Sign in options updated.');
				$this->mo_saml_show_success_message();
			} else {
				update_option( 'mo_saml_message', 'Please complete '.addLink('Service Provider' , add_query_arg( array('tab' => 'save'), $_SERVER['REQUEST_URI'] )) . ' configuration first.');
				$this->mo_saml_show_error_message();
			}
		}


        else if(self::mo_check_option_admin_referer('mo_saml_allow_wp_signin_option')) {
			$mo_saml_backdoor_url_query = "false";

			if(array_key_exists('mo_saml_allow_wp_signin', $_POST)) {
				$allow_wp_signin = htmlspecialchars($_POST['mo_saml_allow_wp_signin']);
			} else {
				$allow_wp_signin = 'false';
			}
			if($allow_wp_signin == 'true') {

				update_option('mo_saml_allow_wp_signin', 'true');
				if(array_key_exists("mo_saml_backdoor_url",$_POST)){
					$mo_saml_backdoor_url_query=htmlspecialchars(trim($_POST['mo_saml_backdoor_url']));
				}
			} else {
				update_option('mo_saml_allow_wp_signin', '');
			}
			update_option('mo_saml_backdoor_url',$mo_saml_backdoor_url_query);
			update_option( 'mo_saml_message', 'Sign In settings updated.');
			$this->mo_saml_show_success_message();
		} else if(self::mo_check_option_admin_referer('mo_saml_custom_button_option')){
			$buttonType = "";
			$buttonCustomTheme = "";
			$buttonSize = "";
			$buttonWidth = "";
			$buttonHeight = "";
			$buttonCurve = "";
			$buttonColor = "";
			$buttonText = "";
			$fontColor = "";
			$fontSize = "";
			$customButtonPosition = "above";
			if(array_key_exists('mo_saml_button_size', $_POST) && !empty($_POST['mo_saml_button_size'])){
				$buttonSize = htmlspecialchars($_POST['mo_saml_button_size']);
			}
			if(array_key_exists('mo_saml_button_width', $_POST) && !empty($_POST['mo_saml_button_width'])){
				$buttonWidth = htmlspecialchars($_POST['mo_saml_button_width']);
			}
			if(array_key_exists('mo_saml_button_height', $_POST) && !empty($_POST['mo_saml_button_height'])){
				$buttonHeight = htmlspecialchars($_POST['mo_saml_button_height']);
			}
			if(array_key_exists('mo_saml_button_curve', $_POST) && !empty($_POST['mo_saml_button_curve'])){
				$buttonCurve = htmlspecialchars($_POST['mo_saml_button_curve']);
			}
			if(array_key_exists('mo_saml_button_color', $_POST)){
				$buttonColor = htmlspecialchars($_POST['mo_saml_button_color']);
			}
			if(array_key_exists('mo_saml_button_theme', $_POST)){
				$buttonType = htmlspecialchars($_POST['mo_saml_button_theme']);
			}

			if(array_key_exists('mo_saml_button_text', $_POST)){
				$buttonText = htmlspecialchars($_POST['mo_saml_button_text']);
				if(empty($buttonText) || $buttonText == 'Login'){
					$buttonText = "Login";
				}
				$identity_provider = get_option('saml_identity_name');
				$buttonText = str_replace("##IDP##", $identity_provider, $buttonText);
			}
			if(array_key_exists('mo_saml_font_color', $_POST)){
				$fontColor = htmlspecialchars($_POST['mo_saml_font_color']);
			}
			if(array_key_exists('mo_saml_font_size', $_POST)){
				$fontSize = htmlspecialchars($_POST['mo_saml_font_size']);
			}
			if(array_key_exists('sso_button_login_form_position', $_POST)){
				$customButtonPosition = htmlspecialchars($_POST['sso_button_login_form_position']);
			}
			update_option('mo_saml_button_theme', $buttonType);
			update_option('mo_saml_button_size', $buttonSize);
			update_option('mo_saml_button_width', $buttonWidth);
			update_option('mo_saml_button_height', $buttonHeight);
			update_option('mo_saml_button_curve', $buttonCurve);
			update_option('mo_saml_button_color', $buttonColor);
			update_option('mo_saml_button_text', $buttonText);
			update_option('mo_saml_font_color', $fontColor);
			update_option('mo_saml_font_size', $fontSize);
			update_option('sso_button_login_form_position', $customButtonPosition);
			update_option('mo_saml_message', 'Sign In settings updated.');
			$this->mo_saml_show_success_message();
		} else if(self::mo_check_option_admin_referer('mo_saml_forgot_password_form_option')){
			if(!mo_saml_is_extension_installed('curl')) {
				update_option( 'mo_saml_message', 'ERROR: PHP cURL extension is not installed or disabled. Resend OTP failed.');
				$this->mo_saml_show_error_message();
				return;
			}

			$email = get_option('mo_saml_admin_email');

			$customer = new Customersaml();
			$content = $customer->mo_saml_forgot_password($email);
			if(!$content)
				return;
			$content = json_decode( $content, true );
			if(strcasecmp($content['status'], 'SUCCESS') == 0){
				update_option( 'mo_saml_message','Your password has been reset successfully. Please enter the new password sent to ' . $email . '.');
				$this->mo_saml_show_success_message();
			}else{
				update_option( 'mo_saml_message','An error occured while processing your request. Please Try again.');
				$this->mo_saml_show_error_message();
			}
		}
		else if(self::mo_check_option_admin_referer('mo_saml_verify_license')){

			if( $this->mo_saml_check_empty_or_null( $_POST['saml_licence_key'] ) ) {
				update_option( 'mo_saml_message', 'All the fields are required. Please enter valid license key.');
				$this->mo_saml_show_error_message();
				return;
			}

			$code = htmlspecialchars(trim($_POST['saml_licence_key']));
			$customer = new Customersaml();
			$content = $customer->check_customer_ln();
			if(!$content)
				return;
			$content = json_decode( $content, true );
			if(strcasecmp($content['status'], 'SUCCESS') == 0){
				$content = json_decode($customer->mo_saml_vl($code,false),true);
				update_option('vl_check_t',time());
				if(is_array($content) and strcasecmp($content['status'], 'SUCCESS') == 0){
					$key = get_option('mo_saml_customer_token');
					update_option('sml_lk',AESEncryption::encrypt_data($code,$key));
					$message = 'Your license is verified. You can now setup the plugin.';
					update_option( 'mo_saml_message',$message);
					$key = get_option('mo_saml_customer_token');
					update_option('site_ck_l',AESEncryption::encrypt_data("true",$key));
					update_option('t_site_status',AESEncryption::encrypt_data("false",$key));

                    $dir = plugin_dir_path( __FILE__ );

                    //home url of the site
                    $home_url = home_url();
                    $home_url = trim($home_url, '/');
                    if (!preg_match('#^http(s)?://#', $home_url)) {
                        $home_url = 'http://' . $home_url;
                    }
                    $urlParts = parse_url($home_url);
                    $domain = preg_replace('/^www\./', '', $urlParts['host']);

                    //upload path of the directory
                    $upload_dir   = wp_upload_dir();
                    $asdjka = $domain . '-' . $upload_dir['basedir'];

                    //Getting hash of the domain and upload path.
                    $dmndsasd = hash_hmac('sha256', $asdjka, '4DHfjgfjasndfsajfHGJ');

                    //finding the position where we need to insert this hash
                    $fdjk = $this->djkasjdksa();
                    $ihfak = round(strlen($fdjk)/rand(2,20));

                    //Adding hashvalue of domain and upload path ($dmndsasd) at the position $ihfak in string $fdjk
                    $fdjk = substr_replace($fdjk, $dmndsasd, $ihfak, 0);

                    //decoding the value and writing it in the file. If file has not write permission then updaing it in the table.
                    $decoded = base64_decode($fdjk);
                    if(is_writable ( $dir . 'license')) {
                        file_put_contents($dir . 'license', $decoded);
                    }else{
						$fdjk = str_rot13($fdjk);
						$opt_val = base64_decode('bGNkamthc2pka3NhY2w=');
                        update_option($opt_val, $fdjk);
                    }
                    update_option('lcwrtlfsaml', true);

                    $url = add_query_arg( array('tab' => 'general'), $_SERVER['REQUEST_URI'] );
                    $this->mo_saml_show_success_message();
				}else if(is_array($content) and strcasecmp($content['status'], 'FAILED') == 0){
					if(strcasecmp($content['message'], 'Code has Expired') == 0){
						$url = add_query_arg( array('tab' => 'licensing'), $_SERVER['REQUEST_URI'] );
						update_option( 'mo_saml_message','License key you have entered has already been used. Please enter a key which has not been used before on any other instance or if you have exausted all your keys then '.addLink('Click here' , $url) . ' to buy more.');
					}else{
						update_option( 'mo_saml_message','You have entered an invalid license key. Please enter a valid license key.');
					}
					$this->mo_saml_show_error_message();
				}else{
					update_option( 'mo_saml_message','An error occured while processing your request. Please Try again.');
					$this->mo_saml_show_error_message();
				}
			}else {
				$key = get_option('mo_saml_customer_token');
				update_option('site_ck_l',AESEncryption::encrypt_data("false",$key));
				$url = add_query_arg( array('tab' => 'licensing'), $_SERVER['REQUEST_URI'] );
				update_option( 'mo_saml_message','You have not upgraded yet. '.addLink('Click here' , $url).' to upgrade to premium version.');
				$this->mo_saml_show_error_message();
			}

		}
		else if(self::mo_check_option_admin_referer('mo_saml_free_trial')){
			if(decryptSamlElement()){
				update_option( 'mo_saml_message','There was an error activating your TRIAL version. Either your trial period is expired or you are using wrong trial version. Please contact info@xecurify.com for getting new license for trial version.');
				$this->mo_saml_show_error_message();
			}else {
				$code = postResponse();
				$customer = new Customersaml();
				$content = $customer->mo_saml_vl($code,false);
				if(!$content)
					return;
				$content = json_decode( $content, true );
				if(strcasecmp($content['status'], 'SUCCESS') == 0){
					$key = get_option('mo_saml_customer_token');
					$key = get_option('mo_saml_customer_token');
					update_option('t_site_status',AESEncryption::encrypt_data("true",$key));
					update_option( 'mo_saml_message','Your 5 days TRIAL is activated. You can now setup the plugin.');
					$this->mo_saml_show_success_message();
				}else if(strcasecmp($content['status'], 'FAILED') == 0){
					update_option( 'mo_saml_message','There was an error activating your TRIAL version. Please contact info@xecurify.com for getting new license for trial version.');
					$this->mo_saml_show_error_message();
				}else{
					update_option( 'mo_saml_message','An error occured while processing your request. Please Try again.');
					$this->mo_saml_show_error_message();
				}
			}
		}
		else  if(self::mo_check_option_admin_referer('mo_saml_check_license')){
			$customer = new Customersaml();
			$content = $customer->check_customer_ln();
			if(!$content)
				return;
			$content = json_decode( $content, true );
			if(strcasecmp($content['status'], 'SUCCESS') == 0){
				if(array_key_exists('licensePlan', $content) && !$this->mo_saml_check_empty_or_null($content['licensePlan'])) { 	  update_option('mo_saml_license_name',base64_encode($content['licensePlan']));
				$key = get_option('mo_saml_customer_token');
				if(array_key_exists('noOfUsers', $content) && !$this->mo_saml_check_empty_or_null($content['noOfUsers'])){
					update_option('mo_saml_usr_lmt',AESEncryption::encrypt_data($content['noOfUsers'],$key));
				}
				update_option('site_ck_l',AESEncryption::encrypt_data("true",$key));

                $dir = plugin_dir_path( __FILE__ );

                //home url of the site
                $home_url = home_url();
                $home_url = trim($home_url, '/');
                if (!preg_match('#^http(s)?://#', $home_url)) {
                    $home_url = 'http://' . $home_url;
                }
                $urlParts = parse_url($home_url);
                $domain = preg_replace('/^www\./', '', $urlParts['host']);

                //upload path of the directory
                $upload_dir   = wp_upload_dir();
                $asdjka = $domain . '-' . $upload_dir['basedir'];

                //Getting hash of the domain and upload path.
                $dmndsasd = hash_hmac('sha256', $asdjka, '4DHfjgfjasndfsajfHGJ');

                //finding the position where we need to insert this hash
                $fdjk = $this->djkasjdksa();
                $ihfak = round(strlen($fdjk)/rand(2,20));

                //Adding hashvalue of domain and upload path ($dmndsasd) at the position $ihfak in string $fdjk
                $fdjk = substr_replace($fdjk, $dmndsasd, $ihfak, 0);

                //decoding the value and writing it in the file. If file has not write permission then updaing it in the table.
                $decoded = base64_decode($fdjk);
                if(is_writable ( $dir . 'license')) {
                    file_put_contents($dir . 'license', $decoded);
                }else{
					$fdjk = str_rot13($fdjk);
					$opt_val = base64_decode('bGNkamthc2pka3NhY2w=');
                    update_option($opt_val, $fdjk);
                }
                update_option('lcwrtlfsaml', true);

				$url = add_query_arg( array('tab' => 'general'), $_SERVER['REQUEST_URI'] );
				update_option( 'mo_saml_message','You have successfully upgraded your license.');
				$this->mo_saml_show_success_message();
			}else {
				$key = get_option('mo_saml_customer_token');
				update_option('site_ck_l',AESEncryption::encrypt_data("false",$key));
				$url = add_query_arg( array('tab' => 'licensing'), $_SERVER['REQUEST_URI'] );
				update_option( 'mo_saml_message','You have not upgraded yet. '.addLink('Click here' , $url).' to upgrade to premium version.');
				$this->mo_saml_show_error_message();
			}
		}else {
			$key = get_option('mo_saml_customer_token');
			update_option('site_ck_l',AESEncryption::encrypt_data("false",$key));
			$url = add_query_arg( array('tab' => 'licensing'), $_SERVER['REQUEST_URI'] );
			update_option( 'mo_saml_message','You have not upgraded yet. '.addLink('Click here' , $url).' to upgrade to premium version.');
			$this->mo_saml_show_error_message();
		}
	}
	else if(self::mo_check_option_admin_referer('mo_saml_remove_account')){

		$this->mo_sso_saml_deactivate();
		add_option('mo_saml_registration_status','removed_account');
		$url = add_query_arg( array('tab' => 'login'), $_SERVER['REQUEST_URI'] );
		header('Location: '.$url);

	}

}
if(mo_saml_is_trial_active()){
	if(decryptSamlElement()){
		$key = get_option('mo_saml_customer_token');
		update_option('t_site_status',AESEncryption::encrypt_data("false",$key));
	}
} else if(!site_check()){

	delete_option ( 'mo_saml_force_authentication' );
}
}

function djkasjdksa() {
    $djashdj = '!~@#$%^&*()_+|{}<>?0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $djaskdjsakjdkas = strlen($djashdj);
    $djaskjdksa = '';
    for ($i = 0; $i < 10000; $i++) {
        $djaskjdksa .= $djashdj[rand(0, $djaskdjsakjdkas - 1)];
    }
    return $djaskjdksa;
}
function create_customer(){
	$customer = new CustomerSaml();
	$content = $customer->create_customer();
	if(!$content)
		return;
	$customerKey = json_decode( $content, true );
	if( strcasecmp( $customerKey['status'], 'CUSTOMER_USERNAME_ALREADY_EXISTS') == 0 ) {
		$this->get_current_customer();
	} else if( strcasecmp( $customerKey['status'], 'SUCCESS' ) == 0 ) {
		update_option( 'mo_saml_admin_customer_key', $customerKey['id'] );
		update_option( 'mo_saml_admin_api_key', $customerKey['apiKey'] );
		update_option( 'mo_saml_customer_token', $customerKey['token'] );
		update_option('mo_saml_admin_password', '');
		update_option( 'mo_saml_message', 'Thank you for registering with miniorange.');
		update_option('mo_saml_registration_status','');
		delete_option('mo_saml_verify_customer');
		delete_option('mo_saml_new_registration');
		$this->mo_saml_show_success_message();
	}
	update_option('mo_saml_admin_password', '');
}

function get_current_customer(){
	$customer = new CustomerSaml();
	$content = $customer->get_customer_key();
	if(!$content)
		return;
	$customerKey = json_decode( $content, true );
	if( json_last_error() == JSON_ERROR_NONE ) {
		update_option( 'mo_saml_admin_customer_key', $customerKey['id'] );
		update_option( 'mo_saml_admin_api_key', $customerKey['apiKey'] );
		update_option( 'mo_saml_customer_token', $customerKey['token'] );
		update_option('mo_saml_admin_password', '' );
		update_option( 'mo_saml_message', 'Your account has been retrieved successfully.' );
		delete_option('mo_saml_verify_customer');
		delete_option('mo_saml_new_registration');
		$this->mo_saml_show_success_message();
	} else {
		update_option( 'mo_saml_message', 'You already have an account with miniOrange. Please enter a valid password.');
		update_option('mo_saml_verify_customer', 'true');
		delete_option('mo_saml_new_registration');
		$this->mo_saml_show_error_message();
	}

}
public function mo_saml_check_empty_or_null( $value ) {
	if( ! isset( $value ) || empty( $value ) ) {
		return true;
	}
	return false;
}

function miniorange_sso_menu() {
		//Add miniOrange SAML SSO
	$page = add_menu_page( 'MO SAML Settings ' . __( 'Configure SAML Identity Provider for SSO', 'mo_saml_settings' ), 'miniOrange SAML 2.0 SSO', 'administrator', 'mo_saml_settings', array( $this, 'mo_login_widget_saml_options' ), plugin_dir_url(__FILE__) . 'images/miniorange.png' );
}


function mo_saml_redirect_for_authentication( $relay_state ) {

	if( mo_saml_is_customer_license_key_verified() ) {

        if( get_option('mo_saml_registered_only_access') == 'true' ) {
            $base_url=home_url();
            echo "<script>window.location.href='$base_url/?option=saml_user_login&redirect_to='+encodeURIComponent(window.location.href);</script>";
            exit;

        }

		if(get_option('mo_saml_registered_only_access') == 'true' || get_option('mo_saml_enable_login_redirect') == 'true') {
			if(mo_saml_is_sp_configured() && !is_user_logged_in()) {
				$sp_base_url = get_option('mo_saml_sp_base_url');
				if(empty($sp_base_url)) {
					$sp_base_url = home_url();
				}
				if(get_option('mo_saml_relay_state') && get_option('mo_saml_relay_state')!=''){
					$relay_state=get_option('mo_saml_relay_state');
				}

                $relay_state=mo_saml_get_relay_state($relay_state);

				$sendRelayState = empty($relay_state)?"/":$relay_state;
				$ssoUrl = html_entity_decode(get_option("saml_login_url"));
				$sso_binding_type = get_option("saml_login_binding_type");
				$force_authn = get_option('mo_saml_force_authentication');
				$acsUrl = $sp_base_url."/";
				$sp_entity_id = get_option('mo_saml_sp_entity_id');
				$saml_nameid_format = get_option('saml_nameid_format');
                if(empty($saml_nameid_format))
                    $saml_nameid_format = '1.1:nameid-format:unspecified';
				if(empty($sp_entity_id)) {
					$sp_entity_id = $sp_base_url.'/wp-content/plugins/miniorange-saml-20-single-sign-on/';
				}

					//$sp_entity_id = $sp_base_url . '/wp-content/plugins/miniorange-saml-20-single-sign-on/';
				$samlRequest = SAMLSPUtilities::createAuthnRequest($acsUrl, $sp_entity_id, $ssoUrl, $force_authn, $sso_binding_type, $saml_nameid_format);

				if(empty($sso_binding_type) || $sso_binding_type == 'HttpRedirect') {
					$redirect = $ssoUrl;
					if (strpos($ssoUrl,'?') !== false) {
						$redirect .= '&';
					} else {
						$redirect .= '?';
					}
					if(get_option('saml_request_signed')=='unchecked'){
						$redirect .= 'SAMLRequest=' . $samlRequest . '&RelayState=' . urlencode($sendRelayState);
						header('Location: '.$redirect);
						exit();
					}
					$samlRequest = "SAMLRequest=" . $samlRequest . "&RelayState=" . urlencode($sendRelayState) . '&SigAlg='. urlencode(XMLSecurityKey::RSA_SHA256);
					$param =array( 'type' => 'private');
					$key = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, $param);
					//$certFilePath = plugin_dir_path(__FILE__) . 'resources' . DIRECTORY_SEPARATOR . 'sp-key.key';
					$certFilePath = get_option( 'mo_saml_current_cert_private_key' );
					//$key->loadKey($certFilePath, TRUE);
					$key->loadKey($certFilePath,FALSE);

					$objXmlSecDSig = new XMLSecurityDSig();
					$signature = $key->signData($samlRequest);
					$signature = base64_encode($signature);
					$redirect .= $samlRequest . '&Signature=' . urlencode($signature);
					header('Location: '.$redirect);
					exit();
				} else {
					if(get_option('saml_request_signed')=='unchecked'){
						$base64EncodedXML = base64_encode($samlRequest);
						SAMLSPUtilities::postSAMLRequest($ssoUrl, $base64EncodedXML, $sendRelayState);
						exit();
					}
					$privateKeyPath = "";
					$publicCertPath = "";

					// $base64EncodedXML = SAMLSPUtilities::signXML( $samlRequest, $publicCertPath, $privateKeyPath, 'NameIDPolicy' );
					$base64EncodedXML = SAMLSPUtilities::signXML( $samlRequest, 'NameIDPolicy' );

					SAMLSPUtilities::postSAMLRequest($ssoUrl, $base64EncodedXML, $sendRelayState);
				}
			}

		} else if(get_option('mo_saml_redirect_to_wp_login') == 'true') {
			if(mo_saml_is_sp_configured() && !is_user_logged_in()){
				$url = site_url() . '/wp-login.php';
				if(!empty($relay_state)){
					$url = $url . '?redirect_to=' . urlencode($relay_state) . '&reauth=1';
				}
				header('Location: '.$url);
				exit();
			}
		}
	}
}

function mo_saml_authenticate() {
        $redirect_to = '';

        if ( isset( $_REQUEST['redirect_to']) ) {
            $redirect_to = htmlspecialchars($_REQUEST['redirect_to']);

        }
        if( is_user_logged_in() ) {

            if(!empty($redirect_to)) {
                header('Location: ' . $redirect_to);
            } else {
                header('Location: ' . home_url());
            }
            exit();
        }
        if( get_option('mo_saml_enable_login_redirect') == 'true' ) {
            $saml_backdoor_url = get_option('mo_saml_backdoor_url')?trim(get_option('mo_saml_backdoor_url')):'false';

            if( isset($_GET['loggedout']) && $_GET['loggedout'] == 'true' ) {
                header('Location: ' . home_url());

                exit();
            } elseif ( get_option('mo_saml_allow_wp_signin') == 'true' ) {
                if( ( isset($_GET['saml_sso']) && $_GET['saml_sso'] === $saml_backdoor_url ) || ( isset($_POST['saml_sso']) && $_POST['saml_sso'] === $saml_backdoor_url ) ) {

                    return;
                }
                elseif ( isset( $_REQUEST['redirect_to']) ) {
                    $redirect_to = htmlspecialchars($_REQUEST['redirect_to']);

                    if( strpos( $redirect_to, 'wp-admin') !== false && strpos( $redirect_to, 'saml_sso='.$saml_backdoor_url) !== false) {
                        return;
                    }
                }
            }

            $this->mo_saml_redirect_for_authentication( $redirect_to );
        }
    }

function mo_saml_auto_redirect() {
	if ( current_user_can( 'read' ) ) {
		return;
	}


	if( get_option('mo_saml_registered_only_access') == 'true' || get_option('mo_saml_redirect_to_wp_login') == 'true') {

		if( get_option('mo_saml_enable_rss_access') == 'true' && is_feed())
			return;

		$relay_state = saml_get_current_page_url();
		$this->mo_saml_redirect_for_authentication( $relay_state );
	}

}

function mo_saml_modify_login_form() {
	$saml_backdoor_url = get_option('mo_saml_backdoor_url')?trim(get_option('mo_saml_backdoor_url')):'false';
	echo '<input type="hidden" name="saml_sso" value='.$saml_backdoor_url.'>'."\n";

	if(get_option('mo_saml_add_sso_button_wp') == 'true')
		$this->mo_saml_add_sso_button();
}

function mo_saml_login_enqueue_scripts(){
	wp_enqueue_script('jquery');
}


function mo_saml_add_sso_button() {
	if(!is_user_logged_in()){
		$sp_base_url = get_option('mo_saml_sp_base_url');
		if(empty($sp_base_url)) {
			$sp_base_url = home_url();
		}

		$customWidth = get_option('mo_saml_button_width') ? get_option('mo_saml_button_width') : '100';
		$customHeight = get_option('mo_saml_button_height') ? get_option('mo_saml_button_height') : '50';
		$customSize = get_option('mo_saml_button_size') ? get_option('mo_saml_button_size') : '50';
		$customCurve = get_option('mo_saml_button_curve') ? get_option('mo_saml_button_curve') : '5';
		$customButtonColor = get_option('mo_saml_button_color') ? get_option('mo_saml_button_color') : '0085ba';
		$customTheme = get_option('mo_saml_button_theme') ? get_option('mo_saml_button_theme') : 'longbutton';
		$customButtonText = get_option('mo_saml_button_text') ? get_option('mo_saml_button_text') : (get_option('saml_identity_name') ? get_option('saml_identity_name') : 'Login');
		$customTextColor=get_option('mo_saml_font_color') ? get_option('mo_saml_font_color') : 'ffffff';
		$customeFontSize = get_option('mo_saml_font_size') ? get_option('mo_saml_font_size') : '20';
		$customButtonPosition = get_option('sso_button_login_form_position') ? get_option('sso_button_login_form_position') : 'above';


		$login_button = '<input type="button" name="mo_saml_wp_sso_button" value="' . $customButtonText .'" style="';
		$style ='';
		if($customTheme == "longbutton"){
			$style = $style . 'width:' . $customWidth . 'px;';
			$style = $style . 'height:' . $customHeight .'px;';
			$style = $style . 'border-radius:' . $customCurve . 'px;';
		} else {
			if($customTheme == "circle"){
				$style = $style . 'width:' . $customSize . 'px;';
				$style = $style . 'height:' . $customSize . 'px;';
				$style = $style . 'border-radius:999px;';
			} elseif($customTheme == "oval"){
				$style = $style . 'width:' . $customSize . 'px;';
				$style = $style . 'height:' . $customSize . 'px;';
				$style = $style . 'border-radius:5px;';
			} elseif($customTheme == "square"){
				$style = $style . 'width:' . $customSize . 'px;';
				$style = $style . 'height:' . $customSize . 'px;';
				$style = $style . 'border-radius:0px;';
				$style = $style . 'padding:0px;';
			}
		}
		$style = $style . 'background-color:#' . $customButtonColor . ';';
		$style = $style . 'border-color:transparent;';
		$style = $style . 'color:#' . $customTextColor . ';';
		$style = $style . 'font-size:' . $customeFontSize . 'px;';
		$style = $style . 'cursor:pointer';
		$login_button = $login_button . $style . '"/>';

		// if(get_option('mo_saml_enable_cloud_broker') !='true'){
			$redirect_to = '';
			if(isset($_GET['redirect_to']))
				$redirect_to = urlencode($_GET['redirect_to']);

			$html = '<a href="'. $sp_base_url. '/?option=saml_user_login&redirect_to=' . $redirect_to .'" style="text-decoration:none;">' . $login_button . '</a>';

		// }else{
		// 	$html = '<a href="' . get_option('mo_saml_host_name')."/moas/rest/saml/request?id=".get_option('mo_saml_admin_customer_key')."&returnurl=".urlencode( home_url() . '/?option=readsamllogin' ) . '" style="text-decoration:none">' . $login_button . '</a>';
		// }
		$html = '<div style="padding:10px;">' . $html . '</div>';
		// $html = '<div id="sso_button" style="text-align:center">' . $html . '</div><br/>';
		if($customButtonPosition == 'above'){
			$html = '<div id="sso_button" style="text-align:center">' . $html . '<div style="padding:5px;font-size:14px;"><b>OR</b></div></div><br/>';
			$html = $html . '<script>
			var $element = jQuery("#user_login");
			jQuery("#sso_button").insertBefore(jQuery("label[for=\'"+$element.attr(\'id\')+"\']"));
			</script>';
		} else {
			$html = '<div id="sso_button" style="text-align:center"><div style="padding:5px;font-size:14px;"><b>OR</b></div>' . $html . '</div><br/>';
			// $html = $html . '<script>
			// jQuery("#sso_button").insertAfter(jQuery("#wp-submit"));
			// </script>';
		}
		echo $html;
	}
}

function mo_get_saml_shortcode(){

    if(!is_user_logged_in()){
        $sp_base_url = get_option('mo_saml_sp_base_url');
        if(empty($sp_base_url)) {
            $sp_base_url = home_url();
        }
        if(mo_saml_is_sp_configured() && mo_saml_is_customer_license_key_verified()){
            $login_title = 'Login with '.get_option('saml_identity_name');
            if(get_option('mo_saml_custom_login_text')) {
                $login_title = get_option('mo_saml_custom_login_text');
			}
			$identity_provider = get_option('saml_identity_name');
			$login_title = str_replace("##IDP##", $identity_provider, $login_title);

			$use_button = false;
			if(get_option('mo_saml_use_button_as_shortcode')){
				if(get_option('mo_saml_use_button_as_shortcode') == "true"){
					$use_button = true;
				}
			}
			if($use_button){
				$customWidth = get_option('mo_saml_button_width') ? get_option('mo_saml_button_width') : '100';
				$customHeight = get_option('mo_saml_button_height') ? get_option('mo_saml_button_height') : '50';
				$customSize = get_option('mo_saml_button_size') ? get_option('mo_saml_button_size') : '50';
				$customCurve = get_option('mo_saml_button_curve') ? get_option('mo_saml_button_curve') : '5';
				$customButtonColor = get_option('mo_saml_button_color') ? get_option('mo_saml_button_color') : '0085ba';
				$customTheme = get_option('mo_saml_button_theme') ? get_option('mo_saml_button_theme') : 'longbutton';
				$customButtonText = get_option('mo_saml_button_text') ? get_option('mo_saml_button_text') : (get_option('saml_identity_name') ? get_option('saml_identity_name') : 'Login');
				$customTextColor=get_option('mo_saml_font_color') ? get_option('mo_saml_font_color') : 'ffffff';
				$customeFontSize = get_option('mo_saml_font_size') ? get_option('mo_saml_font_size') : '20';

				$login_title = '<input type="button" name="mo_saml_wp_sso_button" value="' . $customButtonText .'" style="';
				$style ='';
				if($customTheme == "longbutton"){
					$style = $style . 'width:' . $customWidth . 'px;';
					$style = $style . 'height:' . $customHeight .'px;';
					$style = $style . 'border-radius:' . $customCurve . 'px;';
				} else {
					if($customTheme == "circle"){
						$style = $style . 'width:' . $customSize . 'px;';
						$style = $style . 'height:' . $customSize . 'px;';
						$style = $style . 'border-radius:999px;';
					} elseif($customTheme == "oval"){
						$style = $style . 'width:' . $customSize . 'px;';
						$style = $style . 'height:' . $customSize . 'px;';
						$style = $style . 'border-radius:5px;';
					} elseif($customTheme == "square"){
						$style = $style . 'width:' . $customSize . 'px;';
						$style = $style . 'height:' . $customSize . 'px;';
						$style = $style . 'border-radius:0px;';
					}
				}
				$style = $style . 'background-color:#' . $customButtonColor . ';';
				$style = $style . 'border-color:transparent;';
				$style = $style . 'color:#' . $customTextColor . ';';
				$style = $style . 'font-size:' . $customeFontSize . 'px;';
				$style = $style . 'padding:0px;';
				$login_title = $login_title . $style . '"/>';

			}

                $redirect_to = urlencode(saml_get_current_page_url());
				$html = '<a href="'. $sp_base_url.'/?option=saml_user_login&redirect_to='.$redirect_to . '"';
				if($use_button){
					$html = $html . 'style="text-decoration:none;"';
				}
				$html = $html. '>' . $login_title . '</a>';
        }else
            $html = 'SP is not configured.';
    }
    else {
        $current_user = wp_get_current_user();
		$custom_greeting_text = "Hello,";
		if(get_option('mo_saml_custom_greeting_text')){
			$custom_greeting_text = get_option('mo_saml_custom_greeting_text');
		}
		$custom_greeting_name = '';
		if(get_option('mo_saml_greeting_name')){
			switch(get_option('mo_saml_greeting_name')){
				case 'USERNAME':
					$custom_greeting_name = $current_user->user_login;
					break;
				case 'EMAIL':
					$custom_greeting_name = $current_user->user_email;
					break;
				case 'FNAME':
					$custom_greeting_name = $current_user->user_firstname;
					break;
				case 'LNAME':
					$custom_greeting_name = $current_user->user_lastname;
					break;
				case 'FNAME_LNAME':
					$custom_greeting_name = $current_user->user_firstname . ' ' . $current_user->user_lastname;
					break;
				case 'LNAME_FNAME':
					$custom_greeting_name = $current_user->user_lastname . ' ' . $current_user->user_firstname;
					break;
				default:
					$custom_greeting_name = $current_user->user_login;
			}
		}
		$custom_greeting_name = trim($custom_greeting_name);
		if(empty($custom_greeting_name)){
			$custom_greeting_name = $current_user->user_login;
		}
		$custom_greeting = $custom_greeting_text . ' ' . $custom_greeting_name;
		$custom_logout = "Logout";
		if(get_option('mo_saml_custom_logout_text'))
			$custom_logout = get_option('mo_saml_custom_logout_text');
		$html = $custom_greeting.' | <a href="'.wp_logout_url(home_url()).'" title="logout" >'.$custom_logout.'</a></li>';

	}

    return $html;
}

function _handle_upload_metadata(){

	if ( isset($_FILES['metadata_file']) || isset($_POST['metadata_url'])) {
		if(!empty($_FILES['metadata_file']['tmp_name'])) {
			$file = @file_get_contents( $_FILES['metadata_file']['tmp_name']);
		} else {
			if(!mo_saml_is_extension_installed('curl')){
				update_option( 'mo_saml_message', 'PHP cURL extension is not installed or disabled. Cannot fetch metadata from URL.' );
				$this->mo_saml_show_error_message();
				return;
			}
				//$file = @file_get_contents( $_POST['metadata_file']);
			$url = filter_var( htmlspecialchars($_POST['metadata_url']), FILTER_SANITIZE_URL );
			// $url=$_POST['metadata_url'];
			$response = SAMLSPUtilities::mo_saml_wp_remote_call($url, array('sslverify'=>false), true);

			if(!$response)
				return;
			else
                $file = $response;


			if(isset($_POST['sync_metadata']))
			{
				update_option('saml_metadata_url_for_sync',htmlspecialchars($_POST['metadata_url']));
				update_option('saml_metadata_sync_interval',htmlspecialchars($_POST['sync_interval']));
				if ( ! wp_next_scheduled( 'metadata_sync_cron_action' ) ) {
					wp_schedule_event( time(), htmlspecialchars($_POST['sync_interval']), 'metadata_sync_cron_action' );
				}
			}else{
				delete_option('saml_metadata_url_for_sync');
				delete_option('saml_metadata_sync_interval');
				wp_unschedule_event( wp_next_scheduled( 'metadata_sync_cron_action' ), 'metadata_sync_cron_action' );
			}
		}
		$this->upload_metadata($file);
	}
}

function upload_metadata($file)
{
	$old_error_handler = set_error_handler(array($this, 'handleXmlError'));
	$document = new DOMDocument();
	$document->loadXML($file);
	restore_error_handler();
	$first_child = $document->firstChild;
	if(!empty($first_child)) {
		$metadata = new IDPMetadataReader($document);
		$identity_providers = $metadata->getIdentityProviders();
		if(empty($identity_providers)) {
			update_option('mo_saml_message', 'Please provide a valid metadata file.');
			$this->mo_saml_show_error_message();
			return;
		}
		foreach($identity_providers as $key => $idp){
			//$saml_identity_name = preg_match("/^[a-zA-Z0-9-\._ ]+/", $idp->getIdpName()) ? $idp->getIdpName() : "IDP";
			$saml_identity_name = get_option('saml_identity_name');
			if(isset($_POST['saml_identity_metadata_provider']))
				$saml_identity_name=htmlspecialchars($_POST['saml_identity_metadata_provider']);
			$saml_login_binding_type = 'HttpRedirect';
			$saml_login_url = '';
			if(array_key_exists('HTTP-Redirect', $idp->getLoginDetails()))
				$saml_login_url = $idp->getLoginURL('HTTP-Redirect');
			else if(array_key_exists('HTTP-POST', $idp->getLoginDetails())) {
				$saml_login_binding_type = 'HttpPost';
				$saml_login_url = $idp->getLoginURL('HTTP-POST');
			}
			$saml_logout_binding_type = 'HttpRedirect';
			$saml_logout_url = '';

			if(array_key_exists('HTTP-Redirect', $idp->getLogoutDetails()))
				$saml_logout_url = $idp->getLogoutURL('HTTP-Redirect');
			else if(array_key_exists('HTTP-POST', $idp->getLogoutDetails())){
				$saml_logout_binding_type = 'HttpPost';
				$saml_logout_url = $idp->getLogoutURL('HTTP-POST');
			}
			$saml_issuer = $idp->getEntityID();
			$saml_x509_certificate = $idp->getSigningCertificate();
			update_option('saml_identity_name', $saml_identity_name);
			update_option('saml_login_binding_type', $saml_login_binding_type);
			update_option('saml_login_url', $saml_login_url);
			update_option('saml_logout_binding_type', $saml_logout_binding_type);
			update_option('saml_logout_url', $saml_logout_url);
			update_option('saml_issuer', $saml_issuer);
			update_option('saml_nameid_format', "1.1:nameid-format:unspecified");
				//certs already sanitized in Metadata Reader
			update_option('saml_x509_certificate', maybe_serialize($saml_x509_certificate));
			break;
		}
		update_option('mo_saml_message', 'Identity Provider details saved successfully.');
		$this->mo_saml_show_success_message();
	} else {
		if(!empty($_FILES['metadata_file']['tmp_name'])){
			update_option('mo_saml_message', 'Please provide a valid metadata file.');
			$this->mo_saml_show_error_message();
		} else if(!empty($_POST['metadata_url'])){
			update_option('mo_saml_message','Please provide a valid metadata URL.');
			$this->mo_saml_show_error_message();
		} else {
			update_option('mo_saml_message', 'Please provide a valid metadata file or a valid URL.');
			$this->mo_saml_show_error_message();
			return;
		}
	}
}


	function handleXmlError($errno, $errstr, $errfile, $errline) {
		if ($errno==E_WARNING && (substr_count($errstr,"DOMDocument::loadXML()")>0)) {
			return;
		} else {
			return false;
		}
	}

	function mo_saml_plugin_action_links( $links ) {
		$links = array_merge( array(
			'<a href="' . esc_url( admin_url( 'admin.php?page=mo_saml_settings' ) ) . '">' . __( 'Settings', 'textdomain' ) . '</a>'
		), $links );
		return $links;
	}

	function checkPasswordPattern($password){
		$pattern = '/^[(\w)*(\!\@\#\$\%\^\&\*\.\-\_)*]+$/';
		return !preg_match($pattern,$password);
	}
}

new saml_mo_login;

