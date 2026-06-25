document.addEventListener('DOMContentLoaded', function() {
    const minusBtns = document.querySelectorAll('.js-qty-minus');
    const plusBtns = document.querySelectorAll('.js-qty-plus');
    const summaryList = document.querySelector('.js-booking-summary-list');
    const summaryTotal = document.querySelector('.js-booking-total');
    
    // New variables for date buttons and pricing
    const dateBtns = document.querySelectorAll('.booking-date-btn');
    const sidebarWrapper = document.querySelector('.booking-sidebar-new');
    
    const adultDisplay = document.querySelector('.js-adult-price-display');
    const childDisplay = document.querySelector('.js-child-price-display');
    const infantDisplay = document.querySelector('.js-infant-price-display');
    const mainPriceDisplay = document.querySelector('.js-main-price-display');
    
    const adultInput = document.querySelector('.js-adult-input');
    const childInput = document.querySelector('.js-child-input');
    const infantInput = document.querySelector('.js-infant-input');

    function formatVND(amount) {
        if (amount > 0) {
            return new Intl.NumberFormat('vi-VN').format(amount) + ' ₫';
        }
        return 'Liên hệ';
    }

    function updateSummary() {
        let total = 0;
        let summaryHtml = '';
        
        document.querySelectorAll('.js-qty-input').forEach(input => {
            const qty = parseInt(input.value) || 0;
            if (qty > 0) {
                const price = parseFloat(input.dataset.price) || 0;
                const subtotal = qty * price;
                total += subtotal;
                
                if (price > 0) {
                    summaryHtml += `
                        <div class="d-flex justify-content-between mb-1">
                            <span>${input.dataset.label} x ${qty}</span>
                            <strong class="booking-summary-item-price">${new Intl.NumberFormat('vi-VN').format(subtotal)} ₫</strong>
                        </div>
                    `;
                } else {
                    summaryHtml += `
                        <div class="d-flex justify-content-between mb-1">
                            <span>${input.dataset.label} x ${qty}</span>
                            <strong class="booking-summary-item-price">Liên hệ</strong>
                        </div>
                    `;
                }
            }
        });
        
        if (summaryList) summaryList.innerHTML = summaryHtml;
        if (summaryTotal) {
            if (total > 0) {
                summaryTotal.innerHTML = new Intl.NumberFormat('vi-VN').format(total) + ' ₫';
            } else {
                summaryTotal.innerHTML = 'Liên hệ';
            }
        }
    }

    // Handle date button click
    if (dateBtns.length > 0 && sidebarWrapper) {
        const childPercent = parseFloat(sidebarWrapper.dataset.childPercent) || 0;
        const infantPercent = parseFloat(sidebarWrapper.dataset.infantPercent) || 0;
        
        dateBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // remove active class from all
                dateBtns.forEach(b => b.classList.remove('active'));
                // add active class to clicked
                this.classList.add('active');
                
                const basePrice = parseFloat(this.dataset.price) || 0;
                const childPrice = basePrice * (childPercent / 100);
                const infantPrice = basePrice * (infantPercent / 100);
                
                // update inputs dataset
                if (adultInput) adultInput.dataset.price = basePrice;
                if (childInput) childInput.dataset.price = childPrice;
                if (infantInput) infantInput.dataset.price = infantPrice;
                
                // update UI labels
                if (adultDisplay) adultDisplay.textContent = formatVND(basePrice);
                if (childDisplay) childDisplay.textContent = formatVND(childPrice);
                if (infantDisplay) infantDisplay.textContent = formatVND(infantPrice);
                if (mainPriceDisplay) mainPriceDisplay.textContent = formatVND(basePrice);
                
                // refresh summary
                updateSummary();
            });
        });
    }

    plusBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.previousElementSibling;
            if (input) {
                input.value = parseInt(input.value) + 1;
                updateSummary();
            }
        });
    });

    minusBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.nextElementSibling;
            if (input) {
                const min = input.dataset.label === 'Người lớn' ? 1 : 0;
                if (parseInt(input.value) > min) {
                    input.value = parseInt(input.value) - 1;
                    updateSummary();
                }
            }
        });
    });

    // Mobile popup logic
    const mobileOpenBtn = document.querySelector('.js-mobile-booking-open');
    const mobileCloseBtn = document.querySelector('.js-mobile-booking-close');
    const mobileOverlay = document.querySelector('.js-mobile-booking-overlay');
    const sidebarElement = document.querySelector('.js-booking-sidebar');

    function openMobileBooking() {
        if (sidebarElement) sidebarElement.classList.add('active');
        if (mobileOverlay) mobileOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileBooking() {
        if (sidebarElement) sidebarElement.classList.remove('active');
        if (mobileOverlay) mobileOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (mobileOpenBtn) {
        mobileOpenBtn.addEventListener('click', openMobileBooking);
    }
    if (mobileCloseBtn) {
        mobileCloseBtn.addEventListener('click', closeMobileBooking);
    }
    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', closeMobileBooking);
    }
});
