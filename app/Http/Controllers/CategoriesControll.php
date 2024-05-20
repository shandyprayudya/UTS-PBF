<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Categories;

class CategoriesControll extends Controller
{
    //
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $payload = $validator->validated();

        Categories::create([
            'name'=> $payload['name']
        ]);

        return response()->json([
            'msg' => 'Categories successfully created'
        ]);
    }
    
    function showAll() {
        $Categories = Categories::all();

        return response()->json([
            'msg' => 'All categories data',
            'data' => $Categories
        ]);
    }

    public function update(Request $request, $id){
        try {
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:255',
        
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $payload = $validator->validated();;
        $categories = Categories::findOrFail($id);

        Categories::where('id', $id)->update([
            'name' => $payload['name'],
        
        ]);
        
        return response()->json([
            'msg' => 'Categories data is saved succesfully'
        ]);
        } catch (\Exception $e) {
            return response()->json([
                'msg'=> $e->getMessage()
                ]);
        }
    }
    public function delete($id){
        
        $product = Categories::where('id', $id)->first();
    
       
        if($product){
            Categories::where('id', $id)->delete();
    
            return response()->json([
                'msg'=> 'Data categories with ID: '.$id.' has succesfully deleted'
            ], 200);
        } else {
            
            return response()->json([
                'msg'=> 'Categories with ID: '.$id.' not found'
            ], 404);
        }
    }
}