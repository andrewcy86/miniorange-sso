<?php


include "\x41\x73\x73\145\162\164\151\x6f\156\x2e\x70\150\160";
class SAML2SPResponse
{
    private $assertions;
    private $destination;
    private $certificates;
    private $signatureData;
    public function __construct(DOMElement $pb = NULL, $v1)
    {
        $this->assertions = array();
        $this->certificates = array();
        if (!($pb === NULL)) {
            goto HD;
        }
        return;
        HD:
        $pW = SAMLSPUtilities::validateElement($pb);
        if (!($pW !== FALSE)) {
            goto gw;
        }
        $this->certificates = $pW["\103\145\162\x74\x69\x66\151\143\x61\164\x65\163"];
        $this->signatureData = $pW;
        gw:
        if (!$pb->hasAttribute("\104\145\x73\164\151\156\x61\164\x69\x6f\156")) {
            goto gl;
        }
        $this->destination = $pb->getAttribute("\x44\x65\x73\164\151\156\141\x74\151\x6f\x6e");
        gl:
        $nT = $pb->firstChild;
        qO:
        if (!($nT !== NULL)) {
            goto gB;
        }
        if (!($nT->namespaceURI !== "\165\x72\x6e\72\157\141\x73\x69\163\72\x6e\141\x6d\x65\163\72\x74\143\72\123\x41\x4d\114\x3a\x32\56\60\72\141\x73\163\x65\x72\x74\x69\157\156")) {
            goto qf;
        }
        goto aE;
        qf:
        if (!($nT->localName === "\101\x73\163\145\162\x74\x69\x6f\x6e" || $nT->localName === "\x45\x6e\x63\x72\x79\x70\x74\x65\x64\101\163\163\145\x72\x74\x69\157\156")) {
            goto F1;
        }
        $this->assertions[] = new SAML2SPAssertion($nT, $v1);
        F1:
        aE:
        $nT = $nT->nextSibling;
        goto qO;
        gB:
    }
    public function getAssertions()
    {
        return $this->assertions;
    }
    public function setAssertions(array $tr)
    {
        $this->assertions = $tr;
    }
    public function getDestination()
    {
        return $this->destination;
    }
    public function getCertificates()
    {
        return $this->certificates;
    }
    public function getSignatureData()
    {
        return $this->signatureData;
    }
}
