<?php

/**
 * @brief        参数签名
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2015-8-2 14:05:28
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\library\util\encrypt;

class ParamSign {

    /**
     * 秘钥
     * @var string
     */
    protected static $_SECRET_KEY  = '5vS7uG.J';

    /**
     * 设置秘钥
     * @param  string $secretKey 秘钥
     * @return void
     */
    public static function setSecretKey( $secretKey ) {
        self::$_SECRET_KEY = $secretKey;
    }

    /**
     * 获取签名
     * @param  array  $params 参数
     * @param  string $type   加密类型
     * @return string
     */
    public static function getSign( $params, $type = 'sha1' ) {
        $paramStr = self::_buildParamStr( $params );

        $sign = '';
        switch ( $type ) {
            case 'sha1':
                $sign = base64_encode( hash_hmac($type, $paramStr, self::$_SECRET_KEY) );
                break;

            default:
                throw new Exception( 'Unkown sign type' );
        }

        return $sign;
    }

    /**
     * 验证签名
     * @param  array  $params 参数
     * @return boolean
     */
    public static function authSign( $params ) {
        $flag = false;
        if ( self::getSign($params) == $params['signature'] ) {
            $flag = true;
        }

        return $flag;
    }

    /**
     * 构成参数
     * @param  array $params 参数集合
     * @return string
     */
    protected static function _buildParamStr( $params ) {
        ksort( $params );

        $paramStr = '';
        foreach ( $params as $key => $value ) {
            if ( $key == 'signature' ) {
                continue;
            }
            $paramStr .= sprintf( '%s=%s&', $key, $value );
        }

        return rtrim( $paramStr, '&' );
    }

}
