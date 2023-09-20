<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Ads;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    public function Dashboard() {

	$title = "Quầy bán";
	$user = Auth::user();
	//số lg spham tồn kho
	$totalProducts = DB::table('products')
                ->where('seller_id', '=', $user->id)
                ->sum('amount');
	//số lg spham đang đki quảng cáo
	$totalAds = Ads::where('user_id',$user->id)-> count();
	//số lg đơn hàng
	$totalOrders = DB::table('orders')
    		->join('products', 'products.id', '=', 'orders.product_id')
    		->select(DB::raw('COUNT(*) as total_orders'))
    		->where('seller_id', '=', $user->id)
    		->first();
	//số tiền trong tài khoản	
    $accountBalance = User::where('id', $user->id)->value('balance');	
	//tổng doanh thu				
	$revenue = DB::table('orders')
    		->join('products', 'products.id', '=', 'orders.product_id')
    		->select(DB::raw('SUM(orders.price) as revenue_seller'))
    		->where('seller_id', '=', $user->id)
    		->first();
	//list spham bán chạy
	$bestsellerProduct = DB::table('orders')
			->join('products', 'products.id', '=', 'orders.product_id')
			->select(DB::raw('COUNT(*) as total_orders'), 'products.name as product_name', 'orders.status', 'orders.quantity')
			->groupBy('seller_id', 'products.name', 'orders.status', 'orders.quantity')
			->where('seller_id', '=', $user->id)
			->orderBy('total_orders','desc')
			->get();
	//đơn hàng gần đây
	$lastestOrders = DB::table('orders')
			->join('products', 'products.id', '=', 'orders.product_id')
			->join('users', 'users.id', '=', 'orders.customer_id')
			->where('seller_id', '=', $user->id)
			->select( 'products.name AS product_name', 'users.name AS user_name', 'orders.price', 'orders.quantity')
			->orderBy('orders.created_at','desc')
			->get();
    return view('seller.dashboard', compact('totalProducts','totalAds','totalOrders','accountBalance','revenue','bestsellerProduct','lastestOrders'),compact(
		'title'
	));
 }
}