$(document).ready(function () {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let delivery_method = $('#controller_delivery_method').val();
    let total_weight = null;
    let amount = null;
    let office = $('#controller_office_id').val();
    let office_code = null;
    let selectedCity = $('#controller_city_id').val();
    let delivery_price = 0;
    let courier = $('#controller_courier').val();
    let legal_person;
    //let post_code = $('#controller_post_code').val();

    initData();
    function initData() {
        if (delivery_method && courier) {

            if (delivery_method == "office") {
                initOffices();
            } else {
                initCities();

            }
        }
    }

    $('input[type=radio][name=delivery_method]').change(function () {
        delivery_method = $(this).val();
        destroyCities();
        $('#waybill-street').val(null);
        destroyOffices();

        if (courier && delivery_method == "office") {
            $('#waybill-office-wrapper').show()
            initOffices();
        }
        else if (courier && delivery_method == "address") {
            $('#waybill-address').show()
            initCities();
        }
    })

    $('input[type=radio][name=courier]').change(function () {
        courier = $(this).val();
        destroyCities();
        destroyOffices();
        if (courier == 'speedy' && delivery_method == "office") {
            $('#waybill-office-wrapper').show()
            initOffices();
        } else if (courier == 'speedy' && delivery_method == "address") {
            $('#waybill-address').show()
            initCities();
        }
        else if (courier == 'econt' && delivery_method == "office") {
            $('#waybill-office-wrapper').show()
            initOffices();
        }
        else if (courier == 'econt' && delivery_method == "address") {
            $('#waybill-address').show()
            initCities();
        }
    })

    function destroyOffices() {
        if ($('select#waybill-office').data('select2')) {
            $('select#waybill-office').select2('destroy');
        }
        $('select#waybill-office').val(null);
        $('#waybill-office-wrapper').hide();
    }

    function destroyCities() {
        if ($('select#waybill-city').data('select2')) {
            $('select#waybill-city').select2('destroy');
        }
        $('select#waybill-city').val(null);
        $('#waybill-address').hide();
        // selectedCity = null; 
    }



    function getSpeedyDelivery() {

        fetch(routeToSpeedyDeliveryPrice, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                weight: $('input#weight').val(),
                office: office,
                office_code: office_code,
                amount: $('input#amount').val(),
                declared_value: $('input#declared_value').val(),
                delivery_method: delivery_method,
                city: selectedCity,
                street: $('#waybill-street').val(),
                clent_name: $('#client-name').val(),
                phone: $('#phone').val(),
                content: $('#content').val(),
                courier_payer: $('#courier-payer').val(),
                post_code: $('#controller_post_code').val(),
            }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#loadingIndicator').hide();
                    console.log(data);
                    $("#delivery_price").val(data.price);

                } else {
                    $('#calculatingError').show();
                }
            })
            .catch(error => {
                //console.error(error);
            });
    }

    function getEcontDelivery() {
        fetch(routeToEcontDeliveryPrice, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                weight: $('input#weight').val(),
                office: office,
                office_code: office_code,
                amount: $('input#amount').val(),
                declared_value: $('input#declared_value').val(),
                delivery_method: delivery_method,
                city: selectedCity,
                street: $('#waybill-street').val(),
                clent_name: $('#client-name').val(),
                phone: $('#phone').val(),
                content: $('#content').val(),
                courier_payer: $('#courier-payer').val(),
            }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#loadingIndicator').hide();
                    console.log(data);
                    $("#delivery_price").val(data.price);
                } else {
                    $('#calculatingError').show();
                }
            })
            .catch(error => {
                console.error(error);
            });
    }

    function getDeliveryPrice() {
        $('#loadingIndicator').show();
        $('#calculatingError').hide();
        if (courier == "speedy") {
            getSpeedyDelivery();
        }
        getEcontDelivery();
    }

    var calculateButton = document.getElementById('calculate_delivery');
    calculateButton.addEventListener('click', function () {
        getDeliveryPrice();
    });

    $('input#amount').on('change', function () {
        getDeliveryPrice();
    })
    $('input#weight').on('change', function () {
        getDeliveryPrice();
    })
    $('input#declared_value').on('change', function () {
        getDeliveryPrice();
    })

    function getCityURL() {
        if (courier == 'speedy') {
            return $('select#waybill-city').data('ajax-speedy')
        }
        return $('select#waybill-city').data('ajax-econt')
    }

    function initCities() {
        $('select#waybill-city').select2({
            dropdownParent: $('#waybill_modal'),
            ajax: {
                url: getCityURL(),
                type: "POST",
                delay: 250,
                processResults: function (response) {
                    return {
                        results: response.data
                    };
                }
            }
        })
    }

    $('select#waybill-city').on('select2:select', function (e) {
        selectedCity = e.params.data.id
        $('#controller_post_code').val(e.params.data.post_code)
        if (delivery_method == "office") {
            initOffices();
        } else {
            //initStreets();
        }

    });

    function getStreetURL() {
        if (courier == 'speedy') {
            return $('select#waybill-street').data('ajax-speedy')
        }
        return $('select#waybill-street').data('ajax-econt')
    }
    function getOfficeURL() {
        if (courier == 'speedy') {
            return $('select#waybill-office').data('ajax-speedy')
        }
        return $('select#waybill-office').data('ajax-econt')
    }

    function initOffices() {
        $('select#waybill-office').select2({
            dropdownParent: $('#waybill_modal'),
            ajax: {
                url: getOfficeURL(),
                type: "POST",
                delay: 250,

                data: function (params) {
                    return {
                        q: params.term,
                    }
                },
                processResults: function (response) {
                    console.log(response.data);
                    return {
                        results: response.data
                    };
                }
            }
        })
    }
    $('select#waybill-office').on('select2:select', function (e) {
        office = e.params.data.id;
        office_code = e.params.data.code
        address = null;
        if (courier == "econt") {
            $('input#office_code').val(office_code)
            // calculateEcontDelivery();
        }
        //getDeliveryPrice();
    });
    /* $('select#waybill-office').on('select2:select', function (e) {
         app.office = e.params.data.text
         app.office_id = e.params.data.id
     });*/
    // $('select#waybill-neighborhood').select2({
    //     ajax: {
    //         type: "POST",
    //         delay: 250,
    //         data: function(params) {
    //             return {
    //                 q: params.term,
    //                 city_id: selectedCity
    //             }
    //         },
    //         processResults: function (response) {
    //             return {
    //                 results: response.data
    //             };
    //         }
    //     }
    // })

    $('select#waybill-street').on('select2:select', function (e) {
        const text = e.params.data.text
        console.log(text);
        $('select#waybill-street').val(text);
        console.log($('select#waybill-street').val());
        //getDeliveryPrice();
    });
})
