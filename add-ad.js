
    document.addEventListener('DOMContentLoaded', function () {
        const inputLocation = document.getElementById('location');
        const suggestions = document.createElement('ul');
        suggestions.setAttribute('id', 'location-suggestions');
        inputLocation.parentNode.appendChild(suggestions);

        inputLocation.addEventListener('input', function () {
            const query = inputLocation.value;

            if (query.length < 3) return; // Minimalna liczba znaków do wyszukiwania

            fetch(`https://nominatim.openstreetmap.org/search?q=${query}&format=json&addressdetails=1`)
                .then(response => response.json())
                .then(data => {
                    suggestions.innerHTML = ''; // Wyczyść istniejące sugestie

                    data.forEach(location => {
                        const li = document.createElement('li');
                        li.textContent = location.display_name;
                        li.classList.add('suggestion-item');
                        li.addEventListener('click', function () {
                            inputLocation.value = location.display_name; // Ustaw wybraną lokalizację
                            suggestions.innerHTML = ''; // Wyczyść listę sugestii
                        });
                        suggestions.appendChild(li);
                    });
                });
        });
    });
