<?php

/**
 * @brief        MySQL 类
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2015-3-7 13:14:51
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\library\util\db;

use bootstrapd\config\GlobalConfig;
use bootstrapd\library\util\datetime\Timer;
use bootstrapd\library\util\log\Logger;

class MySql {

    /**
     * 句柄
     * @var array
     */
    public static $HANDLE = array();

    /**
     * 获取句柄
     * @param  string  $database 数据库
     * @param  boolean $slave    从库
     * @param  string  $instance 实例
     * @return object
     * @throws InvalidArgumentException If 无效句柄
     */
    public static function getDbHandler( $database, $slave = true, $instance = 'DEFAULT' ) {
        $instance  = strtoupper( $instance );
        if ( $slave ) {
            $instance = sprintf( 'DB_SERVER_%s_SLAVE', $instance );
        } else {
            $instance = sprintf( 'DB_SERVER_%s_MASTER', $instance );
        }

        //数据库参数
        $server = GlobalConfig::$$instance;

        return self::_createHandler( $server, $database );
    }

    /**
     * 创建句柄
     * @param  array  $server   服务器参数
     * @param  string $database 数据库
     * @return object
     * @throws InvalidArgumentException If 参数错误
     *         Exception If 连接或运行错误
     */
    private static function _createHandler( array $server, $database ) {
        if ( empty($server) || empty($database) ) {
            throw new \InvalidArgumentException( 'MySQL server config or database error.' );
        }

        //标识
        $server['database'] = $database;
        $handlerKey = self::_getHandlerKey( $server );

        //单例
        if ( isset(self::$HANDLE[$handlerKey]) ) {
            self::_checkHandler( self::$HANDLE[$handlerKey] );
            return self::$HANDLE[$handlerKey];
        }

        //重试
        $flag = false;
        for ( $i = 0; $i < 2; $i ++ ) {
            $mysqli = @new \mysqli( $server['host'], $server['username'], $server['password'], $server['database'], $server['port'] );
            if ( $mysqli && is_object($mysqli) && !$mysqli->connect_errno && $mysqli->thread_id > 0 ) {
                $flag = true;
                break;
            }
            usleep( 50000 );
        }

        //异常
        if ( !$flag ) {
            $errno = $mysqli->connect_errno;
            $error = $mysqli->connect_error;
            throw new \Exception( 'MySQL connect error. errno: ' . $errno . ' error: ' . $error );
        }

        //字符
        $flag = $mysqli->set_charset( $server['charset'] );
        if ( !$flag ) {
            $mysqli->close();
            throw new \Exception( 'MySQL set charset error.' );
        }

        return self::$HANDLE[$handlerKey] = $mysqli;
    }

    /**
     * 检查句柄
     * @param  object $handler 句柄
     * @return boolean
     * @throws InvalidArgumentException If 无效句柄
     */
    private static function _checkHandler( \Mysqli $handler ) {
        if ( !$handler || !is_object($handler) || $handler->connect_errno || $handler->thread_id < 1 ) {
            throw new \InvalidArgumentException( 'MySQL handler error.' );
        }
        return true;
    }

    /**
     * 获取句柄标识
     * @param  array $server 服务器参数
     * @return string
     */
    private static function _getHandlerKey( array $server ) {
        ksort( $server );
        $server = implode( '_', $server );
        return md5( $server );
    }

    /**
     * 执行
     * @param  object $handler   句柄
     * @param  string $sqlString 语句
     * @return boolean
     * @throws InvalidArgumentException If 无效句柄
     */
    public static function execute( \Mysqli $handler, $sqlString ) {
        self::_checkHandler( $handler );

        return $handler->query( $sqlString );
    }

    /**
     * 查询
     * @param  object  $handler   句柄
     * @param  string  $sqlString 语句
     * @param  integer $type      类型
     * @return array
     * @throws InvalidArgumentException If 无效句柄
     */
    public static function query( \Mysqli $handler, $sqlString, $type = 0 ) {
        self::_checkHandler( $handler );

        //计时器
        Timer::start( 'sql' );

        $data = null;
        do {

            $result = $handler->query( $sqlString );
            if ( !$result || !$result instanceof \mysqli_result ) {
                throw new \Exception( 'Query fail' );
            }

            switch ( $type ) {
                case 0:
                    while ( $row = $result->fetch_assoc() ) {
                        $data[] = $row;
                    }
                    break;

                case 1:
                case 2:
                    $data = $result->fetch_assoc();
                    if ( $type == 2 && !empty($data) ) {
                        $data = current( $data );
                    }
                    break;

                default:
                    throw new \InvalidArgumentException( 'Unkown query type' );
            }

            //释放资源
            $result->free();
        } while ( 0 );

        //慢查询
        Timer::stop( 'sql' );
        $time = Timer::count( 'sql' );
        if ( $time > 10 ) {
            Logger::Warn( 'Slow Sql:' . $sqlString . ' time:' . $time );
        }

        return $data;
    }

    /**
     * 获取全部
     * @param  object $handler   句柄
     * @param  string $sqlString 语句
     * @return array
     * @throws InvalidArgumentException If 无效句柄
     */
    public static function getAll( \Mysqli $handler, $sqlString ) {
        return self::query( $handler, $sqlString, 0 );
    }

    /**
     * 获取行
     * @param  object $handler   句柄
     * @param  string $sqlString 语句
     * @return array
     * @throws InvalidArgumentException If 无效句柄
     */
    public static function getRow( \Mysqli $handler, $sqlString ) {
        return self::query( $handler, $sqlString, 1 );
    }

    /**
     * 获取一个
     * @param  object $handler   句柄
     * @param  string $sqlString 语句
     * @return string
     * @throws InvalidArgumentException If 无效句柄
     */
    public static function getOne( \Mysqli $handler, $sqlString ) {
        return self::query( $handler, $sqlString, 2 );
    }

    /**
     * 获取插入Id
     * @param  object $handler 句柄
     * @return integer|boolean
     * @throws Exception If 无效句柄
     */
    public static function getLastInsertId( \Mysqli $handler ) {
        self::_checkHandler( $handler );

        return $handler->insert_id;
    }

    /**
     * 获取影响函数
     * @param  object $handler 句柄
     * @return integer|boolean
     * @throws InvalidArgumentException If 无效句柄
     */
    public static function getLastAffected( \Mysqli $handler ) {
        self::_checkHandler( $handler );

        return $handler->affected_rows;
    }

    /**
     * 获取错误
     * @param  object $handler 句柄
     * @return array
     * @throws InvalidArgumentException If 无效句柄
     */
    public static function getLastError( \Mysqli $handler ) {
        self::_checkHandler( $handler );

        return [
            $handler->errno => $handler->error,
        ];
    }

}
