<?php

/**
 * @brief        Session 处理类
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2015-3-15 0:06:03
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\library\util\session;

class Session {

    /**
     * 初始化状态
     * @var boolean
     */
    private static $_INIT = false;

    /**
     * 保存路径
     * @var string
     */
    private static $_SAVE_PATH = '';

    /**
     * 初始化
     * @return boolean
     */
    private static function _init() {
        if ( self::$_INIT ) {
            return true;
        }
        if ( self::$_SAVE_PATH ) {
            session_save_path( self::$_SAVE_PATH );
        }

        session_start();
        self::$_INIT = true;

        return true;
    }

    /**
     * 设置保存路径
     * @param string $path 保存路径
     */
    public static function setSavePath( $path ) {
        if ( $path ) {
            self::$_SAVE_PATH = $path;
            return true;
        }
        return false;
    }

    /**
     * 设置session
     * @param string $key   sessionId
     * @param string $value session值
     */
    public static function set( $key, $value ) {
        self::_init();
        $_SESSION[$key] = $value;
        return true;
    }

    /**
     * 获取session
     * @param  string $key sessionId
     * @return boolean
     */
    public static function get( $key ) {
        self::_init();
        if ( isset($_SESSION[$key]) ) {
            return $_SESSION[$key];
        }
        return false;
    }

    /**
     * 存在session
     * @param  string $key sessionId
     * @return boolean
     */
    public static function exist( $key ) {
        self::_init();
        if ( array_key_exists($key, $_SESSION) ) {
            return true;
        }
        return false;
    }

    /**
     * 删除session
     * @param  string $key sessionId
     * @return boolean
     */
    public static function delete( $key ) {
        self::_init();
        if ( isset($_SESSION[$key]) ) {
            unset( $_SESSION[$key] );
        }
        return true;
    }

    /**
     * 设置flash session 数据
     * 仅一次存取
     * @param  string $key   sessionId
     * @param  string $value session值
     * @return boolean
     */
    public static function flash( $key, $value = '' ) {
        self::_init();
        if ( $key && !$value) {
            $value = $_SESSION[$key];
            unset( $_SESSION[$key] );
            return $value;
        }
        if ( $key && $value ) {
            $_SESSION[$key] = $value;
            return true;
        }
        return false;
    }

    /**
     * 销毁session
     * @return boolean
     */
    public static function destroy() {
        self::_init();
        session_unset();
        session_destroy();
        return true;
    }

}
