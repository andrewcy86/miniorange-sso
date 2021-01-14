<?php
class CertificateUtility {
	
	public static function generate_certificate($dn, $config, $expiryTimeInDays) {
		/*Fill in data for the distinguished name to be used in the cert
		You must change the values of these keys to match your name and
		company, or more precisely, the name and company of the person/site
		that you are generating the certificate for.
		For SSL certificates, the commonName is usually the domain name of
		that will be using the certificate, but for S/MIME certificates,
		the commonName will be the name of the individual who will use the
		certificate.
		$dn = array(
			"countryName" => "UK",
			"stateOrProvinceName" => "Somerset",
			"localityName" => "Glastonbury",
			"organizationName" => "The Brain Room Limited",
			"organizationalUnitName" => "PHP Documentation Team",
			"commonName" => "Wez Furlong",
			"emailAddress" => "wez@example.com"
		);

		$config = array(
		  "digest_alg" => "sha256",
		  'x509_extensions' => 'v3_ca',
		  "private_key_bits" => 2048,
		  "private_key_type" => OPENSSL_KEYTYPE_RSA,
		  "encrypt_key" => false
		);*/

		// Generate a new private (and public) key pair
		$private_key = openssl_pkey_new();

		// Generate a certificate signing request
		$csr = openssl_csr_new($dn, $private_key, $config);

		// You will usually want to create a self-signed certificate at this
		// point until your CA fulfills your request.
		// This creates a self-signed cert that is valid for 365 days
		$csr_signed = openssl_csr_sign($csr, null, $private_key, $expiryTimeInDays, $config, time());

		// Now you will want to preserve your private key, CSR and self-signed
		// cert so that they can be installed into your web server, mail server
		// or mail client (depending on the intended use of the certificate).
		// This example shows how to get those things into variables, but you
		// can also store them directly into files.
		// Typically, you will send the CSR on to your CA who will then issue
		// you with the "real" certificate.
		openssl_csr_export($csr, $csrout);
		openssl_x509_export($csr_signed, $public_key_out);
		openssl_pkey_export($private_key, $private_key_out);
		
		while (($e = openssl_error_string()) !== false) {
			error_log("CertificateUtility: Error generating certificate. " . $e);
			//return FALSE;
		}
		$certificates = array(
							'public_key' => $public_key_out,
							'private_key' => $private_key_out,
						);
		return $certificates;
	}
}