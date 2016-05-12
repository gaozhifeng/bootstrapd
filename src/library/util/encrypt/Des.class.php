<?php

/**
 * @brief        Des 加密类
 * @desc         PHP/JAVA/OBJCET-C/C# 通用，key 长度8位或以内
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2015-2-16 20:34:27
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\library\util\encrypt;

class Des {

    /**
     * 算法
     * @var string
     */
    private static $_MCRYPT_RIJNDAEL = MCRYPT_DES;

    /**
     * 模式
     * @var string
     */
    private static $_MCRYPT_MODE = MCRYPT_MODE_CBC;

    /**
     * 秘钥
     * @var string
     */
    private static $_SECRET_KEY = 'des.3?a#';

    /**
     * 设置秘钥
     * @param string $key 秘钥
     */
    public static function setSecretKey( $key ) {
        self::$_SECRET_KEY = $key;
    }

    /**
     * 加密
     * @param  string $data 加密数据
     * @return string
     */
    public static function encrypt( $data ) {
        $ivSize = mcrypt_get_iv_size( self::$_MCRYPT_RIJNDAEL, self::$_MCRYPT_MODE );
        $iv     = mcrypt_create_iv( $ivSize, MCRYPT_RAND );

        //获得加密算法的分组大小
        $size = mcrypt_get_block_size( self::$_MCRYPT_RIJNDAEL, self::$_MCRYPT_MODE );
        $data = self::_pkcs5Pad( $data, $size );

        //使用给定参数加密明文
        $data = mcrypt_encrypt( self::$_MCRYPT_RIJNDAEL, self::$_SECRET_KEY, $data, self::$_MCRYPT_MODE, $iv );
        return base64_encode( $data );
    }

    /**
     * 解密
     * @param  string $data 解密字符串
     * @return string
     */
    public static function decrypt( $data ) {
        $decryptData = base64_decode( $data );
        $ivSize = mcrypt_get_iv_size( self::$_MCRYPT_RIJNDAEL, self::$_MCRYPT_MODE );
        $iv     = mcrypt_create_iv( $ivSize, MCRYPT_RAND );

        $data = mcrypt_decrypt( self::$_MCRYPT_RIJNDAEL, self::$_SECRET_KEY, $decryptData, self::$_MCRYPT_MODE, $iv );
        return self::_pkcs5Unpad( $data );
    }

    /**
     * 完整性和可信度保护
     * @return string
     */
    private static function _pkcs5Pad( $data, $blockSize ) {
        $pad = $blockSize - (strlen($text) % $blockSize);
    return $text . str_repeat(chr($pad), $pad);
    }

    /**
     * 完整性和可信度保护
     * @return string
     */
    private static function _pkcs5UnPad( $data ) {
        $pad = ord($text{strlen($text)-1});
    if ($pad > strlen($text)) return false;
    if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
    return substr($text, 0, -1 * $pad);
    }

}
