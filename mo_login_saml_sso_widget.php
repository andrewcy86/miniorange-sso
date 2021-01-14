<?php
include_once dirname(__FILE__) . '/Utilities.php';
include_once dirname(__FILE__) . '/Response.php';
include_once dirname(__FILE__) . '/LogoutRequest.php';
include_once 'xmlseclibs.php';
use \RobRichards\XMLSecLibs\XMLSecurityKey;
use \RobRichards\XMLSecLibs\XMLSecurityDSig;
use \RobRichards\XMLSecLibs\XMLSecEnc;
if(!class_exists("AESEncryption")) {
	require_once dirname(__FILE__) . '/includes/lib/encryption.php';
}

class mo_login_wid extends WP_Widget {
	public function __construct() {
		$identityName = get_option('saml_identity_name');
		parent::__construct(
	 		'Saml_Login_Widget',
			'Login with ' . $identityName,
			array( 'description' => __( 'This is a miniOrange SAML login widget.', 'mosaml' ), )
		);
	 }


	public function widget( $args, $instance ) {
		extract( $args );

		$wid_title = apply_filters( 'widget_title', $instance['wid_title'] );

		echo $args['before_widget'];
		if ( ! empty( $wid_title ) )
			echo $args['before_title'] . $wid_title . $args['after_title'];
			$this->loginForm();
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['wid_title'] = strip_tags( $new_instance['wid_title'] );
		return $instance;
	}


	public function form( $instance ) {
		$wid_title = '';
		if(array_key_exists('wid_title', $instance))
			$wid_title = $instance[ 'wid_title' ];
		echo '
		<p><label for="'. $this->get_field_id('wid_title').' ">'. _e('Title:').' </label>
		<input class="widefat" id="'.$this->get_field_id('wid_title').'" name="'. $this->get_field_name('wid_title').'" type="text" value="'.$wid_title.'" />
		</p>';
	}

	public function loginForm(){
		global $post;

		if(!is_user_logged_in()){
			$redirect_to = saml_get_current_page_url();
		echo '
		<script>
		function submitSamlForm(){ document.getElementById("miniorange-saml-sp-sso-login-form").submit(); }
		</script>
		<form name="miniorange-saml-sp-sso-login-form" id="miniorange-saml-sp-sso-login-form" method="post" action="">
		<input type="hidden" name="option" value="saml_user_login" />
		<input type="hidden" name="redirect_to" value="' . $redirect_to . '" />

		<font size="+1" style="vertical-align:top;"> </font>';
		$identity_provider = get_option('saml_identity_name');

		if(!empty($identity_provider)){
			$login_title='Login with ##IDP##';
			if(get_option('mo_saml_custom_login_text')) {
				$login_title = get_option('mo_saml_custom_login_text');
			}
			$login_title = str_replace("##IDP##", $identity_provider, $login_title);

			$use_button = false;
			if(get_option('mo_saml_use_button_as_widget')){
				if(get_option('mo_saml_use_button_as_widget') == "true"){
					$use_button = true;
				}
			}
			if($use_button){
				$customWidth = get_option('mo_saml_button_width') ? get_option('mo_saml_button_width') : '100';
				$customHeight = get_option('mo_saml_button_height') ? get_option('mo_saml_button_height') : '50';
				$customSize = get_option('mo_saml_button_size') ? get_option('mo_saml_button_size') : '50';
				$customCurve = get_option('mo_saml_button_curve') ? get_option('mo_saml_button_curve') : '5';
				$customButtonColor = get_option('mo_saml_button_color') ? get_option('mo_saml_button_color') : '0085ba';
				$customTheme = get_option('mo_saml_button_theme') ? get_option('mo_saml_button_theme') : 'longbutton';
				$customButtonText = get_option('mo_saml_button_text') ? get_option('mo_saml_button_text') : (get_option('saml_identity_name') ? get_option('saml_identity_name') : 'Login');
				$customTextColor=get_option('mo_saml_font_color') ? get_option('mo_saml_font_color') : 'ffffff';
				$customeFontSize = get_option('mo_saml_font_size') ? get_option('mo_saml_font_size') : '20';
				$login_title = '<input type="button" name="mo_saml_wp_sso_button" value="' . $customButtonText .'" style="';
				$style ='';
				if($customTheme == "longbutton"){
					$style = $style . 'width:' . $customWidth . 'px;';
					$style = $style . 'height:' . $customHeight .'px;';
					$style = $style . 'border-radius:' . $customCurve . 'px;';
				} else {
					if($customTheme == "circle"){
						$style = $style . 'width:' . $customSize . 'px;';
						$style = $style . 'height:' . $customSize . 'px;';
						$style = $style . 'border-radius:999px;';
					} elseif($customTheme == "oval"){
						$style = $style . 'width:' . $customSize . 'px;';
						$style = $style . 'height:' . $customSize . 'px;';
						$style = $style . 'border-radius:5px;';
					} elseif($customTheme == "square"){
						$style = $style . 'width:' . $customSize . 'px;';
						$style = $style . 'height:' . $customSize . 'px;';
						$style = $style . 'border-radius:0px;';
					}
				}
				$style = $style . 'background-color:#' . $customButtonColor . ';';
				$style = $style . 'border-color:transparent;';
				$style = $style . 'color:#' . $customTextColor . ';';
				$style = $style . 'font-size:' . $customeFontSize . 'px;';
				$style = $style . 'padding:0px;';
				$login_title = $login_title . $style . '"/>';

			}


				?> <a href="#" onClick="submitSamlForm()"><?php echo $login_title;?></a></form> <?php
		} else
			echo "Please configure the miniOrange SAML Plugin first.";

		if( ! $this->mo_saml_check_empty_or_null_val(get_option('mo_saml_redirect_error_code')))
		{

			echo '<div></div><div title="Login Error"><font color="red">We could not sign you in. Please contact your Administrator.</font></div>';

				delete_option('mo_saml_redirect_error_code');
				delete_option('mo_saml_redirect_error_reason');
		}
		echo
		'	</ul>
		</form>';

		} else {
		$current_user = wp_get_current_user();
		$custom_greeting_text = "Hello,";
		if(get_option('mo_saml_custom_greeting_text')){
			$custom_greeting_text = get_option('mo_saml_custom_greeting_text');
		}
		$custom_greeting_name = '';
		if(get_option('mo_saml_greeting_name')){
			switch(get_option('mo_saml_greeting_name')){
				case 'USERNAME':
					$custom_greeting_name = $current_user->user_login;
					break;
				case 'EMAIL':
					$custom_greeting_name = $current_user->user_email;
					break;
				case 'FNAME':
					$custom_greeting_name = $current_user->user_firstname;
					break;
				case 'LNAME':
					$custom_greeting_name = $current_user->user_lastname;
					break;
				case 'FNAME_LNAME':
					$custom_greeting_name = $current_user->user_firstname . ' ' . $current_user->user_lastname;
					break;
				case 'LNAME_FNAME':
					$custom_greeting_name = $current_user->user_lastname . ' ' . $current_user->user_firstname;
					break;
				default:
					$custom_greeting_name = $current_user->user_login;
			}
		}
		$custom_greeting_name = trim($custom_greeting_name);
		if(empty($custom_greeting_name)){
			$custom_greeting_name = $current_user->user_login;
		}
		$custom_greeting = $custom_greeting_text . ' ' . $custom_greeting_name;
		$custom_logout = "Logout";
		if(get_option('mo_saml_custom_logout_text'))
			$custom_logout = get_option('mo_saml_custom_logout_text');
		echo $custom_greeting.' | <a href="'.wp_logout_url(home_url()).'" title="logout" >'.$custom_logout.'</a></li>';

		}


	}

	public function mo_saml_check_empty_or_null_val( $value ) {
	if( ! isset( $value ) || empty( $value ) ) {
		return true;
	}
	return false;
	}

	public function mo_saml_widget_init(){
        if(isset($_REQUEST['option']) and $_REQUEST['option'] == 'saml_user_logout'){
            $user = is_user_logged_in() ? wp_get_current_user() : null;
            $this->mo_saml_logout('','',$user);
        }

    }

