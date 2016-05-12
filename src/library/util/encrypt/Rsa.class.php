<?php

/**
 * @brief        非对称加密
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2016-5-12 12:31:04
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\library\util\encrypt;

class Rsa {

    /**
     * 私钥加密
     * @param  string $privateKey 私钥
     * @param  string $data       数据
     * @return string
     */
    public static function privateEncrypt( $privateKey, $data ) {
        $privateKey = openssl_get_privatekey( $privateKey );
        openssl_free_key( $privateKey );

        $encryptData = '';
        $splitData = str_split( $data, 117 );
        foreach ( $splitData as $value ) {
            openssl_private_encrypt( $value, $encrypted, $privateKey );
            $encryptData .= base64_encode( $encrypted );
        }

        return $encryptData;
    }

    /**
     * 私钥解密
     * @param  string $privateKey 私钥
     * @param  string $data       数据
     * @return string
     */
    public static function privateDecrypt( $privateKey, $data ) {
        $privateKey = openssl_get_privatekey( $privateKey );
        openssl_free_key( $privateKey );

        $decryptData = '';
        $splitData = str_split( $data, 172 );
        foreach ( $splitData as $value ) {
            $value = base64_decode( $value );
            openssl_private_decrypt( $value , $decrypted, $privateKey );
            $decryptData .= $decrypted;
        }

        return $decryptData;
    }

    /**
     * 公钥加密
     * @param  string $privateKey 公钥
     * @param  string $data       数据
     * @return string
     */
    public static function publicEncrypt( $publicKey, $data ) {
        $publicKey = openssl_get_publickey( $publicKey );
        openssl_free_key( $publicKey );

        $encryptData = '';
        $splitData = str_split( $data, 117 );
        foreach ( $splitData as $value ) {
            openssl_public_encrypt( $value, $crypted, $publicKey );
            $encryptData .= base64_encode( $crypted );
        }

        return $encryptData;
    }

    /**
     * 公钥解密
     * @param  string $privateKey 公钥
     * @param  string $data       数据
     * @return string
     */
    public static function publicDecrypt( $publicKey, $data ) {
        $publicKey = openssl_get_publickey( $publicKey );
        openssl_free_key( $publicKey );

        $decryptData = '';
        $splitData = str_split( $data, 172 );
        foreach ( $splitData as $value ) {
            $value = base64_decode( $value );
            openssl_public_decrypt( $value, $decrypted, $publicKey );
            $decryptData .= $decrypted;
        }

        return $decryptData;
    }

}
