<?php
require_once dirname( __FILE__ ) . '/includes/lib/mo-options-enum.php';
add_action('admin_init', 'mo_saml_update');
class mo_saml_update_framework
{
    /**
     * The plugin current version
     * @var string
     */
    private $current_version;

    /**
     * The plugin remote update path
     * @var string
     */
    private $update_path;

    /**
     * Plugin Slug (plugin_directory/plugin_file.php)
     * @var string
     */
    private $plugin_slug;

    /**
     * Plugin name (plugin_file)
     * @var string
     */
    private $slug;

    /**
     * License User
     * @var string
     */
    //private $license_user;
	
	private $plugin_file;

    /**
     * License Key
     * @var string
     */
    //private $license_key;
	
	private $new_version_changelog;

    /**
     * Initialize a new instance of the WordPress Auto-Update class
     * @param string $current_version
     * @param string $update_path
     * @param string $plugin_slug
     */
    public function __construct( $current_version, $update_path = '/', $plugin_slug = '/' )
    {
        $this->current_version = $current_version;
        $this->update_path = $update_path;
        $this->plugin_slug = $plugin_slug;
        list ($t1, $t2) = explode( '/', $plugin_slug );
        $this->slug = $t1;
		$this->plugin_file = $t2;

        add_filter( 'pre_set_site_transient_update_plugins', array( &$this, 'mo_saml_check_update' ) );
		
        add_filter( 'plugins_api', array( &$this, 'mo_saml_check_info' ), 10, 3 );

    }


    /**
     * Add our self-hosted autoupdate plugin to the filter transient
     *
     * @param $transient
     * @return object $ transient
     */
    public function mo_saml_check_update( $transient )
    {
        if ( empty( $transient->checked ) ) {
            return $transient;
        }

        $remote_version = $this->getRemote();

		if($remote_version['status'] == 'SUCCESS'){
			$support_license_expired = false;
			update_option( 'mo_saml_sle', $support_license_expired );
			if ( version_compare( $this->current_version, $remote_version['newVersion'], '<' ) ) {
				

				ini_set('max_execution_time', 600);
				ini_set('memory_limit','1024M');
				// Start the backup!
				$dir = plugin_dir_path( __FILE__ );
				$dir = rtrim($dir, '/');
				$dir = rtrim($dir, '\\');
				$backup = $dir . '-premium-backup-' . $this->current_version . '.zip';
				
//				if(!file_exists($backup)){
//					$this->zipData($dir, $backup);
//				}
                $this->mo_saml_create_backup_dir();
				
				$hashValue = $this->getAuthToken();
				$currentTimeInMillis = round ( microtime ( true ) * 1000 );
				$currentTimeInMillis = number_format ( $currentTimeInMillis, 0, '', '' );
	
				$obj = new stdClass();
				$obj->slug = $this->slug;
				$obj->new_version = $remote_version['newVersion'];
				$obj->url = 'https://miniorange.com';
				$obj->plugin = $this->plugin_slug;
				$obj->package = mo_options_plugin_constants::HOSTNAME . '/moas/plugin/download-update?pluginSlug=' .
                    $this->plugin_slug. '&licensePlanName='.mo_options_plugin_constants::LICENSE_PLAN_NAME.'&customerId=' . get_option ( 'mo_saml_admin_customer_key' ) .
                    '&licenseType='.mo_options_plugin_constants::LICENSE_TYPE.'&authToken=' . $hashValue . '&otpToken=' . $currentTimeInMillis;
				
				$obj->tested = $remote_version['cmsCompatibilityVersion'];
				$obj->icons = array('1x'=>$remote_version['icon']);
				$obj->new_version_changelog = $remote_version['changelog'];
				$obj->status_code = $remote_version['status'];
				update_option('mo_saml_license_expiry_date',$remote_version['liceneExpiryDate']); 
				$transient->response[$this->plugin_slug] = $obj;
				set_transient( 'update_plugins', $transient );
				return $transient;
			}
		}else if($remote_version['status'] == 'DENIED'){
			if ( version_compare( $this->current_version, $remote_version['newVersion'], '<' ) ) {
				$obj = new stdClass();
				$obj->slug = $this->slug;
				$obj->new_version = $remote_version['newVersion'];
				$obj->url = 'https://miniorange.com';
				$obj->plugin = $this->plugin_slug;
				$obj->tested = $remote_version['cmsCompatibilityVersion'];
				$obj->icons = array('1x'=>$remote_version['icon']);
				$obj->status_code = $remote_version['status'];
				$obj->license_information = $remote_version['licenseInformation'];
				update_option('mo_saml_license_expiry_date',$remote_version['liceneExpiryDate']);
				$transient->response[$this->plugin_slug] = $obj;
				
				$support_license_expired = true;
				update_option( 'mo_saml_sle', $support_license_expired );
				set_transient( 'update_plugins', $transient );
				return $transient;
			}
		}
		return $transient;
    }

