<?php
namespace Tests\Unit;

use App\Models\Record;
use App\Repositories\RecordRepository;
use App\Services\RecordService;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Artisan;
use Mockery;
use Tests\TestCase;

class RecordServiceTest extends TestCase
{
    protected $recordRepoMock;
    protected $queuePublisherMock;
    protected $recordService;

    public function setUp(): void
    {
        parent::setUp();

        $this->recordRepoMock = Mockery::mock(RecordRepository::class);
        $this->queuePublisherMock = Mockery::mock('overload:App\Contracts\MessageQueuePublisher');

        $this->recordService = new RecordService($this->recordRepoMock, $this->queuePublisherMock);
    }

    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function testFindRecordByIdThrowsException()
    {
        $this->recordRepoMock->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn(null);

        $this->expectException(ModelNotFoundException::class);

        $this->recordService->findRecordById(1);
    }

    public function testFindRecordById()
    {
        $mockRecord = new Record(['title' => 'Sample Title']);

        $this->recordRepoMock->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn($mockRecord);

        $record = $this->recordService->findRecordById(1);

        $this->assertInstanceOf(Record::class, $record);
        $this->assertEquals('Sample Title', $record->title);
    }

    public function testCreateRecordFailure()
    {
        $data = ['title' => 'Sample Title'];

        $this->recordRepoMock->shouldReceive('create')
            ->once()
            ->with($data)
            ->andThrow(new \Exception());

        $result = $this->recordService->createRecord($data);

        $this->assertFalse($result);
    }

    public function testCreateRecordSuccessfully()
    {
        $data = ['title' => 'Sample Title'];
        $mockRecord = new Record($data);

        $this->recordRepoMock->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($mockRecord);

        $this->queuePublisherMock->shouldReceive('publish')
            ->once()
            ->andReturn(true);

        $result = $this->recordService->createRecord($data);

        $this->assertTrue($result);
    }


    public function testGetAllRecords()
    {
        $mockRecords = collect([new Record(['title' => 'Sample Title'])]);

        $this->recordRepoMock->shouldReceive('all')
            ->once()
            ->andReturn($mockRecords);

        $records = $this->recordService->getAllRecords();

        $this->assertInstanceOf(Collection::class, $records);
        $this->assertCount(1, $records);
    }


    public function testUpdateRecord()
    {
        $recordData = ['title' => 'Updated Title'];
        $recordId = 1;

        $this->recordRepoMock->shouldReceive('update')
            ->with($recordData, $recordId)
            ->once()
            ->andReturn(new Record($recordData));

        $updatedRecord = $this->recordService->updateRecord($recordId, $recordData);

        $this->assertInstanceOf(Record::class, $updatedRecord);
        $this->assertEquals('Updated Title', $updatedRecord->title);
    }

    public function testSaveRecord()
    {
        $record = Mockery::mock(Record::class . '[save]');

        $record->shouldReceive('save')
            ->once()
            ->andReturn(true);

        $result = $this->recordService->saveRecord($record);

        $this->assertTrue($result);
    }

    public function testDeleteRecord()
    {
        $recordId = 1;

        $this->recordRepoMock->shouldReceive('delete')
            ->with($recordId)
            ->once()
            ->andReturn(true);

        $result = $this->recordService->deleteRecord($recordId);

        $this->assertTrue($result);
    }

    public function testFindRecordByIdNotFound()
    {
        $this->recordRepoMock->shouldReceive('find')
            ->andReturn(null);

        $this->expectException(ModelNotFoundException::class);

        $this->recordService->findRecordById(1);
    }
}