    function mo_saml_logout($redirect_to, $requested_redirect_to, $user) {
            $logout_url = get_option('saml_logout_url');
            $logout_binding_type = get_option('saml_logout_binding_type');
            $referer_url = wp_get_referer();
            $sp_base_url = get_option('mo_saml_sp_base_url');
            if(empty($referer_url))
                $referer_url = !empty($sp_base_url) ? $sp_base_url : home_url();
            if( !empty($logout_url) )  {
                if( ! session_id() || session_id() == '' || !isset($_SESSION) ) {
                    session_start();
                }
                if(isset($_SESSION['mo_saml_logout_request'])) {
                    self::createLogoutResponseAndRedirect($logout_url, $logout_binding_type);
                    exit();
                } elseif (isset($_SESSION['mo_saml']['logged_in_with_idp'])) {

                    $current_user = $user;
                    $nameId = get_user_meta( $current_user->ID, 'mo_saml_name_id');
                    $sessionIndex = get_user_meta( $current_user->ID, 'mo_saml_session_index');

                    if(!empty($nameId)) {
                        mo_saml_create_logout_request($nameId, $sessionIndex, $logout_url, $logout_binding_type, $referer_url);
                        unset($_SESSION['mo_saml']);
                    }
                }
            }
            return $referer_url;
    }

	function createLogoutResponseAndRedirect( $logout_url, $logout_binding_type ) {
		$sp_base_url = get_option('mo_saml_sp_base_url');
		if(empty($sp_base_url)) {
			$sp_base_url = home_url();
		}
		$logout_request = $_SESSION['mo_saml_logout_request'];
		$relay_state = $_SESSION['mo_saml_logout_relay_state'];
		unset($_SESSION['mo_saml_logout_request']);
		unset($_SESSION['mo_saml_logout_relay_state']);
		$document = new DOMDocument();
		$document->loadXML($logout_request);
		$logout_request = $document->firstChild;
		if( $logout_request->localName == 'LogoutRequest' ) {
			$logoutRequest = new SAML2SPLogoutRequest( $logout_request );
			$sp_entity_id = get_option('mo_saml_sp_entity_id');
			if(empty($sp_entity_id)) {
				$sp_entity_id = $sp_base_url.'/wp-content/plugins/miniorange-saml-20-single-sign-on/';
			}
			//$sp_entity_id = $sp_base_url.'/wp-content/plugins/miniorange-saml-20-single-sign-on/';
			$destination = $logout_url;
			$logoutResponse = SAMLSPUtilities::createLogoutResponse($logoutRequest->getId(), $sp_entity_id, $destination, $logout_binding_type);

			if(empty($logout_binding_type) || $logout_binding_type == 'HttpRedirect') {
				$redirect = $logout_url;
				if (strpos($logout_url,'?') !== false) {
					$redirect .= '&';
				} else {
					$redirect .= '?';
				}
				if(get_option('saml_request_signed')!='checked'){
					$redirect .= 'SAMLResponse=' . $logoutResponse . '&RelayState=' . urlencode($relay_state);
					header('Location: '.$redirect);
					exit();
				}
				$samlRequest = 'SAMLResponse=' . $logoutResponse . '&RelayState=' . urlencode($relay_state) . '&SigAlg='. urlencode(XMLSecurityKey::RSA_SHA256);
				$param =array( 'type' => 'private');
				$key = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, $param);
				//$certFilePath = plugins_url('resources/sp-key.key', __FILE__) ;
				//$certFilePath = plugin_dir_path(__FILE__) . 'resources' . DIRECTORY_SEPARATOR . 'sp-key.key';
				$certFilePath = get_option( 'mo_saml_current_cert_private_key' );
				// $key->loadKey($certFilePath, TRUE);
				$key->loadKey($certFilePath, FALSE);
				$objXmlSecDSig = new XMLSecurityDSig();
				$signature = $key->signData($samlRequest);
				$signature = base64_encode($signature);
				$redirect .= $samlRequest . '&Signature=' . urlencode($signature);
				header('Location: '.$redirect);
				exit();
			} else {
				if(get_option('saml_request_signed')!='checked'){
					$base64EncodedXML = base64_encode($logoutResponse);
					SAMLSPUtilities::postSAMLResponse($logout_url, $base64EncodedXML, $relay_state);
					exit();
				}
				$privateKeyPath = "";
				$publicCertPath = "";
				$base64EncodedXML = SAMLSPUtilities::signXML( $logoutResponse, 'Status' );

				SAMLSPUtilities::postSAMLResponse($logout_url, $base64EncodedXML, $relay_state);
			}

		}
	}
}

function mo_saml_create_logout_request($nameId, $sessionIndex, $logout_url, $logout_binding_type, $referer_url){
    $sp_base_url = get_option('mo_saml_sp_base_url');
    if(empty($sp_base_url)) {
        $sp_base_url = home_url();
    }
    $sp_entity_id = get_option('mo_saml_sp_entity_id');
    if(empty($sp_entity_id)) {
        $sp_entity_id = $sp_base_url.'/wp-content/plugins/miniorange-saml-20-single-sign-on/';
    }
    //$sp_entity_id = $sp_base_url.'/wp-content/plugins/miniorange-saml-20-single-sign-on/';
    $destination = $logout_url;
    $sendRelayState = $referer_url;

    $sendRelayState = mo_saml_get_relay_state($sendRelayState);
    $samlRequest = SAMLSPUtilities::createLogoutRequest($nameId, $sessionIndex, $sp_entity_id, $destination, $logout_binding_type);

    if(empty($logout_binding_type) || $logout_binding_type == 'HttpRedirect') {
        $redirect = $logout_url;
        if (strpos($logout_url,'?') !== false) {
            $redirect .= '&';
        } else {
            $redirect .= '?';
        }
        if(get_option('saml_request_signed')!='checked'){
            $redirect .= 'SAMLRequest=' . $samlRequest . '&RelayState=' . urlencode($sendRelayState);
            header('Location: '.$redirect);
            exit();
        }
        $samlRequest = 'SAMLRequest=' . $samlRequest . '&RelayState=' . urlencode($sendRelayState) . '&SigAlg='. urlencode(XMLSecurityKey::RSA_SHA256);
        $param =array( 'type' => 'private');
        $key = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, $param);
        //$certFilePath = plugins_url('resources/sp-key.key', __FILE__) ;
        //$certFilePath = plugin_dir_path(__FILE__) . 'resources' . DIRECTORY_SEPARATOR . 'sp-key.key';
        $certFilePath = get_option( 'mo_saml_current_cert_private_key' );
        //$key->loadKey($certFilePath, TRUE);
        $key->loadKey($certFilePath,FALSE);

        $objXmlSecDSig = new XMLSecurityDSig();
        $signature = $key->signData($samlRequest);
        $signature = base64_encode($signature);

        $redirect .= $samlRequest . '&Signature=' . urlencode($signature);

        header('Location: '.$redirect);
        exit();
    } else {
        if(get_option('saml_request_signed')!='checked'){
            $base64EncodedXML = base64_encode($samlRequest);
            SAMLSPUtilities::postSAMLRequest($logout_url, $base64EncodedXML, $sendRelayState);
            exit();
        }

        $privateKeyPath = "";
        $publicCertPath = "";
        $base64EncodedXML = SAMLSPUtilities::signXML( $samlRequest, 'NameID' );

        SAMLSPUtilities::postSAMLRequest($logout_url, $base64EncodedXML, $sendRelayState);
    }
}

