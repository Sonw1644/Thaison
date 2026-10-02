<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\GHNService;
use Illuminate\Http\Request;

class GHNController extends Controller
{
    /**
     * Lấy danh sách Tỉnh/Thành từ GHN
     */
    public function getProvinces(GHNService $ghn)
    {
        return response()->json($ghn->getProvinces());
    }

    /**
     * Lấy danh sách Quận/Huyện từ GHN theo tỉnh
     */
    public function getDistricts(int $provinceId, GHNService $ghn)
    {
        return response()->json($ghn->getDistricts($provinceId));
    }

    /**
     * Lấy danh sách Phường/Xã từ GHN theo quận
     */
    public function getWards(int $districtId, GHNService $ghn)
    {
        return response()->json($ghn->getWards($districtId));
    }

    /**
     * Tính cước phí vận chuyển GHN
     */
    public function getShippingFee(Request $request, GHNService $ghn)
    {
        $request->validate([
            'to_district_id' => 'required|integer',
            'to_ward_code'   => 'required|string',
        ]);

        $cart = session('cart', []);
        $weight = collect($cart)->sum(
            fn ($item) => (int) ($item['weight'] ?? config('services.ghn.default_weight', 200))
                * (int) ($item['quantity'] ?? 1)
        );

        $params = array_merge([
            'from_district_id' => (int) config('services.ghn.from_district_id', 1442),
            'to_district_id'   => (int) $request->to_district_id,
            'to_ward_code'     => (string) $request->to_ward_code,
        ], $ghn->packageParameters($weight));

        return response()->json($ghn->calculateFee($params));
    }
}
