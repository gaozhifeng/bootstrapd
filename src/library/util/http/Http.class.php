<?php

/**
 * @brief        Http类
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2014-11-20 20:06:34
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\library\util\http;

class Http {

    /**
     * 变量解义
     * @return void
     */
    public static function stripGpc() {
        if ( get_magic_quotes_gpc() ) {
            self::_stripGpc( $_GET );
            self::_stripGpc( $_POST );
            self::_stripGpc( $_COOKIE );
            self::_stripGpc( $_REQUEST );
        }
    }

    /**
     * 变量解义
     * @return void
     */
    private static function _stripGpc( &$var ) {
        if ( is_array($var) ) {
            foreach ( $var as &$_v ) {
                self::_stripGpc( $_v );
            }
        } else {
            $var = trim( stripslashes($var) );
        }
    }

    /**
     * 变量转义
     * @return void
     */
    public static function addGpc() {
        if ( !get_magic_quotes_gpc() ) {
            self::_addGpc( $_GET );
            self::_addGpc( $_POST );
            self::_addGpc( $_COOKIE );
            self::_addGpc( $_REQUEST );
        }
    }

    /**
     * 变量转义
     * @return void
     */
    private static function _addGpc( &$var ) {
        if ( is_array($var) ) {
            foreach ( $var as &$_v ) {
                self::_addGpc( $_v );
            }
        } else {
            $var = trim( addslashes($var) );
        }
    }

    /**
     * 获取请求头
     * @param  string $key 请求头
     * @return string
     */
    public static function getHeader( $key ) {
        if ( stripos($key, 'HTTP') !== 0 ) {
            $key = 'HTTP_' . $key;
        }
        $key = strtoupper( str_replace('-', '_', $key) );

        $value = '';
        do {
            if ( !empty($_SERVER[$key]) ) {
                $value = $_SERVER[$key];
                break;
            }

            if ( function_exists('apache_request_headers') ) {
                $headers = apache_request_headers();
                if ( !empty($headers[$key]) ) {
                    $value = $headers[$key];
                }
            }
        } while ( 0 );

        return $value;
    }

    /**
     * 获取GET变量
     * @param  string  $key      变量名
     * @param  boolean $stripTag 剥离标签
     * @param  boolean $default  默认值
     * @return string|array
     */
    public static function getGet( $key, $stripTag = false, $default = '' ) {
        return self::_getVar( $_GET, $key, $stripTag, $default );
    }

    /**
     * 获取POST变量
     * @param  string  $key      变量名
     * @param  boolean $stripTag 剥离标签
     * @param  boolean $default  默认值
     * @return string|array
     */
    public static function getPost( $key, $stripTag = false, $default = '' ) {
        return self::_getVar( $_POST, $key, $stripTag, $default );
    }

    /**
     * 获取COOKIE变量
     * @param  string  $key      变量名
     * @param  boolean $stripTag 剥离标签
     * @param  boolean $default  默认值
     * @return string
     */
    public static function getCookie( $key, $stripTag = false, $default = '' ) {
        return self::_getVar( $_COOKIE, $key, $stripTag, $default );
    }

    /**
     * 获取REQUEST变量
     * @param  string  $key      变量名
     * @param  boolean $stripTag 剥离标签
     * @param  boolean $default  默认值
     * @return string|array
     */
    public static function getRequest( $key, $stripTag = false, $default = '' ) {
        return self::_getVar( $_REQUEST, $key, $stripTag, $default );
    }

    /**
     * 获取变量
     * @param  sarray  $var      全局变量
     * @param  string  $key      变量名
     * @param  boolean $stripTag 剥离标签
     * @param  boolean $default  默认值
     * @return string|array
     */
    private static function _getVar( &$var, $key, $stripTag = false, $default = '' ) {
        if ( isset($var[$key]) ) {
            if ( $stripTag ) {
                return is_string( $var[$key] ) ? strip_tags( $var[$key] ) : self::_stripTag( $var[$key] );
            } else {
                return $var[$key];
            }
        }

        return $default;
    }

    /**
     * 剥离标签
     * @param  string|array $var 变量
     * @return string|array
     */
    private static function _stripTag( &$var ) {
        foreach ( $var as $key => $value ) {
            if ( is_string($value) ) {
                $var[$key] = strip_tags( $value );
            } else {
                _runStripTag( $value );
            }
        }
        return $var;
    }

    /**
     * 是否GET方式
     * @return boolean
     */
    public static function isGet() {
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }

    /**
     * 是否POST方式
     * @return boolean
     */
    public static function isPost() {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    /**
     * 是否Ajax方式
     * @return boolean
     */
    public static function isAjax() {
        return 'XMLHttpRequest' == self::getHeader( 'X_REQUESTED_WITH' );
    }

    /**
     * 获取当前URI
     * @param  boolean $isScriptName 是否只返回脚本
     * @return string
     */
    public static function getCurrentUrl( $isScriptName = false ) {
        $pageUrl = 'http';
        if ( !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) {
            $pageUrl .= 's';
        }

        $pageUrl .= '://' . $_SERVER['HTTP_HOST'];

        if ( $_SERVER['SERVER_PORT'] != '80' ) {
            $pageUrl .= ':' . $_SERVER['SERVER_PORT'];
        }

        return $pageUrl .= $isScriptName ? $_SERVER['SCRIPT_NAME'] : $_SERVER['REQUEST_URI'];
    }

    /**
     * 安全过滤url
     * @param  string $url
     * @return string
     */
    public static function makeSafeUrl( $url ) {
        $safeUrl = htmlspecialchars_decode( $url );
        return preg_replace( '/[\"\'\\\\n\\\\r<>#]+/', '', $safeUrl );
    }

    /**
     * url重定向
     * @param  string  $url      跳转url
     * @param  integer $status   HTTP状态码
     * @param  boolean $inIframe 通过Js跳转
     * @return string|void
     */
    public static function redirect( $url, $status = 302, $isJs = false ) {
        $url = self::makeSafeUrl( $url );
        if ( !$isJs ) {
            header('HTTP/1.1 ' . $status . ' Moved Temporarily');
            header('Location: ' . $url);
        } else {
            echo '<html>
<head>
<title> </title>
</head>
<body>
<script type="text/javascript">
window.location.href = "' . $url . '";
</script>
</body>
</html>';
        }
        exit;
    }

}
