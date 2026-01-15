<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class FinancialCalculationTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_balance_calculation_is_correct(): void
    {
        $revenue = 5000;
        $expense = 2000;
        $expectedBalance = 3000;

        $this->assertEquals($expectedBalance, $revenue - $expense);
    }
}
