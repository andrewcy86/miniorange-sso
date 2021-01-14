<?php
/** miniOrange SAML 2.0 SSO enables user to perform Single Sign On with any SAML 2.0 enabled Identity Provider.
 Copyright (C) 2015  miniOrange

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>
 * @package 		miniOrange SAML 2.0 SSO
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */
/**
 * This library is miniOrange Authentication Service.
 *
 * Contains Request Calls to Customer service.
 */
class Customersaml {
	public $email;
	public $phone;

	/*
	 * * Initial values are hardcoded to support the miniOrange framework to generate OTP for email.
	 * * We need the default value for creating the first time,
	 * * As we don't have the Default keys available before registering the user to our server.
	 * * This default values are only required for sending an One Time Passcode at the user provided email address.
	 */
	private $defaultCustomerKey = "16555";
	private $defaultApiKey = "fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq";

	function create_customer() {

		$url = mo_options_plugin_constants::HOSTNAME . '/moas/rest/customer/add';

		$current_user = wp_get_current_user();
		$this->email = get_option ( 'mo_saml_admin_email' );
		$this->phone = get_option ( 'mo_saml_admin_phone' );
		$password = get_option ( 'mo_saml_admin_password' );

		$fields = array (
				'companyName' => $_SERVER ['SERVER_NAME'],
				'areaOfInterest' => 'WP miniOrange SAML 2.0 SSO Plugin',
				'firstname' => $current_user->user_firstname,
				'lastname' => $current_user->user_lastname,
				'email' => $this->email,
				'phone' => $this->phone,
				'password' => $password
		);
		$field_string = json_encode ( $fields );

        $headers = array("Content-Type"=>"application/json","charset"=>"UTF-8","Authorization"=>"Basic");
		$args = array(
			'method' => 'POST',
			'body' => $field_string,
			'timeout' => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => $headers
		);
		$response = SAMLSPUtilities::mo_saml_wp_remote_call($url, $args);
		return $response;
	}
	function get_customer_key() {

		$url = mo_options_plugin_constants::HOSTNAME . "/moas/rest/customer/key";

		$email = get_option ( "mo_saml_admin_email" );

		$password = get_option ( "mo_saml_admin_password" );

		$fields = array (
				'email' => $email,
				'password' => $password
		);
		$field_string = json_encode ( $fields );

		$headers = array("Content-Type"=>"application/json","charset"=>"UTF-8","Authorization"=>"Basic");
		$args = array(
			'method' => 'POST',
			'body' => $field_string,
			'timeout' => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => $headers
		);
		$response = SAMLSPUtilities::mo_saml_wp_remote_call($url, $args);
		return $response;
	}
	function check_customer() {

		$url = mo_options_plugin_constants::HOSTNAME. "/moas/rest/customer/check-if-exists";

		$email = get_option ( "mo_saml_admin_email" );

		$fields = array (
				'email' => $email
		);
		$field_string = json_encode ( $fields );

		$headers = array("Content-Type"=>"application/json","charset"=>"UTF-8","Authorization"=>"Basic");
		$args = array(
			'method' => 'POST',
			'body' => $field_string,
			'timeout' => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => $headers
		);
		$response = SAMLSPUtilities::mo_saml_wp_remote_call($url, $args);
		return $response;
	}
	function send_otp_token($email, $phone, $sendToEmail = TRUE, $sendToPhone = FALSE) {
		$url = mo_options_plugin_constants::HOSTNAME . '/moas/api/auth/challenge';

		$customerKey = $this->defaultCustomerKey;
		$apiKey = $this->defaultApiKey;

		//$username = get_option ( 'mo_saml_admin_email' );

		/* Current time in milliseconds since midnight, January 1, 1970 UTC. */
		$currentTimeInMillis = round ( microtime ( true ) * 1000 );

		/* Creating the Hash using SHA-512 algorithm */
		$stringToHash = $customerKey . number_format ( $currentTimeInMillis, 0, '', '' ) . $apiKey;
		$hashValue = hash ( "sha512", $stringToHash );

		// $customerKeyHeader = "Customer-Key: " . $customerKey;
		// $timestampHeader = "Timestamp: " . number_format ( $currentTimeInMillis, 0, '', '' );
		// $authorizationHeader = "Authorization: " . $hashValue;
		$currentTimeInMillis = number_format ( $currentTimeInMillis, 0, '', '' );
		if ($sendToEmail) {
			$fields = array (
					'customerKey' => $customerKey,
					'email' => $email,
					'authType' => 'EMAIL',
					'transactionName' => 'WP miniOrange SAML 2.0 SSO Plugin'
			);
		} else {
			$fields = array (
					'customerKey' => $customerKey,
					'phone' => $phone,
					'authType' => 'SMS',
					'transactionName' => 'WP miniOrange SAML 2.0 SSO Plugin'
			);
		}
		$field_string = json_encode ( $fields );

		$headers = array(
			"Content-Type" => "application/json",
			"Customer-Key" => $customerKey,
			"Timestamp" => $currentTimeInMillis,
			"Authorization" => $hashValue
		);
		$args = array(
			'method' => 'POST',
			'body' => $field_string,
			'timeout' => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => $headers
		);
		$response = SAMLSPUtilities::mo_saml_wp_remote_call($url, $args);
		return $response;
	}
	function validate_otp_token($transactionId, $otpToken) {
		$url = mo_options_plugin_constants::HOSTNAME . '/moas/api/auth/validate';


		$customerKey = $this->defaultCustomerKey;
		$apiKey = $this->defaultApiKey;

		$username = get_option ( 'mo_saml_admin_email' );

		/* Current time in milliseconds since midnight, January 1, 1970 UTC. */
		$currentTimeInMillis = round ( microtime ( true ) * 1000 );

		/* Creating the Hash using SHA-512 algorithm */
		$stringToHash = $customerKey . number_format ( $currentTimeInMillis, 0, '', '' ) . $apiKey;
		$hashValue = hash ( "sha512", $stringToHash );

		// $customerKeyHeader = "Customer-Key: " . $customerKey;
		// $timestampHeader = "Timestamp: " . number_format ( $currentTimeInMillis, 0, '', '' );
		// $authorizationHeader = "Authorization: " . $hashValue;
		$currentTimeInMillis = number_format ( $currentTimeInMillis, 0, '', '' );
		$fields = '';

		// *check for otp over sms/email
		$fields = array (
				'txId' => $transactionId,
				'token' => $otpToken
		);

		$field_string = json_encode ( $fields );

		$headers = array(
			"Content-Type" => "application/json",
			"Customer-Key" => $customerKey,
			"Timestamp" => $currentTimeInMillis,
			"Authorization" => $hashValue
		);
		$args = array(
			'method' => 'POST',
			'body' => $field_string,
			'timeout' => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => $headers
		);
		$response = SAMLSPUtilities::mo_saml_wp_remote_call($url, $args);
		return $response;
	}
	function submit_contact_us($email, $phone, $query) {
		$current_user = wp_get_current_user();
		$query = '[WP SAML 2.0 SP SSO Premium Plugin] ' . $query;
		$fields = array (
				'firstName' => $current_user->user_firstname,
				'lastName' => $current_user->user_lastname,
				'company' => $_SERVER ['SERVER_NAME'],
                'ccEmail'=>'samlsupport@xecurify.com',
				'email' => $email,
				'phone' => $phone,
				'query' => $query
		);
		$field_string = json_encode ( $fields );

		$url = mo_options_plugin_constants::HOSTNAME. '/moas/rest/customer/contact-us';

		$headers = array("Content-Type"=>"application/json","charset"=>"UTF-8","Authorization"=>"Basic");
		$args = array(
			'method' => 'POST',
			'body' => $field_string,
			'timeout' => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => $headers
		);
		$response = SAMLSPUtilities::mo_saml_wp_remote_call($url, $args);
		return $response;
	}

