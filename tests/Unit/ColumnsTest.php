<?php

namespace Tests\Unit;

use App\Models\HolidayPlan;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class ColumnsTest extends TestCase
{
    /** @test  */
    public function check_if_columns_is_correct_for_user(): void
    {
        $user = new User();

        $expected = [
            'name',
            'email',
            'password',
        ];

        $arrayCompared = array_diff($expected, $user->getFillable());

        $this->assertEquals(0, count($arrayCompared) );
    }

    /** @test  */
    public function check_if_columns_is_correct_for_holiday_plan(): void
    {
        $holidayPlan = new HolidayPlan();

        $expected = [
            'title',
            'description',
            'date',
            'location',
            'participants'
        ];

        $arrayCompared = array_diff($expected, $holidayPlan->getFillable());

        $this->assertEquals(0, count($arrayCompared) );
    }
}