function mo_login_validate(){

	if(isset($_REQUEST['option']) && $_REQUEST['option'] == 'mosaml_metadata'){
		miniorange_generate_metadata();
	}

	if(isset($_REQUEST['option']) && $_REQUEST['option'] == 'export_configuration'){
        if ( current_user_can( 'manage_options' ) )
            miniorange_import_export(true);
        exit;
	}

	if(mo_saml_is_customer_license_verified()){
	if((isset($_REQUEST['option']) && $_REQUEST['option'] == 'saml_user_login') || (isset($_REQUEST['option']) && $_REQUEST['option'] == 'testidpconfig')|| (isset($_REQUEST['option']) && $_REQUEST['option'] == 'getsamlrequest')|| (isset($_REQUEST['option']) && $_REQUEST['option'] == 'getsamlresponse')){

		if(mo_saml_is_sp_configured() ) {
			if(is_user_logged_in() && $_REQUEST['option'] == 'saml_user_login'){
				if ( isset( $_REQUEST['redirect_to']) ){
					$redirect_url = htmlspecialchars($_REQUEST['redirect_to']);
					header('Location: '.$redirect_url);
					exit();
				}
				return;
			}
			$sp_base_url = get_option('mo_saml_sp_base_url');
			if(empty($sp_base_url)) {
				$sp_base_url = home_url();
			}

           if($_REQUEST['option'] == 'testidpconfig' and array_key_exists('newcert',$_REQUEST)){
                $sendRelayState = 'testNewCertificate';
            }
           else if($_REQUEST['option'] == 'testidpconfig'){
               $sendRelayState = 'testValidate';
           }
			else if($_REQUEST['option'] == 'getsamlrequest')
				$sendRelayState = 'displaySAMLRequest';
			else if($_REQUEST['option'] == 'getsamlresponse')
				$sendRelayState = 'displaySAMLResponse';
			else if(get_option('mo_saml_relay_state') && get_option('mo_saml_relay_state')!=''){
					$sendRelayState=get_option('mo_saml_relay_state');
				}
			else if ( isset( $_REQUEST['redirect_to']) )
				$sendRelayState = htmlspecialchars($_REQUEST['redirect_to']);
			else
				$sendRelayState = wp_get_referer();

			if(empty($sendRelayState))
			    $sendRelayState = $sp_base_url;


				$ssoUrl = html_entity_decode(get_option("saml_login_url"));
				$sso_binding_type = get_option("saml_login_binding_type");
				$force_authn = get_option('mo_saml_force_authentication');
				$acsUrl = $sp_base_url . "/";
				$sp_entity_id = get_option('mo_saml_sp_entity_id');
				$saml_nameid_format = get_option('saml_nameid_format');
                if(empty($saml_nameid_format))
                    $saml_nameid_format = '1.1:nameid-format:unspecified';
				if(empty($sp_entity_id)) {
					$sp_entity_id = $sp_base_url.'/wp-content/plugins/miniorange-saml-20-single-sign-on/';
				}
				//$sp_entity_id = $sp_base_url.'/wp-content/plugins/miniorange-saml-20-single-sign-on/';
				$samlRequest = SAMLSPUtilities::createAuthnRequest($acsUrl, $sp_entity_id, $ssoUrl, $force_authn, $sso_binding_type, $saml_nameid_format);
				if( $sendRelayState == 'displaySAMLRequest' )
					mo_saml_show_SAML_log(SAMLSPUtilities::createAuthnRequest($acsUrl, $sp_entity_id, $ssoUrl, $force_authn,'HTTPPost', $saml_nameid_format ),$sendRelayState);
				$redirect = $ssoUrl;
				if (strpos($ssoUrl,'?') !== false) {
					$redirect .= '&';
				} else {
					$redirect .= '?';
				}
				cldjkasjdksalc();
				//Parsed relay state URL to get URI
                $sendRelayState=mo_saml_get_relay_state($sendRelayState);
				$sendRelayState = empty($sendRelayState) ? "/" : $sendRelayState;
				if(empty($sso_binding_type) || $sso_binding_type == 'HttpRedirect') {
					if(get_option('saml_request_signed')!='checked'){
						$redirect .= 'SAMLRequest=' . $samlRequest . '&RelayState=' . urlencode($sendRelayState);
						header('Location: '.$redirect);
						exit();
					}
					$samlRequest = "SAMLRequest=" . $samlRequest . "&RelayState=" . urlencode($sendRelayState) . '&SigAlg='. urlencode(XMLSecurityKey::RSA_SHA256);
					$param =array( 'type' => 'private');
                    $key = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, $param);

                    if($_REQUEST['option'] == 'testidpconfig' && array_key_exists('newcert',$_REQUEST))
                        $certFilePath = file_get_contents(plugin_dir_path(__FILE__) . 'resources' . DIRECTORY_SEPARATOR . 'miniorange_sp_2020_priv.key');
                    else
                        $certFilePath = get_option( 'mo_saml_current_cert_private_key' );

					$key->loadKey($certFilePath, FALSE);
					$objXmlSecDSig = new XMLSecurityDSig();
					$signature = $key->signData($samlRequest);
					$signature = base64_encode($signature);
					/*$redirect = $ssoUrl;
					if (strpos($ssoUrl,'?') !== false) {
						$redirect .= '&';
					} else {
						$redirect .= '?';
					}*/
					$redirect .= $samlRequest . '&Signature=' . urlencode($signature);


					header('Location: '.$redirect);
					exit();
				} else {
					if(get_option('saml_request_signed')!='checked'){
							$base64EncodedXML = base64_encode($samlRequest);
							SAMLSPUtilities::postSAMLRequest($ssoUrl, $base64EncodedXML, $sendRelayState);
							exit();
					}

					$privateKeyPath = "";
					$publicCertPath = "";
                    if($_REQUEST['option'] == 'testidpconfig' && array_key_exists('newcert',$_REQUEST)) {
                        $base64EncodedXML = SAMLSPUtilities::signXML($samlRequest, 'NameIDPolicy', true);
                    } else {
                        $base64EncodedXML = SAMLSPUtilities::signXML($samlRequest, 'NameIDPolicy');
                    }

					SAMLSPUtilities::postSAMLRequest($ssoUrl, $base64EncodedXML, $sendRelayState);
                    update_option('mo_saml_new_cert_test', true);
				}
			// } else {
			// 	$mo_redirect_url = get_option('mo_saml_host_name') . "/moas/rest/saml/request?id=" . get_option('mo_saml_admin_customer_key') . "&returnurl=" . urlencode( home_url() . "/?option=readsamllogin&redirect_to=" . urlencode ($sendRelayState) );
			// 	header('Location: ' . $mo_redirect_url);
			// 	exit();
			// }
		}
	}
	if(array_key_exists('SAMLResponse', $_REQUEST) && !empty($_REQUEST['SAMLResponse'])) {
		if(array_key_exists('RelayState', $_POST) && !empty( $_POST['RelayState'] ) && $_POST['RelayState'] != '/') {
			$relayState = $_POST['RelayState'];
		} else {
			$relayState = '';
		}
		$sp_base_url = get_option('mo_saml_sp_base_url');
		if(empty($sp_base_url)) {
			$sp_base_url = home_url();
		}
		$samlResponse = htmlspecialchars($_REQUEST['SAMLResponse']);
		$samlResponse = base64_decode($samlResponse);
		if($relayState=='displaySAMLResponse'){
			mo_saml_show_SAML_log($samlResponse,$relayState);
		}
		if(array_key_exists('SAMLResponse', $_GET) && !empty($_GET['SAMLResponse'])) {
			$samlResponse = gzinflate($samlResponse);
		}

		$document = new DOMDocument();
		$document->loadXML($samlResponse);
		$samlResponseXml = $document->firstChild;
		$doc = $document->documentElement;
		$xpath = new DOMXpath($document);
		$xpath->registerNamespace('samlp', 'urn:oasis:names:tc:SAML:2.0:protocol');
		$xpath->registerNamespace('saml', 'urn:oasis:names:tc:SAML:2.0:assertion');


		if($samlResponseXml->localName == 'LogoutResponse') {
			if(isset($_REQUEST['RelayState']))
				$relay_state = $_REQUEST['RelayState'];
			$logout_relay_state = get_option('mo_saml_logout_relay_state');
			if(!empty($logout_relay_state))
                $relay_state = $logout_relay_state;
			wp_logout();
			
			if(empty($relay_state))
				$relay_state = home_url();
			
			header('Location: ' . $relay_state);
			exit;
		}
		else {
			// It's a SAML Assertion
			$status = $xpath->query('/samlp:Response/samlp:Status/samlp:StatusCode', $doc);
			$statusString = $status->item(0)->getAttribute('Value');
			$statusMessage=$xpath->query('/samlp:Response/samlp:Status/samlp:StatusMessage', $doc)->item(0);
			if(!empty($statusMessage))
				$statusMessage = $statusMessage->nodeValue;
			$statusarray = explode(":",$statusString);
			$status = $statusarray[7];
			if(array_key_exists('RelayState', $_POST) && !empty( $_POST['RelayState'] ) && $_POST['RelayState'] != '/') {
				$relayState = $_POST['RelayState'];
			} else {
				$relayState = '';
			}
			if($status!="Success"){
				show_status_error($status,$relayState, $statusMessage);

			}

			$certFromPlugin = maybe_unserialize(get_option('saml_x509_certificate'));
			$acsUrl = $sp_base_url .'/';
			update_option('mo_saml_response', base64_encode($samlResponse));
			if($relayState == 'testNewCertificate'){
			    $latest_private_key = file_get_contents(plugin_dir_path(__FILE__) . 'resources' . DIRECTORY_SEPARATOR . 'miniorange_sp_2020_priv.key');
                $samlResponse = new SAML2SPResponse($samlResponseXml,$latest_private_key);
            }
			else
			    $samlResponse = new SAML2SPResponse($samlResponseXml,get_option('mo_saml_current_cert_private_key'));
			$responseSignatureData = $samlResponse->getSignatureData();
			$assertionSignatureData = current($samlResponse->getAssertions())->getSignatureData();

			if(empty($assertionSignatureData) && empty($responseSignatureData) ) {

				if($relayState=='testValidate' or $relayState =='testNewCertificate'){
					$Error_message=mo_options_error_constants::Error_no_certificate;
					$Cause_message = mo_options_error_constants::Cause_no_certificate;
					echo '<div style="font-family:Calibri;padding:0 3%;">
				<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
				<div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error  :'.$Error_message.' </strong></p>
				
				<p><strong>Possible Cause: '.$Cause_message.'</strong></p>
				
				</div></div>';
					mo_saml_download_logs($Error_message,$Cause_message);
					exit;
				} else {
					wp_die('We could not sign you in. Please contact administrator','Error: Invalid SAML Response');
				}


			}

			$validSignature = '';

			if(is_array($certFromPlugin)) {
				foreach ($certFromPlugin as $key => $value) {
					$certfpFromPlugin = XMLSecurityKey::getRawThumbprint($value);

					/* convert to UTF-8 character encoding*/
					$certfpFromPlugin = mo_saml_convert_to_windows_iconv($certfpFromPlugin);

					/* remove whitespaces */
					$certfpFromPlugin = preg_replace('/\s+/', '', $certfpFromPlugin);

					/* Validate signature */
					if(!empty($responseSignatureData)) {
						$validSignature = SAMLSPUtilities::processResponse($acsUrl, $certfpFromPlugin, $responseSignatureData, $samlResponse, $key, $relayState);
					}

					if(!empty($assertionSignatureData)) {
						$validSignature = SAMLSPUtilities::processResponse($acsUrl, $certfpFromPlugin, $assertionSignatureData, $samlResponse, $key, $relayState);
					}

					if($validSignature)
						break;
				}
			}
			else {
				$certfpFromPlugin = XMLSecurityKey::getRawThumbprint($certFromPlugin);

				/* convert to UTF-8 character encoding*/
				$certfpFromPlugin = mo_saml_convert_to_windows_iconv($certfpFromPlugin);

				/* remove whitespaces */
				$certfpFromPlugin = preg_replace('/\s+/', '', $certfpFromPlugin);

				/* Validate signature */
				if(!empty($responseSignatureData)) {
					$validSignature = SAMLSPUtilities::processResponse($acsUrl, $certfpFromPlugin, $responseSignatureData, $samlResponse, 0, $relayState);
				}

				if(!empty($assertionSignatureData)) {
					$validSignature = SAMLSPUtilities::processResponse($acsUrl, $certfpFromPlugin, $assertionSignatureData, $samlResponse, 0, $relayState);
				}
			}

			if($responseSignatureData)
				$saml_required_certificate=$responseSignatureData['Certificates'][0];
			elseif($assertionSignatureData)
				$saml_required_certificate=$assertionSignatureData['Certificates'][0];

		if(!$validSignature) {
			if($relayState=='testValidate' or $relayState =='testNewCertificate'){

				$Error_message=mo_options_error_constants::Error_wrong_certificate;
				$Cause_message = mo_options_error_constants::Cause_wrong_certificate;
				$pem = "-----BEGIN CERTIFICATE-----<br>" .
					chunk_split($saml_required_certificate, 64) .
					"<br>-----END CERTIFICATE-----";
				echo '<div style="font-family:Calibri;padding:0 3%;">';
			echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
			<div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong>Unable to find a certificate matching the configured fingerprint.</p>
			<p>Please contact your administrator and report the following error:</p>
			<p><strong>Possible Cause: </strong>\'X.509 Certificate\' field in plugin does not match the certificate found in SAML Response.</p>
			<p><strong>Certificate found in SAML Response: </strong><font face="Courier New";font-size:10pt><br><br>'.$pem.'</p></font>
			<p><strong>Solution: </strong></p>
			 <ol>
                <li>Copy paste the certificate provided above in X509 Certificate under Service Provider Setup tab.</li>
                <li>If issue persists disable <b>Character encoding</b> under Service Provder Setup tab.</li>
             </ol>		
			</div>
					<div style="margin:3%;display:block;text-align:center;">
					<div style="margin:3%;display:block;text-align:center;"><input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Done" onClick="self.close();"></div>';
					mo_saml_download_logs($Error_message,$Cause_message);
					exit;
	}
		else
		{
			wp_die('We could not sign you in. Please contact your administrator','Error: Invalid SAML Response');
		}
			}

			// verify the issuer and audience from saml response
			$issuer = get_option('saml_issuer');
			$sp_entity_id = get_option('mo_saml_sp_entity_id');
			if(empty($sp_entity_id)) {
				$sp_entity_id = $sp_base_url.'/wp-content/plugins/miniorange-saml-20-single-sign-on/';
			}
			//$sp_entity_id = $sp_base_url.'/wp-content/plugins/miniorange-saml-20-single-sign-on/';

			SAMLSPUtilities::validateIssuerAndAudience($samlResponse,$sp_entity_id, $issuer, $relayState);

			$ssoemail = current(current($samlResponse->getAssertions())->getNameId());
			$attrs = current($samlResponse->getAssertions())->getAttributes();
			$attrs['NameID'] = array("0" => $ssoemail);
			$sessionIndex = current($samlResponse->getAssertions())->getSessionIndex();

			mo_saml_checkMapping($attrs,$relayState,$sessionIndex);
		}
	}
	if(array_key_exists('SAMLRequest', $_REQUEST) && !empty($_REQUEST['SAMLRequest'])) {
	    $samlRequest = htmlspecialchars($_REQUEST['SAMLRequest']);
		$relayState = '/';
		if(array_key_exists('RelayState', $_REQUEST)) {
			$relayState = $_REQUEST['RelayState'];
		}

		$samlRequest = base64_decode($samlRequest);
		if(array_key_exists('SAMLRequest', $_GET) && !empty($_GET['SAMLRequest'])) {
			$samlRequest = gzinflate($samlRequest);
		}

		$document = new DOMDocument();
		$document->loadXML($samlRequest);
		$samlRequestXML = $document->firstChild;
		if( $samlRequestXML->localName == 'LogoutRequest' ) {
			$logoutRequest = new SAML2SPLogoutRequest( $samlRequestXML );
			if( ! session_id() || session_id() == '' || !isset($_SESSION) ) {
				session_start();
			}
			$_SESSION['mo_saml_logout_request'] = $samlRequest;
			$_SESSION['mo_saml_logout_relay_state'] = $relayState;
            wp_redirect(htmlspecialchars_decode(wp_logout_url()));
            exit;
		}
	}
	if( isset( $_REQUEST['option'] ) and strpos( $_REQUEST['option'], 'readsamllogin' ) !== false ) {
		// Get the email of the user.
		require_once dirname(__FILE__) . '/includes/lib/encryption.php';

		if(isset($_POST['STATUS']) && $_POST['STATUS'] == 'ERROR')
		{
			update_option('mo_saml_redirect_error_code', htmlspecialchars($_POST['ERROR_REASON']));
			update_option('mo_saml_redirect_error_reason' , htmlspecialchars($_POST['ERROR_MESSAGE']));
		}
		else if(isset($_POST['STATUS']) && $_POST['STATUS'] == 'SUCCESS'){
			$redirect_to = '';
			if(isset($_REQUEST['redirect_to']) && !empty($_REQUEST['redirect_to']) && $_REQUEST['redirect_to'] != '/') {
				$redirect_to = htmlspecialchars($_REQUEST['redirect_to']);
			}

			delete_option('mo_saml_redirect_error_code');
			delete_option('mo_saml_redirect_error_reason');

			try {

				//Get enrypted user_email
				$emailAttribute = get_option('saml_am_email');
				$usernameAttribute = get_option('saml_am_username');
				$firstName = get_option('saml_am_first_name');
				$lastName = get_option('saml_am_last_name');
				$groupName = get_option('saml_am_group_name');
				$defaultRole = get_option('saml_am_default_user_role');
				$dontAllowUnlistedUserRole = get_option('saml_am_dont_allow_unlisted_user_role');
				$checkIfMatchBy = get_option('saml_am_account_matcher');

				$user_email = '';
				$userName = '';
				//Attribute mapping. Check if Match/Create user is by username/email:

				$firstName = str_replace(".", "_", $firstName);
				$firstName = str_replace(" ", "_", $firstName);
				if(!empty($firstName) && array_key_exists($firstName, $_POST) ) {
					$firstName = htmlspecialchars($_POST[$firstName]);
				}

				$lastName = str_replace(".", "_", $lastName);
				$lastName = str_replace(" ", "_", $lastName);
				if(!empty($lastName) && array_key_exists($lastName, $_POST) ) {
					$lastName = htmlspecialchars($_POST[$lastName]);
				}

				$usernameAttribute = str_replace(".", "_", $usernameAttribute);
				$usernameAttribute = str_replace(" ", "_", $usernameAttribute);
				if(!empty($usernameAttribute) && array_key_exists($usernameAttribute, $_POST)) {
					$userName = htmlspecialchars($_POST[$usernameAttribute]);
				} else {
					$userName = htmlspecialchars($_POST['NameID']);
				}

				$user_email = str_replace(".", "_", $emailAttribute);
				$user_email = str_replace(" ", "_", $emailAttribute);
				if(!empty($emailAttribute) && array_key_exists($emailAttribute, $_POST)) {
					$user_email = htmlspecialchars($_POST[$emailAttribute]);
				} else {
					$user_email = htmlspecialchars($_POST['NameID']);
				}

				$groupName = str_replace(".", "_", $groupName);
				$groupName = str_replace(" ", "_", $groupName);
				if(!empty($groupName) && array_key_exists($groupName, $_POST) ) {
					$groupName = htmlspecialchars($_POST[$groupName]);
				}


				if(empty($checkIfMatchBy)) {
					$checkIfMatchBy = "email";
				}

				//Get customer token as a key to decrypt email
				$key = get_option('mo_saml_customer_token');

				if(isset($key) || trim($key) != '')
				{
					$deciphertext = AESEncryption::decrypt_data($user_email, $key);
					$user_email = $deciphertext;
				}

				//Decrypt firstname and lastName and username

				if(!empty($firstName) && !empty($key))
				{
					$decipherFirstName = AESEncryption::decrypt_data($firstName, $key);
					$firstName = $decipherFirstName;
				}
				if(!empty($lastName) && !empty($key))
				{
					$decipherLastName = AESEncryption::decrypt_data($lastName, $key);
					$lastName = $decipherLastName;
				}
				if(!empty($userName) && !empty($key))
				{
					$decipherUserName = AESEncryption::decrypt_data($userName, $key);
					$userName = $decipherUserName;
				}
				if(!empty($groupName) && !empty($key))
				{
					$decipherGroupName = AESEncryption::decrypt_data($groupName, $key);
					$groupName = $decipherGroupName;
				}


			}
			catch (Exception $e) {
				echo sprintf("An error occurred while processing the SAML Response.");
				exit;
			}
			$groupArray = array ( $groupName );
			mo_saml_login_user($user_email,$firstName,$lastName,$userName, $groupArray, $dontAllowUnlistedUserRole, $defaultRole,$redirect_to, $checkIfMatchBy);
		}

	}
	}
}

