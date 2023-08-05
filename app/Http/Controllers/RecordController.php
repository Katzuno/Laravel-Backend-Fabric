<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRecordRequest;
use App\Http\Requests\UpdateRecordRequest;
use App\Services\RecordService;
use App\DTOs\RecordDTO;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

/**
 * @OA\Info(title="Records API", version="0.1")
 * @OA\Server(url="http://localhost/api")
 */
class RecordController
{
    private RecordService $recordService;

    public function __construct(RecordService $recordService)
    {
        $this->recordService = $recordService;
    }

    /**
     * @OA\Get(
     *     path="/records",
     *     summary="Retrieve all records",
     *     operationId="getAllRecords",
     *     @OA\Response(
     *         response=200,
     *         description="List of records",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Record"))
     *     ),
     *     tags={"Records"}
     * )
     */
    public function index(): JsonResponse
    {
        $records = $this->recordService->getAllRecords();
        return response()->json($records, ResponseAlias::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/records",
     *     summary="Create a new record",
     *     operationId="createRecord",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Record")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Record created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Record")
     *     ),
     *     tags={"Records"}
     * )
     */
    public function store(CreateRecordRequest $request): JsonResponse
    {
        $dto = new RecordDTO($request->validated());

        $record = $this->recordService->createRecord(get_object_vars($dto));
        return response()->json($record, ResponseAlias::HTTP_CREATED);
    }


    /**
     * @OA\Get(
     *     path="/records/{id}",
     *     summary="Retrieve a single record",
     *     operationId="getRecord",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Record retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Record")
     *     ),
     *     tags={"Records"}
     * )
     */
    public function show(int $id): JsonResponse
    {
        $record = $this->recordService->findRecordById($id);
        return response()->json($record, ResponseAlias::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/records/{id}",
     *     summary="Update a record",
     *     operationId="updateRecord",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Record")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Record updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Record")
     *     ),
     *     tags={"Records"}
     * )
     */
    public function update(UpdateRecordRequest $request, int $id): JsonResponse
    {
        $dto = new RecordDTO($request->validated());
        $record = $this->recordService->updateRecord($id, get_object_vars($dto));
        return response()->json($record, ResponseAlias::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/records/{id}",
     *     summary="Delete a record",
     *     operationId="deleteRecord",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Record deleted successfully"
     *     ),
     *     tags={"Records"}
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $this->recordService->deleteRecord($id);
        return response()->json(null, ResponseAlias::HTTP_NO_CONTENT);
    }
}
