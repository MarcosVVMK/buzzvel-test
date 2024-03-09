<?php

namespace Tests\Unit;

use App\Http\Resources\V1\HolidayPlanResource;
use App\Models\HolidayPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HolidayPlansTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_all_holiday_plans()
    {
        $holidayPlans = HolidayPlan::factory(3)->create();

        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('secret')
        ]);

        $token = $user->createToken('Test Token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('api/V1/holiday-plans');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'title',
                    'description',
                    'date',
                    'location',
                    'participants'
                ]
            ]
        ]);

        foreach ($holidayPlans as $holidayPlan) {
            $response->assertJsonFragment(
                (new HolidayPlanResource($holidayPlan))->toArray($this->app['request'])
            );
        }
    }

    /** @test */
    public function it_can_create_a_new_holiday_plan()
    {
        $holidayPlanData = [
            'title' => 'Example Holiday Plan',
            'description' => 'Description for holiday plan',
            'date' => '2024-03-08',
            'location' => 'Location of holiday plan',
            'participants' => 'Name of participants'
        ];

        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('secret')
        ]);

        $token = $user->createToken('Test Token')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson('api/V1/holiday-plans', $holidayPlanData);

        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Holiday Plan created!'
        ]);

        $response->assertJsonStructure([
            'message',
            'data' => [
                'title',
                'description',
                'date',
                'location',
                'participants'
            ]
        ]);

        $response->assertJsonFragment([
            'title' => $holidayPlanData['title'],
            'description' => $holidayPlanData['description'],
            'date' => $holidayPlanData['date'],
            'location' => $holidayPlanData['location'],
            'participants' => $holidayPlanData['participants']
        ]);

        $this->assertDatabaseHas('holiday_plans', $holidayPlanData);
    }

    /** @test */
    public function it_can_show_a_specific_holiday_plan()
    {
        $holidayPlan = HolidayPlan::factory()->create();

        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('secret')
        ]);

        $token = $user->createToken('Test Token')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->getJson('api/V1/holiday-plans/' . $holidayPlan->id);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'title' => $holidayPlan->title,
                'description' => $holidayPlan->description,
                'date' => $holidayPlan->date,
                'location' => $holidayPlan->location,
                'participants' => $holidayPlan->participants
            ]
        ]);
    }

    /** @test */
    public function it_can_update_a_holiday_plan()
    {
        $holidayPlan = HolidayPlan::factory()->create();

        $newData = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'date' => '2024-12-31',
            'location' => 'Updated Location',
            'participants' => 'Updated Participants'
        ];

        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('secret')
        ]);

        $token = $user->createToken('Test Token')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->putJson('api/V1/holiday-plans/' . $holidayPlan->id, $newData);

        $response->assertStatus(200);

        $response->assertJson([
                'data' => [
                    'title' => $newData['title'],
                    'description' => $newData['description'],
                    'date' => $newData['date'],
                    'location' => $newData['location'],
                    'participants' => $newData['participants']
                ]
            ]
        );

        $this->assertDatabaseHas('holiday_plans', $newData);
    }

    /** @test */
    public function it_can_delete_a_holiday_plan()
    {
        $holidayPlan = HolidayPlan::factory()->create();

        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('secret')
        ]);

        $token = $user->createToken('Test Token')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->deleteJson('api/V1/holiday-plans/' . $holidayPlan->id);

        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Holiday Plan deleted!'
        ]);

        $this->assertDatabaseMissing('holiday_plans', ['id' => $holidayPlan->id]);
    }

    /** @test */
    public function it_returns_error_when_attempting_to_delete_non_existing_holiday_plan()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('secret')
        ]);

        $token = $user->createToken('Test Token')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->deleteJson('api/V1/holiday-plans/6565');

        $response->assertStatus(400);

        $response->assertJson([
            'message' => 'Holiday Plan not deleted!'
        ]);
    }

    /** @test */
    public function it_can_download_a_holiday_plan_pdf()
    {
        $holidayPlan = HolidayPlan::factory()->create();

        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('secret')
        ]);

        $token = $user->createToken('Test Token')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->get('api/V1/download-pdf/' . $holidayPlan->id );

        $response->assertStatus(200);

        $response->assertHeader('Content-Type', 'application/pdf');

        $this->assertNotEmpty($response->getContent());

        $expectedFilename = 'attachment; filename="' . $holidayPlan->title.'"';
        $this->assertEquals($expectedFilename, $response->headers->get('Content-Disposition'));
    }
}
