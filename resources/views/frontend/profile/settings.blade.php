@extends('frontend.layouts.app')

@section('title', 'Cài đặt tài khoản')

@section('content')
<main class="main pages-category">
    
    <div class="user-profile pt-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('frontend.profile.sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="user-profile-wrapper">
                        
                        <div class="col-lg-12 mb-4">
                            <div class="user-profile-card">
                                <h4 class="user-profile-card-title">Cập nhật thông tin cá nhân</h4>
                                <div class="user-profile-form">
                                    <form action="{{ route('frontend.profile.settings.updateProfile') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Họ và tên</label>
                                                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" placeholder="Họ và tên" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email (Không thể thay đổi)</label>
                                                    <input type="email" class="form-control" value="{{ $user->email }}" readonly disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Số điện thoại</label>
                                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="Số điện thoại">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Địa chỉ</label>
                                                    <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}" placeholder="Địa chỉ">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="theme-btn my-3">Cập nhật thông tin <i class="fas fa-user"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="user-profile-card">
                                <h4 class="user-profile-card-title">Đổi mật khẩu</h4>
                                <div class="col-lg-12">
                                    <div class="user-profile-form">
                                        <form action="{{ route('frontend.profile.settings.updatePassword') }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label>Mật khẩu cũ (Để trống nếu đăng nhập bằng Google lần đầu)</label>
                                                <input type="password" name="current_password" class="form-control" placeholder="Mật khẩu cũ">
                                            </div>
                                            <div class="form-group">
                                                <label>Mật khẩu mới</label>
                                                <input type="password" name="password" class="form-control" placeholder="Mật khẩu mới" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Nhập lại mật khẩu</label>
                                                <input type="password" name="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu mới" required>
                                            </div>
                                            <button type="submit" class="theme-btn my-3">Đổi mật khẩu <i class="fas fa-key"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
