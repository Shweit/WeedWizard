import {sanitizeHtml} from "bootstrap/js/src/util/sanitizer";

document.addEventListener('DOMContentLoaded', function () {
    let address_input = document.getElementById('cannabis_verein_adresse');

    function handleAddressInput(event) {
        const input = event.target.value;

        if (input.length < 3) {
            return;
        }

        fetch('https://api.mapbox.com/search/searchbox/v1/suggest?q=' + input + '&access_token=' + window.mapbox_access_token + '&session_token=' + window.mapbox_session_token + '&language=de&country=DE&limit=5')
            .then(response => response.json())
            .then(data => {
                const suggestions = data.suggestions

                const suggestions_list = document.getElementById('suggestions-list');
                suggestions_list.innerHTML = '';
                suggestions_list.appendChild(document.createElement('ul'));
                suggestions_list.classList.add('list-group');

                suggestions_list.style.display = 'block';

                suggestions.forEach(suggestion => {
                    const suggestion_item = document.createElement('li');
                    suggestion_item.classList.add('list-group-item');
                    suggestion_item.classList.add('list-group-item-action');
                    suggestion_item.innerHTML = sanitizeHtml(suggestion.name + ', ' + suggestion.place_formatted);
                    suggestion_item.addEventListener('click', function () {
                        address_input.value = suggestion.name + ', ' + suggestion.place_formatted;
                        document.getElementById('cannabis_verein_mapbox_id').value = suggestion.mapbox_id;
                        document.getElementById('cannabis_verein_strasse').value = suggestion.context.street.name;
                        if (suggestion.context.address) {
                            document.getElementById('cannabis_verein_hausnummer').value = suggestion.context.address.address_number;
                        }
                        document.getElementById('cannabis_verein_ort').value = suggestion.context.place.name;
                        document.getElementById('cannabis_verein_plz').value = suggestion.context.postcode.name;
                        suggestions_list.innerHTML = '';
                        suggestions_list.style.display = 'none';
                    });
                    suggestions_list.appendChild(suggestion_item);
                });
            });
    }

    address_input.addEventListener('input', window.debounce(handleAddressInput, 1000));

    let clubs = document.getElementsByClassName('cannabisClubs');
    let distance = document.getElementById('entfernung');
    distance.addEventListener('keyup', function () {
        applyFilter();
    });

    let priceMin = document.getElementById('priceMin');
    priceMin.addEventListener("keyup", function () {
        applyFilter();
    })

    let priceMax = document.getElementById('priceMax');
    priceMax.addEventListener("keyup", function () {
        applyFilter();
    })

    let searchClub = document.getElementById('searchClub');
    searchClub.addEventListener('keyup', function () {
        applyFilter();
    });

    let sortBy = document.getElementById('sortBy');
    sortBy.addEventListener('change', function () {
        let sortValue = sortBy.value;
        let sorted = null;
        let parent = null;

        switch (sortValue) {
            case 'id_ASC':
                sorted = [...clubs].sort((a, b) => {
                    return a.dataset.id - b.dataset.id;
                });

                parent = clubs[0].parentNode;
                sorted.forEach((party) => {
                    parent.appendChild(party);
                });
                break;
            case 'id_DESC':
                sorted = [...clubs].sort((a, b) => {
                    return b.dataset.id - a.dataset.id;
                });

                parent = clubs[0].parentNode;
                sorted.forEach((party) => {
                    parent.appendChild(party);
                });
                break;
            case 'price_ASC':
                sorted = [...clubs].sort((a, b) => {
                    return a.dataset.price - b.dataset.price;
                });

                parent = clubs[0].parentNode;
                sorted.forEach((party) => {
                    parent.appendChild(party);
                });
                break;
            case 'price_DESC':
                sorted = [...clubs].sort((a, b) => {
                    return b.dataset.price - a.dataset.price;
                });

                parent = clubs[0].parentNode;
                sorted.forEach((party) => {
                    parent.appendChild(party);
                });
                break;
            case 'participants_ASC':
                sorted = [...clubs].sort((a, b) => {
                    return a.dataset.countParticipants - b.dataset.countParticipants;
                });

                parent = clubs[0].parentNode;
                sorted.forEach((party) => {
                    parent.appendChild(party);
                });
                break;
            case 'participants_DESC':
                sorted = [...clubs].sort((a, b) => {
                    return b.dataset.countParticipants - a.dataset.countParticipants;
                });

                parent = clubs[0].parentNode;
                sorted.forEach((party) => {
                    parent.appendChild(party);
                });
                break;
        }

        applyFilter();
    });

    let cachedGeoLocation = null;

    function applyFilter(geoLocation) {
        [...clubs].forEach(club => {
            let name = club.dataset.name.toLowerCase();
            let price = parseInt(club.dataset.price);
            let coordinates = club.dataset.coordinates.split(',');
            let coordinatesObj = {
                latitude: parseFloat(coordinates[0]),
                longitude: parseFloat(coordinates[1])
            };

            let visible = true;
            if (searchClub.value.length > 0 && !name.includes(searchClub.value.toLowerCase())) {
                visible = false;
            }

            if (((priceMin.value.trim() !== '') && price < priceMin.value) || (priceMax.value.trim() !== '' && price >= priceMax.value)) {
                visible = false;
            }

            if (parseInt(distance.value) > 0 && geoLocation) {
                let distanceInKm = window.calculateDistance(
                    geoLocation.coords.latitude,
                    geoLocation.coords.longitude,
                    coordinatesObj.latitude,
                    coordinatesObj.longitude
                );
                if (distanceInKm > parseInt(distance.value)) {
                    visible = false;
                }
            }

            club.style.display = visible ? 'flex' : 'none';
        });
    }

    distance.addEventListener('change', () => {
        if (parseInt(distance.value) > 0) {
            if (!cachedGeoLocation) {
                window.getUserLocation().then(geoLocation => {
                    cachedGeoLocation = geoLocation;
                    applyFilter(cachedGeoLocation);
                }).catch(error => {
                    alert('Geolocation nicht verfügbar. Bitte erlauben Sie den Zugriff auf Ihren Standort, oder versuche es später erneut.');
                });
            } else {
                applyFilter(cachedGeoLocation);
            }
        } else {
            applyFilter(null);
        }
    });
});