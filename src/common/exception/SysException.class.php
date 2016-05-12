<?php

/**
 * @brief        系统异常类
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2015-7-19 19:25:49
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\common\exception;

class SysException extends \Exception {

    /**
     * 构造器
     */
    public function __construct( $message, $code = 0 ) {
        parent::__construct( $message, $code );
    }

}
