<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Tên chuyên gia <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $expert->name) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Chức vụ <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('role') is-invalid @enderror" name="role" value="{{ old('role', $expert->role) }}" required>
            @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Hình ảnh</label>
    @if ($expert->image)
        <div class="mb-2">
            <img src="{{ \App\Support\MediaManager::publicUrl($expert->image) }}" alt="" class="img-thumbnail" style="max-height: 150px;">
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image" value="1">
            <label class="form-check-label text-danger" for="remove_image">Xóa hình ảnh này</label>
        </div>
    @endif
    <input type="file" class="form-control @error('image_file') is-invalid @enderror" name="image_file" accept="image/*">
    <div class="form-text">Định dạng hỗ trợ: jpg, jpeg, png, webp. Tối đa 2MB. Khuyến nghị tỷ lệ 1:1.</div>
    @error('image_file')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<h5 class="font-size-14 mt-4 mb-3"><i class="fas fa-link me-1"></i> Mạng xã hội (Tùy chọn)</h5>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Facebook URL</label>
            <input type="url" class="form-control @error('facebook_url') is-invalid @enderror" name="facebook_url" value="{{ old('facebook_url', $expert->facebook_url) }}">
            @error('facebook_url')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Twitter/X URL</label>
            <input type="url" class="form-control @error('twitter_url') is-invalid @enderror" name="twitter_url" value="{{ old('twitter_url', $expert->twitter_url) }}">
            @error('twitter_url')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Instagram URL</label>
            <input type="url" class="form-control @error('instagram_url') is-invalid @enderror" name="instagram_url" value="{{ old('instagram_url', $expert->instagram_url) }}">
            @error('instagram_url')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">LinkedIn URL</label>
            <input type="url" class="form-control @error('linkedin_url') is-invalid @enderror" name="linkedin_url" value="{{ old('linkedin_url', $expert->linkedin_url) }}">
            @error('linkedin_url')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Thứ tự hiển thị</label>
            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" name="sort_order" value="{{ old('sort_order', $expert->sort_order ?? 0) }}">
            <div class="form-text">Số nhỏ hơn sẽ xếp lên trước.</div>
            @error('sort_order')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6 d-flex align-items-center">
        <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $expert->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Hiển thị trên website</label>
        </div>
    </div>
</div>
