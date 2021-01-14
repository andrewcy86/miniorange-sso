<?php
require_once dirname( __FILE__ ) . '/includes/lib/mo-options-enum.php';
add_action( 'admin_init', 'miniorange_import_export' );
define( "Tab_Class_Names", serialize( array(
	"SSO_Login"         => 'mo_options_enum_sso_login',
	"Identity_Provider" => 'mo_options_enum_identity_provider',
	"Service_Provider"  => 'mo_options_enum_service_provider',
	"Attribute_Mapping" => 'mo_options_enum_attribute_mapping',
	"Domain_Restriction" => 'mo_options_enum_domain_restriction',
	"Role_Mapping"      => 'mo_options_enum_role_mapping',
	"Test_Configuration" => 'mo_options_enum_test_configuration',
	"Custom_Certificate" => 'mo_options_enum_custom_certificate',
	"Custom_Message"	=> 'mo_options_enum_custom_messages'
) ) );

/**
 *Function to display block of UI for Keeping configuration intact
 */
function miniorange_keep_configuration_saml() {
    $save_check = get_option('saml_save_config_to_file')?get_option('saml_save_config_to_file'):'unchecked';
    $file_location = get_option('saml_config_folder_name')?get_option('saml_config_folder_name'):$_SERVER['DOCUMENT_ROOT'];

	echo '<div class="mo_saml_support_layout" id="mo_saml_keep_configuration_intact">
        <div style="padding-right:10px;padding-bottom:24px">
        <h3>Plugin Configurations</h3><hr><br/>
		<form name="f" method="post" action="" id="settings_intact">';
		wp_nonce_field('mo_saml_keep_settings_on_deletion');
        echo '<input type="hidden" name="option" value="mo_saml_keep_settings_on_deletion"/>
		<label class="switch">
		<input type="checkbox" name="mo_saml_keep_settings_intact" ';
        checked(get_option('mo_saml_keep_settings_on_deletion')=='true');
		echo 'onchange="document.getElementById(\'settings_intact\').submit();"/>
		<span class="slider round"></span>
		</label>
		<span style="padding-left:5px;font-size:16px"><b>Keep Settings Intact</b></span><br/><br/>
		Enabling this would keep your configurations intact even when the plugin is uninstalled.
        </form>
        
        <br /><br />
	';

	echo '
	<form method="post" id="import_config" action="' . admin_url() . 'admin.php?page=mo_saml_settings&tab=save' . '" enctype="multipart/form-data">';
	wp_nonce_field('mo_saml_import');
	echo '<input type="hidden" name="option" value="mo_saml_import" />
	<table>
	<tr><td><span style="font-size:16px"><b>Import Configurations</b></span></td></tr>
	<tr><td><br/></td></tr>
	<tr><td><input type="file" name="configuration_file" id="configuration_file"></td>
	<td><input type="submit" name="submit" style="width: auto" class="button button-primary button-large" value="Import"/></td></tr>
	
	</table>
	<br><br>
	</form>
	


	</div>
</div>
';

	
}


/**
 *Function iterates through the enum to create array of values and converts to JSON and lets user download the file
 */
function miniorange_import_export($test_config_screen=false,$json_in_string=false) {

    if($test_config_screen)
        $_POST['option'] = 'mo_saml_export';

	if ( array_key_exists( "option", $_POST ) ) {
		
        if ($_POST['option'] == 'mo_saml_export') {
			if($test_config_screen && $json_in_string)
				$export_referer = check_admin_referer('mo_saml_contact_us_query_option');
			else
				$export_referer = check_admin_referer('mo_saml_export');
			
			if($export_referer){
				$tab_class_name = unserialize(Tab_Class_Names);
				$configuration_array = array();
				foreach ($tab_class_name as $key => $value) {
					$configuration_array[$key] = mo_get_configuration_array($value);
				}
				$configuration_array["Version_dependencies"] = mo_get_version_informations();
				$version = phpversion();
				if(substr($version,0 ,3) === '5.3'){
					$json_string=(json_encode($configuration_array, JSON_PRETTY_PRINT));
				} else {
					$json_string=(json_encode($configuration_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
				}
				if($json_in_string)
					return $json_string;
				header("Content-Disposition: attachment; filename=miniorange-saml-config.json");
				echo $json_string;
				exit;
			}
		} else if($_POST['option'] == 'mo_saml_import' && check_admin_referer('mo_saml_import')){
			
			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}

			$file_type = $_FILES['configuration_file']['type'];
			$file_ext = substr($file_type, strpos($file_type, "/")+1);
			if($file_ext != "json"){
				$show_message = new saml_mo_login();
				update_option('mo_saml_message','Please upload a valid configuration file in .json format');
				$show_message->mo_saml_show_error_message();
				return;
			}

			if ( ! empty( $_FILES['configuration_file']['tmp_name'] ) ) {
				$file                = @file_get_contents( $_FILES['configuration_file']['tmp_name'] );
				$configuration_array = json_decode( $file, true );
				mo_update_configuration_array( $configuration_array );
			}
		} else if($_POST['option'] == 'mo_saml_keep_settings_on_deletion' && check_admin_referer('mo_saml_keep_settings_on_deletion')){
			if(array_key_exists('mo_saml_keep_settings_intact',$_POST))
				update_option('mo_saml_keep_settings_on_deletion','true');
			else
				update_option('mo_saml_keep_settings_on_deletion','');
				
		}
        
	}

}

function mo_get_configuration_array( $class_name, $option_name = false) {
    $class_object = call_user_func( $class_name . '::getConstants' );
    $mo_array = array();
    foreach ( $class_object as $key => $value ) {
        $mo_option_exists=get_option($value);
        if($option_name){
            $mo_array[$key] = $value;
        } else {
            if ($mo_option_exists) {
                if (@unserialize($mo_option_exists) !== false) {
                    $mo_option_exists = unserialize($mo_option_exists);
                }
                $mo_array[$key] = $mo_option_exists;

            }
        }

    }

    return $mo_array;
}

function mo_update_configuration_array( $configuration_array ) {
	$tab_class_name = unserialize( Tab_Class_Names );
	foreach ( $tab_class_name as $tab_name => $class_name ) {
		if(!array_key_exists($tab_name, $configuration_array)){
			$show_message = new saml_mo_login();
			update_option('mo_saml_message','Please upload a valid configuration file');
			$show_message->mo_saml_show_error_message();
			return;
		}
		foreach ( $configuration_array[ $tab_name ] as $key => $value ) {
			$option_string = constant( "$class_name::$key" );
			if(is_array($value))
				$value = serialize($value);
			update_option( $option_string, $value );
			
		}
		
	}
	$show_message = new saml_mo_login();
	update_option('mo_saml_message','Import Successful!');
	$show_message->mo_saml_show_success_message();

}

function mo_get_version_informations(){
	$array_version = array();
	$array_version["Plugin_version"] = mo_options_plugin_constants::Version;
	$array_version["PHP_version"] = phpversion();
	$array_version["Wordpress_version"] = get_bloginfo('version');
	$array_version["OPEN_SSL"] = mo_saml_is_extension_installed('openssl');
	$array_version["CURL"] = mo_saml_is_extension_installed('curl');
	$array_version["ICONV"] = mo_saml_is_extension_installed('dom');
    $array_version["DOM"] = mo_saml_is_extension_installed('dom');

	return $array_version;

}

