<?php
/*
Plugin Name: miniOrange SSO using SAML 2.0
Plugin URI: http://miniorange.com/
Description: (Standard)miniOrange SAML 2.0 SSO enables user to perform Single Sign On with any SAML 2.0 enabled Identity Provider.
Version: 16.0.3
Author: miniOrange
Author URI: http://miniorange.com/
*/


include_once dirname(__FILE__) . "\57\x6d\x6f\137\x6c\157\x67\151\x6e\x5f\163\141\x6d\x6c\137\163\x73\x6f\137\167\151\x64\x67\145\164\56\160\x68\x70";
include_once "\170\155\154\163\145\143\154\x69\x62\163\x2e\160\150\x70";
use RobRichards\XMLSecLibs\XMLSecurityKey;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecEnc;
require "\155\x6f\55\163\x61\x6d\154\x2d\143\x6c\141\163\x73\55\x63\x75\163\x74\x6f\x6d\x65\x72\x2e\x70\150\160";
require "\155\x6f\137\x73\x61\x6d\x6c\137\x73\145\164\x74\151\156\x67\x73\x5f\x70\x61\147\x65\x2e\160\150\160";
require "\115\145\x74\141\x64\141\164\141\122\x65\141\x64\145\x72\56\160\x68\x70";
require "\143\x65\162\x74\151\146\151\143\141\x74\145\137\165\x74\151\154\151\x74\x79\56\160\150\160";
require_once "\111\x6d\160\x6f\162\164\x2d\x65\x78\160\x6f\x72\164\56\160\150\x70";
require_once "\x6d\x6f\x2d\x73\x61\155\154\x2d\x70\154\x75\147\x69\156\x2d\x76\x65\162\163\x69\157\x6e\x2d\x75\160\144\x61\x74\x65\x2e\x70\x68\x70";
class saml_mo_login
{
    function __construct()
    {
        add_action("\141\x64\x6d\x69\x6e\137\x6d\145\x6e\165", array($this, "\x6d\151\156\x69\157\162\141\x6e\x67\x65\x5f\163\163\x6f\137\155\145\x6e\x75"));
        add_action("\141\144\155\151\156\137\151\156\x69\164", array($this, "\x6d\151\x6e\x69\157\162\141\156\x67\145\x5f\x6c\x6f\x67\151\156\x5f\x77\x69\x64\147\145\x74\x5f\163\141\x6d\154\x5f\163\141\x76\x65\137\x73\145\x74\164\x69\x6e\x67\163"));
        add_action("\x61\144\x6d\151\x6e\x5f\145\x6e\x71\x75\x65\165\145\137\x73\143\162\151\x70\x74\x73", array($this, "\x70\x6c\165\147\x69\156\137\x73\x65\164\164\x69\x6e\x67\x73\137\x73\164\x79\154\145"));
        register_deactivation_hook(__FILE__, array($this, "\155\x6f\137\163\163\x6f\x5f\163\141\x6d\x6c\137\x64\145\x61\x63\164\151\x76\141\x74\145"));
        add_action("\x61\144\155\151\156\x5f\x65\156\x71\x75\x65\x75\145\137\163\x63\162\x69\x70\x74\163", array($this, "\x70\x6c\x75\147\151\x6e\137\x73\145\x74\164\x69\156\147\163\x5f\163\x63\162\151\x70\164"));
        remove_action("\x61\x64\155\x69\156\x5f\156\x6f\164\x69\x63\145\x73", array($this, "\x6d\x6f\137\x73\x61\155\x6c\137\163\165\x63\143\x65\x73\163\x5f\x6d\x65\x73\x73\x61\147\x65"));
        remove_action("\141\144\x6d\151\x6e\x5f\x6e\x6f\164\x69\143\x65\163", array($this, "\155\157\x5f\163\141\155\x6c\137\145\x72\162\157\162\x5f\155\145\x73\163\141\147\145"));
        add_action("\167\x70\x5f\x61\165\x74\x68\x65\156\x74\151\x63\141\164\145", array($this, "\155\x6f\x5f\x73\x61\x6d\x6c\137\x61\165\x74\150\145\x6e\x74\151\x63\x61\x74\x65"));
        add_action("\167\x70", array($this, "\x6d\x6f\137\x73\141\155\x6c\x5f\x61\x75\164\x6f\137\x72\x65\x64\x69\162\145\143\164"));
        add_action("\141\x64\x6d\151\x6e\x5f\x69\156\151\x74", "\155\157\137\x73\x61\x6d\154\x5f\144\157\167\156\154\157\x61\144");
        add_action("\154\x6f\147\151\156\137\146\x6f\x72\x6d", array($this, "\x6d\157\137\163\x61\155\154\x5f\155\157\144\151\x66\x79\137\154\x6f\x67\x69\x6e\x5f\146\x6f\162\x6d"));
        add_shortcode("\x4d\117\x5f\x53\x41\x4d\114\137\x46\117\122\115", array($this, "\x6d\x6f\137\x67\145\x74\137\x73\141\155\154\137\x73\150\x6f\162\x74\x63\157\x64\145"));
        add_action("\141\144\x6d\x69\x6e\x5f\x69\x6e\x69\x74", array($this, "\x64\x65\x66\141\x75\x6c\x74\137\x63\x65\x72\x74\x69\x66\x69\143\141\164\x65"));
        register_activation_hook(__FILE__, array($this, "\155\157\137\x73\x61\155\x6c\x5f\143\150\145\x63\153\137\x6f\160\x65\156\163\163\x6c"));
        add_action("\160\x6c\165\x67\151\156\137\141\x63\x74\x69\157\156\137\154\151\x6e\x6b\163\137" . plugin_basename(__FILE__), array($this, "\x6d\x6f\x5f\x73\141\155\154\137\x70\154\x75\147\x69\156\x5f\141\143\164\151\157\x6e\x5f\154\x69\156\x6b\x73"));
    }
    function default_certificate()
    {
        $l3 = file_get_contents(plugin_dir_path(__FILE__) . "\x72\x65\163\x6f\x75\x72\143\x65\x73" . DIRECTORY_SEPARATOR . "\155\x69\x6e\151\157\x72\141\156\147\145\x5f\163\160\137\62\x30\x32\x30\x2e\x63\162\x74");
        $m2 = file_get_contents(plugin_dir_path(__FILE__) . "\162\x65\x73\x6f\x75\x72\143\x65\163" . DIRECTORY_SEPARATOR . "\155\x69\x6e\151\x6f\x72\141\x6e\147\x65\x5f\x73\160\x5f\x32\x30\x32\60\x5f\160\162\151\x76\56\153\145\171");
        if (!(!get_option("\x6d\157\x5f\163\x61\155\x6c\137\x63\x75\x72\x72\x65\x6e\x74\137\x63\x65\x72\x74") && !get_option("\x6d\157\x5f\163\x61\x6d\x6c\x5f\143\x75\162\x72\x65\156\x74\137\x63\145\162\164\137\x70\162\x69\x76\141\x74\145\137\153\145\x79"))) {
            goto zo;
        }
        if (get_option("\155\x6f\x5f\163\x61\155\154\x5f\143\x65\x72\164") && get_option("\155\x6f\x5f\x73\x61\155\154\137\143\145\162\x74\x5f\160\162\x69\166\141\164\145\137\x6b\x65\171")) {
            goto yT;
        }
        update_option("\x6d\x6f\x5f\x73\x61\155\x6c\x5f\143\165\162\162\145\x6e\164\137\x63\145\x72\x74", $l3);
        update_option("\x6d\157\137\x73\x61\x6d\x6c\137\143\165\162\x72\x65\x6e\164\x5f\143\x65\162\164\x5f\x70\x72\151\x76\141\164\x65\137\153\x65\171", $m2);
        goto s0;
        yT:
        update_option("\x6d\x6f\x5f\x73\141\155\154\137\x63\x75\x72\162\145\156\x74\x5f\x63\x65\162\x74", get_option("\155\x6f\137\x73\x61\x6d\154\x5f\x63\x65\x72\x74"));
        update_option("\155\x6f\137\163\141\x6d\154\x5f\x63\165\162\x72\x65\156\x74\137\x63\145\x72\x74\x5f\x70\162\x69\166\141\164\x65\x5f\153\x65\x79", get_option("\x6d\157\x5f\163\141\155\154\x5f\x63\145\x72\164\x5f\160\162\x69\166\141\164\145\x5f\153\145\x79"));
        s0:
        zo:
    }
    function mo_login_widget_saml_options()
    {
        global $wpdb;
        update_option("\x6d\157\137\x73\x61\155\154\137\150\157\163\x74\x5f\156\141\x6d\x65", "\150\x74\164\160\163\72\57\x2f\154\x6f\x67\151\x6e\56\170\145\x63\x75\162\x69\146\171\x2e\143\x6f\155");
        $f0 = get_option("\155\x6f\x5f\163\x61\155\154\137\150\157\x73\164\137\x6e\x61\155\x65");
        mo_register_saml_sso();
    }
    function mo_saml_check_openssl()
    {
        if (mo_saml_is_extension_installed("\x6f\160\x65\x6e\163\163\154")) {
            goto H6;
        }
        wp_die("\120\x48\x50\40\157\x70\x65\156\163\x73\154\x20\145\x78\x74\x65\156\x73\151\x6f\156\x20\x69\163\40\156\x6f\164\x20\x69\156\163\164\141\154\x6c\145\144\x20\x6f\162\x20\x64\x69\x73\141\x62\154\x65\x64\x2c\160\154\145\x61\163\145\x20\x65\156\141\x62\x6c\x65\40\151\x74\40\164\157\40\x61\x63\164\x69\x76\141\164\x65\40\x74\150\x65\x20\x70\154\165\x67\151\x6e\56");
        H6:
        add_option("\x41\x63\164\151\x76\141\x74\145\144\x5f\120\x6c\165\147\x69\x6e", "\x50\x6c\165\x67\x69\156\55\x53\x6c\x75\147");
    }
    function mo_saml_success_message()
    {
        $JB = "\145\162\162\x6f\162";
        $sc = get_option("\155\157\x5f\x73\141\x6d\x6c\x5f\155\x65\163\163\x61\147\145");
        echo "\x3c\144\x69\x76\40\x63\x6c\141\163\x73\75\47" . $JB . "\x27\76\40\74\160\76" . $sc . "\x3c\x2f\x70\76\74\x2f\x64\x69\166\x3e";
    }
    function mo_saml_error_message()
    {
        $JB = "\x75\x70\144\x61\x74\x65\x64";
        $sc = get_option("\155\x6f\x5f\x73\141\x6d\x6c\137\x6d\x65\163\x73\x61\147\145");
        echo "\74\x64\151\166\x20\143\x6c\x61\163\x73\75\x27" . $JB . "\47\x3e\40\x3c\x70\76" . $sc . "\x3c\57\x70\x3e\74\57\144\151\x76\76";
    }
    public function mo_sso_saml_deactivate()
    {
        if (!is_multisite()) {
            goto fa;
        }
        global $wpdb;
        $NG = $wpdb->get_col("\x53\105\x4c\105\x43\124\x20\142\x6c\157\x67\x5f\151\144\x20\x46\122\117\115\40{$wpdb->blogs}");
        $Kw = get_current_blog_id();
        do_action("\155\157\137\x73\x61\x6d\154\137\146\x6c\165\x73\150\137\143\141\x63\150\x65");
        foreach ($NG as $blog_id) {
            switch_to_blog($blog_id);
            delete_option("\x6d\157\x5f\x73\141\155\154\x5f\x68\157\x73\164\137\x6e\141\155\145");
            delete_option("\x6d\x6f\x5f\163\141\155\154\137\156\x65\167\137\x72\x65\x67\151\x73\x74\x72\x61\164\151\157\156");
            delete_option("\x6d\157\x5f\163\x61\155\154\x5f\x61\144\x6d\151\x6e\x5f\160\x68\x6f\x6e\145");
            delete_option("\x6d\x6f\x5f\x73\141\x6d\154\x5f\141\144\x6d\151\156\x5f\160\141\x73\163\167\x6f\162\144");
            delete_option("\x6d\x6f\137\x73\x61\155\x6c\x5f\x76\x65\162\151\x66\171\x5f\x63\x75\163\164\157\x6d\145\x72");
            delete_option("\155\157\137\163\141\155\154\137\141\144\x6d\151\156\x5f\143\x75\163\x74\157\x6d\145\x72\x5f\x6b\x65\x79");
            delete_option("\155\x6f\137\x73\x61\155\154\x5f\141\x64\x6d\151\156\x5f\x61\160\151\x5f\x6b\x65\171");
            delete_option("\155\157\x5f\163\x61\x6d\154\x5f\143\x75\x73\164\157\x6d\x65\162\137\x74\x6f\x6b\145\156");
            delete_option("\x6d\x6f\137\163\141\155\x6c\x5f\155\x65\163\163\141\147\x65");
            delete_option("\x6d\157\137\163\141\x6d\x6c\137\162\145\147\x69\163\x74\x72\x61\164\151\157\x6e\137\x73\x74\141\164\x75\163");
            delete_option("\155\157\137\x73\x61\155\x6c\x5f\151\x64\160\137\x63\157\x6e\x66\x69\x67\137\143\x6f\155\x70\154\x65\x74\145");
            delete_option("\x6d\x6f\137\x73\x61\155\154\137\164\162\141\x6e\163\x61\x63\x74\x69\157\x6e\x49\x64");
            delete_option("\x76\x6c\137\x63\150\x65\x63\153\x5f\x74");
            delete_option("\166\154\137\143\150\145\x63\x6b\x5f\x73");
            delete_option("\x6d\157\137\x73\141\x6d\154\137\143\145\x72\x74");
            delete_option("\x6d\157\x5f\163\141\155\x6c\137\143\x65\162\164\137\x70\x72\x69\166\x61\164\x65\x5f\153\x65\x79");
            delete_option("\x6d\x6f\137\x73\x61\155\x6c\137\x65\156\x61\142\154\x65\x5f\x63\x6c\x6f\x75\x64\x5f\x62\162\157\153\x65\162");
            YI:
        }
        r2:
        switch_to_blog($Kw);
        goto yF;
        fa:
        do_action("\155\x6f\137\163\141\155\x6c\x5f\146\154\165\x73\x68\x5f\x63\141\x63\x68\x65");
        delete_option("\x6d\x6f\137\x73\141\x6d\154\137\x68\157\163\164\137\156\x61\x6d\x65");
        delete_option("\x6d\157\x5f\x73\141\x6d\154\x5f\x6e\x65\x77\x5f\x72\x65\x67\x69\163\164\162\141\x74\151\x6f\156");
        delete_option("\x6d\157\137\x73\x61\x6d\154\x5f\141\x64\x6d\x69\x6e\137\x70\x68\x6f\x6e\145");
        delete_option("\155\x6f\137\x73\x61\155\154\137\141\144\155\x69\x6e\137\x70\141\163\163\167\157\x72\144");
        delete_option("\155\x6f\x5f\163\141\155\x6c\x5f\x76\x65\x72\151\146\171\x5f\143\x75\163\x74\157\x6d\145\x72");
        delete_option("\x6d\157\137\163\x61\155\x6c\137\141\144\x6d\x69\x6e\137\143\165\163\x74\x6f\155\x65\x72\137\153\145\171");
        delete_option("\155\157\x5f\163\141\155\154\x5f\141\144\x6d\151\x6e\x5f\x61\x70\151\137\x6b\145\x79");
        delete_option("\x6d\157\137\163\141\x6d\x6c\x5f\x63\x75\163\x74\157\155\145\162\137\164\x6f\153\x65\156");
        delete_option("\155\157\137\163\x61\155\154\x5f\155\x65\163\163\141\x67\145");
        delete_option("\155\x6f\137\163\x61\x6d\154\137\x72\145\x67\151\x73\164\162\x61\164\151\x6f\x6e\x5f\x73\x74\x61\164\165\x73");
        delete_option("\x6d\x6f\137\x73\x61\x6d\x6c\137\x69\144\160\x5f\143\x6f\x6e\x66\x69\x67\137\143\x6f\155\160\154\x65\x74\145");
        delete_option("\x6d\x6f\137\163\141\x6d\x6c\x5f\164\x72\141\156\163\x61\143\164\151\157\x6e\x49\x64");
        delete_option("\166\x6c\x5f\x63\x68\x65\143\x6b\x5f\164");
        delete_option("\x76\x6c\137\143\x68\x65\x63\153\x5f\163");
        delete_option("\155\x6f\x5f\163\x61\155\x6c\x5f\x63\145\162\x74");
        delete_option("\x6d\157\137\163\141\x6d\x6c\x5f\143\145\x72\x74\137\160\x72\x69\x76\141\164\x65\137\x6b\145\171");
        delete_option("\155\x6f\137\x73\x61\155\x6c\x5f\145\x6e\141\142\x6c\x65\x5f\x63\154\x6f\165\x64\137\x62\x72\157\x6b\145\x72");
        yF:
    }
    function mo_saml_show_success_message()
    {
        remove_action("\x61\x64\x6d\151\x6e\137\x6e\157\164\x69\x63\145\163", array($this, "\155\157\x5f\163\x61\155\x6c\x5f\163\x75\x63\x63\145\163\163\x5f\155\x65\163\x73\x61\x67\x65"));
        add_action("\141\144\155\x69\156\137\156\x6f\x74\x69\x63\145\163", array($this, "\155\157\x5f\163\x61\x6d\154\137\145\x72\162\x6f\162\x5f\x6d\x65\x73\163\x61\147\x65"));
    }
    function mo_saml_show_error_message()
    {
        remove_action("\141\x64\155\x69\156\x5f\156\157\x74\x69\143\x65\163", array($this, "\155\x6f\x5f\x73\x61\x6d\x6c\x5f\x65\162\162\157\162\137\155\x65\163\163\x61\x67\145"));
        add_action("\141\144\x6d\x69\x6e\137\156\x6f\164\151\x63\145\163", array($this, "\x6d\x6f\137\163\141\x6d\x6c\x5f\163\x75\143\143\145\x73\x73\137\155\x65\x73\x73\x61\x67\145"));
    }
    function plugin_settings_style($Vn)
    {
        if (!("\164\x6f\x70\x6c\145\166\x65\154\137\160\141\x67\x65\137\x6d\x6f\137\x73\x61\x6d\154\x5f\163\x65\x74\164\151\x6e\x67\x73" != $Vn)) {
            goto ub;
        }
        return;
        ub:
        if (!(isset($_REQUEST["\164\141\142"]) && $_REQUEST["\x74\x61\x62"] == "\x6c\x69\x63\x65\156\x73\151\x6e\147")) {
            goto be;
        }
        wp_enqueue_style("\x6d\157\137\163\x61\x6d\154\x5f\142\x6f\157\164\163\164\x72\141\160\x5f\x63\163\163", plugins_url("\x69\156\143\x6c\x75\x64\145\163\x2f\143\x73\x73\x2f\142\157\x6f\x74\163\x74\162\141\160\57\x62\x6f\x6f\164\x73\164\162\141\x70\56\x6d\x69\x6e\x2e\143\x73\x73", __FILE__), array(), mo_options_plugin_constants::Version, "\x61\154\154");
        be:
        wp_enqueue_style("\x6d\x6f\x5f\x73\141\x6d\x6c\x5f\x61\144\155\151\156\x5f\x73\145\x74\164\x69\x6e\147\163\137\x73\x74\x79\x6c\x65\x5f\x74\162\141\143\153\x65\162", plugins_url("\151\156\143\154\x75\x64\145\x73\57\x63\x73\163\x2f\160\x72\157\147\x72\x65\163\163\x2d\164\x72\x61\143\x6b\x65\x72\x2e\x63\x73\x73", __FILE__), array(), mo_options_plugin_constants::Version, "\141\154\x6c");
        wp_enqueue_style("\x6d\157\x5f\x73\141\x6d\x6c\137\x61\x64\x6d\151\156\x5f\163\x65\164\164\151\x6e\x67\163\x5f\163\164\x79\x6c\145", plugins_url("\151\156\143\154\x75\x64\x65\x73\57\143\x73\163\x2f\163\x74\x79\x6c\145\137\x73\145\x74\164\151\x6e\x67\163\x2e\x6d\151\x6e\x2e\x63\x73\163", __FILE__), array(), mo_options_plugin_constants::Version, "\141\154\x6c");
        wp_enqueue_style("\x6d\157\x5f\163\x61\155\x6c\137\x61\x64\x6d\x69\156\137\x73\145\164\164\x69\x6e\147\163\x5f\160\150\157\156\145\x5f\x73\164\x79\154\x65", plugins_url("\x69\x6e\x63\154\165\x64\x65\163\x2f\143\x73\x73\57\160\150\157\156\x65\x2e\x6d\151\156\x2e\x63\x73\x73", __FILE__), array(), mo_options_plugin_constants::Version, "\141\154\154");
        wp_enqueue_style("\155\157\x5f\163\x61\155\154\x5f\x77\160\x62\x2d\146\x61", plugins_url("\x69\156\x63\154\x75\x64\145\163\x2f\x63\x73\163\57\x66\x6f\x6e\164\x2d\x61\x77\x65\x73\x6f\155\145\x2e\x6d\x69\x6e\56\143\x73\x73", __FILE__), array(), mo_options_plugin_constants::Version, "\141\x6c\154");
    }
    function plugin_settings_script($Vn)
    {
        if (!("\164\157\x70\154\x65\x76\145\154\x5f\160\x61\147\x65\137\x6d\157\x5f\x73\141\x6d\x6c\x5f\x73\x65\x74\164\x69\156\x67\163" != $Vn)) {
            goto Cs;
        }
        return;
        Cs:
        wp_enqueue_script("\152\x71\165\145\162\x79");
        wp_enqueue_script("\x6d\157\x5f\163\141\155\x6c\137\x61\144\155\x69\x6e\137\142\157\157\164\x73\164\x72\141\160\137\x73\x63\162\151\160\x74", plugins_url("\151\x6e\143\154\x75\x64\x65\x73\57\x6a\163\57\x62\x6f\x6f\x74\163\164\162\141\x70\x2e\152\x73", __FILE__), array(), mo_options_plugin_constants::Version, false);
        wp_enqueue_script("\x6d\157\x5f\x73\141\x6d\x6c\137\141\144\x6d\x69\x6e\x5f\x73\145\164\164\151\156\x67\x73\x5f\x73\x63\x72\151\160\164", plugins_url("\x69\156\143\154\x75\144\145\x73\x2f\x6a\x73\57\163\145\x74\x74\x69\x6e\147\163\56\155\x69\x6e\56\x6a\163", __FILE__), array(), mo_options_plugin_constants::Version, false);
        wp_enqueue_script("\x6d\x6f\x5f\x73\141\155\x6c\x5f\141\x64\x6d\151\156\x5f\163\x65\164\x74\x69\x6e\x67\163\x5f\x70\150\x6f\156\x65\x5f\x73\x63\162\151\160\164", plugins_url("\x69\x6e\143\x6c\165\x64\145\x73\x2f\x6a\163\57\x70\x68\157\x6e\145\x2e\x6d\151\x6e\56\152\163", __FILE__), array(), mo_options_plugin_constants::Version, false);
        if (!(isset($_REQUEST["\164\x61\x62"]) && $_REQUEST["\x74\x61\x62"] == "\154\x69\143\145\156\x73\151\x6e\x67")) {
            goto C5;
        }
        wp_enqueue_script("\155\157\x5f\163\x61\155\x6c\137\x6d\x6f\144\145\x72\x6e\151\x7a\x72\137\163\x63\162\151\x70\x74", plugins_url("\x69\156\143\x6c\x75\x64\x65\x73\57\x6a\163\57\x6d\157\x64\x65\162\x6e\151\172\162\x2e\152\163", __FILE__), array(), mo_options_plugin_constants::Version, false);
        wp_enqueue_script("\x6d\x6f\137\163\x61\x6d\154\x5f\160\157\x70\157\166\x65\162\x5f\163\143\x72\151\x70\164", plugins_url("\151\156\143\x6c\165\144\145\163\57\152\x73\x2f\x62\157\157\164\x73\x74\162\141\160\57\160\x6f\160\x70\x65\x72\x2e\155\151\x6e\x2e\152\x73", __FILE__), array(), mo_options_plugin_constants::Version, false);
        wp_enqueue_script("\x6d\x6f\x5f\x73\x61\155\154\x5f\x62\157\157\x74\x73\x74\162\x61\x70\137\x73\143\x72\x69\x70\164", plugins_url("\151\x6e\x63\154\165\144\x65\x73\57\x6a\x73\57\x62\x6f\157\x74\x73\164\x72\141\160\57\142\x6f\157\164\x73\164\162\x61\x70\x2e\155\151\x6e\56\152\163", __FILE__), array(), mo_options_plugin_constants::Version, false);
        C5:
    }
    function mo_saml_activation_message()
    {
        $JB = "\x75\160\x64\x61\x74\x65\144";
        $sc = get_option("\155\157\137\163\141\x6d\154\x5f\x6d\x65\163\163\x61\x67\x65");
        echo "\74\144\x69\166\x20\143\x6c\141\163\163\75\47" . $JB . "\x27\x3e\x20\74\x70\76" . $sc . "\x3c\57\160\76\74\x2f\144\151\x76\76";
    }
    static function mo_check_option_admin_referer($DP)
    {
        return isset($_POST["\157\160\x74\151\157\x6e"]) and $_POST["\157\160\x74\151\157\156"] == $DP and check_admin_referer($DP);
    }
    function miniorange_login_widget_saml_save_settings()
    {
        if (!current_user_can("\x6d\x61\156\x61\x67\x65\x5f\x6f\160\164\x69\157\x6e\x73")) {
            goto y0;
        }
        if (!(is_admin() && get_option("\101\x63\x74\x69\x76\x61\164\x65\x64\x5f\120\x6c\165\147\x69\x6e") == "\120\154\x75\147\151\x6e\x2d\x53\154\165\147")) {
            goto Hi;
        }
        delete_option("\101\143\164\x69\x76\x61\x74\x65\x64\x5f\120\154\x75\x67\151\x6e");
        update_option("\x6d\157\137\x73\x61\155\154\137\155\x65\x73\163\141\x67\145", "\x47\157\40\164\x6f\40\160\154\x75\147\x69\156\40\74\142\76\74\141\40\150\x72\145\146\x3d\42\x61\x64\155\151\x6e\56\160\150\x70\77\160\141\147\x65\x3d\x6d\157\x5f\x73\x61\x6d\154\x5f\163\x65\164\x74\151\x6e\147\163\x22\76\x73\x65\x74\x74\x69\156\147\163\74\57\141\76\x3c\57\x62\76\40\x74\x6f\x20\143\157\x6e\x66\151\147\x75\x72\x65\40\123\x41\115\114\x20\123\x69\x6e\x67\154\145\40\x53\x69\x67\x6e\x20\x4f\x6e\40\x62\x79\x20\155\151\156\x69\117\x72\141\156\x67\x65\56");
        add_action("\x61\x64\155\x69\x6e\137\x6e\x6f\x74\x69\143\145\163", array($this, "\155\157\137\163\141\155\154\137\141\x63\164\x69\x76\141\164\151\157\x6e\x5f\155\145\x73\163\141\x67\x65"));
        Hi:
        if (!self::mo_check_option_admin_referer("\154\157\x67\151\x6e\137\167\x69\x64\x67\x65\x74\137\x73\x61\155\x6c\137\x73\x61\166\x65\x5f\x73\x65\164\164\x69\x6e\x67\x73")) {
            goto EB;
        }
        if (mo_saml_is_extension_installed("\143\x75\162\154")) {
            goto Oe;
        }
        update_option("\x6d\x6f\x5f\x73\141\x6d\154\x5f\x6d\x65\163\163\x61\147\145", "\x45\122\122\117\x52\x3a\x50\110\120\x20\143\125\x52\x4c\x20\x65\x78\164\145\156\x73\x69\157\x6e\40\x69\163\40\x6e\x6f\x74\40\151\x6e\163\x74\x61\154\154\x65\x64\x20\157\162\40\x64\x69\163\x61\142\x6c\145\144\56\40\123\x61\x76\145\40\111\144\x65\156\164\x69\x74\x79\40\120\x72\157\166\x69\x64\x65\x72\x20\x43\157\x6e\146\x69\147\x75\x72\x61\164\151\157\156\40\146\x61\151\x6c\x65\x64\56");
        $this->mo_saml_show_error_message();
        return;
        Oe:
        $JS = '';
        $PB = '';
        $UJ = '';
        $G0 = '';
        $gn = '';
        $BQ = '';
        $Ov = '';
        $eY = '';
        if ($this->mo_saml_check_empty_or_null($_POST["\163\141\155\154\x5f\151\144\x65\x6e\x74\x69\x74\x79\137\x6e\141\155\x65"]) || $this->mo_saml_check_empty_or_null($_POST["\163\141\x6d\x6c\x5f\x6c\x6f\x67\x69\x6e\137\165\162\154"]) || $this->mo_saml_check_empty_or_null($_POST["\163\x61\155\154\137\x69\163\x73\165\x65\x72"]) || $this->mo_saml_check_empty_or_null($_POST["\x73\141\155\154\x5f\x6e\x61\x6d\145\x69\144\137\146\x6f\162\x6d\x61\164"])) {
            goto i1;
        }
        if (!preg_match("\57\x5e\x5c\x77\52\x24\x2f", $_POST["\163\141\x6d\154\137\x69\144\x65\156\164\151\164\171\x5f\156\141\155\x65"])) {
            goto BV;
        }
        $JS = htmlspecialchars(trim($_POST["\x73\x61\155\x6c\137\151\144\145\x6e\x74\151\164\x79\x5f\x6e\x61\155\145"]));
        $UJ = htmlspecialchars(trim($_POST["\163\x61\155\154\x5f\154\157\147\151\156\x5f\165\162\x6c"]));
        if (!array_key_exists("\x73\x61\x6d\154\137\x6c\157\x67\x69\x6e\137\x62\151\156\x64\151\156\x67\x5f\164\171\x70\145", $_POST)) {
            goto W8;
        }
        $PB = htmlspecialchars($_POST["\163\x61\x6d\x6c\137\x6c\x6f\147\151\x6e\137\x62\151\x6e\x64\151\x6e\x67\x5f\x74\171\160\x65"]);
        W8:
        $BQ = htmlspecialchars(trim($_POST["\163\141\155\154\137\x69\x73\x73\x75\145\x72"]));
        $Ys = htmlspecialchars(trim($_POST["\163\141\x6d\x6c\x5f\x69\x64\x65\156\164\151\164\171\x5f\160\162\x6f\x76\151\x64\x65\162\x5f\147\165\151\x64\x65\137\x6e\x61\155\x65"]));
        $Ov = $_POST["\163\x61\155\154\137\170\65\x30\x39\x5f\143\145\162\x74\151\146\x69\143\141\x74\x65"];
        $gn = htmlspecialchars($_POST["\163\141\x6d\x6c\137\156\141\x6d\145\151\x64\x5f\x66\x6f\x72\x6d\141\x74"]);
        goto TD;
        BV:
        update_option("\155\157\x5f\163\141\155\x6c\137\155\145\x73\163\x61\147\x65", "\120\x6c\x65\x61\163\145\x20\155\141\x74\143\150\40\164\x68\145\x20\x72\x65\x71\x75\145\163\x74\145\144\x20\146\x6f\162\x6d\x61\164\x20\x66\x6f\162\40\x49\144\145\x6e\x74\x69\164\171\x20\120\162\157\x76\x69\x64\x65\162\40\116\141\x6d\145\56\x20\x4f\x6e\154\x79\x20\141\x6c\160\x68\x61\142\x65\x74\x73\x2c\40\156\165\155\142\x65\x72\163\x20\141\156\144\x20\165\x6e\x64\x65\162\163\x63\157\162\x65\40\151\x73\40\x61\154\154\157\x77\145\x64\x2e");
        $this->mo_saml_show_error_message();
        return;
        TD:
        goto JR;
        i1:
        update_option("\x6d\157\137\163\141\155\154\137\155\x65\163\163\141\x67\x65", "\x41\x6c\x6c\x20\164\x68\145\x20\146\151\x65\154\x64\163\40\x61\162\x65\x20\162\145\x71\165\151\162\145\144\x2e\x20\120\154\x65\x61\x73\x65\x20\145\156\x74\x65\x72\40\x76\x61\x6c\x69\x64\40\145\156\164\x72\151\x65\x73\56");
        $this->mo_saml_show_error_message();
        return;
        JR:
        update_option("\x73\141\x6d\154\x5f\x69\x64\145\156\164\x69\x74\171\137\156\x61\x6d\x65", $JS);
        update_option("\163\141\155\x6c\137\x6c\157\x67\x69\x6e\x5f\x62\151\156\x64\x69\156\147\137\164\x79\x70\x65", $PB);
        update_option("\163\x61\x6d\x6c\137\x6c\157\147\151\x6e\x5f\x75\x72\x6c", $UJ);
        update_option("\x73\141\x6d\154\137\x6c\x6f\147\x6f\x75\x74\137\142\151\x6e\x64\151\x6e\147\137\164\171\160\x65", $G0);
        update_option("\163\x61\x6d\154\137\x69\x73\x73\x75\x65\162", $BQ);
        update_option("\163\141\155\154\x5f\156\x61\155\x65\x69\144\x5f\146\x6f\x72\155\141\164", $gn);
        update_option("\x73\141\x6d\154\137\x69\144\x65\x6e\164\151\164\x79\x5f\160\162\157\166\x69\x64\145\x72\137\147\x75\x69\144\x65\x5f\x6e\x61\x6d\x65", $Ys);
        if (isset($_POST["\x73\141\x6d\154\x5f\162\145\161\165\145\x73\x74\137\163\x69\147\156\x65\144"])) {
            goto fY;
        }
        update_option("\x73\x61\155\x6c\x5f\x72\145\161\x75\145\163\164\137\x73\151\147\156\x65\x64", "\x75\x6e\x63\150\145\143\x6b\x65\144");
        goto aA;
        fY:
        update_option("\163\141\x6d\x6c\x5f\162\145\x71\165\x65\x73\164\137\163\x69\147\156\145\x64", "\143\150\145\x63\x6b\145\144");
        aA:
        foreach ($Ov as $Xr => $tq) {
            if (empty($tq)) {
                goto dZ;
            }
            $Ov[$Xr] = SAMLSPUtilities::sanitize_certificate($tq);
            if (@openssl_x509_read($Ov[$Xr])) {
                goto mU;
            }
            update_option("\155\157\x5f\163\141\x6d\x6c\137\x6d\145\x73\x73\x61\147\x65", "\111\x6e\166\x61\x6c\x69\144\x20\x63\145\162\x74\151\146\x69\x63\x61\164\145\x3a\40\120\154\145\x61\163\x65\40\160\162\x6f\x76\151\144\145\x20\x61\40\x76\x61\154\151\144\40\x63\145\x72\164\151\146\151\x63\141\164\145\x2e");
            $this->mo_saml_show_error_message();
            delete_option("\163\141\155\x6c\x5f\170\65\60\x39\137\143\145\162\164\151\146\x69\x63\x61\x74\x65");
            return;
            mU:
            goto pE;
            dZ:
            unset($Ov[$Xr]);
            pE:
            eZ:
        }
        cg:
        if (!empty($Ov)) {
            goto KP;
        }
        update_option("\x6d\157\x5f\163\x61\x6d\x6c\x5f\x6d\145\x73\163\x61\147\x65", "\111\x6e\x76\x61\154\x69\144\x20\x43\x65\162\164\x69\x66\151\x63\141\x74\145\72\120\x6c\145\x61\163\145\40\160\162\157\x76\151\144\145\40\141\x20\x63\145\x72\164\x69\x66\151\143\x61\164\145");
        $this->mo_saml_show_error_message();
        return;
        KP:
        update_option("\x73\141\155\x6c\x5f\x78\65\60\x39\x5f\143\x65\x72\x74\151\x66\151\x63\x61\164\x65", maybe_serialize($Ov));
        if (isset($_POST["\163\141\155\154\137\x72\145\x73\x70\157\156\x73\145\x5f\x73\x69\x67\156\145\144"])) {
            goto kS;
        }
        update_option("\x73\x61\x6d\154\137\162\x65\x73\160\x6f\156\x73\145\137\x73\x69\x67\x6e\x65\144", "\x59\x65\x73");
        goto s6;
        kS:
        update_option("\x73\141\155\x6c\x5f\162\145\163\x70\x6f\x6e\163\145\137\163\x69\147\x6e\x65\x64", "\x63\150\145\143\x6b\145\144");
        s6:
        if (isset($_POST["\x73\141\155\154\137\141\x73\163\145\x72\x74\x69\157\x6e\x5f\163\x69\x67\156\145\x64"])) {
            goto qE;
        }
        update_option("\163\x61\x6d\154\x5f\141\163\163\x65\x72\164\151\157\156\x5f\x73\151\x67\x6e\145\144", "\131\x65\163");
        goto BK;
        qE:
        update_option("\x73\141\155\x6c\x5f\x61\163\x73\145\x72\164\x69\157\156\x5f\x73\x69\x67\156\x65\144", "\x63\150\145\x63\153\x65\x64");
        BK:
        if (array_key_exists("\145\x6e\141\x62\154\145\x5f\x69\x63\157\156\x76", $_POST)) {
            goto T0;
        }
        update_option("\155\157\x5f\163\x61\155\x6c\137\145\156\x63\157\x64\x69\156\147\x5f\145\x6e\141\142\154\x65\x64", '');
        goto ki;
        T0:
        update_option("\155\157\137\x73\141\155\154\x5f\x65\156\x63\x6f\x64\151\x6e\x67\x5f\x65\156\x61\x62\x6c\x65\144", "\143\150\x65\x63\x6b\x65\144");
        ki:
        update_option("\x6d\x6f\137\163\x61\x6d\154\137\155\145\x73\163\x61\147\x65", "\x49\144\x65\156\164\151\164\171\x20\x50\x72\157\x76\151\x64\145\x72\40\x64\x65\x74\x61\151\154\x73\40\163\141\x76\145\x64\x20\x73\165\x63\x63\x65\163\163\x66\165\x6c\x6c\171\x2e");
        $this->mo_saml_show_success_message();
        EB:
        if (!self::mo_check_option_admin_referer("\x6c\157\x67\x69\156\137\167\151\144\x67\x65\164\137\163\141\155\x6c\x5f\141\x74\164\162\151\142\165\x74\145\x5f\x6d\x61\x70\160\151\x6e\147")) {
            goto xN;
        }
        if (mo_saml_is_extension_installed("\x63\165\162\x6c")) {
            goto Yl;
        }
        update_option("\x6d\157\137\163\x61\x6d\x6c\x5f\155\145\163\163\x61\147\x65", "\x45\122\x52\117\122\x3a\x50\x48\x50\40\143\125\122\114\x20\x65\170\x74\145\156\163\151\x6f\x6e\40\x69\x73\40\156\157\164\x20\x69\156\163\164\x61\154\154\145\x64\x20\x6f\162\40\x64\151\163\x61\x62\154\145\144\x2e\x20\123\141\166\145\40\101\164\164\162\x69\142\x75\164\145\x20\x4d\141\160\x70\151\156\x67\x20\146\x61\x69\x6c\x65\x64\x2e");
        $this->mo_saml_show_error_message();
        return;
        Yl:
        update_option("\163\x61\x6d\x6c\137\x61\155\x5f\165\163\x65\162\156\141\x6d\x65", htmlspecialchars(stripslashes($_POST["\163\x61\x6d\154\x5f\x61\155\137\165\163\145\x72\156\x61\x6d\145"])));
        update_option("\163\x61\155\154\137\x61\x6d\x5f\145\x6d\141\x69\154", htmlspecialchars(stripslashes($_POST["\163\x61\155\x6c\137\141\x6d\x5f\145\x6d\x61\151\x6c"])));
        update_option("\x73\141\155\x6c\x5f\x61\155\x5f\146\151\x72\163\164\x5f\156\141\x6d\x65", htmlspecialchars(stripslashes($_POST["\163\141\155\154\137\141\x6d\x5f\146\x69\162\163\164\x5f\x6e\141\x6d\x65"])));
        update_option("\163\141\x6d\x6c\137\141\x6d\137\x6c\141\163\164\137\156\141\155\x65", htmlspecialchars(stripslashes($_POST["\163\141\x6d\154\137\141\x6d\x5f\154\x61\163\x74\x5f\156\141\155\145"])));
        update_option("\x73\141\155\154\137\x61\x6d\x5f\x64\x69\163\x70\154\141\171\x5f\156\141\x6d\145", htmlspecialchars(stripslashes($_POST["\x73\141\x6d\x6c\x5f\141\x6d\137\x64\x69\163\160\154\x61\171\x5f\x6e\x61\155\145"])));
        update_option("\x6d\157\x5f\163\141\155\x6c\137\155\x65\x73\163\x61\x67\145", "\x41\x74\164\162\151\x62\x75\x74\145\40\115\x61\x70\160\151\x6e\x67\40\144\x65\164\141\x69\x6c\163\x20\x73\x61\166\145\x64\x20\x73\x75\x63\x63\145\x73\x73\146\x75\x6c\x6c\x79");
        $this->mo_saml_show_success_message();
        xN:
        if (!self::mo_check_option_admin_referer("\143\154\145\x61\x72\137\141\x74\164\x72\163\x5f\154\151\x73\164")) {
            goto r5;
        }
        delete_option("\155\157\x5f\x73\141\155\x6c\137\x74\145\163\164\137\x63\157\x6e\146\151\147\137\141\x74\x74\162\x73");
        update_option("\155\157\137\163\141\155\154\x5f\x6d\145\163\163\141\147\145", "\x41\x74\x74\x72\151\x62\165\164\145\x73\40\154\x69\x73\164\x20\x72\145\155\x6f\x76\145\144\40\x73\165\x63\x63\x65\x73\163\x66\x75\x6c\x6c\171");
        $this->mo_saml_show_success_message();
        r5:
        if (!self::mo_check_option_admin_referer("\x6c\x6f\147\151\x6e\137\167\x69\x64\147\145\x74\137\x73\141\155\154\x5f\162\x6f\154\145\137\x6d\141\160\x70\151\x6e\147")) {
            goto TW;
        }
        if (mo_saml_is_extension_installed("\143\165\162\x6c")) {
            goto UQ;
        }
        update_option("\x6d\x6f\137\x73\x61\155\x6c\x5f\x6d\x65\x73\163\141\x67\145", "\x45\122\x52\x4f\x52\x3a\x50\110\x50\40\x63\x55\122\114\40\x65\x78\x74\x65\156\163\x69\157\156\40\x69\163\40\x6e\x6f\x74\40\x69\156\163\x74\x61\x6c\154\x65\144\x20\x6f\x72\40\144\151\x73\141\x62\x6c\x65\x64\x2e\40\x53\141\x76\145\40\122\157\x6c\145\40\x4d\x61\160\x70\151\156\147\40\146\x61\x69\154\145\144\x2e");
        $this->mo_saml_show_error_message();
        return;
        UQ:
        if (isset($_POST["\155\x6f\137\163\141\155\x6c\x5f\x64\157\x6e\x74\137\x75\x70\x64\x61\164\145\137\145\170\x69\x73\x74\151\x6e\x67\137\165\x73\x65\162\x5f\162\157\x6c\x65"])) {
            goto qJ;
        }
        update_option("\x73\x61\x6d\x6c\137\x61\155\137\144\157\156\164\x5f\x75\160\144\x61\x74\145\x5f\x65\170\151\x73\164\x69\x6e\x67\x5f\165\163\x65\162\137\x72\157\154\145", "\165\156\143\150\145\143\153\145\x64");
        goto uM;
        qJ:
        update_option("\x73\x61\155\154\137\141\155\x5f\x64\157\156\164\137\165\160\x64\x61\164\145\137\145\170\151\x73\x74\x69\x6e\147\x5f\x75\x73\x65\x72\137\162\157\x6c\145", "\143\x68\145\143\x6b\145\144");
        uM:
        if (!isset($_POST["\x73\141\155\154\137\141\155\137\144\x65\146\141\x75\x6c\164\x5f\x75\x73\x65\162\137\x72\x6f\154\145"])) {
            goto vX;
        }
        $bj = htmlspecialchars($_POST["\163\x61\x6d\x6c\x5f\141\155\137\x64\145\146\141\165\x6c\164\137\x75\x73\x65\x72\x5f\162\157\x6c\x65"]);
        update_option("\x73\x61\x6d\154\137\141\x6d\x5f\x64\x65\x66\141\165\x6c\x74\x5f\x75\x73\x65\x72\x5f\x72\157\x6c\145", $bj);
        vX:
        update_option("\155\157\x5f\163\141\155\x6c\x5f\x6d\x65\163\x73\x61\x67\x65", "\x52\157\x6c\x65\40\x4d\x61\x70\x70\151\x6e\x67\40\x64\145\164\141\151\154\163\40\163\141\166\x65\x64\40\163\x75\x63\143\x65\163\x73\146\x75\x6c\154\x79\56");
        $this->mo_saml_show_success_message();
        TW:
        if (!self::mo_check_option_admin_referer("\155\157\x5f\x73\141\x6d\154\x5f\165\x70\x64\x61\x74\x65\137\151\x64\160\137\163\x65\x74\x74\x69\x6e\147\163\x5f\x6f\160\x74\151\157\x6e")) {
            goto nB;
        }
        if (!(isset($_POST["\155\x6f\137\x73\x61\x6d\x6c\137\x73\160\137\142\x61\163\x65\137\165\x72\x6c"]) && isset($_POST["\155\x6f\137\163\x61\x6d\x6c\x5f\x73\160\137\x65\x6e\164\x69\x74\x79\x5f\151\144"]))) {
            goto B3;
        }
        $f4 = sanitize_text_field($_POST["\x6d\x6f\x5f\163\x61\x6d\154\137\x73\x70\137\142\x61\163\x65\137\x75\x72\154"]);
        $fK = sanitize_text_field($_POST["\x6d\x6f\x5f\163\141\155\154\x5f\163\160\x5f\145\156\x74\x69\164\x79\x5f\151\144"]);
        if (!(substr($f4, -1) == "\57")) {
            goto zE;
        }
        $f4 = substr($f4, 0, -1);
        zE:
        update_option("\155\157\x5f\x73\141\x6d\x6c\137\163\x70\137\142\x61\x73\x65\137\x75\162\x6c", $f4);
        update_option("\155\x6f\137\163\x61\x6d\154\x5f\x73\160\137\145\156\164\151\164\171\137\151\x64", $fK);
        B3:
        update_option("\x6d\157\137\x73\x61\x6d\154\x5f\x6d\x65\163\x73\141\147\x65", "\123\x65\164\x74\x69\156\x67\x73\40\x75\160\144\141\x74\145\x64\x20\x73\x75\x63\143\145\163\163\146\x75\x6c\x6c\171\56");
        $this->mo_saml_show_success_message();
        nB:
        if (!self::mo_check_option_admin_referer("\x73\x61\x6d\154\137\165\160\x6c\x6f\x61\x64\x5f\155\145\164\141\144\x61\164\141")) {
            goto dI;
        }
        if (function_exists("\167\160\137\x68\141\156\144\154\145\x5f\x75\x70\x6c\x6f\141\144")) {
            goto QA;
        }
        require_once ABSPATH . "\x77\160\x2d\141\144\x6d\151\x6e\x2f\151\x6e\x63\x6c\x75\144\145\x73\x2f\146\x69\x6c\145\x2e\160\150\x70";
        QA:
        $this->_handle_upload_metadata();
        dI:
        if (!self::mo_check_option_admin_referer("\x75\160\147\162\141\144\x65\137\143\145\162\x74")) {
            goto z1;
        }
        $l3 = file_get_contents(plugin_dir_path(__FILE__) . "\162\x65\x73\x6f\x75\x72\143\x65\x73" . DIRECTORY_SEPARATOR . "\155\151\156\x69\157\x72\141\x6e\x67\x65\137\x73\160\137\62\60\x32\60\56\143\x72\x74");
        $m2 = file_get_contents(plugin_dir_path(__FILE__) . "\162\145\x73\157\165\x72\143\x65\x73" . DIRECTORY_SEPARATOR . "\155\x69\156\151\157\x72\x61\x6e\x67\x65\137\x73\160\x5f\62\x30\62\x30\x5f\x70\x72\151\x76\56\x6b\x65\x79");
        update_option("\x6d\157\137\x73\141\x6d\154\137\143\x75\162\x72\x65\156\164\x5f\143\145\x72\164", $l3);
        update_option("\155\x6f\137\163\x61\x6d\x6c\x5f\x63\x75\x72\x72\145\x6e\x74\137\x63\145\162\x74\x5f\160\x72\x69\x76\141\164\145\x5f\153\x65\171", $m2);
        update_option("\155\157\x5f\x73\141\x6d\x6c\137\x63\x65\162\164\151\x66\151\143\141\164\145\137\162\157\x6c\154\137\142\x61\143\153\x5f\141\166\x61\151\154\x61\142\154\x65", true);
        update_option("\x6d\157\137\163\x61\x6d\x6c\137\x6d\145\x73\163\x61\x67\145", "\x43\x65\162\164\x69\146\x69\143\141\164\145\x20\125\160\147\162\x61\x64\145\144\x20\163\165\x63\143\145\163\163\146\165\x6c\154\x79");
        $this->mo_saml_show_success_message();
        z1:
        if (!self::mo_check_option_admin_referer("\x72\x6f\x6c\x6c\142\141\143\153\137\143\x65\x72\164")) {
            goto eH;
        }
        $l3 = file_get_contents(plugin_dir_path(__FILE__) . "\x72\145\x73\157\x75\x72\143\x65\163" . DIRECTORY_SEPARATOR . "\163\x70\55\x63\145\162\x74\x69\x66\x69\x63\141\164\x65\x2e\143\x72\164");
        $m2 = file_get_contents(plugin_dir_path(__FILE__) . "\162\145\163\x6f\x75\x72\143\x65\163" . DIRECTORY_SEPARATOR . "\x73\160\x2d\x6b\x65\x79\x2e\x6b\x65\x79");
        update_option("\155\x6f\x5f\x73\141\x6d\154\137\143\165\x72\x72\x65\x6e\x74\137\143\145\162\x74", $l3);
        update_option("\x6d\x6f\x5f\x73\141\x6d\154\137\143\x75\x72\x72\x65\156\x74\137\x63\145\x72\164\137\x70\x72\x69\166\141\x74\145\137\x6b\145\x79", $m2);
        update_option("\x6d\157\137\x73\x61\x6d\x6c\137\x6d\x65\163\163\x61\x67\145", "\103\145\x72\164\151\x66\151\143\141\x74\145\x20\x52\x6f\154\x6c\x2d\142\x61\143\x6b\x65\144\40\x73\x75\x63\x63\x65\163\x73\146\165\x6c\154\x79");
        delete_option("\x6d\x6f\x5f\x73\x61\x6d\x6c\137\x63\145\x72\164\151\x66\x69\x63\x61\164\145\x5f\x72\157\154\x6c\x5f\x62\141\143\153\x5f\141\x76\x61\x69\x6c\x61\x62\x6c\x65");
        $this->mo_saml_show_success_message();
        eH:
        if (!self::mo_check_option_admin_referer("\155\x6f\137\163\141\155\154\137\x72\145\x6c\x61\x79\x5f\163\164\x61\x74\145\137\x6f\160\x74\x69\157\156")) {
            goto FK;
        }
        $sp = sanitize_text_field($_POST["\x6d\157\x5f\163\x61\x6d\154\x5f\x72\x65\x6c\x61\x79\137\x73\164\x61\x74\145"]);
        update_option("\x6d\157\137\163\x61\155\154\x5f\x72\145\154\x61\x79\137\163\164\141\164\x65", $sp);
        update_option("\155\157\137\x73\x61\155\x6c\x5f\155\145\163\x73\141\x67\145", "\x53\123\x4f\40\160\x61\x67\x65\x20\165\160\144\141\x74\145\144\x20\163\165\x63\x63\145\x73\x73\x66\165\x6c\x6c\171\56");
        $this->mo_saml_show_success_message();
        FK:
        if (!self::mo_check_option_admin_referer("\x6d\x6f\x5f\163\141\x6d\154\x5f\167\x69\x64\x67\145\x74\137\157\x70\x74\x69\x6f\156")) {
            goto IN;
        }
        $FU = sanitize_text_field($_POST["\x6d\x6f\x5f\x73\141\x6d\x6c\x5f\x63\x75\163\164\157\155\x5f\x6c\x6f\x67\x69\156\137\164\x65\170\x74"]);
        update_option("\x6d\157\x5f\x73\141\155\x6c\137\x63\165\163\164\x6f\x6d\x5f\x6c\x6f\x67\x69\x6e\x5f\x74\145\x78\x74", stripcslashes($FU));
        $Vx = sanitize_text_field($_POST["\x6d\157\137\x73\141\x6d\x6c\x5f\x63\165\163\164\x6f\155\x5f\147\x72\145\x65\x74\x69\x6e\147\137\164\145\170\164"]);
        update_option("\155\x6f\137\x73\141\x6d\154\137\143\165\x73\164\x6f\155\137\147\162\x65\x65\164\x69\156\147\137\x74\x65\170\x74", stripcslashes($Vx));
        $Lj = sanitize_text_field($_POST["\x6d\x6f\137\x73\x61\x6d\x6c\x5f\x67\x72\145\x65\164\x69\x6e\147\137\156\x61\x6d\x65"]);
        update_option("\155\x6f\137\163\x61\155\x6c\x5f\x67\x72\145\145\164\x69\156\x67\137\156\x61\155\145", stripcslashes($Lj));
        $IA = sanitize_text_field($_POST["\155\x6f\137\x73\x61\155\x6c\x5f\143\x75\163\x74\157\x6d\x5f\154\x6f\147\157\x75\x74\x5f\x74\145\x78\x74"]);
        update_option("\155\157\137\163\141\x6d\154\137\143\x75\163\164\x6f\155\137\x6c\x6f\147\x6f\165\164\x5f\x74\145\x78\164", stripcslashes($IA));
        update_option("\155\x6f\137\163\141\155\x6c\x5f\x6d\x65\x73\x73\141\147\x65", "\x57\x69\144\x67\x65\x74\x20\123\x65\164\x74\151\x6e\147\163\40\x75\160\x64\x61\x74\x65\144\40\x73\x75\143\x63\x65\x73\x73\146\165\x6c\154\171\x2e");
        $this->mo_saml_show_success_message();
        IN:
        if (!self::mo_check_option_admin_referer("\155\x6f\x5f\163\x61\x6d\154\x5f\162\x65\x67\151\163\x74\145\162\x5f\143\165\x73\164\157\155\145\162")) {
            goto D7;
        }
        if (mo_saml_is_extension_installed("\x63\x75\162\154")) {
            goto Kh;
        }
        update_option("\155\157\x5f\x73\x61\155\x6c\137\155\x65\163\163\141\147\145", "\x45\x52\122\x4f\x52\x3a\x20\x50\110\120\x20\x63\125\x52\114\x20\145\170\164\x65\x6e\163\151\x6f\156\40\x69\x73\40\156\x6f\x74\x20\151\156\x73\164\141\154\x6c\145\x64\40\157\162\x20\x64\151\163\141\142\154\145\x64\56\40\122\x65\147\151\x73\x74\x72\x61\164\151\157\156\x20\x66\x61\151\x6c\x65\144\56");
        $this->mo_saml_show_error_message();
        return;
        Kh:
        $OH = '';
        $En = '';
        $nv = '';
        $CA = '';
        if ($this->mo_saml_check_empty_or_null($_POST["\145\x6d\141\x69\x6c"]) || $this->mo_saml_check_empty_or_null($_POST["\160\x61\163\163\x77\157\x72\144"]) || $this->mo_saml_check_empty_or_null($_POST["\143\x6f\x6e\x66\x69\x72\x6d\x50\x61\163\163\167\157\x72\144"])) {
            goto zj;
        }
        if (strlen($_POST["\x70\141\163\x73\x77\x6f\x72\144"]) < 6 || strlen($_POST["\143\x6f\156\146\151\162\155\120\141\163\163\x77\157\162\144"]) < 6) {
            goto LX;
        }
        if (!filter_var($_POST["\x65\155\x61\151\x6c"], FILTER_VALIDATE_EMAIL)) {
            goto br;
        }
        if ($this->checkPasswordPattern(strip_tags($_POST["\x70\141\163\163\x77\157\162\144"]))) {
            goto S6;
        }
        $OH = sanitize_email($_POST["\x65\155\x61\151\154"]);
        $En = sanitize_text_field($_POST["\160\x68\x6f\156\x65"]);
        $nv = stripslashes(strip_tags($_POST["\160\x61\163\163\x77\x6f\x72\144"]));
        $CA = stripslashes(strip_tags($_POST["\x63\x6f\156\146\x69\162\155\120\x61\x73\x73\x77\x6f\x72\144"]));
        goto C6;
        S6:
        update_option("\x6d\x6f\x5f\163\141\x6d\154\x5f\155\145\163\x73\141\147\145", "\x4d\x69\x6e\151\x6d\165\155\x20\x36\40\143\150\x61\162\141\143\164\x65\162\163\x20\x73\150\157\165\x6c\144\40\142\x65\40\x70\x72\x65\163\145\x6e\164\x2e\40\115\x61\x78\151\x6d\165\155\40\x31\65\x20\143\150\x61\162\141\143\x74\x65\162\163\40\x73\150\x6f\x75\x6c\x64\x20\142\x65\40\x70\x72\x65\163\145\156\x74\56\40\x4f\156\x6c\x79\x20\x66\157\154\x6c\157\x77\x69\x6e\x67\x20\x73\171\x6d\x62\157\154\x73\40\50\x21\100\43\x2e\44\x25\x5e\x26\52\55\x5f\x29\40\x73\150\x6f\165\154\x64\40\x62\x65\40\160\162\145\x73\x65\x6e\164\x2e");
        $this->mo_saml_show_error_message();
        return;
        C6:
        goto NT;
        br:
        update_option("\x6d\157\137\x73\141\155\154\137\x6d\x65\x73\163\141\x67\x65", "\120\154\x65\x61\163\x65\x20\145\x6e\x74\145\x72\40\141\40\x76\x61\x6c\151\144\x20\145\155\x61\x69\154\40\x61\x64\x64\x72\145\x73\163\x2e");
        $this->mo_saml_show_error_message();
        return;
        NT:
        goto In;
        LX:
        update_option("\x6d\x6f\x5f\x73\141\x6d\154\137\x6d\x65\163\163\x61\147\145", "\103\x68\x6f\x6f\163\x65\40\x61\40\160\x61\x73\x73\167\x6f\x72\144\x20\x77\x69\164\150\40\155\151\x6e\x69\155\165\155\40\x6c\x65\x6e\x67\164\150\40\x36\x2e");
        $this->mo_saml_show_error_message();
        return;
        In:
        goto Ql;
        zj:
        update_option("\x6d\x6f\x5f\163\x61\155\x6c\137\x6d\x65\163\x73\141\147\x65", "\x41\x6c\x6c\40\164\150\x65\40\146\x69\145\x6c\144\x73\x20\141\162\145\40\x72\145\161\x75\151\x72\x65\x64\x2e\40\120\x6c\x65\141\163\x65\x20\x65\156\164\x65\162\x20\x76\141\154\x69\x64\40\x65\x6e\164\x72\x69\145\163\x2e");
        $this->mo_saml_show_error_message();
        return;
        Ql:
        update_option("\155\157\x5f\163\x61\155\x6c\x5f\x61\x64\x6d\x69\x6e\137\x65\155\141\x69\154", $OH);
        update_option("\155\x6f\137\x73\141\155\x6c\137\141\144\x6d\151\156\x5f\x70\150\x6f\x6e\145", $En);
        if (strcmp($nv, $CA) == 0) {
            goto v2;
        }
        update_option("\x6d\x6f\x5f\x73\x61\x6d\154\x5f\155\x65\163\x73\141\x67\x65", "\120\x61\163\163\x77\157\x72\x64\163\40\144\x6f\x20\156\157\x74\40\155\141\164\x63\x68\56");
        delete_option("\x6d\x6f\x5f\163\141\x6d\x6c\137\x76\x65\x72\151\x66\x79\137\x63\165\x73\x74\x6f\155\x65\x72");
        $this->mo_saml_show_error_message();
        goto eN;
        v2:
        update_option("\x6d\x6f\x5f\163\x61\x6d\154\x5f\x61\x64\155\151\x6e\x5f\x70\141\163\x73\167\x6f\x72\144", $nv);
        $OH = get_option("\x6d\157\137\163\141\x6d\154\137\141\x64\x6d\x69\156\137\145\x6d\x61\x69\154");
        $Di = new CustomerSaml();
        $NE = $Di->check_customer();
        if ($NE) {
            goto x_;
        }
        return;
        x_:
        $NE = json_decode($NE, true);
        if (strcasecmp($NE["\163\164\x61\164\x75\163"], "\x43\x55\x53\124\x4f\x4d\105\122\137\x4e\117\124\137\106\117\125\116\x44") == 0) {
            goto jc;
        }
        $this->get_current_customer();
        goto aj;
        jc:
        $NE = $Di->send_otp_token($OH, '');
        if ($NE) {
            goto IH;
        }
        return;
        IH:
        $NE = json_decode($NE, true);
        if (strcasecmp($NE["\x73\164\141\x74\x75\163"], "\x53\125\x43\x43\105\123\x53") == 0) {
            goto kx;
        }
        update_option("\x6d\x6f\x5f\163\141\x6d\x6c\x5f\155\x65\163\x73\x61\147\x65", "\x54\x68\145\162\x65\40\167\x61\163\40\x61\x6e\40\x65\162\162\x6f\162\40\x69\x6e\40\x73\145\156\144\151\156\x67\x20\145\x6d\x61\151\x6c\56\40\120\154\145\x61\x73\145\40\x76\145\162\151\x66\x79\x20\171\x6f\165\x72\40\145\x6d\141\x69\x6c\40\141\x6e\144\40\164\x72\x79\x20\x61\x67\x61\151\x6e\56");
        update_option("\155\x6f\x5f\163\141\155\x6c\x5f\x72\145\147\151\x73\164\162\141\x74\151\157\x6e\x5f\x73\164\x61\164\165\x73", "\115\117\x5f\117\124\x50\137\x44\105\114\111\x56\105\122\x45\104\x5f\106\x41\111\x4c\x55\x52\x45\x5f\105\x4d\x41\111\114");
        $this->mo_saml_show_error_message();
        goto tN;
        kx:
        update_option("\x6d\157\x5f\163\x61\x6d\154\137\x6d\x65\163\163\x61\147\x65", "\x20\x41\x20\157\x6e\x65\40\164\x69\155\x65\40\160\x61\163\x73\143\157\144\145\40\x69\x73\40\x73\145\156\x74\x20\164\x6f\x20" . get_option("\x6d\157\137\x73\x61\x6d\154\137\x61\144\155\x69\156\x5f\145\x6d\141\x69\154") . "\x2e\40\120\x6c\145\x61\x73\145\40\x65\x6e\164\x65\162\x20\164\x68\x65\x20\x6f\164\x70\x20\x68\145\x72\x65\x20\164\157\x20\x76\x65\x72\151\x66\x79\40\171\x6f\x75\162\40\x65\x6d\x61\151\154\56");
        update_option("\x6d\x6f\x5f\x73\x61\155\154\137\164\162\x61\x6e\x73\141\143\x74\151\x6f\156\111\x64", $NE["\x74\x78\x49\144"]);
        update_option("\155\157\x5f\163\x61\155\x6c\137\162\x65\x67\151\x73\x74\x72\141\164\151\157\156\x5f\163\x74\141\x74\165\163", "\115\x4f\x5f\x4f\x54\x50\x5f\104\105\114\111\x56\x45\x52\105\x44\x5f\123\125\x43\103\105\x53\x53\x5f\x45\x4d\x41\111\114");
        $this->mo_saml_show_success_message();
        tN:
        aj:
        eN:
        D7:
        if (self::mo_check_option_admin_referer("\155\x6f\137\163\141\155\154\x5f\x76\x61\154\151\x64\x61\164\145\x5f\157\x74\x70")) {
            goto A_;
        }
        if (self::mo_check_option_admin_referer("\x6d\x6f\x5f\x73\141\x6d\154\x5f\x76\x65\162\x69\x66\x79\x5f\x6c\x69\143\145\156\x73\145")) {
            goto yV;
        }
        if (self::mo_check_option_admin_referer("\x6d\157\137\x73\141\x6d\154\137\143\157\x6e\164\x61\143\164\137\x75\x73\137\161\165\x65\162\x79\137\x6f\160\164\x69\x6f\156")) {
            goto Ot;
        }
        if (self::mo_check_option_admin_referer("\155\157\x5f\163\x61\x6d\154\137\162\145\x73\145\156\144\x5f\x6f\x74\x70\137\145\x6d\141\151\154")) {
            goto cu;
        }
        if (self::mo_check_option_admin_referer("\x6d\x6f\137\x73\141\x6d\154\x5f\x72\x65\163\x65\x6e\x64\x5f\157\164\160\x5f\160\x68\157\x6e\145")) {
            goto s4;
        }
        if (self::mo_check_option_admin_referer("\x6d\157\x5f\x73\x61\x6d\x6c\137\147\157\x5f\142\141\x63\x6b")) {
            goto LR;
        }
        if (self::mo_check_option_admin_referer("\x6d\x6f\x5f\163\x61\x6d\154\137\x72\x65\x67\151\x73\164\x65\x72\137\x77\x69\164\150\137\160\150\157\x6e\145\137\x6f\160\164\x69\157\x6e")) {
            goto Yy;
        }
        if (self::mo_check_option_admin_referer("\155\x6f\137\x73\141\155\154\137\x72\145\x67\x69\163\164\145\162\x65\144\x5f\157\x6e\x6c\x79\137\141\143\143\x65\x73\x73\x5f\x6f\160\164\x69\157\x6e")) {
            goto Ig;
        }
        if (self::mo_check_option_admin_referer("\155\x6f\137\x73\x61\155\x6c\x5f\146\157\162\143\x65\137\141\x75\164\150\145\x6e\164\x69\x63\x61\164\x69\x6f\x6e\137\x6f\160\164\151\x6f\156")) {
            goto Be;
        }
        if (self::mo_check_option_admin_referer("\x6d\x6f\x5f\x73\x61\155\154\x5f\x65\156\141\142\x6c\145\137\162\x73\163\x5f\x61\143\143\x65\x73\x73\x5f\x6f\160\x74\151\157\156")) {
            goto MO;
        }
        if (self::mo_check_option_admin_referer("\x6d\157\137\x73\141\155\x6c\x5f\x65\x6e\x61\142\x6c\145\x5f\154\157\x67\x69\156\137\x72\x65\144\x69\x72\x65\143\164\137\157\160\x74\151\x6f\x6e")) {
            goto O7;
        }
        if (self::mo_check_option_admin_referer("\x6d\x6f\137\163\141\155\154\137\x61\154\x6c\x6f\167\x5f\x77\160\137\163\151\147\x6e\151\156\137\157\x70\164\x69\157\156")) {
            goto s8;
        }
        if (isset($_POST["\157\160\x74\151\157\x6e"]) && $_POST["\x6f\x70\x74\x69\157\x6e"] == "\x6d\x6f\x5f\163\x61\155\x6c\137\x66\x6f\162\x67\x6f\x74\x5f\160\x61\x73\x73\x77\x6f\x72\144\x5f\146\x6f\x72\155\x5f\x6f\160\x74\x69\x6f\x6e") {
            goto oM;
        }
        if (!self::mo_check_option_admin_referer("\x6d\x6f\x5f\x73\x61\155\154\137\166\x65\162\151\146\x79\137\143\165\x73\x74\157\x6d\145\162")) {
            goto kz;
        }
        if (mo_saml_is_extension_installed("\x63\165\162\154")) {
            goto bm;
        }
        update_option("\x6d\157\137\163\141\x6d\154\137\x6d\145\163\163\141\147\x65", "\x45\122\122\x4f\x52\x3a\40\120\x48\x50\40\143\x55\x52\x4c\40\145\x78\164\145\156\163\x69\x6f\156\40\151\x73\x20\x6e\x6f\164\x20\x69\156\163\x74\141\154\x6c\145\144\x20\x6f\162\x20\x64\151\x73\141\x62\x6c\x65\x64\56\40\x4c\157\147\x69\156\40\x66\141\151\154\145\x64\x2e");
        $this->mo_saml_show_error_message();
        return;
        bm:
        $OH = '';
        $nv = '';
        if ($this->mo_saml_check_empty_or_null($_POST["\x65\155\141\151\154"]) || $this->mo_saml_check_empty_or_null($_POST["\160\141\x73\x73\167\x6f\162\x64"])) {
            goto Qy;
        }
        if ($this->checkPasswordPattern(strip_tags($_POST["\x70\141\x73\x73\x77\x6f\x72\144"]))) {
            goto fh;
        }
        $OH = sanitize_email($_POST["\x65\x6d\141\151\154"]);
        $nv = stripslashes(strip_tags($_POST["\160\141\163\x73\x77\157\x72\x64"]));
        goto lK;
        fh:
        update_option("\x6d\x6f\x5f\163\141\x6d\154\x5f\x6d\145\x73\x73\141\147\145", "\115\x69\156\151\x6d\165\x6d\x20\66\40\x63\x68\x61\x72\x61\x63\164\x65\162\163\x20\163\x68\x6f\x75\154\x64\40\142\x65\40\x70\162\x65\x73\x65\x6e\164\x2e\40\x4d\141\x78\151\x6d\x75\155\40\x31\x35\40\x63\x68\x61\x72\141\143\x74\145\162\x73\x20\x73\150\157\x75\x6c\144\x20\142\x65\x20\x70\x72\x65\x73\145\x6e\x74\x2e\40\x4f\156\154\171\x20\146\157\154\x6c\157\167\x69\x6e\147\x20\x73\171\155\x62\157\154\x73\x20\50\41\100\x23\56\44\45\136\46\x2a\55\137\51\40\x73\150\x6f\165\154\144\x20\x62\x65\x20\x70\162\145\163\145\156\x74\x2e");
        $this->mo_saml_show_error_message();
        return;
        lK:
        goto WV;
        Qy:
        update_option("\x6d\x6f\137\x73\141\x6d\154\137\155\145\163\163\141\147\145", "\101\x6c\154\x20\164\150\x65\40\x66\151\145\154\x64\x73\40\141\162\145\x20\x72\145\x71\165\x69\162\x65\x64\56\40\x50\154\145\x61\x73\145\x20\145\x6e\164\x65\x72\x20\x76\x61\x6c\151\144\40\145\x6e\164\x72\151\x65\163\56");
        $this->mo_saml_show_error_message();
        return;
        WV:
        update_option("\155\157\x5f\163\x61\155\154\137\141\x64\155\x69\156\x5f\145\155\141\x69\x6c", $OH);
        update_option("\x6d\x6f\137\x73\x61\x6d\x6c\x5f\x61\x64\x6d\x69\x6e\137\160\141\x73\x73\167\x6f\x72\x64", $nv);
        $Di = new Customersaml();
        $NE = $Di->get_customer_key();
        if ($NE) {
            goto pn;
        }
        return;
        pn:
        $Xs = json_decode($NE, true);
        if (json_last_error() == JSON_ERROR_NONE) {
            goto AS1;
        }
        update_option("\x6d\157\137\x73\x61\x6d\154\x5f\155\x65\163\x73\x61\x67\145", "\x49\x6e\x76\141\154\x69\144\40\165\x73\x65\x72\156\141\155\x65\x20\157\x72\x20\x70\x61\x73\x73\x77\157\162\144\56\x20\x50\154\x65\x61\x73\145\40\x74\162\171\x20\x61\147\141\151\156\56");
        $this->mo_saml_show_error_message();
        goto Pt;
        AS1:
        update_option("\x6d\x6f\137\163\x61\155\154\x5f\x61\x64\x6d\x69\x6e\137\143\165\x73\x74\x6f\155\x65\x72\137\153\x65\x79", $Xs["\x69\144"]);
        update_option("\155\x6f\x5f\x73\141\x6d\x6c\137\141\144\155\151\x6e\x5f\x61\x70\x69\137\x6b\145\171", $Xs["\x61\x70\x69\x4b\145\171"]);
        update_option("\x6d\157\137\x73\x61\155\154\137\x63\165\163\164\157\155\145\x72\137\164\x6f\153\x65\x6e", $Xs["\x74\157\153\145\156"]);
        if (empty($Xs["\x70\x68\157\x6e\145"])) {
            goto lD;
        }
        update_option("\x6d\157\x5f\x73\x61\155\x6c\137\141\144\155\x69\x6e\x5f\x70\150\157\x6e\145", $Xs["\x70\150\x6f\x6e\x65"]);
        lD:
        update_option("\x6d\x6f\x5f\163\x61\155\154\x5f\141\x64\x6d\x69\x6e\x5f\x70\x61\163\x73\167\157\162\x64", '');
        update_option("\155\x6f\x5f\163\141\x6d\154\x5f\x6d\145\163\x73\x61\x67\x65", "\103\x75\x73\x74\x6f\155\x65\x72\x20\x72\145\x74\x72\151\x65\166\x65\144\40\x73\x75\143\143\145\x73\x73\146\x75\x6c\154\x79");
        update_option("\155\157\x5f\163\141\x6d\154\x5f\162\x65\147\151\163\164\x72\x61\x74\151\x6f\156\x5f\163\x74\141\x74\x75\x73", "\105\x78\151\163\164\x69\x6e\147\x20\125\163\x65\x72");
        delete_option("\155\x6f\x5f\163\x61\155\x6c\x5f\166\x65\x72\151\146\171\137\143\x75\x73\x74\x6f\x6d\145\162");
        if (get_option("\163\155\154\137\x6c\x6b")) {
            goto zv;
        }
        $this->mo_saml_show_success_message();
        goto g3;
        zv:
        $Xr = get_option("\155\157\137\163\141\155\154\137\x63\x75\163\164\x6f\155\x65\x72\137\164\x6f\x6b\145\x6e");
        $yJ = AESEncryption::decrypt_data(get_option("\163\x6d\154\137\x6c\153"), $Xr);
        $NE = $Di->mo_saml_vl($yJ, false);
        if ($NE) {
            goto jr;
        }
        return;
        jr:
        $NE = json_decode($NE, true);
        update_option("\x76\x6c\137\x63\x68\145\143\153\137\x74", time());
        if (strcasecmp($NE["\x73\x74\x61\x74\165\163"], "\x53\x55\x43\103\105\123\x53") == 0) {
            goto w9;
        }
        update_option("\155\157\x5f\163\x61\x6d\154\x5f\155\145\x73\163\x61\147\145", "\x4c\151\143\x65\156\163\145\40\153\x65\x79\40\146\x6f\x72\x20\164\x68\x69\x73\40\151\156\163\164\x61\x6e\143\145\x20\151\x73\40\151\156\143\x6f\x72\x72\x65\143\164\x2e\x20\x4d\x61\153\x65\x20\163\x75\162\x65\x20\x79\157\165\x20\150\x61\x76\x65\x20\x6e\157\x74\x20\164\x61\155\160\145\162\x65\x64\40\167\x69\x74\150\x20\x69\164\x20\x61\x74\40\x61\x6c\154\x2e\x20\120\x6c\145\141\x73\145\x20\145\x6e\x74\x65\162\40\x61\x20\166\141\x6c\151\144\x20\x6c\x69\143\x65\x6e\163\x65\x20\153\145\x79\56");
        delete_option("\x73\x6d\154\x5f\154\x6b");
        $this->mo_saml_show_error_message();
        goto VJ;
        w9:
        $O6 = plugin_dir_path(__FILE__);
        $Vs = home_url();
        $Vs = trim($Vs, "\x2f");
        if (preg_match("\x23\136\150\x74\164\x70\x28\x73\x29\x3f\x3a\57\57\x23", $Vs)) {
            goto WM;
        }
        $Vs = "\150\164\164\x70\72\x2f\57" . $Vs;
        WM:
        $NC = parse_url($Vs);
        $N8 = preg_replace("\x2f\x5e\167\x77\167\x5c\56\57", '', $NC["\150\157\163\x74"]);
        $Hx = wp_upload_dir();
        $W_ = $N8 . "\55" . $Hx["\x62\141\163\145\x64\151\162"];
        $dR = hash_hmac("\x73\x68\141\x32\x35\x36", $W_, "\64\x44\110\x66\x6a\x67\146\x6a\141\163\156\x64\x66\x73\141\152\x66\110\x47\x4a");
        $zZ = $this->djkasjdksa();
        $Rv = round(strlen($zZ) / rand(2, 20));
        $zZ = substr_replace($zZ, $dR, $Rv, 0);
        $By = base64_decode($zZ);
        if (is_writable($O6 . "\x6c\x69\x63\145\x6e\x73\145")) {
            goto Ss;
        }
        $zZ = str_rot13($zZ);
        $ht = base64_decode("\x62\107\x4e\x6b\x61\x6d\x74\150\143\62\160\153\141\63\x4e\x68\x59\x32\x77\x3d");
        update_option($ht, $zZ);
        goto UZ;
        Ss:
        file_put_contents($O6 . "\x6c\x69\143\145\x6e\163\x65", $By);
        UZ:
        update_option("\154\143\167\x72\164\x6c\146\x73\141\155\x6c", true);
        $this->mo_saml_show_success_message();
        VJ:
        g3:
        Pt:
        update_option("\x6d\157\x5f\163\x61\155\154\x5f\141\144\155\151\x6e\x5f\160\141\163\163\167\x6f\162\x64", '');
        kz:
        goto w5;
        oM:
        if (mo_saml_is_extension_installed("\x63\165\x72\x6c")) {
            goto qp;
        }
        update_option("\155\157\137\x73\x61\155\154\x5f\155\x65\163\x73\x61\x67\x65", "\x45\x52\122\117\122\72\x20\x50\110\x50\x20\x63\x55\122\114\x20\x65\x78\x74\145\x6e\163\151\x6f\x6e\40\x69\163\x20\156\157\x74\x20\151\156\163\164\141\154\154\145\144\40\157\x72\x20\144\151\x73\x61\x62\154\145\x64\x2e\40\x52\x65\163\x65\x6e\x64\40\117\x54\x50\x20\146\141\151\x6c\145\x64\56");
        $this->mo_saml_show_error_message();
        return;
        qp:
        $OH = get_option("\155\157\x5f\x73\141\155\x6c\137\141\x64\x6d\151\156\137\x65\155\141\x69\x6c");
        $Di = new Customersaml();
        $NE = $Di->mo_saml_forgot_password($OH);
        if ($NE) {
            goto F9;
        }
        return;
        F9:
        $NE = json_decode($NE, true);
        if (strcasecmp($NE["\x73\x74\x61\164\165\163"], "\x53\125\x43\x43\x45\x53\x53") == 0) {
            goto RX;
        }
        update_option("\155\x6f\137\163\141\155\x6c\137\x6d\145\163\x73\141\x67\145", "\x41\x6e\x20\145\162\162\157\x72\x20\157\x63\x63\165\x72\145\x64\x20\x77\x68\151\154\145\40\x70\x72\x6f\x63\x65\x73\x73\151\156\x67\x20\171\157\x75\162\40\162\145\161\x75\145\x73\164\x2e\x20\120\154\145\x61\x73\x65\x20\124\162\x79\40\x61\147\x61\x69\156\x2e");
        $this->mo_saml_show_error_message();
        goto sC;
        RX:
        update_option("\155\157\x5f\x73\141\155\x6c\137\x6d\x65\x73\163\x61\x67\145", "\x59\157\x75\162\40\160\x61\163\163\x77\x6f\162\x64\x20\150\x61\163\x20\142\x65\x65\x6e\40\x72\x65\x73\145\164\x20\163\x75\x63\x63\x65\x73\163\146\165\154\154\171\x2e\x20\x50\154\x65\x61\163\145\x20\145\156\164\x65\x72\40\x74\150\x65\40\156\x65\167\x20\160\141\163\x73\167\x6f\162\x64\x20\163\145\x6e\x74\40\164\157\x20" . $OH . "\x2e");
        $this->mo_saml_show_success_message();
        sC:
        w5:
        goto mX;
        s8:
        $RZ = "\x66\x61\x6c\x73\x65";
        if (array_key_exists("\x6d\157\137\x73\141\155\154\x5f\x61\154\x6c\157\x77\x5f\167\160\137\163\x69\147\156\151\156", $_POST)) {
            goto a0;
        }
        $J4 = "\146\141\x6c\163\145";
        goto a6;
        a0:
        $J4 = htmlspecialchars($_POST["\x6d\157\x5f\x73\141\x6d\x6c\x5f\141\x6c\154\x6f\167\137\x77\160\137\163\151\x67\156\x69\x6e"]);
        a6:
        if ($J4 == "\x74\x72\x75\x65") {
            goto V6;
        }
        update_option("\155\157\x5f\x73\x61\x6d\x6c\x5f\x61\x6c\154\157\x77\x5f\167\160\x5f\x73\151\x67\x6e\151\156", "\x46\141\x6c\163\145");
        goto wC;
        V6:
        update_option("\x6d\x6f\x5f\163\141\155\154\137\x61\154\154\157\x77\x5f\x77\x70\137\163\x69\x67\156\x69\156", "\164\162\x75\145");
        wC:
        if (!array_key_exists("\x6d\x6f\137\163\x61\x6d\154\137\x62\x61\143\153\144\x6f\157\162\137\x75\x72\154", $_POST)) {
            goto P5;
        }
        $RZ = htmlspecialchars(trim($_POST["\155\x6f\x5f\163\141\x6d\x6c\x5f\x62\x61\143\x6b\x64\x6f\x6f\162\x5f\165\162\154"]));
        P5:
        update_option("\x6d\157\x5f\x73\141\155\154\x5f\x62\x61\143\x6b\144\157\x6f\x72\137\x75\x72\x6c", $RZ);
        update_option("\155\x6f\x5f\x73\x61\x6d\154\137\x6d\145\x73\163\141\x67\x65", "\123\151\x67\x6e\x20\x49\x6e\x20\163\145\164\x74\x69\156\x67\163\40\165\x70\144\x61\164\x65\x64\56");
        $this->mo_saml_show_success_message();
        mX:
        goto te;
        O7:
        if (mo_saml_is_sp_configured()) {
            goto l7;
        }
        update_option("\155\157\137\163\141\155\x6c\137\155\x65\163\x73\x61\147\x65", "\120\x6c\x65\141\x73\145\40\x63\x6f\x6d\160\154\x65\x74\145\x20" . addLink("\123\145\162\166\x69\x63\x65\x20\120\162\x6f\x76\x69\x64\145\x72", add_query_arg(array("\x74\x61\142" => "\163\x61\166\x65"), $_SERVER["\122\x45\x51\125\105\123\x54\x5f\x55\122\111"])) . "\40\x63\157\156\146\x69\147\165\x72\141\x74\151\157\x6e\40\x66\x69\x72\x73\x74\56");
        $this->mo_saml_show_error_message();
        goto C7;
        l7:
        if (array_key_exists("\x6d\x6f\137\163\141\155\154\x5f\145\x6e\141\142\x6c\x65\x5f\154\157\147\151\156\137\x72\145\144\x69\x72\145\143\164", $_POST)) {
            goto RQ;
        }
        $jY = "\146\x61\154\x73\x65";
        goto FV;
        RQ:
        $jY = htmlspecialchars($_POST["\x6d\157\x5f\x73\141\x6d\x6c\x5f\x65\x6e\x61\x62\x6c\145\137\154\x6f\147\x69\x6e\x5f\x72\145\144\x69\x72\x65\x63\164"]);
        FV:
        if ($jY == "\164\x72\165\x65") {
            goto VB;
        }
        update_option("\x6d\x6f\x5f\163\141\155\154\137\x65\x6e\x61\x62\154\145\x5f\154\x6f\147\151\x6e\x5f\162\x65\144\151\162\x65\143\164", "\146\141\154\x73\x65");
        update_option("\155\157\137\x73\x61\x6d\154\137\141\x6c\x6c\157\167\x5f\167\160\137\163\x69\147\156\x69\156", "\106\x61\x6c\x73\x65");
        goto Id;
        VB:
        update_option("\155\x6f\137\163\141\x6d\154\x5f\x65\156\x61\142\x6c\x65\137\x6c\x6f\147\151\x6e\x5f\x72\x65\144\x69\x72\145\x63\164", "\164\162\x75\x65");
        update_option("\155\x6f\137\x73\x61\x6d\x6c\137\141\x6c\x6c\x6f\167\x5f\167\x70\137\163\x69\147\156\151\x6e", "\x74\x72\165\145");
        Id:
        update_option("\x6d\x6f\137\163\141\155\x6c\x5f\155\x65\163\x73\x61\x67\x65", "\x53\x69\x67\156\x20\x69\x6e\40\157\160\164\x69\157\x6e\x73\40\x75\x70\x64\141\x74\x65\x64\x2e");
        $this->mo_saml_show_success_message();
        C7:
        te:
        goto hb;
        MO:
        if (mo_saml_is_sp_configured()) {
            goto WD;
        }
        update_option("\x6d\x6f\x5f\x73\x61\155\x6c\x5f\x6d\x65\163\x73\x61\x67\x65", "\120\154\145\x61\163\145\x20\x63\157\155\160\154\x65\x74\x65\x20" . addLink("\x53\145\162\166\151\x63\145\40\120\162\x6f\x76\x69\x64\145\x72", add_query_arg(array("\x74\x61\x62" => "\163\x61\x76\x65"), $_SERVER["\122\x45\121\x55\105\123\x54\x5f\x55\122\x49"])) . "\x20\x63\x6f\x6e\x66\151\x67\x75\x72\141\x74\x69\157\x6e\40\x66\151\162\163\x74\56");
        $this->mo_saml_show_error_message();
        goto cN;
        WD:
        if (array_key_exists("\x6d\x6f\x5f\163\141\x6d\154\137\145\x6e\x61\142\154\145\137\162\163\163\x5f\141\x63\143\x65\163\x73", $_POST)) {
            goto Fh;
        }
        $Ww = false;
        goto pA;
        Fh:
        $Ww = htmlspecialchars($_POST["\x6d\x6f\x5f\x73\141\155\x6c\137\145\156\141\142\x6c\145\137\x72\x73\x73\137\x61\x63\143\x65\x73\x73"]);
        pA:
        if ($Ww == "\164\162\165\145") {
            goto hN;
        }
        update_option("\x6d\157\137\x73\x61\155\x6c\137\145\156\141\142\154\145\137\162\163\163\137\141\x63\x63\145\x73\x73", "\146\x61\154\x73\x65");
        goto H5;
        hN:
        update_option("\155\157\137\x73\x61\x6d\154\137\145\156\x61\x62\x6c\145\137\162\163\x73\137\x61\x63\143\145\163\x73", "\x74\162\x75\145");
        H5:
        update_option("\x6d\157\x5f\x73\141\155\x6c\x5f\x6d\x65\x73\x73\141\147\145", "\x52\123\x53\x20\106\145\145\x64\40\157\160\x74\x69\157\156\x20\165\x70\144\141\164\x65\x64\56");
        $this->mo_saml_show_success_message();
        cN:
        hb:
        goto OT;
        Be:
        if (mo_saml_is_sp_configured()) {
            goto WG;
        }
        update_option("\155\157\137\x73\x61\155\x6c\x5f\155\x65\x73\163\141\147\x65", "\120\154\145\141\163\x65\40\x63\157\x6d\x70\x6c\x65\164\x65\40" . addLink("\123\x65\162\x76\151\x63\145\x20\x50\x72\x6f\166\151\x64\145\x72", add_query_arg(array("\x74\141\x62" => "\x73\x61\166\x65"), $_SERVER["\x52\x45\121\125\x45\123\x54\137\125\122\111"])) . "\x20\x63\157\156\146\x69\x67\x75\x72\141\x74\151\157\156\40\x66\x69\x72\x73\x74\56");
        $this->mo_saml_show_error_message();
        goto La;
        WG:
        if (array_key_exists("\155\x6f\x5f\x73\141\x6d\154\x5f\x66\x6f\x72\143\x65\x5f\x61\x75\x74\150\145\156\x74\151\x63\x61\x74\x69\x6f\x6e", $_POST)) {
            goto CN;
        }
        $jY = "\x66\x61\x6c\163\x65";
        goto kK;
        CN:
        $jY = htmlspecialchars($_POST["\155\x6f\x5f\x73\x61\x6d\154\137\146\x6f\162\143\x65\x5f\x61\x75\164\150\x65\x6e\x74\151\143\141\x74\x69\157\x6e"]);
        kK:
        if ($jY == "\164\162\165\145") {
            goto g1;
        }
        update_option("\155\x6f\x5f\x73\x61\x6d\154\137\146\x6f\162\x63\x65\x5f\141\165\x74\150\145\156\164\151\143\141\164\x69\x6f\x6e", "\x66\141\154\x73\x65");
        goto qC;
        g1:
        update_option("\155\157\x5f\x73\141\x6d\154\137\x66\x6f\x72\143\145\x5f\141\x75\164\x68\x65\x6e\x74\x69\x63\x61\x74\x69\157\156", "\164\x72\165\145");
        qC:
        update_option("\x6d\157\x5f\163\141\155\154\137\x6d\x65\163\163\x61\147\x65", "\123\151\x67\156\x20\151\156\40\x6f\x70\164\151\157\156\163\40\x75\160\144\141\x74\145\144\x2e");
        $this->mo_saml_show_success_message();
        La:
        OT:
        goto cm;
        Ig:
        if (mo_saml_is_sp_configured()) {
            goto Z1;
        }
        update_option("\x6d\x6f\x5f\163\x61\155\154\x5f\x6d\x65\x73\x73\141\147\x65", "\x50\x6c\145\141\x73\145\40\x63\x6f\x6d\x70\154\x65\x74\145\x20" . addLink("\x53\145\x72\x76\x69\143\145\x20\120\162\x6f\166\x69\144\145\162", add_query_arg(array("\164\141\142" => "\x73\141\166\x65"), $_SERVER["\122\x45\x51\x55\x45\x53\x54\137\x55\122\x49"])) . "\40\x63\x6f\156\146\x69\147\165\162\x61\164\x69\x6f\x6e\x20\x66\x69\x72\x73\164\56");
        $this->mo_saml_show_error_message();
        goto UP;
        Z1:
        if (array_key_exists("\x6d\157\137\x73\141\x6d\154\137\162\145\147\151\x73\164\145\162\145\x64\x5f\x6f\x6e\154\x79\x5f\x61\x63\x63\145\x73\x73", $_POST)) {
            goto FF;
        }
        $jY = "\x66\x61\x6c\x73\x65";
        goto Ds;
        FF:
        $jY = htmlspecialchars($_POST["\x6d\x6f\137\x73\x61\155\x6c\137\162\145\x67\x69\163\164\x65\x72\145\144\x5f\x6f\156\x6c\171\x5f\141\x63\x63\x65\x73\163"]);
        Ds:
        if ($jY == "\164\x72\x75\145") {
            goto lM;
        }
        update_option("\x6d\157\x5f\x73\x61\155\154\x5f\162\x65\147\x69\x73\164\x65\x72\145\144\137\x6f\x6e\154\171\137\141\143\143\145\x73\163", "\x66\x61\154\x73\x65");
        goto zt;
        lM:
        update_option("\x6d\x6f\137\x73\x61\x6d\x6c\x5f\162\145\x67\x69\x73\164\x65\162\145\144\x5f\157\x6e\x6c\171\137\x61\143\143\x65\163\163", "\x74\x72\x75\x65");
        zt:
        update_option("\x6d\157\x5f\x73\141\x6d\154\x5f\x6d\145\x73\163\141\x67\x65", "\x53\x69\147\x6e\x20\151\156\40\x6f\160\164\x69\x6f\x6e\x73\40\x75\160\144\141\164\x65\144\56");
        $this->mo_saml_show_success_message();
        UP:
        cm:
        goto H_;
        Yy:
        if (mo_saml_is_extension_installed("\143\x75\162\154")) {
            goto vo;
        }
        update_option("\155\x6f\137\x73\141\155\154\x5f\x6d\145\x73\x73\141\147\x65", "\105\122\x52\x4f\x52\72\40\120\x48\x50\40\143\x55\122\114\40\145\170\x74\x65\156\x73\151\x6f\156\x20\151\x73\x20\x6e\x6f\164\x20\x69\156\x73\164\x61\x6c\154\x65\x64\40\x6f\162\40\144\151\163\x61\142\154\x65\144\56\40\x52\x65\163\x65\x6e\x64\40\117\124\120\x20\146\x61\151\x6c\145\144\x2e");
        $this->mo_saml_show_error_message();
        return;
        vo:
        $En = sanitize_text_field($_POST["\x70\150\157\156\x65"]);
        $En = str_replace("\x20", '', $En);
        $En = str_replace("\x2d", '', $En);
        update_option("\155\157\x5f\x73\x61\x6d\154\137\x61\144\x6d\x69\x6e\137\160\x68\157\x6e\x65", $En);
        $Di = new CustomerSaml();
        $NE = $Di->send_otp_token('', $En, FALSE, TRUE);
        if ($NE) {
            goto Jl;
        }
        return;
        Jl:
        $NE = json_decode($NE, true);
        if (strcasecmp($NE["\163\164\141\x74\165\163"], "\123\125\103\x43\105\x53\123") == 0) {
            goto Ne;
        }
        update_option("\x6d\157\137\x73\141\x6d\x6c\137\155\x65\163\163\141\x67\x65", "\x54\x68\x65\x72\145\40\x77\141\163\40\141\x6e\x20\145\x72\162\157\x72\x20\151\x6e\40\163\x65\156\x64\x69\x6e\147\40\123\115\x53\56\40\x50\x6c\145\141\x73\x65\40\143\x6c\x69\x63\x6b\40\157\x6e\40\x52\x65\163\145\156\x64\40\117\124\x50\x20\x74\157\40\164\162\x79\x20\x61\x67\141\151\156\56");
        update_option("\x6d\x6f\137\163\x61\x6d\x6c\x5f\x72\x65\147\x69\x73\164\x72\x61\164\x69\157\156\x5f\x73\x74\141\x74\x75\x73", "\x4d\x4f\x5f\117\124\x50\137\x44\105\x4c\x49\126\105\122\105\x44\137\x46\101\111\x4c\125\122\x45\137\x50\110\x4f\116\x45");
        $this->mo_saml_show_error_message();
        goto eu;
        Ne:
        update_option("\x6d\157\137\163\141\155\154\x5f\155\145\163\163\x61\x67\145", "\40\x41\x20\157\x6e\145\x20\x74\151\155\x65\40\x70\x61\163\x73\143\157\144\145\40\x69\163\x20\x73\145\156\x74\40\x74\x6f\x20" . get_option("\155\x6f\x5f\x73\141\x6d\154\137\x61\144\x6d\151\156\137\160\x68\157\156\145") . "\x2e\x20\x50\154\145\141\x73\x65\40\x65\x6e\164\x65\x72\x20\x74\150\x65\40\x6f\x74\x70\x20\x68\x65\x72\145\x20\164\157\x20\166\x65\x72\x69\x66\171\40\171\157\x75\x72\40\145\x6d\141\151\154\56");
        update_option("\x6d\x6f\x5f\x73\x61\155\x6c\x5f\164\162\141\156\163\141\x63\164\x69\x6f\x6e\x49\144", $NE["\x74\x78\x49\144"]);
        update_option("\x6d\157\x5f\x73\141\155\154\137\162\145\x67\x69\x73\164\162\141\164\x69\x6f\156\137\163\164\141\164\165\x73", "\115\117\137\117\124\120\x5f\x44\105\x4c\x49\126\105\122\x45\104\137\123\x55\103\103\x45\x53\x53\x5f\x50\110\x4f\116\x45");
        $this->mo_saml_show_success_message();
        eu:
        H_:
        goto Xi;
        LR:
        update_option("\155\157\x5f\163\141\x6d\154\x5f\162\x65\147\151\x73\x74\162\141\x74\151\x6f\156\137\x73\164\x61\164\x75\163", '');
        update_option("\155\157\137\163\141\155\x6c\137\x76\145\x72\151\x66\x79\137\x63\x75\163\164\x6f\155\x65\x72", '');
        delete_option("\155\x6f\x5f\163\x61\155\x6c\137\156\145\167\137\162\x65\147\151\x73\x74\162\141\164\151\x6f\x6e");
        delete_option("\155\x6f\137\x73\x61\155\154\x5f\141\144\155\151\156\137\145\x6d\141\151\154");
        delete_option("\x6d\157\137\x73\141\155\x6c\x5f\x61\144\155\x69\x6e\137\160\x68\157\x6e\145");
        delete_site_option("\163\155\x6c\137\x6c\x6b");
        delete_site_option("\164\x5f\x73\x69\x74\x65\137\x73\x74\141\x74\x75\163");
        delete_site_option("\163\151\x74\x65\x5f\143\x6b\137\154");
        Xi:
        goto ZY;
        s4:
        if (mo_saml_is_extension_installed("\x63\x75\162\154")) {
            goto Z2;
        }
        update_option("\155\x6f\137\163\141\155\x6c\x5f\x6d\145\x73\x73\x61\x67\x65", "\x45\x52\122\x4f\x52\72\x20\120\x48\x50\40\143\x55\122\114\x20\145\x78\x74\145\x6e\x73\x69\x6f\x6e\40\x69\163\x20\x6e\x6f\x74\x20\x69\156\x73\164\x61\x6c\154\145\144\40\x6f\162\40\x64\x69\163\141\x62\x6c\x65\144\56\x20\x52\x65\x73\145\x6e\x64\x20\117\124\120\40\146\x61\151\x6c\x65\144\56");
        $this->mo_saml_show_error_message();
        return;
        Z2:
        $En = get_option("\x6d\x6f\137\163\x61\x6d\154\137\141\x64\155\151\x6e\137\x70\x68\x6f\x6e\x65");
        $Di = new CustomerSaml();
        $NE = $Di->send_otp_token('', $En, FALSE, TRUE);
        if ($NE) {
            goto yf;
        }
        return;
        yf:
        $NE = json_decode($NE, true);
        if (strcasecmp($NE["\163\x74\x61\x74\x75\x73"], "\123\125\x43\x43\105\x53\123") == 0) {
            goto xm;
        }
        update_option("\x6d\x6f\x5f\x73\x61\x6d\x6c\137\x6d\x65\163\163\x61\x67\x65", "\124\x68\x65\162\145\40\167\x61\163\40\x61\156\x20\145\x72\x72\157\x72\x20\x69\x6e\x20\163\145\x6e\144\x69\x6e\147\x20\145\x6d\x61\151\x6c\x2e\x20\120\x6c\x65\141\163\x65\x20\x63\x6c\151\x63\x6b\40\157\x6e\40\x52\145\x73\145\156\144\x20\117\124\120\40\x74\x6f\x20\164\162\171\x20\141\147\x61\151\156\56");
        update_option("\155\x6f\x5f\x73\x61\x6d\x6c\x5f\x72\x65\x67\x69\x73\x74\x72\141\164\x69\157\x6e\137\163\x74\x61\x74\165\x73", "\115\x4f\x5f\117\124\120\137\104\105\x4c\111\x56\x45\x52\x45\104\137\106\x41\x49\114\125\x52\105\x5f\120\110\117\x4e\105");
        $this->mo_saml_show_error_message();
        goto mt;
        xm:
        update_option("\x6d\x6f\137\163\x61\155\x6c\137\155\x65\x73\163\x61\x67\x65", "\40\101\40\157\156\x65\40\164\151\155\145\40\x70\x61\x73\x73\143\157\x64\145\x20\x69\163\40\x73\145\156\164\40\x74\x6f\40" . $En . "\40\141\x67\141\151\x6e\x2e\x20\120\154\x65\x61\163\x65\40\143\150\145\x63\153\x20\x69\x66\x20\x79\157\x75\40\147\157\x74\x20\x74\150\x65\x20\157\164\160\40\x61\x6e\144\40\x65\x6e\x74\x65\162\x20\x69\164\40\x68\x65\162\145\x2e");
        update_option("\155\x6f\137\163\x61\x6d\x6c\137\x74\x72\141\x6e\163\141\143\x74\x69\x6f\x6e\x49\144", $NE["\164\x78\x49\x64"]);
        update_option("\x6d\x6f\x5f\163\141\x6d\154\137\x72\145\x67\151\163\x74\162\x61\x74\x69\x6f\x6e\137\163\x74\141\164\165\163", "\x4d\x4f\x5f\x4f\124\x50\137\x44\x45\x4c\111\x56\x45\122\105\104\137\123\x55\103\103\105\123\x53\x5f\x50\x48\x4f\x4e\x45");
        $this->mo_saml_show_success_message();
        mt:
        ZY:
        goto mq;
        cu:
        if (mo_saml_is_extension_installed("\x63\165\x72\x6c")) {
            goto DT;
        }
        update_option("\x6d\157\x5f\163\141\x6d\154\x5f\x6d\145\163\x73\x61\x67\145", "\105\122\x52\117\122\72\40\120\x48\120\x20\143\x55\x52\x4c\40\145\170\164\145\156\x73\x69\x6f\x6e\x20\151\163\40\x6e\x6f\x74\x20\151\156\163\164\141\154\154\x65\x64\x20\x6f\x72\x20\144\x69\163\x61\x62\154\x65\144\x2e\40\122\x65\x73\145\x6e\144\x20\x4f\x54\x50\x20\x66\141\151\154\x65\x64\56");
        $this->mo_saml_show_error_message();
        return;
        DT:
        $OH = get_option("\155\x6f\x5f\163\x61\155\154\x5f\x61\x64\x6d\151\156\x5f\145\155\x61\151\154");
        $Di = new CustomerSaml();
        $NE = $Di->send_otp_token($OH, '');
        if ($NE) {
            goto KJ;
        }
        return;
        KJ:
        $NE = json_decode($NE, true);
        if (strcasecmp($NE["\163\x74\141\164\x75\163"], "\x53\x55\103\103\105\x53\123") == 0) {
            goto fo;
        }
        update_option("\x6d\x6f\x5f\163\141\x6d\154\137\155\x65\163\x73\141\147\x65", "\124\x68\145\x72\145\x20\167\141\163\40\141\156\40\x65\162\x72\x6f\x72\x20\x69\x6e\40\163\x65\x6e\144\151\x6e\147\40\x65\155\x61\151\x6c\56\40\x50\154\145\x61\163\x65\40\x63\154\x69\143\x6b\40\x6f\156\40\x52\x65\163\x65\156\x64\40\117\124\120\40\164\x6f\x20\x74\162\171\x20\x61\147\141\151\156\x2e");
        update_option("\x6d\157\137\163\x61\155\x6c\137\x72\x65\147\151\x73\x74\162\141\x74\151\157\x6e\137\163\x74\x61\164\165\163", "\x4d\x4f\137\117\x54\x50\137\x44\105\114\x49\x56\105\x52\105\x44\137\106\x41\x49\x4c\x55\x52\x45\x5f\x45\x4d\x41\x49\114");
        $this->mo_saml_show_error_message();
        goto Qe;
        fo:
        update_option("\x6d\157\x5f\x73\x61\155\x6c\137\155\145\163\x73\141\147\145", "\40\x41\40\x6f\156\145\40\164\151\x6d\x65\40\160\141\x73\x73\143\157\x64\145\40\x69\x73\x20\163\x65\x6e\x74\x20\x74\x6f\40" . get_option("\x6d\x6f\137\x73\141\155\x6c\x5f\141\x64\155\151\x6e\137\145\155\x61\x69\154") . "\40\141\147\x61\151\156\x2e\40\120\154\x65\x61\x73\145\x20\x63\150\145\x63\153\x20\151\x66\40\171\157\165\x20\x67\157\164\x20\x74\x68\x65\x20\157\164\160\x20\x61\156\144\x20\145\x6e\x74\145\x72\40\x69\164\x20\150\145\162\145\56");
        update_option("\155\x6f\137\163\141\x6d\154\x5f\x74\162\x61\x6e\x73\141\x63\164\151\157\x6e\x49\x64", $NE["\x74\170\111\x64"]);
        update_option("\x6d\x6f\x5f\x73\x61\x6d\154\137\162\145\x67\x69\x73\x74\162\141\164\151\x6f\156\137\x73\x74\x61\164\x75\x73", "\115\117\137\x4f\x54\x50\x5f\104\x45\114\x49\126\105\x52\x45\104\x5f\123\125\103\x43\105\x53\x53\137\105\x4d\101\111\x4c");
        $this->mo_saml_show_success_message();
        Qe:
        mq:
        goto MZ;
        Ot:
        if (mo_saml_is_extension_installed("\x63\165\162\154")) {
            goto Gu;
        }
        update_option("\155\157\x5f\163\x61\155\x6c\137\155\145\163\163\x61\x67\145", "\105\x52\122\x4f\x52\72\x20\120\x48\x50\x20\x63\x55\122\x4c\40\145\x78\x74\x65\156\163\151\157\156\40\x69\163\x20\x6e\x6f\164\40\151\156\163\x74\141\154\154\x65\x64\40\x6f\162\40\x64\151\163\x61\142\154\145\144\x2e\40\121\x75\145\162\171\x20\x73\x75\142\155\x69\x74\x20\146\x61\151\x6c\x65\144\56");
        $this->mo_saml_show_error_message();
        return;
        Gu:
        $OH = htmlspecialchars($_POST["\155\157\x5f\x73\x61\x6d\154\x5f\x63\157\156\164\x61\x63\x74\137\x75\163\137\x65\x6d\141\151\x6c"]);
        $En = htmlspecialchars($_POST["\x6d\157\x5f\163\141\x6d\x6c\x5f\x63\x6f\156\x74\x61\143\x74\137\165\x73\137\160\150\x6f\x6e\145"]);
        $KK = htmlspecialchars($_POST["\155\157\137\163\x61\155\x6c\x5f\143\157\156\164\141\143\164\x5f\x75\163\x5f\x71\x75\x65\x72\171"]);
        if (array_key_exists("\163\145\156\144\x5f\160\154\165\147\151\x6e\137\x63\x6f\156\146\x69\147", $_POST) === true) {
            goto qu;
        }
        update_option("\x73\x65\156\x64\137\x70\154\165\x67\x69\156\137\143\157\156\146\151\147", "\157\x66\x66");
        goto UE;
        qu:
        $sF = miniorange_import_export(true, true);
        $KK .= $sF;
        delete_option("\x73\x65\156\x64\x5f\160\154\165\147\x69\x6e\137\143\x6f\156\146\x69\x67");
        UE:
        $Di = new CustomerSaml();
        if ($this->mo_saml_check_empty_or_null($OH) || $this->mo_saml_check_empty_or_null($KK)) {
            goto lz;
        }
        if (!filter_var($OH, FILTER_VALIDATE_EMAIL)) {
            goto Rq;
        }
        $J7 = $Di->submit_contact_us($OH, $En, $KK);
        if ($J7) {
            goto kR;
        }
        return;
        kR:
        update_option("\155\x6f\x5f\x73\141\x6d\x6c\137\155\x65\163\163\x61\147\145", "\x54\150\x61\x6e\153\x73\40\x66\x6f\x72\x20\147\x65\x74\x74\x69\x6e\147\x20\151\156\40\x74\157\x75\143\x68\41\40\127\145\x20\x73\x68\141\x6c\x6c\40\147\145\x74\40\142\x61\143\x6b\x20\164\x6f\x20\171\x6f\165\x20\163\x68\x6f\x72\164\154\171\56");
        $this->mo_saml_show_success_message();
        goto K3;
        Rq:
        update_option("\x6d\x6f\137\x73\141\155\154\x5f\x6d\145\x73\163\141\147\x65", "\120\x6c\145\141\163\145\x20\145\x6e\x74\x65\x72\40\141\x20\x76\141\x6c\151\144\x20\145\x6d\x61\151\154\40\141\x64\144\162\x65\x73\x73\56");
        $this->mo_saml_show_error_message();
        K3:
        goto q_;
        lz:
        update_option("\x6d\x6f\137\x73\x61\x6d\154\137\x6d\x65\x73\163\x61\147\145", "\120\154\145\141\x73\x65\40\x66\151\x6c\x6c\x20\x75\160\40\x45\155\141\x69\x6c\40\141\156\x64\40\121\165\x65\x72\x79\x20\146\151\x65\x6c\144\x73\x20\164\157\40\x73\165\142\155\151\x74\40\x79\157\x75\162\40\x71\x75\x65\162\171\56");
        $this->mo_saml_show_error_message();
        q_:
        MZ:
        goto RV;
        yV:
        if (!$this->mo_saml_check_empty_or_null($_POST["\x73\x61\155\x6c\137\x6c\151\143\x65\156\x63\x65\137\153\x65\x79"])) {
            goto nX;
        }
        update_option("\x6d\157\137\x73\x61\x6d\x6c\x5f\x6d\145\163\x73\x61\x67\x65", "\x41\154\x6c\40\x74\150\x65\x20\x66\151\x65\154\x64\x73\x20\x61\x72\145\x20\162\145\x71\165\151\162\x65\144\56\40\120\x6c\x65\141\163\x65\40\145\x6e\164\145\162\40\166\141\154\151\144\x20\154\x69\x63\145\x6e\163\x65\x20\x6b\x65\171\56");
        $this->mo_saml_show_error_message();
        return;
        nX:
        $yJ = htmlspecialchars(trim($_POST["\x73\141\x6d\x6c\x5f\x6c\151\x63\x65\156\x63\145\x5f\x6b\x65\x79"]));
        $Di = new Customersaml();
        $NE = $Di->check_customer_ln();
        if ($NE) {
            goto Tp;
        }
        return;
        Tp:
        $NE = json_decode($NE, true);
        if (strcasecmp($NE["\x73\x74\141\x74\x75\x73"], "\123\125\x43\x43\105\123\x53") == 0) {
            goto F4;
        }
        $Xr = get_option("\x6d\157\137\x73\141\x6d\x6c\x5f\x63\165\x73\x74\x6f\155\x65\162\137\x74\157\x6b\145\x6e");
        update_option("\x73\x69\164\x65\x5f\x63\x6b\x5f\x6c", AESEncryption::encrypt_data("\146\141\154\163\145", $Xr));
        $pZ = add_query_arg(array("\164\141\142" => "\154\x69\x63\145\x6e\x73\151\156\x67"), $_SERVER["\x52\105\x51\x55\105\x53\124\x5f\x55\x52\111"]);
        update_option("\x6d\x6f\137\163\141\x6d\x6c\137\155\x65\163\163\141\147\x65", "\x59\x6f\x75\x20\150\x61\x76\145\x20\156\x6f\164\40\x75\x70\147\162\141\x64\145\144\40\x79\x65\x74\56\40" . addLink("\103\154\x69\143\x6b\x20\x68\x65\162\x65", $pZ) . "\x20\164\x6f\40\x75\x70\147\x72\x61\x64\145\x20\164\x6f\x20\160\x72\145\x6d\151\165\155\40\x76\x65\x72\163\x69\x6f\156\56");
        $this->mo_saml_show_error_message();
        goto M2;
        F4:
        $NE = $Di->mo_saml_vl($yJ, false);
        if ($NE) {
            goto a5;
        }
        return;
        a5:
        $NE = json_decode($NE, true);
        update_option("\166\154\x5f\143\x68\x65\x63\x6b\x5f\164", time());
        if (strcasecmp($NE["\x73\164\141\164\165\163"], "\123\125\103\103\105\123\x53") == 0) {
            goto lZ;
        }
        if (strcasecmp($NE["\x73\x74\x61\164\x75\163"], "\106\x41\x49\114\x45\x44") == 0) {
            goto j5;
        }
        update_option("\155\x6f\137\x73\x61\x6d\154\137\x6d\145\x73\163\141\147\145", "\101\156\40\x65\x72\162\x6f\x72\40\157\143\x63\165\x72\x65\x64\x20\167\150\151\154\x65\x20\x70\162\x6f\x63\x65\x73\x73\151\x6e\x67\x20\x79\157\165\162\x20\162\x65\x71\x75\145\x73\x74\56\40\120\x6c\145\x61\163\x65\x20\124\x72\171\x20\141\147\141\x69\x6e\x2e");
        $this->mo_saml_show_error_message();
        goto cC;
        j5:
        if (strcasecmp($NE["\x6d\145\163\x73\x61\x67\x65"], "\103\157\x64\x65\x20\x68\141\163\40\105\170\x70\x69\x72\x65\x64") == 0) {
            goto a4;
        }
        update_option("\x6d\x6f\137\163\x61\155\154\137\155\145\x73\x73\141\147\x65", "\x59\157\165\40\x68\x61\166\145\x20\145\156\x74\145\162\145\x64\x20\x61\x6e\x20\x69\x6e\166\x61\x6c\x69\144\x20\154\151\x63\145\156\x73\145\x20\153\145\171\x2e\40\120\154\x65\x61\x73\x65\x20\145\x6e\164\145\162\40\141\x20\x76\x61\154\151\x64\x20\x6c\151\143\x65\x6e\163\x65\x20\153\x65\x79\56");
        goto X1;
        a4:
        $pZ = add_query_arg(array("\x74\141\x62" => "\x6c\151\x63\x65\x6e\x73\x69\x6e\147"), $_SERVER["\122\105\121\125\105\x53\x54\x5f\x55\122\x49"]);
        update_option("\155\157\x5f\163\141\x6d\154\x5f\x6d\x65\x73\x73\141\x67\145", "\x4c\151\143\x65\156\163\145\x20\x6b\145\x79\x20\x79\157\x75\40\150\x61\x76\x65\40\145\x6e\164\145\162\145\144\x20\150\141\x73\40\x61\x6c\162\x65\141\x64\x79\40\x62\x65\x65\156\40\x75\x73\x65\144\x2e\x20\120\154\x65\141\163\x65\x20\145\x6e\x74\x65\x72\x20\x61\x20\153\x65\171\40\x77\x68\x69\143\x68\40\x68\x61\163\x20\x6e\x6f\x74\40\142\145\x65\156\40\165\163\145\144\x20\x62\145\x66\x6f\x72\x65\40\157\156\40\141\x6e\171\x20\157\x74\150\145\x72\x20\151\156\x73\164\x61\x6e\143\x65\x20\157\x72\x20\x69\146\x20\x79\x6f\x75\x20\150\x61\166\x65\40\145\x78\x61\165\163\164\145\144\x20\x61\154\x6c\x20\171\157\x75\162\x20\153\145\x79\x73\x20\164\x68\x65\x6e\40" . addLink("\x43\154\x69\143\x6b\40\x68\x65\162\145", $pZ) . "\x20\164\157\x20\x62\165\x79\x20\x6d\157\x72\145\x2e");
        X1:
        $this->mo_saml_show_error_message();
        cC:
        goto SI;
        lZ:
        $Xr = get_option("\155\157\137\163\x61\x6d\x6c\137\x63\x75\163\x74\x6f\x6d\x65\x72\x5f\x74\x6f\153\x65\x6e");
        update_option("\163\155\154\x5f\x6c\x6b", AESEncryption::encrypt_data($yJ, $Xr));
        $sc = "\131\x6f\x75\162\x20\x6c\151\x63\145\x6e\163\x65\40\x69\x73\40\166\145\162\151\x66\x69\x65\x64\56\x20\x59\x6f\x75\x20\143\141\x6e\40\156\157\167\x20\163\x65\164\165\x70\x20\x74\150\x65\40\160\154\x75\147\x69\x6e\56";
        update_option("\x6d\x6f\137\x73\x61\155\154\137\155\x65\x73\x73\x61\147\x65", $sc);
        $Xr = get_option("\155\x6f\137\x73\141\155\154\x5f\143\165\x73\x74\x6f\x6d\x65\162\137\x74\157\x6b\x65\156");
        update_option("\x73\151\164\145\137\143\x6b\137\154", AESEncryption::encrypt_data("\x74\162\x75\145", $Xr));
        update_option("\x74\x5f\x73\151\x74\145\137\x73\x74\x61\x74\165\x73", AESEncryption::encrypt_data("\x66\141\154\x73\x65", $Xr));
        $O6 = plugin_dir_path(__FILE__);
        $Vs = home_url();
        $Vs = trim($Vs, "\57");
        if (preg_match("\x23\x5e\150\164\x74\x70\x28\163\x29\x3f\x3a\57\57\43", $Vs)) {
            goto Tl;
        }
        $Vs = "\x68\164\x74\x70\x3a\57\x2f" . $Vs;
        Tl:
        $NC = parse_url($Vs);
        $N8 = preg_replace("\x2f\x5e\x77\x77\167\x5c\56\57", '', $NC["\x68\x6f\163\x74"]);
        $Hx = wp_upload_dir();
        $W_ = $N8 . "\x2d" . $Hx["\142\x61\163\145\144\x69\x72"];
        $dR = hash_hmac("\163\x68\x61\x32\65\x36", $W_, "\64\104\x48\x66\152\x67\x66\152\x61\x73\x6e\144\x66\163\141\152\146\110\x47\112");
        $zZ = $this->djkasjdksa();
        $Rv = round(strlen($zZ) / rand(2, 20));
        $zZ = substr_replace($zZ, $dR, $Rv, 0);
        $By = base64_decode($zZ);
        if (is_writable($O6 . "\x6c\151\x63\x65\x6e\163\145")) {
            goto nU;
        }
        $zZ = str_rot13($zZ);
        $ht = base64_decode("\142\107\x4e\153\x61\x6d\x74\x68\x63\x32\160\153\x61\x33\x4e\150\x59\x32\167\75");
        update_option($ht, $zZ);
        goto Aj;
        nU:
        file_put_contents($O6 . "\154\151\143\x65\x6e\x73\145", $By);
        Aj:
        update_option("\x6c\x63\167\x72\x74\x6c\146\163\141\155\x6c", true);
        $pZ = add_query_arg(array("\164\x61\x62" => "\x67\x65\156\x65\162\141\154"), $_SERVER["\x52\105\121\125\105\123\124\x5f\125\x52\x49"]);
        $this->mo_saml_show_success_message();
        SI:
        M2:
        RV:
        goto rv;
        A_:
        if (mo_saml_is_extension_installed("\x63\x75\162\154")) {
            goto d6;
        }
        update_option("\155\x6f\x5f\163\141\155\154\137\155\x65\163\163\141\x67\145", "\x45\x52\122\x4f\x52\x3a\120\110\120\40\143\x55\x52\114\40\x65\x78\164\145\156\x73\x69\x6f\x6e\40\151\x73\40\x6e\x6f\164\40\x69\156\x73\x74\x61\x6c\154\x65\x64\x20\157\x72\40\x64\x69\x73\x61\142\x6c\145\x64\56\40\x56\x61\x6c\151\x64\x61\x74\145\x20\117\x54\120\40\146\141\x69\x6c\x65\x64\56");
        $this->mo_saml_show_error_message();
        return;
        d6:
        $Yo = '';
        if ($this->mo_saml_check_empty_or_null($_POST["\x6f\x74\160\x5f\164\157\153\x65\x6e"])) {
            goto vz;
        }
        $Yo = sanitize_text_field($_POST["\x6f\x74\160\x5f\x74\x6f\x6b\145\156"]);
        goto NV;
        vz:
        update_option("\155\x6f\137\163\x61\155\154\x5f\x6d\145\x73\163\x61\x67\x65", "\120\x6c\145\x61\163\145\x20\x65\x6e\164\x65\162\x20\x61\x20\x76\141\x6c\165\x65\x20\151\x6e\x20\x6f\x74\x70\x20\x66\151\145\154\144\56");
        $this->mo_saml_show_error_message();
        return;
        NV:
        $Di = new CustomerSaml();
        $NE = $Di->validate_otp_token(get_option("\155\x6f\137\163\x61\x6d\154\137\x74\x72\141\x6e\163\x61\x63\164\x69\157\x6e\111\144"), $Yo);
        if ($NE) {
            goto wO;
        }
        return;
        wO:
        $NE = json_decode($NE, true);
        if (strcasecmp($NE["\x73\x74\x61\164\165\x73"], "\x53\125\x43\103\105\x53\x53") == 0) {
            goto d0;
        }
        update_option("\x6d\x6f\x5f\163\x61\155\x6c\137\155\x65\163\163\141\x67\145", "\111\156\166\x61\x6c\x69\x64\x20\157\x6e\145\x20\164\151\x6d\x65\x20\160\x61\163\163\x63\157\x64\145\x2e\x20\120\x6c\145\x61\x73\x65\x20\145\x6e\164\x65\x72\40\141\40\x76\x61\x6c\151\x64\x20\x6f\x74\x70\x2e");
        $this->mo_saml_show_error_message();
        goto D0;
        d0:
        $this->create_customer();
        D0:
        rv:
        if (!self::mo_check_option_admin_referer("\155\157\x5f\x73\141\x6d\154\x5f\x66\162\145\x65\x5f\x74\x72\x69\x61\154")) {
            goto hT;
        }
        if (decryptSamlElement()) {
            goto r3;
        }
        $yJ = postResponse();
        $Di = new Customersaml();
        $NE = $Di->mo_saml_vl($yJ, false);
        if ($NE) {
            goto FC;
        }
        return;
        FC:
        $NE = json_decode($NE, true);
        if (strcasecmp($NE["\x73\x74\141\164\165\163"], "\x53\125\103\x43\105\123\123") == 0) {
            goto lb;
        }
        if (strcasecmp($NE["\x73\164\141\164\x75\x73"], "\106\101\x49\x4c\105\104") == 0) {
            goto Ci;
        }
        update_option("\155\x6f\x5f\x73\141\155\154\x5f\x6d\x65\x73\x73\x61\x67\x65", "\101\156\40\145\162\162\157\x72\x20\157\x63\143\x75\162\x65\144\40\x77\150\151\x6c\x65\40\160\162\x6f\x63\x65\x73\x73\x69\156\147\x20\171\x6f\165\162\40\x72\145\x71\165\145\x73\x74\56\40\120\x6c\145\141\x73\x65\x20\124\162\x79\x20\x61\147\x61\151\156\x2e");
        $this->mo_saml_show_error_message();
        goto mR;
        Ci:
        update_option("\x6d\x6f\x5f\163\x61\x6d\x6c\137\155\145\x73\163\x61\x67\x65", "\x54\150\145\162\x65\40\x77\141\x73\x20\141\156\x20\145\x72\x72\x6f\162\x20\141\x63\x74\x69\x76\x61\164\151\x6e\147\40\x79\x6f\x75\x72\x20\x54\122\x49\x41\114\40\166\x65\162\x73\x69\157\x6e\56\40\x50\x6c\145\141\163\x65\40\143\x6f\156\x74\141\x63\x74\40\x69\x6e\146\x6f\100\170\x65\x63\165\162\151\146\171\56\x63\157\x6d\40\x66\x6f\x72\40\147\x65\x74\164\x69\x6e\x67\x20\x6e\145\167\x20\x6c\x69\x63\x65\x6e\163\x65\x20\146\x6f\x72\x20\164\162\x69\x61\154\x20\166\x65\162\163\x69\x6f\x6e\x2e");
        $this->mo_saml_show_error_message();
        mR:
        goto yK;
        lb:
        $Xr = get_option("\155\x6f\x5f\163\x61\x6d\x6c\137\143\x75\x73\164\x6f\x6d\145\x72\x5f\x74\x6f\x6b\x65\156");
        $Xr = get_option("\x6d\157\x5f\163\141\155\154\137\x63\x75\163\164\157\x6d\145\x72\x5f\x74\157\x6b\x65\x6e");
        update_option("\x74\137\163\x69\164\145\x5f\163\x74\x61\x74\x75\x73", AESEncryption::encrypt_data("\x74\x72\x75\x65", $Xr));
        update_option("\x6d\x6f\137\163\141\x6d\154\137\155\145\x73\163\x61\147\x65", "\x59\157\x75\x72\40\65\40\144\x61\171\163\x20\x54\x52\111\101\x4c\x20\151\x73\x20\141\x63\x74\151\166\141\164\145\144\x2e\40\131\157\165\x20\143\x61\156\x20\156\x6f\167\x20\x73\145\164\165\160\40\164\150\145\40\160\154\165\x67\x69\156\x2e");
        $this->mo_saml_show_success_message();
        yK:
        goto pZ;
        r3:
        update_option("\x6d\157\137\x73\x61\x6d\154\x5f\x6d\145\163\x73\141\x67\x65", "\124\150\x65\162\x65\x20\x77\x61\x73\40\x61\x6e\x20\145\162\162\x6f\162\x20\x61\143\x74\151\166\x61\x74\151\x6e\147\40\x79\x6f\x75\162\x20\x54\122\x49\101\114\x20\166\x65\x72\163\151\157\156\x2e\x20\105\151\164\150\x65\162\x20\171\157\165\162\40\x74\162\151\141\x6c\x20\160\x65\162\x69\157\144\x20\151\163\40\145\170\160\151\162\145\x64\x20\x6f\162\40\171\157\165\40\141\x72\x65\40\165\163\x69\156\147\x20\167\x72\157\156\147\40\164\162\x69\x61\154\x20\166\145\x72\163\151\x6f\x6e\56\40\x50\x6c\145\141\163\145\x20\x63\x6f\x6e\164\141\143\164\40\151\x6e\146\157\x40\x78\145\143\165\162\151\146\171\56\x63\x6f\155\x20\146\157\162\40\x67\145\164\x74\x69\156\x67\40\156\x65\167\x20\x6c\x69\x63\145\156\163\x65\40\x66\x6f\162\x20\x74\162\x69\x61\x6c\40\166\145\x72\x73\151\157\x6e\56");
        $this->mo_saml_show_error_message();
        pZ:
        hT:
        if (!self::mo_check_option_admin_referer("\155\x6f\137\x73\141\155\x6c\137\x63\x68\x65\x63\153\137\154\x69\x63\x65\156\x73\x65")) {
            goto Ld;
        }
        $Di = new Customersaml();
        $NE = $Di->check_customer_ln();
        if ($NE) {
            goto tc;
        }
        return;
        tc:
        $NE = json_decode($NE, true);
        if (strcasecmp($NE["\163\x74\x61\164\165\163"], "\x53\x55\103\x43\105\123\123") == 0) {
            goto bN;
        }
        $Xr = get_option("\155\157\x5f\x73\x61\155\x6c\137\143\165\x73\x74\157\155\x65\x72\137\164\x6f\x6b\145\x6e");
        update_option("\163\151\164\145\137\143\x6b\137\x6c", AESEncryption::encrypt_data("\x66\141\154\163\x65", $Xr));
        $pZ = add_query_arg(array("\x74\x61\x62" => "\x6c\151\x63\x65\x6e\163\151\x6e\147"), $_SERVER["\x52\x45\121\125\105\x53\x54\137\x55\122\x49"]);
        update_option("\155\x6f\137\x73\141\155\x6c\137\x6d\145\163\x73\141\x67\x65", "\131\x6f\x75\x20\x68\x61\166\145\x20\156\x6f\164\x20\165\x70\x67\162\141\x64\145\x64\40\x79\x65\x74\x2e\x20" . addLink("\x43\x6c\151\x63\153\40\150\145\x72\x65", $pZ) . "\x20\x74\157\40\x75\x70\147\162\x61\144\x65\x20\164\157\x20\x70\162\145\x6d\x69\x75\155\40\166\145\x72\x73\x69\x6f\156\x2e");
        $this->mo_saml_show_error_message();
        goto hH;
        bN:
        if (array_key_exists("\154\x69\143\145\x6e\x73\x65\120\154\x61\156", $NE) && !$this->mo_saml_check_empty_or_null($NE["\x6c\x69\x63\x65\156\163\x65\x50\x6c\x61\x6e"])) {
            goto LB;
        }
        $Xr = get_option("\x6d\x6f\137\x73\x61\155\154\x5f\x63\165\x73\x74\157\155\x65\x72\x5f\x74\x6f\x6b\145\x6e");
        update_option("\x73\x69\x74\145\137\x63\x6b\137\x6c", AESEncryption::encrypt_data("\146\x61\x6c\163\x65", $Xr));
        $pZ = add_query_arg(array("\x74\x61\x62" => "\154\x69\143\145\156\163\x69\156\x67"), $_SERVER["\x52\x45\x51\125\105\123\x54\x5f\125\x52\111"]);
        update_option("\x6d\157\x5f\x73\141\x6d\154\137\155\x65\163\x73\141\147\x65", "\131\x6f\165\40\150\x61\x76\x65\40\156\157\x74\40\x75\160\147\162\141\144\x65\x64\40\x79\145\164\x2e\x20" . addLink("\103\x6c\x69\143\153\x20\150\x65\162\x65", $pZ) . "\40\x74\157\x20\165\160\147\162\x61\144\x65\40\164\x6f\x20\x70\162\x65\155\x69\165\155\x20\166\145\162\163\x69\x6f\156\56");
        $this->mo_saml_show_error_message();
        goto xF;
        LB:
        update_option("\x6d\x6f\x5f\x73\x61\155\154\x5f\x6c\x69\143\145\156\x73\145\x5f\156\x61\155\145", base64_encode($NE["\x6c\151\x63\x65\156\163\145\x50\154\x61\x6e"]));
        $Xr = get_option("\x6d\157\137\163\x61\155\154\137\x63\x75\163\x74\x6f\155\x65\x72\137\164\x6f\153\145\x6e");
        if (!(array_key_exists("\x6e\157\117\x66\x55\163\x65\x72\163", $NE) && !$this->mo_saml_check_empty_or_null($NE["\x6e\157\117\146\x55\x73\145\x72\x73"]))) {
            goto mh;
        }
        update_option("\x6d\x6f\x5f\x73\141\x6d\154\137\x75\163\x72\137\x6c\x6d\164", AESEncryption::encrypt_data($NE["\x6e\157\117\x66\125\x73\145\162\163"], $Xr));
        mh:
        if (!(array_key_exists("\154\x69\x63\x65\156\163\x65\x45\x78\x70\x69\x72\x79", $NE) && !$this->mo_saml_check_empty_or_null($NE["\x6c\x69\143\x65\156\x73\x65\105\170\x70\151\162\171"]))) {
            goto Ij;
        }
        update_option("\155\x6f\137\x73\x61\x6d\154\x5f\154\x69\143\145\x6e\163\x65\137\x65\170\160\151\x72\x79\137\x64\141\164\x65", $this->mo_saml_parse_expiry_date($NE["\154\151\143\145\x6e\x73\x65\x45\x78\x70\151\x72\171"]));
        Ij:
        update_option("\163\x69\x74\145\137\143\x6b\x5f\154", AESEncryption::encrypt_data("\x74\x72\165\x65", $Xr));
        $O6 = plugin_dir_path(__FILE__);
        $Vs = home_url();
        $Vs = trim($Vs, "\57");
        if (preg_match("\x23\x5e\x68\x74\164\x70\x28\163\x29\x3f\72\57\x2f\x23", $Vs)) {
            goto CM;
        }
        $Vs = "\150\x74\164\160\x3a\x2f\x2f" . $Vs;
        CM:
        $NC = parse_url($Vs);
        $N8 = preg_replace("\x2f\x5e\x77\167\x77\134\56\57", '', $NC["\150\x6f\163\x74"]);
        $Hx = wp_upload_dir();
        $W_ = $N8 . "\55" . $Hx["\x62\141\163\x65\x64\151\x72"];
        $dR = hash_hmac("\163\150\141\62\x35\66", $W_, "\64\x44\x48\146\x6a\x67\146\152\141\x73\156\x64\x66\163\141\x6a\146\110\107\112");
        $zZ = $this->djkasjdksa();
        $Rv = round(strlen($zZ) / rand(2, 20));
        $zZ = substr_replace($zZ, $dR, $Rv, 0);
        $By = base64_decode($zZ);
        if (is_writable($O6 . "\x6c\x69\x63\x65\156\163\x65")) {
            goto SM;
        }
        $zZ = str_rot13($zZ);
        $ht = base64_decode("\x62\x47\x4e\x6b\x61\155\x74\150\143\62\160\153\x61\63\x4e\x68\x59\x32\x77\x3d");
        update_option($ht, $zZ);
        goto sW;
        SM:
        file_put_contents($O6 . "\x6c\151\143\x65\156\x73\145", $By);
        sW:
        update_option("\154\143\167\x72\164\154\146\x73\x61\x6d\x6c", true);
        $pZ = add_query_arg(array("\164\141\142" => "\x67\x65\156\145\162\141\154"), $_SERVER["\122\105\121\125\105\123\x54\137\125\122\111"]);
        update_option("\155\157\137\163\x61\155\x6c\137\x6d\145\163\163\x61\147\145", "\131\x6f\165\x20\x68\141\166\145\40\163\x75\143\143\x65\163\x73\146\x75\x6c\154\171\x20\165\x70\x67\x72\x61\144\x65\x64\40\x79\157\x75\162\40\x6c\x69\143\x65\156\x73\145\x2e");
        $this->mo_saml_show_success_message();
        xF:
        hH:
        Ld:
        if (!self::mo_check_option_admin_referer("\155\157\x5f\x73\x61\155\154\x5f\162\x65\155\x6f\166\x65\x5f\x61\x63\143\157\x75\156\164")) {
            goto kC;
        }
        $this->mo_sso_saml_deactivate();
        add_option("\x6d\x6f\x5f\x73\141\155\x6c\x5f\162\x65\147\151\x73\x74\162\141\164\x69\157\x6e\x5f\163\x74\141\164\x75\x73", "\162\145\155\x6f\x76\x65\144\x5f\141\x63\143\157\165\156\164");
        $pZ = add_query_arg(array("\164\x61\142" => "\x6c\157\x67\x69\x6e"), $_SERVER["\x52\105\x51\x55\105\123\x54\x5f\x55\122\x49"]);
        header("\114\157\x63\141\164\151\157\156\72\40" . $pZ);
        kC:
        y0:
    }
    function create_customer()
    {
        $Di = new CustomerSaml();
        $Xs = $Di->create_customer();
        if ($Xs) {
            goto ZE;
        }
        return;
        ZE:
        $Xs = json_decode($Xs, true);
        if (strcasecmp($Xs["\163\164\141\164\x75\x73"], "\103\x55\x53\x54\117\115\x45\122\137\125\x53\105\x52\116\101\115\105\x5f\x41\x4c\122\105\101\x44\131\x5f\x45\x58\111\x53\x54\123") == 0) {
            goto D2;
        }
        if (!(strcasecmp($Xs["\163\164\x61\164\165\x73"], "\123\125\103\x43\105\x53\123") == 0)) {
            goto Wy;
        }
        update_option("\x6d\157\137\163\141\x6d\x6c\x5f\141\144\155\151\156\x5f\143\165\163\164\x6f\155\x65\162\137\153\145\171", $Xs["\151\144"]);
        update_option("\155\x6f\137\x73\141\x6d\154\137\141\144\x6d\151\x6e\137\141\160\x69\x5f\153\x65\x79", $Xs["\141\x70\x69\x4b\x65\x79"]);
        update_option("\x6d\x6f\x5f\163\141\x6d\x6c\137\x63\x75\163\164\157\x6d\x65\x72\x5f\164\157\x6b\145\156", $Xs["\x74\157\153\x65\x6e"]);
        update_option("\x6d\x6f\x5f\163\x61\155\154\x5f\141\144\155\151\156\x5f\160\141\x73\163\x77\157\x72\144", '');
        update_option("\155\157\x5f\x73\141\x6d\154\137\155\x65\163\163\141\x67\145", "\x54\150\141\x6e\x6b\x20\x79\157\x75\x20\146\x6f\x72\40\x72\145\x67\x69\163\x74\x65\162\151\156\x67\x20\167\x69\164\x68\40\x58\x65\143\165\162\151\x66\171\x2e");
        update_option("\155\157\x5f\163\141\155\154\137\x72\x65\x67\151\x73\x74\162\x61\x74\x69\157\x6e\137\163\x74\x61\x74\x75\x73", '');
        delete_option("\155\x6f\x5f\x73\x61\x6d\x6c\137\x76\x65\162\x69\146\171\137\x63\165\x73\x74\157\155\145\x72");
        delete_option("\155\157\x5f\x73\141\x6d\154\137\156\x65\167\137\162\x65\147\151\x73\164\162\x61\x74\151\x6f\x6e");
        $this->mo_saml_show_success_message();
        Wy:
        goto B9;
        D2:
        $this->get_current_customer();
        B9:
        update_option("\x6d\x6f\137\163\x61\155\154\x5f\141\x64\x6d\151\x6e\x5f\x70\141\x73\x73\167\x6f\x72\144", '');
    }
    function get_current_customer()
    {
        $Di = new CustomerSaml();
        $NE = $Di->get_customer_key();
        if ($NE) {
            goto W2;
        }
        return;
        W2:
        $Xs = json_decode($NE, true);
        if (json_last_error() == JSON_ERROR_NONE) {
            goto Fy;
        }
        update_option("\155\157\x5f\163\x61\155\x6c\137\155\145\x73\x73\141\x67\x65", "\x59\157\x75\40\x61\x6c\162\x65\141\144\x79\40\x68\x61\x76\x65\40\x61\x6e\40\141\143\143\157\x75\156\x74\x20\x77\151\164\150\x20\155\151\156\x69\117\162\x61\x6e\x67\145\x2e\x20\120\x6c\145\x61\x73\x65\40\145\156\164\145\x72\x20\x61\40\166\141\x6c\151\x64\40\160\x61\x73\163\x77\157\162\x64\x2e");
        update_option("\x6d\x6f\137\x73\x61\x6d\x6c\137\x76\x65\x72\x69\146\x79\x5f\x63\x75\163\164\157\x6d\x65\x72", "\164\162\165\145");
        delete_option("\x6d\x6f\x5f\163\141\x6d\154\x5f\156\145\167\x5f\162\145\147\x69\163\x74\x72\x61\x74\151\x6f\x6e");
        $this->mo_saml_show_error_message();
        goto N0;
        Fy:
        update_option("\x6d\157\x5f\x73\141\x6d\x6c\137\x61\x64\155\x69\156\137\143\165\163\x74\157\x6d\145\162\137\153\145\171", $Xs["\151\x64"]);
        update_option("\x6d\x6f\x5f\x73\141\x6d\154\137\141\x64\x6d\151\156\x5f\141\x70\151\x5f\153\x65\171", $Xs["\x61\160\x69\x4b\145\171"]);
        update_option("\155\157\x5f\163\x61\155\154\137\143\165\163\164\157\155\145\162\x5f\x74\157\x6b\x65\156", $Xs["\x74\x6f\153\x65\156"]);
        update_option("\155\157\x5f\163\141\x6d\154\137\141\x64\155\x69\x6e\137\160\141\163\163\167\x6f\162\x64", '');
        update_option("\155\157\x5f\x73\x61\x6d\154\x5f\x6d\x65\163\x73\141\147\145", "\131\x6f\165\162\x20\x61\x63\143\x6f\165\156\164\x20\150\x61\163\40\x62\145\145\156\40\x72\145\x74\162\x69\x65\166\x65\144\x20\x73\165\x63\x63\145\x73\163\x66\x75\154\x6c\171\56");
        delete_option("\155\157\137\163\x61\155\x6c\x5f\166\x65\162\151\146\171\137\143\165\163\164\157\x6d\x65\162");
        delete_option("\x6d\157\x5f\x73\x61\x6d\x6c\x5f\156\145\167\137\x72\145\147\x69\163\164\162\x61\x74\151\157\156");
        $this->mo_saml_show_success_message();
        N0:
    }
    public function mo_saml_check_empty_or_null($tq)
    {
        if (!(!isset($tq) || empty($tq))) {
            goto b1;
        }
        return true;
        b1:
        return false;
    }
    function miniorange_sso_menu()
    {
        $Vn = add_menu_page("\x4d\x4f\40\x53\101\x4d\114\40\x53\145\x74\x74\151\x6e\x67\x73\x20" . __("\x43\x6f\x6e\146\151\147\165\162\x65\x20\x53\101\x4d\114\40\111\x64\145\x6e\x74\151\164\171\40\x50\162\157\x76\151\144\x65\x72\40\146\157\x72\40\x53\123\x4f", "\x6d\157\137\x73\141\x6d\154\137\163\x65\164\164\151\156\x67\x73"), "\x6d\x69\x6e\x69\x4f\162\141\x6e\147\x65\x20\x53\101\x4d\114\40\x32\x2e\x30\x20\x53\123\x4f", "\x61\x64\x6d\x69\x6e\151\163\164\x72\x61\x74\157\x72", "\x6d\157\137\163\x61\155\154\x5f\x73\145\164\164\151\x6e\147\x73", array($this, "\155\x6f\137\154\157\147\x69\x6e\137\167\151\x64\x67\145\164\137\x73\x61\x6d\154\137\157\160\164\151\157\x6e\163"), plugin_dir_url(__FILE__) . "\x69\155\141\147\x65\163\57\x6d\x69\156\x69\x6f\x72\x61\156\x67\145\56\160\x6e\x67");
    }
    function mo_saml_redirect_for_authentication($sp)
    {
        if (!mo_saml_is_customer_license_key_verified()) {
            goto ud;
        }
        if (!(get_option("\155\157\137\x73\141\x6d\154\137\162\x65\x67\x69\163\164\x65\162\145\144\137\157\x6e\154\171\x5f\141\x63\143\145\x73\163") == "\x74\x72\x75\145")) {
            goto eG;
        }
        $base_url = home_url();
        echo "\x3c\x73\x63\x72\x69\x70\164\76\167\x69\156\x64\157\x77\x2e\x6c\157\x63\x61\x74\151\157\156\x2e\150\162\x65\146\x3d\47{$base_url}\x2f\x3f\x6f\160\x74\x69\x6f\x6e\75\x73\141\x6d\x6c\137\x75\163\145\x72\x5f\x6c\157\x67\x69\156\x26\x72\145\144\x69\x72\145\143\x74\137\x74\157\x3d\x27\x2b\x65\x6e\x63\x6f\144\x65\x55\122\111\103\157\155\160\x6f\156\145\156\x74\50\167\151\156\144\x6f\167\56\154\x6f\143\141\164\151\x6f\156\56\150\x72\145\x66\x29\x3b\74\57\163\x63\162\151\x70\164\76";
        die;
        eG:
        if (!(mo_saml_is_sp_configured() && get_option("\155\157\137\163\141\155\154\137\145\156\x61\x62\154\x65\x5f\154\x6f\147\x69\156\137\162\145\144\x69\162\145\143\164") == "\164\162\165\x65")) {
            goto zO;
        }
        $f4 = get_option("\x6d\x6f\137\x73\x61\155\x6c\137\x73\160\137\x62\141\x73\145\x5f\x75\162\x6c");
        if (!empty($f4)) {
            goto oC;
        }
        $f4 = home_url();
        oC:
        if (!(get_option("\x6d\x6f\x5f\x73\x61\155\x6c\x5f\162\x65\x6c\x61\171\x5f\x73\164\141\x74\145") && get_option("\x6d\157\137\163\141\155\154\x5f\x72\145\154\141\x79\x5f\x73\x74\141\164\x65") != '')) {
            goto wg;
        }
        $sp = get_option("\155\157\x5f\x73\141\x6d\154\137\162\x65\154\141\x79\137\x73\x74\x61\x74\x65");
        wg:
        $sp = mo_saml_get_relay_state($sp);
        $QS = empty($sp) ? "\57" : $sp;
        $km = htmlspecialchars_decode(get_option("\163\x61\x6d\x6c\x5f\x6c\157\147\x69\x6e\137\165\162\x6c"));
        $ON = get_option("\163\141\155\154\x5f\x6c\x6f\x67\151\x6e\x5f\x62\x69\x6e\x64\x69\x6e\147\137\x74\x79\160\145");
        $Mc = get_option("\x6d\157\x5f\163\141\155\x6c\137\x66\x6f\x72\x63\x65\137\141\165\164\150\145\156\164\x69\x63\x61\164\151\x6f\x6e");
        $BW = $f4 . "\57";
        $fK = get_option("\155\x6f\137\163\141\x6d\x6c\x5f\x73\x70\137\145\156\x74\151\164\x79\x5f\151\144");
        $gn = get_option("\x73\141\155\154\137\x6e\141\155\145\151\x64\x5f\146\157\162\155\x61\x74");
        if (!empty($gn)) {
            goto fp;
        }
        $gn = "\61\x2e\61\x3a\x6e\141\155\x65\x69\x64\x2d\x66\x6f\x72\x6d\x61\164\72\145\x6d\141\x69\154\101\144\144\x72\145\x73\163";
        fp:
        if (!empty($fK)) {
            goto aa;
        }
        $fK = $f4 . "\57\x77\160\55\143\157\156\x74\145\156\164\57\160\154\x75\x67\x69\156\x73\x2f\155\x69\156\x69\157\162\x61\x6e\147\x65\55\163\x61\155\154\x2d\62\x30\55\163\151\x6e\x67\154\145\x2d\x73\x69\x67\156\x2d\157\x6e\x2f";
        aa:
        $bQ = SAMLSPUtilities::createAuthnRequest($BW, $fK, $km, $Mc, $ON, $gn);
        if (empty($ON) || $ON == "\110\x74\164\160\x52\145\144\151\x72\x65\143\164") {
            goto Ye;
        }
        if (!(get_option("\x73\141\x6d\x6c\137\162\x65\x71\165\x65\163\x74\x5f\163\x69\x67\x6e\x65\144") == "\x75\x6e\x63\150\145\143\x6b\x65\x64")) {
            goto d8;
        }
        $G6 = base64_encode($bQ);
        SAMLSPUtilities::postSAMLRequest($km, $G6, $QS);
        die;
        d8:
        $Jr = '';
        $rp = '';
        $G6 = SAMLSPUtilities::signXML($bQ, "\116\141\x6d\145\x49\x44\x50\157\x6c\151\x63\171");
        SAMLSPUtilities::postSAMLRequest($km, $G6, $QS);
        goto uL;
        Ye:
        $HR = $km;
        if (strpos($km, "\77") !== false) {
            goto h0;
        }
        $HR .= "\77";
        goto Iu;
        h0:
        $HR .= "\x26";
        Iu:
        if (!(get_option("\x73\x61\155\x6c\x5f\162\x65\x71\165\145\163\x74\137\163\151\147\x6e\x65\144") == "\x75\156\143\x68\x65\x63\x6b\145\x64")) {
            goto e0;
        }
        $HR .= "\x53\101\x4d\x4c\x52\145\x71\165\145\163\164\x3d" . $bQ . "\x26\122\145\154\x61\171\123\164\x61\x74\x65\x3d" . urlencode($QS);
        header("\x4c\x6f\x63\141\164\151\x6f\x6e\x3a\x20" . $HR);
        die;
        e0:
        $bQ = "\123\101\x4d\x4c\x52\x65\161\x75\145\x73\x74\75" . $bQ . "\x26\x52\x65\154\141\171\x53\164\x61\x74\145\x3d" . urlencode($QS) . "\46\x53\151\147\x41\154\147\x3d" . urlencode(XMLSecurityKey::RSA_SHA256);
        $ks = array("\164\x79\160\x65" => "\x70\162\x69\x76\x61\164\145");
        $Xr = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, $ks);
        $f3 = get_option("\155\x6f\137\x73\x61\155\x6c\137\x63\165\x72\162\145\156\164\x5f\143\x65\x72\x74\x5f\x70\162\x69\x76\x61\164\x65\x5f\153\145\x79");
        $Xr->loadKey($f3, FALSE);
        $Vl = new XMLSecurityDSig();
        $QR = $Xr->signData($bQ);
        $QR = base64_encode($QR);
        $HR .= $bQ . "\x26\x53\x69\147\x6e\x61\164\165\162\145\x3d" . urlencode($QR);
        header("\114\157\143\x61\x74\x69\157\156\72\40" . $HR);
        die;
        uL:
        zO:
        ud:
    }
    function mo_saml_authenticate()
    {
        $nZ = '';
        if (!isset($_REQUEST["\162\x65\x64\x69\162\145\x63\x74\137\164\x6f"])) {
            goto uE;
        }
        $nZ = htmlspecialchars($_REQUEST["\162\145\144\151\x72\145\143\x74\137\164\157"]);
        uE:
        if (!is_user_logged_in()) {
            goto vW;
        }
        $this->mo_saml_login_redirect($nZ);
        vW:
        if (!(get_option("\x6d\x6f\x5f\163\x61\x6d\x6c\137\x65\x6e\141\142\x6c\x65\x5f\154\x6f\x67\151\156\137\x72\145\144\151\162\145\x63\x74") == "\x74\162\165\x65")) {
            goto ZO;
        }
        $YW = get_option("\x6d\157\x5f\x73\x61\155\x6c\x5f\x62\141\x63\x6b\x64\x6f\x6f\162\137\165\162\x6c") ? trim(get_option("\x6d\x6f\137\163\x61\155\154\x5f\142\x61\143\153\144\x6f\157\162\x5f\165\x72\x6c")) : "\x66\x61\x6c\x73\x65";
        if (isset($_GET["\154\x6f\147\147\x65\144\x6f\165\164"]) && $_GET["\x6c\x6f\x67\x67\145\144\157\x75\164"] == "\164\x72\165\x65") {
            goto fx;
        }
        if (get_option("\155\157\137\x73\141\155\x6c\137\x61\154\x6c\157\167\x5f\167\x70\x5f\x73\151\147\156\x69\156") == "\x74\162\165\145") {
            goto dW;
        }
        goto a7;
        fx:
        header("\x4c\x6f\x63\141\x74\x69\x6f\x6e\72\40" . home_url());
        die;
        goto a7;
        dW:
        if (isset($_GET["\x73\x61\155\154\x5f\x73\x73\157"]) && $_GET["\163\x61\x6d\154\137\163\x73\157"] == $YW || isset($_POST["\x73\141\155\154\x5f\163\163\x6f"]) && $_POST["\x73\141\155\x6c\137\163\x73\x6f"] == $YW) {
            goto xo;
        }
        if (isset($_REQUEST["\162\x65\144\151\x72\145\x63\x74\137\x74\157"])) {
            goto ap;
        }
        goto ew;
        xo:
        return;
        goto ew;
        ap:
        $nZ = htmlspecialchars($_REQUEST["\x72\145\144\151\x72\145\x63\164\x5f\164\157"]);
        if (!(strpos($nZ, "\167\x70\x2d\x61\144\155\151\156") !== false && strpos($nZ, "\163\141\155\x6c\137\163\163\157\x3d" . $YW) !== false)) {
            goto lc;
        }
        return;
        lc:
        ew:
        a7:
        $this->mo_saml_redirect_for_authentication($nZ);
        ZO:
    }
    function mo_saml_login_redirect($nZ)
    {
        $UH = false;
        if (!(strcmp(admin_url(), $nZ) == 0 || strcmp(wp_login_url(), $nZ) == 0)) {
            goto EO;
        }
        $UH = true;
        EO:
        if (!empty($nZ) && !$UH) {
            goto cy;
        }
        header("\114\x6f\x63\141\164\151\157\x6e\72\x20" . site_url());
        goto oE;
        cy:
        header("\114\157\x63\141\164\151\157\x6e\x3a\x20" . htmlspecialchars_decode($nZ));
        oE:
        die;
    }
    function mo_saml_auto_redirect()
    {
        if (!current_user_can("\x72\145\141\x64")) {
            goto kH;
        }
        return;
        kH:
        if (!(get_option("\x6d\157\137\163\x61\x6d\x6c\137\162\x65\147\x69\x73\164\x65\162\145\x64\x5f\x6f\x6e\x6c\x79\137\x61\143\x63\145\163\x73") == "\x74\x72\165\x65")) {
            goto fN1;
        }
        if (!(get_option("\155\x6f\137\x73\141\x6d\x6c\x5f\145\x6e\x61\x62\x6c\145\137\x72\163\163\x5f\141\143\x63\x65\x73\163") == "\x74\162\165\x65" && is_feed())) {
            goto jM;
        }
        return;
        jM:
        $sp = saml_get_current_page_url();
        $this->mo_saml_redirect_for_authentication($sp);
        fN1:
    }
    function mo_saml_modify_login_form()
    {
        $YW = get_option("\x6d\x6f\x5f\x73\141\x6d\x6c\137\142\x61\143\153\x64\157\x6f\x72\x5f\165\x72\x6c") ? trim(get_option("\155\157\137\163\x61\155\x6c\137\x62\141\x63\x6b\x64\x6f\x6f\162\x5f\165\x72\154")) : "\x66\141\x6c\x73\x65";
        echo "\x3c\x69\x6e\160\165\164\40\164\x79\x70\145\x3d\x22\150\151\x64\144\x65\x6e\x22\40\156\x61\155\x65\x3d\42\x73\x61\x6d\154\137\x73\x73\157\x22\40\x76\141\154\165\145\75\x22" . $YW . "\x22\76" . "\12";
    }
    function djkasjdksa()
    {
        $YS = "\41\x7e\x40\43\x24\45\x5e\46\x2a\50\51\137\53\x7c\173\x7d\x3c\76\x3f\x30\61\62\x33\64\65\66\x37\x38\x39\x61\x62\x63\x64\145\146\x67\150\x69\x6a\x6b\x6c\x6d\156\157\160\x71\162\163\x74\x75\166\167\170\x79\172\101\x42\103\104\x45\106\x47\x48\111\112\x4b\x4c\x4d\116\x4f\120\121\122\123\124\125\126\127\x58\131\x5a";
        $g0 = strlen($YS);
        $eZ = '';
        $fL = 0;
        dF:
        if (!($fL < 10000)) {
            goto Aw;
        }
        $eZ .= $YS[rand(0, $g0 - 1)];
        lH:
        $fL++;
        goto dF;
        Aw:
        return $eZ;
    }
    function mo_get_saml_shortcode()
    {
        if (!is_user_logged_in()) {
            goto Eo;
        }
        $current_user = wp_get_current_user();
        $Vx = "\x48\x65\154\154\157\54";
        if (!get_option("\155\x6f\137\163\x61\155\x6c\137\143\165\163\x74\157\x6d\x5f\147\162\x65\145\x74\x69\156\x67\x5f\164\x65\x78\x74")) {
            goto me;
        }
        $Vx = get_option("\x6d\x6f\137\163\141\x6d\x6c\137\x63\165\x73\x74\157\x6d\x5f\147\x72\x65\x65\x74\151\156\x67\137\x74\145\170\x74");
        me:
        $Lj = '';
        if (!get_option("\155\x6f\137\163\141\155\x6c\137\x67\162\145\145\164\x69\x6e\x67\137\x6e\141\x6d\145")) {
            goto v5;
        }
        switch (get_option("\155\157\137\x73\x61\x6d\x6c\x5f\x67\x72\145\145\x74\x69\156\x67\x5f\x6e\x61\155\145")) {
            case "\125\x53\105\122\x4e\101\115\x45":
                $Lj = $current_user->user_login;
                goto Nu;
            case "\x45\115\101\111\114":
                $Lj = $current_user->user_email;
                goto Nu;
            case "\x46\x4e\x41\x4d\x45":
                $Lj = $current_user->user_firstname;
                goto Nu;
            case "\x4c\x4e\x41\115\x45":
                $Lj = $current_user->user_lastname;
                goto Nu;
            case "\106\x4e\101\x4d\105\137\114\x4e\x41\115\x45":
                $Lj = $current_user->user_firstname . "\40" . $current_user->user_lastname;
                goto Nu;
            case "\114\x4e\101\x4d\105\137\106\x4e\101\x4d\105":
                $Lj = $current_user->user_lastname . "\x20" . $current_user->user_firstname;
                goto Nu;
            default:
                $Lj = $current_user->user_login;
        }
        fA:
        Nu:
        v5:
        if (!empty(trim($Lj))) {
            goto CL;
        }
        $Lj = $current_user->user_login;
        CL:
        $KI = $Vx . "\x20" . $Lj;
        $Jw = "\x4c\x6f\147\157\x75\164";
        if (!get_option("\x6d\x6f\x5f\163\x61\155\x6c\137\143\x75\163\x74\157\x6d\137\154\x6f\x67\157\165\164\137\x74\x65\170\164")) {
            goto EF;
        }
        $Jw = get_option("\x6d\x6f\x5f\x73\141\x6d\154\x5f\143\165\163\x74\157\x6d\137\154\157\x67\x6f\165\164\137\x74\x65\170\164");
        EF:
        $Cp = $KI . "\40\x7c\40\x3c\141\x20\x68\x72\145\x66\75\42" . wp_logout_url(home_url()) . "\x22\40\164\151\x74\x6c\145\x3d\42\x6c\157\147\157\x75\x74\x22\40\76" . $Jw . "\74\x2f\x61\x3e\x3c\x2f\154\x69\x3e";
        $pZ = saml_get_current_page_url();
        update_option("\154\x6f\147\x6f\x75\x74\x5f\x72\145\144\151\162\145\143\x74\137\x75\162\x6c", $pZ);
        goto no;
        Eo:
        $f4 = get_option("\155\157\137\x73\x61\x6d\154\137\x73\x70\137\142\141\163\x65\137\x75\x72\x6c");
        if (!empty($f4)) {
            goto mQ;
        }
        $f4 = home_url();
        mQ:
        if (mo_saml_is_sp_configured() && mo_saml_is_customer_license_key_verified()) {
            goto T8;
        }
        $Cp = "\x53\x50\x20\x69\x73\40\x6e\x6f\164\x20\x63\157\x6e\x66\x69\x67\165\162\145\144\x2e";
        goto oR1;
        T8:
        $j7 = "\x4c\157\x67\x69\x6e\40\x77\151\164\150\40" . get_option("\x73\141\155\154\x5f\151\144\145\156\x74\x69\x74\x79\x5f\x6e\141\x6d\145");
        if (!get_option("\x6d\157\137\x73\141\155\154\x5f\x63\x75\x73\164\157\155\137\154\157\x67\151\x6e\x5f\164\145\x78\164")) {
            goto Db;
        }
        $j7 = get_option("\155\157\x5f\163\141\x6d\x6c\137\143\x75\163\164\157\x6d\x5f\x6c\157\147\x69\156\137\x74\x65\170\164");
        Db:
        $SZ = get_option("\x73\141\x6d\x6c\x5f\x69\x64\x65\156\164\x69\164\x79\137\x6e\x61\x6d\145");
        $j7 = str_replace("\43\43\111\x44\120\43\43", $SZ, $j7);
        $nZ = urlencode(saml_get_current_page_url());
        $Cp = "\x3c\x61\x20\x68\x72\x65\146\x3d\42" . $f4 . "\57\x3f\157\160\x74\151\157\x6e\75\x73\x61\x6d\x6c\137\165\x73\145\x72\x5f\154\157\147\151\156\46\162\145\144\x69\x72\x65\x63\164\x5f\164\157\x3d" . $nZ . "\x22\x3e" . $j7 . "\74\x2f\141\76";
        oR1:
        no:
        return $Cp;
    }
    function _handle_upload_metadata()
    {
        if (!(isset($_FILES["\155\x65\x74\x61\144\141\164\x61\137\x66\151\x6c\145"]) || isset($_POST["\x6d\145\x74\141\x64\141\164\x61\x5f\x75\x72\x6c"]))) {
            goto n1;
        }
        if (!empty($_FILES["\155\145\x74\x61\144\x61\164\141\x5f\x66\x69\x6c\x65"]["\164\155\160\137\156\x61\x6d\145"])) {
            goto eB;
        }
        if (mo_saml_is_extension_installed("\143\165\162\154")) {
            goto X4;
        }
        update_option("\x6d\157\137\x73\x61\155\x6c\x5f\x6d\x65\163\163\x61\147\145", "\120\x48\120\40\x63\x55\x52\x4c\40\145\x78\x74\x65\x6e\163\x69\x6f\x6e\40\x69\x73\x20\x6e\157\x74\40\x69\156\163\x74\141\154\x6c\x65\144\x20\x6f\162\40\x64\151\163\x61\142\x6c\145\144\x2e\40\x43\x61\156\x6e\x6f\164\x20\x66\x65\164\143\x68\40\155\145\x74\141\144\141\164\x61\x20\x66\162\157\155\x20\125\x52\x4c\56");
        $this->mo_saml_show_error_message();
        return;
        X4:
        $pZ = filter_var(htmlspecialchars($_POST["\155\145\x74\x61\x64\x61\164\x61\x5f\x75\x72\x6c"]), FILTER_SANITIZE_URL);
        $b0 = SAMLSPUtilities::mo_saml_wp_remote_call($pZ, array("\x73\x73\154\166\145\162\151\146\x79" => false), true);
        if (!is_null($b0)) {
            goto M0;
        }
        $Y2 = null;
        goto RJ;
        M0:
        $Y2 = $b0;
        RJ:
        goto Br;
        eB:
        $Y2 = @file_get_contents($_FILES["\x6d\145\x74\x61\x64\141\x74\x61\137\146\x69\154\x65"]["\164\x6d\x70\137\x6e\x61\x6d\x65"]);
        Br:
        if (!is_null($Y2)) {
            goto mi;
        }
        update_option("\155\157\137\163\141\x6d\x6c\137\x6d\145\x73\x73\141\147\145", "\x49\156\x76\x61\x6c\x69\144\40\x4d\145\x74\x61\144\x61\x74\141\40\106\151\x6c\145\x20\157\162\x20\125\x52\x4c");
        return;
        goto iK;
        mi:
        $this->upload_metadata($Y2);
        iK:
        n1:
    }
    function upload_metadata($Y2)
    {
        $Pp = set_error_handler(array($this, "\x68\x61\x6e\x64\x6c\145\130\155\x6c\105\x72\x72\157\x72"));
        $Gx = new DOMDocument();
        $Gx->loadXML($Y2);
        restore_error_handler();
        $gF = $Gx->firstChild;
        if (!empty($gF)) {
            goto WX;
        }
        if (!empty($_FILES["\x6d\x65\x74\141\144\x61\x74\141\137\146\151\154\x65"]["\164\155\160\137\x6e\x61\155\145"])) {
            goto PM;
        }
        if (!empty($_POST["\x6d\145\x74\141\144\141\x74\141\x5f\x75\x72\x6c"])) {
            goto Uz;
        }
        update_option("\x6d\157\x5f\163\x61\155\x6c\x5f\155\145\163\163\141\147\145", "\x50\154\x65\x61\163\145\40\x70\162\x6f\x76\x69\144\145\x20\x61\40\166\141\154\x69\144\x20\155\145\164\141\x64\x61\164\x61\40\146\x69\154\x65\x20\157\162\x20\141\40\x76\x61\154\151\144\40\x55\x52\114\56");
        $this->mo_saml_show_error_message();
        return;
        goto Vj;
        Uz:
        update_option("\155\x6f\137\x73\141\x6d\x6c\137\155\145\x73\x73\141\x67\145", "\x50\154\145\141\163\145\40\x70\x72\157\x76\151\x64\145\x20\x61\40\x76\x61\x6c\x69\x64\40\x6d\x65\164\141\x64\141\164\x61\x20\x55\x52\114\x2e");
        $this->mo_saml_show_error_message();
        return;
        Vj:
        goto AD;
        PM:
        update_option("\155\x6f\137\x73\x61\155\x6c\137\155\x65\x73\163\141\x67\145", "\120\154\145\x61\x73\145\x20\x70\162\x6f\166\x69\144\145\x20\141\x20\x76\141\x6c\151\x64\x20\155\145\164\141\x64\x61\x74\x61\40\146\x69\154\145\56");
        $this->mo_saml_show_error_message();
        return;
        AD:
        goto aR;
        WX:
        $ix = new IDPMetadataReader($Gx);
        $mM = $ix->getIdentityProviders();
        if (!(empty($mM) && !empty($_FILES["\x6d\x65\164\x61\144\141\164\141\x5f\146\151\154\x65"]["\x74\155\x70\x5f\x6e\141\155\x65"]))) {
            goto zS;
        }
        update_option("\x6d\157\x5f\x73\x61\155\154\137\x6d\145\163\163\x61\x67\x65", "\120\x6c\145\x61\x73\x65\x20\160\x72\157\x76\x69\144\145\x20\x61\x20\166\141\154\151\144\x20\155\145\x74\x61\x64\141\x74\141\40\146\x69\154\x65\56");
        $this->mo_saml_show_error_message();
        return;
        zS:
        if (!(empty($mM) && !empty($_POST["\155\x65\164\141\144\141\x74\141\x5f\x75\162\x6c"]))) {
            goto lQ;
        }
        update_option("\x6d\x6f\137\x73\x61\x6d\x6c\137\x6d\145\163\x73\141\x67\x65", "\x50\x6c\x65\x61\163\145\40\160\x72\157\x76\151\x64\x65\x20\x61\40\166\141\x6c\x69\x64\x20\155\145\x74\x61\144\x61\x74\x61\40\x55\122\114\56");
        $this->mo_saml_show_error_message();
        return;
        lQ:
        foreach ($mM as $Xr => $YG) {
            $JS = htmlspecialchars($_POST["\x73\141\x6d\x6c\137\x69\x64\x65\x6e\x74\x69\164\171\x5f\x6d\145\x74\x61\144\x61\164\x61\x5f\160\x72\157\166\151\x64\145\162"]);
            $PB = "\110\164\x74\x70\122\145\x64\151\x72\x65\143\x74";
            $UJ = '';
            if (array_key_exists("\x48\x54\x54\120\55\122\x65\144\151\162\x65\x63\x74", $YG->getLoginDetails())) {
                goto kw;
            }
            if (!array_key_exists("\110\124\124\x50\x2d\120\x4f\x53\x54", $YG->getLoginDetails())) {
                goto xR;
            }
            $PB = "\x48\x74\x74\160\x50\x6f\163\164";
            $UJ = $YG->getLoginURL("\110\124\x54\120\x2d\x50\x4f\123\x54");
            xR:
            goto iT;
            kw:
            $UJ = $YG->getLoginURL("\x48\124\x54\120\x2d\x52\145\x64\x69\x72\x65\143\x74");
            iT:
            $G0 = "\110\164\x74\x70\x52\145\144\151\x72\145\143\x74";
            $sN = '';
            if (array_key_exists("\110\124\x54\x50\x2d\x52\145\x64\151\x72\x65\x63\164", $YG->getLogoutDetails())) {
                goto Iy;
            }
            if (!array_key_exists("\x48\124\x54\120\x2d\x50\x4f\123\124", $YG->getLogoutDetails())) {
                goto W1;
            }
            $G0 = "\x48\x74\164\x70\120\157\163\x74";
            $sN = $YG->getLogoutURL("\110\x54\124\120\55\120\x4f\x53\124");
            W1:
            goto d4;
            Iy:
            $sN = $YG->getLogoutURL("\x48\124\124\120\x2d\x52\145\x64\151\162\x65\x63\164");
            d4:
            $BQ = $YG->getEntityID();
            $Ov = $YG->getSigningCertificate();
            update_option("\163\141\x6d\154\137\151\144\x65\x6e\x74\x69\x74\171\137\x6e\141\155\145", $JS);
            update_option("\x73\141\x6d\154\137\154\157\x67\x69\x6e\x5f\142\151\x6e\144\x69\x6e\147\x5f\x74\x79\160\145", $PB);
            update_option("\x73\x61\155\154\137\154\x6f\147\151\x6e\137\x75\x72\154", $UJ);
            update_option("\163\x61\155\x6c\137\154\157\x67\x6f\165\x74\x5f\142\x69\156\x64\x69\156\x67\x5f\x74\171\160\145", $G0);
            update_option("\x73\x61\155\154\x5f\154\x6f\147\157\x75\x74\x5f\x75\x72\x6c", $sN);
            update_option("\x73\x61\155\154\137\151\163\163\165\145\x72", $BQ);
            update_option("\x73\x61\155\154\x5f\156\x61\x6d\x65\x69\x64\x5f\x66\x6f\162\155\x61\x74", "\x31\56\x31\72\156\141\155\x65\151\x64\x2d\146\x6f\x72\155\x61\164\x3a\x75\x6e\163\160\x65\143\151\146\x69\145\x64");
            update_option("\x73\141\155\x6c\x5f\x78\65\x30\71\x5f\143\145\x72\164\151\x66\x69\143\141\164\x65", maybe_serialize($Ov));
            goto vx;
            Gd:
        }
        vx:
        update_option("\155\157\x5f\163\x61\x6d\154\x5f\155\x65\163\x73\141\x67\x65", "\111\x64\x65\x6e\x74\151\x74\x79\x20\x50\x72\x6f\x76\x69\144\145\x72\x20\x64\145\164\141\151\154\163\40\x73\x61\x76\x65\x64\40\163\165\143\x63\x65\x73\x73\x66\165\154\x6c\171\56");
        $this->mo_saml_show_success_message();
        aR:
    }
    function handleXmlError($yl, $Sl, $op, $aq)
    {
        if ($yl == E_WARNING && substr_count($Sl, "\x44\x4f\115\x44\157\143\165\x6d\x65\x6e\x74\x3a\72\x6c\x6f\x61\144\130\x4d\114\x28\x29") > 0) {
            goto Mh;
        }
        return false;
        goto qF;
        Mh:
        return;
        qF:
    }
    function mo_saml_plugin_action_links($ts)
    {
        $ts = array_merge(array("\x3c\x61\x20\x68\162\145\x66\x3d\x22" . esc_url(admin_url("\141\x64\155\x69\x6e\56\x70\150\x70\77\160\x61\x67\x65\x3d\x6d\x6f\x5f\x73\x61\155\154\x5f\x73\145\164\x74\151\x6e\147\163")) . "\x22\76" . __("\x53\x65\164\x74\x69\x6e\147\x73", "\164\x65\170\x74\x64\x6f\x6d\141\151\x6e") . "\74\x2f\141\76"), $ts);
        return $ts;
    }
    function checkPasswordPattern($nv)
    {
        $d_ = "\57\x5e\x5b\50\x5c\x77\x29\x2a\x28\x5c\41\x5c\100\x5c\x23\x5c\x24\x5c\45\x5c\136\x5c\x26\134\52\x5c\56\x5c\x2d\134\137\x29\52\135\53\44\57";
        return !preg_match($d_, $nv);
    }
    function mo_saml_parse_expiry_date($Qf)
    {
        $DT = new DateTime($Qf);
        $HB = $DT->getTimestamp();
        return date("\106\40\152\54\40\131", $HB);
    }
}
new saml_mo_login();
