@extends('layouts.admin')

@section('title', 'Анализ на поток от хора')
@section('header_title', 'Анализ на поток от хора')


@section('content')
<div id="kt_docs_google_chart_column"></div>
@endsection

@push('scripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    // GOOGLE CHARTS INIT

    google.load('visualization', '1', {
        packages: ['corechart', 'bar', 'line']
    });

    google.setOnLoadCallback(function() {
        // LINE CHART
        var data = new google.visualization.DataTable();
        data.addColumn('number', 'Дни');
        data.addColumn('number', 'Guardians of the Galaxy');
        data.addColumn('number', 'The Avengers');


        data.addRows({{$result_json}});

        var options = {
            chart: {
                title: 'Box Office Earnings in First Two Weeks of Opening',
                subtitle: 'in millions of dollars (USD)'
            },
            colors: ['#6e4ff5', '#f6aa33', '#fe3995']
        };

        var chart = new google.charts.Line(document.getElementById('kt_docs_google_chart_column'));
        chart.draw(data, options);
    });
</script>
@endpush