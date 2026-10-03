<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        $connection = getenv('DB_CONNECTION')
            ?: ($_ENV['DB_CONNECTION'] ?? null);

        $database = getenv('DB_DATABASE')
            ?: ($_ENV['DB_DATABASE'] ?? null);

        if (
            $connection !== 'pgsql'
            || $database !== 'digital_goods_store_test'
        ) {
            throw new RuntimeException(
                'TEST SAFETY STOP: PHPUnit may only run against the PostgreSQL database "digital_goods_store_test". '
                . 'Current connection=' . var_export($connection, true)
                . ', database=' . var_export($database, true)
            );
        }

        parent::setUp();
    }
}