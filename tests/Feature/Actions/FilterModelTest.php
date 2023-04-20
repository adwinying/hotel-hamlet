<?php

namespace Tests\Feature\Actions;

use App\Actions\FilterModel;
use App\Models\User;
use Tests\TestCase;

class FilterModelTest extends TestCase
{
    public function testFilterWithNoParams(): void
    {
        $query = User::query();

        $expectedSql = 'select * from `users` where `users`.`deleted_at` is null';

        $filterModel = new FilterModel();
        $result      = $filterModel->execute($query);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEmpty($result->getBindings());
    }

    public function testFilterWhenParamNull(): void
    {
        $query  = User::query();
        $params = ['users_name' => null];

        $expectedSql = 'select * from `users` where `users`.`deleted_at` is null';

        $filterModel = new FilterModel();
        $result      = $filterModel->execute($query, $params);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEmpty($result->getBindings());
    }

    public function testFilterWhenParamFalse(): void
    {
        $query  = User::query();
        $params = ['name' => false];

        $expectedSql      = 'select * from `users` where `users`.`name` like ? and `users`.`deleted_at` is null';
        $expectedBindings = [$params['name']];

        $filterModel = new FilterModel();
        $result      = $filterModel->execute($query, $params);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }

    public function testFilterWhenParamZero(): void
    {
        $query  = User::query();
        $params = ['name' => 0];

        $expectedSql      = 'select * from `users` where `users`.`name` like ? and `users`.`deleted_at` is null';
        $expectedBindings = [$params['name']];

        $filterModel = new FilterModel();
        $result      = $filterModel->execute($query, $params);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }

    public function testFilterWhenParamStringNull(): void
    {
        $query  = User::query();
        $params = ['name' => 'null'];

        $expectedSql      = 'select * from `users` where `users`.`name` is null and `users`.`deleted_at` is null';
        $expectedBindings = [];

        $filterModel = new FilterModel();
        $result      = $filterModel->execute($query, $params);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }

    public function testFilterLikeParams(): void
    {
        $query  = User::query();
        $params = [
            'name'  => 'foo',
            'email' => 'foo@bar.com',
        ];

        $expectedSql      = 'select * from `users` where `users`.`name` like ? and `users`.`email` like ? and `users`.`deleted_at` is null';
        $expectedBindings = [
            "%{$params['name']}%",
            "%{$params['email']}%",
        ];

        $filterModel = new FilterModel();
        $result      = $filterModel->execute($query, $params);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }

    public function testFilterExactParams(): void
    {
        $query  = User::query();
        $params = [
            'name'  => 'foo',
            'email' => 'foo@bar.com',
        ];
        $exactKeys = ['name', 'email'];

        $expectedSql      = 'select * from `users` where `users`.`name` = ? and `users`.`email` = ? and `users`.`deleted_at` is null';
        $expectedBindings = [
            $params['name'],
            $params['email'],
        ];

        $filterModel = new FilterModel();
        $result      = $filterModel->execute($query, $params, $exactKeys);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }

    public function testFilterWithMultipleParams(): void
    {
        $query  = User::query();
        $params = [
            'name'  => 'foo',
            'email' => 'foo@bar.com',
        ];
        $exactKeys = ['name'];

        $expectedSql      = 'select * from `users` where `users`.`email` like ? and `users`.`name` = ? and `users`.`deleted_at` is null';
        $expectedBindings = [
            "%{$params['email']}%",
            $params['name'],
        ];

        $filterModel = new FilterModel();
        $result      = $filterModel->execute($query, $params, $exactKeys);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }
}
