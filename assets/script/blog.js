import L from 'leaflet';
import 'leaflet-easybutton';

document.addEventListener('DOMContentLoaded', function() {
    const addBlogEntryForm = document.getElementById('addBlogEntryForm');

    addBlogEntryForm.addEventListener('submit', function(event) {
       event.preventDefault();

       const content = document.getElementById('addBlogEntry_content').value;

       fetch('/blog/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
           body: JSON.stringify({
                content: content
          })
       })
       .then(response =>  {
           return response.json()
       })
       .then(data => {
           if (data.error) {
               window.showToast(data.error, 'error');
           } else {
               window.location.reload();
           }
       })
    });

    const addBlogEntry_content = document.getElementById('addBlogEntry_content');
    addBlogEntry_content.addEventListener('keyup', function() {
        const content = addBlogEntry_content.value;

        const character_limit = document.getElementById('character_limit');
        character_limit.innerText = content.length + '/1000';

        let submit = document.getElementById('addBlogEntry_submit')

        if (content.length > 1000) {
            character_limit.style.color = 'red';
            submit.setAttribute('disabled', 'disabled');
        } else {
            character_limit.style.color = '';
            if (submit.hasAttribute('disabled')) {
                submit.removeAttribute('disabled');
            }
        }
    });

    const sortByOptions = document.getElementById('sortBy');
    sortByOptions.addEventListener('change', function() {
        const sortValue = sortByOptions.value;
        const blogEntries = document.getElementsByClassName('blogEntry');

        let sorted = null;

        switch (sortValue) {
            case 'id_ASC':
                sorted = [...blogEntries].sort((a, b) => {
                    return new Date(b.dataset.createdAt) - new Date(a.dataset.createdAt);
                });

                sorted.forEach((entry) => {
                    entry.parentNode.appendChild(entry);
                });
                break;
            case 'id_DESC':
                sorted = [...blogEntries].sort((a, b) => {
                    return new Date(a.dataset.createdAt) - new Date(b.dataset.createdAt);
                });

                sorted.forEach((entry) => {
                    entry.parentNode.appendChild(entry);
                });
                break;
            case 'likes_ASC':
                sorted = [...blogEntries].sort((a, b) => {
                    return a.dataset.likeCount - b.dataset.likeCount;
                });

                sorted.forEach((entry) => {
                    entry.parentNode.appendChild(entry);
                });
                break;
            case 'likes_DESC':
                sorted = [...blogEntries].sort((a, b) => {
                    return b.dataset.likeCount - a.dataset.likeCount;
                });

                sorted.forEach((entry) => {
                    entry.parentNode.appendChild(entry);
                });
                break;
            case 'comments_DESC':
                sorted = [...blogEntries].sort((a, b) => {
                    return b.dataset.commentCount - a.dataset.commentCount;
                });

                sorted.forEach((entry) => {
                    entry.parentNode.appendChild(entry);
                });
                break;
            case 'comments_ASC':
                sorted = [...blogEntries].sort((a, b) => {
                    return a.dataset.commentCount - b.dataset.commentCount;
                });

                sorted.forEach((entry) => {
                    entry.parentNode.appendChild(entry);
                });
                break;
        }
    });

    let mapBlogEntries = document.getElementsByClassName('init-leaflet-map');
    [...mapBlogEntries].forEach((entry) => {
        const icon = L.icon({
            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
            iconSize: [25, 41],
            iconAnchor: [12.5, 41],
        });

        // Get the data from the dataset and form the Coordinates to an array so leaflet can use it
        const data = entry.dataset.marker ? JSON.parse(entry.dataset.marker) : null;
        const coordinates = data.coordinates.split(',').map(Number);

        // Initialize the map and zoom to the coordinates
        const map = L.map(entry).setView(coordinates, 13);

        setTimeout(() => {
            map.invalidateSize();
        }, 800);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> | WeedWizard'
        }).addTo(map);

        let markerLayer = L.marker(coordinates, {icon: icon}).addTo(map);
        markerLayer.bindPopup(`
            <h5>${data.title}</h5>
            <p>${data.description}</p>
        `).openPopup();

        // Add the easy button to add the marker to thein own map
        L.easyButton('fa-solid fa-map-location-dot', function(btn, map) {
            const formData = new FormData();
            formData.append('add_marker_form[title]', data.title);
            formData.append('add_marker_form[description]', data.description);
            formData.append('add_marker_form[coordinates]', data.coordinates);
            formData.append('add_marker_form[skipValidation]', true);

            const urlEncodedData = new URLSearchParams(
                Object.fromEntries(formData)
            ).toString();

            fetch('/api/compliance-map/add-marker', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: urlEncodedData,
            }).then(async response => {
                return response.json();
            }).then(data => {
                if (data.error) {
                    window.showToast(data.error, 'danger');
                    return;
                }
                window.showToast('Marker erfolgreich hinzugefügt', 'success');
            });
        }).addTo(map);
    });
});
