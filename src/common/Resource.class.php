<?php

/**
 * @brief        资源类
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2015-7-22 22:45:01
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\common;

abstract class Resource {

    /**
     * 模板输出
     * @param  array  $data 模板变量
     * @param  string $tpl  模板文件
     * @return string
     */
    public function render( array $data, $tpl ) {
        $view = new View();
        $view->render( $data, $tpl );
    }

}
