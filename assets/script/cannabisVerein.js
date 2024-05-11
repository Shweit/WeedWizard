document.addEventListener('DOMContentLoaded', function() {
    address_input = document.getElementById('cannabis_verein_adresse');

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
                    suggestion_item.innerHTML = suggestion.name + ', ' + suggestion.place_formatted;
                    suggestion_item.addEventListener('click', function() {
                        address_input.value = suggestion.name + ', ' + suggestion.place_formatted;
                        document.getElementById('bud_bash_mapbox_id').value = suggestion.mapbox_id;
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
});