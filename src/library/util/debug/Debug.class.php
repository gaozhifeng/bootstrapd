<?php

/**
 * @brief        错误调试
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2015-2-22 9:53:59
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\library\util\debug;

class Debug {

    /**
     * 错误追踪
     * @return array
     */
    public static function debugBacktrace() {
        $backtraceRes = array();
        $backtrace = debug_backtrace();
        foreach ( $backtrace as $item ) {
            if ( !in_array($item['function'], array('getSysUserError', 'debugDisplay', 'debugBacktrace')) ) {
                $backtraceRes[] = array(
                    'file'  => self::_formatFileFidle( $item['file'] ),
                    'line'  => $item['line'],
                    'class' => $item['class'] . $item['type'] . $item['function'],
                );
            }
        }
        return $backtraceRes;
    }

    /**
     * 错误显示
     * @example View::display(
     *              Debug::debugDisplay(array('errno' => $errno, 'msg' => $errstr, 'file' => $errfile, 'line' => $errline))
     *          );
     * @param  array   $lastMsg 最后信息
     * @param  boolean $isTrace 是否显示回溯
     * @return string
     */
    public static function debugDisplay( $lastMsg, $isTrace = true ) {
        if ( $isTrace ) {
            $backtraceRes = self::debugBacktrace();
        } else {
            $backtraceRes = array();
        }
        return self::_formatErrorHtml( $lastMsg, $backtraceRes );

    }

    /**
     * 获取错误文本
     * @param  int $errno 错误码
     * @return string
     */
    public static function getErrorLevelText( $errno ) {

        switch ( $errno ) {
            case E_PARSE:
                return 'E_PARSE';

            case E_ERROR:
                return 'ERROR';

            case E_WARNING:
                return 'WARNING';

            case E_NOTICE:
                return 'NOTICE';

            case E_USER_ERROR:
                return 'USER_ERROR';

            case E_USER_WARNING:
                return 'USER_WARNING';

            case E_USER_NOTICE:
                return 'USER_NOTICE';

            default:
                return 'OTHER';
        }
    }

    /**
     * 格式化文件字段
     * @param  string $file 文件字段
     * @return string
     */
    private static function _formatFileFidle( $file ) {
        return preg_replace( '/^\w+\:/', '', $file );
    }

    /**
     * 格式化错误HTML
     * @param  array $lastMsg   最后信息
     * @param  array $backtrace 追踪堆栈
     * @return string
     */
    private static function _formatErrorHtml( $lastMsg, $backtrace ) {
        $str = '<style>
                div.error-box ul,div.error-box li,div.error-box p,div.error-box h3{margin:0;padding:0;}
                div.error-box{width:680px;margin:15px 10px;padding:15px 15px;border-radius:10px;background:#f5f5f5;font-family:Verdana;font-size:12px;}
                div.error-box h3{line-height:30px;font-size:14px;}
                div.error-box h3 span{margin-right:8px;padding:3px;background:#333;color:#fff;}
                div.error-box p{margin-bottom:10px;}
                div.error-box ul li{border-radius:6px;margin-bottom:8px;padding:5px 10px;background:#ffffc9;line-height:18px;list-style:none;}
                div.error-box ul li span.file{display:block;}
                div.error-box ul li span.line{display:inline-block;width:30px;}
               </style>';
        $str .= '<div class="error-box"><h3><span>' . self::getErrorLevelText( $lastMsg['errno'] ) . '</span>' . $lastMsg['msg'] . '</h3>';
        $str .= '<p>Error on line ' . $lastMsg['line'] . ' in ' . self::_formatFileFidle( $lastMsg['file'] ) . '</p>';
        $str .= '<ul>';
        foreach ( $backtrace as $item ) {
            $str .= '<li>';
            $str .= '<span class="file">' . $item['file'] . '</span>';
            $str .= '<span class="line">' . $item['line'] . '</span>';
            $str .= '<span class="class">' . $item['class'] . '</span>';
            $str .= '</li>';
        }
        $str .= '</ul></div>';
        return $str;
    }

}