    /**
     * Add our self-hosted description to the filter
     *
     * @param boolean $false
     * @param array $action
     * @param object $arg
     * @return bool|object
     */
    public function mo_saml_check_info($obj, $action, $arg)
    {
        if (($action=='query_plugins' || $action=='plugin_information') &&
            isset($arg->slug) && ($arg->slug === $this->slug || $arg->slug === $this->plugin_file) ) {
            $remote_info = $this->getRemote();
			
			/*Removing the miniOrange implemented plugins_api filter so that 
			WordPress default plugins_api filter can be called to get general 
			information like active installs, rating, reviews etc*/
			
			remove_filter( 'plugins_api', array( $this, 'mo_saml_check_info' ) );

			$api = 	plugins_api( 'plugin_information', array(
								'slug' => $this->slug,
								'fields' => array( 
											'active_installs' => true, 
											'num_ratings'  => true, 
											'rating' => true, 
											'ratings' => true, 
											'reviews' => true
								)
					) 
			);
			
			$active_installs = false;
			$rating  = false;
			$ratings = false;
			$num_ratings = false;
			$description = '';
			$reviews = '';
			
			if( ! is_wp_error( $api ) ) {
				$active_installs =  $api->active_installs;
				$rating  = $api->rating;
				$ratings = $api->ratings;
				$num_ratings = $api->num_ratings;
				$description = $api->sections['description']; 
				$reviews     =  $api->sections['reviews'];
			}
			
			/*Adding back the miniOrange implemented plugins_api filter*/
			
			add_filter( 'plugins_api', array( $this, 'mo_saml_check_info' ), 10, 3 );
			
			if($remote_info['status'] == 'SUCCESS'){
				$support_license_expired = false;
				update_option( 'mo_saml_sle', $support_license_expired );
				if ( version_compare( $this->current_version, $remote_info['newVersion'], '<=' ) ) {
					$remoteObj = new stdClass();
					$remoteObj-> slug = $this->slug;
					$remoteObj-> name = $remote_info['pluginName'];
					$remoteObj-> plugin = $this->plugin_slug;
					$remoteObj-> version = $remote_info['newVersion'];
					$remoteObj-> new_version = $remote_info['newVersion'];
					$remoteObj-> tested = $remote_info['cmsCompatibilityVersion'];
					$remoteObj->requires = $remote_info['cmsMinVersion'];
					$remoteObj->requires_php = $remote_info['phpMinVersion'];
					$remoteObj->compatibility = array($remote_info['cmsCompatibilityVersion']);
					$remoteObj->url = $remote_info['cmsPluginUrl'];
					$remoteObj->author = $remote_info['pluginAuthor'];
					$remoteObj->author_profile = $remote_info['pluginAuthorProfile'];
					$remoteObj-> last_updated = $remote_info['lastUpdated'];
					$remoteObj->banners = array('low' => $remote_info['banner']);
					$remoteObj->icons = array('1x'=>$remote_info['icon']);
					$remoteObj-> sections = array(
					 'changelog' => $remote_info['changelog'],
					 'license_information' => _x( $remote_info['licenseInformation'],      'Plugin installer section title'),
					 'description' => $description,
					 'Reviews' => $reviews
					 );
					
					$hashValue = $this->getAuthToken();
					$currentTimeInMillis = round ( microtime ( true ) * 1000 );
					$currentTimeInMillis = number_format ( $currentTimeInMillis, 0, '', '' );
				
					$remoteObj-> download_link = mo_options_plugin_constants::HOSTNAME . '/moas/plugin/download-update?pluginSlug=' .
                        $this->plugin_slug. '&licensePlanName='.mo_options_plugin_constants::LICENSE_PLAN_NAME.'&customerId=' . get_option( 'mo_saml_admin_customer_key' ) . '&licenseType='.mo_options_plugin_constants::LICENSE_TYPE.'&authToken=' . $hashValue . '&otpToken=' . $currentTimeInMillis;
					$remoteObj-> package = $remoteObj-> download_link;
					$remoteObj-> external = '';
					$remoteObj-> homepage = $remote_info['homepage'];
					$remoteObj-> reviews = true;
					$remoteObj-> active_installs = $active_installs;
					$remoteObj-> rating = $rating;
					$remoteObj-> ratings = $ratings;
					$remoteObj-> num_ratings = $num_ratings;
					
					update_option('mo_saml_license_expiry_date',$remote_info['liceneExpiryDate']);
					return $remoteObj;
				}
			}else if($remote_info['status'] == 'DENIED'){
				if ( version_compare( $this->current_version, $remote_info['newVersion'], '<' ) ) {
					$remoteObj = new stdClass();
					$remoteObj-> slug = $this->slug;
					$remoteObj-> plugin = $this->plugin_slug;
					$remoteObj-> name = $remote_info['pluginName'];
					$remoteObj-> version = $remote_info['newVersion'];
					$remoteObj-> new_version = $remote_info['newVersion'];
					$remoteObj-> tested = $remote_info['cmsCompatibilityVersion'];
					$remoteObj->requires = $remote_info['cmsMinVersion'];
					$remoteObj->requires_php = $remote_info['phpMinVersion'];
					$remoteObj->compatibility = array($remote_info['cmsCompatibilityVersion']);
					$remoteObj->url = $remote_info['cmsPluginUrl'];
					$remoteObj->author = $remote_info['pluginAuthor'];
					$remoteObj->author_profile = $remote_info['pluginAuthorProfile'];
					$remoteObj-> last_updated = $remote_info['lastUpdated'];
					$remoteObj->banners = array('low' => $remote_info['banner']);
					$remoteObj->icons = array('1x'=>$remote_info['icon']);
					$remoteObj-> sections = array(
					 'changelog' => $remote_info['changelog'],
					 'license_information' => _x( $remote_info['licenseInformation'],      'Plugin installer section title'),
					 'description' => $description,
					 'Reviews' => $reviews
					 );
					
					$remoteObj-> external = '';
					$remoteObj-> homepage = $remote_info['homepage'];;
					$remoteObj-> reviews = true;
					$remoteObj-> active_installs = $active_installs;
					$remoteObj-> rating = $rating;
					$remoteObj-> ratings = $ratings;
					$remoteObj-> num_ratings = $num_ratings;
					update_option('mo_saml_license_expiry_date',$remote_info['liceneExpiryDate']);
					return $remoteObj;
				}
			}
		}
        return $obj;	
    }

