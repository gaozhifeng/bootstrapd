<?php

/**
 * @brief        Redis 类
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2015-8-8 22:16:20
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\library\util\cache;

class Redis {

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
    public static function getRedisHandler( $instance = 'DEFAULT' ) {
        $instance = strtoupper( $instance );
        $instance = sprintf( 'REDIS_SERVER_%s', $instance );

        //服务器参数
        $server = GlobalConfig::$$instance;

        return self::_createHandler( $server );
    }

    /**
     * 创建句柄
     * @param  array  $server 服务器参数
     * @return object
     * @throws InvalidArgumentException If 参数错误
     *         Exception If 连接或运行错误
     */
    private static function _createHandler( array $server ) {
        if ( empty($server) ) {
            throw new \InvalidArgumentException( 'Redis server config error.' );
        }

        $handlerKey = self::_getHandlerKey( $server );
        if ( isset($HANDLE[$handlerKey]) ) {
            self::_checkHandler( $HANDLE[$handlerKey] );
            return $HANDLE[$handlerKey];
        }

        $instance = new \Redis();
        $instance->pconnect( $server['host'], $server['port'], $server['timeout'] );
        $instance->auth( $server['password'] );
        $instance->select( $server['database'] );

        return $HANDLE[$handlerKey] = $instance;
    }

    /**
     * 检查句柄
     * @param  object $handler 句柄
     * @return boolean
     * @throws InvalidArgumentException If 无效句柄
     */
    private static function _checkHandler( $handler ) {
        if ( !is_object($handler) || !$handler instanceof \Redis ||  '+PONG' !== $handler->ping() ) {
            throw new \InvalidArgumentException( 'Redis handler error.' );
        }
        return true;
    }

    /**
     * 获取句柄标识
     * @param  array $server 服务器参数
     * @return string
     */
    private static function _getHandlerKey( $server ) {
        ksort( $server );
        $server = implode( '_', $server );
        return md5( $server );
    }

}
