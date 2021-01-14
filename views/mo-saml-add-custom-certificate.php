<?php

function mo_saml_add_custom_certificate()
{
    $certificate =  get_option('mo_saml_current_cert');
    $remaining_days = SAMLSPUtilities::getRemainingDaysOfCurrentCertificate();
    $validTo_time = SAMLSPUtilities::getExpiryDateOfCurrentCertificate();
    $valid_to = date('D, d M Y ',$validTo_time);
    $thumbprint = openssl_x509_fingerprint($certificate,"sha1");
    $cert_download_query = mo_saml_is_customer_license_key_verified()?'onclick="document.forms[\'mo_saml_download_new_cert\'].submit();"':'javascript: void(0)';
    $display_upgrade_certificate_steps = mo_check_certificate_expiry()?'block':'none';

    echo '

		<table style="width: 100%; background-color:#FFFFFF; border:1px solid #CCCCCC;border-bottom: 0px; padding:0px 20px 30px 20px;">';
		check_plugin_state();
		echo '
<tr>
            <td colspan="2" >
            <h3>Certificate Details</h3>
            <hr style="margin-bottom:20px;">
            </td>
            </tr>

            
            <tr style="text-align:center;">
                <td style="font-weight:bold;border:1px solid #949090;padding:2%; width:50%;">Certificate Thumbprint</td>
                <td style="border:1px solid #949090;padding:2%; width:50%;">'.$thumbprint.'</td>
            </tr>
            <tr style="text-align:center;">
                <td style="font-weight:bold; border:1px solid #949090;padding:2%; width:50%;">Expiry Date</td>
                <td style="border:1px solid #949090;padding:2%; width:50%;">'.$valid_to.'</td>
            </tr>
            <tr><td colspan="2" >
<div style="">';
            if($remaining_days <60 and $remaining_days>0){
                echo '<p style="font-size: medium;background-color:#CBCBCB;padding:2%;"><b>Plugin\'s current certificates will expire in <span style = "color: red" >'.$remaining_days.'</span> days. Please upgrade immediately below.</b></p>';
            }
            else if($remaining_days<0) {
                echo'<p style="font-size: medium;background-color:#CBCBCB;padding:2%;"><b>Plugin\'s current certificates have already expired . Please Upgrade immediately below .</b ></p > ';
            }
            $mo_saml_custom_cert= get_option('mo_saml_custom_cert');
            $enable_custom_certificate = $mo_saml_custom_cert? ' active-cert':'';
            $enable_miniorange_certificate = $enable_custom_certificate==''?' active-cert':'';
            $enable_custom_certificate_display = $enable_custom_certificate==''?'':'block';
            $disable_upgrade_tab = $remaining_days < 60;
            $style_value = $mo_saml_custom_cert?'none':'block';

            echo'</div>
		    </td></tr>
            <tr><td><br></td></tr>
            </table>
            <div>
        <div class="tab">
         
    <button class="tablinks '.$enable_miniorange_certificate.'"  style="width: 50%;" onclick="clickHandle(event, \'miniorange_certificate\')">miniOrange default certificate Configuration</button>
    <button class="tablinks '.$enable_custom_certificate.'" style="width: 50%" onclick="clickHandle(event, \'custom_certificate\')">Use a Custom certificate</button>
    
      </div>


<script>
function clickHandle(evt, docName) {
  let i, tabcontent, tablinks;

  // This is to clear the previous clicked content.
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  // Set the tab to be "active".
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active-cert", "");
  }

  // Display the clicked tab and set it to active.
  document.getElementById(docName).style.display = "block";
  evt.currentTarget.className += " active-cert";
}


