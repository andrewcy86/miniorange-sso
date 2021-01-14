<?php function mo_saml_add_custom_messages()
{
	echo '<form width="98%" border="0" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;" name="saml_form" method="post" action="">';
	wp_nonce_field('add_custom_messages');
		echo '<input type="hidden" name="option" value="add_custom_messages" />

		<table style="width:100%;">';
		check_plugin_state();
		echo '<tr>
				<td colspan="2">
					<h3>Customize messages shown to users</h3>
					[&nbsp;<a href="https://docs.miniorange.com/documentation/saml-handbook/custom-message" target="_blank">Click here</a> to know how this is useful. ]
				</td>
			</tr><tr><td colspan="4">
			<hr>';
			

			echo '<tr>
				<td><strong>User creation disabled message <span style="color:red;">*</span>:</strong></td>
				<td><textarea rows="6" cols="20" name="mo_saml_account_creation_disabled_msg" placeholder="Your custom message for account creation disabled error." style="min-width:400px"';
				 if(!mo_saml_is_customer_license_key_verified()) { echo 'disabled'; }
				 echo ' required>'; 
				 $account_creation_disabled_msg = get_option( 'mo_saml_account_creation_disabled_msg' );
				 if(empty($account_creation_disabled_msg))
					 $account_creation_disabled_msg = 'We could not sign you in. Please contact your Administrator.';
				 echo $account_creation_disabled_msg;
				 echo '</textarea></td>
				</tr>
<tr><td><br/></td></tr>
				<tr>
				<td><strong>Restricted Domain error message <span style="color:red;">*</span>:</strong></td>
				<td><textarea rows="6" cols="20" name="mo_saml_restricted_domain_error_msg" placeholder="Your custom message for restricted domain error." style="min-width:400px"';
				 if(!mo_saml_is_customer_license_key_verified()) { echo 'disabled'; }
				 echo ' required>'; 
				 $restricted_domain_msg = get_option( 'mo_saml_restricted_domain_error_msg' );
				 if(empty($restricted_domain_msg))
					 $restricted_domain_msg = 'You are not allowed to login. Please contact your Administrator.';
				 echo $restricted_domain_msg;
				 echo '</textarea></td>
				</tr>
			
				<tr id="save_config_element"><td>&nbsp;</td>
				<td><br />';

				if( !mo_saml_is_customer_license_key_verified())
				  echo '<input disabled type="submit" name="submit" value="Save Settings" class="button button-primary button-large" style="margin-left: 3%;"/>';
				else
					echo '<input type="submit" name="submit" value="Save Settings" class="button button-primary button-large" style="margin-left: 3%;"/>
				</td>
			  </tr>';
			echo '</td>
			</tr>
		</table><br/><br/>
		</form>
	</div>';
}
