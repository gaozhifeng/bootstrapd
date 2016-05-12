<?php

/**
 * @brief        HTTP 响应类
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2015-9-20 13:20:16
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\library\util\http;

class HttpResponse {

    /**
     * 信息
     */
    const HTTP_STATUS_100 = '100 Continue';
    const HTTP_STATUS_101 = '101 Switching Protocols';
    const HTTP_STATUS_102 = '102 Processing';

    /**
     * 完成
     */
    const HTTP_STATUS_200 = '200 OK';
    const HTTP_STATUS_201 = '201 Created';
    const HTTP_STATUS_202 = '202 Accepted';
    const HTTP_STATUS_203 = '203 Non-Authoritative Information';
    const HTTP_STATUS_204 = '204 No Content';
    const HTTP_STATUS_205 = '205 Reset Content';
    const HTTP_STATUS_206 = '206 Partial Content';
    const HTTP_STATUS_207 = '207 Multi-Status';

    /**
     * 重定向
     */
    const HTTP_STATUS_300 = '300 Multiple Choices';
    const HTTP_STATUS_301 = '301 Moved Permanently';
    const HTTP_STATUS_302 = '302 Found';
    const HTTP_STATUS_303 = '303 See Other';
    const HTTP_STATUS_304 = '304 Not Modified';
    const HTTP_STATUS_305 = '305 Use Proxy';
    const HTTP_STATUS_306 = '306 (Unused)';
    const HTTP_STATUS_307 = '307 Temporary Redirect';

    /**
     * 请求错误
     */
    const HTTP_STATUS_400 = '400 Bad Request';
    const HTTP_STATUS_401 = '401 Unauthorized';
    const HTTP_STATUS_402 = '402 Payment Granted';
    const HTTP_STATUS_403 = '403 Forbidden';
    const HTTP_STATUS_404 = '404 File Not Found';
    const HTTP_STATUS_405 = '405 Method Not Allowed';
    const HTTP_STATUS_406 = '406 Not Acceptable';
    const HTTP_STATUS_407 = '407 Proxy Authentication Required';
    const HTTP_STATUS_408 = '408 Request Time-out';
    const HTTP_STATUS_409 = '409 Conflict';
    const HTTP_STATUS_410 = '410 Gone';
    const HTTP_STATUS_411 = '411 Length Required';
    const HTTP_STATUS_412 = '412 Precondition Failed';
    const HTTP_STATUS_413 = '413 Request Entity Too Large';
    const HTTP_STATUS_414 = '414 Request-URI Too Large';
    const HTTP_STATUS_415 = '415 Unsupported Media Type';
    const HTTP_STATUS_416 = '416 Requested range not satisfiable';
    const HTTP_STATUS_417 = '417 Expectation Failed';
    const HTTP_STATUS_422 = '422 Unprocessable Entity';
    const HTTP_STATUS_423 = '423 Locked';
    const HTTP_STATUS_424 = '424 Failed Dependency';

    /**
     * 服务器错误
     */
    const HTTP_STATUS_500 = '500 Internal Server Error';
    const HTTP_STATUS_501 = '501 Not Implemented';
    const HTTP_STATUS_502 = '502 Bad Gateway';
    const HTTP_STATUS_503 = '503 Service Unavailable';
    const HTTP_STATUS_504 = '504 Gateway Time-out';
    const HTTP_STATUS_505 = '505 HTTP Version not supported';
    const HTTP_STATUS_507 = '507 Insufficient Storage';

    /**
     * 页面类型
     */
    const CONTENT_TYPE_HTML  = 'text/html';
    const CONTENT_TYPE_TXT   = 'text/plain';
    const CONTENT_TYPE_JSON  = 'application/json';
    const CONTENT_TYPE_XML   = 'text/xml';

    /**
     * HTTP 版本
     * @var string
     */
    private static $_HTTP_VERSION = '';

    /**
     * HTTP 状态码
     * @var string
     */
    private static $_HTTP_STATUS = '';
    /**
     * 页面类型
     * @var string
     */
    private static $_CONTENT_TYPE = '';

    /**
     * 设置 Http版本
     * @param  string $version 版本号
     * @return boolean
     */
    public static function setHttpVersion( $version = '' ) {
        if ( empty($version) ) {
            self::$_HTTP_VERSION = substr( $_SERVER['SERVER_PROTOCOL'], -3 );
        } else {
            $version = round( (float) $version, 1 );
            if ($version < 1.0 || $version > 1.1) {
                return false;
            }
            self::$_HTTP_VERSION = sprintf( '%0.1f', $version );
        }
        return true;
    }

    /**
     * 设置 Http状态码
     * @param  integer $statusCode 状态码
     * @return boolean
     */
    public static function setStatusCode( $statusCode ) {
        self::$_HTTP_STATUS = $statusCode;
        return true;
    }

    /**
     * 设置 Content-Type
     * @param  string $contentType 页面类型
     * @return boolean
     */
    public static function setContentType( $contentType ) {
        self::$_CONTENT_TYPE = $contentType;
        return true;
    }

    /**
     * 发送 header
     * @return boolean
     */
    public static function sendHeader() {
        $httpDeclare = sprintf( 'HTTP/%s %s', self::$_HTTP_VERSION, self::$_HTTP_STATUS );
        header( $httpDeclare );

        $contentType = sprintf( 'Content-Type: %s', self::$_CONTENT_TYPE );
        header( $contentType );

        return true;
    }

}
