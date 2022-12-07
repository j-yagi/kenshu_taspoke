<?php

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__) . '/bootstrap.php';

final class ConfigTest extends TestCase
{
    public function testGet()
    {
        $this->assertSame('taskemon', Config::get('app.name'));
        $this->assertSame('default', Config::get('app.test.test', 'default'));
        $this->assertIsArray(Config::get('app'), 'configファイルがない');
    }
}
