                    <div class="user-profile-sidebar">
                        <div class="user-profile-sidebar-top">
                            <div class="user-profile-img">
                                @php
                                    $avatar = $user->avatar ? \App\Support\MediaManager::publicUrl($user->avatar) : null;
                                    if (!$avatar) {
                                        $initial = mb_strtoupper(mb_substr($user->name, 0, 1));
                                        $avatar = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect width="100%" height="100%" fill="%23e9ecef"/><text x="50" y="55" dominant-baseline="middle" text-anchor="middle" font-size="40" font-family="Arial, sans-serif" font-weight="bold" fill="%236c757d">'.$initial.'</text></svg>';
                                    }
                                @endphp
                                <img src="{{ $avatar }}" alt="Profile Image">
                                <button type="button" class="profile-img-btn"><i class="fas fa-camera"></i></button>
                                <input type="file" class="profile-img-file" accept="image/*">
                            </div>
                            <h4>{{ $user->name }}</h4>
                            <p>{{ $user->email }}</p>
                        </div>
                        <ul class="user-profile-sidebar-list">
                            <li><a class="{{ request()->routeIs('frontend.profile') ? 'active' : '' }}" href="{{ route('frontend.profile') }}"><i class="fas fa-tachometer-alt"></i> Tổng quan</a></li>
                            <li><a class="{{ request()->routeIs('frontend.wishlist.index') ? 'active' : '' }}" href="{{ route('frontend.wishlist.index') }}"><i class="fas fa-heart"></i> Tour yêu thích</a></li>
                            <li><a class="{{ request()->routeIs('frontend.profile.settings') ? 'active' : '' }}" href="{{ route('frontend.profile.settings') }}"><i class="fas fa-cog"></i> Cài đặt</a></li>
                            <li>
                                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-inner').submit();"><i class="fas fa-sign-out-alt"></i>    Đăng xuất</a>
                                <form id="logout-form-inner" action="{{ route('frontend.logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lấy tất cả các input file (trường hợp có nhiều sidebar như mobile/desktop)
        const fileInputs = document.querySelectorAll('.profile-img-file');
        const imgs = document.querySelectorAll('.user-profile-img img');

        fileInputs.forEach((fileInput, index) => {
            fileInput.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const formData = new FormData();
                    formData.append('avatar', e.target.files[0]);
                    
                    // Gọi AJAX upload
                    fetch("{{ route('frontend.profile.settings.updateAvatar') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Cập nhật tất cả các ảnh đại diện trên sidebar
                            imgs.forEach(img => img.src = data.avatar_url);
                            
                            // Cập nhật ảnh ở header
                            const headerImg = document.querySelector('.header-user-avatar');
                            if(headerImg) headerImg.src = data.avatar_url;
                            
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Thành công',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        } else {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi',
                                    text: data.message || 'Không thể tải ảnh lên'
                                });
                            } else {
                                alert(data.message || 'Không thể tải ảnh lên');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: 'Đã xảy ra lỗi khi tải ảnh lên.'
                            });
                        } else {
                            alert('Đã xảy ra lỗi khi tải ảnh lên.');
                        }
                    })
                    .finally(() => {
                        // Reset input để có thể chọn lại cùng 1 ảnh
                        e.target.value = '';
                    });
                }
            });
        });
    });
</script>
@endpush
