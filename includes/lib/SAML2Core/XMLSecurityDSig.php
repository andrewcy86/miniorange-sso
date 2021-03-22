<?php


namespace RobRichards\XMLSecLibs;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use Exception;
use RobRichards\XMLSecLibs\Utils\XPath;
class XMLSecurityDSig
{
    const XMLDSIGNS = "\150\x74\164\x70\x3a\57\57\167\x77\167\x2e\x77\63\56\157\x72\147\57\x32\x30\60\60\57\60\71\57\170\155\154\144\x73\x69\x67\x23";
    const SHA1 = "\150\x74\164\x70\x3a\57\x2f\167\x77\167\56\167\x33\x2e\157\x72\x67\57\x32\x30\60\x30\x2f\60\x39\57\x78\x6d\154\144\163\151\147\43\x73\x68\x61\61";
    const SHA256 = "\150\x74\x74\160\x3a\x2f\x2f\167\x77\167\56\167\x33\56\157\x72\x67\57\x32\60\x30\x31\57\x30\x34\57\170\x6d\154\145\x6e\x63\x23\x73\x68\141\x32\x35\66";
    const SHA384 = "\150\x74\x74\160\x3a\x2f\57\167\167\167\56\x77\63\x2e\157\x72\147\x2f\62\x30\60\61\57\x30\x34\57\170\x6d\x6c\x64\x73\151\x67\x2d\155\157\162\145\x23\163\150\x61\63\x38\x34";
    const SHA512 = "\x68\164\x74\160\x3a\57\x2f\167\167\167\x2e\x77\63\x2e\157\x72\x67\x2f\x32\60\60\x31\57\x30\x34\57\170\x6d\154\x65\156\x63\43\163\150\141\65\61\x32";
    const RIPEMD160 = "\150\x74\x74\160\72\x2f\57\x77\x77\x77\56\x77\63\x2e\157\x72\147\x2f\x32\x30\60\61\x2f\x30\64\57\170\x6d\x6c\x65\x6e\x63\43\x72\x69\160\145\155\144\61\x36\60";
    const C14N = "\x68\x74\x74\160\72\x2f\x2f\x77\167\x77\x2e\x77\63\x2e\x6f\x72\x67\57\x54\122\57\x32\60\60\x31\57\122\x45\103\x2d\x78\x6d\x6c\55\x63\61\x34\x6e\x2d\x32\x30\60\61\x30\63\x31\x35";
    const C14N_COMMENTS = "\150\x74\164\x70\x3a\57\x2f\x77\x77\x77\56\x77\x33\56\x6f\x72\147\57\124\x52\x2f\x32\x30\60\61\57\122\105\x43\55\x78\155\154\x2d\x63\x31\x34\156\55\62\x30\x30\61\60\63\61\65\43\x57\151\164\x68\x43\157\x6d\155\x65\156\x74\163";
    const EXC_C14N = "\x68\164\x74\160\x3a\57\x2f\x77\x77\x77\x2e\x77\x33\56\x6f\162\x67\x2f\x32\60\x30\x31\57\x31\x30\57\x78\155\154\x2d\145\170\143\x2d\143\x31\64\x6e\43";
    const EXC_C14N_COMMENTS = "\150\164\164\160\72\x2f\57\167\167\167\x2e\167\x33\x2e\x6f\x72\x67\x2f\x32\60\60\61\57\x31\60\x2f\170\x6d\154\x2d\145\170\x63\x2d\143\61\x34\156\x23\127\x69\x74\150\103\x6f\x6d\x6d\x65\156\x74\x73";
    const template = "\74\x64\x73\x3a\x53\x69\147\156\x61\164\x75\162\145\x20\x78\x6d\154\156\x73\72\x64\163\75\x22\x68\164\x74\x70\72\x2f\57\x77\167\x77\x2e\x77\63\56\157\x72\147\57\62\x30\60\x30\57\60\x39\57\x78\x6d\154\144\163\151\147\43\42\x3e\15\12\x20\x20\x3c\x64\x73\x3a\x53\x69\x67\156\145\144\111\156\146\157\x3e\15\12\x20\40\40\x20\74\x64\163\x3a\123\x69\x67\156\x61\x74\x75\162\145\x4d\x65\x74\150\157\144\x20\57\76\xd\xa\x20\x20\74\x2f\144\163\72\123\x69\147\156\145\144\111\x6e\146\157\76\15\xa\x3c\x2f\x64\163\x3a\123\x69\x67\x6e\x61\164\x75\162\145\x3e";
    const BASE_TEMPLATE = "\74\x53\x69\147\156\x61\164\165\x72\x65\40\170\155\x6c\156\x73\x3d\42\x68\164\x74\x70\x3a\57\57\x77\x77\x77\56\x77\63\56\x6f\162\147\57\x32\60\60\x30\57\60\x39\57\x78\x6d\x6c\x64\x73\151\x67\43\x22\76\xd\12\40\x20\74\x53\x69\147\156\x65\x64\111\156\x66\x6f\x3e\xd\xa\40\40\40\40\74\x53\151\x67\x6e\x61\164\165\x72\145\x4d\x65\x74\x68\157\144\x20\x2f\x3e\xd\xa\40\x20\x3c\57\123\x69\x67\156\x65\x64\111\156\146\x6f\76\xd\xa\x3c\57\123\x69\x67\x6e\141\164\165\x72\x65\76";
    public $sigNode = null;
    public $idKeys = array();
    public $idNS = array();
    private $signedInfo = null;
    private $xPathCtx = null;
    private $canonicalMethod = null;
    private $prefix = '';
    private $searchpfx = "\x73\145\x63\x64\x73\151\147";
    private $validatedNodes = null;
    public function __construct($QW = "\x64\163")
    {
        $vr = self::BASE_TEMPLATE;
        if (empty($QW)) {
            goto A8j;
        }
        $this->prefix = $QW . "\72";
        $KP = array("\74\x53", "\x3c\57\x53", "\x78\155\x6c\156\x73\x3d");
        $yw = array("\x3c{$QW}\x3a\x53", "\x3c\57{$QW}\x3a\x53", "\x78\155\154\x6e\x73\72{$QW}\75");
        $vr = str_replace($KP, $yw, $vr);
        A8j:
        $h2 = new DOMDocument();
        $h2->loadXML($vr);
        $this->sigNode = $h2->documentElement;
    }
    private function resetXPathObj()
    {
        $this->xPathCtx = null;
    }
    private function getXPathObj()
    {
        if (!(empty($this->xPathCtx) && !empty($this->sigNode))) {
            goto clj;
        }
        $hd = new DOMXPath($this->sigNode->ownerDocument);
        $hd->registerNamespace("\163\x65\143\144\163\151\147", self::XMLDSIGNS);
        $this->xPathCtx = $hd;
        clj:
        return $this->xPathCtx;
    }
    public static function generateGUID($QW = "\x70\x66\170")
    {
        $Ao = md5(uniqid(mt_rand(), true));
        $fs = $QW . substr($Ao, 0, 8) . "\55" . substr($Ao, 8, 4) . "\x2d" . substr($Ao, 12, 4) . "\55" . substr($Ao, 16, 4) . "\x2d" . substr($Ao, 20, 12);
        return $fs;
    }
    public static function generate_GUID($QW = "\160\x66\x78")
    {
        return self::generateGUID($QW);
    }
    public function locateSignature($Fv, $RF = 0)
    {
        if ($Fv instanceof DOMDocument) {
            goto HSn;
        }
        $vH = $Fv->ownerDocument;
        goto rq5;
        HSn:
        $vH = $Fv;
        rq5:
        if (!$vH) {
            goto HZm;
        }
        $hd = new DOMXPath($vH);
        $hd->registerNamespace("\163\145\x63\x64\163\x69\x67", self::XMLDSIGNS);
        $KK = "\56\57\57\163\145\x63\x64\x73\151\147\72\123\x69\147\156\x61\164\165\x72\x65";
        $Zp = $hd->query($KK, $Fv);
        $this->sigNode = $Zp->item($RF);
        $KK = "\56\57\x73\145\143\144\163\x69\x67\72\123\x69\x67\156\145\x64\x49\156\x66\x6f";
        $Zp = $hd->query($KK, $this->sigNode);
        if (!($Zp->length > 1)) {
            goto WIv;
        }
        throw new Exception("\x49\156\166\x61\x6c\x69\144\x20\163\164\x72\165\143\164\x75\162\x65\x20\x2d\40\124\157\157\40\155\x61\156\x79\x20\123\x69\x67\156\x65\x64\x49\156\146\157\40\x65\x6c\145\155\x65\x6e\164\x73\40\146\x6f\165\156\144");
        WIv:
        return $this->sigNode;
        HZm:
        return null;
    }
    public function createNewSignNode($sb, $tq = null)
    {
        $vH = $this->sigNode->ownerDocument;
        if (!is_null($tq)) {
            goto fgQ;
        }
        $nT = $vH->createElementNS(self::XMLDSIGNS, $this->prefix . $sb);
        goto EEc;
        fgQ:
        $nT = $vH->createElementNS(self::XMLDSIGNS, $this->prefix . $sb, $tq);
        EEc:
        return $nT;
    }
    public function setCanonicalMethod($Vb)
    {
        switch ($Vb) {
            case "\150\164\x74\x70\72\x2f\57\x77\167\167\56\167\63\56\x6f\162\147\x2f\124\x52\x2f\x32\60\x30\x31\57\122\x45\103\55\170\x6d\x6c\x2d\143\61\64\x6e\x2d\62\60\x30\x31\60\63\x31\x35":
            case "\x68\x74\x74\160\x3a\57\57\x77\167\x77\56\x77\63\56\x6f\x72\x67\x2f\x54\x52\x2f\62\x30\60\x31\x2f\x52\x45\x43\x2d\x78\x6d\154\55\143\x31\x34\156\55\x32\60\x30\61\60\x33\x31\x35\x23\x57\151\164\150\103\157\x6d\x6d\145\156\x74\163":
            case "\150\164\x74\x70\x3a\x2f\x2f\x77\167\x77\x2e\167\63\x2e\x6f\162\x67\x2f\x32\60\60\61\x2f\61\60\57\170\155\154\55\145\170\x63\x2d\x63\61\x34\156\43":
            case "\150\x74\164\160\x3a\57\57\167\x77\167\56\167\x33\56\x6f\162\147\57\x32\x30\60\x31\x2f\x31\60\x2f\x78\x6d\x6c\55\145\170\143\x2d\x63\61\x34\x6e\x23\127\151\x74\x68\x43\x6f\155\x6d\145\156\x74\163":
                $this->canonicalMethod = $Vb;
                goto fEr;
            default:
                throw new Exception("\111\156\x76\x61\154\x69\x64\40\103\141\156\x6f\156\151\143\x61\154\40\115\145\x74\x68\157\x64");
        }
        HKx:
        fEr:
        if (!($hd = $this->getXPathObj())) {
            goto ot6;
        }
        $KK = "\x2e\x2f" . $this->searchpfx . "\72\123\151\147\x6e\145\144\111\x6e\146\157";
        $Zp = $hd->query($KK, $this->sigNode);
        if (!($mH = $Zp->item(0))) {
            goto mOE;
        }
        $KK = "\x2e\57" . $this->searchpfx . "\103\141\156\157\x6e\151\x63\141\x6c\x69\x7a\141\x74\151\157\156\115\145\164\x68\x6f\x64";
        $Zp = $hd->query($KK, $mH);
        if ($Ou = $Zp->item(0)) {
            goto hUC;
        }
        $Ou = $this->createNewSignNode("\x43\x61\x6e\157\156\x69\143\x61\x6c\x69\172\141\164\x69\157\156\x4d\x65\164\150\x6f\x64");
        $mH->insertBefore($Ou, $mH->firstChild);
        hUC:
        $Ou->setAttribute("\101\154\147\x6f\162\x69\x74\150\155", $this->canonicalMethod);
        mOE:
        ot6:
    }
    private function canonicalizeData($nT, $aA, $jV = null, $sM = null)
    {
        $Ak = false;
        $ET = false;
        switch ($aA) {
            case "\150\x74\x74\160\72\57\57\x77\x77\x77\x2e\x77\63\56\157\162\147\x2f\x54\122\x2f\62\x30\60\61\x2f\x52\x45\103\55\170\x6d\x6c\55\143\x31\x34\156\55\62\60\x30\x31\x30\x33\x31\65":
                $Ak = false;
                $ET = false;
                goto XiY;
            case "\150\x74\x74\x70\72\57\57\167\167\167\x2e\167\63\56\157\162\x67\x2f\x54\122\x2f\62\x30\60\x31\x2f\x52\105\x43\55\x78\x6d\154\55\x63\x31\64\x6e\x2d\x32\x30\60\61\60\63\x31\65\43\127\x69\x74\x68\x43\x6f\155\155\145\156\164\x73":
                $ET = true;
                goto XiY;
            case "\x68\164\164\160\72\x2f\x2f\167\167\167\x2e\x77\63\x2e\157\162\x67\x2f\x32\60\60\61\x2f\61\x30\x2f\170\155\154\x2d\145\170\143\x2d\143\x31\x34\x6e\43":
                $Ak = true;
                goto XiY;
            case "\x68\x74\164\x70\72\x2f\x2f\167\x77\167\x2e\167\63\x2e\157\162\147\57\x32\x30\x30\61\57\61\x30\57\x78\x6d\x6c\55\145\170\x63\55\x63\61\x34\156\x23\x57\x69\x74\x68\x43\157\155\155\145\x6e\164\163":
                $Ak = true;
                $ET = true;
                goto XiY;
        }
        z3M:
        XiY:
        if (!(is_null($jV) && $nT instanceof DOMNode && $nT->ownerDocument !== null && $nT->isSameNode($nT->ownerDocument->documentElement))) {
            goto nna;
        }
        $yV = $nT;
        ADy:
        if (!($ra = $yV->previousSibling)) {
            goto yJd;
        }
        if (!($ra->nodeType == XML_PI_NODE || $ra->nodeType == XML_COMMENT_NODE && $ET)) {
            goto h9i;
        }
        goto yJd;
        h9i:
        $yV = $ra;
        goto ADy;
        yJd:
        if (!($ra == null)) {
            goto UeV;
        }
        $nT = $nT->ownerDocument;
        UeV:
        nna:
        return $nT->C14N($Ak, $ET, $jV, $sM);
    }
    public function canonicalizeSignedInfo()
    {
        $vH = $this->sigNode->ownerDocument;
        $aA = null;
        if (!$vH) {
            goto BZp;
        }
        $hd = $this->getXPathObj();
        $KK = "\x2e\x2f\x73\x65\x63\144\x73\x69\147\x3a\x53\151\147\156\145\144\111\x6e\146\x6f";
        $Zp = $hd->query($KK, $this->sigNode);
        if (!($Zp->length > 1)) {
            goto w7z;
        }
        throw new Exception("\x49\x6e\x76\141\x6c\x69\144\40\163\x74\x72\165\x63\164\x75\x72\145\x20\x2d\x20\124\157\157\x20\155\141\156\x79\40\x53\151\147\x6e\x65\144\111\156\146\x6f\x20\145\x6c\145\x6d\x65\x6e\x74\x73\x20\x66\157\165\156\144");
        w7z:
        if (!($U3 = $Zp->item(0))) {
            goto f4f;
        }
        $KK = "\56\57\163\x65\143\x64\x73\151\147\72\x43\x61\156\157\x6e\x69\x63\x61\x6c\151\x7a\x61\164\151\157\156\x4d\x65\164\x68\x6f\144";
        $Zp = $hd->query($KK, $U3);
        $sM = null;
        if (!($Ou = $Zp->item(0))) {
            goto RaX;
        }
        $aA = $Ou->getAttribute("\101\154\147\x6f\162\x69\164\150\155");
        foreach ($Ou->childNodes as $nT) {
            if (!($nT->localName == "\111\x6e\143\154\x75\163\x69\166\x65\x4e\141\155\x65\163\x70\141\x63\145\163")) {
                goto V7o;
            }
            if (!($lP = $nT->getAttribute("\x50\x72\145\x66\x69\x78\x4c\151\163\164"))) {
                goto hlh;
            }
            $LT = array_filter(explode("\x20", $lP));
            if (!(count($LT) > 0)) {
                goto iDR;
            }
            $sM = array_merge($sM ? $sM : array(), $LT);
            iDR:
            hlh:
            V7o:
            ArP:
        }
        Vs9:
        RaX:
        $this->signedInfo = $this->canonicalizeData($U3, $aA, null, $sM);
        return $this->signedInfo;
        f4f:
        BZp:
        return null;
    }
    public function calculateDigest($Q1, $zF, $ni = true)
    {
        switch ($Q1) {
            case self::SHA1:
                $jg = "\x73\x68\x61\61";
                goto HY5;
            case self::SHA256:
                $jg = "\163\150\x61\x32\x35\x36";
                goto HY5;
            case self::SHA384:
                $jg = "\163\150\141\x33\x38\x34";
                goto HY5;
            case self::SHA512:
                $jg = "\x73\150\141\65\61\x32";
                goto HY5;
            case self::RIPEMD160:
                $jg = "\162\x69\x70\145\x6d\144\61\x36\x30";
                goto HY5;
            default:
                throw new Exception("\x43\x61\x6e\x6e\x6f\164\40\x76\141\154\x69\x64\141\164\x65\x20\x64\x69\147\145\163\x74\72\x20\125\x6e\x73\165\160\x70\x6f\x72\x74\145\x64\x20\101\x6c\147\x6f\162\x69\164\150\155\x20\x3c{$Q1}\76");
        }
        j1H:
        HY5:
        $LJ = hash($jg, $zF, true);
        if (!$ni) {
            goto cOt;
        }
        $LJ = base64_encode($LJ);
        cOt:
        return $LJ;
    }
    public function validateDigest($kz, $zF)
    {
        $hd = new DOMXPath($kz->ownerDocument);
        $hd->registerNamespace("\163\145\x63\x64\163\151\x67", self::XMLDSIGNS);
        $KK = "\163\164\162\x69\156\147\x28\56\x2f\163\145\143\144\x73\x69\x67\x3a\104\x69\x67\x65\163\x74\x4d\145\164\150\x6f\144\x2f\100\101\x6c\147\x6f\162\x69\164\x68\x6d\x29";
        $Q1 = $hd->evaluate($KK, $kz);
        $B5 = $this->calculateDigest($Q1, $zF, false);
        $KK = "\163\164\x72\151\x6e\x67\x28\56\57\x73\x65\143\x64\x73\x69\147\x3a\x44\x69\147\x65\x73\x74\126\x61\154\x75\x65\51";
        $ST = $hd->evaluate($KK, $kz);
        return $B5 === base64_decode($ST);
    }
    public function processTransforms($kz, $wK, $ED = true)
    {
        $zF = $wK;
        $hd = new DOMXPath($kz->ownerDocument);
        $hd->registerNamespace("\163\x65\143\144\x73\x69\x67", self::XMLDSIGNS);
        $KK = "\x2e\57\163\145\x63\144\163\151\147\72\124\162\x61\x6e\163\146\x6f\x72\155\163\57\163\x65\143\x64\x73\x69\147\72\x54\x72\141\156\163\x66\157\162\x6d";
        $mJ = $hd->query($KK, $kz);
        $MI = "\x68\x74\x74\160\x3a\57\57\167\x77\x77\x2e\167\x33\x2e\157\x72\x67\x2f\124\x52\x2f\62\60\60\x31\57\x52\105\x43\55\x78\155\154\55\143\x31\x34\156\55\x32\60\x30\61\x30\x33\61\65";
        $jV = null;
        $sM = null;
        foreach ($mJ as $rN) {
            $rY = $rN->getAttribute("\x41\x6c\x67\x6f\x72\x69\x74\x68\x6d");
            switch ($rY) {
                case "\x68\164\x74\160\72\57\57\167\167\x77\56\167\63\56\x6f\162\147\57\x32\x30\60\61\x2f\61\x30\57\x78\155\x6c\x2d\x65\x78\143\55\x63\61\64\x6e\43":
                case "\x68\164\x74\160\72\x2f\x2f\167\167\x77\56\x77\x33\56\x6f\x72\x67\x2f\62\x30\x30\x31\57\x31\60\x2f\170\155\x6c\x2d\145\x78\143\55\x63\x31\64\156\x23\127\151\x74\150\x43\157\x6d\155\x65\156\x74\x73":
                    if (!$ED) {
                        goto FUk;
                    }
                    $MI = $rY;
                    goto lLX;
                    FUk:
                    $MI = "\x68\x74\164\x70\72\x2f\57\167\167\167\x2e\x77\x33\56\157\x72\x67\57\x32\60\60\61\x2f\x31\x30\x2f\x78\155\x6c\x2d\145\x78\x63\55\143\x31\x34\x6e\x23";
                    lLX:
                    $nT = $rN->firstChild;
                    qqX:
                    if (!$nT) {
                        goto Nxt;
                    }
                    if (!($nT->localName == "\111\156\x63\x6c\x75\163\151\x76\x65\x4e\x61\x6d\145\163\x70\141\143\x65\163")) {
                        goto Sa7;
                    }
                    if (!($lP = $nT->getAttribute("\x50\162\145\146\x69\170\114\x69\x73\x74"))) {
                        goto Ln6;
                    }
                    $LT = array();
                    $lG = explode("\x20", $lP);
                    foreach ($lG as $lP) {
                        $Au = trim($lP);
                        if (empty($Au)) {
                            goto wVw;
                        }
                        $LT[] = $Au;
                        wVw:
                        bDG:
                    }
                    E7F:
                    if (!(count($LT) > 0)) {
                        goto i16;
                    }
                    $sM = $LT;
                    i16:
                    Ln6:
                    goto Nxt;
                    Sa7:
                    $nT = $nT->nextSibling;
                    goto qqX;
                    Nxt:
                    goto poi;
                case "\150\164\x74\160\x3a\57\x2f\167\167\167\56\x77\63\x2e\157\x72\x67\57\x54\x52\57\x32\60\60\61\x2f\x52\105\x43\x2d\x78\x6d\154\x2d\143\x31\64\x6e\55\62\x30\x30\61\60\x33\61\65":
                case "\x68\164\164\160\72\57\57\x77\x77\167\x2e\x77\x33\x2e\157\x72\147\57\x54\x52\x2f\x32\60\60\x31\57\122\105\103\55\170\x6d\154\55\143\61\x34\x6e\x2d\62\x30\60\61\60\63\61\65\43\127\151\x74\150\103\x6f\x6d\x6d\145\156\164\163":
                    if (!$ED) {
                        goto Xsy;
                    }
                    $MI = $rY;
                    goto yeb;
                    Xsy:
                    $MI = "\x68\164\x74\x70\72\57\57\167\x77\x77\x2e\167\63\56\157\x72\x67\x2f\x54\x52\x2f\62\x30\60\61\57\122\105\x43\x2d\x78\155\x6c\x2d\143\61\x34\156\55\x32\60\60\x31\x30\63\61\x35";
                    yeb:
                    goto poi;
                case "\x68\x74\x74\160\72\57\x2f\x77\x77\x77\x2e\167\63\x2e\157\162\x67\x2f\124\122\57\x31\71\x39\x39\57\x52\x45\x43\x2d\x78\x70\141\164\x68\x2d\x31\x39\71\x39\x31\61\61\x36":
                    $nT = $rN->firstChild;
                    FPJ:
                    if (!$nT) {
                        goto azZ;
                    }
                    if (!($nT->localName == "\130\120\x61\x74\150")) {
                        goto lkk;
                    }
                    $jV = array();
                    $jV["\161\x75\x65\162\x79"] = "\50\56\57\x2f\56\40\x7c\x20\x2e\x2f\57\100\52\x20\x7c\40\56\x2f\57\156\x61\155\145\163\x70\x61\143\x65\x3a\72\52\51\x5b" . $nT->nodeValue . "\x5d";
                    $jV["\x6e\141\155\145\x73\160\141\x63\145\x73"] = array();
                    $P1 = $hd->query("\56\x2f\x6e\x61\155\145\x73\160\141\143\145\x3a\72\x2a", $nT);
                    foreach ($P1 as $jJ) {
                        if (!($jJ->localName != "\170\155\x6c")) {
                            goto Bsg;
                        }
                        $jV["\156\x61\155\145\163\160\141\x63\x65\x73"][$jJ->localName] = $jJ->nodeValue;
                        Bsg:
                        Cvj:
                    }
                    WPi:
                    goto azZ;
                    lkk:
                    $nT = $nT->nextSibling;
                    goto FPJ;
                    azZ:
                    goto poi;
            }
            A20:
            poi:
            A2J:
        }
        rSr:
        if (!$zF instanceof DOMNode) {
            goto aps;
        }
        $zF = $this->canonicalizeData($wK, $MI, $jV, $sM);
        aps:
        return $zF;
    }
    public function processRefNode($kz)
    {
        $pq = null;
        $ED = true;
        if ($Si = $kz->getAttribute("\x55\x52\x49")) {
            goto Ycf;
        }
        $ED = false;
        $pq = $kz->ownerDocument;
        goto MXt;
        Ycf:
        $Ob = parse_url($Si);
        if (!empty($Ob["\x70\x61\164\x68"])) {
            goto Zja;
        }
        if ($Ow = $Ob["\x66\162\x61\x67\155\x65\x6e\164"]) {
            goto CXS;
        }
        $pq = $kz->ownerDocument;
        goto ESe;
        CXS:
        $ED = false;
        $w2 = new DOMXPath($kz->ownerDocument);
        if (!($this->idNS && is_array($this->idNS))) {
            goto wzU;
        }
        foreach ($this->idNS as $fl => $R6) {
            $w2->registerNamespace($fl, $R6);
            Ril:
        }
        m_S:
        wzU:
        $To = "\100\x49\x64\x3d\42" . XPath::filterAttrValue($Ow, XPath::DOUBLE_QUOTE) . "\x22";
        if (!is_array($this->idKeys)) {
            goto O8K;
        }
        foreach ($this->idKeys as $UB) {
            $To .= "\40\157\162\x20\x40" . XPath::filterAttrName($UB) . "\75\42" . XPath::filterAttrValue($Ow, XPath::DOUBLE_QUOTE) . "\x22";
            rGE:
        }
        J0u:
        O8K:
        $KK = "\x2f\x2f\52\x5b" . $To . "\x5d";
        $pq = $w2->query($KK)->item(0);
        ESe:
        Zja:
        MXt:
        $zF = $this->processTransforms($kz, $pq, $ED);
        if ($this->validateDigest($kz, $zF)) {
            goto i9r;
        }
        return false;
        i9r:
        if (!$pq instanceof DOMNode) {
            goto juk;
        }
        if (!empty($Ow)) {
            goto Lbi;
        }
        $this->validatedNodes[] = $pq;
        goto nI2;
        Lbi:
        $this->validatedNodes[$Ow] = $pq;
        nI2:
        juk:
        return true;
    }
    public function getRefNodeID($kz)
    {
        if (!($Si = $kz->getAttribute("\x55\x52\111"))) {
            goto Btt;
        }
        $Ob = parse_url($Si);
        if (!empty($Ob["\160\141\164\x68"])) {
            goto s55;
        }
        if (!($Ow = $Ob["\146\162\141\x67\x6d\x65\156\164"])) {
            goto NSU;
        }
        return $Ow;
        NSU:
        s55:
        Btt:
        return null;
    }
    public function getRefIDs()
    {
        $XV = array();
        $hd = $this->getXPathObj();
        $KK = "\56\x2f\x73\x65\x63\x64\x73\x69\147\72\123\151\x67\156\145\x64\111\156\x66\x6f\133\61\x5d\57\x73\x65\143\x64\x73\151\147\x3a\122\145\x66\145\x72\145\156\x63\x65";
        $Zp = $hd->query($KK, $this->sigNode);
        if (!($Zp->length == 0)) {
            goto Lh_;
        }
        throw new Exception("\x52\145\x66\145\162\145\156\x63\x65\x20\156\x6f\144\145\x73\x20\x6e\x6f\164\x20\x66\x6f\x75\x6e\x64");
        Lh_:
        foreach ($Zp as $kz) {
            $XV[] = $this->getRefNodeID($kz);
            p0a:
        }
        EBL:
        return $XV;
    }
    public function validateReference()
    {
        $m4 = $this->sigNode->ownerDocument->documentElement;
        if ($m4->isSameNode($this->sigNode)) {
            goto xIW;
        }
        if (!($this->sigNode->parentNode != null)) {
            goto Ozx;
        }
        $this->sigNode->parentNode->removeChild($this->sigNode);
        Ozx:
        xIW:
        $hd = $this->getXPathObj();
        $KK = "\56\57\163\145\x63\x64\163\151\x67\72\x53\x69\147\x6e\145\x64\x49\156\x66\157\133\x31\x5d\57\163\145\x63\x64\163\151\x67\72\x52\145\146\145\x72\x65\x6e\x63\x65";
        $Zp = $hd->query($KK, $this->sigNode);
        if (!($Zp->length == 0)) {
            goto lWB;
        }
        throw new Exception("\122\x65\x66\145\162\x65\x6e\143\x65\x20\x6e\x6f\x64\x65\x73\x20\156\157\164\40\x66\157\x75\x6e\x64");
        lWB:
        $this->validatedNodes = array();
        foreach ($Zp as $kz) {
            if ($this->processRefNode($kz)) {
                goto j5K;
            }
            $this->validatedNodes = null;
            throw new Exception("\x52\145\146\145\162\x65\x6e\x63\145\40\x76\141\154\151\x64\x61\164\151\x6f\156\x20\146\x61\x69\x6c\x65\x64");
            j5K:
            OjK:
        }
        VJ0:
        return true;
    }
    private function addRefInternal($R0, $nT, $rY, $P8 = null, $ap = null)
    {
        $QW = null;
        $WR = null;
        $uT = "\x49\144";
        $DB = true;
        $v3 = false;
        if (!is_array($ap)) {
            goto NGi;
        }
        $QW = empty($ap["\160\x72\x65\x66\x69\170"]) ? null : $ap["\x70\x72\x65\146\151\x78"];
        $WR = empty($ap["\160\162\145\x66\x69\x78\137\156\x73"]) ? null : $ap["\160\x72\145\146\151\x78\x5f\x6e\x73"];
        $uT = empty($ap["\151\144\x5f\156\x61\x6d\145"]) ? "\111\x64" : $ap["\151\144\x5f\x6e\141\x6d\145"];
        $DB = !isset($ap["\157\166\x65\162\x77\162\x69\x74\x65"]) ? true : (bool) $ap["\x6f\x76\x65\x72\167\162\151\164\145"];
        $v3 = !isset($ap["\x66\x6f\x72\x63\145\x5f\165\162\151"]) ? false : (bool) $ap["\146\x6f\x72\x63\145\137\x75\x72\x69"];
        NGi:
        $wR = $uT;
        if (empty($QW)) {
            goto Kbs;
        }
        $wR = $QW . "\x3a" . $wR;
        Kbs:
        $kz = $this->createNewSignNode("\x52\145\x66\x65\x72\145\x6e\x63\x65");
        $R0->appendChild($kz);
        if (!$nT instanceof DOMDocument) {
            goto lOK;
        }
        if ($v3) {
            goto j2S;
        }
        goto gmL;
        lOK:
        $Si = null;
        if ($DB) {
            goto lFP;
        }
        $Si = $WR ? $nT->getAttributeNS($WR, $uT) : $nT->getAttribute($uT);
        lFP:
        if (!empty($Si)) {
            goto I0_;
        }
        $Si = self::generateGUID();
        $nT->setAttributeNS($WR, $wR, $Si);
        I0_:
        $kz->setAttribute("\125\122\111", "\43" . $Si);
        goto gmL;
        j2S:
        $kz->setAttribute("\125\x52\111", '');
        gmL:
        $Qk = $this->createNewSignNode("\x54\162\x61\156\163\x66\x6f\x72\155\x73");
        $kz->appendChild($Qk);
        if (is_array($P8)) {
            goto As2;
        }
        if (!empty($this->canonicalMethod)) {
            goto UJ3;
        }
        goto A_l;
        As2:
        foreach ($P8 as $rN) {
            $xo = $this->createNewSignNode("\x54\x72\x61\x6e\x73\146\x6f\162\x6d");
            $Qk->appendChild($xo);
            if (is_array($rN) && !empty($rN["\150\164\x74\x70\72\57\57\x77\167\x77\x2e\x77\x33\56\157\x72\x67\x2f\124\122\57\61\71\71\71\x2f\122\105\103\55\170\x70\x61\164\x68\x2d\61\71\x39\71\x31\61\x31\66"]) && !empty($rN["\150\164\164\160\x3a\x2f\x2f\167\x77\x77\x2e\167\x33\x2e\x6f\162\x67\x2f\x54\122\x2f\61\71\x39\x39\57\122\x45\x43\x2d\170\x70\141\x74\x68\x2d\x31\71\71\71\61\x31\61\x36"]["\161\x75\x65\x72\x79"])) {
                goto S5s;
            }
            $xo->setAttribute("\101\154\147\157\162\x69\x74\150\155", $rN);
            goto w8I;
            S5s:
            $xo->setAttribute("\x41\154\147\x6f\x72\x69\x74\150\x6d", "\150\164\x74\160\x3a\x2f\x2f\x77\x77\x77\x2e\x77\63\x2e\157\162\147\x2f\124\x52\x2f\61\x39\x39\71\x2f\122\105\103\55\x78\160\x61\x74\150\55\61\71\x39\71\61\61\61\66");
            $wf = $this->createNewSignNode("\x58\120\x61\x74\150", $rN["\150\x74\164\160\x3a\57\57\167\167\x77\56\x77\x33\56\157\x72\147\x2f\124\x52\57\x31\x39\71\x39\x2f\122\x45\103\x2d\x78\160\x61\164\x68\x2d\x31\71\x39\x39\x31\61\61\66"]["\161\165\145\162\x79"]);
            $xo->appendChild($wf);
            if (empty($rN["\x68\x74\x74\x70\72\x2f\57\167\x77\167\56\x77\x33\x2e\x6f\162\x67\x2f\x54\122\57\x31\x39\71\71\57\x52\105\103\x2d\170\160\141\x74\150\55\x31\71\x39\71\x31\61\x31\x36"]["\x6e\x61\x6d\145\163\x70\141\x63\145\x73"])) {
                goto ktY;
            }
            foreach ($rN["\x68\164\164\160\x3a\57\57\167\167\167\x2e\x77\63\x2e\157\x72\x67\x2f\124\x52\x2f\61\71\x39\71\x2f\x52\105\103\55\x78\160\141\x74\150\55\x31\71\71\x39\61\x31\61\66"]["\156\141\x6d\145\x73\160\x61\x63\x65\163"] as $QW => $B0) {
                $wf->setAttributeNS("\x68\164\x74\160\x3a\57\x2f\x77\167\x77\56\167\x33\x2e\x6f\162\x67\x2f\62\60\60\60\57\x78\x6d\154\156\x73\57", "\170\155\x6c\x6e\163\x3a{$QW}", $B0);
                wOR:
            }
            tes:
            ktY:
            w8I:
            xxn:
        }
        U8i:
        goto A_l;
        UJ3:
        $xo = $this->createNewSignNode("\124\x72\141\156\163\x66\x6f\x72\x6d");
        $Qk->appendChild($xo);
        $xo->setAttribute("\101\x6c\147\x6f\x72\x69\164\150\155", $this->canonicalMethod);
        A_l:
        $Xk = $this->processTransforms($kz, $nT);
        $B5 = $this->calculateDigest($rY, $Xk);
        $xJ = $this->createNewSignNode("\104\151\x67\x65\163\164\x4d\x65\164\x68\x6f\x64");
        $kz->appendChild($xJ);
        $xJ->setAttribute("\101\x6c\147\157\162\x69\x74\150\x6d", $rY);
        $ST = $this->createNewSignNode("\104\x69\147\145\163\164\126\141\154\165\145", $B5);
        $kz->appendChild($ST);
    }
    public function addReference($nT, $rY, $P8 = null, $ap = null)
    {
        if (!($hd = $this->getXPathObj())) {
            goto ROM;
        }
        $KK = "\56\x2f\163\x65\x63\144\163\x69\147\72\123\x69\147\156\145\x64\111\156\146\x6f";
        $Zp = $hd->query($KK, $this->sigNode);
        if (!($cK = $Zp->item(0))) {
            goto ZFa;
        }
        $this->addRefInternal($cK, $nT, $rY, $P8, $ap);
        ZFa:
        ROM:
    }
    public function addReferenceList($nw, $rY, $P8 = null, $ap = null)
    {
        if (!($hd = $this->getXPathObj())) {
            goto yA4;
        }
        $KK = "\x2e\57\x73\145\143\144\163\x69\147\x3a\x53\151\x67\x6e\145\x64\111\156\146\x6f";
        $Zp = $hd->query($KK, $this->sigNode);
        if (!($cK = $Zp->item(0))) {
            goto IIn;
        }
        foreach ($nw as $nT) {
            $this->addRefInternal($cK, $nT, $rY, $P8, $ap);
            n81:
        }
        Bib:
        IIn:
        yA4:
    }
    public function addObject($zF, $UP = null, $Ev = null)
    {
        $gf = $this->createNewSignNode("\117\142\x6a\145\x63\x74");
        $this->sigNode->appendChild($gf);
        if (empty($UP)) {
            goto w39;
        }
        $gf->setAttribute("\115\x69\155\x65\x54\171\160\145", $UP);
        w39:
        if (empty($Ev)) {
            goto n4Q;
        }
        $gf->setAttribute("\x45\x6e\x63\157\x64\151\x6e\x67", $Ev);
        n4Q:
        if ($zF instanceof DOMElement) {
            goto Bwe;
        }
        $xV = $this->sigNode->ownerDocument->createTextNode($zF);
        goto wQ7;
        Bwe:
        $xV = $this->sigNode->ownerDocument->importNode($zF, true);
        wQ7:
        $gf->appendChild($xV);
        return $gf;
    }
    public function locateKey($nT = null)
    {
        if (!empty($nT)) {
            goto nXO;
        }
        $nT = $this->sigNode;
        nXO:
        if ($nT instanceof DOMNode) {
            goto Yjr;
        }
        return null;
        Yjr:
        if (!($vH = $nT->ownerDocument)) {
            goto O8x;
        }
        $hd = new DOMXPath($vH);
        $hd->registerNamespace("\x73\145\x63\x64\x73\151\x67", self::XMLDSIGNS);
        $KK = "\163\164\162\151\156\147\50\56\57\163\145\x63\x64\163\151\147\x3a\123\x69\x67\156\145\x64\111\x6e\x66\157\x2f\x73\145\x63\144\163\x69\x67\72\x53\151\x67\x6e\141\x74\165\x72\145\x4d\x65\164\150\157\144\x2f\x40\101\154\147\157\162\x69\x74\150\155\51";
        $rY = $hd->evaluate($KK, $nT);
        if (!$rY) {
            goto VSm;
        }
        try {
            $gR = new XMLSecurityKey($rY, array("\x74\x79\160\145" => "\x70\165\x62\x6c\x69\143"));
        } catch (Exception $HI) {
            return null;
        }
        return $gR;
        VSm:
        O8x:
        return null;
    }
    public function verify($gR)
    {
        $vH = $this->sigNode->ownerDocument;
        $hd = new DOMXPath($vH);
        $hd->registerNamespace("\163\145\143\x64\x73\x69\147", self::XMLDSIGNS);
        $KK = "\163\x74\162\x69\156\147\50\56\57\x73\145\143\144\x73\x69\x67\x3a\x53\x69\147\156\x61\164\x75\162\145\126\x61\154\165\x65\51";
        $aK = $hd->evaluate($KK, $this->sigNode);
        if (!empty($aK)) {
            goto soq;
        }
        throw new Exception("\x55\x6e\141\142\x6c\145\40\x74\x6f\40\154\x6f\x63\141\164\145\40\123\151\x67\156\141\164\165\162\x65\x56\141\x6c\165\x65");
        soq:
        return $gR->verifySignature($this->signedInfo, base64_decode($aK));
    }
    public function signData($gR, $zF)
    {
        return $gR->signData($zF);
    }
    public function sign($gR, $Nt = null)
    {
        if (!($Nt != null)) {
            goto rdV;
        }
        $this->resetXPathObj();
        $this->appendSignature($Nt);
        $this->sigNode = $Nt->lastChild;
        rdV:
        if (!($hd = $this->getXPathObj())) {
            goto iAF;
        }
        $KK = "\x2e\57\163\x65\x63\144\x73\151\147\72\123\x69\x67\x6e\x65\x64\111\156\x66\x6f";
        $Zp = $hd->query($KK, $this->sigNode);
        if (!($cK = $Zp->item(0))) {
            goto C83;
        }
        $KK = "\x2e\x2f\x73\x65\x63\144\163\x69\x67\72\x53\x69\x67\x6e\141\164\165\162\x65\x4d\145\x74\x68\x6f\x64";
        $Zp = $hd->query($KK, $cK);
        $QQ = $Zp->item(0);
        $QQ->setAttribute("\101\154\x67\157\x72\x69\164\x68\x6d", $gR->type);
        $zF = $this->canonicalizeData($cK, $this->canonicalMethod);
        $aK = base64_encode($this->signData($gR, $zF));
        $Af = $this->createNewSignNode("\123\151\147\x6e\x61\x74\165\162\145\x56\141\154\x75\145", $aK);
        if ($qy = $cK->nextSibling) {
            goto Aci;
        }
        $this->sigNode->appendChild($Af);
        goto XtQ;
        Aci:
        $qy->parentNode->insertBefore($Af, $qy);
        XtQ:
        C83:
        iAF:
    }
    public function appendCert()
    {
    }
    public function appendKey($gR, $JN = null)
    {
        $gR->serializeKey($JN);
    }
    public function insertSignature($nT, $ct = null)
    {
        $Gx = $nT->ownerDocument;
        $y6 = $Gx->importNode($this->sigNode, true);
        if ($ct == null) {
            goto xzq;
        }
        return $nT->insertBefore($y6, $ct);
        goto eLy;
        xzq:
        return $nT->insertBefore($y6);
        eLy:
    }
    public function appendSignature($DD, $uO = false)
    {
        $ct = $uO ? $DD->firstChild : null;
        return $this->insertSignature($DD, $ct);
    }
    public static function get509XCert($l3, $Y0 = true)
    {
        $rg = self::staticGet509XCerts($l3, $Y0);
        if (empty($rg)) {
            goto NYP;
        }
        return $rg[0];
        NYP:
        return '';
    }
    public static function staticGet509XCerts($rg, $Y0 = true)
    {
        if ($Y0) {
            goto dhN;
        }
        return array($rg);
        goto DFQ;
        dhN:
        $zF = '';
        $IE = array();
        $HX = explode("\xa", $rg);
        $Vq = false;
        foreach ($HX as $Vy) {
            if (!$Vq) {
                goto nxx;
            }
            if (!(strncmp($Vy, "\55\55\55\x2d\55\105\x4e\104\x20\103\x45\122\x54\111\x46\x49\x43\x41\x54\x45", 20) == 0)) {
                goto RuS;
            }
            $Vq = false;
            $IE[] = $zF;
            $zF = '';
            goto J20;
            RuS:
            $zF .= trim($Vy);
            goto tBU;
            nxx:
            if (!(strncmp($Vy, "\x2d\x2d\x2d\x2d\55\x42\x45\107\111\116\x20\103\105\x52\x54\x49\106\111\103\101\x54\x45", 22) == 0)) {
                goto rfy;
            }
            $Vq = true;
            rfy:
            tBU:
            J20:
        }
        ZJP:
        return $IE;
        DFQ:
    }
    public static function staticAdd509Cert($H5, $l3, $Y0 = true, $s8 = false, $hd = null, $ap = null)
    {
        if (!$s8) {
            goto DLP;
        }
        $l3 = file_get_contents($l3);
        DLP:
        if ($H5 instanceof DOMElement) {
            goto Smr;
        }
        throw new Exception("\x49\156\166\141\x6c\x69\144\x20\160\x61\162\145\x6e\164\x20\116\x6f\x64\145\40\160\x61\162\141\x6d\145\x74\145\x72");
        Smr:
        $ie = $H5->ownerDocument;
        if (!empty($hd)) {
            goto LLS;
        }
        $hd = new DOMXPath($H5->ownerDocument);
        $hd->registerNamespace("\x73\x65\x63\144\163\x69\147", self::XMLDSIGNS);
        LLS:
        $KK = "\56\x2f\x73\145\x63\x64\163\151\147\72\x4b\145\171\111\156\146\x6f";
        $Zp = $hd->query($KK, $H5);
        $lg = $Zp->item(0);
        $VM = '';
        if (!$lg) {
            goto FH2;
        }
        $lP = $lg->lookupPrefix(self::XMLDSIGNS);
        if (empty($lP)) {
            goto ttm;
        }
        $VM = $lP . "\x3a";
        ttm:
        goto ptb;
        FH2:
        $lP = $H5->lookupPrefix(self::XMLDSIGNS);
        if (empty($lP)) {
            goto wbn;
        }
        $VM = $lP . "\x3a";
        wbn:
        $Qw = false;
        $lg = $ie->createElementNS(self::XMLDSIGNS, $VM . "\113\x65\x79\x49\156\146\x6f");
        $KK = "\x2e\x2f\x73\145\x63\144\x73\151\147\72\117\142\x6a\145\143\164";
        $Zp = $hd->query($KK, $H5);
        if (!($Zg = $Zp->item(0))) {
            goto i6_;
        }
        $Zg->parentNode->insertBefore($lg, $Zg);
        $Qw = true;
        i6_:
        if ($Qw) {
            goto BNO;
        }
        $H5->appendChild($lg);
        BNO:
        ptb:
        $rg = self::staticGet509XCerts($l3, $Y0);
        $J9 = $ie->createElementNS(self::XMLDSIGNS, $VM . "\130\65\60\71\x44\141\x74\141");
        $lg->appendChild($J9);
        $b_ = false;
        $c_ = false;
        if (!is_array($ap)) {
            goto NEZ;
        }
        if (empty($ap["\151\163\163\165\x65\162\x53\x65\x72\151\141\x6c"])) {
            goto HIl;
        }
        $b_ = true;
        HIl:
        if (empty($ap["\163\x75\x62\x6a\145\143\x74\116\141\155\x65"])) {
            goto GMt;
        }
        $c_ = true;
        GMt:
        NEZ:
        foreach ($rg as $ef) {
            if (!($b_ || $c_)) {
                goto ksv;
            }
            if (!($QM = openssl_x509_parse("\x2d\x2d\x2d\55\x2d\x42\x45\107\x49\116\x20\103\x45\122\124\x49\106\111\103\x41\124\x45\x2d\x2d\x2d\x2d\x2d\xa" . chunk_split($ef, 64, "\12") . "\55\55\x2d\55\55\x45\x4e\104\40\x43\x45\x52\x54\x49\x46\111\103\x41\x54\105\55\x2d\55\55\x2d\12"))) {
                goto ywl;
            }
            if (!($c_ && !empty($QM["\x73\x75\142\x6a\145\143\x74"]))) {
                goto b3W;
            }
            if (is_array($QM["\x73\165\x62\x6a\x65\143\x74"])) {
                goto Dqd;
            }
            $lV = $QM["\151\163\x73\x75\145\162"];
            goto R1M;
            Dqd:
            $Ir = array();
            foreach ($QM["\x73\x75\142\x6a\145\x63\x74"] as $Xr => $tq) {
                if (is_array($tq)) {
                    goto EDY;
                }
                array_unshift($Ir, "{$Xr}\75{$tq}");
                goto EtT;
                EDY:
                foreach ($tq as $EE) {
                    array_unshift($Ir, "{$Xr}\x3d{$EE}");
                    uk9:
                }
                uHv:
                EtT:
                LTI:
            }
            VdK:
            $lV = implode("\x2c", $Ir);
            R1M:
            $F3 = $ie->createElementNS(self::XMLDSIGNS, $VM . "\x58\x35\x30\x39\x53\165\142\x6a\x65\x63\164\x4e\x61\155\x65", $lV);
            $J9->appendChild($F3);
            b3W:
            if (!($b_ && !empty($QM["\151\163\163\x75\x65\162"]) && !empty($QM["\163\145\162\151\x61\154\x4e\x75\x6d\x62\145\x72"]))) {
                goto vjw;
            }
            if (is_array($QM["\151\163\163\x75\x65\x72"])) {
                goto jtt;
            }
            $Wk = $QM["\x69\163\163\x75\x65\162"];
            goto kfO;
            jtt:
            $Ir = array();
            foreach ($QM["\x69\163\x73\x75\145\162"] as $Xr => $tq) {
                array_unshift($Ir, "{$Xr}\x3d{$tq}");
                yqW:
            }
            Z9Z:
            $Wk = implode("\54", $Ir);
            kfO:
            $bz = $ie->createElementNS(self::XMLDSIGNS, $VM . "\x58\x35\60\x39\111\163\163\x75\x65\162\123\145\x72\151\x61\154");
            $J9->appendChild($bz);
            $h9 = $ie->createElementNS(self::XMLDSIGNS, $VM . "\x58\65\x30\x39\111\163\x73\x75\145\x72\x4e\141\x6d\145", $Wk);
            $bz->appendChild($h9);
            $h9 = $ie->createElementNS(self::XMLDSIGNS, $VM . "\x58\65\x30\71\123\145\x72\151\141\x6c\x4e\165\155\x62\x65\162", $QM["\163\x65\162\151\x61\154\x4e\165\155\x62\x65\x72"]);
            $bz->appendChild($h9);
            vjw:
            ywl:
            ksv:
            $ur = $ie->createElementNS(self::XMLDSIGNS, $VM . "\x58\x35\x30\x39\x43\145\162\x74\x69\146\151\143\141\164\x65", $ef);
            $J9->appendChild($ur);
            j1g:
        }
        PoC:
    }
    public function add509Cert($l3, $Y0 = true, $s8 = false, $ap = null)
    {
        if (!($hd = $this->getXPathObj())) {
            goto LB8;
        }
        self::staticAdd509Cert($this->sigNode, $l3, $Y0, $s8, $hd, $ap);
        LB8:
    }
    public function appendToKeyInfo($nT)
    {
        $H5 = $this->sigNode;
        $ie = $H5->ownerDocument;
        $hd = $this->getXPathObj();
        if (!empty($hd)) {
            goto G1z;
        }
        $hd = new DOMXPath($H5->ownerDocument);
        $hd->registerNamespace("\163\x65\x63\x64\x73\151\147", self::XMLDSIGNS);
        G1z:
        $KK = "\x2e\57\x73\x65\x63\144\x73\151\147\72\113\x65\x79\x49\x6e\146\157";
        $Zp = $hd->query($KK, $H5);
        $lg = $Zp->item(0);
        if ($lg) {
            goto ScU;
        }
        $VM = '';
        $lP = $H5->lookupPrefix(self::XMLDSIGNS);
        if (empty($lP)) {
            goto t_X;
        }
        $VM = $lP . "\72";
        t_X:
        $Qw = false;
        $lg = $ie->createElementNS(self::XMLDSIGNS, $VM . "\x4b\145\x79\111\156\x66\x6f");
        $KK = "\x2e\x2f\x73\145\143\144\x73\151\147\72\117\142\152\x65\143\164";
        $Zp = $hd->query($KK, $H5);
        if (!($Zg = $Zp->item(0))) {
            goto mBt;
        }
        $Zg->parentNode->insertBefore($lg, $Zg);
        $Qw = true;
        mBt:
        if ($Qw) {
            goto lCX;
        }
        $H5->appendChild($lg);
        lCX:
        ScU:
        $lg->appendChild($nT);
        return $lg;
    }
    public function getValidatedNodes()
    {
        return $this->validatedNodes;
    }
}
