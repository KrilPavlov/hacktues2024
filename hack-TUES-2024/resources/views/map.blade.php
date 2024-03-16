@extends('layouts.admin')

@section('title', 'Анализ на поток от хора')
@section('header_title', 'Анализ на поток от хора')
<style>
    #map {
        height: 500px;
        width: 100%;
    }
</style>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBx1Wloo61tkRi6Fb7hUxn6-DJtSlJ_mGE&callback=initMap" async defer></script>
<script>
    function initMap() {
        var center = {
            lat: 42.1651,
            lng: 23.7225
        }; // Center coordinates of Rila Mountain
        var map = new google.maps.Map(document.getElementById("map"), {
            zoom: 12,
            center: center
        });

        // Grid lines (adjust spacing and styles as needed)
        var gridSize = 1000; // Meters between grid lines (adjust for desired density)
        for (var lat = center.lat - gridSize; lat <= center.lat + gridSize; lat += gridSize) {
            for (var lng = center.lng - gridSize; lng <= center.lng + gridSize; lng += gridSize) {
                new google.maps.Polyline({
                    path: [{
                        lat: lat,
                        lng: lng
                    }, {
                        lat: lat,
                        lng: lng + gridSize
                    }],
                    map: map,
                    strokeColor: "#ccc",
                    strokeOpacity: 0.5,
                    strokeWeight: 1
                });
                new google.maps.Polyline({
                    path: [{
                        lat: lat,
                        lng: lng
                    }, {
                        lat: lat + gridSize,
                        lng: lng
                    }],
                    map: map,
                    strokeColor: "#ccc",
                    strokeOpacity: 0.5,
                    strokeWeight: 1
                });
            }
        }

        // Define polygon coordinates for the zone (replace with your data)
        var zoneCoords = [{
                lat: 42.1,
                lng: 23.6
            },
            {
                lat: 42.2,
                lng: 23.8
            },
            {
                lat: 42.0,
                lng: 23.9
            },
            {
                lat: 41.9,
                lng: 23.7
            },
            {
                lat: 42.1,
                lng: 23.6
            } // Close the polygon
        ];

        // Create a red polygon for the zone
        var zone = new google.maps.Polygon({
            paths: zoneCoords,
            strokeColor: "#ff0000",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "#ff0000",
            fillOpacity: 0.2
        });
        zone.setMap(map);

        // Get data from a table (replace with your PHP logic to fetch data)
        // This is a placeholder for demonstration purposes
        var tableData = [{
                lat: 42.12,
                lng: 23.75
            },
            {
                lat: 42.08,
                lng: 23.82
            },
            {
                lat: 42.15,
                lng: 23.68
            }
        ];

        // Plot data points on the map (replace with your logic to iterate through data)
        for (var i = 0; i < tableData.length; i++) {
            var marker = new google.maps.Marker({
                position: tableData[i],
                map: map
            });
        }
    }
</script>
</head>

<body>
    <h1>Mountain Rila Data Visualization</h1>
    <div id="map"></div>
    <a href="{{route('admin.restore.population')}}" class="btn btn-primary">Нулирай</a>
</body>

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