function cldjkasjdksalc(){

    $dir = plugin_dir_path( __FILE__ );

    //upload path of the directory
    $upload_dir   = wp_upload_dir();

    //Fetching the home url of the site
    $home_url = home_url();
    $home_url = trim($home_url, '/');
    if (!preg_match('#^http(s)?://#', $home_url)) {
        $home_url = 'http://' . $home_url;
    }
    $urlParts = parse_url($home_url);
    $domain = preg_replace('/^www\./', '', $urlParts['host']);

    //Combining the domain with upload path and getting the hash of it
    $asdjka = $domain . '-' . $upload_dir['basedir'];
    $dmndsasd = hash_hmac('sha256', $asdjka, '4DHfjgfjasndfsajfHGJ');

    //Getting the license file contents either from license or table
    if(is_writable ( $dir . 'license')) {
        $file = file_get_contents($dir . 'license');
		//encode the file or table content
		if($file){
			$encoded = base64_encode($file);
		}
    }else{
		$opt_val = base64_decode('bGNkamthc2pka3NhY2w=');
        $file = get_option($opt_val);
		if(!empty($file)){
			$encoded = str_rot13($file);
		}
    }

    //If the file or table content is empty then show the license error message
    if(empty($file)){
        $decoded_data = base64_decode('TGljZW5zZSBGaWxlIG1pc3NpbmcgZnJvbSB0aGUgcGx1Z2luLg==');
        wp_die($decoded_data);
    }

    //comapring the hash with the file contents
    if (strpos($encoded, $dmndsasd) !== false) {
        return true;
    }else {
        //If fails then calling the miniOrange server to check the license
        $customer = new Customersaml();
        $key = get_option('mo_saml_customer_token');
        $code = AESEncryption::decrypt_data(get_option('sml_lk'),$key);
		$content = $customer->mo_saml_vl($code, false);
		if(!$content)
			return;
		$content = json_decode( $content, true );

        if (strcasecmp($content['status'], 'SUCCESS') == 0) {
            $dir = plugin_dir_path( __FILE__ );

            $home_url = home_url();
            $home_url = trim($home_url, '/');
            if (!preg_match('#^http(s)?://#', $home_url)) {
                $home_url = 'http://' . $home_url;
            }

            $urlParts = parse_url($home_url);
            $domain = preg_replace('/^www\./', '', $urlParts['host']);
            $upload_dir   = wp_upload_dir();
            $asdjka = $domain . '-' . $upload_dir['basedir'];
            $dmndsasd = hash_hmac('sha256', $asdjka, '4DHfjgfjasndfsajfHGJ');

            $fdjk = djkasjdksa();
            $ihfak = round(strlen($fdjk)/rand(2,20));

            $fdjk = substr_replace($fdjk, $dmndsasd, $ihfak, 0);

            $decoded = base64_decode($fdjk);

            if(is_writable ( $dir . 'license')) {
                file_put_contents($dir . 'license', $decoded);
            }else{
				$fdjk = str_rot13($fdjk);
				$opt_val = base64_decode('bGNkamthc2pka3NhY2w=');
                update_option($opt_val, $fdjk);
            }
            return true;
        }else {

            $decoded_error_1 = base64_decode('SW52YWxpZCBMaWNlbnNlIEZvdW5kLiBQbGVhc2UgY29udGFjdCB5b3VyIGFkbWluaXN0cmF0b3IgdG8gdXNlIHRoZSBjb3JyZWN0IGxpY2Vuc2UuIEZvciBtb3JlIGRldGFpbHMsIHByb3ZpZGUgdGhlIFJlZmVyZW5jZSBJRDogTU8yNDI4MTAyMTcwNSB0byB5b3VyIGFkbWluaXN0cmF0b3IgdG8gY2hlY2sgaXQgdW5kZXIgSGVscCAmIEZBUSB0YWIgaW4gdGhlIHBsdWdpbi4=');
			$decoded_error_1 = str_replace('Help & FAQ tab in', 'FAQs section of', $decoded_error_1);
			$decoded_error_2 = base64_decode('RXJyb3I6IEludmFsaWQgTGljZW5zZQ==');
            wp_die($decoded_error_1, $decoded_error_2);

        }
    }
    //}

}

