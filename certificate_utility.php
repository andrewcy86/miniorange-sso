<?php


class CertificateUtility
{
    public static function generate_certificate($gP, $La, $qE)
    {
        $Yz = openssl_pkey_new();
        $I5 = openssl_csr_new($gP, $Yz, $La);
        $L6 = openssl_csr_sign($I5, null, $Yz, $qE, $La, time());
        openssl_csr_export($I5, $l5);
        openssl_x509_export($L6, $QP);
        openssl_pkey_export($Yz, $zt);
        Fn1:
        if (!(($HI = openssl_error_string()) !== false)) {
            goto HdV;
        }
        error_log("\x43\145\162\x74\151\x66\x69\x63\141\x74\145\x55\164\x69\154\x69\x74\171\x3a\40\105\x72\x72\157\162\x20\147\x65\156\145\162\x61\164\x69\156\147\40\143\145\162\164\x69\x66\x69\x63\141\x74\x65\x2e\40" . $HI);
        goto Fn1;
        HdV:
        $tw = array("\160\165\142\154\x69\x63\137\x6b\x65\171" => $QP, "\x70\x72\x69\166\x61\164\x65\137\153\x65\x79" => $zt);
        return $tw;
    }
}
