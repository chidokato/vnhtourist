@php
    if (!function_exists('isMenuItemActive')) {
        function isMenuItemActive($url, $slug) {
            $normalizedCurrent = rtrim(url()->current(), '/');
            $normalizedMenu = rtrim(url($url), '/');
            if ($normalizedMenu === rtrim(url('/'), '/')) {
                return request()->is('/');
            }
            $active = ($normalizedCurrent === $normalizedMenu) || request()->fullUrlIs($normalizedMenu . '/*');
            if (!$active && !empty($slug)) {
                $pathToCheck = ltrim($slug, '/');
                $active = request()->is($pathToCheck) || request()->is($pathToCheck . '/*');
            }
            return $active;
        }
    }

    $menuUrl = $menu->resolved_url ?? url($menu->slug ? '/' . ltrim($menu->slug, '/') : '/');
    $children = $menu->children_tree ?? collect();
    $hasChildren = $children->isNotEmpty();
    $isRoot = ($level ?? 0) === 0;
    
    $isActive = isMenuItemActive($menuUrl, $menu->slug);

    $hasGrandChildren = $children->contains(function ($child) {
        return ($child->children_tree ?? collect())->isNotEmpty();
    });
    $useMegaMenu = $isRoot && $hasChildren && $hasGrandChildren;

    $megaColumns = collect();

    if ($useMegaMenu) {
        if ($hasGrandChildren) {
            $megaColumns = $children->map(function ($child) {
                return [
                    'parent' => $child,
                    'children' => $child->children_tree ?? collect(),
                ];
            })->filter(function ($column) {
                return ($column['parent'] ?? null) !== null;
            })->values();
        } else {
            $columnCount = min(4, max(1, $children->count()));
            $chunkSize = (int) ceil($children->count() / $columnCount);
            $megaColumns = $children->chunk(max(1, $chunkSize))->map(function ($items) {
                return [
                    'parent' => null,
                    'children' => $items,
                ];
            })->values();
        }
    }

    $columnClass = match (max(1, $megaColumns->count())) {
        1 => 'col-lg-12',
        2 => 'col-lg-6',
        3 => 'col-lg-4',
        default => 'col-lg-3',
    };

    $megaColumnCount = max(1, $megaColumns->count());
@endphp

@if ($useMegaMenu)
    <!-- Desktop Mega Menu Item -->
    <li class="{{ $isRoot ? 'nav-item' : '' }} {{ $hasChildren ? ($isRoot ? 'dropdown' : 'dropdown-submenu') : '' }} dropdown-mega d-none d-lg-block">
        <a
            class="{{ $isRoot ? 'nav-link' : 'dropdown-item' }} {{ $hasChildren ? 'dropdown-toggle' : '' }} {{ $isActive ? 'active' : '' }}"
            href="{{ $menuUrl }}"
            @if ($hasChildren && $isRoot)
                data-bs-toggle="dropdown"
            @endif
            @if ($menu->target === '_blank')
                target="_blank" rel="noopener noreferrer"
            @endif
        >
            {{ $menu->name }}
        </a>

        <div class="dropdown-menu mega-menu mega-menu-cols-{{ $megaColumnCount }} fade-down">
            <div class="row g-0">
                @foreach ($megaColumns as $column)
                    <div class="{{ $columnClass }}">
                        @php
                            $columnParent = $column['parent'] ?? null;
                            $columnChildren = $column['children'] ?? collect();
                            $parentUrl = $columnParent?->resolved_url ?? ($columnParent ? url($columnParent->slug ? '/' . ltrim($columnParent->slug, '/') : '/') : '#');
                            $parentActive = $columnParent ? isMenuItemActive($parentUrl, $columnParent->slug) : false;
                        @endphp

                        @if ($columnParent)
                            <div class="mega-menu-parent">
                                @if ($parentUrl !== '#')
                                    <a
                                        class="mega-menu-parent-link {{ $parentActive ? 'active' : '' }}"
                                        href="{{ $parentUrl }}"
                                        @if (($columnParent->target ?? '_self') === '_blank')
                                            target="_blank" rel="noopener noreferrer"
                                        @endif
                                    >
                                        {{ $columnParent->name }}
                                    </a>
                                @else
                                    <span class="mega-menu-parent-link">
                                        {{ $columnParent->name }}
                                    </span>
                                @endif
                            </div>
                        @endif

                        <ul class="mega-menu-children">
                            @foreach ($columnChildren as $item)
                                @php
                                    $itemUrl = $item->resolved_url ?? url($item->slug ? '/' . ltrim($item->slug, '/') : '/');
                                    $itemActive = isMenuItemActive($itemUrl, $item->slug);
                                @endphp
                                <li>
                                    <a
                                        class="dropdown-item {{ $itemActive ? 'active' : '' }}"
                                        href="{{ $itemUrl }}"
                                        @if (($item->target ?? '_self') === '_blank')
                                            target="_blank" rel="noopener noreferrer"
                                        @endif
                                    >
                                        {{ $item->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </li>

    <!-- Mobile Recursive Menu Item -->
    <li class="{{ $isRoot ? 'nav-item' : '' }} {{ $hasChildren ? ($isRoot ? 'dropdown' : 'dropdown-submenu') : '' }} d-block d-lg-none">
        <a
            class="{{ $isRoot ? 'nav-link' : 'dropdown-item' }} {{ $hasChildren ? 'dropdown-toggle' : '' }} {{ $isActive ? 'active' : '' }}"
            href="{{ $menuUrl }}"
            @if ($hasChildren && $isRoot)
                data-bs-toggle="dropdown"
            @endif
            @if ($menu->target === '_blank')
                target="_blank" rel="noopener noreferrer"
            @endif
        >
            {{ $menu->name }}
        </a>

        <ul class="dropdown-menu fade-down">
            @foreach ($children as $child)
                @include('frontend.partials.tourit-menu-item', ['menu' => $child, 'level' => ($level ?? 0) + 1])
            @endforeach
        </ul>
    </li>
@else
    <!-- Standard Menu Item -->
    <li class="{{ $isRoot ? 'nav-item' : '' }} {{ $hasChildren ? ($isRoot ? 'dropdown' : 'dropdown-submenu') : '' }}">
        <a
            class="{{ $isRoot ? 'nav-link' : 'dropdown-item' }} {{ $hasChildren ? 'dropdown-toggle' : '' }} {{ $isActive ? 'active' : '' }}"
            href="{{ $menuUrl }}"
            @if ($hasChildren && $isRoot)
                data-bs-toggle="dropdown"
            @endif
            @if ($menu->target === '_blank')
                target="_blank" rel="noopener noreferrer"
            @endif
        >
            {{ $menu->name }}
        </a>

        @if ($hasChildren)
            <ul class="dropdown-menu fade-down">
                @foreach ($children as $child)
                    @include('frontend.partials.tourit-menu-item', ['menu' => $child, 'level' => ($level ?? 0) + 1])
                @endforeach
            </ul>
        @endif
    </li>
@endif
