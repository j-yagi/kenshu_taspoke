<?php

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__) . '/bootstrap.php';
require_once ROOT_DIR . '/app/Utill/Validation.php';

final class ValidationTest extends TestCase
{
    protected function setUp(): void
    {
        $this->validation = new Validation();
    }

    public function testCheckUnique()
    {
        $result = $this->validation->check_unique('1668319372@sample.com', 'users', 'email', 1);
        $this->assertEmpty($result);
    }
}
