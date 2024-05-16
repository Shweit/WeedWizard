import L from 'leaflet';

// Initialize the map
document.addEventListener('DOMContentLoaded', () => {
    // Check if map is already initialized
    const container = L.DomUtil.get('map');
    if(container != null){
        container._leaflet_id = null;
    }

    const map = L.map('map').setView([51.2075217, 6.7063201], 13);

    setTimeout(function () { map.invalidateSize() }, 800);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Example marker
    //L.marker([51.2075217, 6.7063201]).addTo(map)
      // .bindPopup('A pretty CSS3 popup.<br> Easily customizable.')
        //.openPopup();
});
