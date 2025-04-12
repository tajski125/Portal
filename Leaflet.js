<script src="https://unpkg.com/leaflet-geosearch@3.0.0/dist/bundle.min.js"></script>
<script>
    const searchControl = new GeoSearch.GeoSearchControl({
        provider: new GeoSearch.OpenStreetMapProvider(),
        style: 'bar',
        autoComplete: true,
        autoCompleteDelay: 250,
    });

    const locationInput = document.getElementById('location');
    searchControl.on('change', (result) => {
        locationInput.value = result.location.label;
    });
</script>