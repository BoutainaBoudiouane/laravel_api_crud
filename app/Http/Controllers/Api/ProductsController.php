<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductsResource;

class ProductsController extends Controller
{
    //
    public function index()
    {
        $products = Product::get();
        if($products->count() > 0){
            return ProductsResource::collection($products);
        }
        return response()->json(['message' => 'No products found'], 200);
    }

    //
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
        ]);
        $product=Product::create(
            [
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price]
        );
        return response()->json(
            ['message' => 'Product created successfully',
                   'data' => new ProductsResource($product)], 201);
    }

    //
    public function show(Product $product)
    {
        return new ProductsResource($product);
    }

    //
    public function update(Request $request,Product $product)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric',
        ]);

        $product->update($validated);

        return  response()->json(
            ['message' => 'Product updated successfully',
            'data' => new ProductsResource($product)]);
    }

    //
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
