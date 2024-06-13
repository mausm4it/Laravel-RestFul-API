<?php

namespace App\Http\Controllers\api\V1;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->get();
        
        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'All Category Show',
            'data' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      

        $data = Validator::make($request->all(),[
             'name' => 'required|string|unique:categories',
        ]);

        if($data->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Error',
                'errors' => $data->getMessageBag()
            ], 422);
        }

        $form_data = $data->validated();
        $form_data['slug'] = Str::slug($form_data['name']);

       $category =  Category::create($form_data);


        
        
        return response()->json([
                'success' => true,
                'message' => 'Category Successfully Created',
                'data' => $category
            ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $category = Category::find($id);
         if(!$category){
            return response()->json([
                'success' => false,
                'message' => 'Category Not Found',
                'errors' => []
                
            ], 404);
         }
         return response()->json([
                'success' => true,
                'message' => 'SuccessFull',
                'data' => $category
                
            ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
         $category = Category::find($id);
        
         if(!$category){
            return response()->json([
                'success' => false,
                'message' => 'Category Not Found',
                'errors' => []
                
            ], 404);
         }
        
        $data = Validator::make($request->all(),[
             'name' => 'required|string|unique:categories,name,' .$category->id,
        ]);

        if($data->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Error',
                'errors' => $data->getMessageBag()
            ], 422);
        }

        $form_data = $data->validated();
        $form_data['slug'] = Str::slug($form_data['name']);

      $category->update($form_data);


        
        
        return response()->json([
                'success' => true,
                'message' => 'Category Successfully Updated',
                'data' => []
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        
         if(!$category){
            return response()->json([
                'success' => false,
                'message' => 'Category Not Found',
                'errors' => []
                
            ], 404);
         }

         $category->delete();
         return response()->json([
                'success' => true,
                'message' => 'Category Successfully Deleted',
                'data' => []
            ], 200);
    }
}