<?php

/**
 * @brief        自动装载TestCase
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2015-11-11 11:54:06
 * @copyright    (C) bootstrapd
 */

require_once __DIR__ . '/../../src/common/bootstrap.inc.php' ;

class AutoloadTest extends PHPUnit_Framework_TestCase {

    public function testRun() {
        $this->assertTrue( class_exists('bootstrapd\common\Loader') );
    }

}