    function send_email_alert($email,$phone,$message){

        $url = mo_options_plugin_constants::HOSTNAME . '/moas/api/notify/send';

        $customerKey = $this->defaultCustomerKey;
        $apiKey =  $this->defaultApiKey;

        $currentTimeInMillis = self::get_timestamp();
        $currentTimeInMillis = number_format ( $currentTimeInMillis, 0, '', '' );
        $stringToHash 		= $customerKey .  $currentTimeInMillis . $apiKey;
        $hashValue 			= hash("sha512", $stringToHash);
        $fromEmail			= 'no-reply@xecurify.com';
        $subject            = "Feedback: WordPress SAML 2.0 SSO Plugin";
        $site_url=site_url();

        global $user;
        $user         = wp_get_current_user();


        $query        = '[WordPress SAML SSO 2.0 Plugin: ]: ' . $message;


        $content='<div >Hello, <br><br>First Name :'.$user->user_firstname.'<br><br>Last  Name :'.$user->user_lastname.'   <br><br>Company :<a href="'.$_SERVER['SERVER_NAME'].'" target="_blank" >'.$_SERVER['SERVER_NAME'].'</a><br><br>Phone Number :'.$phone.'<br><br>Email :<a href="mailto:'.$email.'" target="_blank">'.$email.'</a><br><br>Query :'.$query.'</div>';


        $fields = array(
            'customerKey'	=> $customerKey,
            'sendEmail' 	=> true,
            'email' 		=> array(
                'customerKey' 	=> $customerKey,
                'fromEmail' 	=> $fromEmail,
                'bccEmail' 		=> $fromEmail,
                'fromName' 		=> 'Xecurify',
                'toEmail' 		=> 'info@xecurify.com',
                'toName' 		=> 'samlsupport@xecurify.com',
                'bccEmail'		=> 'samlsupport@xecurify.com',
                'subject' 		=> $subject,
                'content' 		=> $content
            ),
        );
        $field_string = json_encode($fields);

        $headers = array(
            "Content-Type" => "application/json",
            "Customer-Key" => $customerKey,
            "Timestamp" => $currentTimeInMillis,
            "Authorization" => $hashValue
        );
        $args = array(
            'method' => 'POST',
            'body' => $field_string,
            'timeout' => '5',
            'redirection' => '5',
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => $headers
        );
        $response = SAMLSPUtilities::mo_saml_wp_remote_call($url, $args);
        return $response['body'];

    }

