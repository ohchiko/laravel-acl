<?php

namespace Junges\ACL\Tests\Concerns\GroupsTrait;

use Junges\ACL\Tests\TestCase;

class SyncPermissionsMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_can_revoke_permissions_that_are_not_in_the_sync_permissions_function_params()
    {
        $this->testUserGroup->assignPermissions(1, 2, 3, 4);

        $this->assertCount(4, $this->testUserGroup->permissions()->get());

        $this->assertTrue($this->testUserGroup->hasPermission(1));
        $this->assertTrue($this->testUserGroup->hasPermission(2));
        $this->assertTrue($this->testUserGroup->hasPermission(3));
        $this->assertTrue($this->testUserGroup->hasPermission(4));

        $this->testUserGroup->syncPermissions(1, 2);

        $this->assertFalse(
            $this->testUserGroup
                ->permissions()
                ->get()
                ->contains('id', 3)
        );
        $this->assertFalse(
            $this->testUserGroup
                ->permissions()
                ->get()
                ->contains('id', 4)
        );
        $this->assertTrue($this->testUserGroup->hasPermission(1));
        $this->assertTrue($this->testUserGroup->hasPermission(2));

        $this->assertCount(2, $this->testUserGroup->permissions()->get());
    }

    public function test_it_can_assign_permissions_which_the_group_does_not_have_previously()
    {
        $this->testUserGroup->assignPermissions(1, 2);

        $this->assertCount(2, $this->testUserGroup->permissions()->get());

        $this->assertTrue($this->testUserGroup->hasPermission(1));
        $this->assertTrue($this->testUserGroup->hasPermission(2));

        $this->testUserGroup->syncPermissions(1, 2, 3, 4);

        $this->assertCount(4, $this->testUserGroup->permissions()->get());
    }

    public function test_it_can_assign_permissions_using_array_as_parameter()
    {
        $this->testUserGroup->assignPermissions([1, 2]);

        $this->assertCount(2, $this->testUserGroup->permissions()->get());

        $this->assertTrue($this->testUserGroup->hasPermission(1));
        $this->assertTrue($this->testUserGroup->hasPermission(2));

        $this->testUserGroup->syncPermissions([1, 2, 3, 4]);

        $this->assertCount(4, $this->testUserGroup->permissions()->get());
    }
}
