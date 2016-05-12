<?php

/**
 * @brief        公共配置设置
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2014-11-17 12:16:21
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\config;

class GlobalConfig {

    /**
     * 错误显示
     */
    const DEBUG = false;

    /**
     * 系统时区
     */
    const TIME_ZONE = 8;

    /**
     * 系统版本号
     */
    const VERSION = '0.9';

    /**
     * 数据库地址
     */
    const DB_HOST = 'localhost';

    /**
     * 数据库用户
     */
    const DB_USERNAME = 'root';

    /**
     * 数据库密码
     */
    const DB_PASSWORD = '';

    /**
     * 数据库端口
     */
    const DB_PORT = '3306';

    /**
     * 数据库编码
     */
    const DB_CHARSET = 'utf8';

    /**
     * 数据主库参数
     * @var array
     */
    public static $DB_SERVER_DEFAULT_MASTER = [
        'host'     => self::DB_HOST,
        'username' => self::DB_USERNAME,
        'password' => self::DB_PASSWORD,
        'port'     => self::DB_PORT,
        'charset'  => self::DB_CHARSET,
    ];

    /**
     * 数据从库参数
     * @var array
     */
    public static $DB_SERVER_DEFAULT_SLAVE = [
        'host'     => self::DB_HOST,
        'username' => self::DB_USERNAME,
        'password' => self::DB_PASSWORD,
        'port'     => self::DB_PORT,
        'charset'  => self::DB_CHARSET,
    ];

    /**
     * Redis服务器参数
     * @var array
     */
    public static $REDIS_SERVER_DEFAULT = [
        'host'     => '10.3.2.9',
        'port'     => 6379,
        'weight'   => 1,
        'database' => 0,
        'password' => '',
        'timeout'  => 3,
    ];

    /**
     * Memcached服务器参数
     * @var array
     */
    public static $MC_SERVER_DEFAULT = [
        [
            'host'   => '',
            'port'   => '',
            'weight' => '',
        ],
    ];


}