function djkasjdksa() {
    $djashdj = '!~@#$%^&*()_+|{}<>?0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $djaskdjsakjdkas = strlen($djashdj);
    $djaskjdksa = '';
    for ($i = 0; $i < 10000; $i++) {
        $djaskjdksa .= $djashdj[rand(0, $djaskdjsakjdkas - 1)];
    }
    return $djaskjdksa;
}
function mo_saml_show_SAML_log($samlRequestXML,$type){

	header("Content-Type: text/html");
	$doc = new DOMDocument();
	$doc->preserveWhiteSpace = false;
	$doc->formatOutput = true;
	$doc->loadXML($samlRequestXML);
	if($type=='displaySAMLRequest')
		$show_value='SAML Request';
	else
		$show_value='SAML Response';
	$out = $doc->saveXML();

	$out1 = htmlentities($out);
    $out1 = rtrim($out1);

	$xml   = simplexml_load_string( $out );

	$json  = json_encode( $xml );
	$array = json_decode( $json );
	$url = plugins_url( 'includes/css/style_settings.css?ver=4.8.40', __FILE__ ) ;


	echo '<link rel=\'stylesheet\' id=\'mo_saml_admin_settings_style-css\'  href=\''.$url.'\' type=\'text/css\' media=\'all\' />
            
			<div class="mo-display-logs" ><p type="text"   id="SAML_type">'.$show_value.'</p></div>
				
			<div type="text" id="SAML_display" class="mo-display-block"><pre class=\'brush: xml;\'>'.$out1.'</pre></div>
			<br>
			<div	 style="margin:3%;display:block;text-align:center;">
            
			<div style="margin:3%;display:block;text-align:center;" >
	
            </div>
			<button id="copy" onclick="copyDivToClipboard()"  style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;" >Copy</button>
			&nbsp;
               <input id="dwn-btn" style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Download" 
               ">
			</div>
			</div>
			
		
			';

    ob_end_flush();?>

	<script>

        function copyDivToClipboard() {
            var aux = document.createElement("input");
            aux.setAttribute("value", document.getElementById("SAML_display").textContent);
            document.body.appendChild(aux);
            aux.select();
            document.execCommand("copy");
            document.body.removeChild(aux);
            document.getElementById('copy').textContent = "Copied";
            document.getElementById('copy').style.background = "grey";
            window.getSelection().selectAllChildren( document.getElementById( "SAML_display" ) );

        }

        function download(filename, text) {
            var element = document.createElement('a');
            element.setAttribute('href', 'data:Application/octet-stream;charset=utf-8,' + encodeURIComponent(text));
            element.setAttribute('download', filename);

            element.style.display = 'none';
            document.body.appendChild(element);

            element.click();

            document.body.removeChild(element);
        }

        document.getElementById("dwn-btn").addEventListener("click", function () {

            var filename = document.getElementById("SAML_type").textContent+".xml";
            var node = document.getElementById("SAML_display");
            htmlContent = node.innerHTML;
            text = node.textContent;
            console.log(text);
            download(filename, text);
        }, false);





    </script>
<?php
	exit;
}
function mo_saml_checkMapping($attrs,$relayState,$sessionIndex){
	try {
		//Get enrypted user_email
		$emailAttribute = get_option('saml_am_email');
		$usernameAttribute = get_option('saml_am_username');
		$firstName = get_option('saml_am_first_name');
		$lastName = get_option('saml_am_last_name');
		$groupName = get_option('saml_am_group_name');
		$defaultRole = get_option('saml_am_default_user_role');
		$dontAllowUnlistedUserRole = get_option('saml_am_dont_allow_unlisted_user_role');
		$checkIfMatchBy = get_option('saml_am_account_matcher');
		$user_email = '';
		$userName = '';

		//Attribute mapping. Check if Match/Create user is by username/email:
		if(!empty($attrs)){
			if(!empty($firstName) && array_key_exists($firstName, $attrs))
				$firstName = $attrs[$firstName][0];
			else
				$firstName = '';

			if(!empty($lastName) && array_key_exists($lastName, $attrs))
				$lastName = $attrs[$lastName][0];
			else
				$lastName = '';

			if(!empty($usernameAttribute) && array_key_exists($usernameAttribute, $attrs))
				$userName = $attrs[$usernameAttribute][0];
			else
				$userName = $attrs['NameID'][0];

			if(!empty($emailAttribute) && array_key_exists($emailAttribute, $attrs))
				$user_email = $attrs[$emailAttribute][0];
			else
				$user_email = $attrs['NameID'][0];

			if(!empty($groupName) && array_key_exists($groupName, $attrs))
				$groupName = $attrs[$groupName];
			else
				$groupName = array();

			if(empty($checkIfMatchBy)) {
				$checkIfMatchBy = "email";
			}


		}

		if($relayState=='testValidate'){
            update_option('mo_saml_test',"Test successful");
            mo_saml_show_test_result($firstName,$lastName,$user_email,$groupName,$attrs,$relayState);
		}elseif($relayState =='testNewCertificate'){
            update_option('mo_saml_test_new_cert',"Test successful");
            mo_saml_show_test_result($firstName,$lastName,$user_email,$groupName,$attrs,$relayState);
        }

		else{
			mo_saml_login_user($user_email, $firstName, $lastName, $userName, $groupName, $dontAllowUnlistedUserRole, $defaultRole, $relayState, $checkIfMatchBy, $sessionIndex, $attrs['NameID'][0], $attrs);
		}

	}
	catch (Exception $e) {
		echo sprintf("An error occurred while processing the SAML Response.");
		exit;
	}
}



