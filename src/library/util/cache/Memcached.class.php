<?php

/**
 * @brief        Memcached 类
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2015-8-8 12:23:43
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\library\util\cache;

use bootstrapd\config\GlobalConfig;

class Memcached {

    /**
     * 句柄
     * @var array
     */
    public static $HANDLE = array();

    /**
     * 获取句柄
     * @param  string $instance 实例名
     * @return object
     */
    public static function getMcHandler( $instance = 'DEFAULT' ) {
        $instance = strtoupper( $instance );
        $instance = sprintf( 'MC_SERVER_%s', $instance );

        //服务器参数
        $serverList = GlobalConfig::$$instance;

        $servers = array();
        foreach ( $serverList as $server ) {
            $servers[] = [
                $server['host'],
                $server['port'],
                $server['weight'],
            ];
        }

        return self::_createHandler( $servers );
    }

    /**
     * 创建句柄
     * @param  array $servers 服务器参数
     * @return object
     * @throws InvalidArgumentException If 参数错误
     */
    private static function _createHandler( array $servers ) {
        if ( empty($servers[0][0]) ) {
            throw new \InvalidArgumentException( 'Memcached server config error.' );
        }

        $handlerKey = self::_getHandlerKey( $servers );
        if ( isset(self::$HANDLE[$handlerKey]) ) {
            self::_checkHandler( self::$HANDLE[$handlerKey] );
            return self::$HANDLE[$handlerKey];
        }

        $handler = new \Memcached( $handlerKey );
        $handler->setOption( \Memcached::OPT_RECV_TIMEOUT, 1000 );
        $handler->setOption( \Memcached::OPT_SEND_TIMEOUT, 1000 );
        $handler->setOption( \Memcached::OPT_TCP_NODELAY, true );
        $handler->setOption( \Memcached::OPT_RETRY_TIMEOUT, 1 );
        $handler->setOption( \Memcached::OPT_DISTRIBUTION, \Memcached::DISTRIBUTION_CONSISTENT );
        $handler->setOption( \Memcached::OPT_LIBKETAMA_COMPATIBLE, true);
        $handler->addServers( $servers );

        return self::$HANDLE[$handlerKey] = $handler;
    }

    /**
     * 检查句柄
     * @param  object $handler 句柄
     * @return boolean
     * @throws InvalidArgumentException If 无效句柄
     */
    private static function _checkHandler( $handler ) {
        if ( !is_object($handler) || !$handler instanceof \Memcached ) {
            throw new \InvalidArgumentException( 'Memcached handler error.' );
        }
        return true;
    }

    /**
     * 获取句柄标识
     * @param  array $servers 服务器参数
     * @return string
     */
    private static function _getHandlerKey( array $servers ) {
        $serverStr = '';
        foreach ( $servers as $server ) {
            $serverStr .= implode( '_', $server );
        }

        return md5( $serverStr );
    }

}
