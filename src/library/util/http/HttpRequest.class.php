<?php

/**
 * @brief        HTTP 请求类
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2015-1-15 17:36:51
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\library\util\http;

class HttpRequest {

    /**
     * curl Get请求
     * @param  string  $url       请求url
     * @param  array   $data      请求数据
     * @param  array   $header    请求头
     * @param  array   $option    curl选项
     * @param  boolean $headerRes 返回头
     * @param  integer $timeout   请求超时
     * @param  integer $retry     重试次数
     * @return string
     */
    public static function curlGet( $url, array $data = array(), array $header = array(), array $option = array(),
                                        $headerRes = false, $timeout = 5000, $retry = 3 ) {
        return self::curlRequest( $url, 'GET', $data, $header, $option, $headerRes, $timeout, $retry );
    }

    /**
     * curl Post请求
     * @param  string  $url       请求url
     * @param  array   $data      请求数据
     * @param  array   $header    请求头
     * @param  array   $option    curl选项
     * @param  boolean $headerRes 返回头
     * @param  integer $timeout   请求超时
     * @param  integer $retry     重试次数
     * @return string
     */
    public static function curlPost( $url, $data, array $header = array(), array $option = array(),
                                        $headerRes = false, $timeout = 5000, $retry = 3 ) {
        return self::curlRequest( $url, 'POST', $data, $header, $option, $headerRes, $timeout, $retry );
    }

    /**
     * curl请求
     * @param  string  $url       请求url
     * @param  string  $method    请求方法
     * @param  array   $data      提交数据
     * @param  array   $header    请求头
     * @param  array   $option    curl选项
     * @param  boolean $headerRes 返回头
     * @param  integer $timeout   请求超时
     * @param  integer $retry     重试次数
     * @return string
     */
    public static function curlRequest( $url, $method = 'GET', array $data = array(), array $header = array(),
                                            array $option = array(), $headerRes = false, $timeout = 5000, $retry = 3 ) {
        //请求数据
        if ( !empty($data) and in_array($method, array('GET', 'DELETE')) ) {
            $url .= '?' . http_build_query( $data );
        }

        //curl选项
        $curlOption = [
            CURLOPT_URL            => $url,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $header,
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_HEADER         => $headerRes,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_NOSIGNAL       => true,
            CURLOPT_TIMEOUT_MS     => $timeout,
        ];
        if ( !empty($option) ) {
            $curlOption = array_merge( $curlOption, $option );
        }

        //初始化
        $ch = curl_init();
        curl_setopt_array( $ch, $curlOption );

        //重试
        for ( $i = 0; $i < $retry; $i ++ ) {
            $result  = curl_exec( $ch );
            $reponse = curl_getinfo( $ch );
            $errno   = curl_errno( $ch );
            $error   = curl_error( $ch );

            if ( $errno === 0 ) {
                break;
            }
        }

        curl_close( $ch );

        //异常
        if ( $errno ) {
            throw new \Exception( sprintf('%s %s %s', __METHOD__, $errno, $error) );
        }

        return $result;
    }

    /**
     * stream Get请求
     * @param  string  $url     请求url
     * @param  array   $data    请求数据
     * @param  integer $timeout 超时时间
     * @param  integer $retry   重试次数
     * @return string
     */
    public static function streamGet( $url, array $data = array(), $timeout = 3, $retry = 3 ) {
        return self::streamRequest( $url, 'GET', $data, $timeout, $retry );
    }

    /**
     * stream请求
     * @param  string  $url     请求url
     * @param  string  $method  请求方法
     * @param  array   $data    请求数据
     * @param  integer $timeout 超时时间
     * @param  integer $retry   重试次数
     * @return string
     */
    public static function streamRequest( $url, $method = 'GET', array $data = array(), $timeout = 3, $retry = 3 ) {
        $streamSetting = stream_context_create(
            array(
                'http' => array(
                    'method'  => $method,
                    'timeout' => $timeout,
                    'content' => http_build_query( $data ),
                ),
            )
        );

        $content = '';
        for ( $i = 0; $i < $retry; $i ++ ) {
            $content = file_get_contents( $url, false, $streamSetting );
            if ( $content === false ) {
                break;
            }
        }

        return $content;
    }

    /**
     * Socket Get
     * @param  string  $url            请求url
     * @param  integer $connectTimeout 连接超时
     * @param  integer $readTimeout    读取超时
     * @param  integer $retry          重试次数
     * @return string
     */
    public static function socketGet( $url, $connectTimeout = 1, $readTimeout = 3, $retry = 3 ) {
        $content = '';
        for ( $i = 0; $i < $retry; $i ++ ) {
            $fp = stream_socket_client( $url, $errno, $error, $connectTimeout );
            if ( $fp ) {
                stream_set_timeout( $fp, $readTimeout );
                $content = stream_get_contents( $fp );
                break;
            }
        }
        fclose( $fp );

        return $content;
    }

}
