<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {

        // return Product::all()->sortByDesc('id')->first()->id;
        //  return Product::find(7)->withVariants()->where('variant','=', 'red')->first();
        // return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */


    public function store(Request $request)
    {



        DB::beginTransaction();

        try {

            /*
            $validator = Validator::make($request->all(),[
            'title' =>'required|max:255',
            'sku' =>'required|max:100',
            'description' =>'required||max:255',
            'product_image.*' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'product_variant.*'=>'required|string',
            'product_variant_price.*'=>'required|string',
            ]);
     
            if ($validator->fails()) {
                return redirect('/product')
                            ->withErrors($validator);
                            
            }
    
            */

            $product = new Product();
            $product->title = $request->title;
            $product->sku = $request->sku;
            $product->description = $request->description;
            $product->save();

            $product_id = Product::all()->sortByDesc('id')->first()->id;

            /*
         if ($request->hasFile('product_image')) {       
                foreach($request->file('product_image') as $image){
                    $productImage=new ProductImage();
                    $path= $request->$image->store('public');
                    $productImage->product_id=$product_id;
                    $productImage->file_path=$path;
                    $productImage->save();
                }

       
*/
            $variants = $request->product_variant;

            foreach ($variants as $variant) {
                $option = $variant['option'];
                foreach ($variant['tags'] as $tag) {
                    $productVariant = new ProductVariant();
                    $productVariant->variant = $tag;
                    $productVariant->variant_id = $option;
                    $productVariant->product_id = $product_id;
                    $productVariant->save();
                }
            }



            $ProVarPrice = $request->product_variant_prices;

            foreach ($ProVarPrice as $PVC) {
                $productVariantPrice = new ProductVariantPrice();
                $vr = $PVC['title']; //title of the variant. like: red/sm/
                $vari = explode("/", $vr); // array of variants.
                $variant = Product::find($product_id)->withVariants()->where('variant', '=', $vari[0])->first();
                $productVariantPrice->product_variant_one = $variant->id;

                if (count($vari) > 2) {
                    $variant2 = Product::find($product_id)->withVariants()->where('variant', '=', $vari[1])->first();
                    $productVariantPrice->product_variant_two = $variant2->id;
                } else {
                    $productVariantPrice->product_variant_two = NULL;
                }


                if (count($vari) > 3) {
                    $variant3 = Product::find($product_id)->withVariants()->where('variant', '=', $vari[2])->first();
                    $productVariantPrice->product_variant_three = $variant3->id;
                } else {
                    $productVariantPrice->product_variant_three = NULL;
                }

                $productVariantPrice->price = (float)$PVC['price'];
                $productVariantPrice->stock = (int)$PVC['stock'];
                $productVariantPrice->product_id = $product_id;
                $productVariantPrice->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
        }
        return redirect('product/create')->with('status', 'Product Created Successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
