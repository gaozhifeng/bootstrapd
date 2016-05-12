<?php

/**
 * @brief        Cookie 操作类
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2015-8-2 19:49:51
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\library\util\cookie;

class Cookie {

    /**
     * 设置Cookie
     * @param  string|array $name     Cookie名
     * @param  string       $value    Cookie值
     * @param  integer      $expire   有效期
     * @param  string       $path     路径
     * @param  string       $domain   域名
     * @param  boolean      $secure   安全性
     * @param  boolean      $httponly 仅http
     * @return boolean
     */
    public static function setCookie( $name, $value, $expire = 0, $path = '', $domain = '', $secure = false, $httponly = false ) {
        $name = self::getCookieName( $name );
        return setcookie( $name, $value, $expire, $path, $domain, $secure, $httponly );
    }

    /**
     * 获取Cookie
     * @param  string|array $name     Cookie名
     * @param  boolean      $stripTag 剥离标签
     * @param  string       $default  默认值
     * @return string
     */
    public static function getCookie( $name, $stripTag = false, $default = '' ) {
        $name = self::getCookieName( $name );
        return isset( $_COOKIE[$name] ) ? ( $stripTag ? strip_tags($_COOKIE[$name]) : $_COOKIE[$name] ) : $default;
    }

    /**
     * 删除Cookie
     * @param  string|array $name     Cookie名
     * @param  string       $path     路径
     * @param  string       $domain   域名
     * @param  boolean      $secure   安全性
     * @param  boolean      $httponly 仅http
     * @return boolean
     */
    public static function delCookie( $name, $path = '', $domain = '', $secure = false, $httponly = false ) {
        $name = self::getCookieName( $name );
        return setcookie( $name, 'deleted', 1, $path, $domain, $secure, $httponly );
    }

    /**
     * 获取数组Cookie名称
     * @param  string|array $name Cookie名
     * @return string
     */
    private static function getCookieName( $name ) {
        if ( is_array($name) ) {
            list( $key, $value ) = each( $name );
            $name = sprintf( '%s[%s]', $key, $value );
        }
        return $name;
    }

}
