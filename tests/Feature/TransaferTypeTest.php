<?php

namespace Tests\Feature;

use App\Models\TransferType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransaferTypeTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_transfer_types()
    {
        $this->withoutMiddleware();

        TransferType::factory()->count(5)->create();

        $transferTypes = TransferType::all();

        $response = $this->get('/api/protected/transfer-types');

        $response->assertStatus(200);
        $this->assertEquals(5, count($transferTypes));
    }
}
