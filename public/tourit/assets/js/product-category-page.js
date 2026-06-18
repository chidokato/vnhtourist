(function () {
    'use strict';

    var form = document.querySelector('[data-product-filter]');
    var results = document.querySelector('[data-product-results]');
    var status = document.querySelector('[data-product-results-status]');

    if (!form || !results) {
        return;
    }

    var destinationOptions = form.querySelector('[data-destination-options]');
    var resetButton = form.querySelector('[data-filter-reset]');
    var ajaxUrl = form.getAttribute('data-ajax-url');

    if (!ajaxUrl) {
        return;
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
        status.textContent = cards > 0 ? cards + ' tour hien thi' : 'Khong co tour phu hop';
    }

    function replaceDestinationOptions(options, selectedValues) {
        if (!destinationOptions) {
            return;
        }

        var fragment = document.createDocumentFragment();
        var selectedMap = new Set(Array.isArray(selectedValues) ? selectedValues : []);

        (options || []).forEach(function (option) {
            var item = document.createElement('label');
            item.className = 'product-filter-option product-filter-option-checkbox';
            item.innerHTML = ''
                + '<input type="checkbox" name="destinations[]">'
                + '<span class="product-filter-option-label"></span>';

            item.querySelector('input').value = option;
            item.querySelector('input').checked = selectedMap.has(option);
            item.querySelector('span').textContent = option;
            fragment.appendChild(item);
        });

        destinationOptions.innerHTML = '';
        destinationOptions.appendChild(fragment);
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
                replaceDestinationOptions(payload.destinationOptions || [], payload.selectedDestinations || []);
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
            if (event.target.name === 'category_id' && destinationOptions) {
                destinationOptions.querySelectorAll('input[name="destinations[]"]').forEach(function (input) {
                    input.checked = false;
                });
            }

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
            fetchResults(1);
        });
    }

    updateStatusFromResults();
})();