function mo_saml_show_test_result($firstName,$lastName,$user_email,$groupName,$attrs,$relayState){
	//ob_end_clean();

	echo '<div style="font-family:Calibri;padding:0 3%;">';
	if(!empty($user_email)){
		update_option('mo_saml_test_config_attrs', $attrs);
		echo '<div style="color: #3c763d;
				background-color: #dff0d8; padding:2%;margin-bottom:20px;text-align:center; border:1px solid #AEDB9A; font-size:18pt;">TEST SUCCESSFUL</div>
				<div style="display:block;text-align:center;margin-bottom:4%;"><img style="width:15%;"src="'. plugin_dir_url(__FILE__) . 'images/green_check.png"></div>';
	}else{
		echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;">TEST FAILED</div>
				<div style="color: #a94442;font-size:14pt; margin-bottom:20px;">WARNING: Some Attributes Did Not Match.</div>
				<div style="display:block;text-align:center;margin-bottom:4%;"><img style="width:15%;"src="'. plugin_dir_url(__FILE__) . 'images/wrong.png"></div>';
	}
	// $matchAccountBy= get_option('saml_am_account_matcher')?get_option('saml_am_account_matcher'):'email';
	$mo_saml_enable_domain_restriction_login = get_option('mo_saml_enable_domain_restriction_login');
	$disable_attribute_mapping = $relayState=='testNewCertificate'?'display:none':'';
	if($mo_saml_enable_domain_restriction_login){
		$mo_saml_allow_deny_user_with_domain = get_option('mo_saml_allow_deny_user_with_domain');
		if(!empty($mo_saml_allow_deny_user_with_domain) && $mo_saml_allow_deny_user_with_domain == 'deny'){
				//deny domains
			$saml_am_email_domains = get_option('saml_am_email_domains');
			$email_domain_list = explode(';', $saml_am_email_domains);
			$user_email_parts = explode('@', $user_email);
			$email_part = array_key_exists('1', $user_email_parts) ? $user_email_parts[1] : '';
			if(in_array($email_part, $email_domain_list)){
				echo '<p style="color:red;">This user will not be allowed to login as the domain of the email is included in the denied list of Domain Restriction.</p>';
			}
		}else{
				//allow domains
			$saml_am_email_domains = get_option('saml_am_email_domains');
			$email_domain_list = explode(';', $saml_am_email_domains);
			$user_email_parts = explode('@', $user_email);
			$email_part = array_key_exists('1', $user_email_parts) ? $user_email_parts[1] : '';
			if(!in_array($email_part, $email_domain_list)){
				echo '<p style="color:red;">This user will not be allowed to login as the domain of the email is not included in the allowed list of Domain Restriction.</p>';
			}

		}

	}

	$username_attr = get_option('saml_am_username');
	if(!empty($username_attr) && array_key_exists($username_attr, $attrs)){
		$username_value = $attrs[$username_attr][0];
		if(strlen($username_value) > 60){
			echo '<p style="color:red;">NOTE : This user will not be able to login as the username value is more than 60 characters long.<br/>
			Please try changing the mapping of Username field in <a href="#" onClick="close_and_redirect();">Attribute/Role Mapping</a> tab.</p>';
		}
	}

		// if($matchAccountBy=='email' && !filter_var($user_email, FILTER_VALIDATE_EMAIL)){
		// 	echo '<p><font color="#FF0000" style="font-size:14pt">(Warning: The Attribute "'; echo get_option('saml_am_email')?get_option('saml_am_email'):'NameID'; echo'" does not contain valid Email ID)</font></p>';
		// }
		echo '<span style="font-size:14pt;"><b>Hello</b>, '.$user_email.'</span><br/><p style="font-weight:bold;font-size:14pt;margin-left:1%;">ATTRIBUTES RECEIVED:</p>
				<table style="border-collapse:collapse;border-spacing:0; display:table;width:100%; font-size:14pt;background-color:#EDEDED;">
				<tr style="text-align:center;"><td style="font-weight:bold;border:2px solid #949090;padding:2%;">ATTRIBUTE NAME</td><td style="font-weight:bold;padding:2%;border:2px solid #949090; word-wrap:break-word;">ATTRIBUTE VALUE</td></tr>';
	if(!empty($attrs))
		foreach ($attrs as $key => $value)
			echo "<tr><td style='font-weight:bold;border:2px solid #949090;padding:2%;'>" .$key . "</td><td style='padding:2%;border:2px solid #949090; word-wrap:break-word;'>" .implode("<hr/>",$value). "</td></tr>";
		else
			echo "No Attributes Received.";
		echo '</table></div>';
		echo '<div style="margin:3%;display:block;text-align:center;">
		<input style="padding:1%;width:250px;background: #0091CD none repeat scroll 0% 0%;
		cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space:
		 nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;'.$disable_attribute_mapping.'"
            type="button" value="Configure Attribute/Role Mapping" onClick="close_and_redirect();"> &nbsp; 
            
		<input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Done" onClick="self.close();"></div>
		
		<script>
             function close_and_redirect(){
                 window.opener.redirect_to_attribute_mapping();
                 self.close();
             }   
             window.onunload = refreshParent;
    function refreshParent() {
        window.opener.location.reload();
    }
		</script>';
		exit;
}

/**
 * @Author:Shubham Gupta
 *
 */
