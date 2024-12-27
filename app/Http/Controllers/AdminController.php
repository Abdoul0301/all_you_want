<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index()
    {
        $orders = Order::orderby('created_at','DESC')->take(10)->get();

        $dashboardDatas = DB::select("
        SELECT
            SUM(total) AS TotalAmount,
            SUM(IF(status='commander', total, 0)) AS TotalcommanderAmount,
            SUM(IF(status='livrer', total, 0)) AS TotallivrerAmount,
            SUM(IF(status='annuler', total, 0)) AS TotalannulerAmount,
            COUNT(*) AS Total,
            SUM(IF(status='commander', 1, 0)) AS Totalcommander,
            SUM(IF(status='livrer', 1, 0)) AS Totallivrer,
            SUM(IF(status='annuler', 1, 0)) AS Totalannuler
        FROM orders
    ");

        /*$monthlyDatas = DB::select("
        SELECT
            M.id AS MonthNo,
            M.name AS MonthName,
            IFNULL(O.TotalAmount, 0) AS TotalAmount,
            IFNULL(O.TotalcommanderAmount, 0) AS TotalcommanderAmount,
            IFNULL(O.TotallivrerAmount, 0) AS TotallivrerAmount,
            IFNULL(O.TotalannulerAmount, 0) AS TotalannulerAmount
        FROM month_names M
        LEFT JOIN (
            SELECT
                DATE_FORMAT(created_at, '%m') AS MonthNo,
                SUM(total) AS TotalAmount,
                SUM(IF(status = 'commander', total, 0)) AS TotalcommanderAmount,
                SUM(IF(status = 'livrer', total, 0)) AS TotallivrerAmount,
                SUM(IF(status = 'annuler', total, 0)) AS TotalannulerAmount
            FROM orders
            WHERE YEAR(created_at) = YEAR(NOW())
            GROUP BY YEAR(created_at), MONTH(created_at)
        ) O ON O.MonthNo = M.id
        ORDER BY M.id;

    ");

        $AmountM = implode(',', collect($monthlyDatas)->pluck('TotalAmount')->toArray());
        $OrderedAmountM = implode(',', collect($monthlyDatas)->pluck('TotalcommanderAmount')->toArray());
        $DeliveredAmountM = implode(',', collect($monthlyDatas)->pluck('TotallivrerAmount')->toArray());
        $CanceledAmountM = implode(',', collect($monthlyDatas)->pluck('TotalannulerAmount')->toArray());

        $TotalAmount = collect($monthlyDatas)->sum('TotalAmount');
        $TotalOrderedAmount = collect($monthlyDatas)->sum('TotalcommanderAmount');
        $TotalDeliveredAmount = collect($monthlyDatas)->sum('TotallivrerAmount');
        $TotalCanceledAmount = collect($monthlyDatas)->sum('TotalannulerAmount');

        return view("admin.index", compact(
            'orders', 'dashboardDatas',
            'AmountM', 'OrderedAmountM',
            'DeliveredAmountM', 'CanceledAmountM',
            'TotalAmount', 'TotalOrderedAmount',
            'TotalDeliveredAmount', 'TotalCanceledAmount'
        ));*/
        return view("admin.index", compact(
            'orders', 'dashboardDatas'
        ));
    }

//-----------BRAND-------------------------------------------------------------------

    public function brands()
    {
        $brands = Brand::orderBy('id','DESC')->paginate(10);
        return view("admin.brands",compact('brands'));
    }

    public function add_brand()
    {
        return view("admin.brand-add");
    }

    public function brand_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $image = $request->file('image');
        $file_extention = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extention;
        $this->GenerateBrandThumbailImage($image,$file_name);
        $brand->image = $file_name;
        $brand->save();
        return redirect()->route('admin.brands')->with('status','Record has been added successfully !');
    }

    public function brand_edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brand-edit',compact('brand'));
    }

    public function GenerateBrandThumbailImage($image,$imageName)
    {
        $destinationPath = public_path('uploads/brands');
        $img = Image::read($image->path());
        $img->cover(124,124,"top");
        $img->resize(124,124,function ($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }

    public function brand_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$request->id,
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);
        $brand = Brand::find($request->id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        if($request->hasFile('image'))
        {
            if (File::exists(public_path('uploads/brands').'/'.$brand->image))
            {
                File::delete(public_path('uploads/brands').'/'.$brand->image);
            }
            $image = $request->file('image');
            $file_extention = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extention;
            $this->GenerateBrandThumbailImage($image,$file_name);
            $brand->image = $file_name;
        }


        $brand->save();
        return redirect()->route('admin.brands')->with('status','Record has been updated successfully !');
    }

    public function brand_delete($id)
    {
        $brand = Brand::find($id);
        if (File::exists(public_path('uploads/brands').'/'.$brand->image))
            {
                File::delete(public_path('uploads/brands').'/'.$brand->image);
            }
        $brand->delete();
        return redirect()->route('admin.brands')->with('status','Record has been deleted successfully !');
    }

//-----------CATEGORIES-------------------------------------------------------------------

    public function categories()
    {
        $categories = Category::orderBy('id','DESC')->paginate(10);
        return view("admin.categories",compact('categories'));
    }

    public function category_add()
    {
        return view("admin.category-add");
    }

    public function category_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $image = $request->file('image');
        $file_extention = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extention;
        $this->GenerateCategoryThumbailImage($image,$file_name);
        $category->image = $file_name;
        $category->save();
        return redirect()->route('admin.categories')->with('status','Category has been added successfully !');
    }

    public function GenerateCategoryThumbailImage($image,$imageName)
    {
        $destinationPath = public_path('uploads/categories');
        $img = Image::read($image->path());
        $img->cover(124,124,"top");
        $img->resize(124,124,function ($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }

    public function category_edit($id)
    {
        $category = Category::find($id);
        return view('admin.category-edit',compact('category'));
    }

    public function category_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$request->id,
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->slug = $request->slug;
        if($request->hasFile('image'))
        {
            if (File::exists(public_path('uploads/categories').'/'.$category->image)) {
                File::delete(public_path('uploads/categories').'/'.$category->image);
            }
            $image = $request->file('image');
            $file_extention = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extention;
            $this->GenerateCategoryThumbailImage($image,$file_name);
            $category->image = $file_name;
        }
        $category->save();
        return redirect()->route('admin.categories')->with('status','Record has been updated successfully !');
    }

    public function category_delete($id)
    {
        $category = Category::find($id);
        if (File::exists(public_path('uploads/categories').'/'.$category->image)) {
            File::delete(public_path('uploads/categories').'/'.$category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories')->with('status','Record has been deleted successfully !');
    }

//-----------PRODUCT-------------------------------------------------------------------

    public function users()
    {
        $users = User::OrderBy('created_at','DESC')->paginate(10);
        return view("admin.users",compact('users'));
    }

//-----------PRODUCT-------------------------------------------------------------------

    public function products()
    {
        $products = Product::OrderBy('created_at','DESC')->paginate(10);
        return view("admin.products",compact('products'));
    }

    public function product_add()
    {
        $categories = Category::Select('id','name')->orderBy('name')->get();
        $brands = Brand::Select('id','name')->orderBy('name')->get();

        return view("admin.product-add",compact('categories','brands'));
    }

    public function product_store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'slug'=>'required|unique:products,slug',
            'category_id'=>'required',
            'brand_id'=>'required',
            'short_description'=>'required',
            'description'=>'required',
            'regular_price'=>'required',
            'sale_price'=>'required',
            'SKU'=>'required',
            'stock_status'=>'required',
            'featured'=>'required',
            'quantity'=>'required',
            'image'=>'required|mimes:png,jpg,jpeg|max:2048'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $current_timestamp = Carbon::now()->timestamp;

        if($request->hasFile('image'))
        {
            $image = $request->file('image');
            $imageName = $current_timestamp.'.'.$image->extension();

            $this->GenerateProductThumbnailImage($image,$imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if($request->hasFile('images'))
        {
            $allowedfileExtension=['jpg','png','jpeg'];
            $files = $request->file('images');
            foreach($files as $file)
            {
                $gextension = $file->getClientOriginalExtension();
                $check=in_array($gextension,$allowedfileExtension);
                if($check)
                {
                    $gfilename = $current_timestamp . "-" . $counter . "." . $gextension;
                    $this->GenerateProductThumbnailImage($file,$gfilename);
                    array_push($gallery_arr,$gfilename);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',', $gallery_arr);
        }
        $product->images = $gallery_images;
        $product->save();
        return redirect()->route('admin.products')->with('status','Record has been added successfully !');
    }

    public function GenerateProductthumbnailImage($image,$imageName)
    {
        $destinationPaththumbnail = public_path('uploads/products/thumbnails');
        $destinationPath = public_path('uploads/products');
        $img = Image::read($image->path());

        $img->cover(540,689,"top");
        $img->resize(540,689,function ($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);

        $img->resize(104,104,function ($constraint){
            $constraint->aspectRatio();
        })->save($destinationPaththumbnail.'/'.$imageName);
    }

    public function product_edit($id)
    {
        $product = Product::find($id);
        $categories = Category::Select('id','name')->orderBy('name')->get();
        $brands = Brand::Select('id','name')->orderBy('name')->get();
        return view('admin.product-edit',compact('product','categories','brands'));
    }

    public function product_update(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'slug'=>'required|unique:products,slug,'.$request->id,
            'category_id'=>'required',
            'brand_id'=>'required',
            'short_description'=>'required',
            'description'=>'required',
            'regular_price'=>'required',
            'sale_price'=>'required',
            'SKU'=>'required',
            'stock_status'=>'required',
            'featured'=>'required',
            'quantity'=>'required',
            'image'=>'mimes:png,jpg,jpeg|max:2048'
        ]);

        $product = Product::find($request->id);
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $current_timestamp = Carbon::now()->timestamp;

        if($request->hasFile('image'))
        {
            if (File::exists(public_path('uploads/products').'/'.$product->image))
            {
                File::delete(public_path('uploads/products').'/'.$product->image);
            }
            if (File::exists(public_path('uploads/products/thumbnails').'/'.$product->image))
            {
                File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
            }
            $image = $request->file('image');
            $imageName = $current_timestamp . "." . $image->extension();
            $this->GenerateProductThumbnailImage($image,$imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if($request->hasFile('images'))
        {
            foreach(explode(',',$product->images) as $ofile)
            {
                if (File::exists(public_path('uploads/products').'/'.$ofile))
                {
                    File::delete(public_path('uploads/products').'/'.$ofile);
                }
                if (File::exists(public_path('uploads/products/thumbnails').'/'.$ofile))
                {
                    File::delete(public_path('uploads/products/thumbnails').'/'.$ofile);
                }
            }
            $allowedfileExtension=['jpg','png','jpeg'];
            $files = $request->file('images');
            foreach($files as $file){
                $gextension = $file->getClientOriginalExtension();
                $gcheck = in_array($gextension,$allowedfileExtension);
                if($gcheck)
                {
                    $gfilename = $current_timestamp . "-" . $counter . "." . $gextension;
                    $this->GenerateProductThumbnailImage($file,$gfilename);
                    array_push($gallery_arr,$gfilename);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(', ', $gallery_arr);
            $product->images = $gallery_images;
        }


        $product->save();
        return redirect()->route('admin.products')->with('status','Record has been updated successfully !');
    }

    public function product_delete($id)
    {
        $product = Product::find($id);
        if (File::exists(public_path('uploads/products').'/'.$product->image))
        {
            File::delete(public_path('uploads/products').'/'.$product->image);
        }
        if (File::exists(public_path('uploads/products/thumbnails').'/'.$product->image))
        {
            File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
        }

        foreach(explode(',',$product->images) as $ofile)
        {
            if (File::exists(public_path('uploads/products').'/'.$ofile))
            {
                File::delete(public_path('uploads/products').'/'.$ofile);
            }
            if (File::exists(public_path('uploads/products/thumbnails').'/'.$ofile))
            {
                File::delete(public_path('uploads/products/thumbnails').'/'.$ofile);
            }
        }

        $product->delete();
        return redirect()->route('admin.products')->with('status','Record has been deleted successfully !');
    }

    public function orders()
    {
        $orders = Order::orderBy('created_at','DESC')->paginate(12);
        return view("admin.orders",compact('orders'));
    }

    public function order_details($order_id){
        $order = Order::find($order_id);
        $orderitems = OrderItem::where('order_id',$order_id)->orderBy('id')->paginate(12);
        $transaction = Transaction::where('order_id',$order_id)->first();
        return view("admin.order-details",compact('order','orderitems','transaction'));
    }

    public function update_order_status(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->status = $request->order_status;

        if($request->order_status=='livrer')
        {
            $order->delivered_date = Carbon::now();
        }
        else if($request->order_status=='annuler')
        {
            $order->canceled_date = Carbon::now();
        }
        $order->save();

        if($request->order_status=='livrer')
        {
            $transaction = Transaction::where('order_id',$request->order_id)->first();
            if ($transaction) {
                $transaction->status = "approuver";
                $transaction->save();
            }
        }
        return back()->with("status", "Le statut de la commande a été mis à jour avec succès !");
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $results = Product::where('name', 'LIKE', "%{$query}%")->get()->take(8);
        return response()->json($results);
    }
}
