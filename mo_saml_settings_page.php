<?php
include 'Import-export.php';
foreach (glob(plugin_dir_path(__FILE__).'views'.DIRECTORY_SEPARATOR.'*.php') as $filename)
{
	include_once $filename;
}

function mo_register_saml_sso() {
    $request_uri            = remove_query_arg( 'action' );
    $_SERVER['REQUEST_URI'] = $request_uri;
    if ( isset( $_GET['tab'] ) ) {
        $active_tab = htmlspecialchars($_GET['tab']);
    } else if ( mo_saml_is_customer_registered_saml() && mo_saml_is_customer_license_key_verified() && mo_saml_is_sp_configured() ) {
        $active_tab = 'general';
    } else if ( mo_saml_is_customer_registered_saml() && mo_saml_is_customer_license_key_verified() ) {
        $active_tab = 'sp_metadata';
    } else {
        $active_tab = 'login';
    }
    if ( ! mo_saml_is_extension_installed('curl') ) {
        echo '
			<p><font color="#FF0000">(Warning: PHP cURL extension is not installed or disabled)</font></p>';
    }

    if ( ! mo_saml_is_extension_installed('openssl') ) {
        echo '
			<p><font color="#FF0000">(Warning: PHP openssl extension is not installed or disabled)</font></p>';
    }
    if ( mo_saml_lk_multi_host() ) {

        echo '<div class="notice notice-error is-dismissible"><p><b>Warning :</b> You are using same license key on multiple sites. Please '; ?>
        <a
        href="<?php echo add_query_arg( array( 'tab' => 'licensing' ), $_SERVER['REQUEST_URI'] ); ?>"><?php echo 'buy more license keys'; ?></a><?php echo ' or your plugin will be disabled soon.</p></div>';
    }


    echo   '<div class="wrap">
            <h1>';

    if($active_tab == 'licensing'){

        echo '<div style="text-align:center;">
                    miniOrange SSO using SAML 2.0</div>
                    <div style="float:left;"><a  class="add-new-h2 add-new-hover" style="font-size: 16px; color: #000;" href="';
        echo add_query_arg( array( 'tab' => 'save' ), htmlentities( $_SERVER['REQUEST_URI'] ) );
        echo '"><span class="dashicons dashicons-arrow-left-alt" style="vertical-align: bottom;"></span> Back To Plugin Configuration</a></div>
                    <!-- span style="float:right;">
                    <a  class="add-new-h2 add-new-hover" style="font-size: 16px; color: #000;" data-toggle="modal" data-target="#standardPremiumModalCenter" ><span class="dashicons dashicons-warning" style="vertical-align: bottom;"></span> Help me choose the right plan</a></span -->
                    <br /><br/><div style="text-align:center; color: rgb(233, 125, 104);">You are currently on the <b>PREMIUM</b> version of the plugin<span style="font-size: 16px; margin-bottom: 0Px;">
                    <li style="color: dimgray; margin-top: 0px;list-style-type: none;">
                    
                    </li></span></div>';
    }else{
        update_option('mo_license_plan_from_feedback', '');
        update_option('mo_saml_license_message', '');


        echo 'miniOrange SSO using SAML 2.0&nbsp
                <a id="license_upgrade" class="add-new-h2 add-new-hover" style="background-color: orange !important; border-color: orange; font-size: 16px; color: #000;" href="';
        echo add_query_arg( array( 'tab' => 'licensing' ), htmlentities( $_SERVER['REQUEST_URI'] ) );
        echo '">Licensing Plans</a>
                <a class="add-new-h2" href="https://faq.miniorange.com/kb/saml-single-sign-on/" target="_blank">FAQs</a>
                <a class="add-new-h2" href="https://forum.miniorange.com/" target="_blank">Ask questions on our forum</a>';
    }

    echo  '</h1>

        </div>';

    echo '
<div id="mo_saml_settings">
	<div class="miniorange_container">
	';

    if($active_tab != 'licensing') {



        if ( mo_check_certificate_expiry() ) {
            $certificate =  get_option('mo_saml_current_cert');
            $parsed_certificate =  openssl_x509_parse($certificate);
            $validTo_time = $parsed_certificate['validTo_time_t'];
            $difference  = $validTo_time - time();
            $remaining_days = round($difference / (60 * 60 * 24));
            if($remaining_days >= 0){
                echo '<div class="notice notice-warning is-dismissible"><p><b>Security Alert :</b> Your certificate is expiring soon, please upgrade your certificate. '; ?>
                <a href="<?php echo add_query_arg( array( 'tab' => 'custom_certificate' ), $_SERVER['REQUEST_URI'] ); ?>"><?php echo 'Upgrade your certificate'; ?></a><?php echo ' or your SSO may stop in '.$remaining_days.' days.</p></div>';
            } else {
                echo '<div class="notice notice-error is-dismissible"><p><b>Security Alert :</b> Your certificate has expired, please upgrade your certificate immediately. '; ?>
                <a href="<?php echo add_query_arg( array( 'tab' => 'custom_certificate' ), $_SERVER['REQUEST_URI'] ); ?>"><?php echo 'Upgrade your certificate'; ?></a><?php echo ' or your SSO will stop.</p></div>';
            }
        }
        echo '<table style="width:100%;">
		<tr>
			<h2 class="nav-tab-wrapper">';

        if ( ! mo_saml_is_customer_registered_saml() || ! mo_saml_is_customer_license_key_verified() || mo_saml_is_trial_active() ) {
            ?> <a class="nav-tab <?php echo( $active_tab == 'login' ? 'nav-tab-active' : '' ); ?>"
                  href="<?php echo add_query_arg( array( 'tab' => 'login' ), $_SERVER['REQUEST_URI'] ); ?>">Account
                Setup</a> <?php
        }
        ?>

        <a id="sp_metadata_tab" class="nav-tab <?php echo( $active_tab == 'sp_metadata' ? 'nav-tab-active' : '' ); ?>"
           href="<?php echo add_query_arg( array( 'tab' => 'sp_metadata' ), $_SERVER['REQUEST_URI'] ); ?>">Service Provider Metadata</a>

        <a class="nav-tab <?php echo( $active_tab == 'save' ? 'nav-tab-active' : '' ); ?>"
           href="<?php echo add_query_arg( array( 'tab' => 'save' ), $_SERVER['REQUEST_URI'] ); ?>">Service Provider Setup</a>

        <a class="nav-tab <?php echo( $active_tab == 'attribute_role' ? 'nav-tab-active' : '' ); ?>"
           href="<?php echo add_query_arg( array( 'tab' => 'attribute_role' ), $_SERVER['REQUEST_URI'] ); ?>">Attribute/Role
            Mapping</a>

        <?php
        if ( mo_saml_is_customer_registered_saml() && mo_saml_is_customer_license_key_verified() ) {
            ?> <a class="nav-tab <?php echo( $active_tab == 'general' ? 'nav-tab-active' : '' ); ?>"
                  href="<?php echo add_query_arg( array( 'tab' => 'general' ), $_SERVER['REQUEST_URI'] ); ?>">Redirection & SSO Links</a> <?php
        }
        ?>

        <a class="nav-tab <?php echo( $active_tab == 'custom_certificate' ? 'nav-tab-active' : '' ); ?>"
           href="<?php echo add_query_arg( array( 'tab' => 'custom_certificate' ), $_SERVER['REQUEST_URI'] ); ?>">Manage Certificates</a>

        <a class="nav-tab <?php echo( $active_tab == 'custom_messages' ? 'nav-tab-active' : '' ); ?>"
           href="<?php echo add_query_arg( array( 'tab' => 'custom_messages' ), $_SERVER['REQUEST_URI'] ); ?>">Custom Messages</a>
        <?php
        if(mo_saml_is_customer_registered_saml()  &&  mo_saml_is_customer_license_key_verified()) {
            echo '<a class="nav-tab '.($active_tab == 'account_info' ? 'nav-tab-active' : '') .'" href="'.add_query_arg( array('tab' => 'account_info'), $_SERVER['REQUEST_URI'] ).'">Account Info</a>';
        }
        echo '</h2>';
        if($active_tab !== 'attribute_role'){
            echo '<td style="vertical-align:top;width:65%;" >';
        } else {
            echo '<td style="vertical-align:top;width:60%;">';
        }

        if ( $active_tab == 'save' ) {
            mo_saml_apps_config_saml();
        } else if ( $active_tab == 'attribute_role' ) {
            mo_saml_save_optional_config();
        }  else if ( $active_tab == 'help' ) {
            mo_saml_show_faqs();
        } else if ( $active_tab == 'sp_metadata' || ($active_tab=='login' && mo_saml_is_customer_license_key_verified())) {
            if($active_tab=='login' && mo_saml_is_customer_license_key_verified()){
                echo '<script>
			var d = document.getElementById("sp_metadata_tab");
			d.className += " nav-tab-active";
			</script>';
            }
            mo_saml_configuration_steps();
        } else if ( $active_tab == 'general' ) {
            mo_saml_general_login_page();
        }else if ( $active_tab == 'licensing' ) {
            mo_saml_show_pricing_page();
            echo '<style>#support-form{ display:none;}</style>';


        } else if($active_tab == 'custom_certificate'){
            mo_saml_add_custom_certificate();
        } else if($active_tab == 'custom_messages'){
            mo_saml_add_custom_messages();
        }else if($active_tab == 'account_info' && mo_saml_is_customer_registered_saml() && mo_saml_is_customer_license_key_verified()){
            mo_saml_display_account_information();
        } else {
            if ( get_option( 'mo_saml_verify_customer' ) == 'true' ) {
                mo_saml_show_verify_password_page_saml();
            } else if ( trim( get_option( 'mo_saml_admin_email' ) ) != '' && trim( get_option( 'mo_saml_admin_api_key' ) ) == '' && get_option( 'mo_saml_new_registration' ) != 'true' ) {
                mo_saml_show_verify_password_page_saml();
            } else if ( get_option( 'mo_saml_registration_status' ) == 'MO_OTP_DELIVERED_SUCCESS_EMAIL' || get_option( 'mo_saml_registration_status' ) == 'MO_OTP_DELIVERED_SUCCESS_PHONE' || get_option( 'mo_saml_registration_status' ) == 'MO_OTP_VALIDATION_FAILURE_EMAIL' || get_option( 'mo_saml_registration_status' ) == 'MO_OTP_VALIDATION_FAILURE_PHONE' || get_option( 'mo_saml_registration_status' ) == 'MO_OTP_DELIVERED_FAILURE' ) {
                mo_saml_show_otp_verification();
            } else if ( ! mo_saml_is_customer_registered_saml() ) {
                mo_saml_show_verify_password_page_saml();
            } else if ( mo_saml_is_customer_registered_saml() && ( ! mo_saml_is_customer_license_key_verified() || mo_saml_is_trial_active() ) ) {

                mo_saml_show_verify_license_page();
            } else {
                mo_saml_general_login_page();
            }
        }

        echo '</td>
			<td style="vertical-align:top;padding-left:1%;" id="support-form">';
        if($active_tab !== 'attribute_role' || !get_option('mo_saml_test_config_attrs')){
            miniorange_support_saml();
            if(mo_saml_is_customer_registered_saml() && mo_saml_is_customer_license_key_verified())
                miniorange_keep_configuration_saml();
        } else {
            mo_saml_display_attrs_list();
        }
        echo '</td>
		</tr>
	</table>
	</div>';
    }
    else if ( $active_tab == 'licensing' ){
        //mo_saml_show_pricing_page();
        mo_saml_show_pricing_page();

    }


    echo '<form name="f" method="post" action="" id="mo_saml_check_license">';
    wp_nonce_field("mo_saml_check_license");
    echo '<input type="hidden" name="option" value="mo_saml_check_license"/>
		</form>
		<form style="display:none;" id="loginform" action="' . mo_options_plugin_constants::HOSTNAME . '/moas/login"
		target="_blank" method="post">
		<input type="email" name="username" value="' . get_option( 'mo_saml_admin_email' ) . '" />
		<input type="text" name="redirectUrl" value="' . mo_options_plugin_constants::HOSTNAME. '/moas/viewlicensekeys" />
		<input type="text" name="requestOrigin" value="wp_saml_sso_basic_plan"  />
		</form>
		<script>
			jQuery("a[href=\"#activatelicense\"]").click(function(){
				jQuery("#mo_saml_check_license").submit();
			});
		</script>';

}

