<?php

/**
 * @brief        文件处理类
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2014-11-22 13:16:51
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\library\util\file;

class File {

    /**
     * 读取文件
     * @param  string $name 文件路径
     * @param  string $mode 读取模式（默认从第一行只读）
     * @return string $file 文件内容
     */
    public static function readFile( $name, $mode = 'r' ) {

        if ( !is_file($name) || !is_readable($name) ) {
            return false;
        }

        $file = false;
        if ( function_exists('file_get_contents') ) {
            $file = file_get_contents( $name );
        } else {
            $fp = fopen( $name, $mode );
            if ( flock($fp, LOCK_SH) ) {
                while ( !feof($fp) ) {
                    $file .= fgets( $fp );
                }
                flock( $fp, LOCK_UN );
            }
            fclose( $fp );
        }

        return $file;
    }

    /**
     * 写入文件
     * @param  string $name 文件路径
     * @param  string $data 写入模式
     * @param  string $mode 写入内容
     * @return boolean
     */
    public static function writeFile( $name, $data, $mode = 'a' ) {

        // 如果文件存在，但不可写，则返回失败
        // 文件不存在则会自动创建，所以不进行验证
        if ( is_file($name) && !is_writeable($name) ) {
            return false;
        }

        if ( function_exists('file_put_contents') ) {
            return file_put_contents( $name, $data, FILE_APPEND | LOCK_EX );
        } else {
            $fp = fopen( $name, $mode );
            if ( flock( $fp, LOCK_EX ) ) {
                $fl = fwrite( $fp, $name, $data );
                flock( $fp, LOCK_UN );
            }
            fclose( $fp );
            return ( $fp ? true : false );
        }
    }

    /**
     * 复制文件
     * @param  string $sourcePath 原始路径
     * @param  string $toPath     目标路径
     * @return boolean
     */
    public static function copyFile( $sourcePath, $toPath ) {
        return is_file( $sourcePath ) ? copy( $sourcePath, $toPath ) : false;
    }

    /**
     * 重命名文件（移动文件）
     * @param  string $sourcePath 原始路径
     * @param  string $toPath     目标路径
     * @return boolean
     */
    public static function renameFile( $sourcePath, $toPath ) {
        return is_file( $sourcePath ) ? rename( $sourcePath, $toPath ) : false;
    }

    /*
     * 递归创建文件夹
     */
    public static function makeDirs( $name, $mode = 0777 ) {
        return is_dir( $name ) or ( self::makeDirs(dirname($name), $mode) and mkdir($name, $mode) );
    }

    /**
     * 获取文件大小
     * @param  integer $size 文件字节
     * @return float
     */
    public static function formatBytes( $size ) {
        $unit = array( ' B', ' KB', ' MB', ' GB', ' TB' );
        for ( $u = 0; $size >= 1024 && $u < 4; $u ++ ) {
            $size /= 1024;
        }
        return round( $size, 2 ) . $unit[$u];
    }

    /**
     * 获取文件MIME
     * @param  string $sourcePath 原始路径
     * @return string
     */
    public static function getFileMime( $sourcePath ) {
        $fi = new \finfo( FILEINFO_MIME_TYPE );
        return $fi->file( $sourcePath );
    }

}
