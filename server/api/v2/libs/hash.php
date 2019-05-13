<?php

class Hash
{
    public static $algos = array(
        "8" => array("adler32", "crc32", "crc32b", "fnv132", "fnv1a32", "joaat"),
        "16" => array("fnv164", "fnv1a64"),
        "32" => array("md2", "md4", "md5", "ripemd128", "tiger128,3", "tiger128,4", "haval128,3", "haval128,4", "haval128,5"),
        "40" => array("sha1", "ripemd160", "tiger160,3", "tiger160,4", "haval160,3", "haval160,4", "haval160,5"),
        "48" => array("tiger192,3", "tiger192,4", "haval192,3", "haval192,4", "haval192,5"),
        "56" => array("sha224", "sha512/224", "sha3-224", "haval224,3", "haval224,4", "haval224,5"),
        "64" => array("sha256", "sha512/256", "sha3-256", "ripemd256", "snefru", "snefru256", "gost", "gost-crypto", "haval256,3", "haval256,4", "haval256,5"),
        "80" => array("ripemd320"),
        "96" => array("sha384", "sha3-384"),
        "128" => array("sha512", "sha3-512", "whirlpool"),
    );
    public static function generate($length)
    {
        return hash(static::$algos[$length][random_int(0, sizeof(static::$algos[$length]) - 1)], rand() * time());
    }
}
