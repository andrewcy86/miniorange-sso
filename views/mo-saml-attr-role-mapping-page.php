<?php
	function mo_saml_save_optional_config() {
		global $wpdb;
		$entity_id = get_option( 'entity_id' );
		if ( ! $entity_id ) {
			$entity_id = 'https://login.xecurify.com/moas';
		}
		$sso_url = get_option( 'sso_url' );
		$cert_fp = get_option( 'cert_fp' );

		$saml_identity_name = get_option( 'saml_identity_name' );

		//Attribute mapping
		$saml_am_username = get_option( 'saml_am_username' );
		if ( $saml_am_username == null ) {
			$saml_am_username = 'NameID';
		}
		$saml_am_email = get_option( 'saml_am_email' );
		if ( $saml_am_email == null ) {
			$saml_am_email = 'NameID';
		}
		$saml_am_first_name = get_option( 'saml_am_first_name' );
		$saml_am_last_name  = get_option( 'saml_am_last_name' );
		$saml_am_group_name = get_option( 'saml_am_group_name' );
		$saml_am_email_domains = get_option( 'saml_am_email_domains' );
		$saml_am_allow_deny_domains = get_option( 'saml_am_allow_deny_domains' );
		$mo_saml_enable_domain_restriction_login = get_option( 'mo_saml_enable_domain_restriction_login' );
		$mo_saml_allow_deny_user_with_domain = get_option('mo_saml_allow_deny_user_with_domain');
		$idp_attrs = get_option('mo_saml_test_config_attrs');
		if(@unserialize($idp_attrs))
			$idp_attrs = unserialize($idp_attrs);

		$current_user = wp_get_current_user();
		$wpum_meta    = get_user_meta( $current_user->ID );
		echo '
		<form name="saml_form_am" method="post" action="">';
		wp_nonce_field('login_widget_saml_attribute_mapping');
		echo '<input type="hidden" name="option" value="login_widget_saml_attribute_mapping" />
		<table id="myTable" width="100%" border="0" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;">
		  <tr>
			<td colspan="4">
				<h3>Attribute Mapping</h3><hr>
			</td>
		  </tr>';
		check_plugin_state();
		echo '<tr>
		  	<td colspan="2">[ '; ?><a target="_blank" href="https://docs.miniorange.com/documentation/saml-handbook/attribute-role-mapping/attribute-mapping">Click Here</a><?php echo ' to know how this is useful. ]
		 		<div hidden id="attribute_mapping_desc" class="mo_saml_help_desc">
					<ol>
						<li>Attributes are user details that are stored in your Identity Provider.</li>
						<li>Attribute Mapping helps you to get user attributes from your IdP and map them to WordPress user attributes like firstname, lastname etc.</li>
						<li>While auto registering the users in your WordPress site these attributes will automatically get mapped to your WordPress user details.</li>
					</ol>
				</div>
				</td>
		  </tr>
		  <tr>
			<td><br/></td>	 
		  </tr>
		  
		  ';


	echo '<tr>
				<td style="width:150px;"><strong>Username <span style="color:red;">*</span>:</strong></td>
				<td>';
				if($idp_attrs){
					echo '<select name="saml_am_username" style="width:auto; min-width:65%; max-width:85%"';
					if(!mo_saml_is_customer_license_key_verified()) { echo 'disabled'; }
					echo '>
					<option value="">--Select an Attribute--</option>';
					foreach($idp_attrs as $key => $value){
						$selected = ($saml_am_username == $key) ? 'selected' : '';
						echo '<option value="' .$key .'" ' . $selected . ' >' . $key . '</option>';
					}
					echo '</select>';
				} else {
					echo '<input type="text" name="saml_am_username" placeholder="Enter attribute name for Username" style="width: 350px;" value="' . $saml_am_username . '" required';
					if(!mo_saml_is_customer_license_key_verified()) { echo 'disabled'; } echo '/>';
				}
				echo '</td>
			  </tr>
			  <tr>
				<td><strong>Email <span style="color:red;">*</span>:</strong></td>
				<td>';

				if($idp_attrs){
					echo '<select name="saml_am_email" style="width:auto; min-width:65%; max-width:85%"';
					if(!mo_saml_is_customer_license_key_verified()) { echo 'disabled'; }
					echo '>
					<option value="">--Select an Attribute--</option>';
					foreach($idp_attrs as $key => $value){
						$selected = ($saml_am_email == $key) ? 'selected' : '';
						echo '<option value="' .$key .'" ' . $selected . ' >' . $key . '</option>';
					}
					echo '</select>';
				} else {
				echo '<input type="text" name="saml_am_email" placeholder="Enter attribute name for Email" style="width: 350px;" value="'.$saml_am_email.'" required';
				 if(!mo_saml_is_customer_license_key_verified()) { echo 'disabled'; }
				 echo '/>';
				}
				echo '</td>
			  </tr>
			  <tr>
				<td><strong>First Name:</strong></td>
				<td>';

				if($idp_attrs){
					echo '<select name="saml_am_first_name" style="width:auto; min-width:65%; max-width:85%"';
					if(!mo_saml_is_customer_license_key_verified()) { echo 'disabled'; }
					echo '>
					<option value="">--Select an Attribute--</option>';
					foreach($idp_attrs as $key => $value){
						$selected = ($saml_am_first_name == $key) ? 'selected' : '';
						echo '<option value="' .$key .'" ' . $selected . ' >' . $key . '</option>';
					}
					echo '</select>';
				} else {
					echo '<input type="text" name="saml_am_first_name" placeholder="Enter attribute name for First Name" style="width: 350px;" value="'.$saml_am_first_name.'"';
					if(!mo_saml_is_customer_license_key_verified()) { echo 'disabled'; }
					echo '/>';
				}

				echo '</td>
			  </tr>
			  <tr>
				<td><strong>Last Name:</strong></td>
				<td>';

				if($idp_attrs){
					echo '<select name="saml_am_last_name" style="width:auto; min-width:65%; max-width:85%"';
					if(!mo_saml_is_customer_license_key_verified()) { echo 'disabled'; }
					echo '>
					<option value="">--Select an Attribute--</option>';
					foreach($idp_attrs as $key => $value){
						$selected = ($saml_am_last_name == $key) ? 'selected' : '';
						echo '<option value="' .$key .'" ' . $selected . ' >' . $key . '</option>';
					}
					echo '</select>';
				} else {
					echo '<input type="text" name="saml_am_last_name" placeholder="Enter attribute name for Last Name" style="width: 350px;" value="'. $saml_am_last_name.'"';
					if(!mo_saml_is_customer_license_key_verified()) { echo 'disabled'; }
					echo '/>';
				}

				echo '</td>
			  </tr>
			  <tr>
				<td><strong>Group/Role:</strong></td>
				<td>';

				if($idp_attrs){
					echo '<select name="saml_am_group_name" style="width:auto; min-width:65%; max-width:85%"';
					if(!mo_saml_is_customer_license_key_verified()) { echo 'disabled'; }
					echo '>
					<option value="">--Select an Attribute--</option>';
					foreach($idp_attrs as $key => $value){
						$selected = ($saml_am_group_name == $key) ? 'selected' : '';
						echo '<option value="' .$key .'" ' . $selected . ' >' . $key . '</option>';
					}
					echo '</select>';
				} else {
					echo '<input type="text" name="saml_am_group_name" placeholder="Enter attribute name for Group/Role" style="width: 350px;" value="'. $saml_am_group_name.'"';
					if(!mo_saml_is_customer_license_key_verified()) { echo 'disabled'; }
					echo '/>';
				}

				echo '</td>
			  </tr>
			 <tr>
				<td><strong>Display Name:</strong></td>
				<td>
					<select name="saml_am_display_name" id="saml_am_display_name" style="width:auto; min-width:65%; max-width:85%"';
		if ( ! mo_saml_is_customer_license_key_verified() ) {
			echo 'disabled';
		}
		echo '>
						<option value="USERNAME"';
		if ( get_option( 'saml_am_display_name' ) == 'USERNAME' ) {
			echo 'selected="selected"';
		}
		echo '>Username</option>
						<option value="FNAME"';
		if ( get_option( 'saml_am_display_name' ) == 'FNAME' ) {
			echo 'selected="selected"';
		}
		echo '>FirstName</option>
						<option value="LNAME"';
		if ( get_option( 'saml_am_display_name' ) == 'LNAME' ) {
			echo 'selected="selected"';
		}
		echo '>LastName</option>
						<option value="FNAME_LNAME"';
		if ( get_option( 'saml_am_display_name' ) == 'FNAME_LNAME' ) {
			echo 'selected="selected"';
		}
		echo '>FirstName LastName</option>
						<option value="LNAME_FNAME"';
		if ( get_option( 'saml_am_display_name' ) == 'LNAME_FNAME' ) {
			echo 'selected="selected"';
		}

		echo '>LastName FirstName</option>
					</select>
				</td>
			  </tr>';

		$i = 0;

			  echo '<tr><td colspan="3">
				<h3>Map Custom Attributes</h3>Map extra IDP attributes which you wish to be included in the user profile. <p><b>NOTE: </b>Customized Attribute Mapping means you can map any attribute of the IDP to the attributes of <b>user-meta</b> table of your database.</p>
				<p>Enable the toggle for an attribute if you want to display it in the Wordpress <a href="' .get_admin_url() .'users.php">Users</a> table.</p>
				<br/>
				<input type="button" name="add_attribute" value="Add Attribute" onClick="add_custom_attribute(this)" class="button button-primary button-large"';
			if(!mo_saml_is_customer_license_key_verified()) { echo ' disabled '; }
			echo '><br/><br/>
			
			</td><td></td>';

			echo '<td></td></tr>

						<tr>
						<td style="text-align:center"><b>Custom Attribute Name</b></td>
						<td style="padding-left:100px"><b>Attribute Name from IDP</b></td>
						<td style="text-align: center;"><b>Display Attribute</b></td>
						<td></td>
						</tr>';



			$custom_attributes = get_option('mo_saml_custom_attrs_mapping');
			if(@unserialize($custom_attributes)){
				$custom_attributes = unserialize($custom_attributes);
			}
			if(!$custom_attributes){
				// Display an empty row
				echo display_custom_attribute_mapping_row($i, '', '', false);
				$i++;
			} else {
				foreach($custom_attributes as $key => $value){
					if(!empty($key)){
						// Display the populated rows
						$attr_in_user_menu = get_option('saml_show_user_attribute');
						$checked = false;
						if($attr_in_user_menu)
							if(in_array($i, $attr_in_user_menu))
								$checked = true;
						echo display_custom_attribute_mapping_row($i, $key, $value, $checked);
						$i++;
					}
				}
			}

			echo '<tr id="save_config_element">
				<td><br /><input type="submit" style="width:100px;" name="submit" value="Save"  class="button button-primary button-large"';
			if ( ! mo_saml_is_customer_license_key_verified() ) {
				echo 'disabled';
			}
			echo '/> &nbsp;
				<br /><br />
				</td>
			  </tr>
			 </table>
			 </form>';
		echo '<script>
			var getRows = document.getElementsByClassName("rows");
			if(getRows.length == 1){
				getRows[0].children[3].style.visibility = \'hidden\';
			} else {
				for(var row of getRows){
					row.children[3].style.visibility = \'visible\';
				}
			}
			
		
		function checkEmptyKeyandValue(o){
			var getRow = o.parentNode.parentNode;
			var child = getRow.children;
			var keys = child[0].children;
			var values = child[1].children;
			var valueField = values[0];
			var keyField = keys[0];
			var key = keyField.value;
			var val = valueField.value;
			if(!key || 0 === key.length){
				if(val.length > 0){
					keyField.setAttribute("required", "required");
				} else {
					keyField.removeAttribute("required");
				}
			}
			
			if(!val || 0 === val.length){
				if(key.length > 0){
					valueField.setAttribute("required", "required");
				} else {
					valueField.removeAttribute("required");
				}
			}
		}
		
		
		function add_custom_attribute(o){
			rows = "' . addslashes(display_custom_attribute_mapping_row($i, '', '', false)) . '";';

			$i++;
			echo '
			jQuery(rows).insertBefore(jQuery("#save_config_element"));
			
			if(getRows.length == 1){
				getRows[0].children[3].style.visibility = \'hidden\';
			} else {
				for(var row of getRows){
					row.children[3].style.visibility = \'visible\';
				}
			}
		}

		
		function removeRow(o){
			var thisRow = o.parentNode.parentNode;
			if(getRows.length == 2){
				if(thisRow.id === getRows[0].id){
					getRows[1].children[3].style.visibility = \'hidden\';
				}
				getRows[0].children[3].style.visibility = \'hidden\';
				
			} else {
				for(var row of getRows){
					row.children[3].style.visibility = \'visible\';
				}
			}
			
			thisRow.parentNode.removeChild(thisRow);
			
		}
			
		</script>
		<br />
		<form name="saml_form_domain_restriction" method="post" action="">';
		wp_nonce_field('saml_form_domain_restriction_option');
		echo '<input type="hidden" name="option" value="saml_form_domain_restriction_option" />
			<table width="100%" border="0" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;">
				<tr>
					<td colspan="2" style="padding-right:5px">
						<h3>Domain Restriction (Optional)</h3><hr>';
        echo '<tr>
								<td colspan="2">[ '; ?><a target="_blank" href="https://docs.miniorange.com/documentation/saml-handbook/attribute-role-mapping/domain-restriction">Click Here</a>
        <?php echo ' to know how this is useful. ]
								
						<p>If you want the users of specific domain to be allowed or restricted to login then you can use this feature. <br/>
						<b>Note:</b> The users will be restricted/allowed to login based on their <b>Email</b> attribute as mapped above in the Attribute Mapping section.<br/>
						<br/>You can enter the semicolon separated list of domains like domain1.com;domain2.com;domain3.com </p>
					</td>
				</tr>
				<tr><td colspan="2"><label class="switch"><input type="checkbox" id="mo_saml_enable_domain_restriction_login" onchange="domainRestrictionCheckboxValidation()" name="mo_saml_enable_domain_restriction_login" value="checked"' . get_option( 'mo_saml_enable_domain_restriction_login' );
		if ( ! mo_saml_is_customer_license_key_verified() ) {
			echo ' disabled ';
		}
		echo '/><span class="slider round"></span>
		</label><span style="padding-left:5px"><b>Enable domain restriction login.</b></span><br /></td></tr>
		<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
			<tr>
				<td><input type="radio" id="allow_login" name="mo_saml_allow_deny_user_with_domain" value="allow" '; if ( ! mo_saml_is_customer_license_key_verified() ) {
			echo 'disabled';
		} echo $mo_saml_allow_deny_user_with_domain == 'allow' ? ' checked' : ''; echo '/>Allow users to login with specified domains<br />
				<input type="radio" id="deny_login" name="mo_saml_allow_deny_user_with_domain" value="deny" '; if ( ! mo_saml_is_customer_license_key_verified() ) {
			echo 'disabled';
		} echo $mo_saml_allow_deny_user_with_domain == 'deny' ? ' checked' : ''; echo '/>Deny users to login with specified domains
				</td>
				<td><input type="text" id="saml_am_email_domains" name="saml_am_email_domains" placeholder="Enter semicolon(;) separated domains to be allowed or restrict" style="width: 400px;" value="' . $saml_am_email_domains . '" ';
		if ( ! mo_saml_is_customer_license_key_verified() ) {
			echo 'disabled';
		}
		echo '/></td>
			  </tr>
			  <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
			  <tr>
				<td>&nbsp;</td>
				<td><input type="submit" style="width:100px;" name="submit" value="Save"  class="button button-primary button-large"';
			if ( ! mo_saml_is_customer_license_key_verified() ) {
				echo 'disabled';
			}
			echo '/></td></tr>
			<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
			</table>
		</form>
			  
			<br />
			 <form name="saml_form_am_role_mapping" method="post" action="">';
			 wp_nonce_field('login_widget_saml_role_mapping');
			echo '<input type="hidden" name="option" value="login_widget_saml_role_mapping" />
				<table width="100%" border="0" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;">
					<tr>
						<td colspan="2">
							<h3>Role Mapping (Optional)</h3><hr>
						</td>
					</tr>
					 <tr>
					  	<td colspan="2">[ '; ?><a target="_blank" href="https://docs.miniorange.com/documentation/saml-handbook/attribute-role-mapping/role-mapping">Click Here</a><?php echo ' to know how this is useful. ]
					 		<div hidden id="role_mapping_desc" class="mo_saml_help_desc">
								<ol>
									<li>WordPress uses a concept of Roles, designed to give the site owner the ability to control what users can and cannot do within the site.</li>
									<li>WordPress has six pre-defined roles: Super Admin, Administrator, Editor, Author, Contributor and Subscriber.</li>
									<li>Role mapping helps you to assign specific roles to users of a certain group in your IdP.</li>
									<li>While auto registering, the users are assigned roles based on the group they are mapped to.</li>
								</ol>
							</div>
							</td>
					  </tr>
					<tr><td colspan="2"><br/><b>NOTE: </b>Role will be assigned only to non-admin users (user that do NOT have Administrator privileges). You will have to manually change the role of Administrator users.<br /><br/></td></tr>
					<tr><td colspan="2"><label class="switch"><input type="checkbox" id="dont_create_user_if_role_not_mapped" name="mo_saml_dont_create_user_if_role_not_mapped" value="checked"'
		                                                                                                . get_option( 'mo_saml_dont_create_user_if_role_not_mapped' );
		if ( ! mo_saml_is_customer_license_key_verified() ) {
			echo ' disabled ';
		}
		echo '/><span class="slider round"></span>
		</label>
		<span style="padding-left:5px"><b>Do not auto create users if roles are not mapped here.</b></span><br /></td></tr>
					<tr><td colspan="2"><label class="switch"><input type="checkbox" id="dont_allow_unlisted_user_role" name="saml_am_dont_allow_unlisted_user_role" value="checked"' . get_option( 'saml_am_dont_allow_unlisted_user_role' );
		if ( ! mo_saml_is_customer_license_key_verified() ) {
			echo 'disabled ';
		}
		echo ' /><span class="slider round"></span>
		</label>
		<span style="padding-left:5px"><b>Do not assign role to unlisted users.</b></span><br /></td></tr>
					<tr><td colspan="2"><label class="switch"><input type="checkbox" id="dont_update_existing_user_role" name="mo_saml_dont_update_existing_user_role" value="checked"'
		     . get_option( 'saml_am_dont_update_existing_user_role' );
		if ( ! mo_saml_is_customer_license_key_verified() ) {
			echo ' disabled ';
		}
		echo '/><span class="slider round"></span>
		</label>
		<span style="padding-left:5px"><b>Do not update existing user\'s roles.</b></span><br /><br /></td></tr>
		<tr><td colspan="2"><label class="switch"><input type="checkbox" onchange="dontAllowUserLoginCheckboxValidation()" id="dont_allow_user_tologin_create_with_given_groups" name="mo_saml_dont_allow_user_tologin_create_with_given_groups" value="checked"'
		     . get_option( 'saml_am_dont_allow_user_tologin_create_with_given_groups' );
		if ( ! mo_saml_is_customer_license_key_verified() ) {
			echo ' disabled ';
		}
		echo '/><span class="slider round"></span>
		</label>
		<span style="padding-left:5px"><b>Do not allow the users to login with the following roles.</b></span><br /></td></tr>
		<tr><td colspan="6"><input type="text" id="mo_saml_restrict_users_with_groups" name="mo_saml_restrict_users_with_groups" value="' . get_option( 'mo_saml_restrict_users_with_groups' ) . '" placeholder="Semi-colon(;) separated Group/Role value" style="width: 500px;"';
		if ( ! mo_saml_is_customer_license_key_verified() ) {
			echo ' disabled ';
		}
		echo ' /><br /><br /></td></tr>
					
					<tr><td colspan="6">
					
					
					<tr>
						<td><strong>Default Role:</strong></td>
						<td>';
		$disabled = '';
		if ( ! mo_saml_is_customer_license_key_verified() ) {
			$disabled = ' disabled ';
		}
		echo '<select id="saml_am_default_user_role" name="saml_am_default_user_role"' . $disabled . ' style="width:150px;" >';
		$default_role = get_option( 'saml_am_default_user_role' );
		if ( empty( $default_role ) ) {
			$default_role = get_option( 'default_role' );
		}
		echo wp_dropdown_roles( $default_role );
		echo '	</select>
							&nbsp;&nbsp;&nbsp;&nbsp;<i>Select the default role to assign to Users.</i>
						</td>
				  	</tr>';
		$is_disabled = "";
		if ( ! mo_saml_is_customer_license_key_verified() ) {
			$is_disabled = ' disabled ';
		}
		$wp_roles         = new WP_Roles();
		$roles            = $wp_roles->get_names();
		$roles_configured = get_option( 'saml_am_role_mapping' );
		if(@unserialize($roles_configured)){
			$roles_configured = unserialize($roles_configured);
		}
		foreach ( $roles as $role_value => $role_name ) {
            $configured_role_value = empty($roles_configured)?'':$roles_configured[$role_value];
            echo '<tr><td><b>' . $role_name . '</b></td><td><input type="text" name="saml_am_group_attr_values_' . $role_value . '" value="' . $configured_role_value . '" placeholder="Semi-colon(;) separated Group/Role value for ' . $role_name . '" style="width: 400px;"' . $is_disabled . ' /></td></tr>';
        }
		echo '<tr>
						<td>&nbsp;</td>
						<td><br /><input type="submit" style="width:100px;" name="submit" value="Save" class="button button-primary button-large"';
		if ( ! mo_saml_is_customer_license_key_verified() ) {
			echo 'disabled';
		}
		echo '/> &nbsp;
						<br /><br />
						</td>
					</tr>
				</table>
			</form>';
		echo '<script>
			function domainRestrictionCheckboxValidation(){
				if(document.getElementById("mo_saml_enable_domain_restriction_login").checked){
					document.getElementById("allow_login").disabled = false;
					document.getElementById("deny_login").disabled = false;
					document.getElementById("saml_am_email_domains").disabled = false;
				} else {
					document.getElementById("allow_login").disabled = true;
					document.getElementById("deny_login").disabled = true;
					document.getElementById("saml_am_email_domains").disabled = true;
				}
			}

			function dontAllowUserLoginCheckboxValidation(){
				if(document.getElementById("dont_allow_user_tologin_create_with_given_groups").checked){
					document.getElementById("mo_saml_restrict_users_with_groups").disabled = false;
				} else {
					document.getElementById("mo_saml_restrict_users_with_groups").disabled = true;
				}
			}
			dontAllowUserLoginCheckboxValidation();
			domainRestrictionCheckboxValidation();
		</script>';

	}

	function display_custom_attribute_mapping_row($index, $key, $value, $checked){
		$html = '<tr class="rows" id="row_'. $index .'"><td><input type="text" name="mo_saml_custom_attribute_keys[]" placeholder="Custom attribute name" value="' . $key . '" onkeyup="checkEmptyKeyandValue(this)"';
		if(!mo_saml_is_customer_license_key_verified()) { $html = $html . 'disabled'; }
		$html = $html . '/></td>';
		$idp_attrs = get_option('mo_saml_test_config_attrs');
		if(@unserialize($idp_attrs))
			$idp_attrs = unserialize($idp_attrs);

		if(!empty($idp_attrs)){
			$html = $html . '<td><select name="mo_saml_custom_attribute_values[]" style="width:90%"';
			if(!mo_saml_is_customer_license_key_verified()) { $html = $html . 'disabled'; }
			$html = $html . '><option value="">--Select an Attribute--</option>';
			foreach($idp_attrs as $attr_key => $attr_value){
				$selected = ($value == $attr_key) ? 'selected' : '';
				$html = $html . '<option value="' .$attr_key .'" ' . $selected . ' >' . $attr_key . '</option>';
			}
			$html = $html . '</td>';
		} else {
			$html = $html . '<td><input type="text" name="mo_saml_custom_attribute_values[]" placeholder="Enter attribute name from IDP" value="' . $value . '" style="width:74%;" onkeyup="checkEmptyKeyandValue(this)"';
			if(!mo_saml_is_customer_license_key_verified()) { $html = $html . 'disabled'; }
			$html = $html . '/></td>';
		}
		$html = $html . '<td style="text-align: center; width:15%;"><label class="switch"><input type="checkbox" title="Display in Wordpress Users table" name="mo_saml_display_attribute_' . $index . '"';
		if($checked)
		$html = $html . 'checked';
		$html = $html . ' value="true"';
		if(!mo_saml_is_customer_license_key_verified()) { $html = $html . 'disabled'; }
		$html = $html . '><span class="slider round"></span></label></td><td><input type="button" value="X" onClick="removeRow(this)" class="button button-primary button-large" style="float:right;" /></td></tr>';

		return $html;
	}