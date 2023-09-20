<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Cart;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
    public function index()
    {
        $title = "Trang chủ";
        $latest_orders = Order::orderBy('created_at')
            ->take(5)
            ->get();

        $high_products = DB::table('products')
            ->where('is_removed', 0)
            ->where('amount', '>=', 0)
            ->where('status', '1')
            ->orderBy('rank_point', 'DESC')
            ->take(5)
            ->get();


        $products = Product::withCount('orders')
            ->where('is_removed', 0)
            ->orderBy('orders_count', 'desc')
            ->where('status', '1')
            ->where('amount', '>', 0)
            ->take(10)
            ->get();

        $categories = Category::where('status', 1)->get();

        $productsOfHighLight = array();
        $highlightCate = Category::where('is_highlight', '1')->get();
        foreach ($highlightCate as $key => $category) {
            $products = Product::where('category_id', $category->id)->orderBy('rank_point', 'DESC')->take(8)->where('is_removed', 0)->get();
            array_push($productsOfHighLight, $products);
        }

        $statics_products = Product::count();
        $statics_categories = Category::count();
        $statics_sellers = User::where('rights', '3')->count();
        $statics_users = User::where('rights', '<', 9)->count();
        $statics_total_orders = Order::count();
        $statics_ads = Ads::count();

        if (auth()->user()) {
            $carts = Cart::where('user_id', auth()->user()->id)->get();
            $total = 0;
            foreach ($carts as $value) {
                $total += $value->product->price * $value->quantity;
            }
        } else {
            $carts = [];
            $total = 0;
        }

        return view('index', compact(
            'title',
            'categories',
            'products',
            'high_products',
            'latest_orders',
            'highlightCate',
            'productsOfHighLight',
            'statics_products',
            'statics_categories',
            'statics_sellers',
            'statics_users',
            'statics_total_orders',
            'statics_ads',
            'carts',
            'total'
        ));
        //list pro theo tên danh mục
    }
}
