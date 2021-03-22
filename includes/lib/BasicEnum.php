<?php


abstract class BasicEnum
{
    private static $constCacheArray = NULL;
    public static function getConstants()
    {
        if (!(self::$constCacheArray == NULL)) {
            goto kjf;
        }
        self::$constCacheArray = array();
        kjf:
        $kR = get_called_class();
        if (array_key_exists($kR, self::$constCacheArray)) {
            goto dnB;
        }
        $KG = new ReflectionClass($kR);
        self::$constCacheArray[$kR] = $KG->getConstants();
        dnB:
        return self::$constCacheArray[$kR];
    }
    public static function isValidName($sb, $er = false)
    {
        $Cg = self::getConstants();
        if (!$er) {
            goto qeX;
        }
        return array_key_exists($sb, $Cg);
        qeX:
        $SV = array_map("\163\x74\x72\164\x6f\x6c\x6f\167\x65\162", array_keys($Cg));
        return in_array(strtolower($sb), $SV);
    }
    public static function isValidValue($tq, $er = true)
    {
        $OM = array_values(self::getConstants());
        return in_array($tq, $OM, $er);
    }
}
