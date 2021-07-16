$(document).ready(function () {

    /* Le $ devant le nom de variable est une convention de nommage pour indiquer que la variable contient un element HTML */
    let $map = document.querySelector('#map')

    class LeafletMap {

        constructor() {
            this.map = null
            this.bounds = []
            this.homeIcon = ''
        }

        async load(element) {

            this.map = L.map(element)
            L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/streets-v11/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoiZndpbGxlbWluIiwiYSI6ImNqamd2eDNldTFraXIza3Ayb3c3a2g5MzUifQ.oQNXaBPv3e-1-l7Kshn4Lw', {
                attribution: '&copy; Xanthellis compagny',
                maxZoom: 13,
                id: 'mapbox.streets',
                accessToken: 'pk.eyJ1IjoiZndpbGxlbWluIiwiYSI6ImNqamd2eDNldTFraXIza3Ayb3c3a2g5MzUifQ.oQNXaBPv3e-1-l7Kshn4Lw',
                zoomSnap: 0.1
            }).addTo(this.map)

            this.homeIcon = L.icon({
                iconUrl: path + '/assets/leaflet/images/marker-icon-red.png',
                iconSize: [25, 41],
                popupAnchor: [-3, 10],
                shadowUrl: 'my-icon-shadow.png',
                shadowSize: [25, 41]
            })


        }

        addMarker(lat, lon, text) {
            let point = [lat, lon];
            this.bounds.push(point)

            L.marker(point).addTo(this.map)
        }
        addBase(lat, lon, text) {
            let point = [lat, lon];
            this.bounds.push(point)

            L.marker(point, {icon: this.homeIcon}).addTo(this.map)
        }

        center() {
            this.map.fitBounds(this.bounds, {
                padding: [30, 30]
            })
        }

    }

    const initMap = async function () {

        let map = new LeafletMap()
        await map.load($map)

        Array.from($('.js-marker')).forEach((item) => {
            if (item.dataset.text == 'BASE') {
                map.addBase(item.dataset.latitude, item.dataset.longitude, item.dataset.text);
            } else {
                map.addMarker(item.dataset.latitude, item.dataset.longitude, item.dataset.text);
            }
        })

        map.center()

    }

    if ($map !== null) {
        initMap();
    }

});


