import L, {LatLng} from 'leaflet';
import 'leaflet.locatecontrol';
import 'leaflet.vectorgrid';
import * as GeoSearch from 'leaflet-geosearch';
import 'leaflet-easybutton';

// Load Leaflet and LocateControl CSS
window.loadCSS([
    'https://unpkg.com/leaflet/dist/leaflet.css',
    'https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.css',
    'https://unpkg.com/leaflet-geosearch/dist/geosearch.css',
    'https://unpkg.com/leaflet-easybutton/src/easy-button.css',
]);

document.addEventListener('DOMContentLoaded', () => {
    const map = L.map('map', {
        maxZoom: 18,
    }).setView([51.2075217, 6.7063201], 13);

    setTimeout(() => {
        map.invalidateSize();
    }, 800);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> | WeedWizard'
    }).addTo(map);

    // BEGIN - Locate Control
    const lc = L.control.locate({
        position: 'topleft',
        strings: {
            title: "Meine Position anzeigen",
        },
        flyTo: true,
    }).addTo(map);

    lc.start();
    // END - Locate Control


    // BEGIN - No Smoke Tile Layer
    let alertShown = false;

    const originalFetch = window.fetch;

    window.fetch = function() {
        return originalFetch.apply(this, arguments)
            .catch(error => {
                if (!alertShown) {
                    alert('Der Tile-server scheint nicht zu laufen. Bitte starte den Tile-server und lade die Seite neu.');
                    alertShown = true;
                }
                throw error;
            });
    };

    L.vectorGrid.protobuf('http://localhost:8080/data/germany-latest-with-tags/{z}/{x}/{y}.pbf', {
        maxNativeZoom: 14,
        vectorTileLayerStyles: {
            'merged': function(properties, zoom) {
                return {
                    fillColor: 'rgba(255, 0, 0, 0.5)',
                    color: 'transparent',
                    fill: true,
                    fillOpacity: 0.5,
                    opacity: 1,
                };
            }
        }
    }).addTo(map);
    // END - No Smoke Tile Layer


    // BEGIN - GeoSearch Control
    const provider = new GeoSearch.OpenStreetMapProvider({
        params: {
            countrycodes: 'de',
            'accept-language': 'de',
            addressdetails: 1,
        },
    });
    const search = new GeoSearch.GeoSearchControl({
        provider: provider,
        style: 'button',
        autoClose: true,
        searchLabel: 'Adresse suchen',
        clearSearchLabel: 'Suche löschen',
        notFoundMessage: 'Adresse nicht gefunden',
    });

    map.addControl(search);
    // END - GeoSearch Control

    // BEGIN - Marker Layer (user Markers)
    fetch('/compliance-map/get-markers')
        .then(response => {
            return response.json();
        })
        .then(data => {
            const icon = L.icon({
                iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                iconSize: [25, 41],
                iconAnchor: [12.5, 41],
            });

            data.markers.forEach(marker => {
                const coordinates = marker.coordinates.split(',').map(Number);
                const markerLayer = L.marker(coordinates, {icon: icon}).addTo(map);
                markerLayer.bindPopup(
                    `<h5>${marker.title}</h5>
                <p>${marker.description}</p>
                <br>
                <a id="marker-${marker.id}" href="#" data-id="${marker.id}" class="text-danger">Löschen</a>`
                );

                markerLayer.on('popupopen', function() {
                    const markerLink = document.getElementById(`marker-${marker.id}`);
                    const markerLink_id = markerLink.dataset.id;

                    console.log(markerLink_id, markerLink.dataset)

                    markerLink.addEventListener('click', function() {
                        fetch(`/compliance-map/del-marker/${markerLink_id}`)
                            .then(response => {
                                return response.json();
                            })
                            .then(data => {
                                if (data.error) {
                                    window.showToast(data.error, 'danger');
                                } else {
                                    markerLayer.remove();
                                    window.showToast('Marker erfolgreich gelöscht', 'success');
                                }
                            });
                    });
                });
            });
        });
// END - Marker Layer (user Markers)

    // BEGIN - Add Marker Button
    L.easyButton({
        position: 'topleft',
        leafletClasses: true,
        states: [{
            stateName: 'addMarker',
            onClick: function(btn, map) {
                btn.state('addingMarker');
                let addMarkerModal = document.getElementById('addMarkerModal');
                const modal = new window.bootstrap.Modal(addMarkerModal);

                map.on('click', function(e) {
                    map.off('click');
                    modal.show();

                    map.setView(e.latlng, 18);
                    const marker = L.marker(e.latlng).addTo(map);

                    const addMarkerForm = document.getElementById('add_marker_form');
                    const addMarkerFormCoords = document.getElementById('add_marker_form_coordinates');
                    addMarkerFormCoords.value = `${e.latlng.lat},${e.latlng.lng}`;

                    addMarkerForm.addEventListener('submit', function(formevent) {
                        formevent.preventDefault();
                        const formData = new FormData(addMarkerForm);
                        formData.append('add_marker_form[coordinates]', addMarkerFormCoords.value);
                        const data = Object.fromEntries(formData.entries());

                        const urlEncodedData = new URLSearchParams(data).toString();

                        fetch('/compliance-map/add-marker', {
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

                            console.log(data.marker);

                            const coordinates = data.marker.coordinates.split(',').map(Number);

                            const marker = L.marker(coordinates);
                            marker.bindPopup(
                                `<h5>${data.marker.title}</h5>
                                <p>${data.marker.description}</p>
                                <br>
                                <a href="/compliance-map/del-marker/${data.marker.id}" class="text-danger">Löschen</a>`
                            ).addTo(map);
                        });

                        modal.hide();
                        btn.state('addMarker');
                        addMarkerForm.reset();
                    });

                    addMarkerModal.addEventListener('hidden.bs.modal', function() {
                        marker.remove();
                        btn.state('addMarker');
                        addMarkerForm.reset();
                    });
                });
            },
            title: 'Marker setzen',
            icon: 'fa-location-dot'
        }, {
            stateName: 'addingMarker',
            onClick: function(btn, map) {
                map.off('click');
                btn.state('addMarker');
            },
            title: 'Ort auf Karte auswählen',
            icon: 'fa-map-location-dot'
        }]
    }).addTo(map);
    // END - Add Marker Button
});