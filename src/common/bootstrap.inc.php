<?php

/**
 * @brief        引导文件
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2014-11-16 17:47:57
 * @copyright    (C) bootstrapd
 */

header( 'Content-Type:text/html; charset=utf-8' );


//定义目录
define( 'BOOTSTRAPD_COMMON', preg_replace('/[\/\\\\]{1,}/', '/', __DIR__) );
define( 'BOOTSTRAPD_ROOT', dirname(BOOTSTRAPD_COMMON) );
define( 'BOOTSTRAPD_APP',  BOOTSTRAPD_ROOT . '/app' );
define( 'BOOTSTRAPD_CONF', BOOTSTRAPD_ROOT . '/config' );
define( 'BOOTSTRAPD_LIB',  BOOTSTRAPD_ROOT . '/library' );
define( 'BOOTSTRAPD_RUN',  BOOTSTRAPD_ROOT . '/runtime' );
define( 'BOOTSTRAPD_SRV',  BOOTSTRAPD_ROOT . '/server' );
define( 'BOOTSTRAPD_STA',  BOOTSTRAPD_ROOT . '/static' );


//自动加载
require_once BOOTSTRAPD_COMMON . '/Loader.class.php';
require_once BOOTSTRAPD_COMMON . '/Autoload.class.php';


//命名空间
use bootstrapd\common;
use bootstrapd\config;
use bootstrapd\library\util;


//开始运行
util\datetime\Timer::start( 'system' );


//默认时区
date_default_timezone_set( 'Etc/GMT' . config\GlobalConfig::TIME_ZONE * -1 );


//错误显示
if ( config\GlobalConfig::DEBUG ) {
    error_reporting( E_ALL );
} else {
    error_reporting( 0 );
    //错误句柄
    set_error_handler( 'catchSysError', E_ALL ^ E_NOTICE );
    //异常句柄
    set_exception_handler( 'catchSysException' );
    //500错误处理
    register_shutdown_function( 'getSysShutdown' );
}


//变量解义
util\http\Http::stripGpc();


/**
 * 系统错误
 * @return void
 */
function getSysShutdown() {
    $error = error_get_last();
    if ( $error && in_array($error['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR)) ) {
        catchSysError( $error['type'], $error['message'], $error['file'], $error['line'] );
    }
    exit;
}

/**
 * 捕获错误
 * @param  integer $errno   错误码
 * @param  string  $errstr  错误内容
 * @param  string  $errfile 错误文件
 * @param  integer $errline 错误行
 * @return void
 */
function catchSysError( $errno, $errstr, $errfile, $errline ) {
    common\View::display( config\ErrorCode::$ERR_MSG[config\ErrorCode::ERR_SYSTEM] );
    $log = sprintf( 'catchSysError: %s %s %s', $errno, $errstr, $errfile, $errline );
    util\log\Logger::Fatal( $log );
    exit;
}

/**
 * 捕获异常
 * @param  object $e 异常内容
 * @return void
 */
function catchSysException( $e ) {
    common\View::display( config\ErrorCode::$ERR_MSG[config\ErrorCode::ERR_SYSTEM] );
    $log = sprintf( 'catchSysException: %s %s %s', $e->getMessage(), $e->getCode(), $e->getTraceAsString() );
    util\log\Logger::Fatal( $log );
    exit;
}
