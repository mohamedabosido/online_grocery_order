<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Traits\ProductRateCompute;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use HttpResponses, ProductRateCompute;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $paginate = 10;
        $products = Product::with('product_details')->with('product_rates')->paginate($paginate);
        foreach ($products as $product) {
            $product->rate = $this->getRate($product);
            $product->save();
            foreach ($product->product_details as $product_detail) {
                $product_detail->image = Storage::url($product_detail->image);
            }
        }
        return $this->success($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $request->validated($request->all());

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'discount_rate' => $request->discount_rate,
            'discount' => $request->discount,
            'category_id' => $request->category_id,
        ]);
        $images = $request->file('images');
        foreach ($images as $image) {
            $image_name =  time() + rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'products/' . $image_name;
            Storage::put($image_path, file_get_contents($image));

            ProductDetail::create([
                'product_id' => $product->id,
                'image' => $image_path
            ]);
        }
        return $this->success($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with('product_details')->find($id);
        $product->rate = $this->getRate($product);
        foreach ($product->product_details as $product_detail) {
            $product_detail->image = Storage::url($product_detail->image);
        }
        return $this->success($product);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::where('id', $id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'discount_rate' => $request->discount_rate,
            'discount' => $request->discount,
            'category_id' => $request->category_id,
        ]);
        return $this->success($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::destroy($id);
        if ($product) {
            return $this->success('', 'Deleted successfully');
        }
        return $this->error('', 'Please check the process', 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $paginate = 10;
        $lower_price = $request->lower_price;
        $higher_price = $request->higher_price;
        $categories = $request->categories;
        $rating = $request->rating;

        foreach (Product::all() as $product) {
            $product->rate = $this->getRate($product);
            $product->save();
            foreach ($product->product_details as $product_detail) {
                $product_detail->image = Storage::url($product_detail->image);
            }
        }

        $products = Product::with(['product_details'])
            ->Where(function ($query) use ($lower_price, $higher_price) {
                $query->where('price', '>=', $lower_price);
                $query->where('price', '<=', $higher_price);
            })
            ->where('rate', '>=', $rating)
            ->whereIn('category_id', $categories)
            ->paginate($paginate);

        foreach ($products as $product) {
            $product->rate = $this->getRate($product);
            $product->save();
            foreach ($product->product_details as $product_detail) {
                $product_detail->image = Storage::url($product_detail->image);
            }
        }
        return $this->success($products);
    }
}
