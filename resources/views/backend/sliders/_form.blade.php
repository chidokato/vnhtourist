<div class="row">
    <div class="col-xl-9">
        <div class="card border mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label" for="title">Tiêu đề</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $slider->title) }}">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="subtitle">Tiêu đề phụ</label>
                    <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle" name="subtitle" value="{{ old('subtitle', $slider->subtitle) }}">
                    @error('subtitle')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="link">Link (URL khi click)</label>
                        <input type="url" class="form-control @error('link') is-invalid @enderror" id="link" name="link" value="{{ old('link', $slider->link) }}">
                        @error('link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="button_text">Tên nút (Hiển thị trên Slider)</label>
                        <input type="text" class="form-control @error('button_text') is-invalid @enderror" id="button_text" name="button_text" value="{{ old('button_text', $slider->button_text) }}" placeholder="VD: Khám phá ngay">
                        @error('button_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3">
        <div class="card border mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Cấu hình</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label" for="sort_order">Thứ tự hiển thị</label>
                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $slider->sort_order ?? 0) }}">
                    <div class="form-text">Số nhỏ hơn sẽ xếp lên trước.</div>
                    @error('sort_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-check form-switch form-switch-md" dir="ltr">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $slider->is_active ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Hiển thị trên website</label>
                </div>
            </div>
        </div>

        <div class="card border mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Hình ảnh</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    @if ($slider->image)
                        <div class="mb-2">
                            <img src="{{ \App\Support\MediaManager::publicUrl($slider->image) }}" alt="" class="img-thumbnail" style="max-height: 150px;">
                        </div>
                    @endif
                    <input class="form-control @error('image_file') is-invalid @enderror" type="file" id="image_file" name="image_file" accept="image/*" {{ $slider->exists ? '' : 'required' }}>
                    <div class="form-text">Định dạng hỗ trợ: jpg, jpeg, png, webp. Tối đa 2MB.</div>
                    @error('image_file')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>
