<?php

include_once 'Utilities.php';
include_once 'xmlseclibs.php';
use \RobRichards\XMLSecLibs\XMLSecurityKey;
use \RobRichards\XMLSecLibs\XMLSecurityDSig;
use \RobRichards\XMLSecLibs\XMLSecEnc;
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

    public function __construct(DOMElement $xml = NULL)
    {
        $this->tagName = 'LogoutRequest';

        $this->id = SAMLSPUtilities::generateID();
        $this->issueInstant = time();
        $this->certificates = array();
        $this->validators = array();

        if ($xml === NULL) {
            return;
        }

        if (!$xml->hasAttribute('ID')) {
            throw new Exception('Missing ID attribute on SAML message.');
        }
        $this->id = $xml->getAttribute('ID');

        if ($xml->getAttribute('Version') !== '2.0') {
            /* Currently a very strict check. */
            throw new Exception('Unsupported version: ' . $xml->getAttribute('Version'));
        }

        $this->issueInstant = SAMLSPUtilities::xsDateTimeToTimestamp($xml->getAttribute('IssueInstant'));

        if ($xml->hasAttribute('Destination')) {
            $this->destination = $xml->getAttribute('Destination');
        }


        $issuer = SAMLSPUtilities::xpQuery($xml, './saml_assertion:Issuer');
        if (!empty($issuer)) {
            $this->issuer = trim($issuer[0]->textContent);
        }

        /* Validate the signature element of the message. */
        try {
            $sig = SAMLSPUtilities::validateElement($xml);

            if ($sig !== FALSE) {
                $this->certificates = $sig['Certificates'];
                $this->validators[] = array(
                    'Function' => array('Utilities', 'validateSignature'),
                    'Data' => $sig,
                    );
            }

        } catch (Exception $e) {
            /* Ignore signature validation errors. */
        }

        //$this->extensions = SAML2_XML_samlp_Extensions::getList($xml);

        $this->sessionIndexes = array();

        if ($xml->hasAttribute('NotOnOrAfter')) {
            $this->notOnOrAfter = SAMLSPUtilities::xsDateTimeToTimestamp($xml->getAttribute('NotOnOrAfter'));
        }

        $nameId = SAMLSPUtilities::xpQuery($xml, './saml_assertion:NameID | ./saml_assertion:EncryptedID/xenc:EncryptedData');
        if (empty($nameId)) {
            throw new Exception('Missing <saml:NameID> or <saml:EncryptedID> in <samlp:LogoutRequest>.');
        } elseif (count($nameId) > 1) {
            throw new Exception('More than one <saml:NameID> or <saml:EncryptedD> in <samlp:LogoutRequest>.');
        }
        $nameId = $nameId[0];
        if ($nameId->localName === 'EncryptedData') {
            /* The NameID element is encrypted. */
            $this->encryptedNameId = $nameId;
        } else {
            $this->nameId = SAMLSPUtilities::parseNameId($nameId);
        }

        $sessionIndexes = SAMLSPUtilities::xpQuery($xml, './saml_protocol:SessionIndex');
        foreach ($sessionIndexes as $sessionIndex) {
            $this->sessionIndexes[] = trim($sessionIndex->textContent);
        }
    }

    /**
     * Retrieve the expiration time of this request.
     *
     * @return int|NULL The expiration time of this request.
     */
    public function getNotOnOrAfter()
    {
        return $this->notOnOrAfter;
    }

    /**
     * Set the expiration time of this request.
     *
     * @param int|NULL $notOnOrAfter The expiration time of this request.
     */
    public function setNotOnOrAfter($notOnOrAfter)
    {
      $this->notOnOrAfter = $notOnOrAfter;
    }

    /**
     * Check whether the NameId is encrypted.
     *
     * @return TRUE if the NameId is encrypted, FALSE if not.
     */
    public function isNameIdEncrypted()
    {
        if ($this->encryptedNameId !== NULL) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Encrypt the NameID in the LogoutRequest.
     *
     * @param XMLSecurityKey $key The encryption key.
     */
    public function encryptNameId(XMLSecurityKey $key)
    {
        /* First create a XML representation of the NameID. */
        $doc = new DOMDocument();
        $root = $doc->createElement('root');
        $doc->appendChild($root);
        SAML2_Utils::addNameId($root, $this->nameId);
        $nameId = $root->firstChild;

        SAML2_Utils::getContainer()->debugMessage($nameId, 'encrypt');

        /* Encrypt the NameID. */
        $enc = new XMLSecEnc();
        $enc->setNode($nameId);
        $enc->type = XMLSecEnc::Element;

        $symmetricKey = new XMLSecurityKey(XMLSecurityKey::AES128_CBC);
        $symmetricKey->generateSessionKey();
        $enc->encryptKey($key, $symmetricKey);

        $this->encryptedNameId = $enc->encryptNode($symmetricKey);
        $this->nameId = NULL;
    }

    /**
     * Decrypt the NameID in the LogoutRequest.
     *
     * @param XMLSecurityKey $key       The decryption key.
     * @param array          $blacklist Blacklisted decryption algorithms.
     */
    public function decryptNameId(XMLSecurityKey $key, array $blacklist = array())
    {
        if ($this->encryptedNameId === NULL) {
            /* No NameID to decrypt. */

            return;
        }

        $nameId = SAML2_Utils::decryptElement($this->encryptedNameId, $key, $blacklist);
        SAML2_Utils::getContainer()->debugMessage($nameId, 'decrypt');
        $this->nameId = SAML2_Utils::parseNameId($nameId);

        $this->encryptedNameId = NULL;
    }

    /**
     * Retrieve the name identifier of the session that should be terminated.
     *
     * @return array The name identifier of the session that should be terminated.
     * @throws Exception
     */
    public function getNameId()
    {
        if ($this->encryptedNameId !== NULL) {
            throw new Exception('Attempted to retrieve encrypted NameID without decrypting it first.');
        }

        return $this->nameId;
    }

    /**
     * Set the name identifier of the session that should be terminated.
     *
     * The name identifier must be in the format accepted by SAML2_message::buildNameId().
     *
     * @see SAML2_message::buildNameId()
     * @param array $nameId The name identifier of the session that should be terminated.
     */
    public function setNameId($nameId)
    {
       $this->nameId = $nameId;
    }

    /**
     * Retrieve the SessionIndexes of the sessions that should be terminated.
     *
     * @return array The SessionIndexes, or an empty array if all sessions should be terminated.
     */
    public function getSessionIndexes()
    {
        return $this->sessionIndexes;
    }

    /**
     * Set the SessionIndexes of the sessions that should be terminated.
     *
     * @param array $sessionIndexes The SessionIndexes, or an empty array if all sessions should be terminated.
     */
    public function setSessionIndexes(array $sessionIndexes)
    {
        $this->sessionIndexes = $sessionIndexes;
    }

    /**
     * Retrieve the sesion index of the session that should be terminated.
     *
     * @return string|NULL The sesion index of the session that should be terminated.
     */
    public function getSessionIndex()
    {
        if (empty($this->sessionIndexes)) {
            return NULL;
        }

        return $this->sessionIndexes[0];
    }

    /**
     * Set the sesion index of the session that should be terminated.
     *
     * @param string|NULL $sessionIndex The sesion index of the session that should be terminated.
     */
    public function setSessionIndex($sessionIndex)
    {
       if (is_null($sessionIndex)) {
            $this->sessionIndexes = array();
        } else {
            $this->sessionIndexes = array($sessionIndex);
        }
    }


    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        
        $this->id = $id;
    }
    
    public function getIssueInstant()
    {
        return $this->issueInstant;
    }

    public function setIssueInstant($issueInstant)
    {
        $this->issueInstant = $issueInstant;
    }

    public function getDestination()
    {
        return $this->destination;
    }

    public function setDestination($destination)
    {
        $this->destination = $destination;
    }

    public function getIssuer()
    {
        return $this->issuer;
    }

    public function setIssuer($issuer)
    {
        $this->issuer = $issuer;
    }
}
