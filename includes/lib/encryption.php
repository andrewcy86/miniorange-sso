<?php


class AESEncryption
{
    public static function encrypt_data($zF, $Xr)
    {
        $Xr = openssl_digest($Xr, "\163\150\x61\x32\65\66");
        $Vb = "\101\105\x53\55\61\62\x38\x2d\x45\103\x42";
        $on = openssl_encrypt($zF, $Vb, $Xr, OPENSSL_RAW_DATA || OPENSSL_ZERO_PADDING);
        return base64_encode($on);
    }
    public static function decrypt_data($zF, $Xr)
    {
        $V4 = base64_decode($zF);
        $Xr = openssl_digest($Xr, "\163\x68\141\x32\65\66");
        $Vb = "\101\x45\x53\55\x31\x32\70\x2d\105\103\102";
        $mF = openssl_cipher_iv_length($Vb);
        $Ne = substr($V4, 0, $mF);
        $zF = substr($V4, $mF);
        $ps = openssl_decrypt($zF, $Vb, $Xr, OPENSSL_RAW_DATA || OPENSSL_ZERO_PADDING, $Ne);
        return $ps;
    }
}
?>
