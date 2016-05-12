<?php

/**
 * @brief        日志类
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2015-7-11 20:23:04
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\library\util\log;

use bootstrapd\library\util\file\File;

class Logger {

    /**
     * 信息日志
     * @param  string $content 内容
     * @return void
     */
    public static function Info( $content ) {
        self::write( 'info', $content );
    }

    /**
     * 警告日志
     * @param  string $content 内容
     * @return void
     */
    public static function Warn( $content ) {
        self::write( 'warn', $content );
    }

    /**
     * 错误日志
     * @param  string $content 内容
     * @return void
     */
    public static function Error( $content ) {
        self::write( 'error', $content );
    }

    /**
     * 致命日志
     * @param  string $content 内容
     * @return void
     */
    public static function Fatal( $content ) {
        self::write( 'fatal', $content );
    }

    /**
     * 写日志
     * @param  string $type    类型
     * @param  string $content 内容
     * @return void
     */
    public static function write( $type, $content ) {
        $logDir     = sprintf( '%s/log/%s/%s', BOOTSTRAPD_RUN, $type, date('Y/m') );
        $logFile    = sprintf( '%s.log', date('d') );
        $logPath    = sprintf( '%s/%s', $logDir, $logFile );
        $logContent = sprintf( "%s\t%s\n", date( 'Y-m-d H:i:s' ), $content );

        File::makeDirs( $logDir );
        File::writeFile( $logPath, $logContent );
    }

}
