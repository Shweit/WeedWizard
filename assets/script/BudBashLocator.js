import nouislider from "nouislider";
import {sanitizeHtml} from "bootstrap/js/src/util/sanitizer";

document.addEventListener('DOMContentLoaded', function() {
    let address_input = document.getElementById('bud_bash_address');

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
                    suggestion_item.addEventListener('click', function() {
                        address_input.value = suggestion.name + ', ' + suggestion.place_formatted;
                        document.getElementById('bud_bash_mapbox_id').value = suggestion.mapbox_id;
                        document.getElementById('bud_bash_address_street').value = suggestion.context.street.name;
                        if (suggestion.context.address) {
                            document.getElementById('bud_bash_address_house_number').value = suggestion.context.address.address_number;
                        }
                        document.getElementById('bud_bash_address_city').value = suggestion.context.place.name;
                        document.getElementById('bud_bash_address_postal_code').value = suggestion.context.postcode.name;
                        suggestions_list.innerHTML = '';
                        suggestions_list.style.display = 'none';
                    });
                    suggestions_list.appendChild(suggestion_item);
                });
            });
    }

    address_input.addEventListener('input', window.debounce(handleAddressInput, 1000));

    let budBashPartys = document.getElementsByClassName('budBashParty');

    let distance = document.getElementById('entfernung');
    distance.addEventListener('change', function() {
        applyFilter();
    });

    let priceSlider = document.getElementById('priceSlider');
    if (priceSlider) {
        nouislider.create(priceSlider, {
            start: [parseInt(priceSlider.dataset.minValue), parseInt(priceSlider.dataset.maxValue)],
            connect: true,
            range: {
                'min': parseInt(priceSlider.dataset.minValue),
                'max': parseInt(priceSlider.dataset.maxValue)
            },
            tooltips: true,
            pips: {
                mode: 'steps',
                stepped: true,
                density: 4
            }
        });
    }

    priceSlider.noUiSlider.on('change', function(values, handle) {
        applyFilter();
    });

    let searchBudBash = document.getElementById('searchBudBash');
    searchBudBash.addEventListener('keyup', function() {
        applyFilter();
    });

    let sortBy = document.getElementById('sortBy');
    sortBy.addEventListener('change', function() {
        let sortValue = sortBy.value;
        let sorted = null;
        let parent = null

        switch (sortValue) {
        case 'id_ASC':
            sorted  = [...budBashPartys].sort((a, b) => {
                return a.dataset.id - b.dataset.id;
            });

            parent  = budBashPartys[0].parentNode;
            sorted.forEach((party) => {
                parent.appendChild(party);
            });
            break;
        case 'id_DESC':
            sorted  = [...budBashPartys].sort((a, b) => {
                return b.dataset.id - a.dataset.id;
            });

            parent  = budBashPartys[0].parentNode;
            sorted.forEach((party) => {
                parent.appendChild(party);
            });
            break;
        case 'price_ASC':
            sorted  = [...budBashPartys].sort((a, b) => {
                return a.dataset.price - b.dataset.price;
            });

            parent  = budBashPartys[0].parentNode;
            sorted.forEach((party) => {
                parent.appendChild(party);
            });
            break;
        case 'price_DESC':
            sorted  = [...budBashPartys].sort((a, b) => {
                return b.dataset.price - a.dataset.price;
            });

            parent  = budBashPartys[0].parentNode;
            sorted.forEach((party) => {
                parent.appendChild(party);
            });
            break;
        case 'date_ASC':
            sorted  = [...budBashPartys].sort((a, b) => {
                return new Date(a.dataset.start) - new Date(b.dataset.start);
            });

            parent  = budBashPartys[0].parentNode;
            sorted.forEach((party) => {
                parent.appendChild(party);
            });
            break;
        case 'date_DESC':
            sorted  = [...budBashPartys].sort((a, b) => {
                return new Date(b.dataset.start) - new Date(a.dataset.start);
            });

            parent  = budBashPartys[0].parentNode;
            sorted.forEach((party) => {
                parent.appendChild(party);
            });
            break;
        case 'participants_ASC':
            sorted  = [...budBashPartys].sort((a, b) => {
                return a.dataset.countParticipants - b.dataset.countParticipants;
            });

            parent  = budBashPartys[0].parentNode;
            sorted.forEach((party) => {
                parent.appendChild(party);
            });
            break;
        case 'participants_DESC':
            sorted  = [...budBashPartys].sort((a, b) => {
                return b.dataset.countParticipants - a.dataset.countParticipants;
            });

            parent  = budBashPartys[0].parentNode;
            sorted.forEach((party) => {
                parent.appendChild(party);
            });
        }

        applyFilter();
    });

    let cachedGeoLocation = null;

    function getUserLocation() {
        return new Promise((resolve, reject) => {
            if (!navigator.geolocation) {
                reject(new Error("Geolocation is not supported by this browser."));
            } else {
                navigator.geolocation.getCurrentPosition(resolve, reject, {
                    timeout: 10000,
                    maximumAge: 60000
                });
            }
        });
    }

    if (sessionStorage.getItem('budBashFilter')) {
        let filter = JSON.parse(sessionStorage.getItem('budBashFilter'));

        if (filter.name) {
            document.getElementById('searchBudBash').value = filter.name;
        }

        if (filter.price) {
            priceSlider.noUiSlider.set(filter.price);
        }

        sessionStorage.removeItem('budBashFilter');

        applyFilter();
    }

    function applyFilter(geoLocation) {
        [...budBashPartys].forEach(budBash => {
            let name = budBash.dataset.name.toLowerCase();
            let price = parseInt(budBash.dataset.price);
            let coordinates = budBash.dataset.coordinates.split(',');
            let coordinatesObj = {
                latitude: parseFloat(coordinates[0]),
                longitude: parseFloat(coordinates[1])
            };

            let visible = true;
            if (searchBudBash.value.length > 0 && !name.includes(searchBudBash.value.toLowerCase())) {
                visible = false;
            }

            if (priceSlider.noUiSlider.get()[0] > price || priceSlider.noUiSlider.get()[1] < price) {
                visible = false;
            }

            if (parseInt(distance.value) > 0 && geoLocation) {
                let distanceInKm = window.calculateDistance(geoLocation.coords.latitude, geoLocation.coords.longitude, coordinatesObj.latitude, coordinatesObj.longitude);
                if (distanceInKm > parseInt(distance.value)) {
                    visible = false;
                }
            }

            budBash.style.display = visible ? 'flex' : 'none';
        });
    }

    distance.addEventListener('change', () => {
        if (parseInt(distance.value) > 0) {
            if (!cachedGeoLocation) {
                getUserLocation().then(geoLocation => {
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
