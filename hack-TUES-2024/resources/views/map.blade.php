@extends('layouts.admin')

@section('title', 'Анализ на поток от хора')
@section('header_title', 'Анализ на поток от хора')

@section('content')
<style>
    #map {
        height: 500px;
        width: 100%;
    }
</style>

<h1>Mountain Rila Data Visualization</h1>
<div id="map"></div>

<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
<script>
    var map;
    var gridSquares = []; // Keep track of grid squares for removal

    function initMap() {
        var center = {lat: 42.1651, lng: 23.7225}; // Center coordinates of Rila Mountain
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: center
        });

        // Initial update
        updateMap();

        // Set interval for updating the map every 3000ms
        setInterval(updateMap, 3000);
    }

    function updateMap() {
        // Remove existing grid squares
        for (var i = 0; i < gridSquares.length; i++) {
            gridSquares[i].setMap(null);
        }
        gridSquares = [];

        fetch('/api/grid-squares')
            .then(response => response.json())
            .then(data => {
                data.forEach(square => {
                    let fillColor = square.pop > 3 ? '#ff0000' : (square.pop > 2 ? '#ffa500' : '#ffff00');

                    var squarePolygon = new google.maps.Polygon({
                        paths: [
                            {lat: square.lat1, lng: square.long1},
                            {lat: square.lat2, lng: square.long2},
                            {lat: square.lat3, lng: square.long3},
                            {lat: square.lat4, lng: square.long4}
                        ],
                        strokeColor: '#000000',
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: fillColor,
                        fillOpacity: 0.5
                    });
                    squarePolygon.setMap(map);

                    // Keep track of this square for removal in the next update
                    gridSquares.push(squarePolygon);
                });
            })
            .catch(error => console.error('Error fetching grid square data:', error));
    }
</script>
</head>

<body>
    <h1>Mountain Rila Data Visualization</h1>
    <div id="map"></div>
    <a href="{{route('admin.restore.population')}}" class="btn btn-primary">Нулирай популацията</a>


    <?php
    // Replace this with your PHP code to connect to a database or data source
    // and fetch data for the zone and data points

    // Example zone data (replace with your actual data)
    $zoneData = array(
        array("lat" => 42.1, "lng" => 23.6),
        array("lat" => 42.2, "lng" => 23.8),
        array("lat" => 42.0, "lng" => 23.9),
        array("lat" => 41.9, "lng" => 23.7),
        array("lat" => 42.1, "lng" => 23.6)  // Close the polygon
    );

    // Example data points (replace with your logic to fetch data)
    $dataPoints = array(
        array("lat" => 42.12, "lng" => 23.75),
        array("lat" => 42.08, "lng" => 23.82),
        array("lat" => 42.15, "lng" => 23.68)
    );
    ?>