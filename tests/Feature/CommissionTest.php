<?php

namespace Tests\Feature;

use App\Http\Controllers\TestController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommissionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $controller = new TestController();
        $test = $controller->__invoke(request()->merge(['test' => true]));
        
        $expectedValues = [
            0.60,
            3.00,
            0.00,
            0.06,
            1.50,
            0,
            0.70,
            0.30,
            0.30,
            3.00,
            0.00,
            0.00,
            8612,
        ];

        foreach($test as $key => $value) {
            $this->assertEquals($expectedValues[$key], $value); 
        }
    }
}
