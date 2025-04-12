document.addEventListener('DOMContentLoaded', function () {
    const inputLocation = document.getElementById('location');
    const suggestions = document.getElementById('location-suggestions');
    let debounceTimeout = null;

    inputLocation.addEventListener('input', function () {
        const query = inputLocation.value.trim();

        if (query.length < 1) {
            suggestions.innerHTML = '';
            suggestions.classList.remove('visible'); // Ukryj listę, gdy brak wyników
            return;
        }

        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&addressdetails=1&limit=10`)
                .then(response => response.json())
                .then(data => {
                    suggestions.innerHTML = '';

                    const seenLocations = new Set();

                    data.forEach(location => {
                        if (!seenLocations.has(location.display_name)) {
                            seenLocations.add(location.display_name);

                            const li = document.createElement('li');
                            li.textContent = location.display_name;
                            li.classList.add('suggestion-item');
                            li.addEventListener('click', function () {
                                inputLocation.value = location.display_name;
                                suggestions.innerHTML = '';
                                suggestions.classList.remove('visible'); // Ukryj listę po wyborze
                            });
                            suggestions.appendChild(li);
                        }
                    });

                    if (data.length === 0) {
                        const li = document.createElement('li');
                        li.textContent = 'Brak wyników';
                        li.classList.add('no-results');
                        suggestions.appendChild(li);
                    }

                    if (suggestions.children.length > 0) {
                        suggestions.classList.add('visible'); // Pokaż listę, jeśli są wyniki
                    } else {
                        suggestions.classList.remove('visible'); // Ukryj listę, jeśli brak wyników
                    }
                })
                .catch(error => {
                    console.error('Błąd podczas pobierania lokalizacji:', error);
                });
        }, 200);
    });

    document.addEventListener('click', function (e) {
        if (!inputLocation.contains(e.target) && !suggestions.contains(e.target)) {
            suggestions.innerHTML = '';
            suggestions.classList.remove('visible'); // Ukryj listę po kliknięciu poza nią
        }
    });
});