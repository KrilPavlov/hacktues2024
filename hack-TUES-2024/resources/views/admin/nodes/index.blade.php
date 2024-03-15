@extends('layouts.admin')

@section('title', 'Координати')
@section('header_title', 'Координати')

@section('content')
<div class="card mb-5 mb-xl-8" style="width: 100%;">
    <div class="card-header border-0 pt-5">
        <div class="card-toolbar">
                <span class="svg-icon svg-icon-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black"></rect>
                        <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="black"></rect>
                    </svg>
                </span>
                Създай координати
            </a>
        </div>
    </div>
    <div class="card-body py-3">
        <div class="table-responsive">

            <table id="nodes_table" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                <thead>
                    <tr class="fw-bolder text-muted">
                        <th>Нименование</th>
                        <th>Тип</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let datatable = initDatatable();

        $('#search_button').on('click', function(e) {
            e.preventDefault();
            datatable.draw();
        });
        $('#search_input').on('change', function(e) {
            datatable.draw();
        });
        $('#remove_filters').on('click', function(e) {
            datatable.destroy();
            e.preventDefault();
            $('#search_input').val(null);
            $('#status').val('all').trigger('change');
            $('#kt_daterangepicker_4').val(null);
            datatable = initDatatable();
        })

        function initDatatable() {
            return $('#nodes_table').DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": 25,
                "ordering": false,
                "ajax": {
                    "url": "{{ route('admin.nodes.datatable') }}",
                    "type": "POST",
                    "data": function(d) {},
                },
                "columns": [{
                        "data": "name",
                        "className": "text-left"
                    },
                    {
                        "data": "type",
                        "className": "text-center"
                    },
                    {
                        "data": "action",
                        "width": "10px.",
                        "className": "text-right"
                    },
                ],

            });
        }

        datatable.on('click', '.delete-resource', function(event) {
            event.preventDefault();
            let delete_route = $(this).data('delete-route');
            Swal.fire({
                title: "Сигурен ли си ?",
                icon: "warning",
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
                        .done(function(data) {
                            if (data.success) {
                                datatable.draw(false);
                                Swal.fire(data.msgTitle, data.message, "success");
                            } else {
                                Swal.fire("Опа", data.message, "error");
                            }
                        })
                        .fail(function(err) {
                            Swal.fire("Опа", "Възникна грешка!", "error");
                        })
                        .always(function() {
                            // console.log("complete");
                        })
                }
            });
        });

        //Date range picker
        var start = moment().subtract(29, 'days');
        var end = moment();
    });
</script>
@endpush