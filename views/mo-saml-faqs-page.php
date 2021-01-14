<?php function mo_saml_show_faqs() {
		echo '<form>
		<div id="instructions_idp"></div>
		<table width="98%" border="0" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;">';
			check_plugin_state();
		echo '<tr>
			<td colspan="2">
				<br/>
			<p style="font-size:13px;">miniOrange SAML SSO Plugin enables login to WordPress through your Identity Provider. <br/>We support all known IdPs - <b>ADFS, Okta, Salesforce, Shibboleth, SimpleSAMLphp, OpenAM, Centrify, Ping, RSA, IBM, Oracle, OneLogin, Bitium, WSO2, JBoss Keycloak etc</b>. ';?>
			<a href="<?php echo add_query_arg( array('tab' => 'sp_metadata'), $_SERVER['REQUEST_URI'] );?>"> Click here to download the step-by-step guides for IDP's</a>
			<?php echo '</p>
				<h3>Frequently Asked Questions</h3>
				<table class="mo_saml_help">
				    <tr>
						<td class="mo_saml_help_cell">
							<div id="help_steps_title" class="mo_saml_title_panel">
								<div class="mo_saml_help_title">Reference Id: MO24281021705</div>
							</div>
							<div hidden id="help_steps_desc" class="mo_saml_help_desc">
							    <b>Possible Causes of this error:</b>b>
								<ul>
									<li>You are using the same license key of the plugin on multiple domains.</li>
									<li>You might have changed the domain name of your WordPress instance without deactivating and deleting the plugin from previous domain.</li>
									<li>You might have clonned your Database and staging site to production site without deactivating and deleting the plugin from staging site.</li>
								</ul>
								<b>How to rectify this issue</b>
								<p style="color:red">If you have only 1 license key:</p>
								<ul>
								    <li>
								        Please deactivate and delete the plugin from your previous domain where you have used this license key. Now deactivate the plugin from current domain. Enter your license key to activate the plugin.
                                    </li>
                                    <li>
                                        If you do not have previous domain then you should enable that domain immediately for the moment to free up the license from that domain and then use the same license on the current domain.
                                    </li>
                                </ul>
                                <p style="color:red">If you have more than 1 license key</p>
                                <ul>
                                    <li>
								        Please deactivate and activate the plugin from current domain. Enter the new license key.
                                    </li>
                                    <li>
                                        If you still want to free up the license key from the previous domain and you do not have previous domain then you should enable that domain immediately for the moment to free up the license from that domain and then use the same license on any new domain.
                                    </li>
                                </ul>
								For any further queries, please contact us.								
							</div>
						</td>
					</tr>
					<tr>
						<td class="mo_saml_help_cell">
							<div id="help_steps_title" class="mo_saml_title_panel">
								<div class="mo_saml_help_title">Instructions to use miniOrange SAML plugin</div>
							</div>
							<div hidden id="help_steps_desc" class="mo_saml_help_desc">
								<ul>
									<li>Step 1:&nbsp;&nbsp;&nbsp;&nbsp;Configure your Identity Provider by following '; ?>
									<a href="<?php echo add_query_arg( array('tab' => 'sp_metadata'), $_SERVER['REQUEST_URI'] );?>">these steps</a>
									<?php echo '.</li>
									<li>Step 2:&nbsp;&nbsp;&nbsp;&nbsp;Download X.509 certificate from your Identity Provider.</li>
									<li>Step 3:&nbsp;&nbsp;&nbsp;&nbsp;Enter appropriate values in the fields in ';?>
									<a href="<?php echo add_query_arg( array('tab' => 'save'), $_SERVER['REQUEST_URI'] );?>">Configure Service Provider</a>
									<?php echo '.</li>
									<li>Step 4:&nbsp;&nbsp;&nbsp;&nbsp;After saving your configuration, you will be able to test your configuration using the <b>Test &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Configuration</b> button on the top of the page.</li>
									<li>Step 5:&nbsp;&nbsp;&nbsp;&nbsp;Add "Login to &lt;IdP&gt;" widget to your WordPress page.</li>
								</ul>
								For any further queries, please contact us.								
							</div>
						</td>
					</tr>
					<tr>
						<td class="mo_saml_help_cell">
							<div id="help_widget_steps_title" class="mo_saml_title_panel">
								<div class="mo_saml_help_title">Add login Link to post/page/blog</div>
							</div>
							<div hidden id="help_widget_steps_desc" class="mo_saml_help_desc">
								<ol>
									<li>Go to Appearances > Widgets.</li>
									<li>Select "Login with &lt;Identity Provider&gt;". Drag and drop to your favourite location and save.</li>
								</ol>								
							</div>
						</td>
					</tr>
					<tr>
						<td class="mo_saml_help_cell">
							<div id="help_faq_idp_config_title" class="mo_saml_title_panel">
								<div class="mo_saml_help_title">I logged in to my Identity Provider and it redirected to WordPress, but I\'m not logged in. There was an error - "We could not sign you in.".</div>
							</div>
							<div hidden id="help_faq_idp_config_desc" class="mo_saml_help_desc">
								To know what actually went wrong,
								<ol>
									<li>Login to you Wordpress administrator account. And go miniOrange SAML SSO plugin\'s Configure Service Provider tab.</li>
									<li>Click on <b>Test Configuration</b>. A popup window will open (make sure you popup enabled in your browser).</li>
									<li>Click on <b>Login</b> button. You will be redirected to your IdP for authentication.</li>
									<li>On successful authentication, You will be redirect back with the actual error message.</li>
									<li>Here are the some frequent errors:
									<ul><br />
										<li><b>INVALID_ISSUER</b>: This means that you have NOT entered the correct Issuer or Entity ID value provided by your Identity Provider. You\'ll see in the error message what was the expected value (that you have configured) and what actually found in the SAML Response.</li>
										<li><b>INVALID_AUDIENCE</b>: This means that you have NOT configured Audience URL in your Identity Provider correctly. It must be set to <b>https://login.xecurify.com/moas/rest/saml/acs</b> in your Identity Provider.</li>
										<li><b>INVALID_DESTINATION</b>: This means that you have NOT configured Destination URL in your Identity Provider correctly. It must be set to <b>https://login.xecurify.com/moas/rest/saml/acs</b> in your Identity Provider.</li>
										<li><b>INVALID_SIGNATURE</b>: This means that the certificate you provided did NOT match the certificate found in the SAML Response. Make sure you provide the same certificate that you downloaded from your IdP. If you have your IdP\'s Metadata XML file then make sure you provide certificate enclosed in X509Certificate tag which has an attribute <b>use="signing"</b>.</li>
										<li><b>INVALID_CERTIFICATE</b>: This means that the certificate you provided is NOT in proper format. Make sure you have copied the entire certificate provided by your IdP. If coiped from IdP\'s Metadata XML file, make sure that you copied the entire value.</li>
									</ul>
								</ol>
								If you need help resolving the issue, please contact us using the support form and we will get back to you shortly.
							</div>
						</td>
					</tr>
					<tr>
						<td class="mo_saml_help_cell">
							<div id="help_faq_idp_redirect_title" class="mo_saml_title_panel">
								<div class="mo_saml_help_title">I clicked on login link but I cannot see the login page of my Identity Provider.</div>
							</div>
							<div hidden id="help_faq_idp_redirect_desc" class="mo_saml_help_desc">
								This could mean that the <b>SAML Login URL</b> you have entered is not correct. Please enter the correct <b>SAML Login URL</b> (with HTTP-Redirect binding) provided by your Identity Provider. <br/><br/>If the problem persists, please contact us using the support form. It would be helpful if you could share your Identity Provider details with us.
							</div>
						</td>
					</tr>
					<tr>
						<td class="mo_saml_help_cell">
							<div id="help_faq_404_title" class="mo_saml_title_panel">
								<div class="mo_saml_help_title">I\'m getting a 404 error page when I try to login.</div>
							</div>
							<div hidden id="help_faq_404_desc" class="mo_saml_help_desc">
								This could mean that you have not entered the correct <b>SAML Login URL</b>. Please enter the correct <b>SAML Login URL</b> (with HTTP-Redirect binding) provided by your Identity Provider and try again.<br/><br/>If the problem persists, please contact us using the support form. It would be helpful if you could share your Identity Provider details with us.
							</div>
						</td>
					</tr>
					
					<tr>
						<td class="mo_saml_help_cell">
							<div id="help_curl_enable_title" class="mo_saml_title_panel">
								<div class="mo_saml_help_title">How to enable PHP cURL extension?</div>
							</div>
							<div hidden id="help_curl_enable_desc" class="mo_saml_help_desc">
								<ol>
									<li>Open php.ini file located under php installation folder.</li>
									<li>Search for extension=php_curl.dll.</li>
									<li>Uncomment it by removing the semi-colon(;) in front of it.</li>
									<li>Restart the Apache Server.</li>
								</ol>
								For any further queries, please contact us.								
							</div>
						</td>
					</tr>
					<tr>
						<td class="mo_saml_help_cell">
							<div id="help_openssl_enable_title" class="mo_saml_title_panel">
								<div class="mo_saml_help_title">How to enable PHP openssl extension?</div>
							</div>
							<div hidden id="help_openssl_enable_desc" class="mo_saml_help_desc">
								<ol>
									<li>Open php.ini file located under php installation folder.</li>
									<li>Search for extension=php_openssl.dll.</li>
									<li>Uncomment it by removing the semi-colon(;) in front of it.</li>
									<li>Restart the Apache Server.</li>
								</ol>
								For any further queries, please contact us.								
							</div>
						</td>
					</tr>
					<tr>
						<td class="mo_saml_help_cell">
							<div id="help_saml_title" class="mo_saml_title_panel">
								<div class="mo_saml_help_title">What is SAML?</div>
							</div>
							<div hidden id="help_saml_desc" class="mo_saml_help_desc">
								Security Assertion Markup Language(SAML) is an XML-based, open-standard data format for exchanging authentication and authorization data between parties, in particular, between an Identity Provider and a Service Provider. In our case, miniOrange is the Service Provider and the application which manages credentials is the Identity provider.
								<br/><br/>
								The SAML specification defines three roles: the Principal (in this case, your Wordpress user), the Identity provider (IdP), and the Service Provider (SP). The Service Provider requests and obtains an identity assertion from the Identity Provider. On the basis of this assertion, the service provider can make an access control decision - in other words it can decide whether to allow user to login to WordPress.
								<br/><br/>
								For more details please refer to this ';?>
								<a href="https://en.wikipedia.org/wiki/Security_Assertion_Markup_Language" target="_blank">SAML document</a>
								<?php echo '.
							</div>
						</td>
					</tr>
					<tr>
						<td class="mo_saml_help_cell">
							<div id="help_saml_flow_title" class="mo_saml_title_panel">
								<div class="mo_saml_help_title">SP-Initiated Login vs. IdP-Initiated Login</div>
							</div>
							<div hidden id="help_saml_flow_desc" class="mo_saml_help_desc">
								The user\'s identity(user profile and credentials) is managed by an Identity Provider(IdP) and the user wants to login to your WordPress site.
								<br/><br/>
								<b>SP-Initiated Login</b>
								<br/>
								<ol>
									<li>The request to login is initiated through the WordPress site.</li>
									<li>Using the miniOrange SAML Plugin, the user is redirected to IdP login page.</li>
									<li>The user authenticates with the IdP.</li>
									<li>With the help of response from IdP, miniOrange SAML Plugin logs in the user to WordPress site.</li>
								</ol>
								<b>IdP-Initiated Login</b>
								<br/>
								<ol>
									<li>The user initiates login through IdP.</li>
									<li>With the help of response from IdP, miniOrange SAML Plugin logs in the user to WordPress site.</li>
								</ol>
							</div>
						</td>
					</tr>
				</table>
				<br/>
				
				<br/><br/>
			</td>
		</tr>
		</table>
		</form>
	</div>';
}