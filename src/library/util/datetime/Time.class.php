<?php

/**
 * @brief        日期时间类
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2014-11-21 15:53:38
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\library\util\datetime;

class Time {

    /**
     * 获取毫秒
     * @return float 毫秒时间
     */
    public static function getMillisecond() {
        return (float) sprintf( '%.0f', microtime(true) * 1000 );
    }

    /**
     * 获取微秒
     * @return float 微妙时间
     */
    public static function getMicrosecond() {
        list( $msec, $sec ) = explode( ' ', microtime() );
        return (float) $sec + $msec;
    }

    /**
     * 获取日期时间
     * @return string 日期时间
     */
    public static function getDateTime() {
        return (string) self::formatTime( time() );
    }

    /**
     * 获取日期
     * @return string 日期
     */
    public static function getDate() {
        return (string) self::formatTime( time(), 'Y-m-d' );
    }

    /**
     * 获取时间
     * @return string 时间
     */
    public static function getTime() {
        return (string) self::formatTime( time(), 'H:i:s' );
    }

    /**
     * 格式化时间函数
     * @param  int    $timestamp 时间戳
     * @param  string $format    格式
     * @return string|bool
     */
    public static function formatTime( $timestamp = '', $format = 'Y-m-d H:i:s' ) {
        return ( $timestamp ? date( $format, $timestamp ) : false );
    }

    /**
     * 格式化文字时间
     * @param  int    $timestamp 时间戳
     * @return string
     */
    public static function formatTimeForText( $timestamp = 0 ) {
        $_day     = '天';
        $_hour    = '小时';
        $_minute  = '分';
        $_minute2 = '分钟';
        $_minute3 = '一分钟内';
        $_second  = '秒';
        $_second2 = '秒钟';
        $_now     = '刚刚';
        $_postfix = '前';

        $timeText    = '';
        $timeDiff    = time() - $timestamp;

        if ( $timeDiff <= 10 || $timestamp < 1 ) {
            return $_now;
        } else if ( $timeDiff > 10 && $timeDiff < 60 ) {
            $timeText = $_minute3;
        } else if ( $timeDiff >= 60 && $timeDiff < 3600 ) {
            $timeText = floor($timeDiff / 60) . $_minute2;
        } else if ( $timeDiff >= 3600 && $timeDiff < 86400 ) {
            $timeText = floor($timeDiff / 3600) . $_hour;
        } else {
            if ( floor($timeDiff / 86400) <= 10 ) {
                $timeText = floor($timeDiff / 86400) . $_day;
            } else {
                return date( 'Y-m-d', $timestamp );
            }
        }

        return $timeText . $_postfix;
    }

    /**
     * 格式化文字时间
     * @param  int     $timestamp 时间戳
     * @param  boolean $isSecond  是否包含秒
     */
    public static function formatTimeForText2( $timestamp, $isSecond = false  ) {
        $_day     = '天';
        $_hour    = '小时';
        $_minute  = '分钟';
        $_second  = '秒';
        $_postfix = '前';

        $timeText = '';
        $timeDiff = time() - $timestamp;

        //毫秒级
        if ( $timestamp < 1 ) {
            return 1 . $_second . $_postfix;
        }

        if ( $timeDiff >= 86400) {
            $timeText .= floor( $timeDiff / 86400 ) . $_day;
            $timeDiff = $timeDiff - ( floor($timeDiff / 86400) * 86400 );
        }
        if ( $timeDiff >= 3600 ) {
            $timeText .= floor( $timeDiff / 3600 ) . $_hour;
            $timeDiff = $timeDiff - ( floor($timeDiff / 3600) * 3600 );
        }
        if ( $timeDiff >= 60 ) {
            $timeText .= floor( $timeDiff / 60 ) . $_minute;
            $timeDiff = $timeDiff - ( floor($timeDiff / 60) * 60 );
        }
        if ( $timeDiff < 60 && $isSecond ) {
            $timeText .= $timeDiff . $_second;
        }

        return $timeText . $_postfix;
    }

    /**
     * 获取中文星期
     * @param  int    $num 阿拉伯星期数，0为星期日
     * @return string      中文星期
     */
    public static function getWeekdayByNum( $num ) {
        $weekday = array(
            '星期日',
            '星期一',
            '星期二',
            '星期三',
            '星期四',
            '星期五',
            '星期六',
        );
        return ( $num < 7 ? $weekday[$num] : false );
    }

    /**
     * 日期转时间戳
     * @param  string $datatime 日期时间
     * @return string           时间戳
     */
    public static function dateTime2timestamp( $datatime ) {
        return (int) strtotime( $datatime );
    }

    /**
     * 生日转年龄
     * @param  string $birthday  生日
     * @param  string $separator 分隔符
     * @return int               年龄
     */
    public static function birthday2age( $birthday, $separator = '-' ) {
        return (int) ( date('Y', time()) - current(explode($separator, $birthday)) );
    }

    /**
     * 年龄转出生年份
     * @param  int    年龄
     * @return string 出生年份
     */
    public static function age2year( $age ) {
        return (string) ( date('Y', time()) - $age );
    }

    /**
     * 获取两个日期之间的日期
     * @param  string $begin      开始日期
     * @param  string $end        结束日期
     * @param  string $format     日期格式
     * @return array  $dateSeries 日期序列
     */
    public static function getDateSerialize( $begin, $end, $format = 'Y-m-d' ) {
        $beginDate  = strtotime( $begin );
        $endDate    = strtotime( $end );
        $dateSeries = array();
        while( $beginDate < $endDate ) {
            $beginDate += 86400;
            $dateSeries[] = date( $format, $beginDate );
        }
        return (array) $dateSeries;
    }

}
