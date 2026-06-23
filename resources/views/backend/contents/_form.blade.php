@csrf

@php
    $currentImage = old('existing_image', $post->image ?? '');
    $imagePreview = \App\Support\MediaManager::publicUrl($currentImage) ?? '';
    $currentLocationImage = old('existing_location_image', $post->location_image ?? '');
    $locationImagePreview = \App\Support\MediaManager::publicUrl($currentLocationImage) ?? '';
    $existingGalleryImages = $galleryImages ?? collect();
    $storedPrice = old('price', null);
    $storedPriceUnit = old('price_unit', null);
    $selectedProvinceId = (string) old('province_id', $post->province_id ?? '');
    $selectedWardId = (string) old('ward_id', $post->ward_id ?? '');
    $provinceOptions = $provinceOptions ?? collect();
    $wardOptions = $wardOptions ?? collect();
    $wardMap = $wardMap ?? [];
    $tourOptionGroups = $tourOptionGroups ?? [];

    if ($type === 'product' && $storedPrice === null && isset($post) && $post->price !== null) {
        if ((float) $post->price >= 1000000000) {
            $storedPrice = rtrim(rtrim(number_format((float) $post->price / 1000000000, 2, '.', ''), '0'), '.');
            $storedPriceUnit = 'ty';
        } else {
            $storedPrice = rtrim(rtrim(number_format((float) $post->price / 1000000, 2, '.', ''), '0'), '.');
            $storedPriceUnit = 'trieu';
        }
    }

    if ($storedPriceUnit === null) {
        $storedPriceUnit = 'ty';
    }

    $tourContentTabs = [
        'summary' => 'Thong tin noi bat',
        'content' => 'Lich trinh',
        'sales_policy' => 'Chinh sach gia',
        'guide_content' => 'Huong dan',
        'visa_content' => 'Visa',
        'insurance_content' => 'Bao hiem',
        'promotion_content' => 'Khuyen mai',
    ];

    $activeTourContentTab = collect(array_keys($tourContentTabs))
        ->first(fn ($field) => $errors->has($field), 'content');
@endphp

@once
    @prepend('styles')
        <link href="{{ asset('tourit/assets/vendor/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <style>
            .select2-container {
                width: 100% !important;
            }

            .select2-container--default .select2-selection--single {
                min-height: 38px;
                border: 1px solid #ced4da;
                border-radius: 0.375rem;
                display: flex;
                align-items: center;
                padding: 0 0.75rem;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #212529;
                line-height: 1.5;
                padding-left: 0;
                padding-right: 1.5rem;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 100%;
                right: 0.75rem;
            }

            .select2-container--default.select2-container--open .select2-selection--single,
            .select2-container--default .select2-selection--single:focus {
                border-color: #405189;
                box-shadow: 0 0 0 0.15rem rgba(64, 81, 137, 0.15);
            }

            .select2-dropdown {
                border: 1px solid #ced4da;
                border-radius: 0.5rem;
                overflow: hidden;
            }

            .select2-search--dropdown {
                padding: 0.75rem;
            }

            .select2-container--default .select2-search--dropdown .select2-search__field {
                border: 1px solid #ced4da;
                border-radius: 0.375rem;
                padding: 0.5rem 0.75rem;
            }
        </style>
    @endprepend

    @prepend('scripts')
        <script src="{{ asset('tourit/assets/js/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('tourit/assets/vendor/select2/select2.min.js') }}"></script>
    @endprepend
@endonce

<div
    class="row backend-content-form"
    id="backend-content-form"
    data-ward-map='@json($wardMap)'
    data-url-base="{{ url('/') }}"
    data-slug-prefix="{{ $type === 'product' ? 'san-pham' : 'tin-tuc' }}"
