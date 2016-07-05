<?php

/**
 * @brief        Ip处理类
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2014-11-20 20:06:34
 * @copyright    (C) bootstrapd
 */


namespace bootstrapd\library\util\geo;

class Ip {

    /**
     * 获取Ip
     * @param  boolean $isInt       是否整型
     * @param  boolean $isReturnAll 返回全部
     * @return string|array|boolean
     */
    public static function getIp( $isInt = true, $isReturnAll = false ) {
        if ( !empty($_SERVER['HTTP_CLIENT_IP']) ) {
            $ipGroup = explode( ',', $_SERVER['HTTP_CLIENT_IP'] );
        } elseif ( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
            $ipGroup = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );
        } elseif ( !empty($_SERVER['REMOTE_ADDR']) ) {
            $ipGroup = explode( ',', $_SERVER['REMOTE_ADDR'] );
        } else {
            $ipGroup = array();
        }

        if ( $isInt ) {
            if ( $isReturnAll ) {
                foreach ( $ipGroup as $ip ) {
                    $ip[] = ip2long( $ip );
                }
                return $ip;
            } else {
                return ip2long( current($ipGroup) );
            }
        } else {
            return ( $isReturnAll ? $ipGroup : current($ipGroup) );
        }

        return false;
    }

    /**
     * 是否是私有Ip
     * @param  string  $ip    验证Ip
     * @param  boolean $isInt 是否整型Ip
     * @return boolean
     */
    public static function isPrivateIp( $ip, $isInt = true ) {
        $privateIp = array(
            '10.',
            '127.',
            '192.168.',
            '172.16',
            '172.17',
            '172.18',
            '172.19',
            '172.20',
            '172.21',
            '172.22',
            '172.23',
            '172.24',
            '172.25',
            '172.26',
            '172.27',
            '172.28',
            '172.29',
            '172.30',
            '172.31',
        );

        if ( $isInt ) {
            $ip = long2ip( $ip );
        }

        foreach ( $privateIp as $rangeIp ) {
            $len = strlen( $rangeIp );
            if ( substr( $ip, 0, $len ) === $rangeIp ) {
                return true;
            }
        }

        return false;
    }

    /**
     * 获取本机Ip
     * @param  boolean $isInt 是否整型
     * @return string|int
     */
    public static function getLocalIp( $isInt = true ) {
        $ip = '';
        if ( !empty($_SERVER['SERVER_ADDR']) ) {
            $ip = $_SERVER['SERVER_ADDR'];
        } else if ( !empty($_SERVER['SERVER_NAME']) ) {
            $ip = gethostbyname( $_SERVER['SERVER_NAME'] );
        }

        return !empty($ip) and $isInt ? ip2long( $ip ) : $ip;
    }

}
