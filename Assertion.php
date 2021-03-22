<?php


include_once "\125\164\x69\154\x69\x74\151\145\163\x2e\160\150\160";
include_once "\x78\x6d\154\163\145\143\x6c\x69\142\x73\x2e\x70\x68\x70";
use RobRichards\XMLSecLibs\XMLSecurityKey;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecEnc;
class SAML2SPAssertion
{
    private $id;
    private $issueInstant;
    private $issuer;
    private $nameId;
    private $encryptedNameId;
    private $encryptedAttribute;
    private $encryptionKey;
    private $notBefore;
    private $notOnOrAfter;
    private $validAudiences;
    private $sessionNotOnOrAfter;
    private $sessionIndex;
    private $authnInstant;
    private $authnContextClassRef;
    private $authnContextDecl;
    private $authnContextDeclRef;
    private $AuthenticatingAuthority;
    private $attributes;
    private $nameFormat;
    private $signatureKey;
    private $certificates;
    private $signatureData;
    private $requiredEncAttributes;
    private $SubjectConfirmation;
    protected $wasSignedAtConstruction = FALSE;
    public function __construct(DOMElement $pb = NULL, $v1)
    {
        $this->id = SAMLSPUtilities::generateId();
        $this->issueInstant = SAMLSPUtilities::generateTimestamp();
        $this->issuer = '';
        $this->authnInstant = SAMLSPUtilities::generateTimestamp();
        $this->attributes = array();
        $this->nameFormat = "\x75\x72\156\x3a\157\141\x73\x69\x73\72\x6e\x61\x6d\x65\163\72\164\143\72\123\101\115\x4c\72\61\x2e\x31\x3a\x6e\141\x6d\145\151\144\55\x66\x6f\x72\155\141\x74\72\165\156\163\x70\145\x63\151\x66\151\145\x64";
        $this->certificates = array();
        $this->AuthenticatingAuthority = array();
        $this->SubjectConfirmation = array();
        if (!($pb === NULL)) {
            goto Qz;
        }
        return;
        Qz:
        if (!($pb->localName === "\105\156\143\x72\171\x70\164\145\x64\x41\x73\x73\x65\x72\x74\151\157\x6e")) {
            goto DJ;
        }
        $zF = SAMLSPUtilities::xpQuery($pb, "\56\x2f\170\x65\156\x63\x3a\105\156\143\x72\171\160\164\145\144\x44\141\x74\x61");
        $oj = SAMLSPUtilities::xpQuery($pb, "\57\57\x2a\x5b\154\157\x63\x61\154\55\x6e\x61\x6d\x65\x28\51\75\x27\105\x6e\x63\162\x79\x70\164\x65\x64\x4b\x65\x79\x27\x5d\57\52\133\154\x6f\143\141\x6c\x2d\156\141\155\x65\x28\51\x3d\47\105\156\x63\x72\171\160\164\151\x6f\x6e\115\145\164\x68\157\144\x27\135\57\x40\x41\154\147\x6f\x72\x69\164\150\x6d");
        $Vb = $oj[0]->value;
        $u2 = SAMLSPUtilities::getEncryptionAlgorithm($Vb);
        if (count($zF) === 0) {
            goto iP;
        }
        if (count($zF) > 1) {
            goto JF;
        }
        goto O8;
        iP:
        throw new Exception("\115\151\163\163\151\156\147\40\145\x6e\x63\162\171\160\164\x65\x64\x20\144\x61\164\141\40\x69\156\x20\74\x73\x61\x6d\x6c\x3a\105\x6e\x63\x72\171\160\164\x65\144\101\x73\163\x65\x72\164\x69\x6f\x6e\76\x2e");
        goto O8;
        JF:
        throw new Exception("\115\x6f\162\145\40\164\150\141\156\40\x6f\x6e\145\x20\x65\156\143\162\x79\x70\x74\145\x64\40\144\x61\x74\141\40\x65\154\x65\x6d\x65\156\164\x20\x69\x6e\x20\74\x73\x61\x6d\154\x3a\105\x6e\143\x72\171\x70\x74\145\x64\101\163\163\x65\x72\x74\151\x6f\x6e\76\56");
        O8:
        $Xr = new XMLSecurityKey($u2, array("\164\171\160\x65" => "\x70\x72\x69\x76\141\x74\145"));
        $Xr->loadKey($v1, FALSE);
        $ey = array();
        $pb = SAMLSPUtilities::decryptElement($zF[0], $Xr, $ey);
        DJ:
        if ($pb->hasAttribute("\x49\104")) {
            goto f4;
        }
        throw new Exception("\x4d\x69\x73\x73\151\x6e\x67\40\x49\104\x20\141\x74\x74\162\151\142\x75\x74\x65\40\x6f\156\x20\x53\x41\115\114\x20\141\163\x73\x65\162\164\x69\x6f\x6e\x2e");
        f4:
        $this->id = $pb->getAttribute("\111\104");
        if (!($pb->getAttribute("\126\145\x72\x73\151\157\156") !== "\x32\56\x30")) {
            goto eV;
        }
        throw new Exception("\125\156\163\165\160\160\x6f\162\164\145\144\40\166\145\x72\x73\x69\x6f\x6e\72\40" . $pb->getAttribute("\126\x65\x72\x73\x69\157\x6e"));
        eV:
        $this->issueInstant = SAMLSPUtilities::xsDateTimeToTimestamp($pb->getAttribute("\x49\163\x73\x75\145\111\x6e\163\x74\141\x6e\164"));
        $VB = SAMLSPUtilities::xpQuery($pb, "\x2e\x2f\163\141\x6d\154\x5f\141\x73\163\145\x72\164\x69\x6f\x6e\72\111\x73\x73\x75\x65\162");
        if (!empty($VB)) {
            goto ab;
        }
        throw new Exception("\115\151\163\163\x69\156\147\x20\x3c\x73\141\155\x6c\x3a\x49\163\x73\x75\x65\162\76\40\151\156\40\141\x73\x73\145\162\x74\151\x6f\156\56");
        ab:
        $this->issuer = trim($VB[0]->textContent);
        $this->parseConditions($pb);
        $this->parseAuthnStatement($pb);
        $this->parseAttributes($pb);
        $this->parseEncryptedAttributes($pb);
        $this->parseSignature($pb);
        $this->parseSubject($pb);
    }
    private function parseSubject(DOMElement $pb)
    {
        $MF = SAMLSPUtilities::xpQuery($pb, "\56\57\x73\141\x6d\x6c\137\141\x73\x73\x65\162\x74\151\157\156\72\x53\165\142\152\x65\143\164");
        if (empty($MF)) {
            goto Ox;
        }
        if (count($MF) > 1) {
            goto s5;
        }
        goto UW;
        Ox:
        return;
        goto UW;
        s5:
        throw new Exception("\115\x6f\162\x65\40\164\150\141\156\40\157\x6e\x65\40\74\x73\x61\x6d\x6c\x3a\x53\165\x62\152\x65\x63\x74\x3e\40\x69\156\x20\x3c\x73\141\155\x6c\x3a\101\163\x73\x65\x72\x74\x69\x6f\156\76\x2e");
        UW:
        $MF = $MF[0];
        $A3 = SAMLSPUtilities::xpQuery($MF, "\x2e\57\163\x61\x6d\x6c\x5f\x61\163\163\x65\162\164\151\157\x6e\x3a\116\x61\155\145\x49\104\x20\174\x20\56\57\x73\141\x6d\154\x5f\141\x73\163\x65\162\x74\151\x6f\156\x3a\x45\156\x63\162\171\160\x74\145\x64\x49\104\x2f\x78\x65\156\143\x3a\x45\x6e\x63\x72\171\x70\164\145\x64\x44\141\x74\x61");
        if (empty($A3)) {
            goto YP;
        }
        if (count($A3) > 1) {
            goto yr;
        }
        goto np;
        YP:
        if ($_POST["\122\145\154\141\x79\123\164\141\164\145"] == "\164\x65\163\x74\126\x61\x6c\151\144\x61\x74\x65" or $_POST["\x52\x65\x6c\x61\171\123\164\141\x74\x65"] == "\x74\x65\163\x74\116\145\x77\x43\145\162\x74\x69\146\x69\x63\141\x74\145") {
            goto Of;
        }
        wp_die("\127\145\40\x63\157\165\x6c\x64\x20\x6e\157\164\40\x73\151\x67\156\x20\171\157\x75\x20\151\156\x2e\x20\x50\x6c\x65\141\x73\145\40\143\x6f\x6e\x74\141\x63\x74\40\x79\157\165\162\40\x61\144\155\x69\156\x69\x73\x74\x72\141\164\157\x72");
        goto VW;
        Of:
        echo "\x3c\144\x69\166\x20\x73\164\x79\x6c\145\x3d\x22\x66\157\156\164\x2d\146\141\x6d\151\x6c\171\x3a\103\x61\x6c\151\x62\x72\x69\x3b\x70\141\x64\x64\151\x6e\147\72\x30\x20\63\45\73\42\x3e";
        echo "\74\144\151\x76\40\x73\x74\171\x6c\145\x3d\42\143\157\154\x6f\162\72\x20\x23\x61\71\64\x34\x34\62\73\x62\x61\143\x6b\147\162\x6f\x75\156\x64\x2d\x63\157\x6c\157\x72\72\x20\43\x66\62\144\x65\x64\145\73\160\141\x64\144\x69\156\147\72\x20\x31\65\x70\x78\73\155\141\162\x67\x69\x6e\55\142\x6f\164\164\x6f\x6d\72\x20\62\x30\x70\170\x3b\164\145\170\x74\55\141\x6c\151\147\x6e\72\x63\145\156\x74\145\162\x3b\x62\157\162\x64\x65\x72\x3a\x31\x70\170\x20\163\157\154\151\144\x20\43\x45\x36\102\x33\102\62\x3b\x66\x6f\x6e\164\x2d\x73\x69\172\145\x3a\x31\x38\x70\164\73\x22\76\x20\x45\122\x52\x4f\122\74\57\144\151\x76\x3e\15\xa\40\40\40\40\x20\40\x20\40\x20\x20\40\74\144\x69\x76\40\163\x74\x79\154\x65\x3d\x22\x63\x6f\x6c\157\162\72\x20\43\141\x39\64\x34\x34\62\x3b\146\x6f\x6e\164\55\163\151\x7a\x65\x3a\x31\64\x70\164\x3b\x20\x6d\x61\x72\x67\x69\x6e\x2d\142\x6f\164\164\157\155\x3a\x32\x30\x70\x78\73\x22\76\x3c\160\76\74\163\x74\x72\157\156\x67\x3e\105\x72\x72\157\162\72\x20\x3c\x2f\x73\164\x72\x6f\x6e\x67\76\115\x69\x73\163\151\x6e\x67\x20\x20\116\x61\x6d\145\111\x44\x20\x69\x6e\40\123\101\x4d\114\40\122\x65\x73\x70\x6f\156\163\145\56\x3c\x2f\x70\76\15\xa\x20\x20\40\40\40\40\40\40\x20\x20\x20\40\40\x20\40\x20\x3c\x70\76\120\154\x65\141\x73\x65\x20\x63\x6f\156\x74\x61\143\x74\x20\x79\157\165\x72\x20\x61\144\155\151\x6e\x69\163\164\x72\x61\164\157\x72\x20\141\x6e\144\x20\162\145\x70\x6f\162\164\40\x74\x68\145\x20\x66\157\x6c\154\x6f\167\x69\x6e\x67\40\145\162\x72\x6f\x72\x3a\74\57\160\76\xd\12\x20\40\40\40\x20\x20\x20\x20\x20\x20\40\x20\x20\x20\x20\x20\x3c\x70\76\74\x73\x74\162\x6f\156\x67\x3e\120\157\x73\163\x69\x62\x6c\145\x20\103\x61\165\x73\145\72\x3c\57\x73\x74\162\157\x6e\x67\76\40\x4e\141\x6d\145\111\104\x20\x6e\x6f\164\x20\146\x6f\165\156\x64\x20\x69\x6e\40\x53\x41\x4d\x4c\x20\122\145\x73\x70\157\x6e\163\145\40\163\x75\x62\x6a\x65\x63\164\56\74\x2f\160\x3e\15\12\x20\x20\x20\40\40\40\x20\x20\40\x20\x20\x20\40\40\40\x20\x3c\x2f\144\151\x76\76\15\12\40\40\x20\x20\x20\40\x20\x20\x20\40\x20\40\x20\x20\x20\x20\x3c\x64\151\166\40\163\164\171\x6c\145\75\42\155\x61\162\x67\x69\156\x3a\63\45\73\144\x69\x73\160\154\141\x79\x3a\x62\x6c\157\x63\x6b\73\164\145\170\164\x2d\141\154\151\x67\x6e\72\x63\x65\156\x74\x65\x72\x3b\42\x3e\15\xa\x20\40\x20\x20\40\40\x20\x20\40\40\40\40\x20\x20\40\xd\xa\40\x20\x20\40\40\40\40\x20\40\40\x20\x20\40\x20\x20\40\x3c\144\151\166\x20\x73\x74\x79\154\145\x3d\x22\x6d\x61\162\x67\151\156\72\x33\45\73\x64\151\x73\160\154\141\x79\72\142\x6c\x6f\x63\153\73\164\145\170\164\x2d\x61\154\x69\x67\x6e\x3a\143\x65\156\x74\x65\x72\x3b\42\x3e\74\x69\156\x70\x75\x74\x20\x73\164\x79\154\x65\x3d\x22\x70\x61\144\x64\151\156\x67\72\61\x25\x3b\x77\151\x64\x74\150\x3a\61\x30\x30\x70\x78\73\142\141\x63\153\x67\162\x6f\165\156\144\x3a\40\43\x30\60\x39\61\103\x44\40\x6e\157\x6e\x65\x20\x72\145\160\145\141\164\40\x73\x63\162\x6f\x6c\154\x20\60\45\x20\x30\x25\73\x63\x75\x72\x73\157\162\x3a\x20\x70\x6f\151\x6e\164\145\162\x3b\146\157\x6e\x74\55\163\x69\172\145\72\61\65\160\170\x3b\142\x6f\162\144\145\x72\x2d\167\151\x64\164\150\x3a\40\x31\x70\170\73\142\x6f\162\x64\x65\162\55\x73\x74\x79\x6c\x65\x3a\x20\x73\157\x6c\151\144\73\142\157\x72\144\x65\162\x2d\x72\141\144\151\165\x73\72\40\x33\x70\x78\73\167\150\151\x74\x65\55\x73\160\x61\x63\145\x3a\x20\x6e\157\167\x72\141\x70\73\x62\x6f\170\x2d\163\x69\x7a\x69\156\x67\x3a\x20\142\x6f\x72\x64\145\x72\55\142\157\170\x3b\x62\x6f\x72\144\145\x72\x2d\x63\x6f\154\x6f\x72\x3a\40\43\x30\60\67\63\x41\x41\x3b\142\157\170\55\163\x68\141\144\157\167\72\x20\60\160\170\40\61\160\170\x20\x30\x70\170\40\162\147\x62\141\50\61\62\60\x2c\x20\x32\x30\x30\x2c\40\62\x33\x30\x2c\x20\x30\56\66\x29\40\151\156\x73\x65\164\x3b\143\x6f\x6c\x6f\162\x3a\40\43\106\x46\106\73\x22\164\x79\x70\145\75\42\x62\x75\164\164\x6f\156\x22\x20\166\x61\x6c\165\x65\75\x22\104\157\x6e\x65\42\x20\x6f\x6e\103\154\x69\x63\153\x3d\x22\x73\x65\154\x66\x2e\x63\154\157\x73\x65\x28\x29\73\42\x3e\74\57\x64\x69\166\76";
        die;
        VW:
        goto np;
        yr:
        throw new Exception("\115\157\162\145\40\164\150\x61\x6e\40\x6f\x6e\145\40\74\x73\141\x6d\154\x3a\116\x61\x6d\x65\x49\x44\76\40\x6f\x72\x20\74\x73\141\x6d\x6c\x3a\x45\156\x63\x72\171\160\x74\x65\x64\104\76\x20\x69\156\x20\74\163\141\155\x6c\x3a\x53\x75\142\x6a\x65\x63\164\x3e\56");
        np:
        $A3 = $A3[0];
        if ($A3->localName === "\x45\x6e\x63\162\171\x70\x74\x65\144\104\x61\164\141") {
            goto XG;
        }
        $this->nameId = SAMLSPUtilities::parseNameId($A3);
        goto w7;
        XG:
        $this->encryptedNameId = $A3;
        w7:
    }
    private function parseConditions(DOMElement $pb)
    {
        $JP = SAMLSPUtilities::xpQuery($pb, "\x2e\x2f\x73\x61\155\154\x5f\141\163\x73\145\162\164\x69\157\156\x3a\103\x6f\156\x64\151\x74\151\x6f\156\x73");
        if (empty($JP)) {
            goto Du;
        }
        if (count($JP) > 1) {
            goto Hv;
        }
        goto P0;
        Du:
        return;
        goto P0;
        Hv:
        throw new Exception("\115\157\162\145\x20\x74\x68\x61\156\x20\x6f\156\x65\40\74\x73\141\x6d\154\72\103\157\156\144\x69\164\x69\x6f\x6e\x73\x3e\x20\151\156\40\x3c\163\x61\x6d\154\x3a\101\163\163\x65\162\164\x69\157\x6e\76\56");
        P0:
        $JP = $JP[0];
        if (!$JP->hasAttribute("\x4e\x6f\x74\x42\x65\146\x6f\x72\145")) {
            goto mV;
        }
        $o2 = SAMLSPUtilities::xsDateTimeToTimestamp($JP->getAttribute("\116\x6f\x74\x42\145\x66\157\162\145"));
        if (!($this->notBefore === NULL || $this->notBefore < $o2)) {
            goto je;
        }
        $this->notBefore = $o2;
        je:
        mV:
        if (!$JP->hasAttribute("\116\157\x74\x4f\156\117\x72\101\x66\164\x65\x72")) {
            goto EQ;
        }
        $i1 = SAMLSPUtilities::xsDateTimeToTimestamp($JP->getAttribute("\116\157\164\x4f\156\x4f\162\101\x66\x74\x65\x72"));
        if (!($this->notOnOrAfter === NULL || $this->notOnOrAfter > $i1)) {
            goto Qt;
        }
        $this->notOnOrAfter = $i1;
        Qt:
        EQ:
        $nT = $JP->firstChild;
        tJ:
        if (!($nT !== NULL)) {
            goto zp;
        }
        if (!$nT instanceof DOMText) {
            goto fM;
        }
        goto Ae;
        fM:
        if (!($nT->namespaceURI !== "\165\x72\156\x3a\157\141\163\151\x73\72\x6e\141\x6d\145\x73\x3a\164\143\72\x53\x41\115\x4c\72\x32\x2e\x30\x3a\x61\x73\163\x65\x72\x74\151\157\x6e")) {
            goto lk;
        }
        throw new Exception("\125\156\153\x6e\x6f\167\x6e\x20\x6e\141\x6d\145\163\x70\x61\x63\x65\40\x6f\x66\40\x63\157\156\x64\151\164\x69\157\156\72\40" . var_export($nT->namespaceURI, TRUE));
        lk:
        switch ($nT->localName) {
            case "\x41\165\144\x69\145\156\143\x65\x52\145\163\164\x72\x69\x63\164\151\x6f\x6e":
                $ka = SAMLSPUtilities::extractStrings($nT, "\x75\x72\156\x3a\157\141\x73\x69\x73\x3a\x6e\141\x6d\x65\163\x3a\x74\x63\72\x53\x41\x4d\114\x3a\62\x2e\x30\72\x61\163\163\x65\x72\x74\x69\x6f\156", "\x41\x75\x64\151\x65\156\143\x65");
                if ($this->validAudiences === NULL) {
                    goto Lq;
                }
                $this->validAudiences = array_intersect($this->validAudiences, $ka);
                goto Ha;
                Lq:
                $this->validAudiences = $ka;
                Ha:
                goto Ir;
            case "\x4f\x6e\x65\x54\151\x6d\145\x55\163\x65":
                goto Ir;
            case "\x50\x72\x6f\170\x79\122\x65\163\164\162\x69\x63\x74\151\157\x6e":
                goto Ir;
            default:
                throw new Exception("\125\156\x6b\156\x6f\x77\156\40\143\157\x6e\144\x69\164\151\157\156\72\x20" . var_export($nT->localName, TRUE));
        }
        ok:
        Ir:
        Ae:
        $nT = $nT->nextSibling;
        goto tJ;
        zp:
    }
    private function parseAuthnStatement(DOMElement $pb)
    {
        $KC = SAMLSPUtilities::xpQuery($pb, "\56\57\163\x61\155\x6c\137\x61\x73\x73\x65\x72\164\x69\157\156\72\101\x75\164\150\156\x53\x74\141\164\145\x6d\145\x6e\164");
        if (empty($KC)) {
            goto GG;
        }
        if (count($KC) > 1) {
            goto sb;
        }
        goto Bk;
        GG:
        $this->authnInstant = NULL;
        return;
        goto Bk;
        sb:
        throw new Exception("\115\x6f\162\145\40\164\x68\x61\164\x20\157\156\145\x20\x3c\163\141\x6d\x6c\72\x41\x75\x74\x68\156\123\164\141\x74\x65\x6d\145\x6e\164\76\40\151\x6e\40\x3c\163\141\155\154\x3a\x41\163\x73\145\x72\164\151\x6f\156\76\x20\156\157\x74\x20\x73\165\x70\x70\157\x72\164\145\x64\56");
        Bk:
        $iL = $KC[0];
        if ($iL->hasAttribute("\101\165\164\150\x6e\111\156\163\x74\x61\x6e\164")) {
            goto oA;
        }
        throw new Exception("\x4d\x69\163\163\151\156\147\x20\162\145\x71\165\151\x72\x65\144\x20\x41\x75\x74\x68\x6e\111\156\163\164\141\x6e\164\40\x61\164\164\162\x69\142\x75\164\145\40\x6f\156\x20\74\163\x61\x6d\x6c\72\101\165\164\150\x6e\x53\164\141\164\145\155\145\x6e\164\x3e\56");
        oA:
        $this->authnInstant = SAMLSPUtilities::xsDateTimeToTimestamp($iL->getAttribute("\x41\165\164\150\x6e\111\x6e\x73\164\141\156\164"));
        if (!$iL->hasAttribute("\123\x65\163\x73\x69\x6f\x6e\116\157\x74\x4f\x6e\x4f\x72\101\x66\164\145\x72")) {
            goto pF;
        }
        $this->sessionNotOnOrAfter = SAMLSPUtilities::xsDateTimeToTimestamp($iL->getAttribute("\123\x65\x73\x73\151\157\x6e\x4e\157\164\117\156\x4f\x72\101\x66\164\x65\x72"));
        pF:
        if (!$iL->hasAttribute("\x53\x65\163\163\x69\x6f\156\x49\156\144\145\x78")) {
            goto g2;
        }
        $this->sessionIndex = $iL->getAttribute("\x53\x65\163\163\x69\x6f\x6e\111\x6e\144\145\x78");
        g2:
        $this->parseAuthnContext($iL);
    }
    private function parseAuthnContext(DOMElement $GM)
    {
        $Jb = SAMLSPUtilities::xpQuery($GM, "\56\x2f\163\141\155\154\x5f\141\163\163\145\x72\164\x69\x6f\156\72\x41\x75\x74\x68\x6e\x43\x6f\156\x74\x65\x78\164");
        if (count($Jb) > 1) {
            goto UC;
        }
        if (empty($Jb)) {
            goto o2;
        }
        goto BY;
        UC:
        throw new Exception("\115\157\162\145\40\x74\x68\x61\156\x20\x6f\x6e\x65\x20\74\163\x61\155\154\x3a\101\x75\x74\x68\156\103\x6f\x6e\x74\145\x78\164\76\40\x69\156\40\x3c\x73\x61\x6d\154\x3a\101\x75\x74\x68\156\123\x74\141\164\145\155\145\156\x74\76\x2e");
        goto BY;
        o2:
        throw new Exception("\x4d\151\x73\x73\151\x6e\147\x20\x72\x65\x71\165\x69\x72\x65\x64\x20\x3c\x73\141\155\154\72\101\x75\x74\x68\x6e\103\157\156\164\x65\170\x74\76\x20\151\x6e\x20\74\x73\x61\x6d\x6c\x3a\x41\x75\x74\150\156\x53\x74\141\x74\145\155\145\156\164\x3e\x2e");
        BY:
        $GX = $Jb[0];
        $pp = SAMLSPUtilities::xpQuery($GX, "\x2e\57\163\141\155\154\137\141\163\163\x65\x72\164\x69\157\156\x3a\x41\165\164\x68\156\103\x6f\156\x74\145\170\164\104\145\143\x6c\122\x65\146");
        if (count($pp) > 1) {
            goto wY;
        }
        if (count($pp) === 1) {
            goto PK;
        }
        goto uR;
        wY:
        throw new Exception("\x4d\x6f\x72\x65\40\164\150\141\x6e\40\157\x6e\x65\40\x3c\163\x61\155\x6c\72\101\x75\164\150\156\x43\157\x6e\x74\x65\170\x74\104\x65\143\154\122\145\x66\x3e\x20\x66\157\x75\x6e\x64\77");
        goto uR;
        PK:
        $this->setAuthnContextDeclRef(trim($pp[0]->textContent));
        uR:
        $Ed = SAMLSPUtilities::xpQuery($GX, "\56\57\x73\x61\155\154\x5f\141\x73\163\145\162\164\151\x6f\156\72\x41\165\164\150\x6e\x43\157\156\164\x65\170\164\104\x65\x63\154");
        if (count($Ed) > 1) {
            goto CW;
        }
        if (count($Ed) === 1) {
            goto KQ;
        }
        goto lI;
        CW:
        throw new Exception("\115\157\x72\x65\x20\x74\x68\141\x6e\40\x6f\156\x65\x20\74\163\141\155\154\72\101\165\x74\150\x6e\x43\x6f\156\164\145\170\164\x44\x65\143\154\76\40\x66\157\165\156\144\x3f");
        goto lI;
        KQ:
        $this->setAuthnContextDecl(new SAML2_XML_Chunk($Ed[0]));
        lI:
        $VS = SAMLSPUtilities::xpQuery($GX, "\x2e\x2f\x73\x61\155\x6c\x5f\141\x73\x73\x65\162\x74\151\157\156\72\101\x75\164\x68\x6e\x43\x6f\x6e\x74\x65\x78\164\x43\x6c\141\x73\x73\x52\x65\x66");
        if (count($VS) > 1) {
            goto t2;
        }
        if (count($VS) === 1) {
            goto b8;
        }
        goto dX;
        t2:
        throw new Exception("\x4d\x6f\x72\x65\40\164\x68\x61\x6e\40\x6f\x6e\x65\x20\74\163\141\x6d\154\72\x41\165\x74\x68\156\x43\x6f\156\x74\x65\x78\164\103\x6c\x61\x73\163\x52\x65\146\76\40\151\x6e\40\74\x73\141\x6d\x6c\x3a\101\165\164\x68\x6e\x43\157\156\x74\145\x78\x74\x3e\56");
        goto dX;
        b8:
        $this->setAuthnContextClassRef(trim($VS[0]->textContent));
        dX:
        if (!(empty($this->authnContextClassRef) && empty($this->authnContextDecl) && empty($this->authnContextDeclRef))) {
            goto WI;
        }
        throw new Exception("\115\151\163\x73\151\x6e\x67\x20\145\151\164\150\x65\x72\x20\74\163\x61\155\154\x3a\x41\x75\x74\x68\x6e\x43\157\x6e\164\145\x78\x74\x43\154\141\x73\x73\122\145\x66\76\40\x6f\162\x20\74\x73\x61\x6d\154\72\101\x75\x74\150\x6e\x43\x6f\156\x74\x65\170\164\104\145\x63\154\122\x65\x66\x3e\x20\x6f\x72\x20\x3c\163\x61\155\x6c\72\101\x75\x74\150\156\103\157\156\x74\145\170\x74\x44\x65\x63\x6c\x3e");
        WI:
        $this->AuthenticatingAuthority = SAMLSPUtilities::extractStrings($GX, "\165\x72\x6e\72\x6f\x61\163\151\163\72\x6e\x61\x6d\x65\163\72\164\143\72\123\101\x4d\114\72\x32\56\60\72\x61\163\x73\145\x72\164\151\x6f\156", "\101\165\x74\x68\x65\156\164\x69\143\x61\164\x69\x6e\147\x41\x75\164\150\157\162\151\x74\171");
    }
    private function parseAttributes(DOMElement $pb)
    {
        $WN = TRUE;
        $so = SAMLSPUtilities::xpQuery($pb, "\56\x2f\163\141\x6d\x6c\137\x61\163\x73\145\162\164\x69\157\156\x3a\x41\x74\x74\162\151\142\165\x74\145\123\x74\x61\164\145\155\145\x6e\x74\57\x73\x61\x6d\x6c\137\141\163\x73\145\x72\164\x69\157\156\x3a\x41\x74\x74\162\x69\142\165\x74\145");
        foreach ($so as $Om) {
            if ($Om->hasAttribute("\116\141\155\x65")) {
                goto DV;
            }
            throw new Exception("\115\151\x73\x73\151\156\147\x20\x6e\141\x6d\x65\40\157\x6e\x20\x3c\163\x61\x6d\154\72\101\164\164\162\151\x62\165\164\145\76\x20\145\x6c\145\x6d\x65\156\164\x2e");
            DV:
            $sb = $Om->getAttribute("\116\141\155\x65");
            if ($Om->hasAttribute("\116\x61\x6d\145\x46\x6f\162\x6d\141\x74")) {
                goto jZ;
            }
            $Pk = "\165\162\x6e\x3a\157\141\x73\x69\163\x3a\156\x61\x6d\x65\163\72\x74\x63\x3a\x53\x41\115\114\72\x31\x2e\61\x3a\x6e\x61\x6d\145\151\144\55\146\157\162\x6d\x61\164\x3a\x75\x6e\x73\160\x65\143\x69\146\151\145\144";
            goto AO;
            jZ:
            $Pk = $Om->getAttribute("\x4e\141\155\x65\106\x6f\162\155\141\164");
            AO:
            if ($WN) {
                goto Yr;
            }
            if (!($this->nameFormat !== $Pk)) {
                goto FX;
            }
            $this->nameFormat = "\165\x72\156\72\157\141\x73\x69\x73\72\156\141\x6d\145\163\72\164\143\x3a\123\101\115\x4c\72\61\x2e\61\x3a\156\x61\x6d\145\151\x64\55\146\157\x72\155\141\x74\72\165\x6e\163\x70\x65\143\151\146\x69\x65\144";
            FX:
            goto Gk;
            Yr:
            $this->nameFormat = $Pk;
            $WN = FALSE;
            Gk:
            if (array_key_exists($sb, $this->attributes)) {
                goto Sf;
            }
            $this->attributes[$sb] = array();
            Sf:
            $OM = SAMLSPUtilities::xpQuery($Om, "\56\57\x73\141\155\x6c\137\141\x73\x73\145\162\164\x69\157\156\72\x41\164\164\x72\151\142\165\164\x65\126\x61\154\165\145");
            foreach ($OM as $tq) {
                $this->attributes[$sb][] = trim($tq->textContent);
                B0:
            }
            rx:
            PW:
        }
        Kz:
    }
    private function parseEncryptedAttributes(DOMElement $pb)
    {
        $this->encryptedAttribute = SAMLSPUtilities::xpQuery($pb, "\56\x2f\163\141\x6d\154\137\x61\163\163\145\x72\x74\x69\157\x6e\x3a\101\164\164\x72\151\142\x75\x74\x65\x53\164\x61\x74\145\x6d\x65\156\x74\57\x73\141\155\154\x5f\141\x73\x73\x65\x72\x74\x69\x6f\x6e\72\x45\156\x63\162\171\x70\164\x65\144\x41\x74\x74\x72\151\x62\x75\x74\145");
    }
    private function parseSignature(DOMElement $pb)
    {
        $pW = SAMLSPUtilities::validateElement($pb);
        if (!($pW !== FALSE)) {
            goto Ef;
        }
        $this->wasSignedAtConstruction = TRUE;
        $this->certificates = $pW["\103\x65\162\164\151\x66\x69\143\141\x74\x65\163"];
        $this->signatureData = $pW;
        Ef:
    }
    public function validate(XMLSecurityKey $Xr)
    {
        if (!($this->signatureData === NULL)) {
            goto f6;
        }
        return FALSE;
        f6:
        SAMLSPUtilities::validateSignature($this->signatureData, $Xr);
        return TRUE;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setId($rq)
    {
        $this->id = $rq;
    }
    public function getIssueInstant()
    {
        return $this->issueInstant;
    }
    public function setIssueInstant($tI)
    {
        $this->issueInstant = $tI;
    }
    public function getIssuer()
    {
        return $this->issuer;
    }
    public function setIssuer($VB)
    {
        $this->issuer = $VB;
    }
    public function getNameId()
    {
        if (!($this->encryptedNameId !== NULL)) {
            goto Bh;
        }
        throw new Exception("\x41\164\164\145\155\160\164\145\144\x20\164\x6f\x20\162\145\x74\x72\x69\145\x76\x65\40\x65\x6e\x63\x72\171\x70\x74\x65\144\x20\x4e\x61\155\145\x49\x44\x20\167\151\x74\150\x6f\x75\x74\40\144\145\143\162\x79\x70\164\151\156\x67\x20\151\164\40\x66\151\x72\x73\164\x2e");
        Bh:
        return $this->nameId;
    }
    public function setNameId($A3)
    {
        $this->nameId = $A3;
    }
    public function isNameIdEncrypted()
    {
        if (!($this->encryptedNameId !== NULL)) {
            goto x8;
        }
        return TRUE;
        x8:
        return FALSE;
    }
    public function encryptNameId(XMLSecurityKey $Xr)
    {
        $vH = new DOMDocument();
        $XN = $vH->createElement("\162\x6f\x6f\164");
        $vH->appendChild($XN);
        SAMLSPUtilities::addNameId($XN, $this->nameId);
        $A3 = $XN->firstChild;
        SAMLSPUtilities::getContainer()->debugMessage($A3, "\x65\156\143\162\171\160\x74");
        $c1 = new XMLSecEnc();
        $c1->setNode($A3);
        $c1->type = XMLSecEnc::Element;
        $YU = new XMLSecurityKey(XMLSecurityKey::AES128_CBC);
        $YU->generateSessionKey();
        $c1->encryptKey($Xr, $YU);
        $this->encryptedNameId = $c1->encryptNode($YU);
        $this->nameId = NULL;
    }
    public function decryptNameId(XMLSecurityKey $Xr, array $ey = array())
    {
        if (!($this->encryptedNameId === NULL)) {
            goto w3;
        }
        return;
        w3:
        $A3 = SAMLSPUtilities::decryptElement($this->encryptedNameId, $Xr, $ey);
        SAMLSPUtilities::getContainer()->debugMessage($A3, "\x64\x65\143\162\171\160\164");
        $this->nameId = SAMLSPUtilities::parseNameId($A3);
        $this->encryptedNameId = NULL;
    }
    public function decryptAttributes(XMLSecurityKey $Xr, array $ey = array())
    {
        if (!($this->encryptedAttribute === NULL)) {
            goto Mf;
        }
        return;
        Mf:
        $WN = TRUE;
        $so = $this->encryptedAttribute;
        foreach ($so as $uW) {
            $Om = SAMLSPUtilities::decryptElement($uW->getElementsByTagName("\105\x6e\143\x72\x79\160\164\145\x64\x44\141\164\x61")->item(0), $Xr, $ey);
            if ($Om->hasAttribute("\116\141\155\x65")) {
                goto q9;
            }
            throw new Exception("\115\151\163\x73\151\156\147\40\x6e\141\x6d\x65\40\x6f\x6e\40\x3c\x73\x61\155\x6c\x3a\x41\x74\x74\162\151\x62\x75\164\145\76\40\145\x6c\x65\155\145\x6e\x74\56");
            q9:
            $sb = $Om->getAttribute("\116\141\x6d\x65");
            if ($Om->hasAttribute("\x4e\141\155\x65\x46\157\162\155\141\x74")) {
                goto Rk;
            }
            $Pk = "\x75\x72\156\x3a\x6f\x61\x73\x69\x73\72\156\x61\x6d\145\163\72\164\143\x3a\123\x41\115\x4c\72\62\x2e\x30\72\141\x74\164\162\156\x61\155\145\x2d\146\x6f\162\x6d\141\x74\72\x75\156\163\x70\x65\143\151\x66\151\145\144";
            goto KY;
            Rk:
            $Pk = $Om->getAttribute("\x4e\141\155\145\x46\x6f\162\x6d\x61\x74");
            KY:
            if ($WN) {
                goto mH;
            }
            if (!($this->nameFormat !== $Pk)) {
                goto RT;
            }
            $this->nameFormat = "\x75\162\156\72\x6f\x61\x73\151\x73\x3a\156\x61\x6d\145\x73\x3a\164\143\72\x53\101\x4d\114\72\62\x2e\60\x3a\141\x74\164\162\156\x61\x6d\145\55\x66\157\x72\155\x61\164\72\x75\156\x73\x70\x65\x63\151\x66\x69\x65\x64";
            RT:
            goto T3;
            mH:
            $this->nameFormat = $Pk;
            $WN = FALSE;
            T3:
            if (array_key_exists($sb, $this->attributes)) {
                goto ZN;
            }
            $this->attributes[$sb] = array();
            ZN:
            $OM = SAMLSPUtilities::xpQuery($Om, "\x2e\57\x73\x61\155\154\x5f\141\x73\x73\x65\162\x74\151\157\x6e\x3a\x41\x74\164\162\x69\x62\x75\164\x65\126\141\x6c\165\x65");
            foreach ($OM as $tq) {
                $this->attributes[$sb][] = trim($tq->textContent);
                pV:
            }
            g7:
            qA:
        }
        zf:
    }
    public function getNotBefore()
    {
        return $this->notBefore;
    }
    public function setNotBefore($o2)
    {
        $this->notBefore = $o2;
    }
    public function getNotOnOrAfter()
    {
        return $this->notOnOrAfter;
    }
    public function setNotOnOrAfter($i1)
    {
        $this->notOnOrAfter = $i1;
    }
    public function setEncryptedAttributes($x7)
    {
        $this->requiredEncAttributes = $x7;
    }
    public function getValidAudiences()
    {
        return $this->validAudiences;
    }
    public function setValidAudiences(array $PA = NULL)
    {
        $this->validAudiences = $PA;
    }
    public function getAuthnInstant()
    {
        return $this->authnInstant;
    }
    public function setAuthnInstant($dk)
    {
        $this->authnInstant = $dk;
    }
    public function getSessionNotOnOrAfter()
    {
        return $this->sessionNotOnOrAfter;
    }
    public function setSessionNotOnOrAfter($Dg)
    {
        $this->sessionNotOnOrAfter = $Dg;
    }
    public function getSessionIndex()
    {
        return $this->sessionIndex;
    }
    public function setSessionIndex($PO)
    {
        $this->sessionIndex = $PO;
    }
    public function getAuthnContext()
    {
        if (empty($this->authnContextClassRef)) {
            goto Ej;
        }
        return $this->authnContextClassRef;
        Ej:
        if (empty($this->authnContextDeclRef)) {
            goto WL;
        }
        return $this->authnContextDeclRef;
        WL:
        return NULL;
    }
    public function setAuthnContext($AI)
    {
        $this->setAuthnContextClassRef($AI);
    }
    public function getAuthnContextClassRef()
    {
        return $this->authnContextClassRef;
    }
    public function setAuthnContextClassRef($Je)
    {
        $this->authnContextClassRef = $Je;
    }
    public function setAuthnContextDecl(SAML2_XML_Chunk $bc)
    {
        if (empty($this->authnContextDeclRef)) {
            goto I_;
        }
        throw new Exception("\x41\x75\x74\x68\156\103\x6f\156\x74\145\x78\164\104\145\x63\x6c\x52\145\146\40\x69\x73\40\x61\154\x72\145\x61\x64\x79\40\162\x65\x67\x69\x73\164\145\x72\x65\144\x21\40\115\x61\171\40\157\156\154\171\x20\150\141\x76\x65\40\x65\x69\164\x68\x65\x72\x20\x61\40\x44\145\143\154\40\x6f\x72\x20\x61\x20\x44\x65\143\x6c\x52\145\146\x2c\40\156\157\164\40\142\x6f\x74\x68\41");
        I_:
        $this->authnContextDecl = $bc;
    }
    public function getAuthnContextDecl()
    {
        return $this->authnContextDecl;
    }
    public function setAuthnContextDeclRef($dg)
    {
        if (empty($this->authnContextDecl)) {
            goto Jp;
        }
        throw new Exception("\101\165\x74\150\156\x43\157\156\164\x65\x78\164\x44\x65\x63\154\x20\x69\x73\40\141\x6c\x72\x65\x61\x64\x79\40\x72\x65\147\151\163\x74\145\x72\145\x64\x21\40\x4d\x61\x79\40\157\x6e\154\171\x20\150\x61\x76\x65\40\x65\x69\x74\x68\x65\162\40\x61\40\x44\x65\x63\154\x20\157\x72\40\141\x20\104\x65\x63\x6c\x52\145\146\54\x20\156\157\x74\x20\142\x6f\164\x68\x21");
        Jp:
        $this->authnContextDeclRef = $dg;
    }
    public function getAuthnContextDeclRef()
    {
        return $this->authnContextDeclRef;
    }
    public function getAuthenticatingAuthority()
    {
        return $this->AuthenticatingAuthority;
    }
    public function setAuthenticatingAuthority($f5)
    {
        $this->AuthenticatingAuthority = $f5;
    }
    public function getAttributes()
    {
        return $this->attributes;
    }
    public function setAttributes(array $so)
    {
        $this->attributes = $so;
    }
    public function getAttributeNameFormat()
    {
        return $this->nameFormat;
    }
    public function setAttributeNameFormat($Pk)
    {
        $this->nameFormat = $Pk;
    }
    public function getSubjectConfirmation()
    {
        return $this->SubjectConfirmation;
    }
    public function setSubjectConfirmation(array $Mi)
    {
        $this->SubjectConfirmation = $Mi;
    }
    public function getSignatureKey()
    {
        return $this->signatureKey;
    }
    public function setSignatureKey(XMLsecurityKey $kx = NULL)
    {
        $this->signatureKey = $kx;
    }
    public function getEncryptionKey()
    {
        return $this->encryptionKey;
    }
    public function setEncryptionKey(XMLSecurityKey $O5 = NULL)
    {
        $this->encryptionKey = $O5;
    }
    public function setCertificates(array $tw)
    {
        $this->certificates = $tw;
    }
    public function getCertificates()
    {
        return $this->certificates;
    }
    public function getSignatureData()
    {
        return $this->signatureData;
    }
    public function getWasSignedAtConstruction()
    {
        return $this->wasSignedAtConstruction;
    }
    public function toXML(DOMNode $A9 = NULL)
    {
        if ($A9 === NULL) {
            goto O0;
        }
        $Gx = $A9->ownerDocument;
        goto jn;
        O0:
        $Gx = new DOMDocument();
        $A9 = $Gx;
        jn:
        $XN = $Gx->createElementNS("\165\162\156\72\157\141\163\151\163\x3a\156\x61\x6d\145\x73\x3a\x74\x63\72\123\x41\x4d\x4c\72\62\56\x30\x3a\x61\x73\163\x65\162\164\151\157\156", "\163\141\x6d\154\x3a" . "\x41\163\163\145\162\x74\x69\x6f\156");
        $A9->appendChild($XN);
        $XN->setAttributeNS("\x75\x72\156\72\x6f\x61\163\151\163\72\x6e\141\x6d\x65\163\72\164\143\x3a\123\101\x4d\114\x3a\x32\56\60\72\160\x72\x6f\164\157\143\157\x6c", "\163\141\x6d\154\160\72\x74\x6d\160", "\x74\x6d\x70");
        $XN->removeAttributeNS("\x75\162\156\x3a\x6f\x61\x73\x69\163\72\x6e\141\x6d\x65\163\x3a\x74\x63\x3a\x53\x41\x4d\x4c\x3a\x32\x2e\60\x3a\x70\162\x6f\x74\157\x63\157\154", "\x74\x6d\x70");
        $XN->setAttributeNS("\x68\x74\x74\x70\72\57\57\167\167\167\x2e\167\x33\x2e\x6f\162\147\x2f\x32\x30\x30\61\57\x58\115\x4c\x53\143\150\x65\155\141\55\151\x6e\163\164\141\156\x63\145", "\170\x73\x69\72\x74\x6d\x70", "\164\155\160");
        $XN->removeAttributeNS("\150\x74\x74\x70\x3a\x2f\x2f\167\167\167\56\x77\63\x2e\x6f\162\x67\x2f\62\x30\60\x31\57\130\115\x4c\123\143\x68\145\155\x61\x2d\x69\156\x73\x74\141\x6e\143\x65", "\x74\155\x70");
        $XN->setAttributeNS("\150\x74\164\160\72\x2f\x2f\167\x77\x77\56\x77\x33\x2e\157\162\147\x2f\x32\x30\x30\x31\57\x58\x4d\x4c\x53\143\x68\145\155\141", "\x78\x73\72\164\x6d\x70", "\x74\155\160");
        $XN->removeAttributeNS("\x68\x74\x74\x70\72\x2f\x2f\167\167\167\x2e\x77\63\56\157\x72\147\57\x32\60\x30\x31\57\x58\x4d\x4c\x53\143\x68\145\x6d\141", "\164\155\160");
        $XN->setAttribute("\111\x44", $this->id);
        $XN->setAttribute("\126\x65\162\163\151\157\156", "\62\x2e\60");
        $XN->setAttribute("\111\163\x73\165\x65\111\x6e\163\164\141\156\164", gmdate("\131\x2d\155\55\x64\x5c\x54\110\x3a\151\x3a\163\x5c\x5a", $this->issueInstant));
        $VB = SAMLSPUtilities::addString($XN, "\x75\x72\156\x3a\x6f\141\x73\x69\x73\72\156\x61\x6d\x65\163\72\x74\x63\x3a\x53\x41\115\x4c\x3a\x32\x2e\x30\x3a\x61\163\163\x65\x72\x74\151\x6f\156", "\x73\141\155\x6c\72\x49\163\x73\x75\145\x72", $this->issuer);
        $this->addSubject($XN);
        $this->addConditions($XN);
        $this->addAuthnStatement($XN);
        if ($this->requiredEncAttributes == FALSE) {
            goto gW;
        }
        $this->addEncryptedAttributeStatement($XN);
        goto xH;
        gW:
        $this->addAttributeStatement($XN);
        xH:
        if (!($this->signatureKey !== NULL)) {
            goto tQ;
        }
        SAMLSPUtilities::insertSignature($this->signatureKey, $this->certificates, $XN, $VB->nextSibling);
        tQ:
        return $XN;
    }
    private function addSubject(DOMElement $XN)
    {
        if (!($this->nameId === NULL && $this->encryptedNameId === NULL)) {
            goto Ra;
        }
        return;
        Ra:
        $MF = $XN->ownerDocument->createElementNS("\165\162\156\72\x6f\x61\163\x69\163\72\156\x61\155\145\163\72\164\x63\72\x53\101\x4d\x4c\72\62\56\60\72\141\x73\x73\145\x72\x74\151\x6f\x6e", "\x73\x61\155\154\72\123\x75\x62\x6a\x65\143\164");
        $XN->appendChild($MF);
        if ($this->encryptedNameId === NULL) {
            goto zH;
        }
        $IG = $MF->ownerDocument->createElementNS("\x75\162\x6e\x3a\x6f\141\163\151\x73\x3a\156\x61\x6d\x65\x73\72\164\x63\72\123\101\115\114\x3a\x32\56\x30\x3a\141\x73\x73\145\162\x74\x69\x6f\156", "\163\141\x6d\154\x3a" . "\105\156\143\x72\171\160\x74\x65\144\111\x44");
        $MF->appendChild($IG);
        $IG->appendChild($MF->ownerDocument->importNode($this->encryptedNameId, TRUE));
        goto QS;
        zH:
        SAMLSPUtilities::addNameId($MF, $this->nameId);
        QS:
        foreach ($this->SubjectConfirmation as $Dz) {
            $Dz->toXML($MF);
            NP:
        }
        H3:
    }
    private function addConditions(DOMElement $XN)
    {
        $Gx = $XN->ownerDocument;
        $JP = $Gx->createElementNS("\x75\162\156\x3a\157\141\x73\x69\x73\x3a\156\x61\155\x65\x73\x3a\164\x63\x3a\123\101\115\114\x3a\x32\x2e\60\x3a\141\x73\163\145\x72\x74\151\x6f\x6e", "\x73\x61\x6d\154\72\103\x6f\156\x64\151\164\x69\x6f\156\163");
        $XN->appendChild($JP);
        if (!($this->notBefore !== NULL)) {
            goto V4;
        }
        $JP->setAttribute("\x4e\x6f\x74\x42\145\146\157\162\x65", gmdate("\131\55\155\x2d\x64\134\124\x48\72\x69\x3a\163\134\132", $this->notBefore));
        V4:
        if (!($this->notOnOrAfter !== NULL)) {
            goto E_;
        }
        $JP->setAttribute("\x4e\x6f\x74\x4f\x6e\117\162\101\x66\164\x65\x72", gmdate("\131\55\x6d\x2d\144\x5c\x54\x48\x3a\x69\x3a\x73\134\132", $this->notOnOrAfter));
        E_:
        if (!($this->validAudiences !== NULL)) {
            goto AY;
        }
        $rD = $Gx->createElementNS("\165\x72\x6e\72\x6f\141\163\x69\163\72\x6e\141\155\145\x73\72\x74\143\x3a\123\101\x4d\114\72\62\56\x30\72\x61\x73\163\145\162\164\x69\157\156", "\x73\141\x6d\154\72\101\x75\144\x69\145\x6e\143\145\x52\x65\x73\164\162\151\x63\164\x69\157\x6e");
        $JP->appendChild($rD);
        SAMLSPUtilities::addStrings($rD, "\165\162\x6e\x3a\157\x61\163\x69\163\72\x6e\141\x6d\x65\163\x3a\x74\x63\72\123\x41\x4d\114\x3a\x32\56\60\x3a\141\x73\x73\145\162\164\x69\157\x6e", "\163\x61\x6d\x6c\72\101\x75\144\151\x65\156\143\145", FALSE, $this->validAudiences);
        AY:
    }
    private function addAuthnStatement(DOMElement $XN)
    {
        if (!($this->authnInstant === NULL || $this->authnContextClassRef === NULL && $this->authnContextDecl === NULL && $this->authnContextDeclRef === NULL)) {
            goto oz;
        }
        return;
        oz:
        $Gx = $XN->ownerDocument;
        $GM = $Gx->createElementNS("\165\x72\x6e\72\x6f\141\x73\x69\163\72\x6e\141\155\x65\x73\x3a\x74\143\x3a\x53\x41\x4d\x4c\x3a\x32\56\60\72\x61\163\163\x65\162\164\x69\x6f\156", "\x73\141\x6d\x6c\x3a\101\x75\164\x68\156\123\164\x61\x74\145\x6d\145\156\164");
        $XN->appendChild($GM);
        $GM->setAttribute("\101\165\x74\150\156\111\x6e\x73\164\x61\x6e\164", gmdate("\x59\x2d\x6d\55\144\x5c\124\x48\72\151\72\163\x5c\132", $this->authnInstant));
        if (!($this->sessionNotOnOrAfter !== NULL)) {
            goto SU;
        }
        $GM->setAttribute("\x53\145\x73\x73\151\157\156\116\157\x74\117\x6e\117\x72\101\146\x74\x65\162", gmdate("\x59\x2d\155\55\144\134\124\x48\72\x69\72\163\134\x5a", $this->sessionNotOnOrAfter));
        SU:
        if (!($this->sessionIndex !== NULL)) {
            goto dB;
        }
        $GM->setAttribute("\x53\145\x73\x73\151\x6f\x6e\111\x6e\x64\x65\170", $this->sessionIndex);
        dB:
        $GX = $Gx->createElementNS("\165\x72\x6e\x3a\157\x61\x73\151\x73\x3a\156\x61\x6d\145\x73\72\164\x63\72\x53\x41\x4d\114\72\x32\x2e\x30\x3a\x61\x73\163\145\x72\x74\x69\157\156", "\163\141\155\x6c\x3a\101\165\x74\x68\156\x43\x6f\x6e\x74\145\170\x74");
        $GM->appendChild($GX);
        if (empty($this->authnContextClassRef)) {
            goto X7;
        }
        SAMLSPUtilities::addString($GX, "\165\162\156\72\x6f\141\163\151\163\x3a\156\141\x6d\x65\x73\x3a\x74\143\x3a\123\101\115\114\x3a\x32\x2e\60\72\x61\x73\163\145\x72\164\x69\157\x6e", "\x73\141\155\154\72\101\165\x74\150\x6e\x43\x6f\156\x74\145\x78\164\103\x6c\141\163\163\122\145\x66", $this->authnContextClassRef);
        X7:
        if (empty($this->authnContextDecl)) {
            goto iQ;
        }
        $this->authnContextDecl->toXML($GX);
        iQ:
        if (empty($this->authnContextDeclRef)) {
            goto JM;
        }
        SAMLSPUtilities::addString($GX, "\x75\x72\156\x3a\x6f\x61\163\151\163\72\x6e\x61\155\x65\163\x3a\x74\143\72\123\x41\x4d\x4c\72\62\x2e\60\72\x61\163\163\x65\162\164\x69\x6f\156", "\x73\x61\x6d\x6c\72\x41\x75\164\150\156\x43\157\x6e\164\145\x78\x74\104\x65\143\154\x52\145\x66", $this->authnContextDeclRef);
        JM:
        SAMLSPUtilities::addStrings($GX, "\165\x72\x6e\72\x6f\x61\163\151\163\x3a\x6e\141\x6d\x65\x73\x3a\164\143\x3a\123\x41\x4d\114\72\62\x2e\x30\x3a\141\x73\163\145\x72\x74\x69\x6f\x6e", "\x73\141\x6d\154\x3a\x41\165\x74\x68\x65\x6e\x74\x69\143\141\164\151\156\147\101\165\x74\150\157\x72\x69\x74\x79", FALSE, $this->AuthenticatingAuthority);
    }
    private function addAttributeStatement(DOMElement $XN)
    {
        if (!empty($this->attributes)) {
            goto FJ;
        }
        return;
        FJ:
        $Gx = $XN->ownerDocument;
        $iO = $Gx->createElementNS("\165\x72\x6e\x3a\x6f\x61\x73\151\x73\x3a\156\x61\155\145\x73\72\164\x63\72\x53\x41\x4d\x4c\72\x32\56\x30\72\141\163\x73\145\162\x74\151\157\156", "\163\x61\x6d\x6c\72\x41\164\x74\x72\151\142\x75\164\145\123\164\x61\x74\145\155\145\156\x74");
        $XN->appendChild($iO);
        foreach ($this->attributes as $sb => $OM) {
            $Om = $Gx->createElementNS("\x75\x72\x6e\x3a\157\141\163\151\163\72\x6e\x61\155\x65\163\x3a\x74\x63\72\x53\101\115\x4c\72\x32\x2e\60\72\x61\163\163\145\x72\x74\x69\157\156", "\x73\x61\155\154\x3a\x41\164\164\162\151\x62\165\x74\x65");
            $iO->appendChild($Om);
            $Om->setAttribute("\x4e\x61\155\x65", $sb);
            if (!($this->nameFormat !== "\165\x72\x6e\x3a\157\x61\x73\x69\163\x3a\156\141\x6d\145\163\72\164\143\x3a\x53\x41\x4d\114\72\x32\56\60\72\x61\x74\164\162\156\141\x6d\145\55\x66\x6f\x72\x6d\x61\x74\72\165\x6e\x73\x70\145\143\x69\x66\x69\x65\144")) {
                goto ve;
            }
            $Om->setAttribute("\116\x61\155\x65\106\157\162\155\x61\x74", $this->nameFormat);
            ve:
            foreach ($OM as $tq) {
                if (is_string($tq)) {
                    goto af;
                }
                if (is_int($tq)) {
                    goto ei;
                }
                $YP = NULL;
                goto qd;
                af:
                $YP = "\170\163\x3a\x73\164\162\151\x6e\147";
                goto qd;
                ei:
                $YP = "\170\163\x3a\x69\156\x74\145\x67\145\x72";
                qd:
                $m1 = $Gx->createElementNS("\165\162\156\72\157\141\163\151\163\x3a\156\x61\x6d\145\x73\x3a\x74\143\72\x53\x41\115\x4c\72\62\x2e\x30\x3a\141\x73\163\x65\162\164\x69\x6f\x6e", "\x73\141\x6d\154\72\x41\164\x74\x72\x69\142\x75\x74\x65\126\141\154\x75\145");
                $Om->appendChild($m1);
                if (!($YP !== NULL)) {
                    goto Y2;
                }
                $m1->setAttributeNS("\x68\x74\164\160\x3a\x2f\x2f\x77\167\x77\x2e\x77\x33\56\x6f\x72\147\x2f\x32\60\x30\61\x2f\x58\x4d\x4c\x53\143\150\145\x6d\141\x2d\151\156\163\164\x61\x6e\x63\145", "\170\x73\151\72\164\x79\160\x65", $YP);
                Y2:
                if (!is_null($tq)) {
                    goto A6;
                }
                $m1->setAttributeNS("\150\164\x74\x70\x3a\x2f\x2f\167\167\167\56\x77\63\56\x6f\x72\x67\57\x32\x30\60\61\57\130\115\114\123\143\150\145\155\141\x2d\151\x6e\163\164\141\156\x63\x65", "\170\163\151\72\156\x69\154", "\x74\x72\x75\x65");
                A6:
                if ($tq instanceof DOMNodeList) {
                    goto U2;
                }
                $m1->appendChild($Gx->createTextNode($tq));
                goto v6;
                U2:
                $fL = 0;
                c_:
                if (!($fL < $tq->length)) {
                    goto VH;
                }
                $nT = $Gx->importNode($tq->item($fL), TRUE);
                $m1->appendChild($nT);
                o4:
                $fL++;
                goto c_;
                VH:
                v6:
                ms:
            }
            Ui:
            yo:
        }
        rk:
    }
    private function addEncryptedAttributeStatement(DOMElement $XN)
    {
        if (!($this->requiredEncAttributes == FALSE)) {
            goto nq;
        }
        return;
        nq:
        $Gx = $XN->ownerDocument;
        $iO = $Gx->createElementNS("\x75\162\156\x3a\x6f\x61\163\x69\163\72\x6e\x61\155\x65\163\x3a\164\x63\72\x53\101\115\114\72\62\x2e\x30\x3a\x61\x73\163\x65\162\x74\151\x6f\x6e", "\163\141\155\154\72\x41\164\164\x72\x69\x62\x75\x74\x65\123\164\x61\164\x65\x6d\x65\x6e\164");
        $XN->appendChild($iO);
        foreach ($this->attributes as $sb => $OM) {
            $Rz = new DOMDocument();
            $Om = $Rz->createElementNS("\x75\x72\156\72\x6f\x61\x73\x69\163\72\156\141\x6d\145\163\72\164\143\x3a\123\x41\x4d\114\x3a\x32\56\x30\x3a\141\x73\163\x65\x72\164\151\157\156", "\x73\141\155\154\72\101\x74\x74\162\151\142\165\164\145");
            $Om->setAttribute("\x4e\141\155\145", $sb);
            $Rz->appendChild($Om);
            if (!($this->nameFormat !== "\165\162\156\x3a\x6f\x61\x73\151\x73\72\x6e\141\155\145\163\72\x74\143\72\x53\x41\x4d\x4c\x3a\62\56\60\x3a\x61\x74\x74\x72\156\141\155\x65\55\x66\157\x72\155\x61\x74\72\x75\x6e\x73\x70\x65\143\x69\x66\151\x65\144")) {
                goto Ln;
            }
            $Om->setAttribute("\x4e\x61\x6d\145\106\157\x72\x6d\141\x74", $this->nameFormat);
            Ln:
            foreach ($OM as $tq) {
                if (is_string($tq)) {
                    goto pp;
                }
                if (is_int($tq)) {
                    goto mg;
                }
                $YP = NULL;
                goto Vs;
                pp:
                $YP = "\170\x73\72\163\x74\162\x69\x6e\x67";
                goto Vs;
                mg:
                $YP = "\170\163\x3a\151\x6e\164\x65\147\x65\x72";
                Vs:
                $m1 = $Rz->createElementNS("\165\162\156\72\157\x61\163\x69\x73\72\156\x61\155\x65\163\72\x74\x63\72\x53\101\x4d\x4c\x3a\62\56\60\x3a\x61\x73\163\145\162\164\151\x6f\x6e", "\x73\141\x6d\154\x3a\x41\164\164\x72\151\142\165\x74\x65\126\141\x6c\165\x65");
                $Om->appendChild($m1);
                if (!($YP !== NULL)) {
                    goto JK;
                }
                $m1->setAttributeNS("\x68\164\x74\x70\x3a\57\x2f\x77\167\x77\56\167\63\x2e\x6f\162\147\57\x32\60\x30\61\57\130\115\x4c\123\x63\x68\145\x6d\141\x2d\x69\x6e\x73\164\141\156\x63\145", "\x78\163\x69\72\x74\171\x70\145", $YP);
                JK:
                if ($tq instanceof DOMNodeList) {
                    goto bL;
                }
                $m1->appendChild($Rz->createTextNode($tq));
                goto zN;
                bL:
                $fL = 0;
                Vv:
                if (!($fL < $tq->length)) {
                    goto Wd;
                }
                $nT = $Rz->importNode($tq->item($fL), TRUE);
                $m1->appendChild($nT);
                JX:
                $fL++;
                goto Vv;
                Wd:
                zN:
                Dn:
            }
            KN:
            $Xt = new XMLSecEnc();
            $Xt->setNode($Rz->documentElement);
            $Xt->type = "\150\164\x74\160\x3a\57\57\x77\x77\167\x2e\167\63\56\157\x72\147\x2f\62\x30\60\61\57\x30\x34\x2f\170\155\x6c\x65\x6e\x63\43\105\x6c\145\155\145\156\x74";
            $YU = new XMLSecurityKey(XMLSecurityKey::AES256_CBC);
            $YU->generateSessionKey();
            $Xt->encryptKey($this->encryptionKey, $YU);
            $rr = $Xt->encryptNode($YU);
            $ig = $Gx->createElementNS("\165\162\x6e\72\x6f\x61\x73\x69\x73\72\156\141\155\145\x73\72\x74\x63\72\x53\x41\x4d\114\72\x32\x2e\60\72\x61\163\163\145\162\x74\151\x6f\156", "\163\x61\x6d\154\x3a\x45\x6e\x63\x72\x79\160\x74\x65\144\101\164\x74\162\151\142\x75\164\145");
            $iO->appendChild($ig);
            $ax = $Gx->importNode($rr, TRUE);
            $ig->appendChild($ax);
            pt:
        }
        Ma:
    }
}
