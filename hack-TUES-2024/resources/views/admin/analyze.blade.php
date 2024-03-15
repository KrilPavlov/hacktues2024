@extends('layouts.admin')

@section('title', 'Анализ на поток от хора')
@section('header_title', 'Анализ на поток от хора')


@section('content')
<div id="kt_docs_google_chart_column" data-ajax-url="{{ route('admin.getAjax') }}"></div>
@endsection

@push('scripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    google.load('visualization', '1', {
        packages: ['corechart', 'bar', 'line']
    });

    google.setOnLoadCallback(function() {
        // Функция за извличане на данни с AJAX и прерисуване на графиката
        function fetchDataAndRedrawChart() {
            var ajaxUrl = $('#kt_docs_google_chart_column').data('ajax-url');

            $.ajax({
                url: ajaxUrl,
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    // Преобразувайте получените данни във формат, който е необходим за графиката
                    var data = new google.visualization.DataTable();
                    data.addColumn('number', 'Event ID');
                    data.addColumn('number', 'Сектор 1');
                    data.addColumn('number', 'Сектор 2');
                    data.addRows(response);

                    // Визуализирайте графиката с новите данни
                    var options = {
                        chart: {
                            title: 'Популация на туристите в двата сектора.'
                        },
                        colors: ['#6e4ff5', '#f6aa33', '#fe3995']
                    };

                    var chart = new google.charts.Line(document.getElementById('kt_docs_google_chart_column'));
                    chart.draw(data, options);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        }

        // Извикване на функцията за първоначално зареждане на страницата
        $(document).ready(function() {
            fetchDataAndRedrawChart();
        });

        // Обновяване на данните на всеки 5 минути
        setInterval(fetchDataAndRedrawChart, 3000); // 300000 милисекунди = 5 минути
    });
</script>
@endpush