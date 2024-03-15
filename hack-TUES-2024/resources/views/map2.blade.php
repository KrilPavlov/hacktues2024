<!DOCTYPE html>
<html>

<head>
    <title>Colored Grid Map</title>
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>
</head>

<body>
    <h1>Colored Grid Map</h1>
    <div id="map"></div>

    <script>
        // Функция, която инициализира картата и рисува grid
        function initMap() {
            var center = { lat: 42.6977, lng: 23.3219 }; // Примерни координати за центъра на картата
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 10,
                center: center
            });

            // Примерни координати за рисуване на grid
            var gridCoordinates = [
                { lat: 42.7, lng: 23.3 },
                { lat: 42.8, lng: 23.3 },
                { lat: 42.8, lng: 23.4 },
                { lat: 42.7, lng: 23.4 }
                // Добавете още координати, ако е необходимо
            ];

            // Нива на опасност
            var avalancheDangers = [0, 1, 2, 3]; // Примерни нива на опасност

            // Рисуване на grid с различни цветове според нивата на опасност
            for (var i = 0; i < gridCoordinates.length; i++) {
                var dangerLevel = avalancheDangers[i];
                var color;
                if (dangerLevel === 0) {
                    color = 'green';
                } else if (dangerLevel === 1) {
                    color = 'yellow';
                } else if (dangerLevel === 2) {
                    color = 'orange';
                } else {
                    color = 'red';
                }
                var gridLine = new google.maps.Polygon({
                    paths: [
                        { lat: gridCoordinates[i].lat, lng: gridCoordinates[i].lng },
                        { lat: gridCoordinates[i].lat + 0.1, lng: gridCoordinates[i].lng },
                        { lat: gridCoordinates[i].lat + 0.1, lng: gridCoordinates[i].lng + 0.1 },
                        { lat: gridCoordinates[i].lat, lng: gridCoordinates[i].lng + 0.1 }
                    ],
                    strokeColor: color,
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: color,
                    fillOpacity: 0.35,
                    map: map
                });
            }
        }
    </script>

    <!-- Зареждане на Google Maps API с ключ и извикване на функцията initMap -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBx1Wloo61tkRi6Fb7hUxn6-DJtSlJ_mGE&callback=initMap" async defer></script>
</body>

</html>
