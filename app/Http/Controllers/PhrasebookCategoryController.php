<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use App\Models\PhrasebookCategory as Category;
use App\Http\Resources\PhrasebookCategory as CategoryResource;
use App\Http\Requests\StorePhrasebookCategory as CategoryRequest;
use App\Http\Resources\PhrasebookCategoryCollection as CategoryCollection;

class PhrasebookCategoryController extends Controller {

    public function __construct() {
        $this->middleware('auth:sanctum')->except([
            'index', 'show'
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return new CategoryCollection(Category::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request) {
        $category = new Category;
        $category->title = $request->title;
        $category->slug = Str::slug($request->title, '-');
        $category->save();

        return $this->responseWithStatus(
            true,
            'Category created',
            JsonResponse::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  String  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug) {
        return new CategoryResource(
            Category::where('slug', $slug)->firstOrFail()
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CategoryRequest  $request
     * @param  String  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $slug) {
        $category = Category::where('slug', $slug)->firstOrFail();
        $category->title = $request->title;
        $category->slug = Str::slug($request->title);
        $category->save();
        return $this->responseWithStatus(
            true,
            'Category updated!',
            JsonResponse::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug) {
        $category = Category::where('slug', $slug)->firstOrFail();
        $category->delete();
        return $this->responseWithStatus(
            true,
            'Category deleted',
            JsonResponse::HTTP_OK
            // JsonResponse::HTTP_NO_CONTENT
        );
    }

    private function responseWithStatus($status, $message, $code) {
        return response()->json([
            'success'   => $status,
            'message'   => $message,
        ], $code);
    }
}
