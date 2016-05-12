<?php

/**
 * @brief        装载类
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2015-2-17 11:17:42
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\common;

class Loader {

    /**
     * 加载目录
     * @var array
     */
    private static $_DIRS = [];

    /**
     * 类映射
     * @var array
     */
    private static $_CLASS_MAP = [];

    /**
     * 函数映射
     * @var array
     */
    private static $_FUNCTION_MAP = [];

    /**
     * 命名空间映射
     * @var array
     */
    private static $_NAMESPACE_MAP = [];

    /**
     * 自动装载
     * @param  boolean $enabled 启用
     * @param  array   $dirs    目录
     * @return void
     */
    public static function autoload( $enabled = true, $dirs = array() ) {
        if ( $enabled ) {
            spl_autoload_register( array(__CLASS__, 'loadclass') );
        } else {
            spl_autoload_unregister( array(__CLASS__, 'loadclass') );
        }

        self::loadFunction();
        self::addDir( $dirs );
    }

    /**
     * 装载类
     * @param  string $class 类名
     * @return void
     */
    public static function loadclass( $class ) {
        $class = str_replace( array('\\', '_'), '/', $class );
        $classFile = $class . '.class.php';

        do {
            //查找类映射
            if ( array_key_exists($class, self::$_CLASS_MAP)) {
                $classPath = self::$_CLASS_MAP[$class];
                if ( is_file($classPath) ) {
                    require_once $classPath; break;
                }
            }

            //查找命名空间
            list( $namespace ) = explode( '/', $class );
            if ( array_key_exists($namespace, self::$_NAMESPACE_MAP) ) {
                $classPath = str_replace($namespace, self::$_NAMESPACE_MAP[$namespace], $classFile);
                if ( is_file($classPath) ) {
                    require_once $classPath; break;
                }
            }

            //递归目录
            foreach ( self::$_DIRS as $dir ) {
                $classPath = $dir . '/' . $classFile;
                if ( is_file($classPath) ) {
                    require_once $classPath; break;
                }
            }
        } while ( 0 );

    }

    /**
     * 装载函数
     * @return void
     */
    public static function loadFunction() {
        foreach ( self::$_FUNCTION_MAP as $funName => $funcPath ) {
            if ( is_file($funcPath) ) {
                require_once $funcPath;
            }
        }
    }

    /**
     * 装载目录
     * @param  array $dir 目录
     * @return void
     */
    public static function addDir( $dirs ) {
        if ( is_array($dirs) ) {
            foreach ( $dirs as $dir ) {
                self::addDir( $dir );
            }
        } else if ( is_string($dirs) && !in_array($dirs, self::$_DIRS) ) {
            self::$_DIRS[] = $dirs;
        }
    }

    /**
     * 添加类映射
     * @param  array $classMap 类映射
     * @return void
     */
    public static function addClassMap( array $classMap ) {
        self::$_CLASS_MAP = array_merge( self::$_CLASS_MAP, $classMap );
    }

    /**
     * 添加函数映射
     * @param  array $functionMap 函数映射
     * @return void
     */
    public static function addFunctionMap( array $functionMap ) {
        self::$_FUNCTION_MAP = array_merge( self::$_FUNCTION_MAP, $functionMap );
    }

    /**
     * 添加命名空间映射
     * @param  array $namespaceMap 命名空间映射
     * @return void
     */
    public static function addNamespaceMap( array $namespaceMap ) {
        self::$_NAMESPACE_MAP = array_merge( self::$_NAMESPACE_MAP, $namespaceMap );
    }

}
