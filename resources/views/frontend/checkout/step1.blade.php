@extends('frontend.layouts.app')

@section('title', 'Đặt tour: ' . $product->title)

@push('styles')
<link rel="stylesheet" href="{{ asset('tourit/assets/css/checkout.css') }}">
@endpush

@section('content')
<main class="main bg-light py-5 pages-category">
    <div class="container">
        <form action="{{ route('frontend.checkout.processStep1', $product->id) }}" method="POST" id="step1-form">
            @csrf
            
            @php
                // Pre-calculate prices
                $adultPrice = $product->custom_price ?: $product->price ?: 0;
                $childPrice = $product->child_price_percent ? ($adultPrice * $product->child_price_percent / 100) : 0;
                $infantPrice = $product->infant_price_percent ? ($adultPrice * $product->infant_price_percent / 100) : 0;
                
                // Get default date
                $defaultDate = request('departure_date');
                if (!$defaultDate) {
                    if ($product->departurePrices && $product->departurePrices->isNotEmpty()) {
                        $defaultDate = $product->departurePrices->first()->departure_date;
                    } else {
                        $defaultDate = $product->meta['departure_date'] ?? 'Đang cập nhật';
                    }
                }
            @endphp
            
            <!-- Hidden inputs to store selections -->
            <input type="hidden" name="departure_date" id="input_departure_date" value="{{ $defaultDate }}">
            
            <div class="row">
                <div class="col-lg-8">
                    <!-- Khởi hành -->
                    <div class="booking-step-block">
                        <div class="booking-step-title">
                            <span class="booking-step-number">1</span>
                            Chọn ngày khởi hành
                        </div>
                        <div class="date-selector">
                            @if ($product->departurePrices && $product->departurePrices->isNotEmpty())
                                @foreach ($product->departurePrices as $index => $dp)
                                    @php
                                        $dateParts = explode('/', $dp->departure_date);
                                        $shortDate = count($dateParts) >= 2 ? $dateParts[0] . '/' . $dateParts[1] : $dp->departure_date;
                                        $rawPrice = $dp->price ?: $adultPrice;
                                        $displayPrice = $rawPrice > 0 ? ($rawPrice < 1000 ? $rawPrice : $rawPrice / 1000000) : 0;
                                        $realPrice = $rawPrice > 0 ? ($rawPrice < 1000 ? $rawPrice * 1000000 : $rawPrice) : 0;
                                        
                                        $isActive = $dp->departure_date == $defaultDate;
                                        if ($isActive && $realPrice > 0) {
                                            $adultPrice = $realPrice;
                                            $childPrice = $product->child_price_percent ? ($adultPrice * $product->child_price_percent / 100) : 0;
                                            $infantPrice = $product->infant_price_percent ? ($adultPrice * $product->infant_price_percent / 100) : 0;
                                        }
                                    @endphp
                                    <div class="date-item {{ $isActive ? 'active' : '' }}" onclick="selectDate(this, '{{ $dp->departure_date }}', {{ $realPrice }})">
                                        <div class="date">{{ $shortDate }}</div>
                                        <div class="price">{{ $displayPrice > 0 ? rtrim(rtrim(number_format($displayPrice, 1, ',', '.'), '0'), ',') . 'tr' : 'Liên hệ' }}</div>
                                    </div>
                                @endforeach
                            @else
                                <div class="date-item active" onclick="selectDate(this, '{{ $defaultDate }}', {{ $adultPrice }})">
                                    <div class="date">{{ $defaultDate }}</div>
                                    <div class="price">{{ $adultPrice > 0 ? number_format($adultPrice) . 'đ' : 'Liên hệ' }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Hành khách -->
                    <div class="booking-step-block">
                        <div class="booking-step-title">
                            <span class="booking-step-number">2</span>
                            Số lượng hành khách
                        </div>
                        
                        <!-- Người lớn -->
                        <div class="qty-row">
                            <div class="qty-info">
                                <h6>Người lớn</h6>
                                <p>Từ 12 tuổi trở lên</p>
                                <div class="price" id="adult_price_display">{{ number_format($adultPrice) }}đ / người</div>
                            </div>
                            <div class="qty-controls">
                                <button type="button" class="qty-btn" onclick="updateQty('adult', -1)">-</button>
                                <input type="text" name="adult_quantity" id="qty_adult" class="qty-input" value="{{ request('adult_quantity', 1) }}" readonly>
                                <button type="button" class="qty-btn" onclick="updateQty('adult', 1)">+</button>
                            </div>
                        </div>

                        <!-- Trẻ em -->
                        @if ($childPrice > 0)
                        <div class="qty-row">
                            <div class="qty-info">
                                <h6>Trẻ em</h6>
                                <p>Từ 2–11 tuổi</p>
                                <div class="price">{{ number_format($childPrice) }}đ / trẻ</div>
                            </div>
                            <div class="qty-controls">
                                <button type="button" class="qty-btn" onclick="updateQty('child', -1)">-</button>
                                <input type="text" name="child_quantity" id="qty_child" class="qty-input" value="{{ request('child_quantity', 0) }}" readonly>
                                <button type="button" class="qty-btn" onclick="updateQty('child', 1)">+</button>
                            </div>
                        </div>
                        @else
                        <input type="hidden" name="child_quantity" id="qty_child" value="0">
                        @endif

                        <!-- Em bé -->
                        @if ($infantPrice > 0)
                        <div class="qty-row">
                            <div class="qty-info">
                                <h6>Em bé</h6>
                                <p>Dưới 2 tuổi</p>
                                <div class="price">{{ number_format($infantPrice) }}đ / bé</div>
                            </div>
                            <div class="qty-controls">
                                <button type="button" class="qty-btn" onclick="updateQty('infant', -1)">-</button>
                                <input type="text" name="infant_quantity" id="qty_infant" class="qty-input" value="{{ request('infant_quantity', 0) }}" readonly>
                                <button type="button" class="qty-btn" onclick="updateQty('infant', 1)">+</button>
                            </div>
                        </div>
                        @else
                        <input type="hidden" name="infant_quantity" id="qty_infant" value="0">
                        @endif
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4 mb-4">
                        @if(auth()->check())
                            <button type="submit" class="theme-btn d-flex justify-content-center align-items-center gap-2" style="padding: 12px 24px; font-size: 16px; font-weight: 600; min-width: 200px; border-radius: 8px;">
                                Tiếp tục: Thông tin khách
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        @else
                            <a href="{{ route('frontend.login', ['redirect' => url()->current()]) }}" class="theme-btn d-flex justify-content-center align-items-center gap-2" style="padding: 12px 24px; font-size: 16px; font-weight: 600; min-width: 200px; border-radius: 8px;">
                                Tiếp tục: Thông tin khách
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="summary-card">
                        <div class="summary-img">
                            <img src="{{ $product->image ? \App\Support\MediaManager::publicUrl($product->image) : asset('tourit/assets/img/hotel/room/01.jpg') }}" alt="{{ $product->title }}">
                        </div>
                        <div class="summary-content">
                            <h4 class="summary-title">{{ $product->title }}</h4>
                            
                            <ul class="summary-info-list">
                                <li><span>Mã tour:</span> <span>{{ $product->tour_code ?? 'Đang cập nhật' }}</span></li>
                                <li><span>Thời gian:</span> <span>{{ $product->duration ?? 'Đang cập nhật' }}</span></li>
                                <li><span>Phương tiện:</span> <span class="text-end" style="max-width: 60%;">{{ $product->transport ?? 'Đang cập nhật' }}</span></li>
                                <li><span>Ngày đi:</span> <span id="summary_date">{{ $defaultDate }}</span></li>
                            </ul>

                            <div class="summary-divider"></div>
                            
                            <p class="text-muted mb-2" style="font-size: 12px; font-weight: 700; text-transform: uppercase;">Chi tiết giá</p>
                            <ul class="price-breakdown" id="price_breakdown">
                                <!-- Rendered by JS -->
                            </ul>

                            <div class="total-row">
                                <span>Tổng cộng</span>
                                <strong id="summary_total">0 đ</strong>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>
@endsection

@push('scripts')
<script>
    let prices = {
        adult: {{ (int)$adultPrice }},
        child: {{ (int)$childPrice }},
        infant: {{ (int)$infantPrice }}
    };

    function selectDate(element, dateStr, price) {
        document.querySelectorAll('.date-item').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
        document.getElementById('input_departure_date').value = dateStr;
        document.getElementById('summary_date').innerText = dateStr;
        
        if (price !== undefined && price > 0) {
            prices.adult = price;
            // Update child/infant prices dynamically based on percentage if they exist
            @if ($product->child_price_percent)
                prices.child = price * {{ $product->child_price_percent }} / 100;
            @endif
            @if ($product->infant_price_percent)
                prices.infant = price * {{ $product->infant_price_percent }} / 100;
            @endif
            
            document.getElementById('adult_price_display').innerText = formatCurrency(prices.adult) + ' / người';
        }
        calculateTotal();
    }

    function updateQty(type, change) {
        const input = document.getElementById('qty_' + type);
        let val = parseInt(input.value) + change;
        
        // Validation
        if(type === 'adult' && val < 1) val = 1; // At least 1 adult
        if((type === 'child' || type === 'infant') && val < 0) val = 0;
        
        input.value = val;
        calculateTotal();
    }

    function formatCurrency(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " ₫";
    }

    function calculateTotal() {
        const adultQty = parseInt(document.getElementById('qty_adult').value);
        const childQty = parseInt(document.getElementById('qty_child').value);
        const infantQty = parseInt(document.getElementById('qty_infant').value);

        let breakdownHtml = '';
        let total = 0;

        if(adultQty > 0) {
            const sub = adultQty * prices.adult;
            total += sub;
            breakdownHtml += `<li><span>Người lớn x ${adultQty}</span> <span>${formatCurrency(sub)}</span></li>`;
        }
        if(childQty > 0) {
            const sub = childQty * prices.child;
            total += sub;
            breakdownHtml += `<li><span>Trẻ em x ${childQty}</span> <span>${formatCurrency(sub)}</span></li>`;
        }
        if(infantQty > 0) {
            const sub = infantQty * prices.infant;
            total += sub;
            breakdownHtml += `<li><span>Em bé x ${infantQty}</span> <span>${formatCurrency(sub)}</span></li>`;
        }

        document.getElementById('price_breakdown').innerHTML = breakdownHtml;
        document.getElementById('summary_total').innerText = formatCurrency(total);
    }

    // Init
    document.addEventListener('DOMContentLoaded', calculateTotal);
</script>
@endpush
