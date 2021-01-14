<?php
include "BasicEnum.php";
 class mo_options_enum_sso_login extends BasicEnum {
	const Relay_state = 'mo_saml_relay_state';
	const Logout_Relay_state = 'mo_saml_logout_relay_state';
	const Redirect_Idp = 'mo_saml_registered_only_access';
	const Force_authentication = 'mo_saml_force_authentication';
	const Enable_access_RSS = 'mo_saml_enable_rss_access';
	const Auto_redirect = 'mo_saml_enable_login_redirect';
	const Allow_wp_signin = 'mo_saml_allow_wp_signin';
	const Backdoor_URL = "mo_saml_backdoor_url";
	const Custom_login_button = 'mo_saml_custom_login_text';
	const Custom_greeting_text = 'mo_saml_custom_greeting_text';
	const Custom_greeting_name = 'mo_saml_greeting_name';
	const Custom_logout_button = 'mo_saml_custom_logout_text';
	const Redirect_to_WP_login = "mo_saml_redirect_to_wp_login";
	const Add_SSO_button = "mo_saml_add_sso_button_wp";
	const Use_Button_as_shortcode = "mo_saml_use_button_as_shortcode";
	const Use_Button_as_widget = "mo_saml_use_button_as_widget";
	const SSO_Button_Size = "mo_saml_button_size";
	const SSO_Button_Width = "mo_saml_button_width";
	const SSO_Button_Height = "mo_saml_button_height";
	const SSO_Button_Curve = "mo_saml_button_curve";
	const SSO_Button_Color = "mo_saml_button_color";
	const SSO_Button_Text = "mo_saml_button_text";
	const SSO_Button_font_color = "mo_saml_font_color";
	const SSO_Button_font_size = "mo_saml_font_size";
	const SSO_Button_position = "sso_button_login_form_position";
	const Keep_Configuration_Intact = "mo_saml_keep_settings_on_deletion";
}

class mo_options_enum_identity_provider extends BasicEnum{
 	const Broker_service ='mo_saml_enable_cloud_broker';
	const SP_Base_Url='mo_saml_sp_base_url';
	const SP_Entity_ID = 'mo_saml_sp_entity_id';
}


class mo_options_enum_service_provider extends BasicEnum{
	const Identity_name ='saml_identity_name';
	const Login_binding_type='saml_login_binding_type';
	const Login_URL = 'saml_login_url';
	const Logout_binding_type = 'saml_logout_binding_type';
	const Logout_URL = 'saml_logout_url';
	const Issuer = 'saml_issuer';
	const X509_certificate = 'saml_x509_certificate';
	const Request_signed = 'saml_request_signed';
	const NameID_Format = 'saml_nameid_format';
	const Guide_name = 'saml_identity_provider_guide_name';
	const Is_encoding_enabled = 'mo_saml_encoding_enabled';
}

class mo_options_enum_test_configuration extends BasicEnum{
	const SAML_REQUEST = 'mo_saml_request';
	const SAML_RESPONSE = 'mo_saml_response';
	const TEST_CONFIG_ERROR_LOG = 'mo_saml_test';
	const TEST_CONFIG_ATTIBUTES = 'mo_saml_test_config_attrs';
}

class mo_options_enum_attribute_mapping extends BasicEnum{
 	const Attribute_Username ='saml_am_username';
 	const Attribute_Email = 'saml_am_email';
 	const Attribute_First_name ='saml_am_first_name';
 	const Attribute_Last_name = 'saml_am_last_name';
 	const Attribute_Group_name ='saml_am_group_name';
 	const Attribute_Custom_mapping = 'mo_saml_custom_attrs_mapping';
	const Attribute_Account_matcher = 'saml_am_account_matcher';
	const Attribute_Display_name = 'saml_am_display_name';
}

class mo_options_enum_domain_restriction extends BasicEnum{
	const Email_Domains = "saml_am_email_domains";
	const Enable_Domain_Restriction_Login = "mo_saml_enable_domain_restriction_login";
	const Allow_deny_user_with_Domain = "mo_saml_allow_deny_user_with_domain";
}

class mo_options_enum_role_mapping extends BasicEnum{
	const Role_do_not_auto_create_users = 'mo_saml_dont_create_user_if_role_not_mapped';
	const Role_do_not_assign_role_unlisted = 'saml_am_dont_allow_unlisted_user_role';
	const Role_do_not_update_existing_user = 'saml_am_dont_update_existing_user_role';
	const Role_default_role ='saml_am_default_user_role';
	const Role_do_not_login_with_roles = "saml_am_dont_allow_user_tologin_create_with_given_groups";
	const Role_restrict_users_with_groups = "mo_saml_restrict_users_with_groups";
	const Role_mapping = "saml_am_role_mapping";
}

