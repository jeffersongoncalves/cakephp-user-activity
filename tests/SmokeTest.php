<?php

use PHPUnit\Framework\TestCase;

/**
 * Every class the plugin ships autoloads (catches syntax errors, bad namespaces and missing dependencies).
 */
class SmokeTest extends TestCase
{
    /**
     * @dataProvider classes
     */
    public function testClassLoads(string $class): void
    {
        $this->assertTrue(class_exists($class) || interface_exists($class) || trait_exists($class), $class);
    }

    public function classes(): array
    {
        return [
            ['JeffersonSimaoGoncalves\\UserActivity\\Model\\Behavior\\UserActivityBehavior'],
            ['JeffersonSimaoGoncalves\\UserActivity\\Model\\Entity\\Log'],
            ['JeffersonSimaoGoncalves\\UserActivity\\Model\\Entity\\LogsDetail'],
            ['JeffersonSimaoGoncalves\\UserActivity\\Model\\Table\\LogsDetailsTable'],
            ['JeffersonSimaoGoncalves\\UserActivity\\Model\\Table\\LogsTable'],
            ['JeffersonSimaoGoncalves\\UserActivity\\Plugin'],
        ];
    }
}
