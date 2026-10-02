<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Gửi tin nhắn từ User tới Admin
     */
    public function send(Request $request)
    {
        // 1. Lấy nội dung từ request JSON
        $messageText = $request->input('message');

        // 2. Kiểm tra nội dung trống
        if (empty(trim($messageText))) {
            return response()->json(['error' => 'Nội dung tin nhắn không được để trống'], 400);
        }

        // 3. Xác định Admin nhận tin
        // Ưu tiên tìm user có role là admin, nếu không thấy thì mặc định lấy ID 1
        $admin = User::where('role', 'admin')->first();
        $receiverId = $admin ? $admin->id : 1;

        try {
            // 4. Lưu tin nhắn vào Database
            $message = Message::create([
                'sender_id'   => Auth::id(),
                'receiver_id' => $receiverId,
                'content'     => $messageText,
                'is_read'     => false,
            ]);

            // Trả về dữ liệu tin nhắn vừa tạo để Frontend hiển thị ngay (nếu cần)
            return response()->json($message);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể gửi tin nhắn: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Lấy số tin nhắn chưa đọc từ Admin gửi cho User
     */
    public function getUnreadCount()
    {
        $userId = Auth::id();
        $adminIds = User::where('role', 'admin')->pluck('id')->toArray();
        if (empty($adminIds)) {
            $adminIds = [1];
        }

        $count = Message::whereIn('sender_id', $adminIds)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->count();

        return response()->json(['unread_count' => $count]);
    }

    /**
     * Lấy lịch sử chat giữa User hiện tại và Admin, đồng thời đánh dấu đã đọc
     */
    public function getMessages()
    {
        $userId = Auth::id();

        // Tìm tất cả Admin IDs để lấy tin nhắn trao đổi qua lại
        $adminIds = User::where('role', 'admin')->pluck('id')->toArray();
        if (empty($adminIds)) {
            $adminIds = [1];
        }

        // Đánh dấu tin nhắn từ Admin gửi cho User này là đã đọc
        Message::whereIn('sender_id', $adminIds)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Lấy toàn bộ hội thoại giữa User và Admin
        $messages = Message::with(['sender', 'receiver'])
            ->where(function ($q) use ($userId, $adminIds) {
                // Tin nhắn User gửi cho Admin
                $q->where('sender_id', $userId)
                  ->whereIn('receiver_id', $adminIds);
            })
            ->orWhere(function ($q) use ($userId, $adminIds) {
                // Tin nhắn Admin phản hồi cho User
                $q->whereIn('sender_id', $adminIds)
                  ->where('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc') // Sắp xếp theo thứ tự thời gian tăng dần
            ->get();

        return response()->json($messages);
    }
}