function mo_saml_is_extension_installed($extension_name) {
	if  (in_array  ($extension_name, get_loaded_extensions())) {
		return 1;
	} else
		return 0;
}


function Multisite_enabled() {
	if ( is_multisite() ) {
		return "<b><font color='green'> enabled </font></b>";
	}

	return "<b><font color='red'> disabled </font></b>";
}


function mo_saml_show_new_registration_page_saml() {
update_option( 'mo_saml_new_registration', 'true' );

echo '<form name="f" method="post" action="">
			<input type="hidden" name="option" value="mo_saml_register_customer" />';
			wp_nonce_field('mo_saml_register_customer');
			echo '<div class="mo_saml_table_layout" style="padding-bottom:72px;">
					<h3>Register with miniOrange</h3>
					<p>'; ?><a href="#" id="help_register_link">[ Why should I register? ]<?php echo '</a></p>
					<div hidden id="help_register_desc" class="mo_saml_help_desc">
						You should register so that in case you need help, we can help you with step by step instructions. We support all known IdPs - <b>ADFS, Okta, Salesforce, Shibboleth, SimpleSAMLphp, OpenAM, Centrify, Ping, RSA, IBM, Oracle, OneLogin, Bitium, JBoss Keycloak etc</b>.
					</div>
					</p>
					<table class="mo_saml_settings_table">
						<tr>
							<td><b><font color="#FF0000">*</font>Email:</b></td>
							<td><input class="mo_saml_table_textbox" type="email" name="email" style="width:80%;"
								required placeholder="person@example.com"
								value="' . get_option( 'mo_saml_admin_email' ) . '" /></td>
						</tr>

						<tr>
							<td><b>Phone number:</b></td>
							<td><input class="mo_saml_table_textbox" type="tel" id="phone_contact" style="width:80%;"
								pattern="[\+]\d{11,14}|[\+]\d{1,4}([\s]{0,1})(\d{0}|\d{9,10})" class="mo_saml_table_textbox" name="phone"
								title="Phone with country code eg. +1xxxxxxxxxx"
								placeholder="Phone with country code eg. +1xxxxxxxxxx"
								value="' . get_option( 'mo_saml_admin_phone' ) . '" /></td>
						</tr>
							<tr>
								<td></td>
								<td>We will call only if you need support.</td>
							</tr>
						<tr>
							<td><b><font color="#FF0000">*</font>Password:</b></td>
							<td><input class="mo_saml_table_textbox" required type="password" style="width:80%;"
								name="password" placeholder="Choose your password (Min. length 6)"
								minlength="6" pattern="^[(\w)*(!@#.$%^&*-_)*]+$"
								title="Minimum 6 characters should be present. Maximum 15 characters should be present. Only following symbols (!@#.$%^&*-_) should be present"
								/></td>
						</tr>
						<tr>
							<td><b><font color="#FF0000">*</font>Confirm Password:</b></td>
							<td><input class="mo_saml_table_textbox" required type="password" style="width:80%;"
								name="confirmPassword" placeholder="Confirm your password"
								minlength="6" pattern="^[(\w)*(!@#.$%^&*-_)*]+$"
								title="Minimum 6 characters should be present. Maximum 15 characters should be present. Only following symbols (!@#.$%^&*-_) should be present"
								/></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input type="submit" name="submit" value="Register"
								class="button button-primary button-large" /></td>
						</tr>
					</table>
			</div>
		</form>';

	}


	function sanitize_element() {
		$code     = file_get_contents( plugins_url( 'resources/en_li.mo', __FILE__ ) );
		$tokenkey = get_option( 'mo_saml_customer_token' );
		$code     = AESEncryption::decrypt_data( $code, $tokenkey );
		if ( empty( $code ) ) {
			return "";
		}

		$apikey = get_option( 'mo_saml_admin_api_key' );
		$code   = AESEncryption::decrypt_data( $code, $apikey );

		$xml = simplexml_load_string( $code );

		return strval( $xml->expirationTime );
	}

	function mo_saml_show_verify_password_page_saml() {

		echo '<form name="f" method="post" action="">
			<input type="hidden" name="option" value="mo_saml_verify_customer" /> ';
			wp_nonce_field('mo_saml_verify_customer');
			echo '<div class="mo_saml_table_layout">
				<div id="toggle1" class="panel_toggle">
					<h3>Login with miniOrange</h3>
				</div>
				<div id="panel1">
					<!--<p><b>It seems you already have an account with miniOrange. Please enter your miniOrange email and password.<br/>-->
					<b>'; ?><a href="https://login.xecurify.com/moas/idp/resetpassword" target="_blank">Click here if
            you forgot your password?</a><?php echo '</b></p>
					<br/>
					<table class="mo_saml_settings_table">
						<tr>
							<td><b><font color="#FF0000">*</font>Email:</b></td>
							<td><input class="mo_saml_table_textbox" type="email" name="email" style="width:80%;"
								required placeholder="person@example.com"
								value="' . get_option( 'mo_saml_admin_email' ) . '" size="60%"/></td>
						</tr>
						<tr>
						<td><b><font color="#FF0000">*</font>Password:</b></td>
						<td><input class="mo_saml_table_textbox" required type="password" style="width:80%;"
							name="password" placeholder="Enter your password" size="60%"
							minlength="6" pattern="^[(\w)*(!@#.$%^&*-_)*]+$"
							title="Minimum 6 characters should be present. Maximum 15 characters should be present. Only following symbols (!@#.$%^&*-_) should be present"
							/></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>
							<input type="submit" name="submit" value="Login"
								class="button button-primary button-large" />

						</tr>
					</table>
				</div>
			</div>
		</form>';
	}

	function mo_saml_show_verify_license_page() {

		echo '<div class="mo_saml_table_layout" style="padding-bottom:50px;!important">';

		if ( signElement() ) {

			if ( mo_saml_is_trial_active() ) {
				echo '<br><h3>You are on Trial Version</h3>
						<span>You are using 5 days trial version of miniOrange saml plugin. It will expire on <span style="color:red;font-weight:bold">';
				echo mo_saml_trial_expiry();
				echo ' </span>.</span>
						<br><br>';
				if ( ! site_check() ) {
					?> <a href="<?php echo add_query_arg( array( 'tab' => 'licensing' ), $_SERVER['REQUEST_URI'] ); ?>"><input
                                type="button" name="submit" id="" value="Click here to Upgrade"
                                class="button button-primary button-large"/></a> &nbsp;&nbsp; <a
                            href="#checklicense"><input type="button" name="submit" id=""
                                                        value="Already upgraded? Check License"
                                                        class="button button-primary button-large"/></a> <?php echo '<br><br>';
				}
			} else if ( ! site_check() ) {
				echo '<table style="width:100%"><tr><td><h3>Confirm your license<hr></h3>
						If you have taken 5 days free trial of premium plugin just click the below button to confirm your plan.<br><br>
						<input type="button" name="submit" id="mo_saml_free_trial_link" value="Activate 5 Days Free Trial" class="button button-primary button-large" /></td>
						</tr>
						<tr><td style="text-align:center">OR<br><br></td></tr>
						<tr>
						<td>If you have upgraded to <b>Do It Yourself or Premium Plan</b>, click the below button to confirm your license plan.<br><br>'; ?>
                <a href="#checklicense"><input type="button" name="submit" id="" value="Confirm Your License Plan"
                                               class="button button-primary button-large"/></a> <?php echo '
						</td></tr></table><br><br>';

			}
		}

		if ( ! signElement() || site_check() ) {
			echo '<h3>Verify License  [ <span style="font-size:13px;font-style:normal;"><a style="cursor:pointer;" onclick="getlicensekeysform()" >Click here to view your license key</a></span> ]</h3><hr>';


			echo '<form name="f" method="post" action="">
						<input type="hidden" name="option" value="mo_saml_verify_license" />';
						wp_nonce_field('mo_saml_verify_license');

						echo '<p><b><font color="#FF0000">*</font>Enter your license key to activate the plugin:</b>
							<input class="mo_saml_table_textbox" required type="text" style="margin-left:40px;width:300px;"
								name="saml_licence_key" placeholder="Enter your license key to activate the plugin" ';
			echo '/>
							</p>
							<p><b><font color="#FF0000">*</font>Please check this to confirm that you have read it: </b>&nbsp;&nbsp;<input required type="checkbox" name="license_conditions" ';
			echo '/></p>
							</p>

							<ol>
							<li>License key you have entered here is associated with this site instance. In future, if you are re-installing the plugin or your site for any reason. You should deactivate and then delete the plugin from wordpress console and should not manually delete the plugin folder. So that you can resuse the same license key.</li><br>
							<li><b>This is not a developer\'s license.</b> Making any kind of change to the plugin\'s code will delete all your configuration and make the plugin unusable.</li>
							<br>
								<input type="submit" name="submit" value="Activate License" class="button button-primary button-large" ';

			echo '/>
			<input type="button" class="button button-primary button-large" value="Back" onclick="document.forms[\'mo_saml_back_license\'].submit();"/>
					</form>';

		}
		echo '</div>

		<form name="f" method="post" action="" id="mo_saml_free_trial_form">';
		wp_nonce_field('mo_saml_free_trial');
		echo '<input type="hidden" name="option" value="mo_saml_free_trial"/>
		</form>
		<form name="f" method="post" action="" id="mo_saml_check_license">';
		wp_nonce_field('mo_saml_check_license');
		echo '<input type="hidden" name="option" value="mo_saml_check_license"/>
		</form>
		<form name="f" method="post" action="" id="mo_saml_back_license">';
		wp_nonce_field('mo_saml_remove_account');
		echo '<input type="hidden" name="option" value="mo_saml_remove_account"/>
		</form>
		<script>
			jQuery("#mo_saml_free_trial_link").click(function(){
				jQuery("#mo_saml_free_trial_form").submit();
			});
			jQuery("a[href=\"#checklicense\"]").click(function(){
				jQuery("#mo_saml_check_license").submit();
			});
		</script>';
	}


	function mo_saml_show_otp_verification() {
		echo '<form name="f" method="post" id="otp_form" action="">';
		wp_nonce_field('mo_saml_validate_otp');
		echo '<input type="hidden" name="option" value="mo_saml_validate_otp" />
			<div class="mo_saml_table_layout">
				<table class="mo_saml_settings_table" style="padding-bottom:72px;">
					<h3>Verify Your Email</h3>
					<tr>
						<td><b><font color="#FF0000">*</font>Enter OTP:</b></td>
						<td colspan="2"><input class="mo_saml_table_textbox" autofocus="true" type="text" name="otp_token" required placeholder="Enter OTP" style="width:61%;" pattern="{6,8}"/>
						 &nbsp;&nbsp;<a style="cursor:pointer;" onclick="document.getElementById(\'resend_otp_form\').submit();">Resend OTP</a></td>
					</tr>
					<tr><td colspan="3"></td></tr>
					<tr>

						<td>&nbsp;</td>
						<td style="width:17%">
						<input type="submit" name="submit" value="Validate OTP" class="button button-primary button-large" /></td>

		</form>
		<form name="f" method="post">
						<td style="width:18%">';
						wp_nonce_field('mo_saml_go_back');
						echo '<input type="hidden" name="option" value="mo_saml_go_back"/>
							<input type="submit" name="submit"  value="Back" class="button button-primary button-large" />
						</td>
		</form>
		<form name="f" id="resend_otp_form" method="post" action="">
						<td>';
		if ( get_option( 'mo_saml_registration_status' ) == 'MO_OTP_DELIVERED_SUCCESS_EMAIL' || get_option( 'mo_saml_registration_status' ) == 'MO_OTP_VALIDATION_FAILURE_EMAIL' ) {
			wp_nonce_field('mo_saml_resend_otp_email');
			echo '<input type="hidden" name="option" value="mo_saml_resend_otp_email"/>';
		} else {
			wp_nonce_field('mo_saml_resend_otp_phone');
			echo '<input type="hidden" name="option" value="mo_saml_resend_otp_phone"/>';
		}
		echo '</td>

		</form>
		</tr>
			</table>';
		if ( get_option( 'mo_saml_registration_status' ) == 'MO_OTP_DELIVERED_SUCCESS_EMAIL' || get_option( 'mo_saml_registration_status' ) == 'MO_OTP_VALIDATION_FAILURE_EMAIL' ) {
			echo '<hr>

				<h3>I did not recieve any email with OTP . What should I do ?</h3>
				<form id="mo_saml_register_with_phone_form" method="post" action="">';
				wp_nonce_field('mo_saml_register_with_phone_option');
				echo '<input type="hidden" name="option" value="mo_saml_register_with_phone_option" />
					 If you cannot see the email from miniOrange in your mails, please check your <b>SPAM</b> folder. If you don\'t see an email even in the SPAM folder, verify your identity with our alternate method.
					 <br><br>
						<b>Enter your valid phone number here and verify your identity using one time passcode sent to your phone.</b><br><br>
						<input class="mo_saml_table_textbox" type="tel" id="phone_contact" style="width:40%;"
								pattern="[\+]\d{11,14}|[\+]\d{1,4}([\s]{0,1})(\d{0}|\d{9,10})" class="mo_saml_table_textbox" name="phone"
								title="Phone with country code eg. +1xxxxxxxxxx" required
								placeholder="Phone with country code eg. +1xxxxxxxxxx"
								value="' . get_option( 'mo_saml_admin_phone' ) . '" />
						<br /><br /><input type="submit" value="Send OTP" class="button button-primary button-large" />

				</form>';
		}
		echo '</div>';

	}



	function postResponse() {
		$code     = file_get_contents( plugins_url( 'resources/en_li.mo', __FILE__ ) );
		$tokenkey = get_option( 'mo_saml_customer_token' );
		$code     = AESEncryption::decrypt_data( $code, $tokenkey );
		if ( empty( $code ) ) {
			return "";
		}

		$apikey = get_option( 'mo_saml_admin_api_key' );
		$code   = AESEncryption::decrypt_data( $code, $apikey );

		$xml = simplexml_load_string( $code );

		return strval( $xml->code );
	}

	function decryptSamlElement() {

		$code = file_get_contents( plugins_url( 'resources/en_li.mo', __FILE__ ) );

		$tokenkey = get_option( 'mo_saml_customer_token' );
		$code     = AESEncryption::decrypt_data( $code, $tokenkey );
		if ( empty( $code ) ) {
			return true;
		}

		$apikey = get_option( 'mo_saml_admin_api_key' );
		$code   = AESEncryption::decrypt_data( $code, $apikey );

		$xml       = simplexml_load_string( $code );
		$signature = $xml->signature;
		$publickey = $xml->token;

		$publickey = AESEncryption::decrypt_data( $publickey, $tokenkey );
		$parts     = str_split( $publickey, $split_length = 64 );
		$publickey = "-----BEGIN PUBLIC KEY-----\n";
		foreach ( $parts as $part ) {
			$publickey .= $part . "\n";
		}
		$publickey .= "-----END PUBLIC KEY-----";

		$pubkeyid = openssl_get_publickey( $publickey );
		$data     = $xml->expirationTime;

		$signature = base64_decode( $signature );
		$ok        = openssl_verify( $data, $signature, $pubkeyid, OPENSSL_ALGO_SHA1 );
		if ( $ok == 1 ) {
			$expiry      = $xml->expirationTime;
			$expirytime  = strtotime( $expiry );
			$currenttime = time();
			if ( $currenttime > $expirytime ) {
				return true;
			} else {
				$customer = new Customersaml();
				$diff     = $expirytime - $currenttime;
				$diffdays = $diff / ( 60 * 60 * 24 );
				$diffdays = intval( $diffdays );
				if ( $diffdays < 1 && ! get_option( "mo_saml_alert_sent_for_one" ) ) {
					$customer->mo_saml_send_alert_email( 1 );
					update_option( "mo_saml_alert_sent_for_one", true );
				} else if ( $diffdays < 2 && ! get_option( "mo_saml_alert_sent_for_two" ) ) {
					$customer->mo_saml_send_alert_email( 2 );
					update_option( "mo_saml_alert_sent_for_two", true );
				}

				return false;
			}
		}

		return true;
	}



	function mo_saml_get_attribute_mapping_url(){

		return add_query_arg( array('tab' => 'attribute_role'), $_SERVER['REQUEST_URI'] );
	}

    function mo_check_certificate_expiry(){
        $applied_certificate =  openssl_x509_parse(get_option('mo_saml_current_cert'))['validTo_time_t'];
        $difference  = $applied_certificate - time();
        $remaining_days = round($difference / (60 * 60 * 24));
        return $remaining_days<60;

    }

    function mo_saml_roll_back_available(){
        return get_option('mo_saml_certificate_roll_back_available');
    }
	function mo_saml_lk_multi_host() {
		$vl_check_s = get_option( 'vl_check_s' );
		$key        = get_option( 'mo_saml_customer_token' );
		if ( $vl_check_s ) {
			$vl_check_s = AESEncryption::decrypt_data( $vl_check_s, $key );
			if ( $vl_check_s == "false" ) {
				return true;
			}
		}

		return false;
	}
	function mo_saml_get_saml_request_url() {

        $url = home_url() . '/?option=getsamlrequest';

    return $url;
}