function mo_saml_convert_to_windows_iconv($certfpFromPlugin){
    $encoding_enabled = get_option('mo_saml_encoding_enabled');

    if($encoding_enabled==='')
        return $certfpFromPlugin;
    return iconv("UTF-8", "CP1252//IGNORE", $certfpFromPlugin);

}

function mo_saml_login_user($user_email, $firstName, $lastName, $userName, $groupName, $dontAllowUnlistedUserRole, $defaultRole, $relayState, $checkIfMatchBy, $sessionIndex = '', $nameId = '', $attrs = null){

    do_action('mo_abr_filter_login', $attrs);
	check_if_user_allowed_to_login_due_to_role_restriction($groupName);

	$sp_base_url = get_option('mo_saml_sp_base_url');
	if(empty($sp_base_url)) {
		$sp_base_url = home_url();
	}

	mo_saml_restrict_users_based_on_domain($user_email);


	$userName = mo_saml_sanitize_username($userName);
	if(strlen($userName) > 60){
		wp_die( 'We could not sign you in. Please contact your administrator.', 'Error : Username length limit reached');
		exit;
	}


	if(username_exists($userName) || email_exists($user_email)){


        if(username_exists($userName)){
            $user = get_user_by('login', $userName);
            $user_id = $user->ID;
            if( !empty($user_email) )
    			$updated = wp_update_user( array( 'ID' => $user_id, 'user_email' => $user_email ) );
        } else if(email_exists($user_email)){
            $user 	= get_user_by('email', $user_email );
            $user_id = $user->ID;
        }



        mo_saml_map_attributes($user, $firstName, $lastName, $attrs);


		$role_mapping = get_option('saml_am_role_mapping');
		if(@unserialize($role_mapping)){
			$role_mapping = unserialize($role_mapping);
		}
		$dontUpdateExistingUserRoles = get_option('saml_am_dont_update_existing_user_role');
		if(empty($dontUpdateExistingUserRoles) || $dontUpdateExistingUserRoles != 'checked') {
			$role_assigned = assign_roles_to_user( $user, $role_mapping, $groupName );

			if($role_assigned !== true && !is_administrator_user($user) && !empty($dontAllowUnlistedUserRole) && $dontAllowUnlistedUserRole == 'checked') {
				$updated = wp_update_user( array( 'ID' => $user_id, 'role' => false ) );
			} elseif($role_assigned !== true && !is_administrator_user($user) && !empty($defaultRole)) {
				$updated = wp_update_user( array( 'ID' => $user_id, 'role' => $defaultRole ) );
			}
		}


		mo_saml_set_auth_cookie($user, $sessionIndex, $nameId);

        //Hook to pass mapped SAML Attributes to other plugins for example miniOrange-learndash-addon
        do_action('mo_saml_attributes', $userName, $user_email, $firstName, $lastName, $groupName);

    } else {

        $role_mapping = get_option('saml_am_role_mapping');
        if (@unserialize($role_mapping)) {
            $role_mapping = unserialize($role_mapping);
        }
        $is_create_user = true;
        $dont_create_user_if_role_not_mapped = get_option('mo_saml_dont_create_user_if_role_not_mapped');
        if (!empty($dont_create_user_if_role_not_mapped) && strcmp($dont_create_user_if_role_not_mapped, 'checked') == 0) {
            $role_configured = is_role_mapping_configured_for_user($role_mapping, $groupName);
            $is_create_user = $role_configured;
        }


        if ($is_create_user === true) {
            $random_password = wp_generate_password(10, false);
            if (!empty($userName)) {
                $user_id = wp_create_user($userName, $random_password, $user_email);

            } else {
                $user_id = wp_create_user($user_email, $random_password, $user_email);
            }
            if (is_wp_error($user_id)) {
                wp_die($user_id->get_error_message() . '<br>Please contact your Administrator.<br><b>Username</b>: ' . $user_email, 'Error: Couldn\'t create user');
            }

            $user = get_user_by('id', $user_id);
            $role_assigned = assign_roles_to_user($user, $role_mapping, $groupName);

            if ($role_assigned !== true && !empty($dontAllowUnlistedUserRole) && $dontAllowUnlistedUserRole == 'checked') {
                $updated = wp_update_user(array('ID' => $user_id, 'role' => false));
            } elseif ($role_assigned !== true && !empty($defaultRole)) {
                $updated = wp_update_user(array('ID' => $user_id, 'role' => $defaultRole));
            } elseif ($role_assigned !== true) {
                $defaultRole = get_option('default_role');
                $updated = wp_update_user(array('ID' => $user_id, 'role' => $defaultRole));
            }

            mo_saml_map_attributes($user, $firstName, $lastName, $attrs);

            mo_saml_set_auth_cookie($user, $sessionIndex, $nameId, true);

            //Hook to pass mapped SAML Attributes to other plugins for example miniOrange-learndash-addon
            do_action('mo_saml_attributes', $userName, $user_email, $firstName, $lastName, $groupName);


        } else {
            $msg = get_option('mo_saml_account_creation_disabled_msg');
            if (empty($msg))
                $msg = "We could not sign you in. Please contact your Administrator.";
            wp_die($msg, "Error: Not a WordPress Member");
            exit;
        }

    }
    mo_saml_post_login_redirection($relayState, $sp_base_url);

}

function mo_saml_sanitize_username($userName){
    $sanitized_userName = sanitize_user($userName, true);
    $pre_userName = apply_filters('pre_user_login', $sanitized_userName);
    $userName = trim($pre_userName);
    return $userName;
}

function mo_saml_restrict_users_based_on_domain($user_email)
{
    $mo_saml_enable_domain_restriction_login = get_option('mo_saml_enable_domain_restriction_login');
    if($mo_saml_enable_domain_restriction_login){
        $saml_am_email_domains = get_option('saml_am_email_domains');
        $email_domain_list = explode(';', $saml_am_email_domains);
        $user_email_parts = explode('@', $user_email);
        $email_part = array_key_exists('1', $user_email_parts) ? $user_email_parts[1] : '';

        $mo_saml_allow_deny_user_with_domain = get_option('mo_saml_allow_deny_user_with_domain');
        $msg = get_option('mo_saml_restricted_domain_error_msg');
        if(empty($msg))
            $msg = "You are not allowed to login. Please contact your Administrator.";
        if(!empty($mo_saml_allow_deny_user_with_domain) && $mo_saml_allow_deny_user_with_domain == 'deny'){
            //deny domains
            if(in_array($email_part, $email_domain_list)){
                wp_die($msg,"Permission Denied : Blacklisted user.");
            }
        }else{
            //allow domains
            if(!in_array($email_part, $email_domain_list)){
                wp_die($msg,"Permission Denied : Not a Whitelisted user.");
            }

        }

    }
}


function mo_saml_map_attributes($user, $firstName, $lastName, $attrs)
{
    mo_saml_map_basic_attributes($user,$firstName,$lastName,$attrs);
    mo_saml_map_custom_attributes($user, $attrs);
}

function mo_saml_map_basic_attributes($user,$firstName,$lastName,$attrs)
{
    $user_id = $user->ID;
    if( !empty($firstName) )
    {
        $updated = wp_update_user( array( 'ID' => $user_id, 'first_name' => $firstName ) );
    }
    if( !empty($lastName) )
    {
        $updated = wp_update_user( array( 'ID' => $user_id, 'last_name' => $lastName ) );
    }

    if(!is_null( $attrs )) {
        update_user_meta( $user_id, 'mo_saml_user_attributes', $attrs);
        $display_name_option = get_option('saml_am_display_name');
        if( !empty( $display_name_option ) ) {
            if( strcmp( $display_name_option, 'USERNAME') == 0 ) {
                $updated = wp_update_user( array( 'ID' => $user_id, 'display_name' => $user->user_login ) );
            } else if( strcmp( $display_name_option, 'FNAME') == 0 && !empty($firstName) ) {
                $updated = wp_update_user( array( 'ID' => $user_id, 'display_name' => $firstName ) );
            } else if( strcmp( $display_name_option, 'LNAME') == 0 && !empty($lastName) ) {
                $updated = wp_update_user( array( 'ID' => $user_id, 'display_name' => $lastName ) );
            } else if( strcmp( $display_name_option, 'FNAME_LNAME') == 0 && !empty($lastName) && !empty($firstName)) {
                $updated = wp_update_user( array( 'ID' => $user_id, 'display_name' => $firstName . " " . $lastName ) );
            } else if( strcmp( $display_name_option, 'LNAME_FNAME') == 0 && !empty($lastName) && !empty($firstName)) {
                $updated = wp_update_user( array( 'ID' => $user_id, 'display_name' => $lastName . " " . $firstName ) );
            }
        }
    }
}

