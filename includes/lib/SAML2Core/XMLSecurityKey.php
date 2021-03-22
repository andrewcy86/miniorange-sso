<?php


namespace RobRichards\XMLSecLibs;

use DOMElement;
use Exception;
class XMLSecurityKey
{
    const TRIPLEDES_CBC = "\x68\x74\164\160\x3a\57\57\x77\167\x77\56\167\63\x2e\157\162\x67\x2f\62\x30\60\61\57\60\64\57\170\x6d\x6c\145\x6e\143\43\164\x72\151\x70\x6c\x65\x64\x65\x73\x2d\143\x62\143";
    const AES128_CBC = "\x68\x74\164\x70\72\x2f\x2f\x77\x77\167\x2e\x77\x33\56\x6f\162\x67\57\x32\60\60\x31\57\x30\64\x2f\170\x6d\x6c\145\x6e\143\43\141\145\x73\61\x32\x38\x2d\x63\x62\143";
    const AES192_CBC = "\150\164\x74\x70\72\57\x2f\167\x77\167\56\x77\x33\56\x6f\x72\147\x2f\x32\x30\x30\x31\57\60\64\57\x78\155\154\145\156\x63\43\x61\x65\163\61\x39\62\55\x63\x62\143";
    const AES256_CBC = "\150\x74\x74\x70\72\x2f\x2f\x77\167\167\56\x77\63\x2e\157\162\147\x2f\x32\60\60\61\x2f\60\x34\57\170\x6d\154\x65\x6e\x63\43\141\145\x73\62\65\x36\x2d\143\x62\x63";
    const AES128_GCM = "\x68\x74\164\x70\72\57\57\x77\167\x77\x2e\x77\63\56\x6f\162\x67\57\x32\x30\60\x39\57\170\x6d\x6c\145\156\143\x31\61\43\141\x65\x73\61\62\70\55\x67\x63\155";
    const AES192_GCM = "\x68\x74\164\160\x3a\57\x2f\167\167\167\x2e\x77\63\x2e\157\162\x67\57\x32\x30\60\x39\57\x78\x6d\154\145\156\x63\x31\x31\x23\x61\x65\x73\x31\x39\62\x2d\x67\x63\x6d";
    const AES256_GCM = "\x68\164\164\x70\x3a\57\57\x77\x77\167\x2e\x77\x33\x2e\157\162\x67\x2f\x32\60\60\71\x2f\x78\155\154\x65\156\143\61\61\43\x61\x65\x73\62\x35\x36\x2d\x67\x63\155";
    const RSA_1_5 = "\x68\x74\164\x70\x3a\57\x2f\167\x77\x77\x2e\x77\63\56\157\162\147\x2f\x32\60\60\61\57\60\x34\57\170\x6d\x6c\x65\156\x63\x23\162\x73\x61\55\61\137\x35";
    const RSA_OAEP_MGF1P = "\x68\x74\x74\160\72\57\x2f\x77\x77\167\x2e\x77\63\x2e\157\x72\x67\57\x32\60\x30\x31\x2f\x30\64\57\170\x6d\154\x65\x6e\x63\43\162\x73\141\55\157\x61\145\160\55\x6d\147\146\x31\x70";
    const RSA_OAEP = "\x68\x74\x74\x70\72\x2f\x2f\167\x77\x77\x2e\167\63\56\157\x72\x67\57\62\60\x30\x39\x2f\170\155\x6c\145\x6e\143\x31\61\43\x72\x73\x61\x2d\x6f\141\145\160";
    const DSA_SHA1 = "\150\x74\x74\160\72\57\x2f\167\167\x77\x2e\167\x33\56\157\162\147\x2f\62\60\60\60\57\60\71\x2f\x78\155\154\x64\x73\151\x67\43\144\x73\141\x2d\163\150\141\61";
    const RSA_SHA1 = "\x68\x74\x74\x70\x3a\57\x2f\x77\167\167\x2e\167\63\x2e\x6f\x72\x67\x2f\62\60\60\x30\57\60\x39\x2f\170\x6d\154\x64\x73\x69\x67\43\x72\163\x61\55\x73\x68\x61\x31";
    const RSA_SHA256 = "\x68\x74\x74\x70\x3a\57\x2f\167\167\x77\x2e\x77\63\56\x6f\x72\147\x2f\x32\60\60\x31\57\60\x34\x2f\x78\x6d\154\144\163\151\147\55\155\157\x72\x65\43\162\x73\x61\x2d\163\x68\x61\x32\65\x36";
    const RSA_SHA384 = "\x68\164\164\160\x3a\57\x2f\167\x77\x77\x2e\x77\x33\x2e\x6f\x72\147\57\x32\x30\x30\x31\x2f\x30\64\x2f\170\155\154\x64\163\151\147\55\x6d\157\162\145\43\x72\163\x61\55\x73\150\141\63\70\64";
    const RSA_SHA512 = "\x68\164\164\x70\72\x2f\57\167\x77\x77\x2e\167\63\56\x6f\x72\147\57\62\60\60\x31\x2f\60\64\x2f\x78\x6d\154\x64\x73\151\147\55\x6d\x6f\162\145\43\x72\x73\x61\55\x73\150\141\x35\61\62";
    const HMAC_SHA1 = "\150\x74\164\x70\x3a\57\x2f\167\167\x77\56\167\63\x2e\157\162\x67\x2f\x32\x30\x30\60\x2f\x30\71\57\170\x6d\x6c\x64\x73\151\147\x23\x68\x6d\141\143\55\163\x68\141\61";
    const AUTHTAG_LENGTH = 16;
    private $cryptParams = array();
    public $type = 0;
    public $key = null;
    public $passphrase = '';
    public $iv = null;
    public $name = null;
    public $keyChain = null;
    public $isEncrypted = false;
    public $encryptedCtx = null;
    public $guid = null;
    private $x509Certificate = null;
    private $X509Thumbprint = null;
    public function __construct($YP, $hJ = null)
    {
        switch ($YP) {
            case self::TRIPLEDES_CBC:
                $this->cryptParams["\x6c\x69\142\162\x61\162\171"] = "\x6f\160\145\x6e\x73\163\x6c";
                $this->cryptParams["\143\151\160\150\x65\x72"] = "\x64\x65\x73\x2d\145\144\x65\x33\55\x63\142\x63";
                $this->cryptParams["\164\x79\160\145"] = "\x73\x79\x6d\x6d\x65\164\x72\151\143";
                $this->cryptParams["\155\145\x74\x68\x6f\144"] = "\x68\164\164\x70\x3a\57\x2f\x77\x77\x77\56\167\63\x2e\x6f\x72\147\57\62\60\x30\x31\x2f\x30\x34\57\x78\x6d\154\x65\x6e\x63\x23\x74\162\151\160\154\145\x64\145\163\55\143\x62\143";
                $this->cryptParams["\x6b\x65\171\163\x69\x7a\x65"] = 24;
                $this->cryptParams["\142\x6c\157\143\153\x73\x69\172\145"] = 8;
                goto Yi;
            case self::AES128_CBC:
                $this->cryptParams["\x6c\x69\x62\162\141\162\171"] = "\157\160\145\x6e\x73\163\x6c";
                $this->cryptParams["\143\x69\x70\x68\145\x72"] = "\x61\x65\x73\55\x31\62\70\55\143\x62\143";
                $this->cryptParams["\164\x79\x70\145"] = "\x73\171\155\x6d\x65\x74\x72\x69\143";
                $this->cryptParams["\x6d\145\x74\x68\x6f\x64"] = "\x68\164\x74\160\x3a\57\57\x77\x77\x77\x2e\167\63\56\x6f\162\x67\57\62\60\x30\x31\57\60\64\57\x78\155\154\145\x6e\x63\43\x61\145\x73\61\62\70\55\143\x62\x63";
                $this->cryptParams["\x6b\145\x79\163\x69\x7a\x65"] = 16;
                $this->cryptParams["\x62\x6c\x6f\143\x6b\163\151\x7a\145"] = 16;
                goto Yi;
            case self::AES192_CBC:
                $this->cryptParams["\x6c\151\x62\x72\141\162\171"] = "\157\160\x65\156\163\163\x6c";
                $this->cryptParams["\x63\151\160\150\145\x72"] = "\x61\145\163\x2d\x31\71\x32\55\x63\x62\143";
                $this->cryptParams["\164\171\160\x65"] = "\x73\x79\155\155\x65\x74\x72\151\x63";
                $this->cryptParams["\x6d\x65\x74\x68\157\144"] = "\150\x74\164\160\x3a\57\57\x77\167\167\x2e\167\x33\56\x6f\162\147\57\x32\60\x30\x31\x2f\x30\64\x2f\x78\x6d\x6c\x65\156\143\43\x61\x65\x73\61\x39\x32\55\x63\x62\143";
                $this->cryptParams["\153\145\x79\x73\x69\x7a\x65"] = 24;
                $this->cryptParams["\x62\154\x6f\143\x6b\163\x69\172\145"] = 16;
                goto Yi;
            case self::AES256_CBC:
                $this->cryptParams["\x6c\151\x62\x72\x61\x72\x79"] = "\157\x70\x65\156\x73\163\x6c";
                $this->cryptParams["\x63\151\160\x68\x65\162"] = "\141\x65\163\x2d\62\65\x36\x2d\x63\x62\x63";
                $this->cryptParams["\x74\171\x70\x65"] = "\163\171\155\x6d\x65\x74\x72\x69\143";
                $this->cryptParams["\155\145\x74\150\157\x64"] = "\150\x74\x74\x70\x3a\x2f\x2f\x77\167\167\56\x77\x33\56\x6f\162\x67\x2f\x32\60\60\61\57\60\x34\57\170\155\154\145\x6e\x63\43\x61\145\x73\62\65\x36\55\143\x62\x63";
                $this->cryptParams["\x6b\145\171\x73\151\172\145"] = 32;
                $this->cryptParams["\x62\154\157\143\153\163\x69\172\145"] = 16;
                goto Yi;
            case self::AES128_GCM:
                $this->cryptParams["\x6c\151\142\162\141\x72\x79"] = "\x6f\160\145\x6e\163\163\154";
                $this->cryptParams["\x63\x69\x70\150\x65\x72"] = "\x61\145\163\x2d\x31\62\70\55\x67\143\155";
                $this->cryptParams["\164\171\160\145"] = "\x73\171\155\x6d\145\164\162\x69\x63";
                $this->cryptParams["\x6d\145\x74\x68\157\144"] = "\150\164\164\160\x3a\57\57\167\x77\x77\x2e\167\x33\x2e\157\162\x67\x2f\62\60\x30\71\57\170\155\154\x65\156\x63\61\x31\x23\x61\x65\x73\61\x32\70\x2d\147\x63\x6d";
                $this->cryptParams["\153\145\171\x73\151\172\x65"] = 16;
                $this->cryptParams["\142\154\x6f\x63\153\x73\151\x7a\x65"] = 16;
                goto Yi;
            case self::AES192_GCM:
                $this->cryptParams["\154\151\142\x72\141\162\171"] = "\x6f\160\x65\x6e\x73\x73\154";
                $this->cryptParams["\143\x69\160\x68\145\162"] = "\x61\145\163\x2d\61\x39\62\55\x67\x63\155";
                $this->cryptParams["\164\171\x70\x65"] = "\x73\x79\x6d\x6d\145\164\x72\151\143";
                $this->cryptParams["\x6d\145\x74\150\157\144"] = "\150\x74\x74\160\x3a\57\x2f\167\167\x77\x2e\167\63\x2e\x6f\162\x67\57\x32\x30\x30\x39\x2f\170\155\154\145\156\x63\x31\x31\x23\x61\145\x73\61\x39\62\55\x67\x63\x6d";
                $this->cryptParams["\x6b\145\171\163\151\x7a\x65"] = 24;
                $this->cryptParams["\142\154\157\143\153\x73\x69\172\x65"] = 16;
                goto Yi;
            case self::AES256_GCM:
                $this->cryptParams["\x6c\151\142\x72\141\162\171"] = "\157\x70\145\x6e\x73\163\154";
                $this->cryptParams["\x63\x69\160\150\145\162"] = "\141\x65\163\x2d\62\x35\66\x2d\147\143\x6d";
                $this->cryptParams["\164\x79\x70\145"] = "\x73\x79\x6d\x6d\145\164\162\x69\143";
                $this->cryptParams["\x6d\145\x74\x68\157\x64"] = "\150\164\164\x70\x3a\x2f\57\167\167\167\56\167\x33\56\x6f\x72\147\x2f\62\60\x30\71\57\x78\x6d\x6c\145\156\x63\61\61\x23\x61\x65\x73\x32\65\66\x2d\147\x63\155";
                $this->cryptParams["\153\x65\171\x73\x69\172\145"] = 32;
                $this->cryptParams["\142\x6c\157\143\153\163\151\172\145"] = 16;
                goto Yi;
            case self::RSA_1_5:
                $this->cryptParams["\154\151\142\162\x61\x72\x79"] = "\x6f\x70\x65\x6e\x73\163\154";
                $this->cryptParams["\x70\x61\x64\x64\x69\x6e\147"] = OPENSSL_PKCS1_PADDING;
                $this->cryptParams["\x6d\x65\164\x68\157\x64"] = "\x68\164\164\160\x3a\x2f\57\167\167\x77\x2e\167\63\56\x6f\x72\x67\x2f\62\x30\60\x31\57\x30\x34\x2f\170\155\x6c\145\x6e\x63\43\162\163\141\55\x31\137\x35";
                if (!(is_array($hJ) && !empty($hJ["\x74\171\160\145"]))) {
                    goto uU;
                }
                if (!($hJ["\164\x79\160\145"] == "\160\x75\x62\154\151\143" || $hJ["\x74\x79\x70\145"] == "\x70\162\x69\x76\x61\x74\x65")) {
                    goto Cf;
                }
                $this->cryptParams["\x74\x79\160\145"] = $hJ["\x74\171\160\145"];
                goto Yi;
                Cf:
                uU:
                throw new Exception("\103\145\x72\164\x69\146\151\x63\x61\x74\x65\x20\42\164\x79\160\x65\x22\x20\50\x70\x72\151\x76\x61\164\x65\x2f\160\165\x62\x6c\151\143\x29\x20\x6d\165\163\164\x20\x62\145\40\160\141\163\x73\x65\x64\40\166\x69\x61\x20\x70\x61\162\x61\155\145\164\x65\x72\163");
            case self::RSA_OAEP_MGF1P:
                $this->cryptParams["\154\151\142\x72\141\162\171"] = "\157\x70\145\x6e\163\x73\154";
                $this->cryptParams["\160\141\144\144\x69\156\x67"] = OPENSSL_PKCS1_OAEP_PADDING;
                $this->cryptParams["\155\145\164\x68\157\144"] = "\150\164\x74\x70\x3a\57\x2f\x77\167\x77\56\167\x33\x2e\x6f\162\147\57\62\x30\x30\61\57\x30\64\57\170\155\x6c\x65\156\143\43\x72\x73\x61\x2d\157\x61\145\160\55\x6d\x67\x66\61\x70";
                $this->cryptParams["\x68\x61\163\x68"] = null;
                if (!(is_array($hJ) && !empty($hJ["\164\x79\160\x65"]))) {
                    goto Tk;
                }
                if (!($hJ["\x74\x79\x70\x65"] == "\x70\x75\x62\154\151\143" || $hJ["\164\171\x70\x65"] == "\x70\162\x69\166\141\164\x65")) {
                    goto vU;
                }
                $this->cryptParams["\164\171\160\145"] = $hJ["\164\171\x70\x65"];
                goto Yi;
                vU:
                Tk:
                throw new Exception("\x43\145\162\x74\x69\146\x69\143\x61\x74\x65\40\x22\164\171\160\145\x22\40\x28\160\162\x69\x76\141\x74\145\57\x70\165\x62\154\x69\143\51\x20\155\165\x73\x74\x20\142\145\x20\160\141\x73\x73\145\144\40\166\x69\x61\40\160\x61\x72\x61\x6d\145\164\x65\162\163");
            case self::RSA_OAEP:
                $this->cryptParams["\154\151\142\162\141\162\x79"] = "\x6f\x70\x65\156\163\x73\x6c";
                $this->cryptParams["\x70\141\144\144\x69\156\147"] = OPENSSL_PKCS1_OAEP_PADDING;
                $this->cryptParams["\155\145\x74\x68\157\x64"] = "\150\164\164\x70\x3a\x2f\x2f\x77\x77\167\56\167\x33\56\157\x72\x67\x2f\62\x30\60\x39\x2f\x78\x6d\154\145\156\x63\x31\x31\43\162\x73\141\x2d\x6f\x61\145\x70";
                $this->cryptParams["\x68\x61\x73\150"] = "\150\164\x74\x70\72\x2f\57\167\167\167\56\x77\x33\x2e\x6f\162\x67\x2f\62\x30\60\71\x2f\170\155\154\145\x6e\x63\x31\x31\x23\x6d\x67\x66\61\x73\x68\141\x31";
                if (!(is_array($hJ) && !empty($hJ["\164\171\x70\x65"]))) {
                    goto CT;
                }
                if (!($hJ["\164\x79\160\145"] == "\160\165\x62\x6c\151\x63" || $hJ["\x74\171\160\145"] == "\160\x72\151\x76\x61\164\145")) {
                    goto S0;
                }
                $this->cryptParams["\x74\171\160\x65"] = $hJ["\164\171\160\145"];
                goto Yi;
                S0:
                CT:
                throw new Exception("\103\x65\x72\x74\x69\146\151\x63\x61\164\x65\x20\42\164\x79\160\145\42\40\50\x70\162\x69\166\x61\x74\145\x2f\x70\165\142\154\x69\143\x29\40\x6d\165\163\x74\x20\142\x65\x20\x70\141\163\163\145\144\40\x76\151\141\x20\x70\x61\x72\x61\155\x65\x74\145\x72\x73");
            case self::RSA_SHA1:
                $this->cryptParams["\154\151\x62\x72\x61\x72\x79"] = "\x6f\x70\145\156\163\x73\x6c";
                $this->cryptParams["\155\x65\x74\x68\157\144"] = "\x68\164\164\x70\72\x2f\x2f\x77\167\167\56\x77\63\x2e\157\162\147\57\62\60\60\x30\57\x30\71\x2f\x78\155\x6c\144\x73\x69\x67\43\162\x73\x61\x2d\163\150\x61\x31";
                $this->cryptParams["\160\x61\x64\144\x69\x6e\x67"] = OPENSSL_PKCS1_PADDING;
                if (!(is_array($hJ) && !empty($hJ["\x74\171\x70\145"]))) {
                    goto W7;
                }
                if (!($hJ["\x74\x79\160\x65"] == "\x70\165\142\x6c\x69\x63" || $hJ["\164\x79\160\145"] == "\160\162\x69\166\141\x74\145")) {
                    goto JE;
                }
                $this->cryptParams["\x74\171\x70\145"] = $hJ["\x74\x79\160\x65"];
                goto Yi;
                JE:
                W7:
                throw new Exception("\103\145\x72\x74\151\146\x69\x63\x61\x74\x65\40\42\x74\x79\160\x65\x22\40\x28\x70\x72\x69\x76\x61\x74\145\57\x70\x75\142\154\151\x63\x29\x20\155\x75\163\x74\40\142\145\x20\160\x61\x73\x73\x65\x64\x20\x76\151\141\40\x70\141\x72\x61\155\145\164\x65\162\x73");
            case self::RSA_SHA256:
                $this->cryptParams["\x6c\151\142\162\x61\x72\x79"] = "\x6f\x70\x65\x6e\163\163\154";
                $this->cryptParams["\155\145\x74\150\157\144"] = "\x68\164\x74\x70\x3a\x2f\x2f\167\167\x77\56\x77\63\x2e\157\x72\x67\57\x32\x30\x30\x31\x2f\60\64\x2f\170\x6d\x6c\x64\163\151\147\55\155\157\x72\x65\43\x72\163\141\x2d\x73\x68\141\62\65\x36";
                $this->cryptParams["\160\x61\x64\144\151\156\147"] = OPENSSL_PKCS1_PADDING;
                $this->cryptParams["\x64\151\147\145\163\x74"] = "\x53\110\x41\x32\65\66";
                if (!(is_array($hJ) && !empty($hJ["\164\171\160\x65"]))) {
                    goto aW;
                }
                if (!($hJ["\x74\171\x70\x65"] == "\x70\165\142\154\x69\143" || $hJ["\164\171\160\x65"] == "\x70\162\151\x76\141\164\145")) {
                    goto ps;
                }
                $this->cryptParams["\164\171\x70\145"] = $hJ["\164\x79\x70\x65"];
                goto Yi;
                ps:
                aW:
                throw new Exception("\x43\x65\162\164\x69\x66\151\x63\141\x74\145\40\x22\164\x79\160\x65\x22\x20\50\x70\162\x69\x76\141\x74\x65\57\160\165\142\154\151\x63\51\40\155\165\x73\x74\40\x62\x65\40\160\x61\x73\x73\145\x64\40\166\151\141\x20\x70\141\x72\141\155\145\164\x65\x72\163");
            case self::RSA_SHA384:
                $this->cryptParams["\x6c\x69\142\x72\141\x72\x79"] = "\x6f\160\145\156\x73\163\154";
                $this->cryptParams["\x6d\145\x74\150\157\x64"] = "\x68\x74\x74\x70\72\x2f\57\167\167\167\56\x77\x33\x2e\x6f\x72\x67\57\x32\60\x30\x31\57\60\64\x2f\x78\x6d\154\x64\x73\x69\147\55\x6d\157\162\145\x23\162\x73\141\x2d\x73\150\141\x33\x38\x34";
                $this->cryptParams["\x70\x61\144\144\151\156\x67"] = OPENSSL_PKCS1_PADDING;
                $this->cryptParams["\x64\x69\147\x65\x73\164"] = "\x53\x48\101\63\x38\64";
                if (!(is_array($hJ) && !empty($hJ["\x74\x79\160\x65"]))) {
                    goto YB;
                }
                if (!($hJ["\164\171\160\145"] == "\160\165\x62\154\151\x63" || $hJ["\x74\171\160\x65"] == "\x70\162\151\x76\x61\164\x65")) {
                    goto Hr;
                }
                $this->cryptParams["\x74\x79\x70\145"] = $hJ["\164\x79\x70\x65"];
                goto Yi;
                Hr:
                YB:
                throw new Exception("\103\145\162\x74\151\146\x69\x63\x61\164\x65\40\x22\x74\171\x70\x65\42\x20\50\160\x72\x69\166\141\x74\x65\57\160\x75\142\154\151\x63\51\x20\x6d\x75\163\x74\x20\x62\145\x20\x70\x61\163\163\145\x64\40\x76\x69\141\x20\160\x61\x72\x61\x6d\x65\x74\x65\x72\x73");
            case self::RSA_SHA512:
                $this->cryptParams["\154\151\142\162\141\x72\171"] = "\x6f\x70\145\156\x73\163\154";
                $this->cryptParams["\155\145\164\x68\157\144"] = "\150\x74\x74\160\72\x2f\x2f\x77\167\167\x2e\x77\63\x2e\157\162\x67\x2f\x32\60\x30\x31\57\x30\64\x2f\170\x6d\x6c\x64\163\x69\147\x2d\x6d\x6f\x72\x65\x23\x72\163\141\x2d\163\150\x61\65\x31\x32";
                $this->cryptParams["\160\141\x64\144\x69\x6e\x67"] = OPENSSL_PKCS1_PADDING;
                $this->cryptParams["\144\x69\147\x65\x73\x74"] = "\123\x48\x41\65\61\x32";
                if (!(is_array($hJ) && !empty($hJ["\x74\x79\160\x65"]))) {
                    goto sg;
                }
                if (!($hJ["\x74\x79\x70\145"] == "\x70\165\142\154\151\143" || $hJ["\x74\x79\x70\x65"] == "\160\162\x69\166\141\x74\145")) {
                    goto Cd;
                }
                $this->cryptParams["\x74\x79\160\145"] = $hJ["\x74\x79\x70\145"];
                goto Yi;
                Cd:
                sg:
                throw new Exception("\103\145\162\x74\151\x66\x69\x63\x61\164\145\40\42\164\171\x70\x65\x22\x20\50\160\162\x69\x76\x61\x74\145\x2f\x70\165\142\154\151\143\51\x20\x6d\165\163\x74\40\142\x65\40\160\141\x73\163\145\x64\x20\x76\x69\141\40\x70\141\162\141\x6d\x65\x74\x65\162\163");
            case self::HMAC_SHA1:
                $this->cryptParams["\154\x69\x62\162\141\x72\x79"] = $YP;
                $this->cryptParams["\155\x65\x74\150\157\x64"] = "\150\164\164\160\x3a\x2f\57\x77\167\167\x2e\x77\63\x2e\x6f\162\147\57\x32\60\x30\x30\57\60\x39\x2f\170\x6d\154\x64\163\151\x67\43\150\155\141\x63\x2d\x73\x68\141\61";
                goto Yi;
            default:
                throw new Exception("\x49\x6e\166\x61\x6c\151\x64\x20\113\145\171\x20\124\171\160\145");
        }
        OR1:
        Yi:
        $this->type = $YP;
    }
    public function getSymmetricKeySize()
    {
        if (isset($this->cryptParams["\153\x65\x79\x73\151\x7a\x65"])) {
            goto fz;
        }
        return null;
        fz:
        return $this->cryptParams["\153\x65\x79\163\151\x7a\x65"];
    }
    public function generateSessionKey()
    {
        if (isset($this->cryptParams["\x6b\x65\171\163\x69\172\x65"])) {
            goto QN;
        }
        throw new Exception("\125\156\x6b\156\157\x77\x6e\x20\153\145\171\40\x73\151\x7a\x65\x20\146\157\x72\40\164\171\160\145\40\42" . $this->type . "\42\x2e");
        QN:
        $yN = $this->cryptParams["\153\x65\171\x73\151\172\x65"];
        $Xr = openssl_random_pseudo_bytes($yN);
        if (!($this->type === self::TRIPLEDES_CBC)) {
            goto fm;
        }
        $fL = 0;
        Fo:
        if (!($fL < strlen($Xr))) {
            goto J8;
        }
        $Mb = ord($Xr[$fL]) & 254;
        $XU = 1;
        $OO = 1;
        QW:
        if (!($OO < 8)) {
            goto zU;
        }
        $XU ^= $Mb >> $OO & 1;
        iF1:
        $OO++;
        goto QW;
        zU:
        $Mb |= $XU;
        $Xr[$fL] = chr($Mb);
        hu:
        $fL++;
        goto Fo;
        J8:
        fm:
        $this->key = $Xr;
        return $Xr;
    }
    public static function getRawThumbprint($l3)
    {
        $HX = explode("\12", $l3);
        $zF = '';
        $Vq = false;
        foreach ($HX as $Vy) {
            if (!$Vq) {
                goto xC;
            }
            if (!(strncmp($Vy, "\55\x2d\x2d\x2d\55\105\116\104\x20\103\x45\122\124\111\106\111\x43\x41\x54\x45", 20) == 0)) {
                goto p2;
            }
            goto wD;
            p2:
            $zF .= trim($Vy);
            goto lg;
            xC:
            if (!(strncmp($Vy, "\x2d\55\55\55\x2d\x42\105\107\111\x4e\40\x43\x45\x52\x54\x49\x46\111\103\101\124\105", 22) == 0)) {
                goto v0;
            }
            $Vq = true;
            v0:
            lg:
            Dq:
        }
        wD:
        if (empty($zF)) {
            goto jo;
        }
        return strtolower(sha1(base64_decode($zF)));
        jo:
        return null;
    }
    public function loadKey($Xr, $c2 = false, $aj = false)
    {
        if ($c2) {
            goto C3;
        }
        $this->key = $Xr;
        goto W6;
        C3:
        $this->key = file_get_contents($Xr);
        W6:
        if ($aj) {
            goto hp;
        }
        $this->x509Certificate = null;
        goto E0;
        hp:
        $this->key = openssl_x509_read($this->key);
        openssl_x509_export($this->key, $BF);
        $this->x509Certificate = $BF;
        $this->key = $BF;
        E0:
        if (!($this->cryptParams["\154\x69\x62\x72\141\162\x79"] == "\157\160\x65\156\163\163\154")) {
            goto Cq;
        }
        switch ($this->cryptParams["\164\x79\x70\x65"]) {
            case "\x70\x75\142\x6c\151\143":
                if (!$aj) {
                    goto rH;
                }
                $this->X509Thumbprint = self::getRawThumbprint($this->key);
                rH:
                $this->key = openssl_get_publickey($this->key);
                if ($this->key) {
                    goto r1;
                }
                throw new Exception("\x55\x6e\141\x62\x6c\145\40\164\157\40\145\x78\x74\x72\141\x63\x74\x20\160\165\x62\x6c\151\x63\x20\153\x65\x79");
                r1:
                goto SL;
            case "\x70\x72\x69\166\x61\164\145":
                $this->key = openssl_get_privatekey($this->key, $this->passphrase);
                goto SL;
            case "\163\171\x6d\x6d\x65\x74\x72\151\x63":
                if (!(strlen($this->key) < $this->cryptParams["\x6b\x65\171\163\x69\x7a\145"])) {
                    goto G1;
                }
                throw new Exception("\x4b\x65\x79\x20\x6d\165\163\x74\x20\x63\x6f\x6e\x74\x61\151\x6e\x20\x61\164\40\154\145\x61\x73\164\x20" . $this->cryptParams["\x6b\145\171\163\x69\172\145"] . "\x20\x63\150\x61\x72\x61\x63\x74\145\x72\163\40\146\157\x72\40\x74\150\151\x73\x20\143\x69\160\150\x65\x72\x2c\40\x63\x6f\x6e\164\141\151\156\163\40" . strlen($this->key));
                G1:
                goto SL;
            default:
                throw new Exception("\125\156\x6b\156\157\x77\156\40\x74\x79\x70\x65");
        }
        hr:
        SL:
        Cq:
    }
    private function padISO10126($zF, $Xc)
    {
        if (!($Xc > 256)) {
            goto R9;
        }
        throw new Exception("\102\x6c\x6f\x63\x6b\40\x73\x69\172\145\x20\x68\x69\147\x68\x65\162\x20\164\150\141\156\40\x32\x35\x36\40\156\157\164\x20\x61\x6c\x6c\x6f\167\145\x64");
        R9:
        $hO = $Xc - strlen($zF) % $Xc;
        $d_ = chr($hO);
        return $zF . str_repeat($d_, $hO);
    }
    private function unpadISO10126($zF)
    {
        $hO = substr($zF, -1);
        $AG = ord($hO);
        return substr($zF, 0, -$AG);
    }
    private function encryptSymmetric($zF)
    {
        $this->iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cryptParams["\x63\151\160\x68\x65\162"]));
        $wl = null;
        if (in_array($this->cryptParams["\143\151\160\x68\x65\x72"], array("\141\145\163\55\x31\x32\x38\x2d\x67\143\x6d", "\x61\x65\x73\55\61\x39\x32\55\147\x63\155", "\141\145\163\x2d\x32\65\x36\x2d\x67\143\x6d"))) {
            goto PG;
        }
        $zF = $this->padISO10126($zF, $this->cryptParams["\142\x6c\157\x63\x6b\163\x69\172\145"]);
        $Nm = openssl_encrypt($zF, $this->cryptParams["\x63\151\x70\x68\145\x72"], $this->key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $this->iv);
        goto dA;
        PG:
        if (!(version_compare(PHP_VERSION, "\67\56\x31\x2e\60") < 0)) {
            goto bl;
        }
        throw new Exception("\x50\110\120\40\67\x2e\61\56\60\x20\x69\163\x20\x72\x65\x71\165\x69\162\145\x64\x20\x74\157\40\x75\163\x65\40\101\105\x53\40\x47\x43\x4d\40\x61\154\147\157\162\151\x74\150\x6d\x73");
        bl:
        $wl = openssl_random_pseudo_bytes(self::AUTHTAG_LENGTH);
        $Nm = openssl_encrypt($zF, $this->cryptParams["\143\151\x70\150\x65\162"], $this->key, OPENSSL_RAW_DATA, $this->iv, $wl);
        dA:
        if (!(false === $Nm)) {
            goto T2u;
        }
        throw new Exception("\x46\141\151\x6c\165\162\x65\40\145\156\143\x72\171\160\164\151\156\147\40\x44\141\x74\141\x20\50\157\x70\145\156\x73\x73\x6c\x20\163\171\155\x6d\x65\164\162\151\143\51\40\x2d\x20" . openssl_error_string());
        T2u:
        return $this->iv . $Nm . $wl;
    }
    private function decryptSymmetric($zF)
    {
        $ZR = openssl_cipher_iv_length($this->cryptParams["\x63\x69\160\x68\x65\162"]);
        $this->iv = substr($zF, 0, $ZR);
        $zF = substr($zF, $ZR);
        $wl = null;
        if (in_array($this->cryptParams["\x63\x69\160\150\x65\162"], array("\141\145\163\55\61\x32\70\x2d\147\143\x6d", "\141\x65\x73\x2d\x31\x39\62\55\x67\143\155", "\x61\x65\x73\x2d\x32\65\x36\x2d\x67\x63\x6d"))) {
            goto qg2;
        }
        $AP = openssl_decrypt($zF, $this->cryptParams["\x63\151\x70\x68\x65\x72"], $this->key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $this->iv);
        goto KAA;
        qg2:
        if (!(version_compare(PHP_VERSION, "\67\x2e\x31\56\x30") < 0)) {
            goto E89;
        }
        throw new Exception("\x50\110\120\x20\x37\x2e\61\x2e\x30\x20\x69\x73\40\x72\145\161\165\x69\162\145\144\40\164\157\40\x75\163\145\x20\x41\105\x53\x20\107\x43\x4d\x20\x61\x6c\147\157\x72\x69\x74\150\155\x73");
        E89:
        $SU = 0 - self::AUTHTAG_LENGTH;
        $wl = substr($zF, $SU);
        $zF = substr($zF, 0, $SU);
        $AP = openssl_decrypt($zF, $this->cryptParams["\143\x69\160\150\x65\162"], $this->key, OPENSSL_RAW_DATA, $this->iv, $wl);
        KAA:
        if (!(false === $AP)) {
            goto Dn4;
        }
        throw new Exception("\106\x61\x69\154\x75\x72\x65\x20\x64\145\x63\162\x79\160\164\x69\x6e\147\x20\104\x61\x74\141\40\50\157\x70\145\156\163\x73\x6c\40\163\171\x6d\x6d\x65\164\162\x69\x63\x29\x20\x2d\40" . openssl_error_string());
        Dn4:
        return null !== $wl ? $AP : $this->unpadISO10126($AP);
    }
    private function encryptPublic($zF)
    {
        if (openssl_public_encrypt($zF, $Nm, $this->key, $this->cryptParams["\x70\141\x64\x64\x69\156\147"])) {
            goto hNQ;
        }
        throw new Exception("\x46\x61\x69\154\x75\162\x65\x20\x65\156\x63\x72\x79\x70\x74\x69\x6e\x67\x20\104\x61\164\141\40\50\157\160\145\156\x73\x73\154\40\160\x75\142\154\151\143\x29\x20\x2d\40" . openssl_error_string());
        hNQ:
        return $Nm;
    }
    private function decryptPublic($zF)
    {
        if (openssl_public_decrypt($zF, $AP, $this->key, $this->cryptParams["\x70\141\144\x64\151\x6e\147"])) {
            goto DS9;
        }
        throw new Exception("\x46\x61\x69\x6c\165\162\145\x20\144\x65\143\162\171\160\x74\151\156\147\40\104\141\x74\x61\x20\50\x6f\160\x65\x6e\163\163\154\40\x70\165\142\x6c\151\x63\x29\x20\55\x20" . openssl_error_string());
        DS9:
        return $AP;
    }
    private function encryptPrivate($zF)
    {
        if (openssl_private_encrypt($zF, $Nm, $this->key, $this->cryptParams["\160\141\144\x64\151\x6e\147"])) {
            goto SZG;
        }
        throw new Exception("\106\x61\x69\154\x75\x72\x65\x20\x65\156\143\162\x79\x70\x74\151\156\x67\x20\x44\x61\164\141\40\x28\x6f\x70\145\156\163\x73\154\x20\160\162\x69\x76\x61\164\145\x29\x20\55\x20" . openssl_error_string());
        SZG:
        return $Nm;
    }
    private function decryptPrivate($zF)
    {
        if (openssl_private_decrypt($zF, $AP, $this->key, $this->cryptParams["\x70\141\144\x64\x69\156\x67"])) {
            goto k4j;
        }
        throw new Exception("\x46\141\151\x6c\x75\162\x65\40\144\x65\x63\x72\171\x70\x74\151\x6e\147\40\104\141\164\x61\40\50\x6f\160\x65\x6e\x73\x73\154\40\x70\x72\151\166\141\x74\x65\51\40\55\x20" . openssl_error_string());
        k4j:
        return $AP;
    }
    private function signOpenSSL($zF)
    {
        $u2 = OPENSSL_ALGO_SHA1;
        if (empty($this->cryptParams["\x64\151\147\145\x73\x74"])) {
            goto ZvK;
        }
        $u2 = $this->cryptParams["\x64\x69\x67\145\163\164"];
        ZvK:
        if (openssl_sign($zF, $QR, $this->key, $u2)) {
            goto EKO;
        }
        throw new Exception("\x46\x61\151\154\165\162\x65\x20\123\151\147\x6e\x69\x6e\147\x20\x44\x61\164\141\x3a\x20" . openssl_error_string() . "\x20\55\x20" . $u2);
        EKO:
        return $QR;
    }
    private function verifyOpenSSL($zF, $QR)
    {
        $u2 = OPENSSL_ALGO_SHA1;
        if (empty($this->cryptParams["\144\151\x67\x65\163\164"])) {
            goto eBs;
        }
        $u2 = $this->cryptParams["\144\x69\x67\145\163\x74"];
        eBs:
        return openssl_verify($zF, $QR, $this->key, $u2);
    }
    public function encryptData($zF)
    {
        if (!($this->cryptParams["\154\151\x62\x72\141\162\171"] === "\157\160\145\x6e\163\163\154")) {
            goto SOl;
        }
        switch ($this->cryptParams["\x74\171\160\145"]) {
            case "\163\171\155\155\x65\x74\162\x69\x63":
                return $this->encryptSymmetric($zF);
            case "\160\x75\142\x6c\151\x63":
                return $this->encryptPublic($zF);
            case "\x70\x72\x69\166\141\164\145":
                return $this->encryptPrivate($zF);
        }
        S9_:
        YdV:
        SOl:
    }
    public function decryptData($zF)
    {
        if (!($this->cryptParams["\154\x69\x62\x72\x61\162\x79"] === "\157\160\x65\x6e\x73\163\x6c")) {
            goto OQR;
        }
        switch ($this->cryptParams["\x74\171\160\145"]) {
            case "\x73\171\155\x6d\x65\164\x72\151\143":
                return $this->decryptSymmetric($zF);
            case "\x70\165\x62\154\151\x63":
                return $this->decryptPublic($zF);
            case "\x70\162\x69\166\141\x74\145":
                return $this->decryptPrivate($zF);
        }
        HjX:
        qII:
        OQR:
    }
    public function signData($zF)
    {
        switch ($this->cryptParams["\154\x69\x62\162\x61\x72\171"]) {
            case "\x6f\160\x65\156\163\163\x6c":
                return $this->signOpenSSL($zF);
            case self::HMAC_SHA1:
                return hash_hmac("\x73\150\141\61", $zF, $this->key, true);
        }
        MB1:
        a5x:
    }
    public function verifySignature($zF, $QR)
    {
        switch ($this->cryptParams["\154\x69\x62\162\x61\x72\x79"]) {
            case "\157\x70\145\x6e\163\163\154":
                return $this->verifyOpenSSL($zF, $QR);
            case self::HMAC_SHA1:
                $n7 = hash_hmac("\x73\x68\141\x31", $zF, $this->key, true);
                return strcmp($QR, $n7) == 0;
        }
        ItW:
        jEp:
    }
    public function getAlgorith()
    {
        return $this->getAlgorithm();
    }
    public function getAlgorithm()
    {
        return $this->cryptParams["\155\145\x74\x68\x6f\x64"];
    }
    public static function makeAsnSegment($YP, $xF)
    {
        switch ($YP) {
            case 2:
                if (!(ord($xF) > 127)) {
                    goto I48;
                }
                $xF = chr(0) . $xF;
                I48:
                goto ccf;
            case 3:
                $xF = chr(0) . $xF;
                goto ccf;
        }
        kOJ:
        ccf:
        $oi = strlen($xF);
        if ($oi < 128) {
            goto RQk;
        }
        if ($oi < 256) {
            goto ETI;
        }
        if ($oi < 65536) {
            goto B1x;
        }
        $uy = null;
        goto EJn;
        B1x:
        $uy = sprintf("\45\x63\x25\143\45\x63\x25\143\x25\163", $YP, 130, $oi / 256, $oi % 256, $xF);
        EJn:
        goto Fo3;
        ETI:
        $uy = sprintf("\x25\x63\x25\143\x25\143\45\x73", $YP, 129, $oi, $xF);
        Fo3:
        goto JHl;
        RQk:
        $uy = sprintf("\x25\143\x25\x63\45\x73", $YP, $oi, $xF);
        JHl:
        return $uy;
    }
    public static function convertRSA($r8, $Tr)
    {
        $Qn = self::makeAsnSegment(2, $Tr);
        $Nq = self::makeAsnSegment(2, $r8);
        $p8 = self::makeAsnSegment(48, $Nq . $Qn);
        $cm = self::makeAsnSegment(3, $p8);
        $K5 = pack("\x48\52", "\63\x30\x30\104\60\x36\60\x39\x32\x41\x38\66\64\x38\x38\x36\106\67\60\x44\x30\x31\60\61\x30\x31\60\65\60\x30");
        $sH = self::makeAsnSegment(48, $K5 . $cm);
        $p_ = base64_encode($sH);
        $Ev = "\55\x2d\x2d\x2d\55\x42\x45\x47\x49\x4e\40\x50\125\x42\x4c\x49\103\40\113\105\131\x2d\55\55\x2d\x2d\xa";
        $SU = 0;
        Vpy:
        if (!($t0 = substr($p_, $SU, 64))) {
            goto Am8;
        }
        $Ev = $Ev . $t0 . "\12";
        $SU += 64;
        goto Vpy;
        Am8:
        return $Ev . "\55\x2d\x2d\55\x2d\x45\x4e\x44\40\x50\125\102\x4c\111\x43\40\x4b\105\x59\55\x2d\55\x2d\55\12";
    }
    public function serializeKey($JN)
    {
    }
    public function getX509Certificate()
    {
        return $this->x509Certificate;
    }
    public function getX509Thumbprint()
    {
        return $this->X509Thumbprint;
    }
    public static function fromEncryptedKeyElement(DOMElement $yV)
    {
        $Gb = new XMLSecEnc();
        $Gb->setNode($yV);
        if ($gR = $Gb->locateKey()) {
            goto HHf;
        }
        throw new Exception("\x55\156\x61\x62\154\145\x20\x74\157\40\x6c\157\143\x61\164\x65\40\x61\154\147\157\162\151\x74\x68\155\x20\146\x6f\x72\x20\164\x68\151\163\x20\x45\x6e\143\x72\171\x70\x74\145\x64\x20\113\x65\171");
        HHf:
        $gR->isEncrypted = true;
        $gR->encryptedCtx = $Gb;
        XMLSecEnc::staticLocateKeyInfo($gR, $yV);
        return $gR;
    }
}
