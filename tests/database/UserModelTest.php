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
            'wedding_role' => 'bride',
            'role'       => 'user',
            'is_active'  => 1,
        ]);

        $user = $model->find($id);

        $this->assertIsArray($user);
        $this->assertNotSame('secret123', $user['password']);
        $this->assertSame('bride', $user['wedding_role']);
        $this->assertTrue($model->verifyPassword('secret123', $user['password']));
        $this->assertFalse($model->verifyPassword('wrong-password', $user['password']));
    }

    public function testWeddingRoleMustBeBrideOrGroomWhenProvided(): void
    {
        $model = new UserModel();

        $result = $model->insert([
            'first_name' => 'Invalid',
            'last_name'  => 'Role',
            'email'      => 'invalid-role@example.com',
            'password'   => 'secret123',
            'wedding_role' => 'guest',
            'role'       => 'user',
            'is_active'  => 1,
        ]);

        $this->assertFalse($result);
        $this->assertArrayHasKey('wedding_role', $model->errors());
    }
}
