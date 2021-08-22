<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\TodoStoreOrUpdateRequest;
use App\Http\Resources\TodoCollectionResource;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Illuminate\Http\JsonResponse;

class TodoController
{
    public function index(): TodoCollectionResource
    {
        return new TodoCollectionResource(Todo::select(['id', 'title', 'description'])->paginate(10));
    }

    public function store(TodoStoreOrUpdateRequest $request): JsonResponse
    {
        $clipboard = auth()->user()->todos()->create($request->validated());

        return (new TodoResource($clipboard))
            ->additional(['meta' => [
                'message' => 'Todo created successfully',
            ]])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Todo $todo): TodoResource
    {
        return new TodoResource($todo);
    }

    public function update(TodoStoreOrUpdateRequest $request, Todo $todo): JsonResponse
    {
        $todo->update($request->validated());

        return (new TodoResource($todo))
            ->additional(['meta' => [
                'message' => 'Todo updated successfully',
            ]])
            ->response()
            ->setStatusCode(200);
    }

    public function destroy(Todo $todo): JsonResponse
    {
        $todo->delete();

        return response()->json([])->setStatusCode(204);
    }
}
