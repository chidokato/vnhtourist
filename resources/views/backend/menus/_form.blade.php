@csrf

<div class="row">
    <div class="col-xl-9">
        <div class="card border">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="mb-0">
                            <label for="category_id" class="form-label">Lay tu category</label>
                            <select id="category_id" class="form-select">
                                <option value="">Chon category de dien ten menu</option>
                                @foreach (($categoryOptions ?? []) as $groupLabel => $categories)
                                    <optgroup label="{{ $groupLabel }}">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category['id'] }}" data-name="{{ $category['plain_name'] }}">{{ $category['name'] }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <div class="form-text">Khi chon category, ten menu se tu dong dien theo category da chon.</div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3 mb-lg-0">
                            <label for="name" class="form-label">Ten menu</label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $menu->name ?? '') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="mb-0">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $menu->slug ?? '') }}">
                            <div class="form-text">Co the sua slug thu cong. Neu de trong, he thong se tu dong tao theo ten menu.</div>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                    <label for="parent_id" class="form-label">Menu cha</label>
                    <select id="parent_id" name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                        <option value="">Khong co</option>
                        @foreach ($parentOptions as $id => $label)
                            <option value="{{ $id }}" {{ (string) old('parent_id', $menu->parent_id ?? '') === (string) $id ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="target" class="form-label">Target</label>
                    <select id="target" name="target" class="form-select @error('target') is-invalid @enderror">
                        <option value="_self" {{ old('target', $menu->target ?? '_self') === '_self' ? 'selected' : '' }}>Cung tab (_self)</option>
                        <option value="_blank" {{ old('target', $menu->target ?? '_self') === '_blank' ? 'selected' : '' }}>Tab moi (_blank)</option>
                    </select>
                    @error('target')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="sort_order" class="form-label">Thu tu</label>
                    <input type="number" min="0" id="sort_order" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $menu->sort_order ?? 0) }}">
                    @error('sort_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $menu->is_active ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Hien thi menu</label>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var categorySelect = document.getElementById('category_id');
        var nameInput = document.getElementById('name');
        var slugInput = document.getElementById('slug');
        var slugTouched = Boolean(slugInput && slugInput.value.trim() !== '');

        if (!nameInput || !slugInput) {
            return;
        }

        function slugify(value) {
            return value
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/đ/g, 'd')
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        function updateSlug() {
            if (slugTouched) {
                return;
            }

            slugInput.value = slugify(nameInput.value);
        }

        if (categorySelect) {
            categorySelect.addEventListener('change', function () {
                var selectedOption = categorySelect.options[categorySelect.selectedIndex];
                var selectedName = selectedOption ? selectedOption.getAttribute('data-name') : '';

                if (!selectedName) {
                    return;
                }

                nameInput.value = selectedName;
                updateSlug();
            });
        }

        nameInput.addEventListener('input', updateSlug);
        slugInput.addEventListener('input', function () {
            slugTouched = slugInput.value.trim() !== '';
        });
        updateSlug();
    });
</script>
