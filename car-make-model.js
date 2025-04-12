document.getElementById('make').addEventListener('change', function () {
    const brand = this.value;
    const modelSelect = document.getElementById('model');

    if (brand) {
        fetch(`add-ad.php?brand=${encodeURIComponent(brand)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Otrzymane dane modeli:', data); // Debugowanie danych
                modelSelect.innerHTML = '<option value="">Wybierz model</option>';
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.model;
                    option.textContent = item.model;
                    modelSelect.appendChild(option);
                });
                modelSelect.disabled = false;
            })
            .catch(error => {
                console.error('Błąd podczas pobierania modeli:', error);
                alert('Wystąpił problem podczas ładowania modeli. Sprawdź konsolę.');
            });
    } else {
        modelSelect.innerHTML = '<option value="">Najpierw wybierz markę</option>';
        modelSelect.disabled = true;
    }
});