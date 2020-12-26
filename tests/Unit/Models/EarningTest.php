<?php

namespace Tests\Unit\Models;

use App\Helper;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Earning;

class EarningTest extends TestCase
{
    public function testFormattedAmount()
    {
        $earning = Earning::factory()->make([
            'amount' => Helper::rawNumberToInteger(39)
        ]);

        $this->assertEquals('39.00', $earning->formatted_amount);
    }
}
