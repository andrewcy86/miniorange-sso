<?php
require_once dirname( __FILE__ ) . '/includes/lib/mo-options-enum.php';
require_once dirname( __FILE__ ) . '/Import-export.php';

define( "Uninstall_Class_Names", serialize( array(
	"SSO_Login"         => 'mo_options_enum_sso_login',
	"Identity_Provider" => 'mo_options_enum_identity_provider',
	"Service_Provider"  => 'mo_options_enum_service_provider',
	"Attribute_Mapping" => 'mo_options_enum_attribute_mapping',
	"Domain_Restriction" => 'mo_options_enum_domain_restriction',
	"Role_Mapping"      => 'mo_options_enum_role_mapping',
	"Test_Configuration" => 'mo_options_enum_test_configuration',
	"Custom_Certificate" => 'mo_options_enum_custom_certificate',
	"Custom_Message"	=> 'mo_options_enum_custom_messages',
	"Plugin_Admin"		=> 'mo_options_plugin_admin'
) ) );

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
	exit();

if(get_option('mo_saml_keep_settings_on_deletion')!=='true') {
	if (! is_multisite ()) {
		mo_saml_delete_plugin_configuration();
        mo_saml_delete_user_meta();
	} else {
		global $wpdb;
		$blog_ids = $wpdb->get_col ( "SELECT blog_id FROM $wpdb->blogs" );
		$original_blog_id = get_current_blog_id ();
		
		foreach ( $blog_ids as $blog_id ) {
			switch_to_blog ( $blog_id );
			mo_saml_delete_plugin_configuration();
            mo_saml_delete_user_meta();
		}
		switch_to_blog ( $original_blog_id );
		
	}
}

function mo_saml_delete_plugin_configuration(){
	$tab_class_name = unserialize(Uninstall_Class_Names);
	$configuration_array = array();
	foreach ($tab_class_name as $key => $value) {
		$configuration_array[$key] = mo_get_configuration_array($value, true);
	}
	foreach($configuration_array as $config_name => $config_object){
		foreach($config_object as $const_name => $option_name){
			delete_option($option_name);
		}		
	}

}
function mo_saml_delete_user_meta(){
    $users = get_users( array() );
    foreach ( $users as $user ) {
        delete_user_meta($user->ID, 'mo_saml_user_attributes');
        delete_user_meta($user->ID, 'mo_saml_session_index');
        delete_user_meta($user->ID, 'mo_saml_name_id');
    }
}
?>