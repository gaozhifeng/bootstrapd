<?php

/**
 * @brief        工厂类
 *
 * @author       Feng <mail.gzf@foxmail>
 * @since        2014-11-17 16:17:10
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\common;

use bootstrapd\config\ErrorCode;
use bootstrapd\common\exception\SysException;

class Factory {

    /**
     * 依赖注入
     * @var string
     */
    private $_morkSet = array();

    /**
     * 创建类实例
     * @param  array $analysisUri 解析uri
     * @return object
     * @throws SysException
     */
    public static function createClass( $analysisUri ) {

        //不允许的 HTTP方法
        if ( !in_array($analysisUri['method'], array('GET', 'POST', 'PUT', 'DELETE', 'PATCH')) ) {
            throw new SysException( ErrorCode::$ERR_MSG[ErrorCode::ERR_NOT_ALLOW_HTTP_METHOD] );
        }

        //加载请求类文件
        $module    = $analysisUri['module'];
        $className = ucfirst( $analysisUri['resource'] ) . 'Page';
        $classFile = sprintf( '%s/%s/resource/%s.class.php', BOOTSTRAPD_APP, $module, $className );
        if ( !is_file($classFile) ) {
            throw new SysException( ErrorCode::$ERR_MSG[ErrorCode::ERR_NOT_FOUND_FILE] );
        }
        require $classFile;

        if ( !class_exists($className) ) {
            throw new SysException( ErrorCode::$ERR_MSG[ErrorCode::ERR_NOT_FOUND_CLASS] );
        }
        $instance = new $className;

        //获取映射绑定配置
        if ( !method_exists($instance, 'setBinder') or !is_callable(array($instance, 'setBinder')) ) {
            throw new SysException( ErrorCode::$ERR_MSG[ErrorCode::ERR_NOT_FOUND_SETBINDER] );
        }
        $binder = $instance->setBinder();
        $queryUri = Router::match( $binder, $analysisUri['query'] );
        if ( empty($queryUri) ) {
            throw new SysException( ErrorCode::$ERR_MSG[ErrorCode::ERR_NOT_FOUND_SETBINDER_URI] );
        }

        //获取 uri 绑定的类方法
        $action = $binder[$queryUri][$analysisUri['method']];
        if ( empty($action) ) {
            throw new SysException( ErrorCode::$ERR_MSG[ErrorCode::ERR_NOT_FOUND_SETBINDER_METHOD] );
        }
        $action = $action . 'Action';

        if ( !method_exists($instance, $action) or !is_callable(array($instance, $action)) ) {
            throw new SysException( ErrorCode::$ERR_MSG[ErrorCode::ERR_NOT_FOUND_CLASS_METHOD] );
        }
        return $instance->$action();
    }

    /**
     * 依赖注入
     * @param  object $mork 注入对象
     * @return void
     */
    public static function set( $class, $mork ) {
        self::$_morkSet[md5($class)] = $mork;
    }

    /**
     * 获取实例
     * @param  string $class 类名
     * @return object
     */
    public static function getInstance( $class ) {
        $morkClass = md5( $class );
        if ( empty(self::$_morkSet[$morkClass]) ) {
            return self::$_morkSet[$morkClass];
        }
        return new $class;
    }

}
