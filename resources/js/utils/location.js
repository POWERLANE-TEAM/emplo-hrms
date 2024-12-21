export default class LocationService {
    constructor() {
        this.primaryApi = 'https://nominatim.openstreetmap.org/reverse?format=json';
        this.fallbackApi = 'https://api.bigdatacloud.net/data/reverse-geocode-client';
    }

    getUserRegion(callback) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => this.showPosition(position, callback),
                this.showError
            );
        }
    }

    showPosition(position, callback) {
        const lat = position.coords.latitude;
        const lon = position.coords.longitude;

        this.fetchRegion(this.primaryApi, lat, lon, callback)
            .catch(() => this.fetchRegion(this.fallbackApi, lat, lon, callback));
    }

    fetchRegion(apiUrl, lat, lon, callback) {
        const url = `${apiUrl}&lat=${lat}&lon=${lon}`;

        return fetch(url)
            .then(response => response.json())
            .then(data => {
                const address = data.address || data;
                console.log(address);
                let region = address.region || address.state || address.county || null;
                console.log('Region:', region);

                if (typeof callback === 'function') {
                    callback(data, region);
                }
            })
            .catch(error => {
                console.warn('Error:', error);
                throw error;
            });
    }

    showError(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                console.warn("User denied the request for Geolocation.");
                break;
            case error.POSITION_UNAVAILABLE:
                console.warn("Location information is unavailable.");
                break;
            case error.TIMEOUT:
                console.warn("The request to get user location timed out.");
                break;
            case error.UNKNOWN_ERROR:
                console.warn("An unknown error occurred.");
                break;
        }
    }
}
