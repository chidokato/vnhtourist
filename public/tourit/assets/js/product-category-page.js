(function () {
    'use strict';

    var form = document.querySelector('[data-product-filter]');
    var results = document.querySelector('[data-product-results]');
    var status = document.querySelector('[data-product-results-status]');

    if (!form || !results) {
        return;
    }

    var destinationOptions = form.querySelector('[data-destination-options]');
    var transportOptions = form.querySelector('[data-transport-options]');
    var resetButton = form.querySelector('[data-filter-reset]');
    var ajaxUrl = form.getAttribute('data-ajax-url');
    var categorySelect = form.querySelector('[data-filter-category-select]');
    var select2CssSources = [
        'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css',
        'https://unpkg.com/select2@4.1.0-rc.0/dist/css/select2.min.css'
    ];
    var select2JsSources = [
        'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js',
        'https://unpkg.com/select2@4.1.0-rc.0/dist/js/select2.min.js'
    ];

    if (!ajaxUrl) {
        return;
    }

    function ensureSelect2Styles() {
        var hasSelect2Styles = Array.prototype.some.call(document.querySelectorAll('link[rel="stylesheet"]'), function (link) {
            var href = link.getAttribute('href') || '';
            return href.indexOf('select2') !== -1;
        });

        if (hasSelect2Styles) {
            return;
        }

        select2CssSources.forEach(function (href) {
            var link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = href;
            document.head.appendChild(link);
        });
    }

    function applyCategorySelect2() {
        if (!categorySelect || typeof window.jQuery === 'undefined' || typeof window.jQuery.fn.select2 !== 'function') {
            return false;
        }

        var $categorySelect = window.jQuery(categorySelect);

        if ($categorySelect.hasClass('select2-hidden-accessible')) {
            $categorySelect.select2('destroy');
        }

        $categorySelect.select2({
            width: '100%',
            minimumResultsForSearch: 0,
            dropdownAutoWidth: true,
            dropdownCssClass: 'product-filter-select2-dropdown'
        });

        return true;
    }

    function loadScriptSequentially(sources, onComplete) {
        var index = 0;

        function tryNext() {
            if (index >= sources.length) {
                if (typeof onComplete === 'function') {
                    onComplete(false);
                }

                return;
            }

            var script = document.createElement('script');
            script.src = sources[index];
            script.async = true;
            script.onload = function () {
                if (typeof onComplete === 'function') {
                    onComplete(true);
                }
            };
            script.onerror = function () {
                index += 1;
                tryNext();
            };
            document.body.appendChild(script);
        }

        tryNext();
    }

    function initCategorySelect() {
        if (!categorySelect || typeof window.jQuery === 'undefined') {
            return;
        }

        ensureSelect2Styles();

        if (applyCategorySelect2()) {
            return;
        }

        loadScriptSequentially(select2JsSources, function () {
            applyCategorySelect2();
        });
    }

    function buildQuery(page) {
        var formData = new FormData(form);
        var params = new URLSearchParams();

        formData.forEach(function (value, key) {
            if (value) {
                params.append(key, value);
            }
        });

        if (page && page > 1) {
            params.set('page', String(page));
        }

        return params;
    }

    function setLoading(isLoading) {
        results.classList.toggle('is-loading', isLoading);
    }

    function updateStatusFromResults(total) {
        if (!status) {
            return;
        }

        if (typeof total === 'number') {
            status.textContent = total > 0 ? total + ' tour phu hop' : 'Khong co tour phu hop';
            return;
        }

        var cards = results.querySelectorAll('.tour-listing-card').length;
        status.textContent = cards > 0 ? cards + ' tour hiển thị' : 'Không có tour phù hợp';
    }

    function replaceCheckboxOptions(container, inputName, options, selectedValues) {
        if (!container) {
            return;
        }

        var fragment = document.createDocumentFragment();
        var selectedMap = new Set(Array.isArray(selectedValues) ? selectedValues : []);

        (options || []).forEach(function (option) {
            var optionName = typeof option === 'string' ? option : option.name;
            var optionLabel = typeof option === 'string' ? option : (option.label || option.name);
            var optionCount = typeof option === 'string' ? null : option.count;
            var item = document.createElement('label');
            item.className = 'product-filter-option product-filter-option-checkbox';
            item.innerHTML = ''
                + '<input type="checkbox">'
                + '<span class="product-filter-option-label"></span>'
                + '<span class="product-filter-option-count"></span>';

            item.querySelector('input').name = inputName;
            item.querySelector('input').value = optionName;
            item.querySelector('input').checked = selectedMap.has(optionName);
            item.querySelector('.product-filter-option-label').textContent = optionLabel;
            item.querySelector('.product-filter-option-count').textContent = optionCount === null || typeof optionCount === 'undefined'
                ? ''
                : String(optionCount);
            fragment.appendChild(item);
        });

        container.innerHTML = '';
        container.appendChild(fragment);
    }

    function syncUrl(params) {
        var nextUrl = window.location.pathname;
        var query = params.toString();

        if (query) {
            nextUrl += '?' + query;
        }

        window.history.replaceState({}, '', nextUrl);
    }

    function fetchResults(page) {
        var params = buildQuery(page);

        setLoading(true);

        fetch(ajaxUrl + '?' + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Request failed');
                }

                return response.json();
            })
            .then(function (payload) {
                results.innerHTML = payload.html || '';
                replaceCheckboxOptions(destinationOptions, 'destinations[]', payload.destinationOptions || [], payload.selectedDestinations || []);
                replaceCheckboxOptions(transportOptions, 'transports[]', payload.transportOptions || [], payload.selectedTransports || []);
                updateStatusFromResults(payload.total);
                syncUrl(params);
            })
            .catch(function () {
                if (status) {
                    status.textContent = 'Khong the tai du lieu';
                }
            })
            .finally(function () {
                setLoading(false);
            });
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        fetchResults(1);
    });

    form.addEventListener('change', function (event) {
        if (event.target.matches('select, input[type="checkbox"], input[type="date"]')) {
            fetchResults(1);
        }
    });

    results.addEventListener('click', function (event) {
        var link = event.target.closest('.pagination a.page-link');

        if (!link) {
            return;
        }

        var url = new URL(link.href, window.location.origin);
        var page = Number(url.searchParams.get('page') || '1');

        event.preventDefault();

        if (!Number.isNaN(page)) {
            fetchResults(page);
        }
    });

    if (resetButton) {
        resetButton.addEventListener('click', function () {
            form.reset();

            if (categorySelect && typeof window.jQuery !== 'undefined' && typeof window.jQuery.fn.select2 === 'function') {
                window.jQuery(categorySelect).trigger('change.select2');
            }

            fetchResults(1);
        });
    }

    initCategorySelect();
    updateStatusFromResults();
})();
