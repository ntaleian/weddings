<?php

use App\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * @internal
 */
final class UserModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    public function testPasswordIsHashedAndCanBeVerified(): void
    {
        $model = new UserModel();
        $id    = $model->insert([
            'first_name' => 'Grace',
            'last_name'  => 'Tester',
            'email'      => 'grace@example.com',
            'password'   => 'secret123',
            'role'       => 'user',
            'is_active'  => 1,
        ]);

        $user = $model->find($id);

        $this->assertIsArray($user);
        $this->assertNotSame('secret123', $user['password']);
        $this->assertTrue($model->verifyPassword('secret123', $user['password']));
        $this->assertFalse($model->verifyPassword('wrong-password', $user['password']));
    }
}
