<?php

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__) . '/bootstrap.php';
require_once ROOT_DIR . '/app/Models/DB.php';
require_once ROOT_DIR . '/app/Models/Model.php';

final class DBTest extends TestCase
{
    public function testConnectStatic()
    {
        $stmt = DB::getConnection()->query('SELECT * FROM users');
        $rs = $stmt->execute();
        // print_r($stmt->fetchAll());
        $this->assertTrue($rs);
    }

    public function testConnectInstance()
    {
        $db = new DB();
        $stmt = $db->getConnection()->query('SELECT * FROM users');
        $rs = $stmt->execute();
        // print_r($stmt->fetchAll());
        $this->assertTrue($rs);
    }
}
