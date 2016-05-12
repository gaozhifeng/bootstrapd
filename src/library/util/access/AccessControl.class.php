<?php

/**
 * @brief        Ip访问控制
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2015-9-19 09:25:15
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\library\util\access;

use bootstrapd\library\util\geo\Ip;
use bootstrapd\library\util\cache\Memcached;

class AccessControl {

    /**
     * Ip访问控制
     * @param  string  $key    标识 Key
     * @param  integer $max    访问上限
     * @param  integer $expire 过期时间
     * @return boolean
     * @throws Exception mc存储失败
     */
    public static function ipControl( $key, $max = 1500, $expire = 3600 ) {
        $result = true;

        do {
            $ip = Ip::getIp();

            //私有Ip不受限
            if ( Ip::isPrivateIp($ip) ) {
                break;
            }

            $mcKey = sprintf( 'access_control_%s_ip_%u', $key, $ip );
            $mcHandler = Memcached::getMcHandler();
            $mcValue = (int) $mcHandler->read( $mcKey );
            if ( $mcValue > $max ) {
                $result = false;
                break;
            }

            $flag = $mcHandler->write( $mcKey, 1, $expire );
            if ( !$flag ) {
                throw new \Exception( sprintf('%s: mc write fail.', __METHOD__) );
            }
        } while ( 0 );

        return $result;
    }

}
