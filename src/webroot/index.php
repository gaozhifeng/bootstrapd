<?php

/**
 * @brief        入口文件
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2014-11-16 17:47:57
 * @copyright    (C) bootstrapd
 */

require_once preg_replace( '/[\/\\\\]{1,}/', '/', dirname(__DIR__) . '/common/bootstrap.inc.php' );

use bootstrapd\common\Router;

//运行
Router::run();
