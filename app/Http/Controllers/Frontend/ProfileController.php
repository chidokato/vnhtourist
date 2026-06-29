<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends BaseFrontendController
{
    public function index()
    {
        $orders = \App\Models\Order::with('items.post')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('frontend.profile.index', $this->sharedViewData([
            'pageTitle' => 'Thông tin tài khoản',
            'user' => Auth::user(),
            'orders' => $orders
        ]));
    }

    public function settings()
    {
        return view('frontend.profile.settings', $this->sharedViewData([
            'pageTitle' => 'Cài đặt tài khoản',
            'user' => Auth::user()
        ]));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'phone.max' => 'Số điện thoại không hợp lệ.',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->back()->with('success', 'Đã cập nhật thông tin thành công.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $messages = [
            'current_password.required' => 'Vui lòng nhập mật khẩu cũ.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Nhập lại mật khẩu không khớp.',
        ];

        // Nếu user có mật khẩu cũ thì bắt buộc nhập đúng mật khẩu cũ
        if ($user->password) {
            $request->validate([
                'current_password' => 'required|string',
                'password' => 'required|string|min:6|confirmed',
            ], $messages);

            if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Mật khẩu cũ không chính xác.']);
            }
        } else {
            // Nếu user chưa có mật khẩu (ví dụ đăng nhập Google) thì không yêu cầu mật khẩu cũ
            $request->validate([
                'password' => 'required|string|min:6|confirmed',
            ], $messages);
        }

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Đã thay đổi mật khẩu thành công.');
    }
    public function updateAvatar(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'avatar.required' => 'Vui lòng chọn một ảnh.',
            'avatar.image' => 'File tải lên phải là một ảnh.',
            'avatar.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'avatar.max' => 'Kích thước ảnh không được vượt quá 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('avatar')
            ]);
        }

        $user = Auth::user();

        try {
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($user->avatar) {
                    \App\Support\MediaManager::delete($user->avatar);
                }

                // Store new avatar
                $path = \App\Support\MediaManager::store($request->file('avatar'), 'avatars', 'user-' . $user->id);
                
                $user->update([
                    'avatar' => $path
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Cập nhật ảnh đại diện thành công.',
                    'avatar_url' => \App\Support\MediaManager::publicUrl($path)
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy file ảnh.'
        ], 400);
    }
}
