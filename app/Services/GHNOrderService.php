<?php

namespace App\Services;

use App\Models\Order;

class GHNOrderService
{
    public function __construct(private GHNService $ghn)
    {
    }

    /**
     * Tạo vận đơn GHN từ model Order của hệ thống
     */
    public function create(Order $order, bool $isPaid = false): array
    {
        $items = [];
        $weight = 0;

        foreach ($order->items as $item) {
            $itemWeight = (int) ($item->product->weight ?? 200);
            $weight += $itemWeight * (int) $item->quantity;
            $items[] = [
                'name'     => $item->product_name ?? ($item->product->name ?? 'Sản phẩm'),
                'quantity' => (int) $item->quantity,
                'price'    => (int) $item->price,
                'weight'   => $itemWeight,
            ];
        }

        $payload = [
            'payment_type_id' => 2, // Người mua/nhận trả cước
            'note'            => 'Đơn hàng #' . $order->order_code,
            'required_note'   => 'KHONGCHOXEMHANG',
            'to_name'         => $order->customer_name,
            'to_phone'        => $order->customer_phone,
            'to_address'      => $order->shipping_address,
            'to_ward_code'    => (string) $order->to_ward_code,
            'to_district_id'  => (int) $order->to_district_id,
            'cod_amount'      => $isPaid || $order->payment_status === 'paid' ? 0 : (int) $order->total_amount,
            'weight'          => $weight > 0 ? $weight : 300,
            'length'          => 15,
            'width'           => 15,
            'height'          => 10,
            'service_type_id' => 2,
            'items'           => $items,
        ];

        return $this->ghn->createOrder($payload);
    }
}
