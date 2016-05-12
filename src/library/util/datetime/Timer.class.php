<?php

/**
 * @brief        计时器
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2015-2-21 21:53:00
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\library\util\datetime;

class Timer {

    /**
     * 开始时间
     * @var integer
     */
    private static $_startTime = array();

    /**
     * 结束时间
     * @var integer
     */
    private static $_endTime = array();

    /**
     * 统计时间
     * @var integer
     */
    private static $_countTime = array();

    /**
     * 是否运行
     * @var boolean
     */
    private static $_isRuning = array();

    /**
     * 开始计时
     * @param  string $id 标识
     */
    public static function start( $id = 'me' ) {
        if ( !isset(self::$_isRuning[$id]) ) {
            self::$_startTime[$id] = gettimeofday( true );
            self::$_isRuning[$id]  = true;
        }
    }

    /**
     * 停止计时
     * @param  string $id 标识
     */
    public static function stop( $id = 'me' ) {
        if ( isset(self::$_isRuning[$id]) ) {
            self::$_endTime[$id]  = gettimeofday( true );
            self::$_isRuning[$id] = false;
        }
    }

    /**
     * 重置计时
     * @param  string $id 标识
     */
    public static function reset( $id = 'me' ) {
        self::$_startTime[$id] = self::$_endTime[$id] = self::$_countTime[$id] = 0;
        self::$_isRuning[$id] = false;
    }

    /**
     * 统计计时
     * @param  string $id 标识
     * @return int
     */
    public static function count( $id = 'me' ) {
        if ( empty(self::$_endTime[$id]) || empty(self::$_startTime[$id]) ) {
            return 0;
        }
        return self::$_endTime[$id] - self::$_startTime[$id];
    }

    /**
     * 是否运行
     * @param  string $id 标识
     * @return boolean
     */
    public static function isRuning( $id = 'me' ) {
        if ( isset(self::$_isRuning[$id]) ) {
            return self::$_isRuning[$id];
        }
        return false;
    }

}
