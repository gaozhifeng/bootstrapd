<?php

/**
 * @brief        路由类TestCase
 *
 * @author       Feng <mail.gzf@foxmail.com>
 * @since        2015-11-11 16:44:11
 * @copyright    (C) bootstrapd
 */

require_once __DIR__ . '/../../src/common/bootstrap.inc.php' ;

use bootstrapd\common\Router;

class RouterTest extends PHPUnit_Framework_TestCase {

    public function testPase() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI']    = '/';

        $analysisUri = Router::parse();
        $this->assertArrayHasKey( 'method', $analysisUri );
        $this->assertArrayHasKey( 'uri', $analysisUri );
        $this->assertArrayHasKey( 'module', $analysisUri );
        $this->assertArrayHasKey( 'resource', $analysisUri );
        $this->assertArrayHasKey( 'query', $analysisUri );
    }

}
