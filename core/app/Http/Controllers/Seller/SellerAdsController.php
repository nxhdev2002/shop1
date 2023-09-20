<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Ads;
use App\Models\ProductStatistic;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SellerAdsController extends Controller
{
    public function index(){
        $title = "Quảng cáo";
        $user = Auth::user();
        $ads = Ads::where('user_id',$user->id)->get();
        return view('seller.frontend.ads.index', compact('ads','title'));
    }

    public function show($id){
        $title = "Chi tiết quảng cáo";
        $ad = Ads::find($id);
        if (!$ad) {
            return redirect()->back()->withErrors(['message' => 'Ads không tồn tại']);
        }

        return view('seller.frontend.ads.detail', compact('ad','title'));
    }

    public function update($id){
        $ad = Ads::find($id);
        if (!$ad) {
            return redirect()->back()->withErrors(['message' => 'Ads không tồn tại']);
        }

        $product = $ad->products;
        $product->is_ads = 0;
        $product->save();
        return redirect()->route('seller.ads.index')->with('success', 'Xoá thành công.');
    }

    public function statistic($id)
    {
        $ads = Ads::find($id);
        $product = $ads->products;
        $statistic = ProductStatistic::where('product_id', $product->id)->take(5)->orderBy('created_at')->get();

        $endDate = Carbon::now(); // Ngày kết thúc, là ngày hiện tại
        $startDate = now()->subDays(3)->toDateString(); // Ngày bắt đầu, là ngày 3 ngày trước

        $orders = $product->orders->whereBetween('created_at', [$startDate, $endDate])
            ->pluck('created_at')
            ->map(function ($date) {
                return $date->format('d/m');
            })
            ->countBy()
            ->toArray();
        $result = [];
        foreach ($orders as $date => $count) {
            $result[$date] = $count;
        }

        return response()->json([
            'success' => true,
            'message' => 'Lấy thông tin thành công',
            'data' => [
                'stat' => $statistic,
                'order' => $result
            ]
        ]);
    }
}