	function mo_saml_forgot_password($email) {
		$url = mo_options_plugin_constants::HOSTNAME. '/moas/rest/customer/password-reset';


		/* The customer Key provided to you */
		$customerKey = get_option ( 'mo_saml_admin_customer_key' );

		/* The customer API Key provided to you */
		$apiKey = get_option ( 'mo_saml_admin_api_key' );

		/* Current time in milliseconds since midnight, January 1, 1970 UTC. */
		$currentTimeInMillis = round ( microtime ( true ) * 1000 );

		/* Creating the Hash using SHA-512 algorithm */
		$stringToHash = $customerKey . number_format ( $currentTimeInMillis, 0, '', '' ) . $apiKey;
		$hashValue = hash ( "sha512", $stringToHash );

		// $customerKeyHeader = "Customer-Key: " . $customerKey;
		// $timestampHeader = "Timestamp: " . number_format ( $currentTimeInMillis, 0, '', '' );
		// $authorizationHeader = "Authorization: " . $hashValue;
		$currentTimeInMillis = number_format ( $currentTimeInMillis, 0, '', '' );
		$fields = '';

		// *check for otp over sms/email
		$fields = array (
				'email' => $email
		);

		$field_string = json_encode ( $fields );

		$headers = array(
			"Content-Type" => "application/json",
			"Customer-Key" => $customerKey,
			"Timestamp" => $currentTimeInMillis,
			"Authorization" => $hashValue
		);
		$args = array(
			'method' => 'POST',
			'body' => $field_string,
			'timeout' => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => $headers
		);
		$response = SAMLSPUtilities::mo_saml_wp_remote_call($url, $args);
		return $response;
	}

	function mo_saml_vl($code,$active) {

		if(!$this->check_internet_connection()){
			return '{"status":"SUCCESS"}';
		}

		$url = "";
		if($active)
			$url = mo_options_plugin_constants::HOSTNAME . '/moas/api/backupcode/check';
		else
			$url = mo_options_plugin_constants::HOSTNAME . '/moas/api/backupcode/verify';



		/* The customer Key provided to you */
		$customerKey = get_option ( 'mo_saml_admin_customer_key' );

		/* The customer API Key provided to you */
		$apiKey = get_option ( 'mo_saml_admin_api_key' );

		/* Current time in milliseconds since midnight, January 1, 1970 UTC. */
		$currentTimeInMillis = round ( microtime ( true ) * 1000 );

		/* Creating the Hash using SHA-512 algorithm */
		$stringToHash = $customerKey . number_format ( $currentTimeInMillis, 0, '', '' ) . $apiKey;
		$hashValue = hash ( "sha512", $stringToHash );

		// $customerKeyHeader = "Customer-Key: " . $customerKey;
		// $timestampHeader = "Timestamp: " . number_format ( $currentTimeInMillis, 0, '', '' );
		// $authorizationHeader = "Authorization: " . $hashValue;
		$currentTimeInMillis = number_format ( $currentTimeInMillis, 0, '', '' );
		$fields = '';

		// *check for otp over sms/email

		$fields = array (
				'code' => $code ,
				'customerKey' => $customerKey,
				'additionalFields' => array(
					'field1' => home_url()
				)

		);

		$field_string = json_encode ( $fields );

		$headers = array(
			"Content-Type" => "application/json",
			"Customer-Key" => $customerKey,
			"Timestamp" => $currentTimeInMillis,
			"Authorization" => $hashValue
		);

		$args = array(
			'method' => 'POST',
			'body' => $field_string,
			'timeout' => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => $headers
		);
		$response = SAMLSPUtilities::mo_saml_wp_remote_call($url, $args);
		return $response;
	}

