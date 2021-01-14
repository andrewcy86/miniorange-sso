<?php
	function mo_saml_apps_config_saml() {
		$sync_interval = get_option( 'saml_metadata_sync_interval' );
		$sync_url      = get_option( 'saml_metadata_url_for_sync' );
		$sync_selected = ! empty( $sync_url ) ? 'checked' : '';
		$hidden        = empty( $sync_url ) ? 'hidden' : '';
		if ( isset( $_GET['action'] ) && $_GET['action'] == 'upload_metadata' ) {
			echo '<div border="0" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;">
		<table style="width:100%;">
		<form name="saml_form" method="post" action="' . admin_url() . 'admin.php?page=mo_saml_settings&tab=save' . '" enctype="multipart/form-data">

			<tr>
				<td colspan="3">
					<h3>Upload IDP Metadata
						<span style="float:right;margin-right:25px;">
							'; ?><a href="<?php echo admin_url(); ?>admin.php?page=mo_saml_settings&tab=save"><input
                        type="button" class="button button-primary button-large" value="Cancel"/></a><?php echo '
						</span>
					</h3>
				</td>
			</tr><tr><td colspan="4"><hr></td></tr>';

			echo '
		<tr>
				<td width="30%"><strong>Identity Provider Name<span style="color:red;">*</span>:</strong></td>
				<td><input type="text" name="saml_identity_metadata_provider" placeholder="Identity Provider name like ADFS, SimpleSAML" style="width: 95%;" value="" required pattern="\S+" title="Only alphabets, numbers and underscore is allowed. No white space is allowed."/></td>
				</tr>

				<tr>';
				wp_nonce_field('saml_upload_metadata');
				echo '<input type="hidden" name="option" value="saml_upload_metadata" />
				<input type="hidden" name="action" value="upload_metadata" />
					<td>Upload Metadata:</td>
					<td colspan="2"><input type="file" name="metadata_file" />
					<input type="submit" class="button button-primary button-large" value="Upload"/></td>

			</tr>';
			echo '<tr>
				<td colspan="2"><p style="font-size:13pt;text-align:center;"><b>OR</b></p></td>
			</tr>';
			echo '

			<tr>
				<input type="hidden" name="option" value="saml_upload_metadata" />
				<input type="hidden" name="action" value="fetch_metadata" />
				<td width="20%">Enter metadata URL:</td>
				<td><input type="url" name="metadata_url" placeholder="Enter metadata URL of your IdP." style="width:100%" value="' . $sync_url . '"/></td>
				<td width="20%">&nbsp;&nbsp;<input type="submit" class="button button-primary button-large" value="Fetch Metadata"/></td>
			</tr>
			<tr>
				<td colspan="2"><br/>
				<label class="switch">
				<input type="checkbox" ' . $sync_selected . ' id="sync_metadata" name="sync_metadata">
				<span class="slider round"></span>
				</label>
				<span style="padding-left:5px;"><b>Update IdP settings by pinging metadata URL ? ( We will store the metadata URL )</b></span> </td>
			</tr>
			<tr>
				<td colspan="2">
					<div id="select_time_sync_metadata" ' . $hidden . '  class="mo_saml_help_desc">
						<span>Select how often you want to ping the IdP : </span>
						<select name="sync_interval">
							<option value="hourly" ' . ( $sync_interval == 'hourly' ? 'selected' : '' ) . ' >hourly</option>
							<option value="twicedaily" ' . ( $sync_interval == 'twicedaily' ? 'selected' : '' ) . ' >twicedaily</option>
							<option value="daily" ' . ( $sync_interval == 'daily' ? 'selected' : '' ) . ' >daily</option>
							<option value="weekly"' . ( $sync_interval == 'weekly' ? 'selected' : '' ) . ' >weekly</option>
							<option value="monthly" ' . ( $sync_interval == 'monthly' ? 'selected' : '' ) . ' >monthly</option>
						</select>
					</div>
				</td>
			</tr>
			</form>';
			echo '</table><br /></div>';

		} else {
			global $wpdb;
			$entity_id = get_option( 'entity_id' );
			if ( ! $entity_id ) {
				$entity_id = 'https://login.xecurify.com/moas';
			}
			$sso_url = get_option( 'sso_url' );
			$cert_fp = get_option( 'cert_fp' );

			//Broker Service
			$saml_identity_name       = get_option( 'saml_identity_name' );
			$saml_login_binding_type  = get_option( 'saml_login_binding_type' );
			$saml_login_url           = get_option( 'saml_login_url' );
			$saml_logout_binding_type = get_option( 'saml_logout_binding_type' );
			$saml_logout_url          = get_option( 'saml_logout_url' );
			$saml_issuer              = get_option( 'saml_issuer' );
			$saml_x509_certificate    = maybe_unserialize( get_option( 'saml_x509_certificate' ) );
			$saml_x509_certificate    = ! is_array( $saml_x509_certificate ) ? array( 0 => $saml_x509_certificate ) : $saml_x509_certificate;
			$saml_request_signed      = get_option( 'saml_request_signed' ) ? get_option( 'saml_request_signed' ) : 'unchecked';
			$saml_nameid_format       = get_option( 'saml_nameid_format' );
			$saml_identity_provider_guide_name = get_option('saml_identity_provider_guide_name')?get_option('saml_identity_provider_guide_name'):mo_options_plugin_idp::$IDP_GUIDES['ADFS'];
			$saml_is_encoding_enabled = get_option('mo_saml_encoding_enabled')!==false?get_option('mo_saml_encoding_enabled'):'checked';

			$action = '';
			if( $saml_identity_provider_guide_name && $saml_identity_provider_guide_name!='Other')
                $action = "https://plugins.miniorange.com/saml-single-sign-on-sso-wordpress-using-".$saml_identity_provider_guide_name;

            $idp_config = get_option( 'mo_saml_idp_config_complete' );
			echo '<form width="98%" border="0" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;" name="saml_form" method="post" action="">';
			wp_nonce_field('login_widget_saml_save_settings');
		echo '<input type="hidden" name="option" value="login_widget_saml_save_settings" />
		<table style="width:100%;">
			<tr>
				<td colspan="2">
					<h3>Configure Service Provider
						<span style="float:right;margin-right:25px;">';
			?><a href="<?php echo admin_url(); ?>admin.php?page=mo_saml_settings&tab=save&action=upload_metadata"><input
                    type="button" class="button button-primary button-large"
                    value="Upload IDP Metadata" <?php if ( ! mo_saml_is_customer_license_key_verified() ) {
				echo 'disabled';
			} ?> /></a>
			<?php echo '</span>
					</h3>
				</td>
			</tr><tr><td colspan="4"><hr></td></tr>';

			check_plugin_state();

			echo '<tr>
				<td colspan="2">Select your Identity Provider from the list below, and you can find the link to the guide for setting up SAML below.
				Please contact us if you don\'t find your IDP in the list.<br /><br /></td>
			</tr>
			<tr>
			<td>&nbsp;</td>
		</tr>
		<tr >

			<td style="width:200px;">

<strong>Select your Identity Provider :</strong>
			</td>
			<td>
<div style="line-height: 5;background: white;position: relative;" id="select_your_idp">
			<select id="idpguide" onchange="getidpguide()" name="saml_identity_provider_guide_name"
					   style="width: 50%;" value="' . $saml_identity_provider_guide_name . '">';
					foreach(mo_options_plugin_idp::$IDP_GUIDES as $key=>$value) {
						$selected="";
						if($value===$saml_identity_provider_guide_name)
							$selected ="selected";
						echo '<option value="' . $value . '"' . $selected . '>' . $key . '</option>';
					}


				echo '</select>';

//				if($saml_identity_provider_guide_name!='Other'){
					echo '<a style="margin-left: 2%;margin-top:17px" target="_blank" class="button button-primary button-large"';
					if(!$saml_identity_provider_guide_name)
						echo "hidden";
					echo ' id="idplink" href="'. $action . '">Link to guide</a>';
//				}
				echo '</div>
			</td>
		</tr>

		<tr>
			<td>&nbsp;</td>
		</tr>
			<tr>
				<td style="width:200px;"><strong>Identity Provider Name <span style="color:red;">*</span>:</strong></td>
				<td><input type="text" name="saml_identity_name" placeholder="Identity Provider name like ADFS, SimpleSAML, Salesforce" style="width: 95%;" value="' . $saml_identity_name . '" required
				pattern="\S+" title="Only alphabets, numbers and underscore is allowed. No white space is allowed."
				';
			if ( ! mo_saml_is_customer_license_key_verified() ) {
				echo ' disabled ';
			}
			echo 'pattern="^\w*$" title="Only alphabets, numbers and underscore is allowed"/></td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td><strong>IdP Entity ID or Issuer <span style="color:red;">*</span>:</strong></td>
				<td><input type="text" name="saml_issuer" placeholder="Identity Provider Entity ID or Issuer" style="width: 95%;" value="' . $saml_issuer . '" required ';
			if ( ! mo_saml_is_customer_license_key_verified() ) {
				echo 'disabled';
			}
			echo '/></td>
			</tr>
			<tr><td>&nbsp;</td></tr>';

				echo '

			<tr>
				<td><strong>Sign SSO & SLO Requests:</strong></td>
				<td>
				<label class="switch">
				<input type="checkbox" name="saml_request_signed" value="Yes" ' . $saml_request_signed;
				if ( ! mo_saml_is_customer_license_key_verified() ) {
					echo ' disabled';
				}
				echo '/><span class="slider round"></span>
				</label>
				<span style="padding-left:5px;"><b>Check this option to send Signed SSO and SLO requests.</b></span></td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			';

			echo '<tr>
				<td><strong>SAML Login URL <span style="color:red;">*</span>:</strong></td>
				<td>';
				echo '<input type="radio" name="saml_login_binding_type" id="sso-http-redirect" value="HttpRedirect"';
				if ( $saml_login_binding_type == 'HttpRedirect' || empty( $saml_login_binding_type ) ) {
					echo 'checked="checked"';
				}
				echo '/>
				<label for="sso-http-redirect">Use HTTP-Redirect Binding for SSO</label>';

				echo '<input style="margin-left:15px;" type="radio" name="saml_login_binding_type" id="sso-http-post" value="HttpPost"';
				if ( $saml_login_binding_type == 'HttpPost' ) {
					echo 'checked="checked"';
				}
				echo '/>
				<label for="http-post">Use HTTP-POST Binding for SSO</label>

				<br><br>';
			// }
			echo '<input type="url" name="saml_login_url" placeholder="Single Sign On Service URL of your IdP" style="width: 95%;" value="' . $saml_login_url . '" required ';
			if ( ! mo_saml_is_customer_license_key_verified() ) {
				echo 'disabled';
			}
			echo '/></td>
			</tr>';
				echo '<tr><td>&nbsp;</td></tr>
			<tr>
				<td><strong>SAML Logout URL :</strong></td>
				<td><input type="radio" name="saml_logout_binding_type" id="slo-http-redirect" value="HttpRedirect"';
				if ( $saml_logout_binding_type == 'HttpRedirect' || empty( $saml_logout_binding_type ) ) {
					echo 'checked="checked"';
				}
				echo '/>
				<label for="slo-http-redirect">Use HTTP-Redirect Binding for SLO</label>
				<input style="margin-left:15px;" type="radio" name="saml_logout_binding_type" id="slo-http-post" value="HttpPost"';
				if ( $saml_logout_binding_type == 'HttpPost' ) {
					echo 'checked="checked"';
				}
				echo '/>
				<label for="slo-http-post">Use HTTP-POST Binding for SLO</label>
				<br /><br />
				<input type="url" name="saml_logout_url" placeholder="Single Logout Service URL of your IdP" style="width: 95%;" value="' . $saml_logout_url . '"';
				if ( ! mo_saml_is_customer_license_key_verified() ) {
					echo 'disabled';
				}
				echo '/><br></td></tr>';
			// }

			echo '<tr>
              <td><strong>NameID Format <span style="color:red;">*</span>:</strong></td>
              <td><select style="margin-top:4%;width:95%;" name="saml_nameid_format" required ';
			if ( ! mo_saml_is_customer_license_key_verified() ) {
				echo "disabled";
			};
			echo '> 
		<option value="1.1:nameid-format:unspecified">' . mo_options_enum_nameid_formats::UNSPECIFIED . '</option>
		<option value="1.1:nameid-format:emailAddress"' . (strpos($saml_nameid_format,'emailAddress') ? 'selected' : '') . '>' . mo_options_enum_nameid_formats::EMAIL . '</option>
		<option value="2.0:nameid-format:transient"' . (strpos($saml_nameid_format,'transient') ? 'selected' : '') . '>' . mo_options_enum_nameid_formats::TRANSIENT . '</option>
		<option value="2.0:nameid-format:persistent"' . (strpos($saml_nameid_format,'persistent') ? 'selected' : '') . '>' . mo_options_enum_nameid_formats::PERSISTENT . '</option>
		<tr><td><br/></td></td>
        </tr>';

		if(empty($saml_x509_certificate)){
			echo '<tr>
				<td><strong>X.509 Certificate <span style="color:red;">*</span>:</strong></td>
				<td><textarea rows="6" cols="5" name="saml_x509_certificate[0]" placeholder="Copy and Paste the content from the downloaded certificate or copy the content enclosed in X509Certificate tag (has parent tag KeyDescriptor use=signing) in IdP-Metadata XML file" style="width: 95%;"';
				 if(!mo_saml_is_customer_license_key_verified()) { echo 'disabled'; }
				 echo '></textarea></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><b>NOTE:</b> Format of the certificate:<br/><b>-----BEGIN CERTIFICATE-----<br/>XXXXXXXXXXXXXXXXXXXXXXXXXXX<br/>-----END CERTIFICATE-----</b></i><br/>
			</tr>';
		} else {
			foreach ( $saml_x509_certificate as $key => $value ) {
				echo '<tr>
				<td><strong>X.509 Certificate <span style="color:red;">*</span>:</strong></td>
				<td><textarea rows="6" cols="5" name="saml_x509_certificate[' . $key . ']" placeholder="Copy and Paste the content from the downloaded certificate or copy the content enclosed in X509Certificate tag (has parent tag KeyDescriptor use=signing) in IdP-Metadata XML file" style="width: 95%;"';
				if ( ! mo_saml_is_customer_license_key_verified() ) {
					echo 'disabled';
				}

				echo ' >' . $value . '</textarea></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><b>NOTE:</b> Format of the certificate:<br/><b>-----BEGIN CERTIFICATE-----<br/>XXXXXXXXXXXXXXXXXXXXXXXXXXX<br/>-----END CERTIFICATE-----</b></i><br/>
				</tr>';
			}
		}
			echo ' <tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><strong><label for="enable_iconv">Character encoding :</label></strong></td>
			<td>
			<label class="switch">
			<input type="checkbox" name="enable_iconv" id="enable_iconv"' . $saml_is_encoding_enabled . '/>
			<span class="slider round"></span>
			</label>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><b>NOTE: </b>Uses iconv encoding to convert X509 certificate into correct encoding. </td>
		</tr>
		<tr>
			<td>&nbsp;</td>';

			echo'<tr>
				<td>&nbsp;</td>
				<td><br /><input type="submit" name="submit" style="margin-right:3%;width:150px;" value="Save" class="button button-primary button-large"';
				if(!mo_saml_is_customer_license_key_verified()) { echo 'disabled'; }
				echo '/>
				<input type="button" name="test" title="You can only test your Configuration after saving your Service Provider Settings." onclick="showTestWindow();"';
				if(!mo_saml_is_sp_configured() || !get_option('saml_x509_certificate') || !mo_saml_is_customer_license_key_verified()) { echo 'disabled';} echo ' value="Test configuration" class="button button-primary button-large" style="margin-right: 3%;width:150px;"/>
				</td>
			</tr>
			<tr>';
			echo '<tr>
                <td></td>
                <td><br /><input type="button" name="saml_request" id="export-import-config"
                               title="Export Plugin Configuration" ';
                if ( ! mo_saml_is_sp_configured() || ! get_option( 'saml_x509_certificate' ) ) {
                            echo 'disabled';
                } echo ' value="Export Plugin Configuration" class="button button-primary button-large" style="width:320px;position: relative"
                        onclick="jQuery(\'#mo_export\').submit();"/></td>
                </tr>
		</table><br/>
		</form>
		<form method="get" target="_blank" action="" id="getIDPguides"></form>
		<form method="post" action="" name="mo_export" id="mo_export">';
		wp_nonce_field('mo_saml_export');
			echo '<input type="hidden" name="option" value="mo_saml_export" /></form>
		<script>
			function showTestWindow() {
				var myWindow = window.open("' . mo_saml_get_test_url() . '", "TEST SAML IDP", "scrollbars=1 width=800, height=600");
			}
			function showSAMLRequest() {
                var myWindow = window.open("'.mo_saml_get_saml_request_url().'", "VIEW SAML REQUEST", "scrollbars=1 width=800, height=600");
            }

            function showSAMLResponse() {
                var myWindow = window.open("'. mo_saml_get_saml_response_url().'", "VIEW SAML RESPONSE", "scrollbars=1 width=800, height=600");
			}
			function getidpguide(){
                var dropdown = document.getElementById(\'idpguide\');

                var action = \'https://plugins.miniorange.com/saml-single-sign-on-sso-wordpress-using-\'+dropdown.value;
                if(dropdown.value===\'Other\'){
                    action = "";
                    jQuery(\'#idplink\').hide();

                }
                
                if(action!==""){

                jQuery(\'#idplink\').attr("href",action).show();
                jQuery(\'#getIDPguides\').attr(\'action\',action);
                

                }
            }
            getidpguide();
			function redirect_to_attribute_mapping(){
				window.location.href= "'. mo_saml_get_attribute_mapping_url().'";
			}
		</script>';
		}
	}