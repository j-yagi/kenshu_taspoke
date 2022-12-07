<?php

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__) . '/bootstrap.php';

final class LogTest extends TestCase
{
    public function testInfo()
    {
        $this->assertTrue(Log::info('infoログテスト', __METHOD__));
    }

    public function testDebug()
    {
        $this->assertTrue(Log::debug('debugログテスト', __METHOD__));
    }

    public function testWarning()
    {
        $this->assertTrue(Log::warning('warningログテスト', __METHOD__));
    }

    public function testError()
    {
        $this->assertTrue(Log::error('errorログテスト', __METHOD__));
    }
}
