<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CustomerInquiry;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function login()
    {
        return view('backend.auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->route('backend.admin.dashboard');
        }

        return back()
            ->withErrors(['email' => 'Thông tin đăng nhập không chính xác.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('backend.admin.login');
    }

    public function index()
    {
        $stats = [
            'properties' => Post::query()->where('type', Post::TYPE_PRODUCT)->count(),
            'agents' => User::query()->count(),
            'customers' => CustomerInquiry::query()->count(),
            'pending_posts' => Post::query()->where('type', Post::TYPE_PRODUCT)->where('is_active', false)->count(),
        ];

        $latestInquiries = CustomerInquiry::query()
            ->latest()
            ->limit(3)
            ->get();

        $activities = $latestInquiries->map(function (CustomerInquiry $inquiry) {
            return [
                'title' => 'Khách hàng mới: ' . $inquiry->name,
                'detail' => trim($inquiry->phone . ($inquiry->project_title ? ' | ' . $inquiry->project_title : '')),
            ];
        })->all();

        if ($activities === []) {
            $activities = [
                ['title' => 'Chưa có khách hàng mới', 'detail' => 'Form thông tin khách hàng sẽ hiển thị tại đây sau khi có người gửi.'],
            ];
        }

        return view('backend.admin.dashboard_content', compact('stats', 'activities'));
    }
}