class mo_options_enum_custom_certificate extends BasicEnum{
	const Custom_Public_Certificate = 'mo_saml_custom_cert';
 	const Custom_Private_Certificate = 'mo_saml_custom_cert_private_key';
 	const Enable_Public_certificate = 'mo_saml_enable_custom_certificate';
}

class mo_options_enum_custom_messages extends BasicEnum{
	const Custom_Account_Creation_Disabled_message = 'mo_saml_account_creation_disabled_msg';
	const Custom_Restricted_Domain_message = 'mo_saml_restricted_domain_error_msg';
}

class mo_options_error_constants extends BasicEnum{
 	const Error_no_certificate = "Unable to find a certificate .";
	const Cause_no_certificate = "No signature found in SAML Response or Assertion. Please sign at least one of them.";
	const Error_wrong_certificate = "Unable to find a certificate matching the configured fingerprint.";
	const Cause_wrong_certificate = "X.509 Certificate field in plugin does not match the certificate found in SAML Response.";
	const Error_invalid_audience = "Invalid Audience URI.";
	const Cause_invalid_audience = "The value of 'Audience URI' field on Identity Provider's side is incorrect";
	const Error_issuer_not_verfied = "Issuer cannot be verified.";
	const Cause_issuer_not_verfied = "IdP Entity ID configured and the one found in SAML Response do not match";
}

class mo_options_enum_nameid_formats extends BasicEnum{
	const EMAIL = "urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress";
	const UNSPECIFIED = "urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified";
	const TRANSIENT = "urn:oasis:names:tc:SAML:2.0:nameid-format:transient";
	const PERSISTENT = "urn:oasis:names:tc:SAML:2.0:nameid-format:persistent";
}

class mo_options_plugin_constants extends  BasicEnum{
	const CMS_Name = "WP";
	const Application_Name = "WP miniOrange SAML 2.0 SSO Plugin";
	const Application_type = "SAML";
	const Version = "12.0.2";
	const HOSTNAME = "https://login.xecurify.com";
	const LICENSE_TYPE ="WP_SAML_SP_PLUGIN";
	const LICENSE_PLAN_NAME = "wp_saml_sso_basic_plan";
    const PLUGIN_CONFIG_FILENAME = "mo_saml_config.php";
}

class mo_options_plugin_idp extends  BasicEnum{
    public static $IDP_GUIDES = array(
        "ADFS" => "adfs",
        "Okta" => "okta",
        "SalesForce" => "salesforce",
        "Google Apps" => "google-apps",
        "Azure AD" => "azure-ad",
        "OneLogin" => "onelogin",
        "Keycloak" => "jboss-keycloak",
        "MiniOrange" => "miniorange",
        "PingFederate" => "pingfederate",
        "PingOne" => "pingone",
        "Centrify" => "centrify",
        "Oracle" => "oracle-enterprise-manager",
        "Bitium" => "bitium",
        "Shibboleth 2" => "shibboleth2",
        "Shibboleth 3" => "shibboleth3",
        "SimpleSAMLphp" => "simplesaml",
        "OpenAM" => "openam",
        "Authanvil"=>"authanvil",
        "Auth0"=>"auth0",
        "CA Identity"=>"ca-identity",
        "WSO2"=>"wso2",
        "RSA SecureID"=>"rsa-secureid",
        "Other"=>"Other"
    );
}

class mo_options_plugin_admin extends BasicEnum{
	const HOST_NAME = "mo_saml_host_name";
	const New_Registration = "mo_saml_new_registration";
	const Admin_Phone = "mo_saml_admin_phone";
	const Admin_Email = "mo_saml_admin_email";
	const Admin_Pass = "mo_saml_admin_password";
	const Verify_Customer = "mo_saml_verify_customer";
	const Admin_Customer_Key = "mo_saml_admin_customer_key";
	const Admin_Api_Key = "mo_saml_admin_api_key";
	const Customer_Token = "mo_saml_customer_token";
	const Admin_notices_Message = "mo_saml_message";
	const Registration_Status = "mo_saml_registration_status";
	const IDP_Config_ID = "saml_idp_config_id";
	const IDP_Config_Complete = "mo_saml_idp_config_complete";
	const Transaction_ID = "mo_saml_transactionId";
	const SML_LK = "sml_lk";
	const Site_Status = "t_site_status";
	const Site_Check = "site_ck_l";
	const Free_Version = "mo_saml_free_version";
	const Admin_company = "mo_saml_admin_company";
	const Admin_First_Name = "mo_saml_admin_first_name";
	const Admin_Last_Name = "mo_saml_admin_last_name";
	const License_Name = "mo_saml_license_name";
	const User_Limit = "mo_saml_usr_lmt";
}