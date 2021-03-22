<?php


include_once dirname(__FILE__) . "\x2f\125\x74\x69\154\151\x74\x69\x65\163\x2e\160\x68\160";
include_once dirname(__FILE__) . "\57\122\145\163\x70\157\156\163\x65\56\x70\150\x70";
include_once "\170\155\x6c\163\x65\x63\154\151\x62\163\x2e\160\x68\x70";
use RobRichards\XMLSecLibs\XMLSecurityKey;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecEnc;
if (class_exists("\101\105\x53\x45\x6e\143\162\171\x70\x74\x69\x6f\x6e")) {
    goto w2;
}
require_once dirname(__FILE__) . "\57\x69\156\x63\x6c\x75\144\x65\x73\x2f\154\x69\142\x2f\x65\x6e\143\x72\171\160\x74\151\157\x6e\x2e\x70\150\160";
w2:
class mo_login_wid extends WP_Widget
{
    public function __construct()
    {
        $UA = get_option("\163\x61\155\154\137\x69\x64\145\156\164\151\x74\x79\137\156\141\x6d\145");
        parent::__construct("\123\141\x6d\154\137\x4c\x6f\x67\151\x6e\137\127\151\x64\147\145\164", "\x4c\157\x67\x69\156\x20\x77\151\x74\150\x20" . $UA, array("\x64\145\163\x63\162\151\160\x74\151\x6f\x6e" => __("\124\150\x69\163\40\x69\163\40\x61\x20\155\151\x6e\151\117\162\141\x6e\x67\x65\40\123\x41\115\x4c\x20\x6c\157\147\x69\156\40\x77\x69\x64\147\x65\164\x2e", "\155\157\163\141\x6d\x6c")));
    }
    public function widget($kF, $F1)
    {
        extract($kF);
        $sj = apply_filters("\167\x69\144\147\145\x74\137\x74\151\x74\x6c\145", $F1["\167\x69\144\x5f\x74\151\x74\x6c\145"]);
        echo $kF["\142\x65\x66\x6f\162\x65\x5f\167\x69\x64\147\x65\x74"];
        if (empty($sj)) {
            goto UI;
        }
        echo $kF["\x62\x65\146\x6f\162\x65\137\164\151\164\x6c\x65"] . $sj . $kF["\141\x66\x74\145\162\x5f\164\151\x74\154\x65"];
        UI:
        $this->loginForm();
        echo $kF["\141\146\x74\x65\x72\x5f\167\x69\x64\x67\145\164"];
    }
    public function update($EB, $Iv)
    {
        $F1 = array();
        $F1["\167\x69\144\x5f\x74\151\164\154\145"] = strip_tags($EB["\167\x69\144\x5f\x74\151\164\154\145"]);
        return $F1;
    }
    public function form($F1)
    {
        $sj = '';
        if (!array_key_exists("\x77\151\x64\137\164\151\x74\x6c\x65", $F1)) {
            goto bJ;
        }
        $sj = $F1["\x77\x69\x64\x5f\x74\151\164\x6c\x65"];
        bJ:
        echo "\xd\xa\x9\x9\74\x70\76\74\154\141\x62\145\154\40\x66\157\x72\x3d\42" . $this->get_field_id("\x77\x69\144\x5f\x74\x69\164\154\x65") . "\x20\x22\x3e" . _e("\124\151\x74\x6c\145\72") . "\x20\x3c\57\154\x61\142\x65\x6c\76\xd\xa\x9\x9\x3c\x69\156\160\x75\164\x20\x63\154\x61\x73\x73\75\x22\x77\x69\x64\x65\146\141\x74\x22\x20\x69\144\x3d\x22" . $this->get_field_id("\x77\151\144\137\164\x69\x74\x6c\x65") . "\x22\x20\156\141\x6d\145\x3d\42" . $this->get_field_name("\167\151\144\137\164\x69\x74\154\145") . "\42\40\x74\171\x70\145\75\x22\164\145\170\x74\42\40\166\141\154\165\145\x3d\42" . $sj . "\x22\x20\57\76\15\xa\x9\x9\74\x2f\160\76";
    }
    public function loginForm()
    {
        if (!is_user_logged_in()) {
            goto BJ;
        }
        $current_user = wp_get_current_user();
        $Vx = "\x48\145\154\154\157\54";
        if (!get_option("\x6d\157\x5f\163\141\x6d\x6c\x5f\143\x75\163\164\157\155\137\x67\162\145\x65\x74\x69\x6e\x67\137\x74\x65\170\164")) {
            goto Cp;
        }
        $Vx = get_option("\x6d\x6f\x5f\x73\x61\x6d\x6c\x5f\143\165\x73\164\157\155\137\x67\162\x65\145\164\151\x6e\147\137\164\145\170\x74");
        Cp:
        $Lj = '';
        if (!get_option("\155\x6f\137\x73\141\155\x6c\x5f\147\x72\x65\145\x74\151\x6e\147\137\156\141\155\145")) {
            goto xV;
        }
        switch (get_option("\155\157\x5f\x73\141\155\x6c\x5f\x67\x72\x65\x65\x74\x69\x6e\x67\x5f\156\x61\x6d\145")) {
            case "\125\x53\105\122\116\101\x4d\x45":
                $Lj = $current_user->user_login;
                goto B6;
            case "\x45\115\101\111\114":
                $Lj = $current_user->user_email;
                goto B6;
            case "\106\116\x41\115\105":
                $Lj = $current_user->user_firstname;
                goto B6;
            case "\114\116\101\x4d\x45":
                $Lj = $current_user->user_lastname;
                goto B6;
            case "\x46\x4e\101\x4d\105\x5f\114\x4e\101\115\x45":
                $Lj = $current_user->user_firstname . "\40" . $current_user->user_lastname;
                goto B6;
            case "\x4c\x4e\x41\115\105\x5f\106\116\101\x4d\105":
                $Lj = $current_user->user_lastname . "\40" . $current_user->user_firstname;
                goto B6;
            default:
                $Lj = $current_user->user_login;
        }
        pN:
        B6:
        xV:
        if (!empty(trim($Lj))) {
            goto ws;
        }
        $Lj = $current_user->user_login;
        ws:
        $KI = $Vx . "\40" . $Lj;
        $Jw = "\x4c\157\147\x6f\165\164";
        if (!get_option("\155\x6f\x5f\163\141\155\154\x5f\x63\165\x73\164\x6f\x6d\137\x6c\x6f\x67\x6f\x75\164\x5f\164\x65\170\164")) {
            goto M5;
        }
        $Jw = get_option("\155\x6f\x5f\163\141\x6d\x6c\137\x63\165\x73\164\x6f\x6d\137\x6c\157\x67\157\165\164\137\164\145\x78\x74");
        M5:
        echo $KI . "\40\174\40\74\141\x20\x68\162\x65\146\75\x22" . wp_logout_url(home_url()) . "\x22\40\164\151\x74\154\x65\75\42\154\157\x67\x6f\x75\x74\x22\x20\x3e" . $Jw . "\74\57\141\76\x3c\57\x6c\x69\x3e";
        $pZ = saml_get_current_page_url();
        update_option("\x6c\157\x67\157\165\164\x5f\x72\x65\144\151\x72\x65\143\x74\x5f\165\162\x6c", $pZ);
        goto u8;
        BJ:
        $nZ = saml_get_current_page_url();
        echo "\xd\12\11\11\74\163\143\x72\x69\160\x74\76\xd\12\11\11\146\x75\x6e\x63\x74\151\157\156\x20\x73\x75\x62\x6d\x69\x74\123\141\155\x6c\106\x6f\x72\x6d\x28\51\173\40\x64\157\143\x75\155\x65\x6e\x74\x2e\147\145\x74\105\x6c\x65\155\145\x6e\164\x42\x79\111\144\x28\42\155\151\x6e\151\157\x72\141\x6e\147\x65\55\x73\x61\x6d\154\55\x73\x70\55\x73\x73\157\x2d\154\x6f\x67\151\156\x2d\x66\x6f\162\155\x22\x29\x2e\x73\165\142\x6d\x69\164\50\51\73\x20\175\xd\12\11\x9\74\57\x73\143\162\x69\x70\164\x3e\xd\12\x9\11\74\x66\x6f\x72\155\x20\156\x61\155\145\75\x22\x6d\x69\x6e\151\x6f\162\x61\156\x67\x65\x2d\x73\141\x6d\154\55\x73\160\55\163\x73\157\x2d\154\x6f\x67\151\x6e\x2d\146\x6f\x72\x6d\x22\40\151\x64\x3d\42\x6d\151\156\151\157\x72\141\x6e\x67\145\x2d\x73\x61\155\x6c\x2d\163\160\55\163\x73\157\55\x6c\157\x67\151\x6e\x2d\x66\157\162\155\x22\40\155\145\164\150\x6f\144\x3d\42\160\x6f\x73\x74\42\x20\141\x63\x74\151\x6f\156\75\x22\x22\x3e\15\12\11\11\x3c\151\x6e\x70\165\x74\x20\164\x79\x70\145\75\42\150\151\x64\x64\x65\x6e\x22\40\156\141\155\x65\x3d\x22\x6f\160\x74\151\x6f\156\42\40\x76\141\x6c\x75\145\x3d\42\x73\x61\155\x6c\137\x75\163\x65\162\x5f\x6c\x6f\147\151\156\42\x20\57\x3e\xd\12\x9\11\x3c\151\156\160\165\x74\40\x74\171\160\x65\75\x22\150\151\144\144\x65\156\x22\x20\x6e\141\155\145\75\x22\162\x65\144\x69\162\145\x63\164\137\x74\157\42\x20\x76\x61\x6c\165\x65\75\x22" . $nZ . "\42\40\57\x3e\xd\xa\15\12\11\11\74\x66\157\156\164\40\x73\x69\x7a\x65\75\42\53\x31\x22\x20\x73\164\x79\154\x65\75\x22\x76\x65\x72\x74\x69\x63\141\154\55\x61\154\x69\x67\x6e\x3a\x74\157\x70\x3b\x22\76\x20\74\x2f\x66\157\x6e\x74\x3e";
        $SZ = get_option("\163\x61\x6d\154\x5f\x69\x64\x65\156\164\151\164\171\x5f\x6e\141\x6d\x65");
        if (!empty($SZ)) {
            goto Ii;
        }
        echo "\x50\154\x65\x61\x73\x65\40\x63\157\x6e\146\x69\147\165\162\x65\x20\x74\150\145\x20\155\x69\156\151\117\x72\141\156\147\145\x20\123\101\x4d\x4c\40\x50\x6c\x75\x67\x69\156\x20\x66\x69\162\x73\164\x2e";
        goto ym;
        Ii:
        $FU = "\114\x6f\x67\151\156\40\x77\151\164\x68\40\43\x23\111\104\x50\43\43";
        if (!get_option("\x6d\157\x5f\x73\x61\x6d\x6c\137\143\x75\x73\164\157\x6d\137\154\157\147\x69\x6e\137\164\145\x78\164")) {
            goto Fs;
        }
        $FU = get_option("\x6d\x6f\137\163\x61\x6d\x6c\x5f\x63\165\x73\x74\157\155\x5f\154\x6f\147\x69\156\137\x74\x65\x78\x74");
        Fs:
        $FU = str_replace("\x23\x23\111\104\x50\43\43", $SZ, $FU);
        echo "\x3c\141\x20\x68\162\x65\x66\x3d\x22\x23\x22\x20\x6f\x6e\103\x6c\x69\143\x6b\75\x22\163\165\x62\155\x69\164\x53\x61\155\154\x46\157\x72\155\x28\x29\42\76" . $FU . "\x3c\x2f\x61\76\74\57\x66\157\x72\x6d\76";
        ym:
        if ($this->mo_saml_check_empty_or_null_val(get_option("\155\157\x5f\163\x61\x6d\154\137\x72\145\x64\151\x72\x65\143\x74\137\145\162\162\157\x72\137\x63\157\144\145"))) {
            goto TQ;
        }
        echo "\x3c\144\x69\x76\76\74\57\x64\151\x76\x3e\x3c\144\x69\x76\40\x74\x69\164\x6c\x65\75\42\x4c\x6f\x67\151\156\40\105\x72\162\157\162\x22\76\x3c\146\x6f\x6e\164\x20\x63\x6f\x6c\x6f\162\75\x22\162\x65\144\42\x3e\127\145\x20\x63\x6f\x75\154\144\40\156\x6f\164\x20\163\151\x67\x6e\40\171\x6f\x75\x20\x69\156\x2e\40\120\x6c\145\x61\x73\x65\40\x63\157\156\x74\141\143\164\x20\x79\157\165\162\40\101\144\155\x69\156\151\163\164\x72\141\x74\157\x72\x2e\x3c\57\x66\157\x6e\164\x3e\x3c\57\144\151\x76\x3e";
        delete_option("\155\x6f\x5f\x73\x61\155\x6c\x5f\162\145\144\151\x72\145\x63\x74\x5f\x65\162\x72\x6f\162\x5f\x63\x6f\144\145");
        delete_option("\155\x6f\x5f\x73\x61\x6d\154\137\x72\145\x64\x69\162\x65\x63\x74\x5f\145\x72\162\x6f\x72\137\162\145\x61\x73\157\156");
        TQ:
        echo "\74\x61\40\x68\162\x65\146\x3d\x22\x68\x74\164\160\x3a\x2f\57\155\151\156\151\x6f\162\x61\x6e\147\x65\x2e\143\157\155\x2f\x77\157\x72\x64\x70\x72\x65\x73\x73\55\154\144\x61\160\x2d\154\x6f\x67\x69\156\x22\x20\x73\x74\x79\x6c\145\x3d\42\144\x69\x73\160\154\141\x79\72\x6e\157\156\x65\x22\x3e\114\157\147\151\x6e\40\164\157\40\x57\x6f\162\144\x50\x72\145\163\163\x20\x75\163\x69\x6e\147\40\114\104\x41\x50\74\x2f\141\x3e\xd\xa\x9\x9\74\141\40\150\x72\x65\146\x3d\x22\x68\x74\x74\160\x3a\x2f\x2f\155\151\156\151\x6f\162\141\156\x67\x65\56\x63\157\x6d\x2f\x63\154\x6f\x75\144\x2d\x69\x64\145\x6e\164\151\x74\x79\55\142\162\x6f\153\145\162\x2d\163\x65\x72\166\151\x63\145\42\x20\x73\164\x79\x6c\145\x3d\42\144\x69\x73\160\154\141\171\72\x6e\x6f\x6e\145\42\x3e\103\x6c\157\165\x64\40\111\x64\145\x6e\164\151\x74\171\40\142\162\x6f\153\x65\162\x20\163\x65\162\166\151\143\x65\74\57\x61\76\xd\xa\x9\x9\x3c\141\x20\x68\x72\x65\146\75\42\x68\x74\x74\x70\72\57\57\x6d\x69\x6e\151\x6f\162\x61\156\147\x65\56\143\157\155\x2f\x73\164\x72\x6f\x6e\x67\137\x61\x75\164\x68\x22\40\163\164\x79\154\145\x3d\42\144\x69\163\x70\154\141\171\x3a\x6e\157\156\x65\x3b\x22\x3e\74\57\141\x3e\15\12\x9\x9\74\x61\x20\150\x72\145\146\75\x22\x68\x74\164\x70\72\57\57\155\x69\156\x69\x6f\x72\141\x6e\147\x65\x2e\x63\x6f\155\57\x73\151\156\x67\154\145\55\x73\151\x67\x6e\x2d\157\x6e\55\163\163\x6f\x22\x20\x73\164\171\x6c\145\75\42\x64\x69\x73\x70\x6c\141\171\72\156\x6f\x6e\145\x3b\x22\x3e\x3c\x2f\141\76\xd\12\x9\x9\x3c\x61\40\150\x72\x65\146\75\x22\x68\x74\x74\160\72\57\57\x6d\151\x6e\151\157\x72\141\156\x67\145\x2e\143\x6f\x6d\57\x66\x72\x61\x75\144\x22\x20\163\x74\171\154\x65\75\x22\144\151\x73\x70\x6c\141\171\72\x6e\157\x6e\145\73\42\x3e\x3c\x2f\141\x3e\15\12\xd\xa\11\11\x9\x3c\x2f\165\x6c\76\15\xa\x9\11\74\57\146\x6f\x72\x6d\x3e";
        u8:
    }
    public function mo_saml_check_empty_or_null_val($tq)
    {
        if (!(!isset($tq) || empty($tq))) {
            goto GU;
        }
        return true;
        GU:
        return false;
    }
}
function mo_login_validate()
{
    if (!(isset($_REQUEST["\x6f\160\x74\x69\157\x6e"]) && $_REQUEST["\157\160\164\151\x6f\156"] == "\155\x6f\x73\x61\x6d\154\137\155\145\164\141\x64\x61\x74\x61")) {
        goto pR;
    }
    miniorange_generate_metadata();
    pR:
    if (!(isset($_REQUEST["\x6f\160\164\151\157\x6e"]) && $_REQUEST["\x6f\x70\164\151\157\x6e"] == "\145\170\160\157\x72\164\x5f\x63\x6f\156\146\151\x67\165\162\141\164\x69\157\156")) {
        goto u7;
    }
    if (!current_user_can("\x6d\x61\x6e\x61\x67\x65\x5f\157\x70\x74\151\157\156\x73")) {
        goto HP;
    }
    miniorange_import_export(true);
    HP:
    die;
    u7:
    if (!mo_saml_is_customer_license_verified()) {
        goto cT;
    }
    if (!(isset($_REQUEST["\x6f\x70\164\151\x6f\156"]) && $_REQUEST["\157\x70\164\x69\x6f\156"] == "\x73\141\155\154\137\x75\163\145\x72\x5f\x6c\157\147\x69\x6e" || isset($_REQUEST["\157\160\164\151\x6f\156"]) && $_REQUEST["\x6f\x70\x74\x69\157\156"] == "\164\x65\x73\x74\151\144\x70\143\x6f\x6e\146\151\147" || isset($_REQUEST["\157\x70\164\151\157\156"]) && $_REQUEST["\157\x70\164\x69\x6f\156"] == "\147\x65\164\x73\x61\155\x6c\x72\145\161\x75\145\163\x74" || isset($_REQUEST["\x6f\160\164\x69\x6f\156"]) && $_REQUEST["\x6f\x70\x74\x69\157\x6e"] == "\147\x65\x74\163\141\x6d\x6c\162\x65\163\x70\x6f\156\163\x65")) {
        goto m1;
    }
    if (!mo_saml_is_sp_configured()) {
        goto q4;
    }
    if (!(is_user_logged_in() && $_REQUEST["\157\160\x74\x69\157\156"] == "\163\x61\x6d\154\137\x75\x73\145\162\x5f\x6c\x6f\147\151\x6e")) {
        goto dM;
    }
    if (!isset($_REQUEST["\x72\x65\144\x69\x72\145\x63\164\137\164\x6f"])) {
        goto Y1;
    }
    $ov = htmlspecialchars($_REQUEST["\162\x65\x64\151\162\145\143\x74\137\164\x6f"]);
    header("\x4c\157\143\x61\x74\x69\157\156\x3a\x20" . $ov);
    die;
    Y1:
    return;
    dM:
    $f4 = get_option("\155\157\x5f\x73\x61\155\x6c\137\x73\160\137\x62\141\163\x65\137\165\x72\154");
    if (!empty($f4)) {
        goto Yk;
    }
    $f4 = home_url();
    Yk:
    if ($_REQUEST["\157\160\164\151\157\156"] == "\x74\x65\x73\x74\151\x64\160\x63\157\156\x66\151\x67" and array_key_exists("\x6e\x65\167\143\x65\x72\164", $_REQUEST)) {
        goto fy;
    }
    if ($_REQUEST["\x6f\160\164\x69\x6f\156"] == "\x74\x65\163\164\151\144\160\143\157\x6e\x66\x69\x67") {
        goto ih;
    }
    if ($_REQUEST["\x6f\x70\x74\151\x6f\156"] == "\147\145\164\163\141\x6d\x6c\x72\x65\x71\165\145\x73\x74") {
        goto rp;
    }
    if ($_REQUEST["\x6f\160\x74\151\x6f\x6e"] == "\x67\145\x74\163\x61\155\x6c\162\x65\163\160\157\x6e\163\145") {
        goto WP;
    }
    if (get_option("\x6d\x6f\137\x73\x61\x6d\x6c\x5f\162\145\154\x61\x79\137\163\x74\141\x74\x65") && get_option("\155\x6f\137\x73\x61\155\x6c\137\162\x65\154\x61\171\x5f\163\x74\141\164\x65") != '') {
        goto nH;
    }
    if (isset($_REQUEST["\x72\145\x64\151\x72\145\x63\164\137\164\157"])) {
        goto FW;
    }
    $QS = wp_get_referer();
    goto DF;
    FW:
    $QS = htmlspecialchars($_REQUEST["\162\x65\x64\151\x72\x65\x63\164\x5f\x74\x6f"]);
    DF:
    goto hg;
    nH:
    $QS = get_option("\155\x6f\137\163\141\155\154\x5f\162\x65\x6c\x61\171\x5f\163\x74\x61\x74\x65");
    hg:
    goto Up;
    WP:
    $QS = "\144\x69\x73\160\x6c\x61\x79\123\101\115\114\x52\x65\163\160\157\156\x73\x65";
    Up:
    goto Yz;
    rp:
    $QS = "\144\151\x73\x70\x6c\x61\x79\x53\x41\x4d\x4c\122\x65\x71\165\145\x73\x74";
    Yz:
    goto Ul;
    ih:
    $QS = "\164\145\x73\164\126\x61\154\151\144\x61\x74\145";
    Ul:
    goto FN1;
    fy:
    $QS = "\164\x65\x73\x74\x4e\x65\x77\x43\x65\x72\164\x69\146\151\x63\x61\x74\x65";
    FN1:
    if (!empty($QS)) {
        goto Di;
    }
    $QS = $f4;
    Di:
    $km = htmlspecialchars_decode(get_option("\163\141\155\x6c\137\154\157\147\151\156\137\165\162\154"));
    $ON = get_option("\163\x61\x6d\x6c\x5f\154\157\x67\151\156\x5f\x62\x69\156\x64\151\x6e\147\137\x74\171\x70\x65");
    $Mc = get_option("\x6d\157\137\163\x61\x6d\154\137\146\x6f\162\143\145\137\x61\x75\164\150\145\156\164\151\x63\141\164\x69\x6f\x6e");
    $BW = $f4 . "\57";
    $fK = get_option("\x6d\157\x5f\163\141\155\154\x5f\x73\160\137\145\x6e\164\151\x74\171\x5f\151\144");
    if (!empty($fK)) {
        goto rT;
    }
    $fK = $f4 . "\57\x77\160\55\x63\157\156\x74\x65\156\x74\x2f\160\x6c\165\x67\x69\x6e\x73\x2f\x6d\x69\x6e\x69\x6f\162\141\x6e\147\145\x2d\163\x61\155\154\55\x32\60\x2d\163\151\x6e\147\x6c\x65\55\x73\x69\147\156\55\157\156\x2f";
    rT:
    $gn = get_option("\x73\141\x6d\x6c\x5f\x6e\141\x6d\145\x69\x64\137\x66\x6f\x72\x6d\141\164");
    if (!empty($gn)) {
        goto Vi;
    }
    $gn = "\61\56\61\72\156\x61\155\145\151\144\55\146\x6f\x72\x6d\x61\x74\72\x65\155\x61\151\x6c\x41\144\x64\162\145\163\x73";
    Vi:
    $bQ = SAMLSPUtilities::createAuthnRequest($BW, $fK, $km, $Mc, $ON, $gn);
    if (!($QS == "\144\151\163\160\154\141\x79\x53\101\x4d\114\x52\145\x71\165\145\x73\x74")) {
        goto jg;
    }
    mo_saml_show_SAML_log(SAMLSPUtilities::createAuthnRequest($BW, $fK, $km, $Mc, "\x48\124\124\x50\x50\157\163\164", $gn), $QS);
    jg:
    $HR = $km;
    if (strpos($km, "\x3f") !== false) {
        goto t3;
    }
    $HR .= "\77";
    goto Bm;
    t3:
    $HR .= "\x26";
    Bm:
    cldjkasjdksalc();
    $QS = mo_saml_get_relay_state($QS);
    $QS = empty($QS) ? "\57" : $QS;
    if (empty($ON) || $ON == "\110\164\x74\x70\x52\x65\x64\x69\x72\145\143\x74") {
        goto pL;
    }
    if (!(empty(get_option("\x73\x61\x6d\154\137\162\x65\161\x75\x65\163\164\137\163\151\x67\x6e\145\144")) || get_option("\163\x61\155\154\137\162\145\x71\x75\145\163\x74\137\163\151\x67\156\x65\144") == "\x75\156\143\x68\x65\143\x6b\145\144")) {
        goto kj;
    }
    $G6 = base64_encode($bQ);
    SAMLSPUtilities::postSAMLRequest($km, $G6, $QS);
    die;
    kj:
    $Jr = '';
    $rp = '';
    if ($_REQUEST["\157\160\164\151\157\156"] == "\164\x65\163\164\151\144\160\143\157\x6e\x66\x69\x67" && array_key_exists("\156\x65\x77\x63\145\162\164", $_REQUEST)) {
        goto OB;
    }
    $G6 = SAMLSPUtilities::signXML($bQ, "\x4e\x61\155\145\111\x44\120\x6f\154\x69\143\171");
    goto l6;
    OB:
    $G6 = SAMLSPUtilities::signXML($bQ, "\x4e\141\155\145\111\104\120\x6f\154\x69\x63\x79", true);
    l6:
    SAMLSPUtilities::postSAMLRequest($km, $G6, $QS);
    update_option("\155\x6f\137\x73\141\155\x6c\x5f\156\x65\x77\137\x63\145\162\x74\137\x74\145\163\x74", true);
    goto hj;
    pL:
    if (!(empty(get_option("\x73\x61\155\x6c\x5f\x72\145\161\x75\145\x73\164\137\x73\151\x67\x6e\x65\x64")) || get_option("\x73\x61\x6d\x6c\137\162\145\161\165\145\163\164\137\163\151\x67\x6e\145\144") == "\x75\156\x63\150\x65\143\x6b\145\144")) {
        goto OI;
    }
    $HR .= "\x53\101\115\x4c\122\x65\x71\x75\145\x73\164\x3d" . $bQ . "\x26\x52\145\x6c\x61\171\123\x74\x61\164\x65\75" . urlencode($QS);
    header("\114\157\x63\141\164\151\157\156\x3a\40" . $HR);
    die;
    OI:
    $bQ = "\x53\x41\x4d\x4c\x52\145\x71\165\x65\x73\x74\75" . $bQ . "\46\x52\145\x6c\141\x79\123\x74\141\164\x65\75" . urlencode($QS) . "\46\123\x69\147\101\154\147\75" . urlencode(XMLSecurityKey::RSA_SHA256);
    $ks = array("\x74\x79\160\145" => "\x70\162\x69\166\x61\x74\x65");
    $Xr = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, $ks);
    if ($_REQUEST["\x6f\x70\x74\x69\x6f\x6e"] == "\164\145\163\x74\151\x64\160\x63\x6f\x6e\146\x69\x67" && array_key_exists("\156\x65\x77\143\x65\162\164", $_REQUEST)) {
        goto ux;
    }
    $f3 = get_option("\155\157\137\163\x61\x6d\154\x5f\143\165\x72\162\x65\x6e\164\137\x63\145\x72\164\x5f\x70\162\151\166\141\164\145\x5f\x6b\x65\x79");
    goto Ut;
    ux:
    $f3 = file_get_contents(plugin_dir_path(__FILE__) . "\162\x65\163\x6f\165\162\x63\145\x73" . DIRECTORY_SEPARATOR . "\x6d\x69\156\151\157\162\x61\x6e\x67\x65\x5f\163\x70\x5f\x32\x30\62\x30\137\160\162\151\166\x2e\x6b\145\x79");
    Ut:
    $Xr->loadKey($f3, FALSE);
    $Vl = new XMLSecurityDSig();
    $QR = $Xr->signData($bQ);
    $QR = base64_encode($QR);
    $HR .= $bQ . "\x26\123\x69\147\x6e\x61\164\x75\x72\145\x3d" . urlencode($QR);
    header("\114\157\143\141\x74\x69\157\x6e\72\40" . $HR);
    die;
    hj:
    q4:
    m1:
    if (!(array_key_exists("\123\101\115\x4c\122\x65\163\160\157\x6e\x73\145", $_REQUEST) && !empty($_REQUEST["\x53\x41\115\114\x52\x65\163\160\x6f\156\163\145"]))) {
        goto ga;
    }
    if (array_key_exists("\122\145\154\x61\x79\x53\164\141\x74\145", $_POST) && !empty($_POST["\122\x65\x6c\x61\171\123\164\141\x74\145"]) && $_POST["\122\145\x6c\x61\x79\123\164\x61\164\145"] != "\x2f") {
        goto l_;
    }
    $od = '';
    goto n7;
    l_:
    $od = htmlspecialchars_decode($_POST["\x52\145\154\x61\x79\123\164\x61\x74\x65"]);
    n7:
    $f4 = get_option("\x6d\x6f\137\163\141\x6d\154\137\x73\160\137\x62\x61\x73\x65\137\x75\162\154");
    if (!empty($f4)) {
        goto V1;
    }
    $f4 = home_url();
    V1:
    $p4 = htmlspecialchars($_REQUEST["\123\101\x4d\114\x52\x65\163\x70\x6f\156\163\x65"]);
    $p4 = base64_decode($p4);
    if (!($od == "\144\x69\163\160\x6c\x61\171\123\x41\x4d\114\122\145\x73\160\157\x6e\163\x65")) {
        goto MD;
    }
    mo_saml_show_SAML_log($p4, $od);
    MD:
    if (!(array_key_exists("\x53\101\115\114\122\145\x73\160\157\x6e\163\x65", $_GET) && !empty($_GET["\123\101\115\114\122\145\163\x70\157\x6e\x73\145"]))) {
        goto ph;
    }
    $p4 = gzinflate($p4);
    ph:
    $Gx = new DOMDocument();
    $Gx->loadXML($p4);
    $kY = $Gx->firstChild;
    $vH = $Gx->documentElement;
    $hd = new DOMXpath($Gx);
    $hd->registerNamespace("\x73\x61\x6d\x6c\160", "\165\162\x6e\x3a\157\x61\163\x69\163\x3a\156\x61\x6d\x65\x73\x3a\164\x63\x3a\x53\101\x4d\x4c\72\x32\x2e\x30\72\160\162\157\164\x6f\x63\157\x6c");
    $hd->registerNamespace("\163\141\x6d\x6c", "\165\162\x6e\x3a\x6f\x61\163\151\x73\x3a\x6e\141\x6d\145\x73\72\x74\x63\x3a\123\101\x4d\x4c\x3a\x32\x2e\60\x3a\141\163\x73\145\162\164\x69\x6f\156");
    if ($kY->localName == "\114\157\147\157\165\x74\x52\145\x73\x70\x6f\156\163\x65") {
        goto k7;
    }
    $Wu = $hd->query("\57\163\x61\155\x6c\160\x3a\x52\145\x73\x70\157\x6e\163\145\x2f\x73\x61\x6d\x6c\160\72\123\x74\141\x74\x75\163\x2f\x73\x61\155\x6c\160\x3a\x53\164\141\164\165\163\103\157\x64\x65", $vH);
    $Cq = $Wu->item(0)->getAttribute("\x56\141\x6c\x75\145");
    $Um = $hd->query("\x2f\163\141\x6d\154\x70\x3a\x52\x65\163\x70\x6f\156\163\145\x2f\163\141\x6d\154\160\72\123\164\141\164\x75\163\57\x73\x61\x6d\154\x70\72\x53\x74\x61\164\165\x73\115\145\163\x73\141\147\x65", $vH)->item(0);
    if (empty($Um)) {
        goto ue;
    }
    $Um = $Um->nodeValue;
    ue:
    $ZG = explode("\x3a", $Cq);
    $Wu = $ZG[7];
    if (!($Wu != "\123\165\x63\x63\x65\x73\163")) {
        goto jQ;
    }
    show_status_error($Wu, $od, $Um);
    jQ:
    $kb = maybe_unserialize(get_option("\163\141\155\x6c\137\x78\x35\60\x39\137\143\145\162\164\x69\146\x69\143\x61\164\x65"));
    $BW = $f4 . "\x2f";
    update_option("\x4d\117\137\123\101\115\x4c\137\122\105\x53\120\x4f\x4e\123\x45", base64_encode($p4));
    if ($od == "\164\145\163\x74\116\145\x77\x43\145\x72\164\151\x66\151\x63\x61\164\145") {
        goto wc;
    }
    $p4 = new SAML2SPResponse($kY, get_option("\x6d\x6f\137\x73\141\x6d\154\x5f\143\x75\x72\x72\x65\x6e\x74\x5f\x63\x65\x72\x74\137\x70\162\151\x76\x61\164\145\137\153\x65\x79"));
    goto qc;
    wc:
    $l4 = file_get_contents(plugin_dir_path(__FILE__) . "\x72\x65\163\157\x75\162\143\x65\x73" . DIRECTORY_SEPARATOR . "\x6d\151\156\151\x6f\162\141\x6e\x67\x65\137\x73\x70\137\62\60\x32\60\x5f\x70\x72\151\166\56\153\145\x79");
    $p4 = new SAML2SPResponse($kY, $l4);
    qc:
    $dO = $p4->getSignatureData();
    $TW = current($p4->getAssertions())->getSignatureData();
    if (!(empty($TW) && empty($dO))) {
        goto L0;
    }
    if ($od == "\164\145\163\164\x56\141\154\151\144\x61\164\145" or $od == "\164\145\x73\x74\x4e\145\167\103\145\162\164\x69\146\x69\143\141\164\145") {
        goto JG;
    }
    wp_die("\x57\145\40\x63\x6f\x75\x6c\x64\40\156\x6f\164\x20\x73\x69\147\156\40\x79\157\165\x20\151\x6e\56\x20\120\x6c\x65\141\163\145\40\x63\157\156\164\x61\143\x74\40\x61\x64\155\x69\x6e\x69\x73\164\162\x61\x74\x6f\162", "\x45\162\162\x6f\x72\72\40\x49\156\166\141\154\x69\144\40\x53\101\x4d\x4c\40\x52\x65\163\160\x6f\x6e\163\145");
    goto RM;
    JG:
    $fA = mo_options_error_constants::Error_no_certificate;
    $CS = mo_options_error_constants::Cause_no_certificate;
    echo "\x3c\144\151\166\40\163\164\x79\x6c\145\75\42\146\157\x6e\164\55\146\x61\155\x69\154\x79\x3a\x43\141\154\x69\142\x72\x69\x3b\160\x61\x64\144\151\x6e\147\x3a\60\x20\63\45\73\42\x3e\xd\12\11\11\x9\11\11\74\x64\151\x76\x20\163\164\x79\154\145\x3d\x22\x63\157\x6c\x6f\162\x3a\40\43\x61\71\x34\64\64\x32\x3b\142\141\x63\x6b\147\162\157\165\x6e\144\x2d\143\x6f\154\x6f\162\x3a\40\x23\x66\x32\x64\145\144\145\x3b\x70\141\144\144\x69\x6e\147\x3a\40\x31\65\160\x78\73\x6d\141\162\x67\151\x6e\55\142\157\164\x74\157\155\72\40\x32\60\x70\170\73\x74\145\170\x74\55\x61\154\x69\x67\x6e\x3a\143\x65\x6e\x74\145\x72\73\142\157\x72\x64\145\x72\x3a\61\160\x78\40\163\x6f\154\151\144\x20\x23\105\x36\102\63\102\x32\73\146\x6f\x6e\164\x2d\163\151\172\145\72\x31\70\x70\164\x3b\x22\x3e\x20\105\122\x52\x4f\122\x3c\57\144\x69\x76\x3e\15\12\x9\11\x9\x9\11\74\144\151\x76\40\x73\164\x79\154\145\75\42\143\157\154\x6f\x72\72\40\x23\141\71\x34\64\64\x32\73\x66\157\x6e\164\55\163\x69\172\145\x3a\x31\x34\160\164\x3b\x20\x6d\x61\162\147\151\156\x2d\142\157\x74\164\157\155\72\62\x30\x70\170\73\x22\x3e\74\160\x3e\74\x73\x74\x72\x6f\156\147\76\105\162\162\157\162\40\40\72" . $fA . "\40\x3c\57\x73\164\x72\x6f\x6e\147\x3e\74\57\160\76\xd\12\x9\x9\x9\11\x9\xd\12\11\11\x9\x9\11\74\160\76\x3c\163\x74\162\x6f\156\147\x3e\x50\x6f\x73\x73\x69\142\154\x65\40\x43\141\165\x73\x65\x3a\40" . $CS . "\x3c\57\x73\164\x72\157\x6e\x67\76\74\57\x70\76\15\xa\x9\x9\11\x9\x9\xd\xa\11\11\11\11\x9\74\x2f\144\x69\x76\x3e\74\57\144\151\166\x3e";
    mo_saml_download_logs($fA, $CS);
    die;
    RM:
    L0:
    $mT = '';
    if (is_array($kb)) {
        goto cQ;
    }
    $Th = XMLSecurityKey::getRawThumbprint($kb);
    $Th = mo_saml_convert_to_windows_iconv($Th);
    $Th = preg_replace("\x2f\x5c\x73\53\57", '', $Th);
    if (empty($dO)) {
        goto GZ;
    }
    $mT = SAMLSPUtilities::processResponse($BW, $Th, $dO, $p4, 0, $od);
    GZ:
    if (empty($TW)) {
        goto Ih;
    }
    $mT = SAMLSPUtilities::processResponse($BW, $Th, $TW, $p4, 0, $od);
    Ih:
    goto yc;
    cQ:
    foreach ($kb as $Xr => $tq) {
        $Th = XMLSecurityKey::getRawThumbprint($tq);
        $Th = mo_saml_convert_to_windows_iconv($Th);
        $Th = preg_replace("\57\x5c\x73\53\57", '', $Th);
        if (empty($dO)) {
            goto Hn;
        }
        $mT = SAMLSPUtilities::processResponse($BW, $Th, $dO, $p4, $Xr, $od);
        Hn:
        if (empty($TW)) {
            goto ay;
        }
        $mT = SAMLSPUtilities::processResponse($BW, $Th, $TW, $p4, $Xr, $od);
        ay:
        if (!$mT) {
            goto Mo;
        }
        goto mp;
        Mo:
        rf:
    }
    mp:
    yc:
    if ($dO) {
        goto Zp;
    }
    if ($TW) {
        goto EJ;
    }
    goto fC;
    Zp:
    $j8 = $dO["\x43\x65\x72\164\x69\146\151\143\x61\x74\145\163"][0];
    goto fC;
    EJ:
    $j8 = $TW["\103\x65\x72\164\151\146\x69\x63\141\x74\x65\x73"][0];
    fC:
    if ($mT) {
        goto UJ;
    }
    if ($od == "\x74\x65\x73\164\x56\141\x6c\x69\x64\x61\x74\145" or $od == "\164\x65\163\164\x4e\x65\x77\103\x65\x72\164\151\x66\151\143\x61\164\x65") {
        goto gA;
    }
    wp_die("\127\145\x20\143\x6f\165\x6c\x64\x20\x6e\x6f\x74\40\x73\151\147\x6e\x20\171\157\165\40\151\156\x2e\x20\120\x6c\x65\141\x73\x65\40\x63\x6f\156\164\141\x63\164\40\141\x64\x6d\151\x6e\x69\x73\x74\162\141\164\x6f\x72", "\x45\162\162\157\x72\72\40\111\x6e\166\141\x6c\151\144\40\x53\x41\x4d\114\40\122\145\x73\160\x6f\156\163\x65");
    goto EA;
    gA:
    $fA = mo_options_error_constants::Error_wrong_certificate;
    $CS = mo_options_error_constants::Cause_wrong_certificate;
    $yS = "\x2d\55\x2d\55\55\x42\x45\x47\x49\116\40\103\x45\x52\124\111\106\x49\x43\101\x54\x45\55\x2d\55\x2d\x2d\x3c\142\x72\76" . chunk_split($j8, 64) . "\x3c\142\162\x3e\x2d\x2d\x2d\55\55\x45\x4e\104\40\x43\x45\x52\x54\x49\x46\111\103\x41\124\105\x2d\x2d\55\55\55";
    echo "\x3c\x64\151\x76\40\x73\164\x79\154\145\75\x22\146\157\156\x74\x2d\x66\141\155\151\x6c\x79\x3a\103\141\x6c\x69\x62\162\151\73\160\x61\144\x64\x69\x6e\147\72\60\40\63\45\x3b\x22\76";
    echo "\74\144\x69\166\x20\163\x74\171\x6c\x65\x3d\42\143\157\154\x6f\x72\72\x20\43\141\x39\64\x34\x34\x32\x3b\x62\x61\143\x6b\147\x72\x6f\165\156\144\55\x63\x6f\x6c\157\x72\x3a\x20\x23\146\62\144\145\x64\145\x3b\160\141\144\144\x69\156\147\72\40\61\65\160\x78\73\155\x61\x72\x67\x69\156\x2d\x62\x6f\164\164\x6f\155\72\40\x32\x30\160\170\73\164\145\170\164\55\x61\x6c\x69\x67\x6e\x3a\143\x65\156\164\x65\x72\x3b\142\157\162\144\145\162\x3a\x31\x70\x78\40\x73\157\154\151\x64\40\43\105\x36\102\63\102\62\73\x66\x6f\156\x74\x2d\163\x69\x7a\145\72\61\x38\x70\164\x3b\x22\76\x20\105\x52\122\117\122\x3c\57\144\151\x76\76\15\xa\11\x9\x9\11\x9\x9\x3c\x64\151\x76\x20\x73\x74\171\154\145\75\x22\143\x6f\154\157\162\72\40\x23\x61\71\x34\x34\x34\62\x3b\146\x6f\156\164\x2d\163\x69\x7a\x65\x3a\61\x34\160\x74\73\x20\x6d\x61\x72\x67\151\156\x2d\x62\x6f\164\164\157\x6d\72\x32\x30\x70\x78\x3b\x22\76\74\x70\76\x3c\163\x74\162\157\x6e\x67\x3e\x45\x72\162\x6f\162\x3a" . $fA . "\40\x3c\x2f\x73\x74\162\x6f\x6e\x67\x3e\74\x2f\160\x3e\xd\xa\11\x9\x9\x9\x9\x9\xd\12\x9\x9\11\x9\x9\x9\x3c\160\76\x3c\163\x74\x72\157\x6e\147\x3e\x50\x6f\x73\163\151\x62\x6c\x65\x20\x43\141\x75\163\x65\72\40" . $CS . "\74\57\163\164\x72\157\156\x67\76\74\57\160\76\xd\12\x9\11\x9\11\x9\x9\x3c\160\x3e\x3c\x73\x74\162\157\x6e\147\x3e\x43\x65\x72\x74\x69\x66\151\143\141\x74\145\x20\146\x6f\165\156\x64\40\x69\156\x20\x53\x41\115\x4c\40\122\145\163\160\157\x6e\x73\145\72\x20\x3c\57\163\x74\162\157\156\147\x3e\x3c\x66\x6f\156\x74\x20\146\x61\x63\x65\75\x22\103\x6f\165\x72\x69\x65\162\40\x4e\145\x77\x22\73\146\157\x6e\x74\55\163\x69\172\x65\x3a\61\x30\160\164\x3e\74\142\162\x3e\x3c\142\162\x3e" . $yS . "\74\x2f\160\x3e\74\57\146\x6f\x6e\x74\76\xd\xa\11\11\11\x9\x9\x9\x3c\x70\76\x3c\163\x74\162\x6f\x6e\147\76\123\x6f\154\165\x74\x69\157\156\72\40\x3c\57\163\x74\162\x6f\x6e\x67\x3e\x3c\57\x70\76\xd\12\11\x9\11\x9\x9\x9\x3c\157\154\76\15\12\11\11\x9\11\x9\x9\11\x3c\x6c\151\76\x43\x6f\x70\171\x20\160\141\163\x74\145\x20\164\x68\145\x20\x63\x65\162\x74\x69\x66\x69\x63\141\164\x65\x20\x70\x72\x6f\x76\151\144\145\x64\40\141\x62\x6f\x76\145\x20\x69\x6e\x20\x58\x35\60\71\40\x43\x65\162\x74\151\x66\x69\143\x61\x74\145\x20\x75\156\x64\x65\x72\x20\x53\x65\x72\x76\x69\143\145\40\120\162\157\x76\151\144\145\162\x20\x53\145\x74\165\160\x20\x74\x61\142\56\x3c\57\x6c\151\76\15\12\x9\11\11\x9\11\x9\11\x3c\x6c\151\76\x49\146\x20\x69\x73\x73\x75\x65\40\x70\x65\x72\x73\151\x73\164\x73\x20\144\151\x73\x61\x62\154\145\x20\x3c\x62\76\x43\x68\141\x72\x61\x63\164\x65\x72\40\145\156\x63\x6f\x64\151\156\147\74\x2f\x62\x3e\40\165\156\x64\145\x72\40\x53\145\162\x76\x69\x63\145\40\x50\x72\157\x76\144\145\x72\40\123\x65\x74\165\160\x20\x74\141\x62\x2e\x3c\x2f\x6c\151\x3e\15\12\x9\11\x9\x9\11\x9\74\57\157\154\x3e\x20\11\x9\15\xa\x9\11\11\11\x9\11\x3c\x2f\144\x69\166\76\15\12\11\x9\x9\11\11\x9\x9\x3c\57\x64\x69\x76\76";
    mo_saml_download_logs($fA, $CS);
    die;
    EA:
    UJ:
    $VB = get_option("\163\141\155\x6c\x5f\151\163\163\165\x65\x72");
    $fK = get_option("\x6d\157\x5f\x73\x61\x6d\154\137\x73\160\x5f\x65\156\164\x69\164\x79\137\151\144");
    if (!empty($fK)) {
        goto Df;
    }
    $fK = $f4 . "\57\167\160\x2d\143\x6f\156\x74\145\156\x74\57\160\154\x75\x67\x69\x6e\x73\x2f\x6d\x69\156\x69\157\162\141\x6e\147\x65\55\163\x61\155\x6c\x2d\62\x30\55\163\151\x6e\x67\x6c\145\55\163\x69\x67\x6e\x2d\157\156\x2f";
    Df:
    SAMLSPUtilities::validateIssuerAndAudience($p4, $fK, $VB, $od);
    $i1 = current($p4->getAssertions())->getNotOnOrAfter();
    $qV = current($p4->getAssertions())->getId();
    if ($i1 !== NULL) {
        goto VR;
    }
    $NO = 15 * MINUTE_IN_SECONDS;
    goto NQ;
    VR:
    $NO = $i1 - time() + 300;
    NQ:
    $g2 = get_transient($qV);
    if (false === $g2) {
        goto U9;
    }
    wp_die("\x57\145\x20\x63\157\165\x6c\x64\x20\x6e\157\164\x20\x73\151\x67\x6e\40\171\157\x75\x20\x69\x6e\x2e\x20\x50\x6c\x65\141\x73\145\x20\143\x6f\x6e\x74\x61\143\164\x20\171\157\165\162\x20\163\x69\164\145\x20\x61\144\155\x69\156\x69\163\164\162\141\164\x6f\162\56", "\105\x72\162\x6f\x72\72\x20\104\x75\160\x6c\x69\x63\141\x74\x65\x20\123\x41\x4d\114\x20\122\x65\x73\160\157\x6e\x73\145");
    goto T6;
    U9:
    set_transient($qV, "\x65\170\x69\163\x74\x65\x64", $NO);
    T6:
    $Fs = current(current($p4->getAssertions())->getNameId());
    $Vj = current($p4->getAssertions())->getAttributes();
    $Vj["\116\141\x6d\x65\111\104"] = array("\x30" => $Fs);
    $PO = current($p4->getAssertions())->getSessionIndex();
    mo_saml_checkMapping($Vj, $od, $PO);
    goto SB;
    k7:
    header("\x4c\x6f\143\141\164\x69\157\156\72\40" . home_url());
    die;
    SB:
    if (!(array_key_exists("\123\x41\115\114\x52\145\x71\165\x65\x73\x74", $_REQUEST) && !empty($_REQUEST["\123\101\115\114\122\145\161\x75\145\x73\164"]))) {
        goto Nh;
    }
    $bQ = htmlspecialchars($_REQUEST["\123\x41\x4d\114\x52\145\161\165\145\x73\x74"]);
    $od = "\57";
    if (!array_key_exists("\x52\145\154\141\171\x53\164\141\164\x65", $_REQUEST)) {
        goto MY;
    }
    $od = htmlspecialchars($_REQUEST["\x52\145\x6c\x61\171\123\164\141\x74\145"]);
    MY:
    $bQ = base64_decode($bQ);
    if (!(array_key_exists("\x53\x41\115\x4c\x52\145\161\165\145\163\x74", $_GET) && !empty($_GET["\x53\x41\115\114\x52\x65\x71\165\145\x73\x74"]))) {
        goto XX;
    }
    $bQ = gzinflate($bQ);
    XX:
    $Gx = new DOMDocument();
    $Gx->loadXML($bQ);
    $cU = $Gx->firstChild;
    Nh:
    if (!(isset($_REQUEST["\x6f\x70\x74\x69\x6f\156"]) and strpos($_REQUEST["\157\x70\x74\151\x6f\x6e"], "\162\x65\x61\x64\163\141\155\154\x6c\x6f\x67\x69\156") !== false)) {
        goto XU;
    }
    require_once dirname(__FILE__) . "\57\x69\156\x63\x6c\165\144\145\x73\x2f\x6c\x69\142\57\145\x6e\x63\162\171\160\164\x69\157\156\56\x70\150\160";
    if (isset($_POST["\x53\124\101\124\125\x53"]) && $_POST["\123\x54\101\124\x55\x53"] == "\x45\x52\x52\x4f\x52") {
        goto M1;
    }
    if (!(isset($_POST["\123\x54\x41\124\x55\123"]) && $_POST["\x53\124\x41\124\125\x53"] == "\x53\x55\x43\103\105\x53\x53")) {
        goto SW;
    }
    $nZ = '';
    if (!(isset($_REQUEST["\x72\145\x64\x69\x72\145\143\x74\x5f\164\x6f"]) && !empty($_REQUEST["\162\x65\x64\x69\x72\145\143\164\137\164\x6f"]) && $_REQUEST["\x72\145\144\151\162\x65\143\164\x5f\x74\x6f"] != "\x2f")) {
        goto j4;
    }
    $nZ = htmlspecialchars($_REQUEST["\x72\x65\144\x69\x72\145\143\164\x5f\164\157"]);
    j4:
    delete_option("\x6d\x6f\137\163\141\155\154\137\x72\145\x64\151\x72\x65\x63\164\x5f\x65\x72\162\157\162\137\x63\157\144\x65");
    delete_option("\155\x6f\137\x73\141\x6d\154\137\162\x65\x64\x69\x72\x65\x63\164\137\145\162\x72\157\x72\137\162\x65\x61\x73\157\x6e");
    try {
        $fm = get_option("\163\141\x6d\x6c\x5f\141\155\137\145\155\141\x69\x6c");
        $Ks = get_option("\x73\x61\x6d\x6c\x5f\141\x6d\137\x75\x73\145\162\156\x61\x6d\x65");
        $z8 = get_option("\x73\141\x6d\154\x5f\x61\155\x5f\146\x69\162\x73\164\137\x6e\x61\x6d\x65");
        $sB = get_option("\x73\141\155\154\x5f\x61\155\137\154\x61\163\x74\137\156\x61\155\145");
        $Xh = get_option("\163\141\155\x6c\x5f\141\155\137\147\162\157\x75\160\x5f\x6e\x61\x6d\145");
        $aH = get_option("\x73\x61\155\x6c\137\x61\155\x5f\144\145\x66\x61\x75\154\164\x5f\165\163\x65\162\x5f\162\157\x6c\x65");
        $Bq = get_option("\163\141\155\154\137\x61\155\x5f\x64\x6f\x6e\x74\137\x61\154\154\157\167\x5f\165\156\x6c\151\x73\x74\x65\144\x5f\x75\x73\145\x72\137\x72\x6f\154\145");
        $Oh = get_option("\163\x61\x6d\154\x5f\x61\155\137\141\143\143\x6f\x75\156\164\137\155\x61\x74\143\x68\x65\162");
        $t3 = '';
        $y_ = '';
        $z8 = str_replace("\x2e", "\x5f", $z8);
        $z8 = str_replace("\x20", "\137", $z8);
        if (!(!empty($z8) && array_key_exists($z8, $_POST))) {
            goto op;
        }
        $z8 = htmlspecialchars($_POST[$z8]);
        op:
        $sB = str_replace("\x2e", "\137", $sB);
        $sB = str_replace("\40", "\x5f", $sB);
        if (!(!empty($sB) && array_key_exists($sB, $_POST))) {
            goto rq;
        }
        $sB = htmlspecialchars($_POST[$sB]);
        rq:
        $Ks = str_replace("\56", "\137", $Ks);
        $Ks = str_replace("\x20", "\x5f", $Ks);
        if (!empty($Ks) && array_key_exists($Ks, $_POST)) {
            goto i9;
        }
        $y_ = htmlspecialchars($_POST["\116\x61\155\145\x49\104"]);
        goto r0;
        i9:
        $y_ = htmlspecialchars($_POST[$Ks]);
        r0:
        $t3 = str_replace("\56", "\137", $fm);
        $t3 = str_replace("\40", "\x5f", $fm);
        if (!empty($fm) && array_key_exists($fm, $_POST)) {
            goto MQ;
        }
        $t3 = htmlspecialchars($_POST["\116\x61\155\x65\111\x44"]);
        goto iE;
        MQ:
        $t3 = htmlspecialchars($_POST[$fm]);
        iE:
        $Xh = str_replace("\x2e", "\137", $Xh);
        $Xh = str_replace("\x20", "\137", $Xh);
        if (!(!empty($Xh) && array_key_exists($Xh, $_POST))) {
            goto dR;
        }
        $Xh = htmlspecialchars($_POST[$Xh]);
        dR:
        if (!empty($Oh)) {
            goto fi;
        }
        $Oh = "\145\x6d\141\x69\154";
        fi:
        $Xr = get_option("\x6d\157\137\x73\x61\155\154\x5f\x63\x75\163\x74\157\x6d\145\x72\x5f\164\157\x6b\145\x6e");
        if (!(isset($Xr) || trim($Xr) != '')) {
            goto ci;
        }
        $Jg = AESEncryption::decrypt_data($t3, $Xr);
        $t3 = $Jg;
        ci:
        if (!(!empty($z8) && !empty($Xr))) {
            goto m3;
        }
        $r2 = AESEncryption::decrypt_data($z8, $Xr);
        $z8 = $r2;
        m3:
        if (!(!empty($sB) && !empty($Xr))) {
            goto ip;
        }
        $p9 = AESEncryption::decrypt_data($sB, $Xr);
        $sB = $p9;
        ip:
        if (!(!empty($y_) && !empty($Xr))) {
            goto DE;
        }
        $qj = AESEncryption::decrypt_data($y_, $Xr);
        $y_ = $qj;
        DE:
        if (!(!empty($Xh) && !empty($Xr))) {
            goto VF;
        }
        $jx = AESEncryption::decrypt_data($Xh, $Xr);
        $Xh = $jx;
        VF:
    } catch (Exception $HI) {
        echo sprintf("\x41\156\40\x65\162\162\157\162\x20\x6f\143\143\165\x72\x72\x65\x64\40\x77\150\x69\154\x65\x20\x70\162\157\143\145\x73\163\x69\156\x67\x20\x74\150\145\x20\x53\101\x4d\114\x20\x52\145\x73\x70\x6f\x6e\163\x65\x2e");
        die;
    }
    $Ly = array($Xh);
    mo_saml_login_user($t3, $z8, $sB, $y_, $Ly, $Bq, $aH, $nZ, $Oh);
    SW:
    goto DH;
    M1:
    update_option("\155\x6f\x5f\163\141\155\154\137\162\145\x64\151\x72\x65\x63\x74\137\x65\162\162\157\x72\137\x63\157\144\145", htmlspecialchars($_POST["\105\122\x52\117\122\x5f\x52\105\x41\x53\117\116"]));
    update_option("\x6d\x6f\x5f\x73\141\155\x6c\137\x72\x65\x64\x69\x72\x65\143\164\x5f\145\162\x72\157\162\x5f\162\145\141\163\x6f\156", htmlspecialchars($_POST["\105\x52\122\117\122\x5f\x4d\x45\123\123\x41\x47\105"]));
    DH:
    XU:
    ga:
    cT:
}
function cldjkasjdksalc()
{
    $O6 = plugin_dir_path(__FILE__);
    $Hx = wp_upload_dir();
    $Vs = home_url();
    $Vs = trim($Vs, "\57");
    if (preg_match("\x23\x5e\150\x74\x74\x70\50\163\51\x3f\72\57\x2f\x23", $Vs)) {
        goto O2;
    }
    $Vs = "\150\x74\x74\x70\x3a\x2f\x2f" . $Vs;
    O2:
    $NC = parse_url($Vs);
    $N8 = preg_replace("\57\136\x77\167\167\134\x2e\57", '', $NC["\150\x6f\163\x74"]);
    $W_ = $N8 . "\x2d" . $Hx["\142\x61\163\145\x64\151\x72"];
    $dR = hash_hmac("\163\150\141\x32\65\x36", $W_, "\64\104\x48\146\x6a\147\146\152\x61\x73\156\x64\x66\163\141\x6a\146\110\107\x4a");
    if (is_writable($O6 . "\154\x69\143\145\156\163\145")) {
        goto xc;
    }
    $ht = base64_decode("\x62\107\116\x6b\x61\x6d\164\x68\x63\62\160\x6b\141\x33\x4e\150\131\62\167\75");
    $Y2 = get_option($ht);
    if (empty($Y2)) {
        goto ag;
    }
    $S6 = str_rot13($Y2);
    ag:
    goto PT;
    xc:
    $Y2 = file_get_contents($O6 . "\x6c\x69\143\x65\x6e\x73\145");
    if (!$Y2) {
        goto GQ;
    }
    $S6 = base64_encode($Y2);
    GQ:
    PT:
    if (!empty($Y2)) {
        goto u0;
    }
    $qi = base64_decode("\124\x47\154\152\x5a\x57\65\x7a\x5a\123\102\107\x61\x57\x78\154\x49\107\61\160\143\63\x4e\160\142\x6d\143\x67\132\156\x4a\x76\142\x53\102\x30\141\107\x55\147\143\x47\170\61\x5a\62\x6c\165\114\x67\75\75");
    wp_die($qi);
    u0:
    if (strpos($S6, $dR) !== false) {
        goto CS;
    }
    $Di = new Customersaml();
    $Xr = get_option("\155\157\x5f\163\x61\x6d\x6c\137\143\165\163\x74\x6f\155\x65\x72\137\164\x6f\153\x65\x6e");
    $yJ = AESEncryption::decrypt_data(get_option("\163\155\154\137\x6c\x6b"), $Xr);
    $NE = $Di->mo_saml_vl($yJ, false);
    if ($NE) {
        goto Q4;
    }
    return;
    Q4:
    $NE = json_decode($NE, true);
    if (strcasecmp($NE["\163\x74\141\164\165\x73"], "\x53\x55\x43\103\105\x53\123") == 0) {
        goto NS;
    }
    $V2 = base64_decode("\x53\x57\x35\62\131\x57\170\x70\132\103\x42\x4d\x61\x57\x4e\x6c\x62\156\116\x6c\x49\x45\132\166\x64\127\65\x6b\x4c\x69\x42\121\142\107\126\150\143\62\125\x67\x59\62\71\x75\144\x47\x46\x6a\x64\103\x42\65\x62\x33\x56\x79\111\x47\x46\153\x62\127\x6c\x75\141\x58\116\60\143\155\106\60\142\x33\x49\147\x64\107\x38\x67\x64\130\x4e\154\111\x48\x52\157\132\x53\102\x6a\x62\x33\112\171\x5a\x57\x4e\x30\111\x47\x78\160\131\x32\x56\x75\143\x32\x55\x75\x49\105\132\x76\x63\x69\102\164\x62\63\112\154\111\107\122\154\144\107\x46\x70\x62\110\115\x73\111\110\x42\171\142\63\132\x70\x5a\x47\x55\x67\x64\x47\x68\154\111\x46\x4a\154\x5a\155\x56\171\x5a\x57\x35\x6a\132\x53\x42\x4a\122\x44\x6f\x67\124\x55\70\171\116\x44\x49\64\x4d\x54\101\x79\115\124\143\167\x4e\123\102\x30\142\x79\102\65\x62\x33\x56\x79\111\x47\106\x6b\142\127\x6c\165\x61\130\116\60\143\x6d\106\60\142\63\x49\147\x64\x47\70\147\131\62\x68\x6c\131\x32\163\147\x61\130\121\x67\x64\127\65\x6b\132\130\111\147\123\107\x56\x73\x63\x43\x41\x6d\x49\x45\x5a\102\125\x53\x42\60\x59\x57\111\147\141\127\64\147\x64\x47\150\x6c\x49\x48\x42\163\x64\127\144\x70\142\151\x34\75");
    $V2 = str_replace("\x48\x65\154\x70\40\x26\40\106\x41\x51\x20\164\x61\x62\x20\x69\x6e", "\x46\101\x51\163\x20\163\145\143\164\151\157\x6e\40\157\x66", $V2);
    $uU = base64_decode("\x52\130\112\171\142\63\111\66\111\105\154\165\x64\155\x46\163\141\x57\x51\147\124\107\154\x6a\x5a\127\65\172\x5a\x51\x3d\x3d");
    wp_die($V2, $uU);
    goto iZ;
    NS:
    $O6 = plugin_dir_path(__FILE__);
    $Vs = home_url();
    $Vs = trim($Vs, "\x2f");
    if (preg_match("\43\x5e\x68\x74\164\160\50\163\51\77\72\x2f\x2f\x23", $Vs)) {
        goto vn;
    }
    $Vs = "\x68\164\x74\x70\72\57\57" . $Vs;
    vn:
    $NC = parse_url($Vs);
    $N8 = preg_replace("\57\x5e\167\167\x77\x5c\56\x2f", '', $NC["\150\157\163\x74"]);
    $Hx = wp_upload_dir();
    $W_ = $N8 . "\x2d" . $Hx["\x62\x61\x73\x65\x64\x69\162"];
    $dR = hash_hmac("\163\150\x61\62\65\x36", $W_, "\x34\104\110\x66\152\147\x66\152\x61\x73\156\144\x66\x73\x61\x6a\x66\x48\x47\112");
    $zZ = djkasjdksa();
    $Rv = round(strlen($zZ) / rand(2, 20));
    $zZ = substr_replace($zZ, $dR, $Rv, 0);
    $By = base64_decode($zZ);
    if (is_writable($O6 . "\x6c\x69\143\145\156\x73\145")) {
        goto zz;
    }
    $zZ = str_rot13($zZ);
    $ht = base64_decode("\142\x47\116\x6b\141\155\x74\x68\x63\62\160\153\141\63\x4e\x68\131\x32\x77\x3d");
    update_option($ht, $zZ);
    goto ji;
    zz:
    file_put_contents($O6 . "\154\x69\x63\145\156\163\145", $By);
    ji:
    return true;
    iZ:
    goto nT;
    CS:
    return true;
    nT:
}
function djkasjdksa()
{
    $YS = "\41\176\x40\x23\x24\x25\x5e\46\52\50\x29\137\x2b\174\173\175\x3c\x3e\77\60\61\x32\63\x34\x35\66\x37\70\71\141\142\143\x64\x65\146\x67\x68\151\152\153\x6c\x6d\x6e\157\160\161\x72\163\x74\165\x76\x77\170\x79\x7a\101\x42\x43\x44\x45\106\107\110\111\x4a\113\x4c\x4d\116\117\120\121\x52\x53\x54\x55\x56\127\x58\131\132";
    $g0 = strlen($YS);
    $eZ = '';
    $fL = 0;
    c9:
    if (!($fL < 10000)) {
        goto Zw;
    }
    $eZ .= $YS[rand(0, $g0 - 1)];
    SR:
    $fL++;
    goto c9;
    Zw:
    return $eZ;
}
function mo_saml_show_SAML_log($cU, $YP)
{
    header("\103\x6f\x6e\x74\x65\x6e\x74\55\x54\171\160\x65\72\40\x74\145\x78\164\57\150\x74\155\x6c");
    $vH = new DOMDocument();
    $vH->preserveWhiteSpace = false;
    $vH->formatOutput = true;
    $vH->loadXML($cU);
    if ($YP == "\x64\151\x73\x70\154\141\x79\123\x41\115\x4c\x52\x65\161\165\145\163\164") {
        goto lT;
    }
    $wV = "\123\101\115\114\40\x52\145\163\x70\157\x6e\163\145";
    goto fw;
    lT:
    $wV = "\x53\x41\x4d\114\40\122\145\161\x75\x65\x73\164";
    fw:
    $fY = $vH->saveXML();
    $cC = htmlentities($fY);
    $cC = rtrim($cC);
    $pb = simplexml_load_string($fY);
    $OB = json_encode($pb);
    $im = json_decode($OB);
    $pZ = plugins_url("\x69\x6e\143\x6c\165\144\x65\163\x2f\143\163\163\x2f\x73\164\171\154\x65\137\x73\x65\164\x74\151\156\x67\x73\56\143\x73\163\x3f\x76\x65\162\x3d\64\x2e\x38\56\64\60", __FILE__);
    echo "\x3c\x6c\x69\x6e\x6b\x20\162\x65\154\x3d\47\x73\x74\171\154\x65\163\150\x65\145\164\x27\x20\151\x64\75\x27\x6d\157\137\163\x61\155\x6c\x5f\x61\144\x6d\x69\156\137\163\145\164\164\x69\156\x67\163\137\163\164\x79\x6c\145\55\143\163\x73\47\40\x20\x68\162\145\146\x3d\x27" . $pZ . "\x27\40\x74\171\x70\x65\75\x27\164\x65\x78\x74\x2f\x63\163\x73\x27\x20\155\x65\x64\151\x61\x3d\47\141\x6c\154\x27\40\x2f\76\15\xa\40\x20\x20\x20\40\40\x20\40\x20\40\40\40\xd\12\x9\11\11\x3c\x64\x69\166\x20\143\154\x61\x73\x73\x3d\42\x6d\x6f\x2d\x64\x69\x73\160\x6c\141\171\55\x6c\157\147\163\x22\x20\x3e\74\160\x20\164\171\160\145\x3d\x22\x74\145\170\164\x22\40\40\x20\x69\144\x3d\42\123\101\115\x4c\x5f\x74\171\160\x65\42\76" . $wV . "\x3c\x2f\x70\76\x3c\x2f\144\x69\166\x3e\15\xa\11\x9\x9\11\15\xa\x9\11\x9\x3c\144\x69\x76\x20\x74\x79\160\145\x3d\42\164\145\x78\x74\42\40\151\x64\75\42\x53\101\x4d\114\137\144\x69\163\160\154\x61\171\42\x20\x63\x6c\141\x73\163\75\x22\x6d\x6f\55\x64\x69\x73\160\154\x61\171\55\x62\154\157\143\x6b\x22\76\74\160\x72\x65\40\143\154\x61\x73\x73\75\47\x62\162\165\x73\x68\72\x20\x78\x6d\x6c\73\x27\x3e" . $cC . "\74\x2f\x70\162\x65\x3e\74\x2f\144\x69\x76\x3e\xd\12\x9\11\11\x3c\x62\162\x3e\15\xa\11\x9\x9\x3c\x64\151\x76\x9\x20\x73\164\171\154\x65\75\42\x6d\x61\x72\x67\151\x6e\x3a\x33\x25\x3b\x64\x69\163\x70\154\x61\x79\72\x62\x6c\x6f\x63\x6b\x3b\164\145\x78\x74\55\x61\x6c\x69\147\x6e\x3a\143\x65\156\x74\x65\162\x3b\42\76\xd\xa\40\x20\x20\40\x20\x20\x20\x20\x20\x20\x20\40\15\12\11\x9\x9\x3c\144\x69\x76\40\163\164\171\x6c\x65\x3d\x22\155\141\x72\x67\x69\156\x3a\x33\x25\73\144\151\x73\x70\154\141\x79\x3a\142\154\x6f\x63\153\x3b\164\x65\x78\164\x2d\141\x6c\x69\x67\156\x3a\143\145\156\164\145\x72\73\42\40\x3e\15\12\x9\xd\12\40\x20\40\40\40\x20\x20\x20\x20\x20\x20\40\74\x2f\x64\151\x76\x3e\15\12\x9\11\11\x3c\x62\x75\164\164\x6f\x6e\x20\x69\x64\x3d\42\143\x6f\160\171\42\x20\x6f\x6e\x63\x6c\x69\x63\153\75\x22\143\157\160\x79\x44\151\166\x54\157\x43\154\x69\x70\142\x6f\141\x72\x64\x28\51\42\x20\x20\x73\x74\171\154\145\x3d\42\x70\141\x64\x64\x69\x6e\x67\x3a\x31\45\73\167\x69\x64\164\x68\72\61\60\60\160\x78\73\142\141\x63\153\x67\162\157\165\156\144\x3a\40\43\x30\60\71\61\x43\104\40\156\157\x6e\x65\x20\x72\x65\160\x65\x61\x74\40\x73\143\x72\x6f\x6c\154\x20\x30\45\40\x30\x25\73\143\165\162\x73\157\162\x3a\40\160\x6f\151\x6e\164\x65\x72\x3b\146\x6f\156\164\55\163\151\172\145\72\x31\65\x70\x78\73\142\157\x72\x64\x65\162\x2d\167\151\144\164\x68\x3a\40\61\160\x78\73\x62\x6f\x72\144\x65\162\x2d\x73\x74\x79\x6c\145\x3a\40\x73\157\154\x69\144\73\x62\x6f\162\144\x65\x72\x2d\162\x61\144\x69\165\x73\72\x20\x33\x70\x78\x3b\x77\150\151\164\x65\55\163\160\x61\143\x65\72\x20\x6e\157\x77\x72\141\160\73\x62\x6f\170\x2d\x73\x69\x7a\x69\x6e\x67\72\40\x62\157\x72\144\x65\162\55\x62\157\x78\x3b\142\157\162\144\145\x72\55\x63\x6f\154\157\x72\x3a\x20\x23\x30\60\67\x33\x41\101\x3b\142\x6f\x78\x2d\163\x68\141\x64\157\x77\72\40\x30\x70\170\40\61\160\170\40\60\160\x78\40\x72\x67\142\141\x28\x31\62\60\54\x20\62\60\x30\x2c\40\x32\x33\x30\54\40\60\56\66\x29\40\151\x6e\x73\x65\x74\73\143\x6f\x6c\157\162\x3a\40\x23\x46\x46\106\73\x22\40\x3e\103\157\x70\171\x3c\57\142\x75\x74\x74\x6f\x6e\x3e\15\xa\11\x9\11\x26\156\x62\163\160\73\15\12\x20\x20\40\40\x20\x20\40\40\40\40\40\x20\x20\x20\40\74\151\x6e\160\x75\164\40\151\144\x3d\x22\x64\x77\156\55\x62\164\x6e\x22\x20\x73\164\171\154\145\75\42\160\x61\144\144\x69\156\147\x3a\x31\45\x3b\167\151\x64\x74\x68\72\x31\x30\60\160\x78\x3b\x62\x61\x63\x6b\x67\x72\157\165\156\x64\x3a\x20\x23\60\60\71\61\x43\x44\40\156\x6f\156\145\x20\162\145\160\145\x61\x74\40\x73\143\x72\157\x6c\x6c\40\60\x25\40\x30\45\x3b\143\165\x72\163\x6f\162\72\40\160\x6f\x69\x6e\164\x65\162\x3b\146\x6f\x6e\164\55\163\x69\172\x65\72\61\65\160\x78\73\x62\157\162\144\x65\x72\55\167\x69\x64\164\150\72\40\x31\x70\x78\x3b\142\157\x72\x64\x65\x72\55\163\x74\171\x6c\x65\x3a\x20\163\x6f\x6c\151\x64\73\142\157\x72\144\x65\x72\x2d\x72\141\144\x69\x75\x73\72\40\x33\x70\x78\x3b\x77\x68\x69\164\x65\55\163\160\x61\x63\x65\x3a\x20\156\x6f\167\x72\141\x70\73\142\157\170\x2d\163\151\x7a\x69\x6e\147\x3a\40\142\x6f\x72\x64\x65\162\55\142\x6f\170\x3b\142\157\162\x64\x65\x72\x2d\143\157\154\x6f\x72\72\x20\43\60\60\67\x33\x41\101\x3b\x62\157\x78\55\163\150\141\144\x6f\167\x3a\40\x30\160\170\x20\61\x70\x78\x20\60\160\x78\x20\x72\x67\x62\x61\x28\x31\62\60\x2c\40\62\60\x30\54\x20\x32\x33\x30\x2c\x20\x30\x2e\66\51\x20\151\x6e\x73\x65\x74\x3b\143\x6f\154\157\162\x3a\x20\43\x46\106\106\x3b\x22\164\171\160\145\x3d\42\142\x75\x74\x74\157\156\x22\x20\x76\x61\x6c\165\145\75\x22\104\157\x77\x6e\x6c\x6f\141\x64\x22\40\15\12\x20\40\x20\40\x20\x20\x20\40\40\40\40\x20\x20\40\x20\x22\x3e\xd\12\11\x9\11\74\57\x64\151\x76\x3e\xd\12\11\x9\x9\x3c\x2f\144\151\166\x3e\xd\xa\11\11\x9\xd\12\11\11\xd\xa\x9\11\11";
    ob_end_flush();
    ?>

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
    die;
}
function mo_saml_checkMapping($Vj, $od, $PO)
{
    try {
        $fm = get_option("\163\141\x6d\x6c\x5f\x61\155\137\145\155\x61\151\x6c");
        $Ks = get_option("\163\x61\x6d\x6c\137\141\x6d\137\165\163\145\162\x6e\x61\x6d\145");
        $z8 = get_option("\x73\141\155\x6c\137\141\155\x5f\x66\x69\162\163\x74\x5f\156\141\155\145");
        $sB = get_option("\x73\141\x6d\x6c\137\x61\155\x5f\154\141\163\x74\x5f\x6e\x61\155\145");
        $Xh = get_option("\x73\x61\155\x6c\x5f\141\155\137\147\162\157\165\160\x5f\x6e\x61\x6d\145");
        $aH = get_option("\x73\x61\155\x6c\137\141\x6d\137\144\x65\146\141\165\x6c\x74\137\x75\x73\x65\x72\x5f\x72\x6f\x6c\x65");
        $Bq = get_option("\163\141\155\154\x5f\141\x6d\137\x64\157\x6e\x74\x5f\141\x6c\154\x6f\167\x5f\165\156\x6c\151\163\164\x65\144\137\x75\x73\x65\162\137\x72\x6f\154\x65");
        $Oh = get_option("\x73\x61\x6d\x6c\137\x61\155\137\141\143\143\157\x75\156\164\x5f\x6d\x61\164\143\150\x65\x72");
        $t3 = '';
        $y_ = '';
        if (empty($Vj)) {
            goto pO;
        }
        if (!empty($z8) && array_key_exists($z8, $Vj)) {
            goto qW;
        }
        $z8 = '';
        goto p3;
        qW:
        $z8 = $Vj[$z8][0];
        p3:
        if (!empty($sB) && array_key_exists($sB, $Vj)) {
            goto mN;
        }
        $sB = '';
        goto dl;
        mN:
        $sB = $Vj[$sB][0];
        dl:
        if (!empty($Ks) && array_key_exists($Ks, $Vj)) {
            goto Gq;
        }
        $y_ = $Vj["\x4e\141\155\145\111\x44"][0];
        goto nb;
        Gq:
        $y_ = $Vj[$Ks][0];
        nb:
        if (!empty($fm) && array_key_exists($fm, $Vj)) {
            goto of;
        }
        $t3 = $Vj["\116\141\x6d\x65\111\104"][0];
        goto LO;
        of:
        $t3 = $Vj[$fm][0];
        LO:
        if (!empty($Xh) && array_key_exists($Xh, $Vj)) {
            goto L9;
        }
        $Xh = array();
        goto js;
        L9:
        $Xh = $Vj[$Xh];
        js:
        if (!empty($Oh)) {
            goto sY;
        }
        $Oh = "\x65\155\141\151\154";
        sY:
        pO:
        if ($od == "\x74\145\163\164\x56\141\154\x69\144\x61\x74\x65") {
            goto sk;
        }
        if ($od == "\x74\x65\163\x74\x4e\145\167\103\145\x72\164\x69\x66\x69\143\x61\164\145") {
            goto Lj;
        }
        mo_saml_login_user($t3, $z8, $sB, $y_, $Xh, $Bq, $aH, $od, $Oh, $PO, $Vj["\x4e\x61\x6d\145\111\104"][0], $Vj);
        goto yz;
        sk:
        update_option("\x6d\x6f\x5f\x73\141\x6d\x6c\x5f\x74\x65\163\x74", "\x54\x65\163\164\40\x73\165\143\143\145\x73\x73\x66\x75\154");
        mo_saml_show_test_result($z8, $sB, $t3, $Xh, $Vj, $od);
        goto yz;
        Lj:
        update_option("\x6d\157\137\163\141\x6d\154\x5f\x74\x65\163\x74\x5f\x6e\145\x77\x5f\x63\x65\162\164", "\x54\x65\x73\164\40\163\x75\x63\143\x65\x73\x73\146\165\x6c");
        mo_saml_show_test_result($z8, $sB, $t3, $Xh, $Vj, $od);
        yz:
    } catch (Exception $HI) {
        echo sprintf("\101\156\x20\x65\x72\162\157\x72\40\x6f\143\x63\x75\162\162\x65\144\40\x77\x68\151\x6c\x65\x20\x70\162\157\143\145\163\163\151\x6e\147\40\164\x68\145\x20\x53\101\115\x4c\x20\122\x65\x73\160\157\156\x73\x65\x2e");
        die;
    }
}
function mo_saml_show_test_result($z8, $sB, $t3, $Xh, $Vj, $od)
{
    echo "\x3c\144\151\166\x20\x73\x74\x79\x6c\x65\75\42\x66\x6f\156\164\x2d\146\x61\155\x69\x6c\171\72\x43\141\x6c\151\142\x72\x69\x3b\x70\x61\144\x64\x69\x6e\x67\x3a\60\40\63\45\73\42\x3e";
    if (!empty($t3)) {
        goto IE;
    }
    echo "\74\x64\151\x76\x20\x73\x74\171\x6c\145\x3d\42\143\x6f\154\157\162\x3a\40\x23\141\71\64\64\x34\62\73\142\x61\x63\x6b\x67\162\x6f\x75\156\x64\x2d\143\x6f\154\157\x72\x3a\x20\43\x66\x32\144\x65\x64\145\73\x70\x61\144\x64\151\156\x67\x3a\40\61\65\160\170\73\155\141\162\147\151\156\x2d\x62\x6f\x74\164\x6f\x6d\x3a\x20\x32\x30\160\170\x3b\x74\x65\x78\x74\55\141\x6c\x69\x67\156\72\143\x65\156\x74\x65\x72\x3b\x62\x6f\162\x64\x65\162\72\x31\160\170\40\163\x6f\x6c\x69\144\x20\x23\x45\66\102\63\x42\x32\x3b\146\x6f\156\164\x2d\x73\x69\x7a\x65\72\61\70\160\x74\73\42\x3e\x54\x45\123\124\x20\x46\101\111\114\105\x44\x3c\57\144\151\166\x3e\15\12\x9\11\x9\x9\74\144\x69\x76\40\163\164\x79\154\x65\x3d\x22\143\157\154\157\x72\x3a\x20\43\141\71\x34\64\x34\x32\x3b\146\x6f\156\164\x2d\x73\151\x7a\145\72\x31\x34\x70\x74\x3b\x20\x6d\x61\x72\147\151\156\55\142\x6f\x74\164\x6f\x6d\x3a\x32\x30\160\170\73\x22\x3e\127\x41\x52\116\111\x4e\x47\x3a\x20\x53\157\155\x65\40\x41\164\x74\162\151\x62\x75\x74\x65\163\40\x44\x69\144\x20\x4e\x6f\164\x20\115\x61\164\x63\x68\x2e\x3c\x2f\144\151\166\76\15\xa\11\11\x9\11\x3c\144\151\x76\40\163\164\x79\x6c\x65\75\x22\x64\x69\x73\x70\x6c\x61\x79\x3a\142\x6c\157\x63\153\73\x74\x65\x78\x74\x2d\141\154\151\x67\x6e\x3a\143\x65\x6e\164\x65\162\73\x6d\141\x72\x67\x69\156\x2d\142\x6f\164\x74\x6f\155\x3a\64\x25\73\42\x3e\74\151\x6d\147\40\x73\x74\x79\154\145\x3d\42\167\151\144\x74\x68\72\x31\65\x25\73\x22\x73\162\143\x3d\x22" . plugin_dir_url(__FILE__) . "\x69\x6d\141\x67\x65\163\57\167\x72\x6f\x6e\x67\56\160\x6e\x67\42\x3e\74\57\x64\x69\x76\76";
    goto ye;
    IE:
    update_option("\x6d\x6f\x5f\x73\x61\x6d\154\x5f\164\x65\x73\164\x5f\143\157\156\146\151\x67\137\x61\164\x74\x72\163", $Vj);
    echo "\x3c\144\x69\166\40\x73\x74\171\154\145\x3d\42\143\x6f\154\157\x72\x3a\40\x23\x33\x63\67\x36\x33\x64\x3b\xd\xa\11\11\x9\x9\x62\141\143\x6b\147\162\157\x75\x6e\x64\x2d\x63\x6f\x6c\x6f\x72\x3a\x20\43\x64\146\x66\60\144\x38\73\40\x70\141\x64\144\x69\x6e\147\72\x32\x25\x3b\155\141\x72\147\151\156\55\142\x6f\164\164\157\155\x3a\x32\x30\x70\x78\73\x74\145\170\164\55\x61\154\x69\x67\156\72\143\x65\156\x74\x65\x72\73\x20\x62\x6f\x72\144\145\162\72\61\160\x78\x20\x73\x6f\x6c\151\144\x20\x23\101\x45\104\102\x39\x41\73\x20\146\x6f\x6e\x74\55\163\151\x7a\145\72\x31\70\160\x74\x3b\42\76\124\x45\123\x54\40\123\x55\103\103\x45\123\x53\x46\125\114\74\57\x64\151\x76\x3e\15\xa\11\11\x9\11\74\144\x69\x76\x20\163\x74\171\154\x65\75\x22\144\151\x73\x70\154\x61\x79\x3a\142\154\157\143\x6b\73\x74\x65\x78\164\55\141\x6c\151\147\156\x3a\143\x65\156\x74\145\x72\73\155\x61\162\x67\151\156\x2d\x62\x6f\x74\x74\x6f\x6d\x3a\64\x25\73\x22\x3e\x3c\x69\x6d\147\40\x73\164\171\x6c\x65\75\x22\x77\151\144\x74\x68\72\61\x35\45\x3b\42\163\x72\143\75\42" . plugin_dir_url(__FILE__) . "\x69\155\141\x67\x65\x73\57\x67\162\x65\x65\156\x5f\143\x68\x65\x63\153\56\x70\x6e\x67\x22\76\x3c\x2f\144\151\x76\76";
    ye:
    $kX = get_option("\163\x61\155\x6c\x5f\x61\155\x5f\x61\x63\x63\157\165\x6e\164\137\x6d\x61\164\143\x68\x65\x72") ? get_option("\163\x61\155\154\x5f\141\x6d\x5f\x61\143\143\157\165\156\x74\137\x6d\x61\x74\x63\x68\x65\x72") : "\145\x6d\141\151\154";
    $jR = $od == "\x74\x65\x73\164\x4e\x65\x77\x43\145\x72\x74\x69\x66\151\x63\141\x74\x65" ? "\144\x69\163\x70\x6c\x61\x79\x3a\x6e\157\156\145" : '';
    if (!($kX == "\145\155\x61\151\154" && !filter_var($t3, FILTER_VALIDATE_EMAIL))) {
        goto C4;
    }
    echo "\74\160\76\74\146\x6f\156\x74\x20\143\157\154\x6f\x72\75\42\x23\106\106\60\x30\x30\60\x22\40\163\x74\171\154\145\x3d\42\x66\x6f\x6e\164\55\163\151\172\x65\x3a\x31\x34\160\x74\42\x3e\x28\x57\x61\162\x6e\151\x6e\147\72\x20\x54\x68\x65\x20\101\164\x74\162\151\x62\165\x74\x65\40\x22";
    echo get_option("\163\141\x6d\x6c\x5f\141\x6d\x5f\x65\155\141\x69\154") ? get_option("\x73\x61\155\x6c\x5f\x61\155\137\x65\155\x61\151\x6c") : "\x4e\141\155\x65\x49\x44";
    echo "\x22\40\x64\x6f\145\x73\x20\x6e\x6f\x74\40\143\157\x6e\x74\141\151\x6e\40\x76\x61\x6c\151\x64\x20\105\155\x61\x69\154\40\111\x44\x29\x3c\57\146\157\x6e\164\x3e\74\x2f\x70\76";
    C4:
    echo "\74\x73\160\x61\156\40\x73\x74\171\x6c\145\75\42\146\x6f\156\164\55\163\151\x7a\145\x3a\61\64\160\164\73\x22\76\74\142\76\110\145\154\154\x6f\x3c\57\142\x3e\54\x20" . $t3 . "\x3c\x2f\x73\160\141\156\x3e\x3c\142\x72\x2f\x3e\74\160\x20\x73\164\x79\154\145\75\42\146\x6f\156\164\x2d\167\x65\151\x67\150\x74\x3a\142\x6f\x6c\x64\73\x66\x6f\156\164\55\163\x69\x7a\x65\x3a\x31\x34\x70\164\x3b\x6d\x61\162\x67\x69\156\x2d\x6c\x65\x66\164\x3a\61\45\73\42\x3e\101\124\x54\x52\111\102\x55\x54\105\123\x20\x52\x45\x43\x45\x49\126\x45\x44\72\74\57\x70\76\xd\xa\11\11\11\11\74\x74\141\142\154\145\x20\x73\x74\x79\154\x65\x3d\x22\142\157\162\x64\145\x72\x2d\x63\157\x6c\154\x61\160\163\x65\x3a\143\x6f\x6c\154\x61\160\x73\145\x3b\x62\x6f\162\144\x65\x72\x2d\163\160\141\143\151\156\x67\72\60\x3b\40\x64\x69\x73\x70\154\141\171\72\x74\141\x62\154\x65\73\x77\151\144\164\150\72\x31\60\60\45\73\x20\x66\157\156\164\55\x73\151\x7a\145\x3a\x31\x34\x70\164\73\142\141\x63\x6b\147\162\x6f\x75\x6e\144\x2d\x63\157\x6c\x6f\162\x3a\x23\x45\x44\x45\104\105\x44\73\x22\x3e\xd\xa\11\x9\11\x9\74\x74\x72\x20\x73\x74\x79\154\145\x3d\42\164\x65\x78\164\55\x61\154\151\147\x6e\x3a\143\145\156\x74\145\x72\73\42\x3e\x3c\164\x64\40\x73\x74\x79\154\145\75\42\x66\x6f\156\x74\55\167\x65\151\x67\150\164\72\x62\157\x6c\x64\73\x62\x6f\x72\x64\x65\162\x3a\x32\x70\x78\x20\x73\x6f\x6c\151\x64\40\43\x39\64\x39\x30\71\60\73\160\x61\144\x64\x69\156\x67\72\62\x25\x3b\42\x3e\x41\x54\124\122\x49\102\x55\124\105\40\116\x41\115\x45\74\57\164\144\x3e\74\164\x64\x20\163\x74\171\154\x65\x3d\x22\146\157\156\164\x2d\x77\x65\151\147\x68\164\72\142\157\154\144\73\x70\141\x64\144\151\x6e\x67\72\62\x25\x3b\142\157\162\144\x65\162\72\x32\160\x78\40\163\157\x6c\151\144\40\x23\x39\x34\71\60\71\x30\73\x20\167\157\x72\x64\55\167\x72\x61\x70\72\x62\x72\145\141\153\x2d\x77\x6f\162\144\x3b\42\76\101\x54\x54\x52\111\102\x55\x54\105\40\126\101\114\x55\x45\74\x2f\x74\x64\x3e\74\x2f\x74\162\76";
    if (!empty($Vj)) {
        goto NZ;
    }
    echo "\x4e\157\40\101\x74\x74\162\151\142\165\x74\145\x73\x20\122\145\143\145\151\x76\x65\144\x2e";
    goto FH;
    NZ:
    foreach ($Vj as $Xr => $tq) {
        echo "\x3c\164\162\x3e\x3c\164\x64\40\163\164\171\154\145\x3d\x27\x66\157\x6e\x74\55\x77\x65\151\x67\150\x74\72\142\157\x6c\x64\x3b\x62\x6f\162\144\145\x72\x3a\62\160\170\x20\x73\x6f\154\x69\x64\40\43\x39\64\71\x30\x39\60\x3b\x70\141\x64\x64\151\156\147\x3a\x32\45\x3b\x20\167\157\162\144\55\x77\162\141\160\x3a\x62\x72\145\x61\x6b\55\x77\157\162\144\73\47\76" . $Xr . "\x3c\x2f\x74\144\x3e\74\164\144\40\x73\164\x79\x6c\145\x3d\47\x70\141\144\x64\x69\x6e\147\72\62\x25\x3b\x62\157\162\144\x65\x72\72\x32\x70\170\x20\163\x6f\154\x69\x64\40\43\x39\64\71\x30\x39\60\73\x20\x77\x6f\x72\x64\55\167\x72\x61\x70\x3a\x62\x72\145\x61\153\x2d\x77\x6f\x72\x64\73\x27\76" . implode("\x3c\150\162\x2f\76", $tq) . "\74\x2f\x74\144\76\x3c\x2f\164\162\76";
        um:
    }
    dV:
    FH:
    echo "\74\57\x74\x61\142\x6c\x65\x3e\74\x2f\x64\151\166\x3e\x3c\x64\151\166\40\x73\164\171\154\x65\x3d\42\x6d\x61\x72\147\x69\156\x3a\x33\45\73\x64\x69\x73\x70\x6c\141\171\x3a\142\154\x6f\x63\x6b\x3b\x74\x65\x78\x74\55\141\x6c\151\147\156\72\143\145\x6e\164\145\x72\x3b\x22\x3e\15\xa\x9\x9\x3c\x69\156\160\165\x74\40\x73\x74\171\x6c\145\x3d\42\x70\141\x64\144\x69\156\147\x3a\61\45\73\x77\x69\144\x74\x68\x3a\x32\x35\60\x70\x78\73\142\141\x63\153\147\x72\x6f\165\x6e\x64\x3a\40\x23\60\x30\71\61\x43\x44\x20\x6e\157\x6e\x65\40\x72\145\x70\x65\141\164\x20\x73\143\162\157\x6c\x6c\x20\x30\45\40\60\45\73\15\12\11\11\143\165\162\x73\157\162\72\x20\x70\x6f\151\x6e\164\x65\x72\x3b\146\157\x6e\164\55\163\151\172\145\72\61\65\160\x78\73\142\x6f\162\144\x65\x72\x2d\167\x69\144\x74\x68\72\40\61\x70\170\x3b\x62\x6f\162\144\x65\162\55\x73\x74\x79\154\x65\72\40\163\157\154\151\x64\x3b\142\157\x72\x64\x65\162\x2d\x72\x61\144\x69\x75\x73\x3a\40\63\x70\x78\73\167\150\151\x74\145\55\x73\160\x61\x63\x65\x3a\xd\xa\x9\11\40\156\x6f\x77\x72\141\160\73\x62\x6f\x78\55\x73\151\172\151\156\147\72\40\142\157\162\144\x65\x72\x2d\x62\157\170\73\142\x6f\162\x64\x65\x72\55\x63\x6f\x6c\x6f\x72\72\x20\43\60\x30\x37\63\101\x41\73\x62\157\170\55\163\150\141\144\157\167\72\x20\x30\x70\170\40\61\160\x78\x20\x30\160\170\40\162\147\x62\141\50\x31\62\60\x2c\40\x32\60\x30\x2c\40\62\63\x30\54\40\x30\x2e\x36\51\x20\151\156\x73\145\164\x3b\143\157\x6c\157\x72\x3a\40\43\x46\106\x46\73" . $jR . "\42\15\xa\40\40\40\x20\40\x20\40\40\x20\40\40\40\x74\x79\x70\x65\75\42\x62\165\164\164\157\x6e\x22\40\166\x61\154\165\x65\75\x22\103\x6f\x6e\146\151\147\x75\162\145\x20\101\x74\x74\x72\x69\x62\x75\164\145\x2f\x52\x6f\154\145\40\115\141\x70\x70\x69\156\147\x22\40\157\156\x43\x6c\151\143\x6b\x3d\42\x63\154\x6f\x73\x65\x5f\x61\x6e\144\137\x72\x65\x64\151\162\x65\143\164\50\x29\x3b\x22\76\40\x26\x6e\x62\163\x70\x3b\x20\40\74\x69\x6e\160\165\164\x20\x73\164\x79\154\145\75\42\x70\141\x64\144\x69\156\147\72\x31\45\x3b\x77\151\144\x74\150\72\61\x30\60\x70\170\73\142\141\x63\153\x67\x72\157\x75\x6e\144\x3a\x20\x23\x30\x30\71\x31\x43\x44\40\x6e\157\156\x65\40\162\145\x70\x65\141\164\x20\x73\x63\162\157\x6c\x6c\40\60\45\40\60\x25\73\x63\x75\162\x73\x6f\162\x3a\x20\160\x6f\x69\156\x74\x65\x72\x3b\x66\x6f\x6e\x74\55\x73\x69\x7a\145\x3a\x31\x35\160\170\73\142\157\162\144\145\x72\x2d\167\151\x64\164\150\x3a\40\61\x70\170\73\142\157\x72\x64\145\x72\55\x73\x74\171\154\x65\72\x20\x73\x6f\x6c\151\144\x3b\142\157\x72\x64\145\x72\x2d\162\141\x64\x69\165\163\72\x20\x33\160\x78\73\x77\x68\151\x74\x65\x2d\163\160\141\x63\x65\72\40\x6e\x6f\167\162\x61\x70\x3b\142\157\x78\x2d\x73\x69\x7a\x69\x6e\147\72\x20\x62\x6f\162\x64\x65\x72\55\x62\x6f\x78\x3b\x62\157\x72\144\x65\162\55\x63\157\x6c\157\x72\x3a\x20\43\60\x30\x37\x33\101\101\73\142\x6f\170\55\163\x68\141\x64\157\x77\72\x20\60\160\170\x20\x31\160\170\x20\x30\160\170\40\x72\147\142\141\x28\61\x32\60\54\40\62\60\x30\54\40\x32\x33\x30\54\x20\60\56\66\x29\x20\x69\x6e\x73\145\164\x3b\x63\157\x6c\x6f\162\x3a\40\43\x46\106\106\x3b\42\164\171\160\x65\75\x22\142\x75\x74\x74\x6f\x6e\x22\40\x76\x61\x6c\165\x65\75\42\x44\x6f\x6e\x65\42\x20\157\156\103\154\151\x63\x6b\x3d\x22\143\154\x6f\163\145\x5f\x61\156\x64\137\162\x65\x66\162\x65\163\x68\50\51\x22\x3e\xd\12\x20\40\40\40\40\x20\40\x20\15\12\40\x20\x20\x20\x20\40\x20\x20\x20\40\40\40\x3c\x2f\144\x69\x76\x3e\15\12\x20\x20\x20\x20\40\40\40\x20\x20\x20\x20\40\15\12\40\x20\x20\x20\x20\x20\x20\40\x20\x20\x20\x20\x3c\163\x63\162\x69\160\164\76\xd\12\x20\x20\x20\x20\40\40\40\x20\x20\40\x20\x20\xd\xa\x20\40\40\40\x20\x20\x20\40\40\40\x20\x20\40\146\165\x6e\x63\x74\151\x6f\x6e\x20\x63\154\157\x73\145\137\x61\x6e\144\137\162\145\x64\x69\x72\x65\x63\164\50\x29\173\15\12\x20\x20\x20\x20\x20\x20\x20\x20\40\40\40\x20\x20\x20\x20\x20\40\167\151\156\x64\x6f\x77\56\x6f\160\x65\156\x65\x72\56\162\145\x64\x69\162\145\x63\164\137\x74\157\x5f\141\164\164\162\151\x62\x75\164\x65\x5f\x6d\x61\x70\x70\151\x6e\x67\x28\51\x3b\15\xa\x20\40\x20\40\x20\40\40\x20\x20\x20\40\40\40\x20\40\40\40\163\145\154\146\56\x63\154\x6f\163\x65\x28\51\x3b\15\xa\11\11\11\11\175\x20\15\12\x9\x9\x9\x66\x75\156\143\x74\x69\x6f\156\40\x63\154\x6f\x73\x65\137\141\x6e\144\x5f\162\145\146\162\x65\x73\x68\x28\51\173\15\xa\11\x9\11\11\167\x69\156\x64\x6f\167\x2e\x6f\x70\x65\x6e\x65\162\56\x6c\157\x63\141\164\x69\x6f\156\x2e\162\x65\154\x6f\141\144\x28\x29\73\15\12\x9\x9\x9\11\163\145\154\146\56\x63\x6c\157\x73\x65\50\x29\x3b\xd\xa\x9\x9\x9\175\15\xa\x20\15\12\x3c\57\x73\143\162\151\160\164\76";
    die;
}
function mo_saml_convert_to_windows_iconv($Th)
{
    $bO = get_option("\x6d\x6f\x5f\x73\x61\155\154\137\145\156\143\x6f\144\151\156\x67\x5f\145\156\141\142\x6c\145\x64");
    if (!($bO === '' || !mo_saml_is_extension_installed("\151\x63\x6f\x6e\x76"))) {
        goto MN;
    }
    return $Th;
    MN:
    return iconv("\125\124\106\x2d\70", "\103\120\x31\x32\65\62\57\57\x49\107\x4e\x4f\x52\x45", $Th);
}
function mo_saml_map_basic_attributes($user, $z8, $sB, $Vj)
{
    $JQ = $user->ID;
    if (empty($z8)) {
        goto Ng;
    }
    $ul = wp_update_user(array("\x49\x44" => $JQ, "\x66\x69\x72\163\164\137\156\141\155\145" => $z8));
    Ng:
    if (empty($sB)) {
        goto uX;
    }
    $ul = wp_update_user(array("\x49\x44" => $JQ, "\154\x61\x73\x74\137\156\x61\155\145" => $sB));
    uX:
    if (is_null($Vj)) {
        goto cs;
    }
    update_user_meta($JQ, "\x6d\157\x5f\163\141\155\154\137\x75\163\145\162\x5f\x61\164\x74\x72\151\x62\x75\164\145\x73", $Vj);
    $NM = get_option("\163\x61\155\x6c\x5f\x61\x6d\x5f\144\x69\x73\160\154\x61\171\x5f\x6e\x61\x6d\x65");
    if (empty($NM)) {
        goto Y_;
    }
    if (strcmp($NM, "\125\123\x45\x52\x4e\x41\115\x45") == 0) {
        goto sO;
    }
    if (strcmp($NM, "\106\116\101\115\105") == 0 && !empty($z8)) {
        goto FZ;
    }
    if (strcmp($NM, "\114\116\101\115\105") == 0 && !empty($sB)) {
        goto CF;
    }
    if (strcmp($NM, "\106\x4e\x41\115\105\x5f\x4c\116\101\x4d\105") == 0 && !empty($sB) && !empty($z8)) {
        goto cE;
    }
    if (!(strcmp($NM, "\x4c\x4e\x41\115\x45\x5f\x46\x4e\x41\115\x45") == 0 && !empty($sB) && !empty($z8))) {
        goto wr;
    }
    $ul = wp_update_user(array("\111\104" => $JQ, "\x64\x69\163\x70\x6c\x61\171\x5f\156\x61\155\145" => $sB . "\x20" . $z8));
    wr:
    goto Ca;
    cE:
    $ul = wp_update_user(array("\x49\x44" => $JQ, "\144\x69\163\x70\x6c\141\171\137\156\141\x6d\145" => $z8 . "\x20" . $sB));
    Ca:
    goto ob;
    CF:
    $ul = wp_update_user(array("\111\104" => $JQ, "\x64\151\x73\x70\x6c\141\171\137\156\x61\155\145" => $sB));
    ob:
    goto OX;
    FZ:
    $ul = wp_update_user(array("\111\x44" => $JQ, "\144\x69\163\x70\154\141\171\x5f\x6e\141\x6d\145" => $z8));
    OX:
    goto Am;
    sO:
    $ul = wp_update_user(array("\x49\104" => $JQ, "\144\x69\163\160\154\141\171\x5f\x6e\x61\155\145" => $user->user_login));
    Am:
    Y_:
    cs:
}
function mo_saml_set_auth_cookie($user, $PO, $A3, $mu = false)
{
    $JQ = $user->ID;
    wp_set_current_user($JQ);
    $Fp = false;
    $Fp = apply_filters("\155\x6f\137\162\145\x6d\x65\x6d\x62\145\x72\x5f\x6d\145", $Fp);
    wp_set_auth_cookie($JQ, $Fp);
    if (!$mu) {
        goto TT;
    }
    do_action("\x75\x73\145\x72\x5f\162\x65\147\151\163\164\x65\x72", $JQ);
    TT:
    do_action("\x77\x70\x5f\154\157\x67\x69\156", $user->user_login, $user);
}
function mo_saml_login_user($t3, $z8, $sB, $y_, $Xh, $Bq, $aH, $od, $Oh, $PO = '', $A3 = '', $Vj = null)
{
    $f4 = get_option("\x6d\x6f\x5f\163\x61\155\154\x5f\x73\x70\137\142\x61\x73\x65\137\165\162\x6c");
    if (!empty($f4)) {
        goto Xn;
    }
    $f4 = home_url();
    Xn:
    if (username_exists($y_) || email_exists($t3)) {
        goto zR;
    }
    $sa = true;
    $r4 = get_option("\155\x6f\x5f\x73\141\155\x6c\137\144\157\x6e\x74\137\143\162\x65\141\164\145\x5f\165\163\145\x72\x5f\151\x66\137\x72\157\154\145\137\156\157\164\137\x6d\x61\x70\160\145\144");
    if (!(!empty($r4) && strcmp($r4, "\143\150\145\143\x6b\x65\x64") == 0)) {
        goto oe;
    }
    $QG = is_role_mapping_configured_for_user($cP, $Xh);
    $sa = $QG;
    oe:
    if ($sa === true) {
        goto B4;
    }
    $mD = "\x57\145\40\143\x6f\x75\x6c\x64\40\x6e\157\x74\40\x73\151\147\x6e\40\x79\157\165\40\151\156\56\40\x50\x6c\145\x61\163\145\40\x63\157\156\x74\x61\x63\164\40\171\x6f\165\x72\x20\101\x64\x6d\151\x6e\x69\x73\x74\x72\x61\164\x6f\162\x2e";
    wp_die($mD, "\x45\162\162\157\162\x3a\40\116\157\x74\x20\141\40\127\x6f\x72\x64\x50\162\145\163\x73\40\x4d\145\x6d\x62\145\162");
    die;
    goto Ud;
    B4:
    $s_ = wp_generate_password(10, false);
    if (!empty($y_)) {
        goto p4;
    }
    $JQ = wp_create_user($t3, $s_, $t3);
    goto q5;
    p4:
    $JQ = wp_create_user($y_, $s_, $t3);
    q5:
    if (!is_wp_error($JQ)) {
        goto tI;
    }
    wp_die($JQ->get_error_message() . "\x3c\x62\162\x3e\120\154\145\x61\x73\x65\40\x63\x6f\156\x74\x61\x63\164\x20\x79\157\x75\x72\x20\x41\x64\x6d\151\x6e\x69\163\x74\162\141\x74\157\162\56\74\142\x72\76\74\142\76\125\163\145\162\156\x61\155\x65\74\57\x62\x3e\72\40" . $t3, "\x45\162\x72\x6f\x72\72\x20\103\x6f\x75\154\144\156\x27\x74\40\143\162\x65\141\x74\145\x20\165\163\x65\162");
    tI:
    $user = get_user_by("\x69\x64", $JQ);
    if (!empty($Bq) && $Bq == "\143\150\145\x63\x6b\145\x64") {
        goto kq;
    }
    if (!empty($aH)) {
        goto gU;
    }
    $aH = get_option("\x64\x65\x66\141\165\x6c\164\x5f\162\x6f\x6c\145");
    $ul = wp_update_user(array("\x49\x44" => $JQ, "\x72\157\154\x65" => $aH));
    goto FP;
    kq:
    $ul = wp_update_user(array("\111\104" => $JQ, "\x72\x6f\x6c\x65" => false));
    goto FP;
    gU:
    $ul = wp_update_user(array("\111\104" => $JQ, "\162\x6f\154\145" => $aH));
    FP:
    mo_saml_map_basic_attributes($user, $z8, $sB, $Vj);
    mo_saml_set_auth_cookie($user, $PO, $A3, true);
    Ud:
    goto Rx;
    zR:
    if (username_exists($y_)) {
        goto hz;
    }
    if (!email_exists($t3)) {
        goto Uh;
    }
    $user = get_user_by("\145\155\x61\x69\154", $t3);
    $JQ = $user->ID;
    Uh:
    goto ss;
    hz:
    $user = get_user_by("\x6c\x6f\147\151\x6e", $y_);
    $JQ = $user->ID;
    if (empty($t3)) {
        goto Sx;
    }
    $ul = wp_update_user(array("\111\x44" => $JQ, "\x75\x73\145\162\x5f\145\x6d\x61\151\x6c" => $t3));
    Sx:
    ss:
    mo_saml_map_basic_attributes($user, $z8, $sB, $Vj);
    $Bc = get_option("\x73\141\x6d\154\x5f\141\x6d\x5f\144\157\x6e\164\137\165\160\144\141\x74\x65\x5f\145\x78\x69\163\164\151\x6e\x67\137\x75\163\145\162\137\x72\x6f\x6c\145");
    if (!(empty($Bc) || $Bc != "\143\x68\145\143\153\x65\x64")) {
        goto HE;
    }
    if (!is_administrator_user($user) && !empty($Bq) && $Bq == "\x63\x68\x65\143\153\x65\x64") {
        goto Md;
    }
    if (!is_administrator_user($user) && !empty($aH)) {
        goto P7;
    }
    goto We;
    Md:
    $ul = wp_update_user(array("\x49\104" => $JQ, "\162\157\x6c\145" => false));
    goto We;
    P7:
    $ul = wp_update_user(array("\x49\x44" => $JQ, "\x72\157\154\x65" => $aH));
    We:
    HE:
    mo_saml_set_auth_cookie($user, $PO, $A3);
    Rx:
    mo_saml_post_login_redirection($od, $f4);
}
function mo_saml_post_login_redirection($od, $f4)
{
    $cf = get_option("\155\x6f\x5f\x73\141\x6d\x6c\137\x72\x65\x6c\141\171\137\163\164\141\x74\x65");
    if (!empty($cf)) {
        goto D6;
    }
    if (empty($od)) {
        goto xs;
    }
    if (filter_var($od, FILTER_VALIDATE_URL) === FALSE) {
        goto yq;
    }
    if (strpos($od, home_url()) !== false) {
        goto qK;
    }
    $qA = $f4;
    goto Is;
    qK:
    $qA = htmlspecialchars_decode($od);
    Is:
    goto S2;
    yq:
    $qA = htmlspecialchars_decode($od);
    S2:
    xs:
    goto ZI;
    D6:
    $qA = htmlspecialchars_decode($cf);
    ZI:
    if (!empty($qA)) {
        goto ll;
    }
    $qA = htmlspecialchars_decode($f4);
    ll:
    wp_redirect($qA);
    die;
}
function is_role_mapping_configured_for_user($cP, $Xh)
{
    if (!(!empty($Xh) && !empty($cP))) {
        goto Eb;
    }
    foreach ($cP as $qo => $hH) {
        $qe = explode("\73", $hH);
        foreach ($qe as $Pm) {
            foreach ($Xh as $Hc) {
                $Hc = trim($Hc);
                if (!(!empty($Hc) && $Hc == $Pm)) {
                    goto Oi;
                }
                return true;
                Oi:
                ti:
            }
            Gt:
            Ck:
        }
        lq:
        MK:
    }
    yl:
    Eb:
    return false;
}
function is_administrator_user($user)
{
    $ac = $user->roles;
    if (!is_null($ac) && in_array("\141\x64\155\151\156\x69\x73\164\162\141\164\x6f\x72", $ac, TRUE)) {
        goto Sn;
    }
    return false;
    goto PX;
    Sn:
    return true;
    PX:
}
function mo_saml_is_customer_license_verified()
{
    $Xr = get_option("\155\157\137\x73\141\x6d\154\137\x63\165\x73\x74\157\155\145\162\137\x74\x6f\x6b\x65\156");
    $k9 = AESEncryption::decrypt_data(get_option("\x74\x5f\163\151\164\x65\x5f\163\164\x61\164\x75\163"), $Xr);
    $Kn = get_option("\163\155\x6c\x5f\x6c\153");
    $OH = get_option("\155\x6f\137\x73\x61\x6d\x6c\137\141\x64\155\x69\156\137\x65\155\141\x69\154");
    $Xs = get_option("\x6d\x6f\137\x73\141\155\x6c\x5f\x61\x64\x6d\x69\x6e\x5f\x63\165\x73\x74\157\x6d\x65\162\137\153\x65\x79");
    if (!$k9 && !$Kn || !$OH || !$Xs || !is_numeric(trim($Xs))) {
        goto pI;
    }
    return 1;
    goto Xj;
    pI:
    return 0;
    Xj:
}
function saml_get_current_page_url()
{
    $kn = $_SERVER["\110\x54\124\120\x5f\110\x4f\x53\124"];
    if (!(substr($kn, -1) == "\x2f")) {
        goto Fd;
    }
    $kn = substr($kn, 0, -1);
    Fd:
    $Cy = $_SERVER["\122\x45\121\125\105\x53\124\137\125\x52\x49"];
    if (!(substr($Cy, 0, 1) == "\x2f")) {
        goto Kt;
    }
    $Cy = substr($Cy, 1);
    Kt:
    $yY = isset($_SERVER["\110\124\x54\120\x53"]) && strcasecmp($_SERVER["\110\124\124\120\123"], "\x6f\x6e") == 0;
    $sp = "\150\164\x74\x70" . ($yY ? "\163" : '') . "\x3a\57\x2f" . $kn . "\x2f" . $Cy;
    return $sp;
}
function show_status_error($UN, $od, $BN)
{
    $UN = strip_tags($UN);
    $BN = strip_tags($BN);
    if ($od == "\x74\x65\163\x74\x56\x61\x6c\151\144\141\x74\145" or $od == "\x74\x65\163\164\x4e\145\x77\x43\x65\x72\164\151\x66\151\143\x61\164\145") {
        goto qi;
    }
    wp_die("\x57\x65\x20\143\157\165\x6c\144\40\156\x6f\x74\40\x73\x69\147\156\x20\171\x6f\x75\x20\151\156\x2e\x20\120\x6c\145\141\163\145\x20\x63\157\156\164\141\143\164\x20\x79\x6f\x75\162\40\x41\x64\155\151\156\151\163\164\x72\x61\164\157\x72\x2e", "\x45\x72\162\x6f\x72\72\40\x49\156\166\141\154\151\x64\40\x53\101\115\114\40\122\145\x73\160\x6f\156\163\145\40\x53\164\x61\164\165\x73");
    goto JB;
    qi:
    echo "\74\144\x69\166\x20\163\164\171\x6c\x65\75\42\x66\x6f\x6e\x74\x2d\x66\141\x6d\151\154\171\72\x43\x61\154\x69\142\x72\x69\x3b\x70\141\x64\144\x69\156\x67\72\x30\40\x33\45\73\x22\x3e";
    echo "\x3c\144\x69\x76\x20\x73\x74\171\x6c\145\75\42\x63\x6f\x6c\x6f\x72\x3a\40\43\x61\71\64\64\64\62\73\142\141\x63\x6b\147\x72\157\165\x6e\144\55\x63\157\154\157\x72\x3a\40\x23\146\62\144\145\144\145\73\x70\x61\x64\144\151\x6e\x67\72\40\61\65\x70\170\x3b\155\x61\x72\x67\151\x6e\x2d\x62\x6f\164\164\x6f\155\72\40\x32\x30\160\x78\x3b\164\x65\x78\x74\x2d\x61\154\151\147\156\x3a\143\145\156\x74\145\162\x3b\x62\x6f\x72\x64\x65\162\x3a\x31\160\x78\x20\x73\x6f\x6c\x69\144\x20\x23\x45\x36\x42\x33\102\x32\73\146\x6f\x6e\x74\55\x73\151\172\145\72\x31\70\160\164\73\x22\76\40\x45\x52\x52\117\122\x3c\x2f\x64\151\x76\76\15\xa\x20\x20\x20\x20\40\x20\x20\40\x20\40\40\40\x20\x20\x20\40\74\x64\x69\166\40\163\164\x79\154\145\75\x22\143\x6f\x6c\x6f\162\x3a\40\43\141\x39\x34\64\x34\62\x3b\146\157\x6e\x74\x2d\x73\x69\x7a\145\x3a\x31\x34\x70\x74\x3b\x20\x6d\141\162\147\151\156\55\142\x6f\x74\x74\157\x6d\72\x32\x30\160\x78\x3b\x22\x3e\x3c\x70\x3e\x3c\163\164\162\x6f\156\147\76\105\x72\x72\x6f\x72\x3a\x20\x3c\x2f\x73\x74\162\157\x6e\147\x3e\x20\x49\156\166\141\154\151\144\x20\x53\x41\x4d\x4c\40\122\x65\163\160\x6f\x6e\x73\x65\40\x53\164\141\164\x75\163\56\74\57\x70\x3e\xd\xa\x20\x20\x20\x20\x20\x20\40\40\x20\40\x20\40\x20\x20\x20\40\x3c\160\x3e\x3c\163\164\x72\x6f\x6e\147\x3e\103\x61\165\163\x65\163\x3c\57\x73\x74\x72\157\156\147\76\72\x20\111\x64\x65\x6e\x74\x69\x74\171\x20\x50\162\157\x76\x69\144\x65\162\40\150\141\x73\40\x73\145\x6e\164\40\x27" . $UN . "\x27\x20\x73\x74\x61\164\165\163\40\143\x6f\144\145\40\151\156\x20\123\101\115\x4c\40\122\145\163\x70\x6f\x6e\163\x65\56\x20\x3c\x2f\x70\76\15\xa\x9\11\x9\x9\11\11\11\x9\x3c\160\x3e\74\163\x74\x72\157\156\x67\76\x52\145\141\163\157\156\x3c\x2f\163\164\162\157\x6e\x67\76\x3a\40" . get_status_message($UN) . "\x3c\x2f\160\76\40";
    if (empty($BN)) {
        goto DD;
    }
    echo "\x3c\160\x3e\74\x73\164\x72\157\x6e\x67\x3e\123\x74\141\164\x75\163\x20\115\x65\163\163\141\147\145\x20\x69\156\40\164\x68\145\40\123\101\115\114\x20\x52\145\x73\x70\x6f\x6e\163\145\72\74\x2f\163\x74\162\157\x6e\147\x3e\40\74\142\162\x2f\76" . $BN . "\74\x2f\x70\76";
    DD:
    echo "\x3c\142\162\76\xd\xa\11\x9\x9\x9\x3c\57\144\151\166\76\15\12\15\xa\40\40\x20\x20\40\40\40\x20\x20\40\x20\x20\x20\40\x20\40\x3c\144\151\x76\40\x73\x74\171\154\145\x3d\42\x6d\x61\x72\x67\151\x6e\x3a\63\45\x3b\x64\151\163\x70\x6c\x61\171\72\142\154\x6f\x63\x6b\73\164\x65\170\x74\x2d\x61\154\151\147\156\72\143\x65\156\164\x65\162\x3b\x22\76\15\12\40\x20\x20\40\40\40\40\40\x20\40\40\40\x20\x20\40\x20\x3c\x64\x69\166\x20\163\164\x79\x6c\x65\x3d\x22\155\x61\x72\x67\151\x6e\72\x33\45\x3b\144\x69\163\160\x6c\x61\171\x3a\142\x6c\x6f\x63\153\x3b\x74\x65\x78\x74\x2d\141\154\x69\x67\156\x3a\143\x65\156\164\x65\162\73\x22\76\74\x69\156\x70\x75\x74\x20\163\x74\x79\154\x65\75\x22\160\141\144\144\x69\x6e\147\x3a\x31\45\73\x77\151\x64\x74\x68\x3a\x31\x30\60\160\x78\x3b\142\x61\143\x6b\x67\x72\x6f\x75\156\x64\x3a\x20\x23\60\x30\x39\61\103\104\x20\156\157\156\145\40\x72\145\x70\x65\141\164\x20\x73\143\162\x6f\x6c\154\x20\x30\x25\40\60\45\73\x63\x75\x72\163\157\162\72\x20\160\x6f\151\156\164\145\x72\73\146\157\156\x74\55\x73\151\172\145\72\x31\x35\x70\x78\x3b\142\157\162\144\145\x72\55\167\x69\144\164\150\72\40\x31\x70\x78\x3b\x62\x6f\x72\x64\x65\x72\55\x73\x74\x79\x6c\x65\72\x20\x73\157\154\x69\x64\x3b\x62\157\x72\x64\x65\162\x2d\x72\141\x64\x69\x75\x73\72\x20\63\160\x78\73\167\150\151\x74\145\x2d\163\x70\x61\143\x65\72\40\x6e\157\x77\162\141\x70\x3b\142\x6f\x78\x2d\x73\151\x7a\151\x6e\147\72\x20\142\157\x72\x64\x65\x72\x2d\x62\157\x78\x3b\142\x6f\162\144\x65\162\55\143\157\154\x6f\162\x3a\x20\43\60\60\x37\63\101\101\73\142\157\x78\55\163\150\x61\x64\x6f\x77\72\x20\x30\x70\170\x20\61\x70\x78\x20\x30\x70\170\x20\162\x67\142\141\x28\61\62\x30\x2c\40\62\x30\x30\54\x20\62\x33\x30\x2c\x20\x30\56\66\51\x20\x69\x6e\x73\145\x74\73\x63\x6f\x6c\157\162\x3a\x20\43\106\106\x46\x3b\x22\x74\x79\x70\145\75\42\x62\x75\x74\x74\157\156\x22\x20\166\x61\x6c\x75\x65\x3d\x22\104\x6f\156\145\x22\x20\157\x6e\103\x6c\x69\x63\x6b\x3d\x22\163\x65\154\x66\x2e\x63\x6c\x6f\x73\145\x28\51\73\42\x3e\74\x2f\144\151\x76\x3e";
    die;
    JB:
}
function get_status_message($UN)
{
    switch ($UN) {
        case "\x52\x65\161\165\x65\163\x74\145\162":
            return "\x54\x68\145\40\x72\x65\161\x75\x65\x73\164\40\143\x6f\x75\154\144\40\x6e\x6f\164\40\142\145\x20\x70\x65\162\x66\x6f\x72\x6d\145\144\x20\144\165\x65\40\164\157\x20\141\156\x20\x65\162\162\x6f\x72\40\157\156\40\164\150\145\40\x70\x61\162\x74\x20\157\x66\40\164\x68\145\x20\x72\145\x71\165\145\163\164\145\162\56";
            goto JS;
        case "\x52\x65\163\x70\157\x6e\144\x65\162":
            return "\124\x68\145\40\162\x65\x71\165\145\163\x74\40\143\157\x75\154\x64\40\156\x6f\164\40\142\145\40\160\x65\x72\146\157\x72\155\145\x64\40\144\x75\x65\x20\x74\x6f\x20\141\156\40\x65\x72\x72\157\x72\40\157\156\40\x74\150\145\x20\160\141\162\164\40\157\x66\40\x74\150\145\40\x53\x41\115\114\x20\162\x65\x73\x70\157\x6e\144\x65\162\40\157\162\x20\123\x41\115\x4c\40\141\165\x74\150\157\162\151\x74\171\x2e";
            goto JS;
        case "\126\145\x72\163\151\x6f\156\115\151\x73\x6d\141\x74\x63\x68":
            return "\x54\x68\x65\x20\x53\101\x4d\x4c\x20\162\145\x73\160\157\156\x64\145\x72\40\x63\157\165\x6c\144\x20\156\157\164\x20\x70\162\157\x63\145\163\163\40\164\x68\x65\x20\162\x65\x71\165\x65\163\164\x20\x62\145\143\x61\x75\x73\145\x20\x74\x68\145\40\x76\x65\x72\163\151\157\156\x20\157\x66\x20\x74\x68\x65\x20\162\x65\161\x75\145\163\x74\40\x6d\145\x73\x73\x61\x67\x65\x20\x77\141\x73\x20\151\x6e\x63\x6f\x72\x72\x65\x63\164\x2e";
            goto JS;
        default:
            return "\125\x6e\x6b\156\157\167\156";
    }
    GH:
    JS:
}
function mo_saml_register_widget()
{
    register_widget("\155\x6f\x5f\154\157\147\151\156\137\x77\x69\144");
}
function mo_saml_get_relay_state($sp)
{
    if (!($sp == "\164\x65\x73\x74\126\x61\x6c\x69\x64\x61\164\145" || $sp == "\x74\145\x73\x74\116\x65\167\x43\145\162\x74\x69\146\x69\x63\x61\164\x65")) {
        goto dQ;
    }
    return $sp;
    dQ:
    $ha = parse_url($sp, PHP_URL_PATH);
    if (!parse_url($sp, PHP_URL_QUERY)) {
        goto g8;
    }
    $G5 = parse_url($sp, PHP_URL_QUERY);
    $ha = $ha . "\77" . $G5;
    g8:
    if (!parse_url($sp, PHP_URL_FRAGMENT)) {
        goto pu;
    }
    $Fj = parse_url($sp, PHP_URL_FRAGMENT);
    $ha = $ha . "\x23" . $Fj;
    pu:
    return $ha;
}
function addLink($cR, $Zr)
{
    $Cp = "\74\x61\40\150\162\x65\146\75\x22" . $Zr . "\x22\x3e" . $cR . "\74\57\x61\x3e";
    return $Cp;
}
add_action("\167\x69\x64\147\x65\164\x73\x5f\x69\156\x69\x74", "\x6d\x6f\x5f\163\141\155\x6c\x5f\162\145\x67\151\163\164\145\x72\137\x77\x69\x64\147\145\x74");
add_action("\151\156\151\164", "\x6d\x6f\x5f\x6c\157\x67\x69\156\x5f\x76\141\154\x69\144\x61\x74\x65");
?>
