<?php


namespace RobRichards\XMLSecLibs;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use Exception;
use RobRichards\XMLSecLibs\Utils\XPath;
class XMLSecEnc
{
    const template = "\x3c\170\x65\156\x63\72\105\x6e\143\x72\171\x70\x74\145\x64\104\141\164\x61\x20\x78\155\x6c\156\163\72\x78\x65\156\x63\75\47\x68\x74\x74\x70\x3a\x2f\57\167\x77\167\56\167\63\x2e\x6f\162\147\57\62\60\60\x31\57\60\64\57\170\x6d\x6c\x65\156\x63\43\47\x3e\xd\12\40\40\x20\74\170\x65\x6e\x63\x3a\x43\151\x70\x68\x65\x72\x44\x61\x74\141\x3e\xd\xa\40\x20\40\x20\x20\40\74\x78\x65\156\143\x3a\x43\151\x70\150\x65\x72\x56\x61\154\x75\145\76\74\x2f\170\x65\x6e\143\72\103\x69\160\150\145\x72\126\141\x6c\165\145\76\15\12\x20\x20\x20\x3c\x2f\170\x65\156\143\x3a\x43\x69\x70\x68\x65\x72\104\x61\164\141\76\15\xa\x3c\57\170\x65\x6e\x63\72\x45\156\143\x72\x79\x70\x74\x65\144\104\x61\x74\x61\x3e";
    const Element = "\x68\x74\164\x70\72\x2f\x2f\x77\167\x77\x2e\x77\x33\56\x6f\162\x67\57\x32\x30\x30\x31\57\60\x34\x2f\170\155\x6c\x65\x6e\x63\x23\105\154\145\x6d\x65\156\164";
    const Content = "\x68\164\x74\160\x3a\57\57\x77\x77\167\x2e\x77\63\56\157\x72\147\57\x32\x30\60\x31\x2f\x30\x34\57\x78\x6d\154\145\156\x63\43\103\157\x6e\164\x65\x6e\x74";
    const URI = 3;
    const XMLENCNS = "\x68\x74\x74\x70\72\57\x2f\167\x77\167\x2e\167\63\56\x6f\x72\x67\57\62\60\x30\x31\x2f\x30\x34\x2f\170\155\154\x65\156\x63\43";
    private $encdoc = null;
    private $rawNode = null;
    public $type = null;
    public $encKey = null;
    private $references = array();
    public function __construct()
    {
        $this->_resetTemplate();
    }
    private function _resetTemplate()
    {
        $this->encdoc = new DOMDocument();
        $this->encdoc->loadXML(self::template);
    }
    public function addReference($sb, $nT, $YP)
    {
        if ($nT instanceof DOMNode) {
            goto LRj;
        }
        throw new Exception("\44\156\x6f\x64\x65\x20\151\x73\x20\x6e\x6f\164\x20\157\146\x20\x74\x79\x70\145\40\104\117\115\116\x6f\x64\x65");
        LRj:
        $Un = $this->encdoc;
        $this->_resetTemplate();
        $Tg = $this->encdoc;
        $this->encdoc = $Un;
        $I3 = XMLSecurityDSig::generateGUID();
        $yV = $Tg->documentElement;
        $yV->setAttribute("\111\x64", $I3);
        $this->references[$sb] = array("\156\x6f\144\145" => $nT, "\164\171\160\x65" => $YP, "\145\x6e\x63\156\157\144\145" => $Tg, "\162\145\146\x75\x72\151" => $I3);
    }
    public function setNode($nT)
    {
        $this->rawNode = $nT;
    }
    public function encryptNode($gR, $yw = true)
    {
        $zF = '';
        if (!empty($this->rawNode)) {
            goto pnX;
        }
        throw new Exception("\x4e\x6f\144\145\x20\164\157\40\145\156\x63\x72\171\x70\x74\40\x68\x61\x73\40\156\x6f\x74\x20\142\145\x65\x6e\x20\163\x65\164");
        pnX:
        if ($gR instanceof XMLSecurityKey) {
            goto y6e;
        }
        throw new Exception("\x49\156\x76\x61\154\151\x64\40\x4b\145\x79");
        y6e:
        $vH = $this->rawNode->ownerDocument;
        $w2 = new DOMXPath($this->encdoc);
        $Zs = $w2->query("\x2f\x78\x65\x6e\143\x3a\105\156\x63\x72\x79\x70\164\145\x64\x44\141\x74\x61\57\x78\145\156\143\x3a\103\x69\160\150\145\x72\104\x61\164\141\x2f\x78\x65\x6e\x63\x3a\x43\x69\160\x68\x65\x72\126\x61\154\165\145");
        $cj = $Zs->item(0);
        if (!($cj == null)) {
            goto e0e;
        }
        throw new Exception("\x45\x72\x72\x6f\162\x20\154\x6f\143\141\x74\x69\x6e\x67\40\x43\151\x70\x68\145\x72\126\141\x6c\165\x65\x20\145\x6c\x65\155\x65\156\164\x20\x77\x69\x74\x68\x69\x6e\40\x74\145\155\160\x6c\141\164\145");
        e0e:
        switch ($this->type) {
            case self::Element:
                $zF = $vH->saveXML($this->rawNode);
                $this->encdoc->documentElement->setAttribute("\124\171\x70\145", self::Element);
                goto KGD;
            case self::Content:
                $mA = $this->rawNode->childNodes;
                foreach ($mA as $bl) {
                    $zF .= $vH->saveXML($bl);
                    zXZ:
                }
                sc6:
                $this->encdoc->documentElement->setAttribute("\x54\x79\x70\145", self::Content);
                goto KGD;
            default:
                throw new Exception("\124\171\x70\x65\x20\x69\x73\x20\x63\x75\162\162\x65\x6e\x74\154\x79\x20\x6e\x6f\164\x20\163\165\x70\x70\x6f\x72\x74\x65\144");
        }
        v4k:
        KGD:
        $Bh = $this->encdoc->documentElement->appendChild($this->encdoc->createElementNS(self::XMLENCNS, "\x78\145\156\x63\x3a\x45\156\143\162\x79\160\x74\151\x6f\x6e\115\145\x74\x68\157\144"));
        $Bh->setAttribute("\x41\154\147\x6f\162\151\164\x68\155", $gR->getAlgorithm());
        $cj->parentNode->parentNode->insertBefore($Bh, $cj->parentNode->parentNode->firstChild);
        $Wi = base64_encode($gR->encryptData($zF));
        $tq = $this->encdoc->createTextNode($Wi);
        $cj->appendChild($tq);
        if ($yw) {
            goto Evh;
        }
        return $this->encdoc->documentElement;
        goto GRl;
        Evh:
        switch ($this->type) {
            case self::Element:
                if (!($this->rawNode->nodeType == XML_DOCUMENT_NODE)) {
                    goto teG;
                }
                return $this->encdoc;
                teG:
                $j3 = $this->rawNode->ownerDocument->importNode($this->encdoc->documentElement, true);
                $this->rawNode->parentNode->replaceChild($j3, $this->rawNode);
                return $j3;
            case self::Content:
                $j3 = $this->rawNode->ownerDocument->importNode($this->encdoc->documentElement, true);
                jSx:
                if (!$this->rawNode->firstChild) {
                    goto xtH;
                }
                $this->rawNode->removeChild($this->rawNode->firstChild);
                goto jSx;
                xtH:
                $this->rawNode->appendChild($j3);
                return $j3;
        }
        idE:
        nAh:
        GRl:
    }
    public function encryptReferences($gR)
    {
        $yA = $this->rawNode;
        $PM = $this->type;
        foreach ($this->references as $sb => $ui) {
            $this->encdoc = $ui["\145\156\x63\156\x6f\x64\x65"];
            $this->rawNode = $ui["\x6e\x6f\x64\x65"];
            $this->type = $ui["\164\x79\x70\145"];
            try {
                $dQ = $this->encryptNode($gR);
                $this->references[$sb]["\145\156\x63\156\157\x64\145"] = $dQ;
            } catch (Exception $HI) {
                $this->rawNode = $yA;
                $this->type = $PM;
                throw $HI;
            }
            fvv:
        }
        RnT:
        $this->rawNode = $yA;
        $this->type = $PM;
    }
    public function getCipherValue()
    {
        if (!empty($this->rawNode)) {
            goto Wl7;
        }
        throw new Exception("\x4e\157\144\145\40\x74\157\x20\144\x65\x63\x72\171\x70\x74\x20\x68\141\x73\40\156\x6f\164\40\142\145\145\x6e\x20\163\145\x74");
        Wl7:
        $vH = $this->rawNode->ownerDocument;
        $w2 = new DOMXPath($vH);
        $w2->registerNamespace("\170\x6d\154\145\x6e\x63\x72", self::XMLENCNS);
        $KK = "\56\57\170\x6d\x6c\145\156\x63\x72\x3a\103\151\160\150\x65\162\104\x61\164\141\57\170\155\154\x65\156\x63\x72\x3a\x43\151\160\150\x65\162\126\x61\x6c\x75\x65";
        $Zp = $w2->query($KK, $this->rawNode);
        $nT = $Zp->item(0);
        if ($nT) {
            goto TrK;
        }
        return null;
        TrK:
        return base64_decode($nT->nodeValue);
    }
    public function decryptNode($gR, $yw = true)
    {
        if ($gR instanceof XMLSecurityKey) {
            goto i37;
        }
        throw new Exception("\111\x6e\166\x61\154\x69\144\40\113\145\x79");
        i37:
        $y0 = $this->getCipherValue();
        if ($y0) {
            goto qvT;
        }
        throw new Exception("\x43\x61\156\156\157\x74\40\154\x6f\143\141\x74\145\x20\145\x6e\x63\162\171\x70\164\145\x64\x20\x64\141\164\x61");
        goto VFc;
        qvT:
        $AP = $gR->decryptData($y0);
        if ($yw) {
            goto OBX;
        }
        return $AP;
        goto gAw;
        OBX:
        switch ($this->type) {
            case self::Element:
                $iR = new DOMDocument();
                $iR->loadXML($AP);
                if (!($this->rawNode->nodeType == XML_DOCUMENT_NODE)) {
                    goto aBg;
                }
                return $iR;
                aBg:
                $j3 = $this->rawNode->ownerDocument->importNode($iR->documentElement, true);
                $this->rawNode->parentNode->replaceChild($j3, $this->rawNode);
                return $j3;
            case self::Content:
                if ($this->rawNode->nodeType == XML_DOCUMENT_NODE) {
                    goto dRD;
                }
                $vH = $this->rawNode->ownerDocument;
                goto Kma;
                dRD:
                $vH = $this->rawNode;
                Kma:
                $wz = $vH->createDocumentFragment();
                $wz->appendXML($AP);
                $JN = $this->rawNode->parentNode;
                $JN->replaceChild($wz, $this->rawNode);
                return $JN;
            default:
                return $AP;
        }
        gGq:
        Yt9:
        gAw:
        VFc:
    }
    public function encryptKey($hP, $to, $KS = true)
    {
        if (!(!$hP instanceof XMLSecurityKey || !$to instanceof XMLSecurityKey)) {
            goto UaT;
        }
        throw new Exception("\x49\156\x76\x61\154\x69\x64\x20\x4b\145\171");
        UaT:
        $rx = base64_encode($hP->encryptData($to->key));
        $XN = $this->encdoc->documentElement;
        $Oy = $this->encdoc->createElementNS(self::XMLENCNS, "\x78\145\x6e\x63\x3a\x45\x6e\x63\x72\171\160\164\x65\144\x4b\x65\x79");
        if ($KS) {
            goto y0h;
        }
        $this->encKey = $Oy;
        goto l13;
        y0h:
        $lg = $XN->insertBefore($this->encdoc->createElementNS("\x68\164\x74\x70\72\x2f\57\167\x77\x77\x2e\x77\x33\56\x6f\x72\147\57\x32\60\x30\60\x2f\x30\x39\x2f\170\155\x6c\144\163\x69\147\43", "\144\163\x69\x67\72\113\x65\171\x49\156\146\x6f"), $XN->firstChild);
        $lg->appendChild($Oy);
        l13:
        $Bh = $Oy->appendChild($this->encdoc->createElementNS(self::XMLENCNS, "\170\145\x6e\x63\72\x45\156\x63\162\x79\x70\164\x69\x6f\156\x4d\145\x74\x68\157\144"));
        $Bh->setAttribute("\101\154\x67\157\162\x69\x74\x68\155", $hP->getAlgorith());
        if (empty($hP->name)) {
            goto tO2;
        }
        $lg = $Oy->appendChild($this->encdoc->createElementNS("\150\164\x74\x70\x3a\x2f\57\x77\167\167\x2e\x77\x33\56\x6f\162\x67\x2f\x32\60\x30\60\57\60\x39\x2f\170\155\x6c\x64\163\151\x67\43", "\144\163\x69\147\x3a\x4b\145\171\x49\x6e\x66\157"));
        $lg->appendChild($this->encdoc->createElementNS("\x68\164\x74\x70\x3a\57\x2f\167\167\167\x2e\x77\63\56\x6f\x72\x67\57\x32\x30\60\x30\x2f\60\71\57\x78\x6d\154\144\x73\151\147\43", "\x64\x73\x69\x67\72\113\x65\x79\x4e\141\x6d\145", $hP->name));
        tO2:
        $gu = $Oy->appendChild($this->encdoc->createElementNS(self::XMLENCNS, "\170\145\156\143\72\x43\x69\160\150\x65\162\x44\141\164\x61"));
        $gu->appendChild($this->encdoc->createElementNS(self::XMLENCNS, "\170\145\156\x63\72\x43\x69\x70\x68\x65\x72\126\141\154\165\145", $rx));
        if (!(is_array($this->references) && count($this->references) > 0)) {
            goto q8Y;
        }
        $pM = $Oy->appendChild($this->encdoc->createElementNS(self::XMLENCNS, "\x78\x65\x6e\x63\x3a\122\145\x66\145\162\145\x6e\143\145\x4c\151\163\164"));
        foreach ($this->references as $sb => $ui) {
            $I3 = $ui["\x72\145\146\x75\x72\x69"];
            $ly = $pM->appendChild($this->encdoc->createElementNS(self::XMLENCNS, "\x78\x65\156\143\x3a\x44\x61\x74\141\x52\145\146\x65\x72\145\x6e\x63\145"));
            $ly->setAttribute("\125\122\111", "\x23" . $I3);
            TAi:
        }
        sjZ:
        q8Y:
        return;
    }
    public function decryptKey($Oy)
    {
        if ($Oy->isEncrypted) {
            goto v1E;
        }
        throw new Exception("\x4b\x65\x79\40\151\163\x20\156\x6f\x74\x20\105\156\143\x72\x79\160\164\145\x64");
        v1E:
        if (!empty($Oy->key)) {
            goto d8D;
        }
        throw new Exception("\x4b\x65\x79\x20\x69\x73\40\x6d\151\163\x73\x69\x6e\x67\40\x64\x61\164\141\x20\164\x6f\40\160\145\162\x66\157\x72\155\40\x74\x68\x65\x20\144\x65\x63\x72\x79\x70\164\x69\x6f\x6e");
        d8D:
        return $this->decryptNode($Oy, false);
    }
    public function locateEncryptedData($yV)
    {
        if ($yV instanceof DOMDocument) {
            goto NXi;
        }
        $vH = $yV->ownerDocument;
        goto K3r;
        NXi:
        $vH = $yV;
        K3r:
        if (!$vH) {
            goto uCF;
        }
        $hd = new DOMXPath($vH);
        $KK = "\57\x2f\x2a\x5b\154\x6f\x63\141\x6c\55\x6e\x61\155\x65\50\x29\x3d\47\x45\156\143\x72\171\160\x74\x65\144\104\x61\x74\141\x27\x20\141\156\x64\x20\156\141\155\145\163\160\x61\x63\x65\55\165\162\151\50\51\75\x27" . self::XMLENCNS . "\x27\x5d";
        $Zp = $hd->query($KK);
        return $Zp->item(0);
        uCF:
        return null;
    }
    public function locateKey($nT = null)
    {
        if (!empty($nT)) {
            goto hAH;
        }
        $nT = $this->rawNode;
        hAH:
        if ($nT instanceof DOMNode) {
            goto FyB;
        }
        return null;
        FyB:
        if (!($vH = $nT->ownerDocument)) {
            goto H5z;
        }
        $hd = new DOMXPath($vH);
        $hd->registerNamespace("\x78\155\x6c\x73\145\x63\145\x6e\x63", self::XMLENCNS);
        $KK = "\56\57\x2f\x78\155\154\163\145\143\145\156\x63\72\105\156\143\162\x79\x70\x74\x69\x6f\x6e\115\145\x74\150\157\144";
        $Zp = $hd->query($KK, $nT);
        if (!($JD = $Zp->item(0))) {
            goto Wtj;
        }
        $g4 = $JD->getAttribute("\x41\x6c\147\x6f\162\151\164\150\x6d");
        try {
            $gR = new XMLSecurityKey($g4, array("\x74\x79\160\x65" => "\x70\162\x69\166\141\x74\x65"));
        } catch (Exception $HI) {
            return null;
        }
        return $gR;
        Wtj:
        H5z:
        return null;
    }
    public static function staticLocateKeyInfo($n3 = null, $nT = null)
    {
        if (!(empty($nT) || !$nT instanceof DOMNode)) {
            goto GJD;
        }
        return null;
        GJD:
        $vH = $nT->ownerDocument;
        if ($vH) {
            goto uQT;
        }
        return null;
        uQT:
        $hd = new DOMXPath($vH);
        $hd->registerNamespace("\170\x6d\x6c\163\145\x63\145\x6e\143", self::XMLENCNS);
        $hd->registerNamespace("\170\x6d\154\163\x65\x63\x64\x73\x69\x67", XMLSecurityDSig::XMLDSIGNS);
        $KK = "\x2e\x2f\x78\155\x6c\163\145\x63\x64\x73\151\x67\72\113\145\171\x49\156\x66\x6f";
        $Zp = $hd->query($KK, $nT);
        $JD = $Zp->item(0);
        if ($JD) {
            goto EGw;
        }
        return $n3;
        EGw:
        foreach ($JD->childNodes as $bl) {
            switch ($bl->localName) {
                case "\113\x65\171\116\x61\155\145":
                    if (empty($n3)) {
                        goto faq;
                    }
                    $n3->name = $bl->nodeValue;
                    faq:
                    goto lhs;
                case "\113\145\171\x56\141\154\x75\145":
                    foreach ($bl->childNodes as $Be) {
                        switch ($Be->localName) {
                            case "\104\x53\x41\113\x65\x79\x56\141\154\165\145":
                                throw new Exception("\104\123\101\113\x65\171\x56\141\154\x75\x65\x20\143\x75\x72\162\145\x6e\164\154\171\40\156\157\164\40\x73\165\x70\160\x6f\x72\164\x65\x64");
                            case "\122\123\101\x4b\145\x79\126\x61\154\x75\145":
                                $r8 = null;
                                $Tr = null;
                                if (!($xh = $Be->getElementsByTagName("\x4d\x6f\x64\165\154\x75\163")->item(0))) {
                                    goto wLi;
                                }
                                $r8 = base64_decode($xh->nodeValue);
                                wLi:
                                if (!($pm = $Be->getElementsByTagName("\x45\x78\x70\157\156\145\x6e\164")->item(0))) {
                                    goto G7h;
                                }
                                $Tr = base64_decode($pm->nodeValue);
                                G7h:
                                if (!(empty($r8) || empty($Tr))) {
                                    goto sAT;
                                }
                                throw new Exception("\x4d\x69\163\x73\x69\156\x67\40\115\x6f\144\x75\154\x75\x73\40\x6f\162\x20\x45\170\x70\x6f\156\145\156\x74");
                                sAT:
                                $qp = XMLSecurityKey::convertRSA($r8, $Tr);
                                $n3->loadKey($qp);
                                goto ypb;
                        }
                        EZV:
                        ypb:
                        yMR:
                    }
                    oMO:
                    goto lhs;
                case "\122\x65\164\162\x69\145\166\x61\154\115\145\x74\150\x6f\144":
                    $YP = $bl->getAttribute("\x54\171\x70\x65");
                    if (!($YP !== "\150\164\164\160\72\x2f\x2f\167\167\167\56\x77\x33\56\x6f\162\147\x2f\62\60\x30\x31\x2f\60\x34\57\170\155\x6c\x65\156\143\x23\105\156\x63\162\x79\160\x74\145\x64\113\145\171")) {
                        goto Plt;
                    }
                    goto lhs;
                    Plt:
                    $Si = $bl->getAttribute("\125\122\111");
                    if (!($Si[0] !== "\43")) {
                        goto ZQ5;
                    }
                    goto lhs;
                    ZQ5:
                    $rq = substr($Si, 1);
                    $KK = "\x2f\x2f\170\155\x6c\163\145\x63\x65\156\x63\72\105\156\143\162\171\x70\x74\145\144\113\145\x79\133\100\111\x64\75\x22" . XPath::filterAttrValue($rq, XPath::DOUBLE_QUOTE) . "\x22\x5d";
                    $Im = $hd->query($KK)->item(0);
                    if ($Im) {
                        goto NeG;
                    }
                    throw new Exception("\125\156\141\142\154\x65\x20\x74\157\x20\x6c\x6f\x63\141\164\x65\40\105\156\143\x72\x79\160\x74\x65\144\113\145\171\40\x77\151\x74\150\40\x40\x49\144\x3d\x27{$rq}\47\x2e");
                    NeG:
                    return XMLSecurityKey::fromEncryptedKeyElement($Im);
                case "\x45\156\143\x72\x79\x70\x74\145\x64\113\x65\171":
                    return XMLSecurityKey::fromEncryptedKeyElement($bl);
                case "\130\65\x30\71\x44\x61\164\141":
                    if (!($m0 = $bl->getElementsByTagName("\x58\x35\x30\71\x43\145\162\x74\151\x66\x69\143\x61\x74\145"))) {
                        goto LZM;
                    }
                    if (!($m0->length > 0)) {
                        goto uh6;
                    }
                    $yG = $m0->item(0)->textContent;
                    $yG = str_replace(array("\15", "\12", "\x20"), '', $yG);
                    $yG = "\x2d\55\55\x2d\55\x42\x45\107\111\x4e\x20\103\x45\122\124\x49\x46\x49\x43\101\x54\105\55\55\55\55\x2d\12" . chunk_split($yG, 64, "\12") . "\x2d\55\55\x2d\55\x45\116\x44\40\103\105\122\x54\x49\x46\x49\103\x41\124\x45\x2d\x2d\x2d\55\55\xa";
                    $n3->loadKey($yG, false, true);
                    uh6:
                    LZM:
                    goto lhs;
            }
            XhG:
            lhs:
            vD_:
        }
        slx:
        return $n3;
    }
    public function locateKeyInfo($n3 = null, $nT = null)
    {
        if (!empty($nT)) {
            goto z2Z;
        }
        $nT = $this->rawNode;
        z2Z:
        return self::staticLocateKeyInfo($n3, $nT);
    }
}
