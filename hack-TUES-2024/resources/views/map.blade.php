@extends('layouts.admin')

@section('title', 'Анализ на поток от хора')
@section('header_title', 'Анализ на поток от хора')

@section('content')
<h1>Визуализация на потока от туристи.</h1>
<div id="map" style="height: 800px; width: 100%;"></div>
<a href="{{route('admin.restore.population')}}" class="btn btn-primary mt-5">Нулирай</a>
@endsection

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBx1Wloo61tkRi6Fb7hUxn6-DJtSlJ_mGE&callback=initMap" async defer></script>
<script>
    function initMap() {
        var center = {
            lat: 41.756702, 
            lng: 23.416513
        }; // Center coordinates of Rila Mountain
        var map = new google.maps.Map(document.getElementById("map"), {
            zoom: 10,
            center: center
        });

        updateMap();
        setInterval(updateMap, 10000); // Refresh every 3000ms

    }

    function updateMap() {
        
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 10,
            center: { lat: 41.775, lng: 23.375 }
        });
        
        fetch('/get-grid-square-data')
            .then(response => response.json())
            .then(gridSquares => {
                // Clear existing squares from the map before updating
                // Assuming you have a way to track and remove them. If not, you'll need to implement it.
                gridSquares.forEach(square => {
                    // Only proceed if population is greater than 0
                    if (square.pop > 0) {
                        // Calculate the color based on the population
                        let fillColor = square.pop > 3 ? '#ff0000' : (square.pop > 2 ? '#ffa500' : '#ffff00');
                        // Create a square polygon for each grid square
                        let squarePolygon = new google.maps.Polygon({
                            paths: [
                                { lat: parseFloat(square.lat1), lng: parseFloat(square.lng1) },
                                { lat: parseFloat(square.lat2), lng: parseFloat(square.lng2) },
                                {lat: parseFloat(square.lat4), lng: parseFloat(square.lng4) },
                                { lat: parseFloat(square.lat3), lng: parseFloat(square.lng3) },
                                
                            ],
                            strokeColor: '#000000', // Black border for visibility
                            strokeOpacity: 0.8,
                            strokeWeight: 2,
                            fillColor: fillColor,
                            fillOpacity: 0.5
                        });
                        squarePolygon.setMap(map);
                    }
                });
            })
            .catch(error => console.error('Error fetching grid square data:', error));
    }

</script>
@endpush