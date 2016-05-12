<?php

/**
 * @brief        加密类
 * @desc         支持 AES/DES/3DES
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2015-1-19 15:59:26
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\library\util\encrypt;

class Encrypt {

    /**
     * AES加密
     */
    const MODE_AES  = 1;

    /**
     * DESC加密
     */
    const MODE_DES  = 2;

    /**
     * 3DES加密
     */
    const MODE_3DES = 3;

    /**
     * 句柄
     * @var array
     */
    private static $_HANDLE = array();

    /**
     * 创建一个加密对象
     * @param  int    $mode      加密方式
     * @param  string $secretKey 加密key
     * @param  string $iv        偏移量
     * @return object
     */
    public static function create( $mode, $secretKey, $iv = '' ) {
        //构成key值
        $handleKey = self::_getHandlerKey( array(
            'mode'      => $mode,
            'secretKey' => $secretKey,
        ) );

        if ( isset($_HANDLE[$handleKey]) ) {
            return $_HANDLE[$handleKey];
        }

        try {
            switch ( $mode) {
                case self::MODE_AES:
                    require_once __DIR__ . '/AesEncrypt.class.php';
                    $object = new AesEncrypt( $secretKey );
                    break;

                case self::MODE_DES:
                    require_once __DIR__ . '/DesEncrypt.class.php';
                    DesEncrypt::setSecretKey( $secretKey );
                    break;

                case self::MODE_3DES:
                    require_once __DIR__ . '/Des3Encrypt.class.php';
                    $object = new Des3Encrypt( $secretKey, $iv );
                    break;

                default:
                    require_once __DIR__ . '/AesEncrypt.class.php';
                    $object = new AesEncrypt( $secretKey );
                    break;
            }

            $_HANDLE[$handleKey] = $object;
        } catch ( Exception $e ) {
            throw new Exception( 'Encrypt.create ' . $e->getMessage() );
        }

        return $_HANDLE[$handleKey];
    }

    /**
     * 获取句柄Key
     * @param  array $params 句柄参数
     * @return string
     */
    private static function _getHandlerKey( $params ) {
        ksort( $params );
        return md5( implode('_', $params) );
    }

}
