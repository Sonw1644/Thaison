<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Lấy danh sách những User đã từng nhắn tin với Admin kèm số tin chưa đọc
     */
    public function getUsers()
    {
        $currentAdminId = Auth::id();
        $adminIds = User::where('role', 'admin')->pluck('id')->toArray();
        if (!in_array($currentAdminId, $adminIds)) {
            $adminIds[] = $currentAdminId;
        }

        // Tìm tất cả ID của người dùng (khách hàng) có tương tác với admin
        $userIds = Message::whereIn('receiver_id', $adminIds)
            ->orWhereIn('sender_id', $adminIds)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($msg) use ($adminIds) {
                return in_array($msg->sender_id, $adminIds) ? $msg->receiver_id : $msg->sender_id;
            })
            ->unique()
            ->values()
            ->toArray();

        // Lấy thông tin chi tiết các User đó (loại trừ tài khoản admin) và đếm tin chưa đọc
        $users = User::whereIn('id', $userIds)
            ->whereNotIn('id', $adminIds)
            ->select('id', 'name', 'email')
            ->get()
            ->map(function($user) use ($adminIds) {
                $user->unread_count = Message::where('sender_id', $user->id)
                    ->whereIn('receiver_id', $adminIds)
                    ->where('is_read', false)
                    ->count();
                return $user;
            });

        return response()->json($users);
    }

    /**
     * Lấy tổng số tin nhắn chưa đọc của tất cả khách hàng
     */
    public function getUnreadCount()
    {
        $currentAdminId = Auth::id();
        $adminIds = User::where('role', 'admin')->pluck('id')->toArray();
        if (!in_array($currentAdminId, $adminIds)) {
            $adminIds[] = $currentAdminId;
        }

        $count = Message::whereNotIn('sender_id', $adminIds)
            ->whereIn('receiver_id', $adminIds)
            ->where('is_read', false)
            ->count();

        return response()->json(['unread_count' => $count]);
    }

    /**
     * Lấy lịch sử tin nhắn của một User cụ thể và đánh dấu là đã đọc
     */
    public function getMessages($userId)
    {
        $currentAdminId = Auth::id();
        $adminIds = User::where('role', 'admin')->pluck('id')->toArray();
        if (!in_array($currentAdminId, $adminIds)) {
            $adminIds[] = $currentAdminId;
        }

        // Đánh dấu tin nhắn từ User này gửi đến Admin là đã đọc
        Message::where('sender_id', $userId)
            ->whereIn('receiver_id', $adminIds)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return Message::with('sender')
            ->where(function ($q) use ($userId, $adminIds) {
                $q->where('sender_id', $userId)->whereIn('receiver_id', $adminIds);
            })
            ->orWhere(function ($q) use ($userId, $adminIds) {
                $q->whereIn('sender_id', $adminIds)->where('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Admin gửi tin nhắn phản hồi
     */
    public function send(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required'
        ]);

        $message = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->user_id,
            'content'     => $request->message,
            'is_read'     => false // User chưa đọc tin nhắn này
        ]);

        return response()->json($message);
    }
}
