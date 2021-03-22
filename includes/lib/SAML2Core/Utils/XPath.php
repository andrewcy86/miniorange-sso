<?php


namespace RobRichards\XMLSecLibs\Utils;

class XPath
{
    const ALPHANUMERIC = "\x5c\167\x5c\144";
    const NUMERIC = "\x5c\144";
    const LETTERS = "\x5c\x77";
    const EXTENDED_ALPHANUMERIC = "\134\167\x5c\x64\134\x73\x5c\55\x5f\72\x5c\56";
    const SINGLE_QUOTE = "\x27";
    const DOUBLE_QUOTE = "\42";
    const ALL_QUOTES = "\x5b\47\42\x5d";
    public static function filterAttrValue($tq, $w4 = self::ALL_QUOTES)
    {
        return preg_replace("\43" . $w4 . "\43", '', $tq);
    }
    public static function filterAttrName($sb, $yn = self::EXTENDED_ALPHANUMERIC)
    {
        return preg_replace("\x23\x5b\136" . $yn . "\x5d\x23", '', $sb);
    }
}
