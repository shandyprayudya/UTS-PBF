<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Categories;


class ProductControll extends Controller
{
    //
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'required|mimes:png,jpg,jpeg|max:2048',
            'category_id' => 'required',
            'expired_at' => 'required|date',
            'modified_by' => 'required|max:255'
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $thumbnail = $request->file('image');
        $filename = now()->timestamp."-".$request->image->getClientOriginalName();
        $thumbnail->move('uploads', $filename);

        $payload = $validator->validated();

        $category = Categories::where('name', $payload['category_id'])->first();

        if (!$category) {
            return response()->json(['error' => 'Category not found'])->setStatusCode(404);
        }

        Product::create([
            'name' => $payload['name'],
            'description' => $payload['description'],
            'price' => $payload['price'],
            'image' => 'uploads/'.$filename,
            'category_id' => $category->id,
            'expired_at' => $payload['expired_at'],
            'modified_by' => $payload['modified_by']
        ]);

        return response()->json([
            'msg' => 'Products successfully created'
        ]);
    }

    function showAll(){
        $products = Product::all();

        return response()->json([
            'msg' => 'All data product',
            'data' => $products
        ]);
    }

    public function update(Request $request, $id){
        try {
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'image' => 'required|mimes:png,jpg,jpeg|max:2048',
            'category_id' => 'required',
            'expired_at' => 'required|date',
            'modified_by' => 'required|email',
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $payload = $validator->validated();
        $category = Categories::where('name', $payload['category_id'])->first();
        $thumbnail = $request->file('image');
        $filename = now()->timestamp."-".$request->image->getClientOriginalName();
        $thumbnail->move('uploads', $filename);
        $product = Product::findOrFail($id);

        Product::where('id', $id)->update([
            'name' => $payload['name'],
            'description' => $payload['description'],
            'price' => $payload['price'],
            'image' => 'uploads/' . $filename,
            'category_id' => $category->id,
            'expired_at' => $payload['expired_at'],
            'modified_by' => $payload['modified_by']
        ]);
        
        return response()->json([
            'msg' => 'Product data is saved succesfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'msg'=> $e->getMessage()
            ]);
    }

    
    }

    public function delete($id){
        // Cari produk berdasarkan ID
        $product = Product::where('id', $id)->first();
    
        // Jika produk ditemukan, hapus produk tersebut
        if($product){
            Product::where('id', $id)->delete();
    
            return response()->json([
                'msg'=> 'Product data with ID: '.$id.' is deleted succesfully'
            ], 200);
        } else {
            // Jika produk tidak ditemukan, kembalikan respons error
            return response()->json([
                'msg'=> 'Product with ID: '.$id.' is not found'
            ], 404);
        }
    }
    
}

