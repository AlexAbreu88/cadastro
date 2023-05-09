if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
} else {
    console.log("Geolocation is not supported by this browser.");
}

function showPosition(position) {
    var lat = position.coords.latitude;
    var lon = position.coords.longitude;
    var latlon = lat + "," + lon;

    // Use the OpenCage Geocoder to get the city name from the latitude and longitude
    fetch('config.json')
        .then(response => response.json())
        .then(config => {
            var apiKey = config.apiKey;
            var geocodingUrl = "https://api.opencagedata.com/geocode/v1/json?q=" + latlon + "&key=" + apiKey;

            $.getJSON(geocodingUrl, function (data) {
                var cityName = data.results[0].components.village;
                var date = new Date();
                var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                var formattedDate = date.toLocaleDateString('pt-BR', options);
                document.getElementById("current_date").innerHTML = cityName + ', ' + formattedDate;
            });
        });
}