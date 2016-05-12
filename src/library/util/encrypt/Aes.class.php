<?php

/**
 * @brief        Aes 加密类
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2015-7-27 22:12:57
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\library\util\encrypt;

class Aes {

    /**
     * 算法
     * @var string
     */
    private static $_MCRYPT_RIJNDAEL = MCRYPT_RIJNDAEL_128;

    /**
     * 模式
     * @var string
     */
    private static $_MCRYPT_MODE = MCRYPT_MODE_CBC;

    /**
     * 秘钥
     * @var string
     */
    private static $_SECRET_KEY = 'aes.3?encrypt#';

    /**
     * 设置秘钥
     * @param string $secretKey void
     */
    public static function setSecretKey( $secretKey) {
        self::$_SECRET_KEY = $secretKey;
    }

    /**
     * 加密
     * @param  string $data 数据
     * @return string
     */
    public static function encrypt( $data ) {
        $ivSize = mcrypt_get_iv_size( self::$_MCRYPT_RIJNDAEL, self::$_MCRYPT_MODE );
        $iv     = mcrypt_create_iv( $ivSize, MCRYPT_RAND );
        $encryptData = mcrypt_encrypt( self::$_MCRYPT_RIJNDAEL, self::$_SECRET_KEY, $data, self::$_MCRYPT_MODE, $iv );
        $encryptData = $iv . $encryptData;
        return base64_encode( $encryptData );
    }

    /**
     * 解密
     * @param  string $data 数据
     * @return string
     */
    public static function decrypt( $data ) {
        $decryptData = base64_decode( $data );
        $ivSize = mcrypt_get_iv_size( self::$_MCRYPT_RIJNDAEL, self::$_MCRYPT_MODE );
        $iv     = substr( $decryptData, 0, $ivSize );
        $decryptData = substr( $decryptData, $ivSize );
        $decryptData = mcrypt_decrypt( self::$_MCRYPT_RIJNDAEL, self::$_SECRET_KEY, $decryptData, self::$_MCRYPT_MODE, $iv );
        return rtrim( $decryptData, "\0" );
    }

}