</script>
</div>
            <div id="miniorange_certificate" class="tabcontent " style="background:white;display:'.$style_value.'">';
            if($disable_upgrade_tab){
            echo'
            <table  style="width: 100%;border-spacing: 0 1em;border-top: 0px; background-color:#FFFFFF;  padding:0px 20px 30px 20px;display: ';
            echo $display_upgrade_certificate_steps;
            echo '">
            
            <tr><td colspan="4">
            
            <h4 style="font-size: 15px;">Follow the steps mentioned below for successful migration of new certificates:</h4>
            </td></tr>
            <tr><td colspan="4">
            <div ><p style="font-weight: bold"><div ><b>Step 1: Provide this plugin information to your Identity Provider team. You can choose one of the below options</b></p></div>
            </td></tr>
            <tr><td colspan="4" style="padding-left:2% ">
            
            a) Download the Plugin XML metadata and upload it on your Identity Provider</td>
            <td>
            <input type="button" class="button button-primary button-large" 
            value="Download Metadata" style="width: 100%" ' . mo_saml_is_customer_license_key_verified( true ) . ' onclick="document.getElementById(\'mo_saml_download_new_metadata\').submit();" />
            </td>
            </tr>
            <tr><td colspan="4" style="text-align: center">OR</td></tr>
            <tr>
            <td colspan="4" style="padding-left:2% ">
            b) Download the New Plugin Certificate and upload it on your Identity Provider
            </td>
            <td>
            <input type="button" class="button button-primary button-large" style="width: 100%"';
                if(!mo_saml_is_customer_license_key_verified()) { echo ' disabled '; }
                echo $cert_download_query . ' value="Download Certificate"/></td>
            

		    </tr>
            
            <tr><td colspan="4"><b>Step 2:
                Test if the Certficate have been added to IDP </b></td>
                <td><input type="button" name="test" onclick="showTestWindow();" style="width: 100%"';
                    if(!mo_saml_is_sp_configured() || !mo_saml_is_customer_license_key_verified() ) { echo 'disabled';}
                    echo ' value="Test Connection" class="button button-primary button-large" style="width:150px;"/></td>
                    
		    </tr>
           <tr><td colspan="4"><div><b>Step 3:</b>
            
                <b> You\'re all set click on Upgrade to miniOrange to apply latest certificates</b> </td><td>
 <button type="button" class="button button-primary button-large" data-toggle="modal" style="width: 100%" ';
    if(!get_option('mo_saml_test_new_cert')) {echo 'disabled';}
 echo ' id="upgrade_to_miniorange_certs" data-target="#upgrade-cert-modal">Apply Certificate</button>
           </div></td></tr>
            
            <tr><td><br></td></tr>
            <tr>
                <td style="text-align:right;">';

                echo '
                
            </tr>
            </table>';}
            else{
                echo'
            <table  style="width: 100%;border-spacing: 0 1em;border-top: 0px; background-color:#FFFFFF;  ">
            <tr style="color: green;font-size: 21px;height:100px;text-align: center"><td>
            <b>Your certificates are upto date. </b>
</td></tr>
</table>';
            }
        echo'                
                
         </div>
             <div id="custom_certificate" class="tabcontent" style="background: white;display: '.$enable_custom_certificate_display.'">
            <form method="post" >
			<table  style="width: 100%;border-spacing:0; background-color:#FFFFFF;padding:0px 0px 30px 20px;">
			<tr>
				<td colspan="2">
					<h3>Add Custom Certificate</h3>
			    </td>
			</tr>
			<tr>
				<td colspan="2">
					[&nbsp;<a href="https://docs.miniorange.com/documentation/saml-handbook/custom-certificate" target="_blank">Click here</a> to know how this is useful. ]
				</td>
			</tr>
			<tr><td colspan="2"><br><hr></td></tr>
				<tr>
				<td style=" width:30%">';
                 wp_nonce_field('add_custom_certificate');
                echo '
				<input type="hidden" name="option" value="add_custom_certificate" /><strong>X.509 Public Certificate <span style="color:red;">*</span>:</strong></td>
				<td colspan="2"><textarea rows="6" cols="5" name="saml_public_x509_certificate" placeholder="Enter the X.509 Public Certificate here" style="width: 95%;"';
				 if(!mo_saml_is_customer_license_key_verified()) { echo 'disabled'; }
				 echo ' required>';
				 $public_cert = get_option( 'mo_saml_custom_cert' );
				 if( $public_cert != "" )
		            {
						echo $public_cert;
					}
				 echo '</textarea></td>
				</tr>
				<tr>
					<td></td>
					<td><b>NOTE:</b> Format of the certificate:<br/><b>-----BEGIN CERTIFICATE-----<br/>XXXXXXXXXXXXXXXXXXXXXXXXXXX<br/>-----END CERTIFICATE-----</b></i><br/>
				</tr>
				<tr><td><br><br></td></tr>
				<tr>
				<td><strong>X.509 Private Certificate <span style="color:red;">*</span>:</strong></td>
				<td colspan="2"><textarea rows="6" cols="5" name="saml_private_x509_certificate" placeholder="Enter the X.509 Private Certificate here" style="width: 95%;"';
				 if(!mo_saml_is_customer_license_key_verified()) { echo 'disabled'; }
				 echo ' required>';

				 $private_cert = get_option( 'mo_saml_custom_cert_private_key' );
				 if( $private_cert != "" ) {
				     echo $private_cert;
				 }
				 echo '</textarea></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><b>NOTE:</b> Format of the certificate:<br/><b>-----BEGIN PRIVATE KEY-----<br/>XXXXXXXXXXXXXXXXXXXXXXXXXXX<br/>-----END PRIVATE KEY-----</b></i><br/>
				</tr>
				<tr id="save_config_element"><td>&nbsp;</td>
				<td><br />


				<input style="margin-right: 10px;" type="submit" class="button button-primary button-large"
				id="upload_certificate_modal" value="Upload" name="submit"
				';
				if(!mo_saml_is_customer_license_key_verified() ) { echo 'disabled'; }
				echo '/>';

				if( !mo_saml_is_customer_license_key_verified() || ( $private_cert == "" ) && ( $public_cert == "" ) )
				  echo '<input disabled type="submit" name="submit" value="Reset" class="button button-primary button-large" style="margin-left: 3%;"/>';
				else
					echo '<input type="submit" name="submit" value="Reset" class="button button-primary button-large" style="margin-left: 3%;"/>
				</td>
			  </tr>';
				//------------------------------------------------
			echo '</td>
			</tr>
		    </table>
			

		
		</form>
	</div>
	<form method="post" action="" name="mo_saml_download_new_cert">';
    wp_nonce_field('mo_saml_download_new_cert');
    echo '<input type="hidden" name="option" value="mo_saml_download_new_cert" />
    </form>
   
    
	<form method="post" action="" id="mo_saml_download_new_metadata">';
    wp_nonce_field('mo_saml_download_new_metadata');
    echo '<input type="hidden" name="option" value="mo_saml_download_new_metadata"/> 
                 </form>

	<form method="post" name="upgrade_cert" id="upgrade_cert_form">';
    wp_nonce_field('upgrade_cert');