    /**
     * Return the remote version
     *
     * @return string $remote_version
     */
    private function getRemote()
    {
		//$customerKey = '51872';
		//$apiKey = 'zE0DSZQIJxuyxXPmRn3YSWhrhZtg0SUA';
		$customerKey = get_option ( 'mo_saml_admin_customer_key' );
		$apiKey = get_option ( 'mo_saml_admin_api_key' );
		
		$currentTimeInMillis = round ( microtime ( true ) * 1000 );
		$stringToHash = $customerKey . number_format ( $currentTimeInMillis, 0, '', '' ) . $apiKey;
		$hashValue = hash ( "sha512", $stringToHash );
		$currentTimeInMillis = number_format ( $currentTimeInMillis, 0, '', '' );
		
		$body_paramers = array(
				'pluginSlug'       => $this->plugin_slug,
				'licensePlanName'  => mo_options_plugin_constants::LICENSE_PLAN_NAME,
				'customerId'       => $customerKey,
				'licenseType'      => mo_options_plugin_constants::LICENSE_TYPE
            );
			
		$params = array(
			'headers' => array(
				'Content-Type'   => 'application/json; charset=utf-8',
				'Customer-Key'   => $customerKey,
				'Timestamp'      => $currentTimeInMillis,
				'Authorization'  => $hashValue
			),
            'body'               => json_encode($body_paramers),
			'method'             => 'POST',
            'data_format'        => 'body',
			'sslverify'          => false
			
        );
		
        $response = wp_remote_post($this->update_path, $params );
		if ( !is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) === 200 ) {
			$response_array = json_decode($response['body'], true);
			return $response_array;
        }

