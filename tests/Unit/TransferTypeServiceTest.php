<?php

namespace Tests\Unit;

use App\Models\TransferType;
use App\Services\Transaction\TransferTypeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransferTypeServiceTest extends TestCase
{
    use RefreshDatabase;

    private TransferTypeService $transferTypeService;

    public function setUp(): void
    {
        parent::setUp();
        $this->transferTypeService = new TransferTypeService();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_getTransferTypes_should_return_all_available_type()
    {
        $response = $this->transferTypeService->getTransferTypes();

        $this->assertCount(count($response), TransferType::all());
    }
}