echo'
    <input hidden name="option" value="upgrade_cert"/>
</form>
	<form method="post" name="rollback_cert" id="rollback_cert_form">';
    wp_nonce_field('rollback_cert');
echo'
    <input hidden name="option" value="rollback_cert"/>
</form>
	
	

  <!-- Modal -->
<div class="modal" id="upgrade-cert-modal" role="dialog" style="margin-top: 200px;">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title" style="font-size: 20px;">Are you sure you want to Upgrade?</h2>
            </div>
            
                
            <div class="modal-footer" style="text-align:center; border-top:0px;">
            

            <button style="margin-right: 15px;padding:0px 30px;" type="button" class="button button-primary button-large" value="upgrade_cert" id="upgrade_cert" onclick="document.getElementById(\'upgrade_cert_form\').submit();" />
                        Confirm Upgrade </button>
            <button style="padding:0px 30px;" type="button" class="button button-primary button-large" data-dismiss="modal">Don\'t Upgrade</button>
            </div>
        </div>
    </div>
</div>

<script>

			function showTestWindow() {
				var myWindow = window.open("' . mo_saml_get_test_url(true) . '", "TEST SAML IDP", "scrollbars=1 width=800, height=600");
			}
			
jQuery(document).ready(function(){
   jQuery("#upload_certificate_modal").click(function(){ 
      document.getElementById("upgrade_cert").style.display = "none";
      document.getElementById("miniorange_upload").style.display = "";
   });
   jQuery("#upgrade_to_miniorange_certs").click(function(){ 
      document.getElementById("miniorange_upload").style.display = "none";
      document.getElementById("upgrade_cert").style.display = "";
   });
});

</script>

	
	';
}

function display_confirm_certificate_modal($custom_certificate=false){

}
