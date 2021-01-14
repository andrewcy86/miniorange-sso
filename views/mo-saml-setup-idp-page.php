<?php

  function mo_saml_configuration_steps() {

	$sp_base_url = get_option('mo_saml_sp_base_url')?:home_url();

	$sp_entity_id = get_option('mo_saml_sp_entity_id')?:$sp_base_url.'/wp-content/plugins/miniorange-saml-20-single-sign-on/';

	$saml_nameid_format = get_option('saml_nameid_format');
	if(empty($saml_nameid_format)){
		$saml_nameid_format = '1.1:nameid-format:emailAddress';
	}
	$upload_metadata_query = mo_saml_is_customer_license_key_verified()?add_query_arg( array( 'tab'    => 'save',
        'action' => 'upload_metadata'
    ), $_SERVER['REQUEST_URI'] ):"javascript: void(0)";

	$service_provider_query = mo_saml_is_customer_license_key_verified()?add_query_arg( array( 'tab' => 'save' ), $_SERVER['REQUEST_URI'] ):"javascript:void(0)";

	$cert_download_query = mo_saml_is_customer_license_key_verified()?'onclick="document.forms[\'mo_saml_download\'].submit();"':'javascript: void(0)';

	$metadata_url = mo_saml_is_customer_license_key_verified()?$sp_base_url . "/?option=mosaml_metadata":"javascript:void(0)";


		echo '<input type="hidden" name="option" value="mo_saml_idp_config" />';
		
		echo '<div class="slideshow-container ">
	<div id="parent">
	<div class="mo_prev"  onclick="plusSlides(-1);changeTrackerforStep1(1);" style="z-index: 2" id="mo_prev"><span id="mo_prev_pointer">&#10094;</span></div>
	<div class="mo_next"  style="z-index: 2" onclick="plusSlides(1);changeTrackerforStep2(2)" id="mo_next"><span id="mo_next_pointer">&#10095;</span></div>
	</div>
	    
	<table width="98%" border="0" class="progress-tracker-table">
	    <tr><td>';
	    	check_plugin_state();echo '

</td></tr>

		
<tr><td>

<ul class="progress-tracker progress-tracker--text progress-tracker--center anim-path anim-ripple" >
  <li class="progress-step is-active" id="progress_step1">
    <span class="progress-marker" id="tracker_step1" onclick="changeTrackerforStep1(1)">1</span>
    <span class="progress-text">
     Configure your Identity Provider 
    </span>
  </li>

  <li class="progress-step"  id="progress_step2">
    <span class="progress-marker" onclick="changeTrackerforStep2(2)" id="tracker_step2">2</span>
    <span class="progress-text">
      Configure Service Provider
    </span>
  </li>

</ul>

		<div class="mySlides fade">
		
		<h3 style="text-align: center">Configure your Identity Provider </h3>
		<h4>Provide this plugin information to your Identity Provider team. You can choose any one of the below options:</h4>
		<p><b>a) Provide this metadata URL to your Identity Provider </b></p>
		<p style="margin-left: 2%"><code><b><a id="metadata_url" target="_blank" href="' . $metadata_url . '" '.mo_saml_is_customer_license_key_verified( true ).' >' . $sp_base_url . '/?option=mosaml_metadata</a></b></code>
		<i class="fa fa-fw fa-lg fa-copy mo_copy copytooltip" onclick="copyToClipboard(this, \'#metadata_url\', \'#metadata_url_copy\');"><span id="metadata_url_copy" class="copytooltiptext">Copy to Clipboard</span></i>
		</p>    
        <p><b>b) Download the Plugin XML metadata and upload it on your Identity Provider </b></p>				
		<p ><form method="post" action="">';
		wp_nonce_field('mo_saml_download_metadata');
		echo '<input type="hidden" name="option" value="mo_saml_download_metadata"/> 
        <input type="submit" class="button button-primary button-large"  value="Download XML Metadata" style="margin-left: 2%" ' . mo_saml_is_customer_license_key_verified( true ) . ' /></form></p>
		<p><b>c) Provide the following information to your Identity Provider.</b></p>
		
		<table border="1" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px; margin:2px; border-collapse: collapse; width:98%">
			
				<form method="post" action="" name="mo_saml_download">';
				wp_nonce_field('mo_saml_download_cert');
				echo '<input type="hidden" name="option" value="mo_saml_download_cert" /></form>
					<tr>
						<td style="width:40%; padding: 15px;"><b>SP-EntityID / Issuer</b></td>
						<td style="width:60%; padding: 15px;"><table width="100%"><tr><td><span id="entity_id">' . $sp_entity_id . '</span></td>
						<td><i class="fa fa-fw fa-lg fa-pull-right fa-copy mo_copy copytooltip" onclick="copyToClipboard(this, \'#entity_id\', \'#entity_id_copy\');"><span id="entity_id_copy" class="copytooltiptext">Copy to Clipboard</span></i></td></tr></table>
						</td>

					</tr>
					<tr>
						<td style="width:40%; padding: 15px;"><b>ACS (AssertionConsumerService) URL</b></td>
						<td style="width:60%;  padding: 15px;"><table width="100%"><tr><td><span id="base_url">' . $sp_base_url . '/</span></td>
						<td><i class="fa fa-fw fa-pull-right fa-lg fa-copy mo_copy copytooltip" onclick="copyToClipboard(this, \'#base_url\', \'#base_url_copy\');"><span id="base_url_copy" class="copytooltiptext">Copy to Clipboard</span></i></td></tr></table>
						</td>

					</tr>

					<tr>
						<td style="width:40%; padding: 15px;"><b>Single Logout URL</b></td>
						<td style="width:60%;  padding: 15px;"><table width="100%"><tr><td><span id="slo_url">' . $sp_base_url . '/</span></td>
						<td><i class="fa fa-fw fa-pull-right fa-lg fa-copy mo_copy copytooltip" onclick="copyToClipboard(this, \'#slo_url\', \'#slo_url_copy\');"><span id="slo_url_copy" class="copytooltiptext">Copy to Clipboard</span></i></td></tr></table>
						</td>

					</tr>


					<tr>
						<td style="width:40%; padding: 15px;"><b>Audience URI</b></td>
						<td style="width:60%; padding: 15px;"><table width="100%"><tr><td><span id="audience">' . $sp_entity_id . '</span></td>
						<td><i class="fa fa-fw fa-pull-right fa-lg fa-copy mo_copy copytooltip" onclick="copyToClipboard(this, \'#audience\',\'#audience_copy\');"><span id="audience_copy" class="copytooltiptext">Copy to Clipboard</span></i></td></tr></table>
						</td>

					</tr>
					<tr>
						<td style="width:40%; padding: 15px;"><b>NameID format</b></td>
						<td style="width:60%; padding: 15px;"><table width="100%"><tr><td><span id="nameid">urn:oasis:names:tc:SAML:' . $saml_nameid_format . '</span></td>
						<td><i class="fa fa-fw fa-pull-right fa-lg fa-copy mo_copy copytooltip" onclick="copyToClipboard(this, \'#nameid\', \'#nameid_copy\');"><span id="nameid_copy" class="copytooltiptext">Copy to Clipboard</span></i></td></tr></table>
						</td>

					</tr>
					<tr>
						<td style="width:40%; padding: 15px;"><b>Recipient URL</b></td>
						<td style="width:60%;  padding: 15px;"><table width="100%"><tr><td><span id="recipient">' . $sp_base_url . '/</span></td>
						<td><i class="fa fa-fw fa-pull-right fa-lg fa-copy mo_copy copytooltip" onclick="copyToClipboard(this, \'#recipient\',\'#recipient_copy\');"><span id="recipient_copy" class="copytooltiptext">Copy to Clipboard</span></i></td></tr></table>
						</td>

					</tr>
					<tr>
						<td style="width:40%; padding: 15px;"><b>Destination URL</b></td>
						<td style="width:60%;  padding: 15px;"><table width="100%"><tr><td><span id="destination">' . $sp_base_url . '/</span></td>
						<td><i class="fa fa-fw fa-pull-right fa-lg fa-copy mo_copy copytooltip" onclick="copyToClipboard(this, \'#destination\',\'#destination_copy\');"><span id="destination_copy" class="copytooltiptext">Copy to Clipboard</span></i></td></tr></table>
						</td>

					</tr>
					<tr>
						<td style="width:40%; padding: 15px;"><b>Default Relay State (Optional)</b></td>
						<td style="width:60%;  padding: 15px;"><table width="100%"><tr><td><span id="relaystate">' . $sp_base_url . '/</span></td>
						<td><i class="fa fa-fw fa-pull-right fa-lg fa-copy mo_copy copytooltip" onclick="copyToClipboard(this, \'#relaystate\', \'#relaystate_copy\');"><span id="relaystate_copy" class="copytooltiptext">Copy to Clipboard</span></i></td></tr></table>
						</td>

					</tr>
					<tr>
						<td style="width:40%; padding: 15px;"><b>Certificate (Optional)</b></td>
						<td style="width:60%;  padding: 15px;">
							<input type="button" class="button button-primary"';
							if(!mo_saml_is_customer_license_key_verified()) { echo ' disabled '; }
							echo $cert_download_query . ' value="Download"/>
						</td>

					</tr>
					</table>
					
	
	<tr>
		
		<td colspan="2">
		<div class="mySlides fade">
		
		<h3 style="text-align: center;">Configure Service Provider</h3>
		<h4>Assuming you are done with step 1 and you already have your Identity Provider\'s metadata, you can now proceed with configuring the Service Provider in either of the following two ways.</h4>
		<p style="text-align: center"><b>
		<a class="button button-primary button-large" href="' . $upload_metadata_query . '"  ' . mo_saml_is_customer_license_key_verified( true ) . '>
		Upload IDP Metadata</a></b></p>
		<p style="text-align: center;font-size: 13pt;font-weight: bold;">OR</p>
		<p style="text-align: center;"><b>
		<a href="' . $service_provider_query . '" class="button button-primary button-large"  ' . mo_saml_is_customer_license_key_verified( true ) . '>Manually configure SP</a> </b></p>
		</div>
		
		</td>
		
	</tr>
	
	<tr>
		<td>
			</br><br>
		</td>
	</tr>
	
		
	</table>
	</div>
	
	<br>

	<div style="position:relative;">
	<table width="98%" border="0" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:2%;">
		<tr>
	<td colspan="2">			
		<h3><b>Service Provider Endpoints:</b></h3>		
		<form width="98%" border="0" method="post" id="mo_saml_update_idp_settings_form" action="">';
		wp_nonce_field('mo_saml_update_idp_settings_option');
			echo '<input type="hidden" name="option" value="mo_saml_update_idp_settings_option" />
				<table width="98%">
					<tr>
						<td width="20%">SP Base URL:</td>
						<td width="70%"><input type="text" name="mo_saml_sp_base_url" placeholder="You site base URL" style="width: 95%;" value="' . $sp_base_url . '" required ' . mo_saml_is_customer_license_key_verified( true ) . '/></td>
					</tr>
					<tr>
						<td>SP EntityID / Issuer:</td>
						<td><input type="text" name="mo_saml_sp_entity_id" placeholder="You site base URL" style="width: 95%;" value="' . $sp_entity_id . '" required ' . mo_saml_is_customer_license_key_verified( true ) . '/></td>
					</tr>
						<tr>
							<td>
							</td>
							<td>
							<i><b>Note:</b> If you have already shared the above URLs or Metadata with your IdP, do <b>NOT</b> change SP EntityID. It might break your existing login flow.</i>
							</td>
						</tr>
					<tr>		
						<td colspan="2" style="text-align: center"><br><input type="submit" name="submit" style="width:100px;" value="Update" class="button button-primary button-large" ' . mo_saml_is_customer_license_key_verified( true ) . '/></td>
					</tr>
				</table>
		</form>
		</div>
		</td>
