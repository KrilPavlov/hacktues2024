$(document).ready(function() {
    let token = $('meta[name="csrf-token"]').attr('content')

    if (token) {
        // CSRF Token injection
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': token
            }
        });
    } else {
        console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token')
    }
})

function deleteResource(delete_route, row_id) {
    Swal.fire({
        text: "Сигурни ли сте, че искате да изтриете този ресурс ?",
        icon: "warning",
        buttonsStyling: false,
        showCancelButton: true,
        confirmButtonText: "Изтрий!",
        cancelButtonText: "Не!",
        customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-primary",
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // execute delete
            $.ajax({
                url: delete_route,
                type: 'POST',
                data: {_method: 'DELETE'}
            })
                .done(function(data) {
                    if (data.success) {
                        $('#'+row_id).remove()
                        Swal.fire("Ресурсът беше изтрит", "", "success");
                    }
                    else {
                        Swal.fire("Възникна грешка!", "", "error");
                    }
                })
                .fail(function() {
                    Swal.fire("Възникна грешка!", "", "error");
                })
                .always(function() {
                    // console.log("complete");
                })
        }
    });
}
