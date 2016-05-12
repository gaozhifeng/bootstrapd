<?php

/**
 * @brief        自动装载
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2015-2-17 11:17:42
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\common;

class Autoload {

    public static function run() {
        $classMap = [];
        Loader::addClassMap( $classMap );

        $functionMap = [];
        Loader::addFunctionMap( $functionMap );

        $namespaceMap = [ 'bootstrapd' => BOOTSTRAPD_ROOT ];
        Loader::addNamespaceMap( $namespaceMap );

        $dirs = self::_loadDir( BOOTSTRAPD_ROOT );
        Loader::autoload( true, $dirs );
    }

    /**
     * 目录加载
     * @param  string $dir 目录
     * @return array
     */
    private static function _loadDir( $dir ) {
        $dirs[] = $dir;
        $dirFiles   = scandir( $dir );
        foreach ( $dirFiles as $file ) {
            $filtPath = sprintf( '%s/%s', $dir, $file );
            if ( is_dir($filtPath) && !in_array($file, array('.', '..')) ) {
                $dirs = array_merge( $dirs, self::_loadDir( $filtPath ) );
            }
        }
        return $dirs;
    }

}

Autoload::run();
