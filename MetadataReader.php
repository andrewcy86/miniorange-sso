<?php


include_once "\x55\164\x69\154\151\x74\151\145\163\x2e\x70\150\x70";
class IDPMetadataReader
{
    private $identityProviders;
    private $serviceProviders;
    public function __construct(DOMNode $pb = NULL)
    {
        $this->identityProviders = array();
        $this->serviceProviders = array();
        $aB = SAMLSPUtilities::xpQuery($pb, "\56\x2f\163\141\x6d\154\137\155\145\x74\141\x64\x61\164\x61\72\x45\156\x74\x69\x74\171\104\145\x73\x63\x72\x69\x70\x74\x6f\x72");
        foreach ($aB as $tO) {
            $Ln = SAMLSPUtilities::xpQuery($tO, "\x2e\57\x73\x61\155\154\x5f\155\145\x74\x61\144\x61\x74\141\72\111\x44\x50\x53\x53\x4f\x44\145\x73\x63\x72\x69\x70\164\157\x72");
            if (!(isset($Ln) && !empty($Ln))) {
                goto xx;
            }
            array_push($this->identityProviders, new IdentityProviders($tO));
            xx:
            q2:
        }
        rE:
    }
    public function getIdentityProviders()
    {
        return $this->identityProviders;
    }
    public function getServiceProviders()
    {
        return $this->serviceProviders;
    }
}
class IdentityProviders
{
    private $idpName;
    private $entityID;
    private $loginDetails;
    private $logoutDetails;
    private $signingCertificate;
    private $encryptionCertificate;
    private $signedRequest;
    public function __construct(DOMElement $pb = NULL)
    {
        $this->idpName = '';
        $this->nameIDFormat = '';
        $this->loginDetails = array();
        $this->logoutDetails = array();
        $this->signingCertificate = array();
        $this->encryptionCertificate = array();
        if (!$pb->hasAttribute("\x65\156\164\x69\x74\x79\x49\x44")) {
            goto fS;
        }
        $this->entityID = $pb->getAttribute("\145\x6e\164\x69\164\x79\111\x44");
        fS:
        if (!$pb->hasAttribute("\127\141\x6e\x74\x41\x75\x74\150\156\122\x65\161\x75\145\x73\164\163\x53\x69\147\x6e\145\x64")) {
            goto wt;
        }
        $this->signedRequest = $pb->getAttribute("\x57\141\156\x74\x41\x75\x74\x68\x6e\x52\x65\161\x75\145\163\x74\x73\x53\151\147\156\x65\x64");
        wt:
        $Ln = SAMLSPUtilities::xpQuery($pb, "\x2e\57\x73\141\155\154\x5f\x6d\x65\164\141\144\141\164\x61\x3a\x49\104\x50\x53\123\x4f\x44\x65\x73\143\162\x69\160\x74\157\162");
        if (count($Ln) > 1) {
            goto CV;
        }
        if (empty($Ln)) {
            goto tG;
        }
        goto Kj;
        CV:
        throw new Exception("\115\157\162\x65\40\164\x68\x61\x6e\x20\157\156\x65\x20\x3c\x49\104\x50\x53\x53\x4f\104\145\163\143\162\151\160\164\x6f\x72\x3e\x20\x69\156\40\x3c\105\x6e\164\x69\164\x79\x44\x65\x73\143\162\x69\160\164\157\162\x3e\56");
        goto Kj;
        tG:
        throw new Exception("\115\151\x73\x73\151\x6e\x67\40\162\145\x71\165\151\x72\145\x64\x20\x3c\111\x44\120\x53\123\117\104\145\x73\143\x72\x69\x70\x74\157\162\76\40\x69\x6e\x20\74\x45\x6e\x74\151\x74\171\104\145\163\143\162\151\160\x74\157\x72\76\56");
        Kj:
        $N3 = $Ln[0];
        $i8 = SAMLSPUtilities::xpQuery($pb, "\x2e\57\x73\141\x6d\154\137\155\145\x74\141\x64\x61\164\x61\72\105\170\164\x65\156\163\x69\157\x6e\x73");
        if (!$i8) {
            goto Jg;
        }
        $this->parseInfo($N3);
        Jg:
        $this->parseSSOService($N3);
        $this->parseSLOService($N3);
        $this->parsex509Certificate($N3);
    }
    private function parseInfo($pb)
    {
        $oy = SAMLSPUtilities::xpQuery($pb, "\x2e\57\155\x64\x75\x69\x3a\x55\111\111\x6e\x66\157\x2f\155\144\x75\151\72\104\151\163\160\154\141\171\x4e\141\155\145");
        foreach ($oy as $sb) {
            if (!($sb->hasAttribute("\x78\155\x6c\72\154\x61\x6e\x67") && $sb->getAttribute("\x78\x6d\x6c\x3a\x6c\x61\x6e\147") == "\145\x6e")) {
                goto jE;
            }
            $this->idpName = $sb->textContent;
            jE:
            OZ:
        }
        k8:
    }
    private function parseSSOService($pb)
    {
        $JV = SAMLSPUtilities::xpQuery($pb, "\x2e\x2f\x73\141\x6d\154\137\x6d\145\164\141\144\141\x74\141\72\123\x69\156\x67\154\x65\x53\x69\147\x6e\117\156\x53\x65\162\x76\x69\143\x65");
        foreach ($JV as $aV) {
            $yH = str_replace("\x75\162\x6e\72\x6f\x61\163\x69\x73\x3a\x6e\141\155\x65\163\x3a\x74\143\72\123\x41\115\x4c\x3a\x32\x2e\60\72\142\151\x6e\144\x69\156\147\163\72", '', $aV->getAttribute("\102\151\x6e\144\x69\156\147"));
            $this->loginDetails = array_merge($this->loginDetails, array($yH => $aV->getAttribute("\114\157\x63\141\x74\x69\157\156")));
            Xb:
        }
        a9:
    }
    private function parseSLOService($pb)
    {
        $MQ = SAMLSPUtilities::xpQuery($pb, "\x2e\x2f\163\141\155\154\x5f\x6d\145\x74\141\144\141\164\141\72\123\151\x6e\147\154\x65\114\157\x67\x6f\165\x74\x53\145\162\x76\151\x63\x65");
        if (!empty($MQ)) {
            goto uY;
        }
        $this->logoutDetails = array("\x48\x54\124\x50\x2d\122\145\x64\151\x72\x65\x63\x74" => '');
        goto bO;
        uY:
        foreach ($MQ as $u1) {
            $yH = str_replace("\165\162\156\72\x6f\141\x73\x69\163\x3a\156\x61\x6d\145\163\72\x74\x63\x3a\x53\101\115\114\72\62\x2e\x30\72\x62\x69\156\x64\x69\x6e\x67\163\x3a", '', $u1->getAttribute("\102\151\x6e\144\151\x6e\147"));
            $this->logoutDetails = array_merge($this->logoutDetails, array($yH => $u1->getAttribute("\114\x6f\x63\x61\164\151\x6f\156")));
            nC:
        }
        Or1:
        bO:
    }
    private function parsex509Certificate($pb)
    {
        foreach (SAMLSPUtilities::xpQuery($pb, "\x2e\x2f\x73\x61\x6d\154\x5f\155\145\164\141\144\x61\164\141\x3a\x4b\x65\171\x44\145\x73\143\x72\151\x70\x74\x6f\162") as $bX) {
            if ($bX->hasAttribute("\x75\163\145")) {
                goto tb;
            }
            $this->parseSigningCertificate($bX);
            goto Xm;
            tb:
            if ($bX->getAttribute("\165\163\x65") == "\x65\156\x63\x72\171\x70\x74\151\x6f\156") {
                goto wW;
            }
            $this->parseSigningCertificate($bX);
            goto Fc;
            wW:
            $this->parseEncryptionCertificate($bX);
            Fc:
            Xm:
            Kr:
        }
        Zo:
    }
    private function parseSigningCertificate($pb)
    {
        $b2 = SAMLSPUtilities::xpQuery($pb, "\x2e\x2f\144\163\72\113\x65\171\111\156\146\x6f\x2f\144\163\72\130\65\60\x39\104\x61\164\x61\57\x64\163\72\130\65\60\x39\103\x65\x72\164\x69\x66\x69\143\141\164\145");
        $QM = trim($b2[0]->textContent);
        $QM = str_replace(array("\15", "\xa", "\11", "\x20"), '', $QM);
        if (empty($b2)) {
            goto HC;
        }
        array_push($this->signingCertificate, SAMLSPUtilities::sanitize_certificate($QM));
        HC:
    }
    private function parseEncryptionCertificate($pb)
    {
        $b2 = SAMLSPUtilities::xpQuery($pb, "\56\x2f\144\163\72\x4b\145\x79\x49\x6e\x66\157\57\x64\163\72\130\65\x30\71\x44\141\x74\141\57\144\x73\72\130\65\x30\71\103\145\162\164\x69\x66\151\x63\141\164\145");
        $QM = trim($b2[0]->textContent);
        $QM = str_replace(array("\xd", "\xa", "\x9", "\40"), '', $QM);
        if (empty($b2)) {
            goto UR;
        }
        array_push($this->encryptionCertificate, $QM);
        UR:
    }
    public function getIdpName()
    {
        return $this->idpName;
    }
    public function getEntityID()
    {
        return $this->entityID;
    }
    public function getLoginURL($yH)
    {
        return $this->loginDetails[$yH];
    }
    public function getLogoutURL($yH)
    {
        return $this->logoutDetails[$yH];
    }
    public function getLoginDetails()
    {
        return $this->loginDetails;
    }
    public function getLogoutDetails()
    {
        return $this->logoutDetails;
    }
    public function getSigningCertificate()
    {
        return $this->signingCertificate;
    }
    public function getEncryptionCertificate()
    {
        return $this->encryptionCertificate[0];
    }
    public function isRequestSigned()
    {
        return $this->signedRequest;
    }
}
