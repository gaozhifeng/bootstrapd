<?php

/**
 * @brief        首页处理类
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2015-2-18 19:39:11
 * @copyright    (C) bootstrapd
 */

use bootstrapd\common\Resource;

class IndexPage extends Resource {

    public function setBinder() {
        return array(
            '/' => array(
                'GET' => 'default',
            ),
        );
    }

    public function defaultAction() {
        $this->render( array(
            'title'  => ':-D Bootstrapd',
            'slogen' => '开启一段奇幻旅程',
        ), 'index.tpl.php');
    }

}
