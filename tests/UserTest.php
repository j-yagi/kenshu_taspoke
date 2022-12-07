<?php

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__) . '/bootstrap.php';
require_once ROOT_DIR . '/app/Models/User.php';

final class UserTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        $this->user = new User();
        $this->user->test = 'test';
    }

    public function testToArrayDefalt()
    {
        $this->user->sample = 'test';
        $array = $this->user->toArray();
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('email', $array);
        $this->assertArrayHasKey('password', $array);
        $this->assertArrayHasKey('created_at', $array);
        $this->assertArrayHasKey('updated_at', $array);
        $this->assertArrayHasKey('sample', $array);
        $this->assertEquals($array['id'], null);
        $this->assertEquals($array['name'], null);
        $this->assertEquals($array['email'], null);
        $this->assertEquals($array['password'], null);
        $this->assertEquals($array['created_at'], null);
        $this->assertEquals($array['updated_at'], null);
        $this->assertEquals($array['sample'], 'test');
    }

    public function testFindFalse()
    {
        $user = User::find(0);
        $this->assertFalse($user);
    }

    public function testInsert()
    {
        $user = new User();
        $before = $user->toArray();

        $time = time();
        $user->name = 'Test_create_' . $time;
        $user->email = $time . '@sample.com';
        $user->password = password_hash('password_' . $time, PASSWORD_DEFAULT);

        $result = $user->insert();
        $after = $user->toArray();

        $this->assertEquals($result, 1);
        $this->assertIsInt($after['id']);
        $this->assertNotEquals($before['id'], $after['id']);
        $this->assertNotEquals($before['name'], $after['name']);
        $this->assertNotEquals($before['email'], $after['email']);
        $this->assertNotEquals($before['password'], $after['password']);
        $this->assertNotEquals($before['created_at'], $after['created_at']);
        $this->assertNotEquals($before['updated_at'], $after['updated_at']);
    }

    public function testFindGetUseInstance()
    {
        $user = $this->user->find(1);
        $this->assertEquals($user->id, 1);
        $this->assertNotEquals($this->user->id, 1);
    }

    public function testFindGet()
    {
        $user = User::find(1);
        $this->assertEquals($user->id, 1);
    }

    public function testFindGetUseInstancePropInstance()
    {
        $user = $this->user->find(1, $this->user);
        $this->assertEquals($user->id, 1);
        $this->assertEquals($this->user->id, 1);
    }

    public function testToArrayAfter()
    {
        if ($user = User::find(1)) {
            $user->test = 'sample';
            $array = $user->toArray();
            $this->assertArrayHasKey('id', $array);
            $this->assertArrayHasKey('test', $array);
            $this->assertEquals($array['id'], 1);
            $this->assertEquals($array['test'], 'sample');
        }
    }

    public function testUpdate()
    {
        if ($user = User::find(1)) {
            $before = $user->toArray();

            $time = time() + 1;
            $user->name = 'Test_update_' . $time;
            $user->password = password_hash('password_' . $time, PASSWORD_DEFAULT);

            $result = $user->update();
            $after = $user->toArray();
            $this->assertEquals($result, 1);
            $this->assertEquals($before['id'], $after['id']);
            $this->assertNotEquals($before['name'], $after['name']);
            $this->assertEquals($before['email'], $after['email']);
            $this->assertNotEquals($before['password'], $after['password']);
            $this->assertEquals($before['created_at'], $after['created_at'], '作成日時');
        }
    }

    public function testGetFalse()
    {
        $result = User::get('id = :id', ['id' => 0]);
        $this->assertIsArray($result);
        $this->assertCount(0, $result);
    }

    public function testGetALL()
    {
        $result = User::get();
        $this->assertIsArray($result);
        $this->assertNotCount(0, $result);
    }
}