        return false;
    }
	
	private function getAuthToken(){
		$customerKey = get_option ( 'mo_saml_admin_customer_key' );
		$apiKey = get_option ( 'mo_saml_admin_api_key' );
		$currentTimeInMillis = round ( microtime ( true ) * 1000 );

		/* Creating the Hash using SHA-512 algorithm */
		$stringToHash = $customerKey . number_format ( $currentTimeInMillis, 0, '', '' ) . $apiKey;
		$hashValue = hash ( "sha512", $stringToHash );
		return $hashValue;
	}
	
	function zipData($source, $destination) {
		if (extension_loaded('zip') && file_exists($source) && count(glob($source . DIRECTORY_SEPARATOR . '*')) !== 0) {
			$zip = new ZipArchive();
			if ($zip->open($destination, ZIPARCHIVE::CREATE)) {
				$source = realpath($source);
				if (is_dir($source) === true) {
					$iterator = new RecursiveDirectoryIterator($source);
					// skip dot files while iterating
					$iterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
					$files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
					
					foreach ($files as $file) {
						$file = realpath($file);
						if (is_dir($file) === true) {
							$zip->addEmptyDir(str_replace($source . DIRECTORY_SEPARATOR, '', $file . DIRECTORY_SEPARATOR));
						} else if (is_file($file) === true) {
							$zip->addFromString(str_replace($source . DIRECTORY_SEPARATOR, '', $file), file_get_contents($file));
						}
					}
				} else if (is_file($source)) {
					$zip->addFromString(basename($source), file_get_contents($source));
				}
			}
			return $zip->close();
		}
		return false;
	}
	
	function mo_saml_plugin_update_message($plugin_data, $response){
        if(!array_key_exists('status_code', $plugin_data))
            return;

        if($plugin_data['status_code'] == 'SUCCESS'){
            $uploads_dir = wp_upload_dir();
            $base_uploads_dir = $uploads_dir['basedir'];
            $uploads_dir = rtrim($base_uploads_dir, '/');
            $dir = $uploads_dir . DIRECTORY_SEPARATOR . 'backup';
            $backup =  'miniorange-saml-20-single-sign-on-premium-backup-' . $this->current_version;

            $arr = explode("</ul>", $plugin_data['new_version_changelog']);
            $first = $arr[0];
            $html = $first . '</ul>';

            echo '<div><b>' . __('<br />An automatic backup of current version ' . $this->current_version . ' has been created at the location ' . $dir . ' with the name <span style="color:#0073aa;">' . $backup . '</span>. In case, something breaks during the update, you can revert to your current version by replacing the backup using FTP access.' , 'miniorange-saml-20-single-sign-on') . '</b></div><div style="color: #f00;">' . __( '<br />Take a minute to check the changelog of latest version of the plugin. Here\'s why you need to update:', 'miniorange-saml-20-single-sign-on' ) . '</div>';

            echo '<div style="font-weight: normal;">' . $html . '</div><b>Note:</b> Please click on <b>View Version details</b> link to get complete changelog and license information. Click on <b>Update Now</b> link to update the plugin to latest version.';
        }else if($plugin_data['status_code'] == 'DENIED'){
            echo sprintf( __(  $plugin_data['license_information'] ) );

        }
	}
	
	public function mo_saml_license_key_notice(){

        if(array_key_exists("mosaml-dismiss",$_GET))
            return;
		
		if(get_option('mo_saml_sle') && new DateTime() > get_option('mo-saml-plugin-timer')){
			
			$url = esc_url( add_query_arg( array( 'mosaml-dismiss' => wp_create_nonce( 'saml-dismiss' )) ) );
			echo '<script>
				function moSAMLPaymentSteps() {
					var attr = document.getElementById("mosamlpaymentsteps").style.display;
					if(attr == "none"){
						document.getElementById("mosamlpaymentsteps").style.display = "block";
					}else{
						document.getElementById("mosamlpaymentsteps").style.display = "none";
					}
				}
			</script>';
				echo '<div id="message" style="position:relative" class="notice notice notice-warning"><br /><span class="alignleft" style="color:#a00;font-family: -webkit-pictograph;font-size: 25px;">IMPORTANT!</span><br /><img src="' . plugin_dir_url(__FILE__) . 'images/miniorange-logo.png'  . '" class="alignleft" height="87" width="66" alt="miniOrange logo" style="margin:10px 10px 10px 0; height:128px; width: 128px;"><h3>miniOrange SAML 2.0 Single Sign-On Support & Maintenance License Expired</h3><p>Your miniOrange SAML 2.0 Single Sign-On license is expired. This means you’re missing out on latest security patches, compatibility with the latest PHP versions and Wordpress. Most importantly you’ll be missing out on our awesome support! </p>
		<p><a href="'. mo_options_plugin_constants::HOSTNAME . '/moas/login?redirectUrl=' . mo_options_plugin_constants::HOSTNAME . '/moas/admin/customer/licenserenewals?renewalrequest='.mo_options_plugin_constants::LICENSE_TYPE. '" class="button button-primary" target="_blank">Renew your support license</a>&nbsp;&nbsp;<b><a href="#" onclick="moSAMLPaymentSteps()">Click here</a> to know how to renew?</b><div id="mosamlpaymentsteps"  style="display: none;"><br /><ul style="list-style: disc;margin-left: 15px;">
<li>Click on above button to login into miniOrange.</li>
<li>You will be redirected to plugin renewal page after login.</li>
<li>If the plugin license plan is not selected then choose the right one from the dropdown, otherwise contact <b><a href="mailto:info@xecurify.com.com">info@xecurify.com.com</a></b> to know about your license plan.</li>
<li>You will see the plugin renewal amount.</li>
<li>Fill up your Credit Card information to make the payment.</li>
<li>Once the payment is done, click on <b>Check Again</b> button from the Force Update area of your WordPress admin dashboard or wait for a day to get the automatic update.</li>
<li>Click on <b>Update Now</b> link to install the latest version of the plugin from plugin manager area of your admin dashboard.</li>
</ul>In case, you are facing any difficulty in installing the update, please contact <b><a href="mailto:info@xecurify.com.com">info@xecurify.com.com</a></b>.
Our Support Executive will assist you in installing the updates.<br /><i>For more information, please contact <b><a href="mailto:info@xecurify.com.com">info@xecurify.com.com</a></b>.</i></div><a href="' . $url . '" class="alignright button button-link">Dismiss</a></p>
		<div class="clear"></div></div>';
		}
		
	}
	
	public function mo_saml_dismiss_notice(){
		
		if( empty( $_GET['mosaml-dismiss'] ) ) {
			return;
		}

		// Invalid nonce
		if( !wp_verify_nonce( $_GET['mosaml-dismiss'], 'saml-dismiss' ) ) {
			return;
		}
		
		if(isset($_GET['mosaml-dismiss']) && wp_verify_nonce( $_GET['mosaml-dismiss'], 'saml-dismiss' )){
			//delete_transient( "mo-saml-plugin-timer" );
			$dateTime = new DateTime();
			$dateTime->modify('+1 day');
			update_option('mo-saml-plugin-timer', $dateTime);
		}
		
	}

    function mo_saml_create_backup_dir(){

        $dir = plugin_dir_path( __FILE__ );
        $dir = rtrim($dir, '/');
        $dir = rtrim($dir, '\\');

        $plugin_data = get_plugin_data(__FILE__);

        $plugin_dir_name = $plugin_data['TextDomain'];

        $uploads_dir = wp_upload_dir();
        $base_uploads_dir = $uploads_dir['basedir'];
        $uploads_dir = rtrim($base_uploads_dir, '/');
        $backup_path = $uploads_dir . DIRECTORY_SEPARATOR . 'backup' . DIRECTORY_SEPARATOR . $plugin_dir_name . '-premium-backup-' . $this->current_version;
        if(!file_exists($backup_path))
            mkdir($backup_path, 0777, true);

        $source = $dir;
        $destination = $backup_path;

        $this->mo_saml_copy_files_to_backup_dir($source, $destination);


    }



    function mo_saml_copy_files_to_backup_dir($dir, $backup_path){
        if(is_dir($dir))
            $plugin_dir_content = scandir($dir);

        if(empty($plugin_dir_content))
            return;

        foreach($plugin_dir_content as $content){
            if($content == '.' || $content == '..')
                continue;
            $plugin_sub_dir = $dir . DIRECTORY_SEPARATOR . $content;
            $backup_sub_dir = $backup_path . DIRECTORY_SEPARATOR . $content;
            if(is_dir($plugin_sub_dir)) {
                if(!file_exists($backup_sub_dir))
                    mkdir($backup_sub_dir, 0777, true);
                $this->mo_saml_copy_files_to_backup_dir($plugin_sub_dir, $backup_sub_dir);
            } else {
                copy($plugin_sub_dir, $backup_sub_dir);
            }
        }

    }

}