function mo_saml_get_saml_response_url() {
        $url = home_url() . '/?option=getsamlresponse';

    return $url;
}

	function check_plugin_state() {
		echo '<tr>';
		if ( ! mo_saml_is_customer_registered_saml() ) {
			echo '<td colspan="2"><div style="display:block;margin-top:10px;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">Please '; ?>
            <a href="<?php echo add_query_arg( array( 'tab' => 'login' ), $_SERVER['REQUEST_URI'] ); ?>">Register or
                Login with miniOrange</a><?php echo ' to configure the miniOrange SAML Plugin.</div></td>';
		} else if ( ! mo_saml_is_customer_license_key_verified() ) {
			echo '<td colspan="2"><div style="display:block;margin-top:10px;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">';
			if ( signElement() && ! site_check() ) {
				?><a href="<?php echo add_query_arg( array( 'tab' => 'login' ), $_SERVER['REQUEST_URI'] ); ?>">Click
                    here</a><?php echo ' to activate your 5 days FREE Trial for the plugin.';
			} else {
				echo 'Please enter your'; ?><a
                href="<?php echo add_query_arg( array( 'tab' => 'login' ), $_SERVER['REQUEST_URI'] ); ?>"> license
                    key</a> <?php echo ' to activate the plugin.';
			}
			echo '</div></td>';
		} else if ( mo_saml_is_trial_active() ) {
			echo '<td colspan="2"><div style="display:block;margin-top:10px;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">You are on Trial version of the plugin and it will expire on <span style="color:#0073aa">';
			echo mo_saml_trial_expiry();
			echo '. </span>';
			if ( site_check() ) {
				?><a href="<?php echo add_query_arg( array( 'tab' => 'login' ), $_SERVER['REQUEST_URI'] ) ?>">Click
                    here</a><?php echo ' to activate your license.';
			} else {
				echo 'If you have already purchased the license'; ?><a href="#activatelicense">click here to
                    activate</a><?php
			}
			echo '</div></td>';
		}
		echo '</tr>';

	}


	function signElement() {
		if ( file_exists( plugin_dir_path( __FILE__ ) . 'resources/en_li.mo' ) ) {
			return true;
		}

		return false;
	}


	function site_check() {
		$status = false;
		$key    = get_option( 'mo_saml_customer_token' );
		if ( get_option( "site_ck_l" ) ) {
			if ( AESEncryption::decrypt_data( get_option( 'site_ck_l' ), $key ) == "true" ) {
				$status = true;
			}
		}
		if ( $status && ! mo_saml_lk_multi_host() ) {
			$vl_check_t = get_option( 'vl_check_t' );
			if ( $vl_check_t ) {
				$vl_check_t = intval( $vl_check_t );
				if ( time() - $vl_check_t < 3600 * 24 * 3 ) {
					return $status;
				}
			}
			$code = get_option( 'sml_lk' );
			if ( $code ) {
				$code     = AESEncryption::decrypt_data( $code, $key );
				$customer = new Customersaml();
				$content  = $customer->mo_saml_vl( $code, true );
				if(!$content)
					return;
				$content = json_decode( $content, true );
				if ( strcasecmp( $content['status'], 'SUCCESS' ) == 0 ) {
					delete_option( 'vl_check_s' );
				} else {
					update_option( 'vl_check_s', AESEncryption::encrypt_data( "false", $key ) );
				}
			}
			update_option( 'vl_check_t', time() );
		}

		return $status;
	}



	add_action( 'mo_saml_flush_cache', 'mo_saml_flush_cache', 10, 3 );
	function mo_saml_flush_cache() {
		if ( mo_saml_is_customer_registered_saml() && get_option( 'sml_lk' ) ) {
			$customer = new Customersaml();
			$customer->mo_saml_update_status();
		}
	}


	function mo_saml_get_test_url($new_cert = false) {
	    $url = home_url() . '/?option=testidpconfig';
        $new_cert_url = $new_cert?$url.'&newcert=true':$url;
		return $new_cert_url;
	}

	function mo_saml_is_customer_registered_saml() {
		$email       = get_option( 'mo_saml_admin_email' );
		$customerKey = get_option( 'mo_saml_admin_customer_key' );
		if ( ! $email || ! $customerKey || ! is_numeric( trim( $customerKey ) ) ) {
			return 0;
		} else {
			return 1;
		}
	}

	function mo_saml_is_trial_active() {
		$email         = get_option( 'mo_saml_admin_email' );
		$customerKey   = get_option( 'mo_saml_admin_customer_key' );
		$key           = get_option( 'mo_saml_customer_token' );
		$isTrialActive = AESEncryption::decrypt_data( get_option( 't_site_status' ), $key );
		if ( $isTrialActive != "true" || ! $email || ! $customerKey || ! is_numeric( trim( $customerKey ) ) ) {
			return 0;
		} else {
			return 1;
		}
	}

	function mo_saml_trial_expiry() {
		$date = DateTime::createFromFormat( 'Y-m-d H:i:s.u', sanitize_element() );

		return $date->format( 'F j, Y, G:i:s' );
	}

	function mo_saml_is_customer_license_key_verified($html_element=false) {

		$key           = get_option( 'mo_saml_customer_token' );
		$isTrialActive = AESEncryption::decrypt_data( get_option( 't_site_status' ), $key );
		$licenseKey    = get_option( 'sml_lk' );
		$email         = get_option( 'mo_saml_admin_email' );
		$customerKey   = get_option( 'mo_saml_admin_customer_key' );
		if ( ( $isTrialActive != "true" && ! $licenseKey ) || ! $email || ! $customerKey || ! is_numeric( trim( $customerKey ) ) ) {
			return $html_element?'disabled':0;
		}return $html_element?'':1;
	}

	function mo_saml_is_sp_configured($html_element=false) {
		$saml_login_url        = get_option( 'saml_login_url' );
		$saml_x509_certificate = get_option( 'saml_x509_certificate' );
		if ( ! empty( $saml_login_url ) && ! empty( $saml_x509_certificate ) ) {
			return $html_element?'':1;
		}
		return $html_element?'disabled title="Disabled. Configure your Service Provider"':0;
	}

	function mo_saml_download_logs($error_msg,$cause_msg) {

		echo '<div style="font-family:Calibri;padding:0 3%;">';
		echo '<hr class="header"/>';
		echo '          <p style="font-size: larger       ">Please try the solution given above.If the problem persists,download the plugin configuration by clicking on Export Plugin Configuration and mail us at <a href="mailto:info@xecurify.com">info@xecurify.com</a>.</p>
						<p>We will get back to you soon!<p>
						</div>
						<div style="margin:3%;display:block;text-align:center;">
						<div style="margin:3%;display:block;text-align:center;">
						<form method="get" action="" name="mo_export" id="mo_export">';
						wp_nonce_field('mo_saml_export');
					echo '<input type="hidden" name="option" value="export_configuration" />
					<input type="submit" class="miniorange-button" value="Export Plugin Configuration">
					<input class="miniorange-button" type="button" value="Close" onclick="self.close()"></form>
				   ';
		echo '&nbsp;&nbsp;';

		$samlResponse = htmlspecialchars($_POST['SAMLResponse']);
		update_option('mo_saml_response',$samlResponse);
		$error_array  = array("Error"=>$error_msg,"Cause"=>$cause_msg);
		update_option('mo_saml_test',$error_array);
		?>
		<style>
		.miniorange-button {
		padding:1%;
		background: #0091CD none repeat scroll 0% 0%;
		cursor: pointer;font-size:15px;
		border-width: 1px;border-style: solid;
		border-radius: 3px;white-space: nowrap;
		box-sizing: border-box;border-color: #0073AA;
		box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;
		margin: 22px;
		}
	</style>
		<?php

		exit();


	}

	function mo_saml_display_attrs_list(){
		$idp_attrs = get_option('mo_saml_test_config_attrs');
		if(@unserialize($idp_attrs))
			$idp_attrs = unserialize($idp_attrs);

		if(!empty($idp_attrs)){
			echo '<div class="mo_saml_support_layout" style="padding-bottom:20px; padding-right:5px;">
			<h3>Attributes received from the Identity Provider:</h3>
					<div>
						<table style="border-collapse:collapse;border-spacing:0;table-layout: fixed; width: 95%;background-color:#ffffff;">
						<tr style="text-align:center;"><td style="font-weight:bold;border:1px solid #949090;padding:2%; width:65%;">ATTRIBUTE NAME</td><td style="font-weight:bold;padding:2%;border:1px solid #949090; word-wrap:break-word; width:35%;">ATTRIBUTE VALUE</td></tr>';

								foreach($idp_attrs as $attr_name => $values){
									echo '<tr style="text-align:center;"><td style="font-weight:bold;border:1px solid #949090;padding:2%; word-wrap:break-word;">' . $attr_name . '</td>';
									echo '<td style="padding:2%;border:1px solid #949090; word-wrap:break-word;">' .implode("<hr/>",$values) . '</td>
									</tr>';
								}
								echo '
							</table>
							<br/>
							<input type="button" class="button-primary"';
							if(!mo_saml_is_customer_license_key_verified()) { echo ' disabled '; }
							echo 'value="Clear Attributes List" onclick="document.forms[\'attrs_list_form\'].submit();">
							<p><b>NOTE :</b> Please clear this list after configuring the plugin to hide your confidential attributes.<br/>
							Click on <b>Test configuration</b> in <b>Service Provider Setup</b> tab to populate the list again.</p>
							<form method="post" action="" id="attrs_list_form">';
							wp_nonce_field('clear_attrs_list');
							echo '<input type="hidden" name="option" value="clear_attrs_list">
							</form>
							</div>
			</div>';
		}
	}

    function mo_saml_display_account_information(){

        echo '<div style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;">
	<h3>Your Profile';

        if(get_option("sml_lk")) {
            echo ' <span style="float:right;margin-right:15px;">
		&nbsp;&nbsp;<input type="button" name="ok_btn" class="button button-primary button-large" value="Get License Keys" onclick="getlicensekeysform()" /></span></h3>';
        }
        echo '<p>Thanks for upgrading to <b>Premium</b> plugin.</p>
	<table border="1" style="background-color:#FFFFFF; border:1px solid #CCCCCC; border-collapse: collapse; padding:0px 0px 0px 10px; margin:2px; width:85%">
		<tr>
			<td style="width:45%; padding: 10px;">miniOrange Account Email</td>
			<td style="width:55%; padding: 10px;">'. get_option('mo_saml_admin_email'). '</td>
		</tr>
		<tr>
			<td style="width:45%; padding: 10px;">Customer ID</td>
			<td style="width:55%; padding: 10px;">'. get_option('mo_saml_admin_customer_key'). '</td>
		</tr>

	</table>
	<div style="display:block;text-align:center;margin:2%; margin-left: -10%">
	<input type="button" name="mo_saml_remove_account" id="mo_saml_remove_account" value="Remove Account" class="button button-primary button-large" />
	</div>
</div>';
        if(get_option('mo_saml_license_expiry_date')){
            echo '<div style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;"><h3>License Details</h3>
	<table border="1" style="background-color:#FFFFFF; border:1px solid #CCCCCC; border-collapse: collapse; padding:0px 0px 0px 10px; margin:2px; width:85%">
		<tr>
			<td style="width:45%; padding: 10px;">License Expiry Date</td>
			<td style="width:55%; padding: 10px;">'. get_option('mo_saml_license_expiry_date'). '</td>
		</tr>
		<tr>
			<td style="width:45%; padding: 10px;">License Renewal Link </td>
			<td style="width:55%; padding: 10px;"><a href="'. mo_options_plugin_constants::HOSTNAME . '/moas/login?redirectUrl=' . mo_options_plugin_constants::HOSTNAME . '/moas/admin/customer/licenserenewals?renewalrequest=' . mo_options_plugin_constants::LICENSE_TYPE . '" class="button button-primary" target="_blank">Renew your support license</a>.</td>
		</tr>

	</table><br /><br /></div>';
        }
        echo '
<form name="f" method="post" action="" id="mo_saml_remove_account_form">';
        wp_nonce_field('mo_saml_remove_account');
        echo '<input type="hidden" name="option" value="mo_saml_remove_account"/>
</form>
<script>
jQuery("#mo_saml_remove_account").click(function(){
	jQuery("#mo_saml_remove_account_form").submit();
});
</script>';
    }


	function miniorange_generate_metadata($download = false, $new_cert = false) {

		$sp_base_url = get_option( 'mo_saml_sp_base_url' );
		if ( empty( $sp_base_url ) ) {
			$sp_base_url = home_url();
		}
		if ( substr( $sp_base_url, - 1 ) == '/' ) {
			$sp_base_url = substr( $sp_base_url, 0, - 1 );
		}
		$sp_entity_id = get_option( 'mo_saml_sp_entity_id' );
		if ( empty( $sp_entity_id ) ) {
			$sp_entity_id = $sp_base_url . '/wp-content/plugins/miniorange-saml-20-single-sign-on/';
		}
        $saml_request_signed      = get_option( 'saml_request_signed' ) ? get_option( 'saml_request_signed' ) : 'unchecked';

		$entity_id   = $sp_entity_id;
		$acs_url     = $sp_base_url . '/';
		if($new_cert) {
            $certificate = file_get_contents(plugin_dir_path(__FILE__) . 'resources' . DIRECTORY_SEPARATOR . 'miniorange_sp_2020.crt');
            $certificate = SAMLSPUtilities::desanitize_certificate( $certificate );
        } else {
            $certificate = get_option( 'mo_saml_current_cert' );
            $certificate = SAMLSPUtilities::desanitize_certificate( $certificate );
        }

        if(ob_get_contents())
            ob_clean();
		header( 'Content-Type: text/xml' );
		if($download){
		    if($new_cert)
                header('Content-Disposition: attachment; filename="mo-saml-sp-new-metadata.xml"');
		    else
                header('Content-Disposition: attachment; filename="mo-saml-sp-metadata.xml"');
        }

		echo '<?xml version="1.0"?>
<md:EntityDescriptor xmlns:md="urn:oasis:names:tc:SAML:2.0:metadata" validUntil="2022-10-28T23:59:59Z" cacheDuration="PT1446808792S" entityID="' . $entity_id . '">
  <md:SPSSODescriptor AuthnRequestsSigned="';
        if($saml_request_signed == 'checked')
            echo 'true';
        else
            echo 'false';

		echo '" WantAssertionsSigned="true" protocolSupportEnumeration="urn:oasis:names:tc:SAML:2.0:protocol">';

		if($saml_request_signed == 'checked') {
            echo '<md:KeyDescriptor use="signing">
      <ds:KeyInfo xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
        <ds:X509Data>
          <ds:X509Certificate>' . $certificate . '</ds:X509Certificate>
        </ds:X509Data>
      </ds:KeyInfo>
    </md:KeyDescriptor>';
        }

    echo '<md:KeyDescriptor use="encryption">
      <ds:KeyInfo xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
        <ds:X509Data>
          <ds:X509Certificate>' . $certificate . '</ds:X509Certificate>
        </ds:X509Data>
      </ds:KeyInfo>
    </md:KeyDescriptor>
	<md:SingleLogoutService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST" Location="' . $acs_url . '"/>
    <md:SingleLogoutService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect" Location="' . $acs_url . '"/>
    <md:NameIDFormat>urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified</md:NameIDFormat>
    <md:NameIDFormat>urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress</md:NameIDFormat>
	<md:NameIDFormat>urn:oasis:names:tc:SAML:2.0:nameid-format:persistent</md:NameIDFormat>
	<md:NameIDFormat>urn:oasis:names:tc:SAML:2.0:nameid-format:transient</md:NameIDFormat>
    <md:AssertionConsumerService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST" Location="' . $acs_url . '" index="1"/>
  </md:SPSSODescriptor>
  <md:Organization>
    <md:OrganizationName xml:lang="en-US">miniOrange</md:OrganizationName>
    <md:OrganizationDisplayName xml:lang="en-US">miniOrange</md:OrganizationDisplayName>
    <md:OrganizationURL xml:lang="en-US">http://miniorange.com</md:OrganizationURL>
  </md:Organization>
  <md:ContactPerson contactType="technical">
    <md:GivenName>miniOrange</md:GivenName>
    <md:EmailAddress>info@xecurify.com</md:EmailAddress>
  </md:ContactPerson>
  <md:ContactPerson contactType="support">
    <md:GivenName>miniOrange</md:GivenName>
    <md:EmailAddress>info@xecurify.com</md:EmailAddress>
  </md:ContactPerson>
</md:EntityDescriptor>';
		exit;

	}

	?>
