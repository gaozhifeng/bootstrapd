<?php

/**
 * @brief        SQL 语句构造
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2015-3-7 11:20:48
 * @copyright    (C) bootstrapd
 */

namespace bootstrapd\library\util\db;

class SqlBuilder {

    /**
     * 构造查询语句
     * @param  string       $tableName 表名
     * @param  string|array $field     字段
     * @param  array        $filter    条件过滤
     * @param  array        $limit     查询限制
     * @param  array        $orderBy   排序
     * @param  array        $groupBy   分组
     * @param  array        $index     使用索引
     * @return string
     */
    public static function selectBuilder( $tableName, $field = '*', array $filter = array(), $limit = '',
                                            array $orderBy = array(), array $groupBy = array(), array $index = array() ) {
        $sqlString = 'SELECT';

        if ( is_array($field) ) {
            $sqlString .= sprintf( ' `%s`', implode('`, `', $field) );
        } else {
            $sqlString .= sprintf( ' %s', $field );
        }

        $sqlString .= sprintf( ' FROM `%s`', $tableName );

        if ( !empty($index) ) {
            $use = strtoupper( key($index) );
            if ( !in_array($use, array('USE', 'FORCE', 'IGNORE')) ) {
                continue;
            }
            $sqlString .= sprintf( ' %s (`%s`)', $use, implode('`, `', $index[$use]) );
        }

        if ( !empty($filter) ) {
            $sqlString .= self::filterBuilder( $filter );
        }

        if ( !empty($orderBy) ) {
            $sqlString .= ' ORDER BY';
            foreach ( $orderBy as $key => $value ) {
                $value = strtoupper( $value );
                if ( !in_array($value, array('ASC', 'DESC')) ) {
                    continue;
                }
                $sqlString .= sprintf( ' `%s` %s, ', $key, $value );
            }
            $sqlString = rtrim( $sqlString, ', ' );
        }

        if ( !empty($groupBy) ) {
            $sqlString .= sprintf( ' GROUP BY `%s`', implode('`, `', $groupBy) );
        }

        if ( !empty($limit) ) {
            $sqlString .= sprintf( ' LIMIT %s', implode(',', $limit) );
        }

        return $sqlString . ';';
    }

    /**
     * 构造插入语句
     * @param  string $tableName 表名
     * @param  array  $data      数据
     * @return string
     */
    public static function insertBuilder( $tableName, array $data ) {
        $sqlString  = sprintf( 'INSERT INTO `%s` VALUES ', $tableName );
        $sqlString .= self::dataBuilder( $data );

        return $sqlString . ';';
    }

    /**
     * 构造更新语句
     * @param  string $tableName 表名
     * @param  array  $data      数据
     * @param  array  $filter    过滤
     * @return string
     */
    public static function updateBuilder( $tableName, array $data, array $filter = array() ) {
        $sqlString  = sprintf( 'UPDATE `%s` SET ', $tableName );
        $sqlString .= self::dataBuilder( $data );

        if ( !empty($filter) ) {
            $sqlString .= self::filterBuilder( $filter );
        }

        return $sqlString . ';';
    }

    /**
     * 构造删除语句
     * @param  string $tableName 表名
     * @param  array  $filter    过滤
     * @return string
     */
    public static function deleteBuilder( $tableName, array $filter ) {
        $sqlString  = sprintf( 'DELETE FROM `%s` ', $tableName );
        $sqlString .= self::filterBuilder( $filter );

        return $sqlString . ';';
    }

    /**
     * 构造条件
     * @param  array  $filter 过滤条件
     * @return string
     */
    public static function filterBuilder( array $filter ) {
        $filterString = '';

        foreach ( $filter as $item ) {
            if ( is_array(current($item)) ) {
                $_filterString = '';
                foreach ( $item as $value ) {
                    $_filterString .= self::_filterBuilder( $value );
                }
                $filterString .= sprintf( ' AND ( %s ) ', ltrim($_filterString, ' OR') );
            }

            if ( !in_array(count($item), array(3, 4)) ) {
                continue;
            }

            //关键字转大写
            $item[1] = strtoupper( $item[1] );
            if ( !in_array($item[1], array('=', '>', '<', '<>', '>=', '<=', 'IN', 'NOT IN', 'LIKE', 'NOT LIKE')) ) {
                continue;
            }

            //OR 支持
            $sign = ' AND';
            if ( count($item) == 4 ) {
                $sign = ' OR';
            }

            //数组转字符串
            if ( in_array($item[1], array('IN', 'NOT IN')) ) {
                foreach ( $item[2] as $value ) {
                    $inValue[] = addslashes( $value );
                }
                $filterString .= sprintf( "${sign} `%s` %s ('%s')", $item[0], $item[1], implode("', '", $inValue) );
            } else {
                $filterString .= sprintf( "${sign} `%s` %s '%s'", $item[0], $item[1], addslashes($item[2]) );
            }

            //剥离多余连接符
            $filterString = ltrim( $filterString, ' AND' );
            $filterString = ltrim( $filterString, ' OR' );
        }

        return ' WHERE ' . $filterString;
    }

    /**
     * 子条件构造
     * @param  array $filter 子条件
     * @return string
     */
    private static function _filterBuilder( $filter ) {
        if ( count($filter) != 3 ) {
            return;
        }

        //数组转字符串
        return sprintf( " OR `%s` %s '%s'", $filter[0], $filter[1], addslashes($filter[2]) );
    }

    /**
     * 构造数据
     * @param  array $data 数据
     * @return return
     */
    public static function dataBuilder( array $data ) {
        $sqlString = '';
        foreach ( $data as $field => $value ) {
            $sqlString .= sprintf( "`%s` = '%s', ", $field, addslashes($value) );
        }

        return rtrim( $sqlString, ', ' );
    }

}
