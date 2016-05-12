<?php

/**
 * @brief        视图类
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2014-11-16 17:47:57
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\common;

use bootstrapd\library\util\http\HttpResponse;

class View {

    /**
     * 模板变量
     * @param  array $data 模板变量
     * @return void
     */
    public function fetch( array $data ) {
        foreach ( $data as $k => $v ) {
            $this->$k = $v;
        }
    }

    /**
     * 模板输出
     * @param  array  $data 模板变量
     * @param  string $tpl  模板文件
     * @return string
     */
    public function render( array $data, $tpl ) {
        $this->fetch( $data );

        ob_start();
        $tplPath = $this->getTplPath();
        require_once $tplPath . '/' . $tpl;
        $response = ob_get_contents();
        ob_end_clean();

        $this->display( $response );
    }

    /**
     * 获取模板路径
     * @return string
     */
    public function getTplPath() {
        $analysisUri = Router::parse();
        return sprintf( '%s/%s/template', BOOTSTRAPD_APP, $analysisUri['module'] );
    }

    /**
     * 显示
     * @param  string|array  $body   实体内容
     * @param  string        $status 状态码
     * @param  boolean       $isJson Json格式
     * @return string
     */
    public static function display( $body, $status = '', $isJson = false ) {
        //设置 Http版本
        HttpResponse::setHttpVersion();

        //设置 Http状态码
        if ( empty($status) ) {
            $status = HttpResponse::HTTP_STATUS_200;
        }
        HttpResponse::setStatusCode( $status );

        //设置 Content-Type
        HttpResponse::setContentType( HttpResponse::CONTENT_TYPE_HTML );
        if ( $isJson ) {
            HttpResponse::setContentType( HttpResponse::CONTENT_TYPE_JSON );
            $body = json_encode( $body );
        }

        HttpResponse::sendHeader();
        echo $body;

        return;
    }

    /**
     * 显示json
     * @param  array   $body   实体内容
     * @param  integer $status 状态码
     * @return string
     */
    public static function displayJson( $body, $status = 200 ) {
        self::display( $body, $statusCode );
    }

    /**
     * 显示调试时间
     * @param  string $time 调试时间
     * @param  string $ip   服务器地址
     * @return string
     */
    public static function displayTools( $time, $ip ) {
        $body = sprintf( '<blockquote style="background:#333;margin:0;padding:5px 8px;text-align:center;position:absolute;bottom:0;left:0;font-family:Verdana;font-size:12px;color:#fff;">Page Run: %ss - %s</blockquote>', $time, $ip );
        self::display( $body );
    }

}
