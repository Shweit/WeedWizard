import L from 'leaflet';
import 'leaflet.locatecontrol';
import 'leaflet.vectorgrid';
import * as GeoSearch from 'leaflet-geosearch';

// Load Leaflet and LocateControl CSS
window.loadCSS([
    'https://unpkg.com/leaflet/dist/leaflet.css',
    'https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.css',
    'https://unpkg.com/leaflet-geosearch/dist/geosearch.css',
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

    const lc = L.control.locate({
        position: 'topleft',
        strings: {
            title: "Meine Position anzeigen",
        },
        flyTo: true,
    }).addTo(map);

    lc.start();


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
});