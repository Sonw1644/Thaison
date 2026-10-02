<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GHNService
{
    protected string $baseUrl;
    protected string $token;
    protected int $shopId;

    public function __construct()
    {
        $this->baseUrl = config('services.ghn.base_url') ?? 'https://dev-online-gateway.ghn.vn/shiip/public-api';
        $this->token = config('services.ghn.token') ?? '';
        $this->shopId = (int) config('services.ghn.shop_id', 0);
    }

    protected function client(bool $withShopId = false)
    {
        $headers = [
            'Token'        => $this->token,
            'Content-Type' => 'application/json',
        ];

        if ($withShopId && $this->shopId > 0) {
            $headers['ShopId'] = (int) $this->shopId;
        }

        return Http::baseUrl($this->baseUrl)
            ->withOptions([
                'verify' => filter_var(config('services.ghn.verify_ssl', false), FILTER_VALIDATE_BOOLEAN),
            ])
            ->acceptJson()
            ->timeout(15)
            ->withHeaders($headers);
    }

    /**
     * Lấy danh sách Tỉnh/Thành phố (Tự động lọc bỏ các tỉnh rác/test của GHN Sandbox và ưu tiên các TP lớn)
     */
    public function getProvinces(): array
    {
        $response = $this->get('/master-data/province');

        if (!empty($response['data']) && is_array($response['data'])) {
            // Lọc bỏ các tỉnh thử nghiệm của GHN Sandbox (Hà Nội 02, Test - Alert...)
            $response['data'] = array_values(array_filter($response['data'], function ($item) {
                $name = $item['ProvinceName'] ?? '';
                $id = (int) ($item['ProvinceID'] ?? 0);

                if ($id === 2002 || $id === 298) {
                    return false;
                }
                if (stripos($name, 'Test') !== false || stripos($name, 'Alert') !== false) {
                    return false;
                }
                if ($name === 'Hà Nội 02') {
                    return false;
                }

                return true;
            }));

            // Sắp xếp ưu tiên các thành phố lớn lên đầu, sau đó sắp xếp theo tên
            $priority = [
                'Hà Nội' => 1,
                'Hồ Chí Minh' => 2,
                'Đà Nẵng' => 3,
                'Hải Phòng' => 4,
                'Cần Thơ' => 5,
            ];

            usort($response['data'], function ($a, $b) use ($priority) {
                $nameA = $a['ProvinceName'] ?? '';
                $nameB = $b['ProvinceName'] ?? '';
                $pA = $priority[$nameA] ?? 99;
                $pB = $priority[$nameB] ?? 99;

                if ($pA !== $pB) {
                    return $pA <=> $pB;
                }

                return strcasecmp($nameA, $nameB);
            });
        }

        return $response;
    }

    /**
     * Lấy danh sách Quận/Huyện theo ID Tỉnh
     */
    public function getDistricts(int $provinceId): array
    {
        return $this->get('/master-data/district', [
            'province_id' => $provinceId,
        ]);
    }

    /**
     * Lấy danh sách Phường/Xã theo ID Quận/Huyện
     */
    public function getWards(int $districtId): array
    {
        return $this->get('/master-data/ward', [
            'district_id' => $districtId,
        ]);
    }

    /**
     * Thông số kích thước và đóng gói tiêu chuẩn
     */
    public function packageParameters(int $weight = 200): array
    {
        return [
            'service_type_id' => 2, // Gói Chuẩn
            'weight'          => $weight > 0 ? $weight : 200,
            'length'          => 15,
            'width'           => 15,
            'height'          => 10,
        ];
    }

    /**
     * Tính cước phí vận chuyển GHN
     */
    public function calculateFee(array $params): array
    {
        return $this->post('/v2/shipping-order/fee', $params, false);
    }

    /**
     * Tạo đơn hàng / mã vận đơn trên GHN
     */
    public function createOrder(array $orderData): array
    {
        return $this->post('/v2/shipping-order/create', array_merge([
            'shop_id' => $this->shopId,
        ], $orderData), true);
    }

    /**
     * Hủy đơn hàng trên GHN
     */
    public function cancelOrder(array $orderCodes): array
    {
        return $this->post('/v2/switch-status/cancel', [
            'order_codes' => $orderCodes,
            'shop_id'     => $this->shopId,
        ], true);
    }

    protected function get(string $uri, array $query = [], bool $withShopId = false): array
    {
        try {
            $response = $this->client($withShopId)->get($uri, $query);
            if (!$response->successful()) {
                Log::warning('GHN GET request failed', [
                    'uri'    => $uri,
                    'status' => $response->status(),
                    'body'   => $response->json(),
                ]);
                $body = $response->json();
                return [
                    'code'    => $response->status(),
                    'message' => $body['message'] ?? ($body['code_message'] ?? 'GHN API request failed.'),
                    'data'    => null,
                ];
            }
            return $response->json() ?? ['code' => -1, 'message' => 'GHN returned an empty response.', 'data' => null];
        } catch (ConnectionException $exception) {
            Log::error('Unable to connect to GHN', ['uri' => $uri, 'error' => $exception->getMessage()]);
            return ['code' => -1, 'message' => 'Unable to connect to GHN.', 'data' => null];
        }
    }

    protected function post(string $uri, array $payload, bool $withShopId = false): array
    {
        try {
            $response = $this->client($withShopId)->post($uri, $payload);
            if (!$response->successful()) {
                Log::warning('GHN POST request failed', [
                    'uri'     => $uri,
                    'status'  => $response->status(),
                    'payload' => $payload,
                    'body'    => $response->json(),
                ]);
                $body = $response->json();
                return [
                    'code'    => $response->status(),
                    'message' => $body['message'] ?? ($body['code_message'] ?? 'GHN API request failed.'),
                    'data'    => null,
                ];
            }
            return $response->json() ?? ['code' => -1, 'message' => 'GHN returned an empty response.', 'data' => null];
        } catch (ConnectionException $exception) {
            Log::error('Unable to connect to GHN', ['uri' => $uri, 'error' => $exception->getMessage()]);
            return ['code' => -1, 'message' => 'Unable to connect to GHN.', 'data' => null];
        }
    }
}
