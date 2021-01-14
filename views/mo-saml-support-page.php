<?php function miniorange_support_saml(){
	$send_plugin_config = checked(get_option('send_plugin_config'),false,false);

	echo '<div class="mo_saml_support_layout">
		<div style="padding-right:10px">
			<h3>Support</h3>
			<p>Need any help? We can help you with configuring your Identity Provider. Just send us a query and we will get back to you soon.</p>
			<form method="post" action="">';
			wp_nonce_field('mo_saml_contact_us_query_option');
				echo '<input type="hidden" name="option" value="mo_saml_contact_us_query_option" />
				<table class="mo_saml_settings_table">
					<tr>
						<td><input style="width:95%" type="email" class="mo_saml_table_textbox" required name="mo_saml_contact_us_email" value="'.get_option("mo_saml_admin_email").'" placeholder="Enter your email"></td>
					</tr>
					<tr>
						<td><input type="tel" style="width:95%" id="contact_us_phone" pattern="[\+]?[0-9]{1,4}[\s]?[0-9]{4,12}" class="mo_saml_table_textbox" name="mo_saml_contact_us_phone" value="'.get_option('mo_saml_admin_phone').'" placeholder="Enter your phone"></td>
					</tr>
					<tr>
						<td><textarea class="mo_saml_table_textbox" style="width:95%" onkeypress="mo_saml_valid_query(this)" onkeyup="mo_saml_valid_query(this)" onblur="mo_saml_valid_query(this)" required name="mo_saml_contact_us_query" rows="4" style="resize: vertical;" placeholder="Write your query here"></textarea></td>
					</tr>
				<tr><td><br/></td></tr>
					<tr>
                    <td>
					<label class="switch">
                    <input type="checkbox" name="send_plugin_config" id="send_plugin_config" '.$send_plugin_config.' />
					<span class="slider round"></span>
					</label>
					<span style="padding-left:5px"><b>Send plugin configuration with the query</b></span>
					</td>
				</tr>
				<tr><td><br/></td></tr>
				<tr>
				<td><input type="submit" name="submit" style="width:120px;" class="button button-primary button-large" value="Submit"/></td>
				</tr>
				</table>
				<br><br>
				<div>
				</div>
			</form>
		</div>
	</div>&nbsp
	<script>
		jQuery("#contact_us_phone").intlTelInput();
		
	</script>';
	}
	
?>