function mo_saml_update()
    {
        if(mo_saml_is_customer_registered()) {
            $host_name = mo_options_plugin_constants::HOSTNAME;
            $plugin_current_version = mo_options_plugin_constants::Version;
            $plugin_remote_path = $host_name . '/moas/api/plugin/metadata';
            $plugin_slug = plugin_basename(dirname(__FILE__) . '/login.php');
            $updateFramework = new mo_saml_update_framework ($plugin_current_version, $plugin_remote_path, $plugin_slug);
            add_action("in_plugin_update_message-{$plugin_slug}", array($updateFramework, 'mo_saml_plugin_update_message'), 10, 2);
            add_action('admin_head', array($updateFramework, 'mo_saml_license_key_notice'));
            add_action('admin_notices', array($updateFramework, 'mo_saml_dismiss_notice'), 50);

            if (get_option('mo_saml_sle')) {
                update_option('mo_saml_sle_message', 'Your SAML plugin license hase been expired. You are missing out on updates and support! Please <a href="' . mo_options_plugin_constants::HOSTNAME . '/moas/login?redirectUrl=' . mo_options_plugin_constants::HOSTNAME . '/moas/admin/customer/licenserenewals?renewalrequest=' . mo_options_plugin_constants::LICENSE_TYPE . ' " target="_blank"><b>Click Here</b></a> to renew the Support and Maintenace plan.');
            }
        }
    }
