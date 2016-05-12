<?php

/**
 * @brief        错误码
 *
 * @author       Feng <mail.gzf@foxmail>
 * @since        2015-9-10 23:23:06
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\config;

class ErrorCode {

    /**
     * 系统级别
     */
    const ERR_SUCESS                     = 10000;
    const ERR_SYSTEM                     = 10001;
    const ERR_NOT_ALLOW_HTTP_METHOD      = 10002;
    const ERR_NOT_FOUND                  = 10003;

    /**
     * 应用级别
     */
    const ERR_NOT_FOUND_FILE             = 10100;
    const ERR_NOT_FOUND_CLASS            = 10101;
    const ERR_NOT_FOUND_CLASS_METHOD     = 10102;
    const ERR_NOT_FOUND_SETBINDER        = 10103;
    const ERR_NOT_FOUND_SETBINDER_URI    = 10104;
    const ERR_NOT_FOUND_SETBINDER_METHOD = 10105;

    /**
     * 错误描述
     * @var array
     */
    public static $ERR_MSG = [
        self::ERR_SUCESS                     => '成功',
        self::ERR_SYSTEM                     => '系统繁忙，请稍后重试',
        self::ERR_NOT_ALLOW_HTTP_METHOD      => '不允许的 HTTP方法',
        self::ERR_NOT_FOUND                  => ':-D 请求的页面不在这个地球上，去火星上看看',
        self::ERR_NOT_FOUND_FILE             => '类文件没有找到',
        self::ERR_NOT_FOUND_CLASS            => '类没有找到',
        self::ERR_NOT_FOUND_CLASS_METHOD     => '类方法没有找到',
        self::ERR_NOT_FOUND_SETBINDER        => 'setBinder 没有找到',
        self::ERR_NOT_FOUND_SETBINDER_URI    => 'setBinder 关系 uri 没有找到',
        self::ERR_NOT_FOUND_SETBINDER_METHOD => 'setBinder 关系 method 没有找到',
    ];
}


