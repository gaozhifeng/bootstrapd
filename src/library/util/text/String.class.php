<?php

/**
 * @brief        字符串处理类
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2014-11-21 22:42:08
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\library\util\text;

class String {

    /**
     * 截取字符串
     * @param  string  $str     要截取的字符串
     * @param  integer $len     截取后长度
     * @param  string  $postfix 超出字符省略后缀
     * @return string           截取后字符串
     */
    public static function trword( $str, $len = 10, $postfix = '...' ) {
        $newStr = '';
        $i = 0;
        $n = 0.0;

        //字符串的字节数
        $strLen = strlen( $str );
        while ( ($n < $len) && ($i < $strLen) ) {
            $ascii = ord( substr( $str, $i, 1 ) );    //得到字符串中第$i位字符的ASCII码
            if($ascii >= 252) {
             ///如果ASCII位高与252
                $newStr .= substr( $str, $i, 6 );    //UTF-8编码规范，将6个连续的字符计为单个字符
                $i += 6;                             //实际Byte计为6
                ++$n;                                //字串长度计1
            } else if ( $ascii >= 248 ) {
                $newStr .= substr( $str, $i, 5 );
                $i += 5;
                ++$n;
            } else if ( $ascii >= 240 ) {
                $newStr .= substr( $str, $i, 4 );
                $i += 4;
                ++$n;
            } else if( $ascii >= 224 ) {
                $newStr .= substr( $str, $i, 3 );
                $i += 3 ;
                ++$n;
            } else if ( $ascii >= 192 ) {
                $newStr .= substr( $str, $i, 2 );
                $i += 2;
                ++$n;
            } else if ( $ascii>=65 && $ascii<=90 && $ascii!=73 ) {
             ///如果是大写字母I除外
                $newStr .= substr( $str, $i, 1 );
                $i += 1;    //实际的Byte数仍计1个，但考虑整体美观，大写字母计成一个高位字符
                ++$n;
            } else if ( !(array_search($ascii, array(37, 38, 64, 109 ,119)) === FALSE) ) {
             ///%,&,@,m,w 字符按1个字符宽
                $newStr .= substr( $str, $i, 1 );
                $i += 1;    //实际的Byte数仍计1个，但考虑整体美观，这些字条计成一个高位字符
                ++$n;
            } else {
             ///其他情况下，包括小写字母和半角标点符号
                $newStr .= substr( $str, $i, 1 );
                $i += 1;      //实际的Byte数计1个
                $n += 0.5;    //其余的小写字母和半角标点等与半个高位字符宽
           }
        }

        if( $i < $strLen ) {
           $newStr .= $postfix;
        }

        return $newStr;
    }

    /**
     * 获取字符串长度
     * 一个汉字计两个字节
     * @param  string $str 计算的字符串
     * @return int         字符串长度
     */
    public static function getStrLen( $str ) {
        $strLen = 0;
        //实际占用的字节数
        $len = strlen( $str );
        $i = 0;
        while( $i < $len ) {
            $chr = ord( $str[$i] );
            //非ascii字符
            if ( $chr & 0x80 ) {
                $strLen += 2;
                $chr <<= 1;
                while ( $chr & 0x80 ) {
                    ++ $i;
                    $chr <<= 1;
                }
                ++ $i;
            } else {
            ///ascii字符
                ++ $strLen;
                ++ $i;
            }
        }
        return (int) $strLen;
    }

    /**
     * 获取字符串长度
     * 一个汉字计一个字节
     * @param  string $str 计算的字符串
     * @return int         字符串长度
     */
    public static function getStrLen2( $string ) {
        $string = str_replace( "\r\n", ' ', $string );
        return (int) mb_strlen( $string, 'utf-8' );
    }

    /**
     * 查找字符串第一次出现的位置
     * @param  string  $string 源字符串
     * @param  string  $needle 要查找的字符串
     * @param  integer $offset 搜索位置的偏移
     * @return int | bool
     */
    public static function findStrPos( $string, $needle, $offset = 0 ) {
        return mb_strpos( $string, $needle, $offset, 'utf-8' );
    }

    /**
     * 判断是否以某字符串开头
     * @param  [type] $string [description]
     * @param  [type] $prefix [description]
     * @return [type]         [description]
     */
    public static function beginWith( $string, $prefix ) {
        return substr_compare( $string, $prefix, 0, strlen($prefix) );
    }

    /**
     * 判断是否以某字符串结尾
     * @param  [type] $string  [description]
     * @param  [type] $postfix [description]
     * @return [type]          [description]
     */
    public static function endWith( $string, $postfix ) {
        $size = strlen( $postfix );
        return substr_compare( $string, $postfix, strlen($string)-$size, $size );
    }

    /**
     * 获取随机字符串
     * @param  integer $minLen 最小长度
     * @param  integer $type   生成的字符串类别
     *                         0为大小写和数字的组合
     *                         1为全部小写和数字的组合
     *                         2为全部大写和数字的组合
     *                         3为大小写字母的组合
     *                         4为全部数字的组合
     *                         5为全部小写的组合
     *                         6为全部大写的组合
     * @return string          随机字符串
     */
    public static function random( $len = 9, $type = 4 ) {

        while ( strlen($str) < $len ) {
            $ascii = array(
                mt_rand( 49, 57 ),     //1-9的ASCII码
                mt_rand( 65, 90 ),     //A-Z的ASCII码
                mt_rand( 97, 122 ),    //a-z的ASCII码
            );

            switch ( $type ) {
                case 0:
                    return chr( $ascii[mt_rand(0, 2)] );
                case 1:
                    return strtolower( chr($ascii[mt_rand(0, 2)]) );
                case 2:
                    return strtoupper( chr($ascii[mt_rand(0, 2)]) );
                case 3:
                    return chr( $ascii[mt_rand(1, 2)] );
                case 4:
                    return chr( $ascii[0] );
                case 5:
                    return chr( $ascii[2] );
                case 6:
                    return chr( $ascii[1] );
                default:
                    return chr( $ascii[0] );
            }
        }
    }

    /**
     * 判断是否是中文
     * @param  string $string 检测字符串
     * @return boolean
     */
    public static function isChineseUtf8( $string ) {
        return preg_match( '/^[\x7f-\xff]+$/', $string );
    }

    /**
     * 获取guid
     * 标准的UUID格式为：xxxxxxxx-xxxx-xxxx-xxxxxx-xxxxxxxxxx (8-4-4-4-12)
     * @return string
     */
    public static function guid() {
        if ( function_exists('com_create_guid') ) {
            return com_create_guid();
        } else {
            $charId = strtoupper( md5(uniqid(mt_rand(), true)) );
            $hyphen = chr( 45 );    // -
            $uuid   = chr( 123 )    // {
                . substr( $charId, 0,  8 ) . $hyphen
                . substr( $charId, 8,  4 ) . $hyphen
                . substr( $charId, 12, 4 ) . $hyphen
                . substr( $charId, 16, 4 ) . $hyphen
                . substr( $charId, 20, 12 )
                . chr( 125 );        // }

            return $uuid;
        }
    }

}