>
    <div class="col-xl-9">
        <div class="card border">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="mb-3">
                            <label for="title" class="form-label">Tiều đề</label>
                            <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $post->title ?? '') }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $post->slug ?? '') }}">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    

                    @if ($type === 'product')
                        <div class="col-8">
                            <div class="mb-3">
                                <label for="itinerary" class="form-label">Hanh trinh</label>
                                <input type="text" id="itinerary" name="itinerary" class="form-control @error('itinerary') is-invalid @enderror" value="{{ old('itinerary', $post->itinerary ?? '') }}">
                                @error('itinerary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <input type="hidden" name="address" value="{{ old('address', $post->address ?? '') }}">

                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="duration" class="form-label">Thoi luong</label>
                                <input type="text" id="duration" name="duration" class="form-control @error('duration') is-invalid @enderror" value="{{ old('duration', $post->duration ?? '') }}" placeholder="Vi du: 9 ngay 8 dem">
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="mb-3">
                                <label for="transport" class="form-label">Phuong tien</label>
                                <div class="d-flex gap-1">
                                    <div class="flex-grow-1" style="min-width: 0;">
                                        @php
                                            $selectedTransports = old('transport', isset($post) && $post->transport ? explode(', ', $post->transport) : []);
                                        @endphp
                                        <select id="transport" name="transport[]" multiple class="form-select @error('transport') is-invalid @enderror">
                                            @foreach (($tourOptionGroups['transport'] ?? []) as $value => $label)
                                                <option value="{{ $value }}" {{ in_array($value, (array)$selectedTransports) ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary js-quick-add-tour-option flex-shrink-0" data-group="transport" data-target="#transport" title="Thêm nhanh"><i class="ri-add-line"></i></button>
                                    <button type="button" class="btn btn-outline-secondary js-reload-tour-option flex-shrink-0" data-group="transport" data-target="#transport" title="Tải lại"><i class="ri-refresh-line"></i></button>
                                </div>
                                @error('transport')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="departure_location" class="form-label">Dia diem khoi hanh</label>
                                <div class="d-flex gap-1">
                                    <div class="flex-grow-1" style="min-width: 0;">
                                        <select id="departure_location" name="departure_location" class="form-select @error('departure_location') is-invalid @enderror">
                                            <option value="">Chon dia diem khoi hanh</option>
                                            @foreach (($tourOptionGroups['location'] ?? []) as $value => $label)
                                                <option value="{{ $value }}" {{ old('departure_location', $post->departure_location ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary js-quick-add-tour-option flex-shrink-0" data-group="location" data-target="#departure_location" title="Thêm nhanh"><i class="ri-add-line"></i></button>
                                    <button type="button" class="btn btn-outline-secondary js-reload-tour-option flex-shrink-0" data-group="location" data-target="#departure_location" title="Tải lại"><i class="ri-refresh-line"></i></button>
                                </div>
                                @error('departure_location')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        

                        <div class="col-lg-8">
                            <div class="mb-3">
                                <label for="attractions" class="form-label">Diem tham quan</label>
                                <input type="text" id="attractions" name="attractions" class="form-control @error('attractions') is-invalid @enderror" value="{{ old('attractions', $post->attractions ?? '') }}" placeholder="Vi du: Phap, Duc, Thuy Si">
                                @error('attractions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="departure_date" class="form-label">Ngay khoi hanh</label>
                                <input type="date" id="departure_date" name="departure_date" class="form-control @error('departure_date') is-invalid @enderror" value="{{ old('departure_date', $post->departure_date ?? '') }}">
                                @error('departure_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <input type="hidden" name="province_id" value="{{ old('province_id', $post->province_id ?? '') }}">
                            <input type="hidden" name="ward_id" value="{{ old('ward_id', $post->ward_id ?? '') }}">
                            <input type="hidden" name="map_embed" value="{{ old('map_embed', $post->map_embed ?? '') }}">
                            <input type="hidden" name="existing_location_image" value="{{ $currentLocationImage }}">
                            <input type="hidden" name="remove_location_image" id="remove_location_image" value="0">
                        </div>

                        <input type="hidden" name="area_from" value="{{ old('area_from', $post->area_from ?? $post->area ?? '') }}">
                        <input type="hidden" name="area_to" value="{{ old('area_to', $post->area_to ?? $post->area ?? '') }}">
                        <input type="hidden" name="floor_count_from" value="{{ old('floor_count_from', $post->floor_count_from ?? $post->floor_count ?? '') }}">
                        <input type="hidden" name="floor_count_to" value="{{ old('floor_count_to', $post->floor_count_to ?? $post->floor_count ?? '') }}">
                        <input type="hidden" name="unit_count_from" value="{{ old('unit_count_from', $post->unit_count_from ?? $post->unit_count ?? '') }}">
                        <input type="hidden" name="unit_count_to" value="{{ old('unit_count_to', $post->unit_count_to ?? $post->unit_count ?? '') }}">
                        <input type="hidden" name="bedroom_count_from" value="{{ old('bedroom_count_from', $post->bedroom_count_from ?? $post->bedroom_count ?? '') }}">
                        <input type="hidden" name="bedroom_count_to" value="{{ old('bedroom_count_to', $post->bedroom_count_to ?? $post->bedroom_count ?? '') }}">
                        <input type="hidden" name="bathroom_count_from" value="{{ old('bathroom_count_from', $post->bathroom_count_from ?? $post->bathroom_count ?? '') }}">
                        <input type="hidden" name="bathroom_count_to" value="{{ old('bathroom_count_to', $post->bathroom_count_to ?? $post->bathroom_count ?? '') }}">
                    @endif

                    @if ($type === 'product')
                        <div class="col-12">
                            <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                @foreach ($tourContentTabs as $field => $label)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ $activeTourContentTab === $field ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#tour-tab-{{ $field }}" type="button" role="tab" aria-selected="{{ $activeTourContentTab === $field ? 'true' : 'false' }}">
                                            {{ $label }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade {{ $activeTourContentTab === 'summary' ? 'show active' : '' }}" id="tour-tab-summary" role="tabpanel">
                                    <textarea id="summary" name="summary" rows="3" class="form-control editor @error('summary') is-invalid @enderror">{{ old('summary', $post->summary ?? '') }}</textarea>
                                    @error('summary')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="tab-pane fade {{ $activeTourContentTab === 'content' ? 'show active' : '' }}" id="tour-tab-content" role="tabpanel">
                                    <textarea id="tour_content" name="content" rows="8" class="form-control editor @error('content') is-invalid @enderror">{{ old('content', $post->content ?? '') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="tab-pane fade {{ $activeTourContentTab === 'sales_policy' ? 'show active' : '' }}" id="tour-tab-sales_policy" role="tabpanel">
                                    <textarea id="tour_sales_policy" name="sales_policy" rows="8" class="form-control editor @error('sales_policy') is-invalid @enderror">{{ old('sales_policy', $post->sales_policy ?? '') }}</textarea>
                                    @error('sales_policy')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="tab-pane fade {{ $activeTourContentTab === 'guide_content' ? 'show active' : '' }}" id="tour-tab-guide_content" role="tabpanel">
                                    <textarea id="guide_content" name="guide_content" rows="8" class="form-control editor @error('guide_content') is-invalid @enderror">{{ old('guide_content', $post->guide_content ?? '') }}</textarea>
                                    @error('guide_content')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="tab-pane fade {{ $activeTourContentTab === 'visa_content' ? 'show active' : '' }}" id="tour-tab-visa_content" role="tabpanel">
                                    <textarea id="visa_content" name="visa_content" rows="8" class="form-control editor @error('visa_content') is-invalid @enderror">{{ old('visa_content', $post->visa_content ?? '') }}</textarea>
                                    @error('visa_content')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="tab-pane fade {{ $activeTourContentTab === 'insurance_content' ? 'show active' : '' }}" id="tour-tab-insurance_content" role="tabpanel">
                                    <textarea id="insurance_content" name="insurance_content" rows="8" class="form-control editor @error('insurance_content') is-invalid @enderror">{{ old('insurance_content', $post->insurance_content ?? '') }}</textarea>
                                    @error('insurance_content')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="tab-pane fade {{ $activeTourContentTab === 'promotion_content' ? 'show active' : '' }}" id="tour-tab-promotion_content" role="tabpanel">
                                    <textarea id="promotion_content" name="promotion_content" rows="8" class="form-control editor @error('promotion_content') is-invalid @enderror">{{ old('promotion_content', $post->promotion_content ?? '') }}</textarea>
                                    @error('promotion_content')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endif


                    @if ($type !== 'product')
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="summary" class="form-label">Thông tin nổi bật</label>
                                <textarea id="summary" name="summary" rows="3" class="form-control editor @error('summary') is-invalid @enderror">{{ old('summary', $post->summary ?? '') }}</textarea>
                                @error('summary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endif

                    @if (false && $type === 'product')
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="sales_policy" class="form-label">KHuyến mãi đặc biệt</label>
                                <textarea id="sales_policy" name="sales_policy" rows="6" class="form-control editor @error('sales_policy') is-invalid @enderror">{{ old('sales_policy', $post->sales_policy ?? '') }}</textarea>
                                @error('sales_policy')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endif

                    @if ($type !== 'product')
                        <div class="col-12">
                            <div class="mb-0">
                                <label for="content" class="form-label">Noi dung</label>
                                <textarea id="content" name="content" rows="8" class="form-control editor @error('content') is-invalid @enderror">{{ old('content', $post->content ?? '') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card border">
            <div class="card-header">
                <h5 class="card-title mb-0">Cau hinh SEO</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="seo_title" class="form-label">Title</label>
                            <input type="text" id="seo_title" name="seo_title" class="form-control @error('seo_title') is-invalid @enderror" value="{{ old('seo_title', $post->seo_title ?? '') }}" placeholder="Nhap SEO title">
                            @error('seo_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label for="seo_description" class="form-label">Description</label>
                            <textarea id="seo_description" name="seo_description" rows="3" class="form-control @error('seo_description') is-invalid @enderror" placeholder="Nhap SEO description">{{ old('seo_description', $post->seo_description ?? '') }}</textarea>
                            @error('seo_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="row">
                            <div class="col-lg-2">
                                <label class="form-label mb-0">Hien thi</label>
                            </div>
                            <div class="col-lg-10">
                                <div id="seo-link-preview" class="text-muted">{{ url('/') }}/san-pham/slug</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-xl-3">
        <div class="card border">
            <div class="card-body">
                <div class="mb-3">
                    <label for="category_id" class="form-label">Danh muc (diem den)</label>
                    <div class="d-flex gap-1">
                        <div class="flex-grow-1" style="min-width: 0;">
                            <select
                                id="category_id"
                                name="category_id"
                                class="form-select js-admin-category-select @error('category_id') is-invalid @enderror"
                                data-placeholder="Chon danh muc"
                            >
                                <option value="">Khong chon</option>
                                @foreach ($categories as $id => $name)
                                    <option value="{{ $id }}" {{ (string) old('category_id', $post->category_id ?? '') === (string) $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" class="btn btn-outline-primary js-quick-add-category flex-shrink-0" data-type="{{ $type }}" data-target="#category_id" title="Thêm nhanh"><i class="ri-add-line"></i></button>
                        <button type="button" class="btn btn-outline-secondary js-reload-category flex-shrink-0" data-type="{{ $type }}" data-target="#category_id" title="Tải lại"><i class="ri-refresh-line"></i></button>
                    </div>
                    @error('category_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                @if ($type === 'product')
                    <div class="mb-3">
                        <label for="seller_id" class="form-label">Seller</label>
                        <select id="seller_id" name="seller_id" class="form-select @error('seller_id') is-invalid @enderror">
                            <option value="">Khong chon</option>
                            @foreach (($sellerOptions ?? collect()) as $id => $name)
                                <option value="{{ $id }}" {{ (string) old('seller_id', $post->seller_id ?? '') === (string) $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('seller_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Gia</label>
                        <div class="row g-2">
                            <div class="col-8">
                                <input type="number" step="0.01" min="0" id="price" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ $storedPrice ?? '' }}">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-4">
                                <select id="price_unit" name="price_unit" class="form-select @error('price_unit') is-invalid @enderror">
                                    <option value="trieu" {{ $storedPriceUnit === 'trieu' ? 'selected' : '' }}>Trieu</option>
                                </select>
                                @error('price_unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card border mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Hinh anh</h5>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="existing_image" value="{{ $currentImage }}">
                        <input type="hidden" name="remove_image" id="remove_image" value="0">
                        <input type="file" id="image_file" name="image_file" class="d-none" accept="image/*">
                        @if ($type === 'product')
                            <input type="file" id="gallery_files" name="gallery_files[]" class="d-none" accept="image/*" multiple>
                        @endif

                        <div class="d-flex flex-column gap-2">
                            <div class="fw-semibold">Anh chinh</div>
                            <button type="button" id="image-preview-box" class="border rounded bg-light d-flex align-items-center justify-content-center overflow-hidden p-0 w-100 content-image-dropzone content-image-dropzone--cover">
                                <img id="image-preview" src="{{ $imagePreview }}" alt="Preview" class="w-100 h-100 object-fit-cover {{ $imagePreview ? '' : 'd-none' }}">
                                <div id="image-placeholder" class="text-center text-muted px-3 {{ $imagePreview ? 'd-none' : '' }}">
                                    <div class="display-6 mb-2">
                                        <i class="ri-image-line"></i>
                                    </div>
                                    <div class="fw-semibold">NO IMAGE</div>
                                    <div>Click hoac drop anh vao day</div>
                                </div>
                            </button>
                            <button type="button" id="remove-image-trigger" class="btn btn-soft-danger btn-sm align-self-start {{ $imagePreview ? '' : 'd-none' }}">Bo anh dai dien</button>
                        </div>

                        @error('image_file')
                            <div class="text-danger small mt-3">{{ $message }}</div>
                        @enderror

                        @if ($type === 'product')
                            @error('gallery_files.*')
                                <div class="text-danger small mt-3">{{ $message }}</div>
                            @enderror

                            <div class="mt-3 d-flex flex-column gap-3">
                                <div class="border rounded p-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="fw-semibold">Anh phu</div>
                                        <button
                                            type="button"
                                            class="btn btn-light border rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 gallery-picker-trigger gallery-picker-button"
                                            data-gallery-type="gallery"
                                        >
                                            <i class="ri-add-line gallery-picker-icon"></i>
                                        </button>
                                    </div>
                                    <div id="gallery-preview-grid-gallery" data-gallery-grid="gallery" class="d-flex align-items-start gap-2 flex-wrap">
                                        @foreach ($existingGalleryImages as $galleryImage)
                                            <div class="position-relative border rounded overflow-hidden bg-light gallery-item gallery-item-thumb" data-gallery-item>
                                                <img src="{{ \App\Support\MediaManager::publicUrl($galleryImage->image) }}" alt="Gallery" class="w-100 h-100 object-fit-cover">
                                                <button
                                                    type="button"
                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle d-flex align-items-center justify-content-center remove-gallery-item gallery-remove-button"
                                                    data-existing-id="{{ $galleryImage->id }}"
                                                    data-gallery-type="gallery"
                                                >x</button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-text mt-0">Anh phu se duoc nen truoc khi upload.</div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $post->is_active ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Hien thi {{ strtolower($typeLabel) }}</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="quickAddOptionModal" tabindex="-1" aria-labelledby="quickAddOptionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickAddOptionModalLabel">Thêm tùy chọn mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="quickAddOptionName" class="form-label">Tên tùy chọn</label>
                    <input type="text" class="form-control" id="quickAddOptionName" placeholder="Nhập tên tùy chọn...">
                    <input type="hidden" id="quickAddOptionGroup">
                    <input type="hidden" id="quickAddOptionTarget">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="btnQuickAddOptionSave">Lưu</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="quickAddCategoryModal" tabindex="-1" aria-labelledby="quickAddCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickAddCategoryModalLabel">Thêm danh mục mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="quickAddCategoryParent" class="form-label">Danh mục cha</label>
                    <select class="form-select" id="quickAddCategoryParent">
                        <option value="">Không chọn</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="quickAddCategoryName" class="form-label">Tên danh mục</label>
                    <input type="text" class="form-control" id="quickAddCategoryName" placeholder="Nhập tên danh mục...">
                    <input type="hidden" id="quickAddCategoryType">
                    <input type="hidden" id="quickAddCategoryTarget">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="btnQuickAddCategorySave">Lưu</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#transport').select2({
            placeholder: "Chọn phương tiện"
        });
        $('#departure_location').select2({
            placeholder: "Chọn địa điểm khởi hành"
        });

        let quickAddModal = new bootstrap.Modal(document.getElementById('quickAddOptionModal'));
        
        $('#quickAddOptionModal').on('shown.bs.modal', function () {
            $('#quickAddOptionName').focus();
        });

        $('#quickAddOptionName').on('keypress', function (e) {
            if (e.which === 13) {
                $('#btnQuickAddOptionSave').click();
            }
        });

        $('.js-quick-add-tour-option').on('click', function() {
            let btn = $(this);
            let group = btn.data('group');
            let targetSelect = btn.data('target');
            
            $('#quickAddOptionGroup').val(group);
            $('#quickAddOptionTarget').val(targetSelect);
            $('#quickAddOptionName').val('');
            
            quickAddModal.show();
        });

        $('#btnQuickAddOptionSave').on('click', function() {
            let name = $('#quickAddOptionName').val().trim();
            if (!name) {
                alert('Vui lòng nhập tên tùy chọn mới.');
                return;
            }
            
            let group = $('#quickAddOptionGroup').val();
            let targetSelect = $($('#quickAddOptionTarget').val());
            let urlBase = $('#backend-content-form').data('url-base');
            
            let btn = $(this);
            let originalHtml = btn.html();
            
            btn.prop('disabled', true).html('<i class="ri-loader-line ri-spin"></i> Đang lưu...');
            
            $.ajax({
                url: urlBase + '/admin/tour-options/quick-store',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    group_key: group,
                    name: name
                },
                success: function(res) {
                    btn.prop('disabled', false).html(originalHtml);
                    if (res.success) {
                        let newOption = new Option(res.data.label, res.data.value, true, true);
                        targetSelect.append(newOption).trigger('change');
                        quickAddModal.hide();
                    }
                },
                error: function(err) {
                    btn.prop('disabled', false).html(originalHtml);
                    let msg = 'Có lỗi xảy ra.';
                    if (err.responseJSON && err.responseJSON.message) {
                        msg = err.responseJSON.message;
                    }
                    alert(msg);
                }
            });
        });
        
        $('.js-reload-tour-option').on('click', function() {
            let btn = $(this);
            let group = btn.data('group');
            let targetSelect = $(btn.data('target'));
            let urlBase = $('#backend-content-form').data('url-base');
            let currentValue = targetSelect.val();
            let firstOptionText = targetSelect.find('option:first').text();
            let originalHtml = btn.html();
            
            btn.prop('disabled', true).html('<i class="ri-loader-line ri-spin"></i>');
            
            $.ajax({
                url: urlBase + '/admin/tour-options/options',
                method: 'GET',
                data: { group_key: group },
                success: function(res) {
                    btn.prop('disabled', false).html(originalHtml);
                    if (res.success) {
                        targetSelect.empty();
                        targetSelect.append(new Option(firstOptionText, '', false, false));
                        res.data.forEach(function(item) {
                            let selected = (item.value === currentValue);
                            targetSelect.append(new Option(item.label, item.value, selected, selected));
                        });
                        targetSelect.trigger('change');
                    }
                },
                error: function(err) {
                    btn.prop('disabled', false).html(originalHtml);
                    alert('Có lỗi xảy ra khi tải lại danh sách.');
                }
            });
        });

        let quickAddCatModal = new bootstrap.Modal(document.getElementById('quickAddCategoryModal'));
        
        $('#quickAddCategoryModal').on('shown.bs.modal', function () {
            $('#quickAddCategoryName').focus();
        });

        $('#quickAddCategoryName').on('keypress', function (e) {
            if (e.which === 13) {
                $('#btnQuickAddCategorySave').click();
            }
        });

        $('.js-quick-add-category').on('click', function() {
            let btn = $(this);
            let type = btn.data('type');
            let targetSelect = $(btn.data('target'));
            
            $('#quickAddCategoryType').val(type);
            $('#quickAddCategoryTarget').val(btn.data('target'));
            $('#quickAddCategoryName').val('');
            
            let parentSelect = $('#quickAddCategoryParent');
            parentSelect.empty();
            parentSelect.append(new Option('Không chọn', ''));
            targetSelect.find('option').each(function() {
                if ($(this).val() !== '') {
                    parentSelect.append(new Option($(this).text(), $(this).val()));
                }
            });
            parentSelect.val('');
            
            quickAddCatModal.show();
        });

        $('#btnQuickAddCategorySave').on('click', function() {
            let name = $('#quickAddCategoryName').val().trim();
            if (!name) {
                alert('Vui lòng nhập tên danh mục mới.');
                return;
            }
            
            let type = $('#quickAddCategoryType').val();
            let parent_id = $('#quickAddCategoryParent').val();
            let targetSelectSelector = $('#quickAddCategoryTarget').val();
            let targetSelect = $(targetSelectSelector);
            let urlBase = $('#backend-content-form').data('url-base');
            
            let btn = $(this);
            let originalHtml = btn.html();
            
            btn.prop('disabled', true).html('<i class="ri-loader-line ri-spin"></i> Đang lưu...');
            
            $.ajax({
                url: urlBase + '/admin/categories/quick-store',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    type: type,
                    name: name,
                    parent_id: parent_id
                },
                success: function(res) {
                    btn.prop('disabled', false).html(originalHtml);
                    if (res.success) {
                        quickAddCatModal.hide();
                        $('[data-target="' + targetSelectSelector + '"].js-reload-category').trigger('click', [res.data.value]);
                    }
                },
                error: function(err) {
                    btn.prop('disabled', false).html(originalHtml);
                    let msg = 'Có lỗi xảy ra.';
                    if (err.responseJSON && err.responseJSON.message) {
                        msg = err.responseJSON.message;
                    }
                    alert(msg);
                }
            });
        });
        
        $('.js-reload-category').on('click', function(e, newValueToSelect) {
            let btn = $(this);
            let type = btn.data('type');
            let targetSelect = $(btn.data('target'));
            let urlBase = $('#backend-content-form').data('url-base');
            let currentValue = newValueToSelect !== undefined ? newValueToSelect : targetSelect.val();
            let firstOptionText = targetSelect.find('option:first').text();
            let originalHtml = btn.html();
            
            btn.prop('disabled', true).html('<i class="ri-loader-line ri-spin"></i>');
            
            $.ajax({
                url: urlBase + '/admin/categories/options',
                method: 'GET',
                data: { type: type },
                success: function(res) {
                    btn.prop('disabled', false).html(originalHtml);
                    if (res.success) {
                        targetSelect.empty();
                        targetSelect.append(new Option(firstOptionText, '', false, false));
                        res.data.forEach(function(item) {
                            let selected = (item.value == currentValue);
                            targetSelect.append(new Option(item.label, item.value, selected, selected));
                        });
                        targetSelect.trigger('change');
                    }
                },
                error: function(err) {
                    btn.prop('disabled', false).html(originalHtml);
                    alert('Có lỗi xảy ra khi tải lại danh sách.');
                }
            });
        });
    });
</script>
@endpush
