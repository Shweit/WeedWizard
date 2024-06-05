import L, {LatLng} from 'leaflet';
import 'leaflet.locatecontrol';
import 'leaflet.vectorgrid';
import * as GeoSearch from 'leaflet-geosearch';
import 'leaflet-easybutton';
import BudBashMarker from '../../public/build/images/party_marker.png'

let userMarkers = [];
let budBashMarkers = [];
let publicMarkers = [];

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

    const pedestrainZones = L.vectorGrid.protobuf('http://localhost:8080/data/germany-pedestrian-zones/{z}/{x}/{y}.pbf', {
        maxNativeZoom: 14,
        vectorTileLayerStyles: {
            'merged_pedestrian': function(properties, zoom) {
                return {
                    fillColor: 'rgba(255, 0, 0, 0.5)',
                    color: 'transparent',
                    fill: true,
                    fillOpacity: 0.5,
                    opacity: 1,
                };
            }
        }
    });

    // Only show pedestrian zones when time is between 7am and 8pm
    const date = new Date();
    const hours = date.getHours();
    if (hours >= 7 && hours <= 20) {
        pedestrainZones.addTo(map);
    }
    // END - No Smoke Tile Layer

    const pedestrainZones = L.vectorGrid.protobuf('http://localhost:8080/data/germany-pedestrian-zones/{z}/{x}/{y}.pbf', {
        maxNativeZoom: 14,
        vectorTileLayerStyles: {
            'merged_pedestrian': function(properties, zoom) {
                return {
                    fillColor: 'rgba(255, 0, 0, 0.5)',
                    color: 'transparent',
                    fill: true,
                    fillOpacity: 0.5,
                    opacity: 1,
                };
            }
        }
    });

    // Only show pedestrian zones when time is between 7am and 8pm
    const date = new Date();
    const hours = date.getHours();
    if (hours >= 7 && hours <= 20) {
        pedestrainZones.addTo(map);
    }
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
    fetch('/api/compliance-map/get-markers')
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
                markerLayer.bindPopup(`
                    <h5>${marker.title}</h5>
                    <p>${marker.description}</p>
                    <br>
                    <a id="marker-${marker.id}" href="#" data-id="${marker.id}" class="text-danger">Löschen</a>
                `);

                markerLayer.on('popupopen', function() {
                    const markerLink = document.getElementById(`marker-${marker.id}`);
                    const markerLink_id = markerLink.dataset.id;

                    markerLink.addEventListener('click', function() {
                        fetch(`/api/compliance-map/del-marker/${markerLink_id}`)
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

                userMarkers.push(markerLayer);
            });

            applyMapSettings(map);
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

                    const icon = L.icon({
                        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                        iconSize: [25, 41],
                        iconAnchor: [12.5, 41],
                    });
                    const marker = L.marker(e.latlng, {icon: icon}).addTo(map);

                    const addMarkerForm = document.getElementById('add_marker_form');
                    const addMarkerFormCoords = document.getElementById('add_marker_form_coordinates');
                    addMarkerFormCoords.value = `${e.latlng.lat},${e.latlng.lng}`;

                    addMarkerForm.addEventListener('submit', function(formevent) {
                        formevent.preventDefault();
                        const formData = new FormData(addMarkerForm);
                        formData.append('add_marker_form[coordinates]', addMarkerFormCoords.value);
                        const data = Object.fromEntries(formData.entries());

                        const urlEncodedData = new URLSearchParams(data).toString();

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

                            const coordinates = data.marker.coordinates.split(',').map(Number);

                            const icon = L.icon({
                                iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                                iconSize: [25, 41],
                                iconAnchor: [12.5, 41],
                            });

                            const marker = L.marker(coordinates, {icon: icon});
                            marker.bindPopup(
                                `<h5>${data.marker.title}</h5>
                                <p>${data.marker.description}</p>
                                <br>
                                <a id="marker-${data.marker.id}" href="#" data-id="${data.marker.id}" class="text-danger">Löschen</a>`
                            ).addTo(map);

                            marker.on('popupopen', function() {
                                const markerLink = document.getElementById(`marker-${data.marker.id}`);
                                const markerLink_id = markerLink.dataset.id;

                                markerLink.addEventListener('click', function() {
                                    fetch(`/api/compliance-map/del-marker/${markerLink_id}`)
                                        .then(response => {
                                            return response.json();
                                        })
                                        .then(data => {
                                            if (data.error) {
                                                window.showToast(data.error, 'danger');
                                            } else {
                                                marker.remove();
                                                window.showToast('Marker erfolgreich gelöscht', 'success');
                                            }
                                        });
                                });
                            });
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

    // BEGIN - Legal Notice Modal
    L.easyButton('fa-info', function() {
        const modal = new window.bootstrap.Modal(document.getElementById('legalNoticeModal'));
        modal.show();
    }).addTo(map);
    // END - Legal Notice Modal

    // BEGIN - Bud Bash Marker Layer
    fetch('/api/compliance-map/get-bud-bashes')
        .then(response => {
            return response.json();
        })
        .then(data => {
            const icon = L.icon({
                iconUrl: BudBashMarker,
                iconSize: [40, 40],
                iconAnchor: [20, 20],
            });

            for (let [key, marker] of Object.entries(data.markers)) {
                const coordinates = marker.coordinates.split(',');
                const markerLayer = L.marker(coordinates, {icon: icon}).addTo(map);
                markerLayer.bindPopup(`
                    <h5>${marker.name}</h5>
                    <p>${marker.extraInfo}</p>
                    <hr>
                    <p>
                        <b>Start:</b> ${marker.start}<br>
                        <b>Einlassgebühr:</b> ${marker.entrance_fee}€<br>
                        <b>Adresse:</b> ${marker.address}<br>
                    </p>
                    <br>
                    <a id="party-${marker.id}" href="#">Anschauen</a>
                `);

                markerLayer.on('popupopen', function() {
                    const partyLink = document.getElementById(`party-${marker.id}`);
                    partyLink.addEventListener('click', function() {
                        sessionStorage.setItem('budBashFilter', JSON.stringify({
                            name: marker.name,
                            price: [marker.entrance_fee],
                        }));
                        window.location.href = `/budbash-locator`;
                    });
                });

                budBashMarkers.push(markerLayer);
            }

            applyMapSettings(map);
        });
    // END - Bud Bash Marker Layer

    // BEGIN - Compliance Map Settings
    L.easyButton({
        position: 'topright',
        leafletClasses: true,
        states: [{
            stateName: 'complianceMapSettings',
            onClick: function(btn, map) {
                btn.state('complianceMapSettingsActive');
                let complianceMapSettingsModal = document.getElementById('mapSettingsModal');
                const modal = new window.bootstrap.Modal(complianceMapSettingsModal);

                modal.show();

                complianceMapSettingsModal.addEventListener('hidden.bs.modal', function() {
                    btn.state('complianceMapSettings');
                });
            },
            title: 'Einstellungen',
            icon: 'fa-cog'
        }, {
            stateName: 'complianceMapSettingsActive',
            onClick: function(btn, map) {
                btn.state('complianceMapSettings');
            },
            title: 'Einstellungen schließen',
            icon: 'fa-times'
        }]
    }).addTo(map);

    const settingsForm = document.getElementById('mapSettingsForm');

    settingsForm.addEventListener('submit', function(formevent) {
        formevent.preventDefault();
        sessionStorage.setItem('mapSettings', JSON.stringify(Object.fromEntries(new FormData(settingsForm).entries())));
        applyMapSettings(map);
    });
    // END - Compliance Map Settings

    // BEGIN - Public Marker Layer
    fetch('/api/compliance-map/get-public-markers')
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
                markerLayer.bindPopup(`
                    <h5>${marker.title}</h5>
                    <p>${marker.description}</p>
                    <small>Öffentlicher Marker von: ${marker.name}</small>
                `);

                publicMarkers.push(markerLayer);
            });

            applyMapSettings(map);
        });
    // END - Public Marker Layer
});