</tr>

	
</table>
		

<br>




<script>


 var progress_step1 = document.getElementById("progress_step1");
  var progress_step2 = document.getElementById("progress_step2");

var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  
  showSlides(slideIndex = n);
  
}

function changeTrackerforStep2(n) {
     progress_step1.classList.remove("is-active");
     progress_step1.classList.add("is-complete");
     progress_step2.classList.add("is-active");

     currentSlide(n);
}

function changeTrackerforStep1(n) {
  progress_step2.classList.remove("is-active");
  progress_step1.classList.remove("is-complete");
  progress_step1.classList.add("is-active");
  currentSlide(n);
}

function opacity() {
  if(slideIndex===1){
      document.getElementById("mo_prev").setAttribute("style","opacity:0");
      document.getElementById("mo_next").setAttribute("style","opacity:1.05");

	}
  else if(slideIndex===2) {      
      document.getElementById("mo_prev").setAttribute("style","opacity:1.05");
      document.getElementById("mo_next").setAttribute("style","opacity:0");}
	      
}
  

function showSlides(n) {
  var i;
  opacity();
  var slides = document.getElementsByClassName("mySlides");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
  }
  
  slides[slideIndex-1].style.display = "block";  
}

function copyToClipboard(copyButton, element, copyelement) {
	var temp = jQuery("<input>");
	jQuery("body").append(temp);
	temp.val(jQuery(element).text()).select();
	document.execCommand("copy");
	temp.remove();
	jQuery(copyelement).text("Copied");

	jQuery(copyButton).mouseout(function(){
		jQuery(copyelement).text("Copy to Clipboard");
	});
  }


