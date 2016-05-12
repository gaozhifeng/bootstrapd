<?php

/**
 * @brief        路由类
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2014-11-16 17:47:57
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\common;

use bootstrapd\config;
use bootstrapd\library\util;
use bootstrapd\common\exception\SysException;

class Router {

    public static function run() {
        try {
            $analysisUri = self::parse();
            Factory::createClass( $analysisUri );
        } catch ( SysException $e ) {
            self::exceptionHandler( 'SysException', $e );
        } catch ( \Exception $e ) {
            self::exceptionHandler( 'Exception', $e );
        } finally {
            util\datetime\Timer::stop( 'system' );
            if ( config\GlobalConfig::DEBUG ) {
                $time = util\datetime\Timer::count( 'system' );
                $ip   = util\geo\Ip::getIp( false );
                View::displayTools( $time, $ip );
            }
        }
        return;
    }

    /**
     * uri解析
     * @return array
     */
    public static function parse() {
        $uri     = $_SERVER['REQUEST_URI'];
        $pattern = '/^\/([\w\-\_]+)\/([\w]+)\/(.*)/';
        $flag    = preg_match( $pattern, $uri, $match );
        if ( $flag ) {
            list( $uri, $module, $resource, $query ) = $match;
        } else {
            $match = explode( '/', $uri );
            $args  = array( 'all', 'module', 'resource', 'query' );
            foreach ( $match as $key => $value ) {
                $$args[$key] = preg_replace( '/\?(?<=\?).*/', '', $value );
            }
        }

        $analysisUri['method']   = strtoupper( $_SERVER['REQUEST_METHOD'] );
        $analysisUri['uri']      = $uri;
        $analysisUri['module']   = empty( $module )   ? 'default' : str_replace( '-', '/', $module );
        $analysisUri['resource'] = empty( $resource ) ? 'index'   : $resource;
        $analysisUri['query']    = empty( $query )    ? ''        : $query;

        return $analysisUri;
    }

    /**
     * uri匹配
     * @param  array  $methodMap 方法映射
     * @param  string $queryUri  请求uri
     * @return array|void
     */
    public static function match( array $methodMap, $queryUri ) {
        foreach ( $methodMap as $methodUri => $item ) {
            $pattern = preg_replace( '/:\w+/', '([:\w]+)', $methodUri );
            $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';
            if ( preg_match($pattern, '/' . $queryUri, $match) ) {
                if ( preg_match($pattern, $methodUri, $uriKeys) ) {
                    $count = count( $uriKeys );
                    for ( $i = 1; $i < $count; $i ++ ) {
                        $_GET[$uriKeys[$i]] = $match[$i];
                    }
                    return $methodUri;
                }
            }
        }
        return;
    }

    /**
     * 异常处理
     * @param  string $type      异常类型
     * @param  object $exception 异常对象
     * @return void
     */
    public static function exceptionHandler( $type, $exception ) {
        if ( config\GlobalConfig::DEBUG ) {
            View::display( $exception );
        } else {
            switch ( $type ) {
                case 'SysException':
                    View::display( config\ErrorCode::$ERR_MSG[config\ErrorCode::ERR_NOT_FOUND], 404 );
                    break;

                case 'Exception':
                    catchSysException( $exception );
                    break;
            }
        }
        return;
    }

}