function applyMapSettings(map) {
    if (sessionStorage.getItem('mapSettings')) {
        const settings = JSON.parse(sessionStorage.getItem('mapSettings'));

        let settingUserMarker = document.getElementById('showUserMarker');
        if (!settings['showUserMarker']) {
            settingUserMarker.checked = false;
            userMarkers.forEach(marker => map.removeLayer(marker));
        } else if (settings['showUserMarker'] === "true") {
            settingUserMarker.checked = true;
            userMarkers.forEach(marker => map.addLayer(marker));
        }

        let settingBudBashMarker = document.getElementById('showBudBashMarker');
        if (!settings['showBudBashMarker']) {
            settingBudBashMarker.checked = false;
            budBashMarkers.forEach(marker => map.removeLayer(marker));
        } else if (settings['showBudBashMarker'] === "true") {
            settingBudBashMarker.checked = true;
            budBashMarkers.forEach(marker => map.addLayer(marker));
        }

        let settingPublicMarker = document.getElementById('showPublicMarker');
        if (!settings['showPublicMarker']) {
            settingPublicMarker.checked = false;
            publicMarkers.forEach(marker => map.removeLayer(marker));
        } else if (settings['showPublicMarker'] === "true") {
            settingPublicMarker.checked = true;
            publicMarkers.forEach(marker => map.addLayer(marker));
        }
    }
}