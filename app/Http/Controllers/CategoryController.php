<?php

namespace App\Http\Controllers;

use App\Exceptions\AlreadyExistsException;
use App\Exceptions\NotFoundException;
use App\Http\Resources\Category\CategoryResource;
use App\Services\Category\DestroyCategory;
use App\Services\Category\IndexCategory;
use App\Services\Category\StoreCategory;
use App\Services\Category\UpdateCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\ValidationException;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|AnonymousResourceCollection
    {
        try {
            $data = app(IndexCategory::class)->execute($request->all());
            return CategoryResource::collection($data);
        }catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = app(StoreCategory::class)->execute($request->all());
            return new CategoryResource($data);
        } catch (AlreadyExistsException $e) {
            return $e->render();
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = app(UpdateCategory::class)->execute([
                'name' => $request->name,
                'id' => $id,
            ]);
            return new CategoryResource($data);
        } catch (AlreadyExistsException $e) {
            return $e->render();
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            app(DestroyCategory::class)->execute([
                'id' => $id,
            ]);
            return $this->respondSuccess();
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        } catch (NotFoundException $e) {
            return $e->render();
        }
    }
}