	function check_customer_ln(){

		$url = mo_options_plugin_constants::HOSTNAME . '/moas/rest/customer/license';

		$customerKey = get_option ( 'mo_saml_admin_customer_key' );

		$apiKey = get_option ( 'mo_saml_admin_api_key' );
		$currentTimeInMillis = round(microtime(true) * 1000);
		$stringToHash = $customerKey . number_format($currentTimeInMillis, 0, '', '') . $apiKey;
		$hashValue = hash("sha512", $stringToHash);
		$currentTimeInMillis = number_format ( $currentTimeInMillis, 0, '', '' );

		// $customerKeyHeader = "Customer-Key: " . $customerKey;
		// $timestampHeader = "Timestamp: " . $currentTimeInMillis;
		// $authorizationHeader = "Authorization: " . $hashValue;
		$fields = '';
		$fields = array(
			'customerId' => $customerKey,
			'applicationName' => 'wp_saml_sso_basic_plan'
		);
		$field_string = json_encode($fields);
		$headers = array(
			"Content-Type" => "application/json",
			"Customer-Key" => $customerKey,
			"Timestamp" => $currentTimeInMillis,
			"Authorization" => $hashValue
		);

		$args = array(
			'method' => 'POST',
			'body' => $field_string,
			'timeout' => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => $headers
		);
		$response = SAMLSPUtilities::mo_saml_wp_remote_call($url, $args);
		return $response;
	}

	function mo_saml_update_status() {

		$url = mo_options_plugin_constants::HOSTNAME . '/moas/api/backupcode/updatestatus';
		$customerKey = get_option ( 'mo_saml_admin_customer_key' );
		$apiKey = get_option ( 'mo_saml_admin_api_key' );
		$currentTimeInMillis = round ( microtime ( true ) * 1000 );
		$stringToHash = $customerKey . number_format ( $currentTimeInMillis, 0, '', '' ) . $apiKey;
		$hashValue = hash ( "sha512", $stringToHash );
		// $customerKeyHeader = "Customer-Key: " . $customerKey;
		// $timestampHeader = "Timestamp: " . number_format ( $currentTimeInMillis, 0, '', '' );
		// $authorizationHeader = "Authorization: " . $hashValue;
		$key = get_option('mo_saml_customer_token');
		$code = AESEncryption::decrypt_data(get_option('sml_lk'),$key);
		$fields = array ( 'code' => $code , 'customerKey' => $customerKey, 'additionalFields' => array('field1' => home_url()) );
		$field_string = json_encode ( $fields );
		$currentTimeInMillis = number_format ( $currentTimeInMillis, 0, '', '' );
		$headers = array(
			"Content-Type" => "application/json",
			"Customer-Key" => $customerKey,
			"Timestamp" => $currentTimeInMillis,
			"Authorization" => $hashValue
		);

		$args = array(
			'method' => 'POST',
			'body' => $field_string,
			'timeout' => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => $headers
		);
		$response = SAMLSPUtilities::mo_saml_wp_remote_call($url, $args);
		return $response;
	}

	function get_timestamp() {
		$url = mo_options_plugin_constants::HOSTNAME . '/moas/rest/mobile/get-timestamp';
		$response = SAMLSPUtilities::mo_saml_wp_remote_call($url);
		return $response;

	}

	function check_internet_connection()
	{
		return (bool) @fsockopen('login.xecurify.com', 443, $iErrno, $sErrStr, 5);
	}

}
?>