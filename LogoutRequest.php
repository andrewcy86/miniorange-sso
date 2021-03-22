<?php


include_once "\x55\164\x69\154\151\164\151\x65\163\x2e\x70\x68\x70";
include_once "\170\155\154\163\145\143\154\151\x62\163\56\x70\x68\160";
use RobRichards\XMLSecLibs\XMLSecurityKey;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecEnc;
class SAML2SPLogoutRequest
{
    private $tagName;
    private $id;
    private $issuer;
    private $destination;
    private $issueInstant;
    private $certificates;
    private $validators;
    private $notOnOrAfter;
    private $encryptedNameId;
    private $nameId;
    private $sessionIndexes;
    public function __construct(DOMElement $pb = NULL)
    {
        $this->tagName = "\114\x6f\x67\x6f\x75\164\x52\145\x71\x75\145\x73\x74";
        $this->id = SAMLSPUtilities::generateID();
        $this->issueInstant = time();
        $this->certificates = array();
        $this->validators = array();
        if (!($pb === NULL)) {
            goto e1;
        }
        return;
        e1:
        if ($pb->hasAttribute("\111\104")) {
            goto j6;
        }
        throw new Exception("\115\151\x73\163\x69\156\x67\x20\x49\104\40\x61\164\x74\x72\x69\x62\x75\164\x65\40\157\156\40\x53\101\115\114\40\155\x65\163\x73\141\147\x65\x2e");
        j6:
        $this->id = $pb->getAttribute("\111\104");
        if (!($pb->getAttribute("\x56\145\162\x73\151\157\x6e") !== "\x32\56\60")) {
            goto Uk;
        }
        throw new Exception("\x55\x6e\163\165\x70\160\x6f\162\x74\x65\144\x20\x76\145\x72\x73\x69\x6f\x6e\x3a\x20" . $pb->getAttribute("\126\145\x72\x73\x69\x6f\x6e"));
        Uk:
        $this->issueInstant = SAMLSPUtilities::xsDateTimeToTimestamp($pb->getAttribute("\111\x73\x73\x75\x65\111\x6e\163\164\x61\x6e\x74"));
        if (!$pb->hasAttribute("\x44\x65\163\164\x69\x6e\x61\164\x69\x6f\156")) {
            goto C9;
        }
        $this->destination = $pb->getAttribute("\104\x65\x73\164\151\x6e\x61\x74\151\157\x6e");
        C9:
        $VB = SAMLSPUtilities::xpQuery($pb, "\x2e\x2f\x73\x61\x6d\x6c\137\141\x73\x73\x65\x72\164\x69\157\156\x3a\x49\163\163\165\145\162");
        if (empty($VB)) {
            goto x0;
        }
        $this->issuer = trim($VB[0]->textContent);
        x0:
        try {
            $pW = SAMLSPUtilities::validateElement($pb);
            if (!($pW !== FALSE)) {
                goto ZX;
            }
            $this->certificates = $pW["\103\x65\x72\x74\151\x66\151\143\141\x74\145\163"];
            $this->validators[] = array("\x46\165\x6e\x63\164\x69\157\156" => array("\x55\164\x69\x6c\x69\164\x69\145\x73", "\x76\141\154\151\144\141\x74\145\123\x69\147\156\x61\164\x75\x72\145"), "\x44\x61\164\141" => $pW);
            ZX:
        } catch (Exception $HI) {
        }
        $this->sessionIndexes = array();
        if (!$pb->hasAttribute("\x4e\157\x74\117\156\x4f\x72\101\146\x74\145\162")) {
            goto DZ;
        }
        $this->notOnOrAfter = SAMLSPUtilities::xsDateTimeToTimestamp($pb->getAttribute("\x4e\x6f\x74\117\x6e\117\x72\x41\x66\x74\x65\162"));
        DZ:
        $A3 = SAMLSPUtilities::xpQuery($pb, "\56\57\163\x61\155\154\137\x61\163\163\145\162\x74\x69\157\156\x3a\116\141\155\x65\x49\104\x20\174\x20\56\x2f\163\x61\x6d\154\x5f\x61\x73\x73\x65\x72\x74\x69\157\156\72\105\x6e\x63\x72\x79\x70\x74\x65\x64\111\x44\x2f\x78\x65\156\143\72\105\x6e\143\x72\171\160\164\145\144\x44\141\x74\x61");
        if (empty($A3)) {
            goto az;
        }
        if (count($A3) > 1) {
            goto cn;
        }
        goto TL;
        az:
        throw new Exception("\115\x69\x73\x73\x69\x6e\x67\x20\x3c\x73\x61\155\x6c\72\116\x61\155\145\111\104\x3e\40\157\x72\x20\74\x73\x61\x6d\x6c\72\105\x6e\143\x72\x79\x70\164\x65\x64\111\104\76\40\x69\156\x20\74\163\x61\155\x6c\x70\72\x4c\x6f\147\x6f\x75\164\122\145\x71\x75\x65\x73\x74\x3e\56");
        goto TL;
        cn:
        throw new Exception("\115\x6f\162\x65\40\164\x68\x61\x6e\x20\x6f\x6e\x65\40\x3c\x73\x61\x6d\x6c\72\116\x61\x6d\x65\x49\x44\x3e\x20\157\162\40\74\163\141\x6d\154\72\x45\x6e\x63\162\x79\160\164\145\x64\104\x3e\40\x69\156\x20\x3c\x73\141\155\154\160\x3a\x4c\157\147\157\165\164\x52\x65\161\165\145\163\164\76\56");
        TL:
        $A3 = $A3[0];
        if ($A3->localName === "\x45\x6e\x63\x72\171\160\164\x65\144\x44\x61\164\x61") {
            goto AM;
        }
        $this->nameId = SAMLSPUtilities::parseNameId($A3);
        goto sB;
        AM:
        $this->encryptedNameId = $A3;
        sB:
        $GO = SAMLSPUtilities::xpQuery($pb, "\56\57\163\141\x6d\154\x5f\x70\162\x6f\164\157\143\157\154\x3a\x53\145\x73\x73\x69\x6f\x6e\x49\156\144\145\170");
        foreach ($GO as $PO) {
            $this->sessionIndexes[] = trim($PO->textContent);
            HH:
        }
        hl:
    }
    public function getNotOnOrAfter()
    {
        return $this->notOnOrAfter;
    }
    public function setNotOnOrAfter($i1)
    {
        $this->notOnOrAfter = $i1;
    }
    public function isNameIdEncrypted()
    {
        if (!($this->encryptedNameId !== NULL)) {
            goto pf;
        }
        return TRUE;
        pf:
        return FALSE;
    }
    public function encryptNameId(XMLSecurityKey $Xr)
    {
        $vH = new DOMDocument();
        $XN = $vH->createElement("\162\x6f\x6f\x74");
        $vH->appendChild($XN);
        SAML2_Utils::addNameId($XN, $this->nameId);
        $A3 = $XN->firstChild;
        SAML2_Utils::getContainer()->debugMessage($A3, "\145\x6e\143\162\x79\x70\164");
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
            goto EK;
        }
        return;
        EK:
        $A3 = SAML2_Utils::decryptElement($this->encryptedNameId, $Xr, $ey);
        SAML2_Utils::getContainer()->debugMessage($A3, "\x64\x65\143\162\x79\x70\x74");
        $this->nameId = SAML2_Utils::parseNameId($A3);
        $this->encryptedNameId = NULL;
    }
    public function getNameId()
    {
        if (!($this->encryptedNameId !== NULL)) {
            goto WT;
        }
        throw new Exception("\101\164\x74\x65\x6d\x70\x74\x65\x64\40\164\157\x20\162\x65\x74\x72\151\x65\166\x65\40\145\156\x63\x72\x79\x70\164\x65\144\40\116\x61\155\x65\111\x44\40\167\151\164\x68\157\165\x74\40\x64\x65\143\162\171\160\164\151\x6e\x67\x20\151\x74\40\146\x69\x72\x73\x74\56");
        WT:
        return $this->nameId;
    }
    public function setNameId($A3)
    {
        $this->nameId = $A3;
    }
    public function getSessionIndexes()
    {
        return $this->sessionIndexes;
    }
    public function setSessionIndexes(array $GO)
    {
        $this->sessionIndexes = $GO;
    }
    public function getSessionIndex()
    {
        if (!empty($this->sessionIndexes)) {
            goto AB;
        }
        return NULL;
        AB:
        return $this->sessionIndexes[0];
    }
    public function setSessionIndex($PO)
    {
        if (is_null($PO)) {
            goto DU;
        }
        $this->sessionIndexes = array($PO);
        goto gu;
        DU:
        $this->sessionIndexes = array();
        gu:
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
    public function getDestination()
    {
        return $this->destination;
    }
    public function setDestination($sY)
    {
        $this->destination = $sY;
    }
    public function getIssuer()
    {
        return $this->issuer;
    }
    public function setIssuer($VB)
    {
        $this->issuer = $VB;
    }
}
