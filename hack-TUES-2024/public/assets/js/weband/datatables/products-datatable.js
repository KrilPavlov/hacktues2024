
$(document).ready(function () {
    $('#search').val(localStorage.getItem('admin_products_search'));
    $('#category').val(localStorage.getItem('admin_products_category'));
    $('#category').trigger('change');
    $('#published').val(localStorage.getItem('admin_products_published'));
    $('#manufacturer').val(localStorage.getItem('admin_products_manufacturer'));
    $('#manufacturer').trigger('change');
    $('#visibility').val(localStorage.getItem('admin_products_visibility'));

    function fill_datatable() {
        var route = $('#products_datatable').data('load-route');
        var search = $('#search').val();
        var category = $('#category').val();
        var published = $('#published').val();
        var manufacturer = $('#manufacturer').val();
        var visibility = $('#visibility').val();
        localStorage.setItem('admin_products_search', search);
        localStorage.setItem('admin_products_category', category);
        localStorage.setItem('admin_products_published', published);
        localStorage.setItem('admin_products_manufacturer', manufacturer);
        localStorage.setItem('admin_products_visibility', visibility);
        return $('#products_datatable').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "All"]],
            ajax: {
                url: route,
                type: 'POST',
                data: {
                    search: search,
                    category: category,
                    published: published,
                    manufacturer: manufacturer,
                    visibility: visibility,
                },
                "beforeSend": function () {
                    $('#loading_spinner').show(); // Показване на индикатора за зареждане преди изпращане на заявката
                },
                "complete": function () {
                    $('#loading_spinner').hide(); // Скриване на индикатора за зареждане след приключване на заявката
                }
            },
            // Selecting rows with checkboxes
            select: {
                style: 'multi',
                selector: 'td:first-child .kt-checkable'
            },
            // Make the title of the first column a checkbox
            headerCallback: function headerCallback(thead, data, start, end, display) {
                thead.getElementsByTagName('th')[0].innerHTML = "\n                <label class=\"kt-checkbox kt-checkbox--single kt-checkbox--solid kt-checkbox--brand\">\n                    <input type=\"checkbox\" value=\"\" class=\"form-check-input kt-group-checkable\">\n                    <span></span>\n                </label>";
            },
            dom: "<'row'<'col-sm-12'ti>><'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7 dataTables_pager'p>>" + "<'row'<'col-sm-12'ti>><'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7 dataTables_pager'p>>",
            columnDefs: [// Make the first column a checkbox
                {
                    targets: 0,
                    orderable: false,
                    width: 20,
                    render: function render(data, type, full, meta) {
                        return "\n                            <label class=\"kt-checkbox kt-checkbox--single kt-checkbox--solid kt-checkbox--brand\">\n                                <input type=\"checkbox\" value=\"" + data + "\" class=\"form-check-input kt-checkable\">\n                                <span></span>\n                            </label>";
                    }
                }],
            columns: [{
                data: "id",
                name: "id",
                width: 20
            }, {
                data: "name",
                name: "name",
                width: 400
            }, {
                data: "promo",
                name: "promo",
                width: 100
            }, {
                data: "price",
                name: "price",
                width: 100
            }, {
                data: "promo_price",
                name: "promo_price",
                width: 100
            }, {
                data: "quantity",
                name: "quantity",
                width: 100
            }, {
                data: "manufacturer",
                name: "manufacturer",
                width: 150
            }, {
                data: "actions",
                name: "actions",
                className: "text-right",
                width: 100
            }]
        });
    }

    var datatable = fill_datatable(); // Search at the press of a button

    $('#search_button').on('click', function (e) {
        e.preventDefault();
        $('#products_datatable').DataTable().destroy();
        toggleRemoveFiltersButton()
        datatable = fill_datatable();
    });

    $('#search').on('change', function (e) {
        $('#products_datatable').DataTable().destroy();
        datatable = fill_datatable();
    });

    $('#remove_filters').on('click', function (e) {
        $('#products_datatable').DataTable().destroy();
        e.preventDefault();
        $('#search').val(null);
        $('#category').val('all').trigger('change');
        $('#published').val('all').trigger('change');
        $('#manufacturer').val('all').trigger('change');
        $('#visibility').val('all').trigger('change');
        datatable = fill_datatable();
    })
    datatable.on('select', function (e, dt, type, indexes) {
        var rowData = datatable.rows(indexes).data().toArray();
        var count = datatable.rows({
            selected: true
        }).count();
        $('#products-selected-count').html(count);

        if (count > 0) {
            $('#mass-action-wrapper').collapse('show');
        } else {
            $('#mass-action-wrapper').collapse('hide');
        }
    }).on('deselect', function (e, dt, type, indexes) {
        var rowData = datatable.rows(indexes).data().toArray();
        var count = datatable.rows({
            selected: true
        }).count();
        $('#products-selected-count').html(count);

        if (count > 0) {
            $('#mass-action-wrapper').collapse('show');
        } else {
            $('#mass-action-wrapper').collapse('hide');
        }
    }); // Check/uncheck all functionality

    datatable.on('change', '.kt-group-checkable', function () {
        var set = $(this).closest('table').find('td:first-child .kt-checkable');
        var checked = $(this).is(':checked');
        $(set).each(function () {
            if (checked) {
                $(this).prop('checked', true);
                datatable.rows($(this).closest('tr')).select();
            } else {
                $(this).prop('checked', false);
                datatable.rows($(this).closest('tr')).deselect();
            }
        });
    });
    datatable.on('draw', function () {
        toggleRemoveFiltersButton();
    });

    function fill_ids(list_id) {
        // Get the row data of the selected documents
        var rows = datatable.rows({
            selected: true
        }).data();
        var ids_html = '';
        rows.each(function (row) {
            ids_html += '<input name="products[]" type="hidden" value="' + row.id + '">';
        });
        return $(list_id).html(ids_html);
    }

    $('#update-products-btn').click(function (e) {
        fill_ids('.product-list').promise().done(function () {
            $('#product-form').submit();
        });
    });
    $('#remove-promo-btn').click(function (e) {
        fill_ids('.product-list').promise().done(function () {
            $('#product-promo-form').submit();
        });
    }); // Fast edit

    $('#remove-mass-btn').click(function (e) {
        Swal.fire({
            title: "Сигурен ли си че искаш да изтриеш продуктите?",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: 'Изтрий',
            cancelButtonText: 'Назад',
            customClass: {
                confirmButton: 'btn',
                cancelButton: 'btn btn-light'
            },
            confirmButtonColor: '#e3342f',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                fill_ids('.product-list').promise().done(function () {
                    $('#product-remove-form').submit();
                });
            }
        });

    })
    $('#clone-mass-btn').click(function (e) {
        fill_ids('.product-list').promise().done(function () {
            $('#product-clone-form').submit();
        });
    })

    datatable.on('keyup', '.price-input', function () {
        var route = $(this).data('route');
        var price = $(this).val();
        $.ajax({
            url: route,
            method: 'POST',
            data: {
                _method: 'PUT',
                price: price
            }
        }).done(function (res) {
            if (res.success) {
                toastr.success(res.message, {
                    positionClass: "toastr-top-center"
                });
            }
        }).fail(function () { });
    });
    datatable.on('keyup', '.promo-input', function () {
        var route = $(this).data('route');
        var price = $(this).val();
        $.ajax({
            url: route,
            method: 'POST',
            data: {
                _method: 'PUT',
                promo: price
            }
        }).done(function (res) {
            if (res.success) {
                toastr.success(res.message, {
                    positionClass: "toastr-top-center"
                });
            }
        }).fail(function () { });
    });
    datatable.on('keyup', '.quantity-input', function () {
        var route = $(this).data('route');
        var quantity = $(this).val();
        $.ajax({
            url: route,
            method: 'POST',
            data: {
                _method: 'PUT',
                quantity: quantity
            }
        }).done(function (res) {
            if (res.success) {
                toastr.success(res.message, {
                    positionClass: "toastr-top-center"
                });
            }
        }).fail(function () { });
    }); // Resource delete

    datatable.on('click', '.delete-resource', function (event) {
        event.preventDefault()
        let delete_route = $(this).data('delete-route');

        Swal.fire({
            title: "Сигурен ли си ?",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: 'Изтрий',
            cancelButtonText: 'Назад',
            customClass: {
                confirmButton: 'btn',
                cancelButton: 'btn btn-light'
            },
            confirmButtonColor: '#e3342f',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: delete_route,
                    type: 'DELETE',
                })
                    .done(function (data) {
                        if (data.success) {
                            datatable.draw(false);
                            Swal.fire(data.msgTitle, data.message, "success");
                        } else {
                            Swal.fire("Опа", data.message, "error");
                        }
                    })
                    .fail(function (err) {
                        Swal.fire("Опа", "Възникна грешка!", "error");
                    })
                    .always(function () {
                        // console.log("complete");
                    })
            }
        });
    })


    const searchInput = document.getElementById('search');
    const categorySelect = document.getElementById('category');
    const manufacturerSelect = document.getElementById('manufacturer');
    const publishedSelect = document.getElementById('published');
    const visibilitySelect = document.getElementById('visibility');
    const removeFiltersButton = document.getElementById('remove_filters');

    searchInput.addEventListener('input', toggleRemoveFiltersButton);
    categorySelect.addEventListener('change', toggleRemoveFiltersButton);
    manufacturerSelect.addEventListener('change', toggleRemoveFiltersButton);
    publishedSelect.addEventListener('change', toggleRemoveFiltersButton);
    visibilitySelect.addEventListener('change', toggleRemoveFiltersButton);

    function toggleRemoveFiltersButton() {
        const anyFilterApplied = searchInput.value.trim() !== '' ||
            categorySelect.value !== 'all' ||
            manufacturerSelect.value !== 'all' ||
            publishedSelect.value !== 'all' ||
            visibilitySelect.value !== 'all';
        removeFiltersButton.style.display = anyFilterApplied ? 'inline-block' : 'none';
    }
    toggleRemoveFiltersButton();
});