jQuery("#idpguide").on("change", function() {
					var selectedIdp =  jQuery(this).find("option:selected").val();

						jQuery("#idpsetuplink").css("display","inline");
						selectedIdp = selectedIdp + "_as_idp.pdf";
						selectedIdp = "'. plugins_url('resources/idp-setup-guides/', __FILE__) .'" + selectedIdp;
						jQuery("#idpsetuplink").attr("href",selectedIdp);

				});
</script>
		


';

}
function mo_saml_download() {

	if ( array_key_exists( "option", $_POST ) && $_POST['option'] == 'mo_saml_download_cert' && check_admin_referer('mo_saml_download_cert')) {

		$content = get_option('mo_saml_current_cert');

		header('Content-Type: application/crt');
		header( 'Content-Disposition: attachment; filename="sp-certificate.crt"' );
		echo( $content );
		exit;
	}

    if ( array_key_exists( "option", $_POST ) && $_POST['option'] == 'mo_saml_download_new_cert' && check_admin_referer('mo_saml_download_new_cert')) {

        $cert = file_get_contents(plugin_dir_path(__DIR__) . 'resources' . DIRECTORY_SEPARATOR . 'miniorange_sp_2020.crt');
        header('Content-Type: application/crt');
        header( 'Content-Disposition: attachment; filename="miniorange_sp_2020.crt"' );
        echo( $cert );
        exit;
    }

	if ( array_key_exists( "option", $_POST ) && $_POST['option'] == 'mo_saml_download_metadata' && check_admin_referer('mo_saml_download_metadata') ) {
		miniorange_generate_metadata(true);
	}

    if ( array_key_exists( "option", $_POST ) && $_POST['option'] == 'mo_saml_download_new_metadata' && check_admin_referer('mo_saml_download_new_metadata') ) {
        miniorange_generate_metadata(true,true);
    }





}