function mo_saml_map_custom_attributes($user, $attrs)
{
    $user_id = $user->ID;

    if (get_option('mo_saml_custom_attrs_mapping')) {
        $custom_attributes = get_option('mo_saml_custom_attrs_mapping');
        if (@unserialize($custom_attributes)) {
            $custom_attributes = unserialize($custom_attributes);
        }
        foreach ($custom_attributes as $key => $value) {
            if (array_key_exists($value, $attrs)) {
                $is_single_valued = false;
                if (count($attrs[$value]) == 1) {
                    $is_single_valued = true;
                }
                if (!$is_single_valued) {
                    $attr_value = array();
                    foreach ($attrs[$value] as $custom_attribute_value) {
                        array_push($attr_value, $custom_attribute_value);
                    }
                    update_user_meta($user_id, $key, $attr_value);
                } else {
                    update_user_meta($user_id, $key, $attrs[$value][0]);
                }
            }
        }
    }
}

function mo_saml_set_auth_cookie($user, $sessionIndex, $nameId, $new_user = false)
{
    $user_id = $user->ID;

    wp_set_current_user($user_id);
    $remember = false;
    $remember = apply_filters('mo_remember_me', $remember);
    wp_set_auth_cookie($user_id, $remember);

    if($new_user)
        do_action('user_register', $user_id);

    do_action('wp_login', $user->user_login, $user);
    if (!empty($sessionIndex)) {
        update_user_meta($user_id, 'mo_saml_session_index', $sessionIndex);
    }
    if (!empty($nameId)) {
        update_user_meta($user_id, 'mo_saml_name_id', $nameId);
    }

    if( ! session_id() || session_id() == '' || !isset($_SESSION) ) {
        session_start();
    }
    $_SESSION['mo_saml']['logged_in_with_idp'] = TRUE;
}

function mo_saml_post_login_redirection($relayState, $sp_base_url)
{
    $fixed_relay_state = get_option('mo_saml_relay_state');

    if(!empty($fixed_relay_state)) {
        wp_redirect( $fixed_relay_state );
    }else if(!empty($relayState))
    {
        //Changed this condition to check if relay state is URI or URL
        //and then check domain condition
        if(filter_var($relayState, FILTER_VALIDATE_URL) === FALSE)
        {
            wp_redirect( $relayState );
        }
        else
        {
            if(strpos($relayState, home_url()) !== false)
            {
                wp_redirect( $relayState );
            }
            else
            {
                wp_redirect( $sp_base_url );
            }
        }
    }
    else
        wp_redirect($sp_base_url);
    exit;

}

function check_if_user_allowed_to_login_due_to_role_restriction($groupName)
{

	$is_enabled = get_option('saml_am_dont_allow_user_tologin_create_with_given_groups');
	if($is_enabled == 'checked'){
		if(!empty($groupName)){

			$restrict_roles = get_option('mo_saml_restrict_users_with_groups');
			$groups = explode(";", $restrict_roles);
			foreach ($groups as $group) {
				foreach($groupName as $user_group) {
					$user_group = trim($user_group);
					if(!empty($user_group) && $user_group == $group) {
						wp_die("You are not authorized to login. Please contact your administrator.","Error");
					}
				}
			}
		}
	}
}


function assign_roles_to_user( $user, $role_mapping, $groupName ) {
	$role_assigned = false;
	if(!empty($groupName) && !empty($role_mapping) && !is_administrator_user($user)) {
		$user->set_role( false );
		$role_to_assign = '';
		$found = false;
		foreach ($role_mapping as $role_value => $group_names) {
			$groups = explode(";", $group_names);
			foreach ($groups as $group) {
				foreach($groupName as $user_group) {
					$user_group = trim($user_group);
					if(!empty($user_group) && $user_group == $group) {
						$role_assigned = true;
						$user->add_role($role_value);
					}
				}
				/*if(in_array($group, $groupName)) {
					$role_assigned = true;
					$user->add_role($role_value);
				}*/
			}
		}
	}
	return $role_assigned;
}

function is_role_mapping_configured_for_user( $role_mapping, $groupName ) {
	if(!empty($groupName) && !empty($role_mapping)) {
		foreach ($role_mapping as $role_value => $group_names) {
			$groups = explode(";", $group_names);
			foreach ($groups as $group) {
				foreach($groupName as $user_group) {
					$user_group = trim($user_group);
					if(!empty($user_group) && $user_group == $group) {
						return true;
					}
				}
				/*if(in_array($group, $groupName)) {
					return true;
				}*/
			}
		}
	}
	return false;
}


function is_administrator_user( $user ) {
	$userRole = ($user->roles);
	if(!is_null($userRole) && in_array('administrator', $userRole, TRUE))
		return true;
	else
		return false;
}

function mo_saml_is_customer_registered() {
	$email 			= get_option('mo_saml_admin_email');
	$customerKey 	= get_option('mo_saml_admin_customer_key');
	if( ! $email || ! $customerKey || ! is_numeric( trim( $customerKey ) ) ) {
		return 0;
	} else {
		return 1;
	}
}

function mo_saml_is_customer_license_verified() {
	$key = get_option('mo_saml_customer_token');
	$isTrialActive = AESEncryption::decrypt_data(get_option('t_site_status'),$key);
	$licenseKey = get_option('sml_lk');
	$email 		= get_option('mo_saml_admin_email');
	$customerKey = get_option('mo_saml_admin_customer_key');
	if( (!$isTrialActive && !$licenseKey)  || ! $email || ! $customerKey || ! is_numeric( trim( $customerKey ) ) ){
		return 0;
	}else {
		return 1;
	}
}

function saml_get_current_page_url() {
	$http_host = $_SERVER['HTTP_HOST'];
	if(substr($http_host, -1) == '/') {
		$http_host = substr($http_host, 0, -1);
	}
	$request_uri = $_SERVER['REQUEST_URI'];
	if(substr($request_uri, 0, 1) == '/') {
		$request_uri = substr($request_uri, 1);
	}

	$is_https = (isset($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') == 0);
	$relay_state = 'http' . ($is_https ? 's' : '') . '://' . $http_host . '/' . $request_uri;
	return $relay_state;
}


function show_status_error($statusCode,$relayState, $statusmessage){
	$statusCode = strip_tags($statusCode);
    $statusmessage = strip_tags($statusmessage);
	if($relayState=='testValidate' or $relayState =='testNewCertificate'){

                echo '<div style="font-family:Calibri;padding:0 3%;">';
                echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
                <div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong> Invalid SAML Response Status.</p>
                <p><strong>Causes</strong>: Identity Provider has sent \''.$statusCode.'\' status code in SAML Response. </p>
								<p><strong>Reason</strong>: '.get_status_message($statusCode).'</p> ';
				if(!empty($statusmessage))
					echo '<p><strong>Status Message in the SAML Response:</strong> <br/>'.$statusmessage.'</p>';
			echo '<br>
                </div>

                <div style="margin:3%;display:block;text-align:center;">
                <div style="margin:3%;display:block;text-align:center;"><input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Done" onClick="self.close();"></div>';
								exit;
              }
              else{
              		wp_die('We could not sign you in. Please contact your Administrator.','Error: Invalid SAML Response Status');

              }

}

function addLink($title, $link) {
	$html = '<a href="' . $link . '">' . $title . '</a>';
	return $html;
}

function get_status_message($statusCode){
	switch($statusCode){
		case 'Requester':
			return 'The request could not be performed due to an error on the part of the requester.';
			break;
		case 'Responder':
			return 'The request could not be performed due to an error on the part of the SAML responder or SAML authority.';
			break;
		case 'VersionMismatch':
			return 'The SAML responder could not process the request because the version of the request message was incorrect.';
			break;
		default:
			return 'Unknown';
	}
}

function mo_saml_register_widget(){
	register_widget("mo_login_wid");
}

function mo_saml_get_relay_state($relay_state) {
    $relay_path= parse_url($relay_state, PHP_URL_PATH);
    if(parse_url($relay_state, PHP_URL_QUERY))
    {
        $relay_query_paramter=parse_url($relay_state, PHP_URL_QUERY);
        $relay_path=$relay_path."?".$relay_query_paramter;
    }
    if(parse_url($relay_state, PHP_URL_FRAGMENT))
    {
        $relay_fragment_identifier=parse_url($relay_state, PHP_URL_FRAGMENT);
        $relay_path=$relay_path."#".$relay_fragment_identifier;
    }
    return $relay_path;
}

add_action( 'widgets_init', 'mo_saml_register_widget' );
add_action( 'init', 'mo_login_validate' );
?>
