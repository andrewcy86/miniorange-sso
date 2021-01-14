<?php
	function mo_saml_general_login_page() {
		$sp_base_url = get_option( 'mo_saml_sp_base_url' );
		if ( empty( $sp_base_url ) ) {
			$sp_base_url = home_url();
		}
				
		if ( mo_saml_is_customer_registered_saml() && mo_saml_is_customer_license_key_verified() ) {

            if (!get_option('mosaml_read_license_information')){
                echo '<div class="modal fade" id="mosamllicenseModal" tabindex="-1" role="dialog" aria-labelledby="mosamllicenseModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="mosamllicenseModalLabel">Important! License Information
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button></h2>
      </div>
      <div class="modal-body">
        <p><b>Your license has been verified successfully and linked to your current domain.</b><br /><br/>
        1. If this is your staging site and want to clone your staging site to your production site then please deactivate the plugin from your current site to activate the plugin on production site.
        </p>
       <p> 2. If you want to change the domain of your current site then please deactivate the plugin first. After changing the domain, activate and verify the license key again.
        </p>
        <p>4. If you will clone your site or change the domain without deactivating the plugin then the plugin will not work on the 2nd site or changed domain. In this case, you will need to get your previous domain up or move the plugin back to your previous domain and deactivate the plugin to free up the license key.
      </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="button button-primary" id="dismissModalButton" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>';
        }
update_option('mosaml_read_license_information', true);
			echo '<div style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 2% 0px 2%;">
				<h3>SSO Login Options</h3><hr>';
            echo '[&nbsp;<a href="https://docs.miniorange.com/documentation/saml-handbook/redirection-sso-links-settings" target="_blank">Click here</a> to know how this is useful. ]';

            if ( mo_saml_is_trial_active() ) {
				echo '<div style="display:block;margin-top:10px;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">You are on Trial version of the plugin and it will expire on <span style="color:#0073aa">';
				mo_saml_trial_expiry();
				echo '</span>';
				if ( site_check() ) {
					echo 'Click '; ?> <a
                            href="<?php echo add_query_arg( array( 'tab' => 'login' ), $_SERVER['REQUEST_URI'] ); ?>">here</a> <?php echo ' to activate your license.';
				} else {
					echo 'If you have already purchased license '; ?><a href="#activatelicense">click here to
                        activate</a> <?php echo ' your license.';
				}
				echo '</div>';
			}

			echo '<form id="mo_saml_relay_state_form" method="post" action="">';
			wp_nonce_field('mo_saml_relay_state_option');
					echo '<input type="hidden" name="option" value="mo_saml_relay_state_option"/>
						<br/>
						<table>
							<tr>
								<td width="20%">Relay State URL:</td>
								<td width="80%"><input type="url" name="mo_saml_relay_state" style="width:90%;" ' . mo_saml_is_sp_configured(true) . ' placeholder="Enter a valid URL (Example : ' . $sp_base_url . ')" value="' . get_option( 'mo_saml_relay_state' ) . '"/><br />
								</td>
								
							</tr>
							<tr>
								<td></td>
								<td>Users will always be redirected to this URL after SSO.<br/>
								When left blank, the users will be redirected to the same page from where the SSO was initiated.</td>
								<td></td>
							</tr>
							<tr><td><br/><br/></td></tr>
							<tr>
								<td width="20%">Logout Relay State URL:</td>
								<td width="80%"><input type="url" name="mo_saml_logout_relay_state" style="width:90%;" ' . mo_saml_is_sp_configured(true) . ' placeholder="Enter a valid URL (Example : ' . $sp_base_url . ')" value="' . get_option( 'mo_saml_logout_relay_state' ) . '"/><br />
								</td>
								
							</tr>
							<tr>
								<td></td>
								<td>Users will always be redirected to this URL after logging out.<br/>
								When left blank, the users will be redirected to the same page from where the SLO was initiated.</td>
								<td></td>
							</tr>
							<tr><td colspan="3">
							
							<span style="display:block;text-align: center;padding-top: 20px;"><input type="submit" value="Update" class="button button-primary button-large"/></span>
</td></tr>
							
						</table>
					</form><br/>
					
					<br/>
					
					</div>
					<br/>

				<div style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 2% 0px 2%;position: relative" id="miniorange-auto-redirect">
				<h3>Option 1: Auto-Redirection from site</h3>		
				<div style="margin:2% 2% 2% 17px;">
				<form id="mo_saml_registered_only_access_form" method="post" action="">';
				wp_nonce_field('mo_saml_registered_only_access_option');
				echo '<span>1. Enable this option if you want to restrict your site to only logged in users. The users will be redirected to your IdP if logged in session is not found.</span>
					<br /><br/>
					<input type="hidden" name="option" value="mo_saml_registered_only_access_option"/>
					<label class="switch">
					<input type="checkbox" name="mo_saml_registered_only_access" value="true" ' . mo_saml_is_sp_configured(true);
					checked(get_option('mo_saml_registered_only_access') == "true");
	
						echo 'onchange="document.getElementById(\'mo_saml_registered_only_access_form\').submit();"/>
						<span class="slider round"></span>
					</label><span style="padding-left:5px"><b>Redirect to IdP if user not logged in &nbsp; [PROTECT COMPLETE SITE]</b></span>
					<br/><br/>
					<div style="background-color:#CBCBCB;padding:2%;">
								<span style="color:#FF0000;"><b>HOW DO I PROTECT SPECIFIC PAGES OF MY SITE?</b></span> <br/>';
								if(is_plugin_active('miniorange-page-restriction/Page-restriction.php')){
									$url = 'href="admin.php?page=page_restriction&tab=login_page_restriction"';
								} else {
									$url = 'target="_blank" href="https://plugins.miniorange.com/wordpress-page-restriction"';
								}
								echo 'You can use our <a ' . $url . '><b>Page Restriction</b></a> add-on to restrict users from accessing specific pages of your site.
								</div>
					<br><div hidden id="registered_only_access_desc" class="mo_saml_help_desc">
							<span>Select this option if you want to restrict your site to only logged in users. Selecting this option will redirect the users to your IdP if logged in session is not found.</span>
							</div>
						</form><br/>

						<form id="mo_saml_force_authentication_form" method="post" action="">';
						wp_nonce_field('mo_saml_force_authentication_option');
						echo '<span>2. It will force user to provide credentials on your IdP on each login attempt even if the user is already logged in to IdP. This option may require some additional setting in your IdP to force it depending on your Identity Provider.</span>
							<br /><br />
							<input type="hidden" name="option" value="mo_saml_force_authentication_option"/>
							
							<label class="switch">
							<input type="checkbox" name="mo_saml_force_authentication" value="true"';
							if(!mo_saml_is_sp_configured()) { echo 'disabled title="Disabled. Configure your Service Provider"'; }  checked(get_option('mo_saml_force_authentication') == "true");
							echo ' onchange="document.getElementById(\'mo_saml_force_authentication_form\').submit();"/>
							<span class="slider round"></span>
						</label> 
						<span style="padding-left:5px"><b>Force authentication with your IdP on each login attempt</b></span>
							<br><div hidden id="force_authentication_with_idp_desc" class="mo_saml_help_desc">
							<span>It will force user to provide credentials on your IdP on each login attempt even if the user is already logged in to IdP. This option may require some additional setting in your IdP to force it depending on your Identity Provider.</span>
						</div>
					</form><br/>
					
					<form id="mo_saml_enable_rss_access_form" method="post" action="">';
					wp_nonce_field('mo_saml_enable_rss_access_option');
					echo '<input type="hidden" name="option" value="mo_saml_enable_rss_access_option"/>
					<label class="switch">
					<input type="checkbox" name="mo_saml_enable_rss_access" value="true"';
					if(!mo_saml_is_sp_configured()) { echo 'disabled title="Disabled. Configure your Service Provider"'; }  checked(get_option('mo_saml_enable_rss_access') == "true");
					echo ' onchange="document.getElementById(\'mo_saml_enable_rss_access_form\').submit();"/>
					<span class="slider round"></span>
						</label><span style="padding-left:5px"><b>Enable access to RSS Feeds</b></span><br><br>
				</form>
				</div>
				</div> <br/>

			<div style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 2% 0px 2%;position: relative" id="miniorange-auto-redirect-login-page">
				<h3>Option 2: Auto-Redirection from WordPress Login</h3>
				<div style="margin:2% 2% 2% 17px;">
            <span>1. Enable this option if you want the users visiting any of the following URLs to get redirected to your configured IdP for authentication:</span>
                <br/><code><b> ' . wp_login_url() . '</b></code> or
                <code><b> ' . admin_url() . '</b></code><br /><br/>
				<form id="mo_saml_enable_redirect_form" method="post" action="">';
				wp_nonce_field('mo_saml_enable_login_redirect_option');
				echo '<input type="hidden" name="option" value="mo_saml_enable_login_redirect_option"/>
				<label class="switch">
				<input type="checkbox" name="mo_saml_enable_login_redirect" value="true"';
				if(!mo_saml_is_sp_configured()) { echo 'disabled title="Disabled. Configure your Service Provider"';}
					checked(get_option('mo_saml_enable_login_redirect') == "true");
					echo 'onchange="document.getElementById(\'mo_saml_enable_redirect_form\').submit();"/>
					<span class="slider round"></span>
						</label><span style="padding-left:5px"><b>Redirect to IdP from WordPress Login Page</b></span>
            <br /><br/>
			</form>
            <span>2. Enable this option to create a backdoor, using which you can login to your website using WordPress credentials, incase you get locked out of your IDP.</span>
			<br/><form id="mo_saml_allow_wp_signin_form" method="post" action="">';
			wp_nonce_field('mo_saml_allow_wp_signin_option');
			echo '<input type="hidden" name="option" value="mo_saml_allow_wp_signin_option"/>
			<p>
			<label class="switch">
			<input type="checkbox" name="mo_saml_allow_wp_signin" value="true"';
			checked(get_option('mo_saml_allow_wp_signin') == "true");

			if(get_option('mo_saml_enable_login_redirect') != 'true') { echo 'disabled'; }


            $mo_saml_backdoor_url = get_option('mo_saml_backdoor_url')?get_option('mo_saml_backdoor_url'):"false";
			
			echo ' onchange="document.getElementById(\'mo_saml_allow_wp_signin_form\').submit();"/>
			<span class="slider round"></span>
						</label><span style="padding-left:5px"><b>Enable backdoor login</b></span><br/>
									<br/><i>
									<table width="100%">
									<tr>
									<td style="display:block;"><b>Backdoor URL:</b><br/>(Please note it down) </td>
									<td><div style="background-color:#ededed;padding:1%;display:block;"><b> ' . site_url() . '/wp-login.php?saml_sso=<input style="width:150px" type="text" id="backdoor_url" name="mo_saml_backdoor_url" ';
									if(get_option('mo_saml_allow_wp_signin') != 'true') { echo 'disabled'; }
									echo ' value="'.$mo_saml_backdoor_url.'"></b><i class="fa fa-fw fa-lg fa-copy mo_copy fa-pull-right copytooltip" onclick="copyBackdoorUrl(this);"><span id="backdoor_url_copy" class="copytooltiptext">Copy to Clipboard</span></i>
									</div></td></tr></table><div style="display:block;text-align:center; margin:2%;"><input type="submit" value="Update" class="button button-primary" '.mo_saml_is_sp_configured(true).'></div></i></span>
								<div style="background-color:#CBCBCB;padding:1%;">
									<span style="color:#FF0000;">WARNING:</span> Checking the above option will <b>open a security hole</b>. Anybody knowing the above URL will be able to login to your website using WordPress Credentials. <b>Please do not share this URL.</b>
								</div>
							</p>
						</form>
					</div>
					</div>
					<br/>
					<script>
					function copyBackdoorUrl(copyButton){
						var temp = jQuery("<input>");
						jQuery("body").append(temp);
						temp.val("' . site_url() . '/wp-login.php?saml_sso=" + jQuery("#backdoor_url").val()).select();
						document.execCommand("copy");
						temp.remove();
						jQuery("#backdoor_url_copy").text("Copied");

						jQuery(copyButton).mouseout(function(){
							jQuery("#backdoor_url_copy").text("Copy to Clipboard");
						});
					}
					</script>
				<div style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 2% 0px 2%;position: relative" id="minorange-use-widget">
					<h3><b>Option 3: Login button</b></h3>
					<div style="margin:2% 0 2% 17px;">';
					mo_saml_add_sso_button_settings();
					echo '</div>
				</div>
				<br/>';

				

			echo '<div style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 2% 0px 2%;position: relative" id="minorange-use-widget">
			<h3><b>Option 4: SSO Links</b></h3>
			<div style="margin:2% 0 2% 17px;">
			<form id="mo_saml_widget_form" method="post" action="">';
			wp_nonce_field('mo_saml_widget_option');
		echo '<input type="hidden" name="option" value="mo_saml_widget_option"/>
			
		<table width="100%">
			<tr>
			<td><b>Login text:</b></td>';
			$idp_name = get_option('saml_identity_name');
			if(empty($idp_name)){
				$idp_name = '##IDP##';
			}
			echo '<td><input type="text" id="mo_saml_custom_login_text"style="width:330px; height:30px; auto;" name="mo_saml_custom_login_text" placeholder ="Login with ' . $idp_name .'" value="'.get_option('mo_saml_custom_login_text').'"></td>
			</tr>

			<tr>
			<td><b>Greeting text:</b></td>
			<td><input type="text" id="mo_saml_custom_greeting_text"style="width:160px; height:30px; auto; vertical-align:middle;" name="mo_saml_custom_greeting_text" placeholder="Hello," value="'.get_option('mo_saml_custom_greeting_text').'">&nbsp
			<select name="mo_saml_greeting_name" id="mo_saml_greeting_name" style="width:160px;height:30px; auto;">
				<option value="USERNAME"';
			if(get_option('mo_saml_greeting_name') == 'USERNAME') { echo 'selected="selected"' ; }
			echo '>Username</option>
							<option value="EMAIL"';
			if(get_option('mo_saml_greeting_name') == 'EMAIL') {echo 'selected="selected"' ; }
			echo '>Email</option>
							<option value="FNAME"';
			if(get_option('mo_saml_greeting_name') == 'FNAME') { echo 'selected="selected"' ; }
			echo '>FirstName</option>
							<option value="LNAME"';
			if(get_option('mo_saml_greeting_name') == 'LNAME') { echo 'selected="selected"' ; }
			echo '>LastName</option>
							<option value="FNAME_LNAME"';
			if(get_option('mo_saml_greeting_name') == 'FNAME_LNAME') {
			echo 'selected="selected"' ;}
			echo '>FirstName LastName</option>
							<option value="LNAME_FNAME"';
			if(get_option('mo_saml_greeting_name') == 'LNAME_FNAME') { echo 'selected="selected"' ; }
			echo '>LastName FirstName</option>
			</select></td>
			</tr>

			<tr>
			<td><b>Logout text:</b></td>
			<td><input type="text" id="mo_saml_custom_logout_text"style="width:330px; height:30px; auto;" name="mo_saml_custom_logout_text" placeholder ="Logout"value="'.get_option('mo_saml_custom_logout_text').'"></td>
			</tr>
			</table>
			<div style="display:block;text-align:center; margin:2%;"><input type="submit" value="Update" class="button button-primary" '.mo_saml_is_sp_configured(true).'/></div></form>
			
			<hr><br/>
			<div>
			The above custom texts will be applied to the <b>SSO links</b>, which you can add on your site using the following ways:
			<br/><br/>
			<div style="padding-left:8px">
			<b>Widget:</b><br/>
			<ol>
			<li>  Go to <span><a href="' . get_admin_url(). 'widgets.php" >Widgets</a></span></li>
			<li>Select "Login with ' . get_option( 'saml_identity_name' ) . '", drag and drop to your
			favourite location and save.</li>
			</ol>
			<br/>
			<b>Shortcode:</b><br/><br/>
			<div id="mo_saml_add_shortcode_steps" >
			<table style="padding-left:8px"><tr><td width="20%">1. For PHP page:</td>
			<td><code>echo do_shortcode(\'[MO_SAML_FORM]\'); </code></td>
			</tr>
			<tr><td><br/></td></tr>
			<tr><td style="display:block;">2. For HTML page:</td>
			<td><code>[MO_SAML_FORM]</code><br/><b style="padding-left:40px">OR</b><br/><code>';

			echo '&lt;a href="'. $sp_base_url.'/?option=saml_user_login"&gt;';
			$login_title = 'Login with '.get_option('saml_identity_name');
			if(get_option('mo_saml_custom_login_text')) {
			$login_title = get_option('mo_saml_custom_login_text');
			}
			$identity_provider = get_option('saml_identity_name');
			$login_title = str_replace("##IDP##", $identity_provider, $login_title);
			echo $login_title . '&lt;/a&gt';

			echo '</code></td></tr>
			
            </table>
			</div>
						</div>
						</div>


			</div>
			<br/><br/>
			</div>
			<br/>
			
			<script>
				
				jQuery("#mosamllicenseModal").modal({
                    backdrop: \'static\',
                    keyboard: false
                });
				
			</script>';
		}
	}