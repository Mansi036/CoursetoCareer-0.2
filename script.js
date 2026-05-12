const searchBox = document.getElementById("searchBox");
const suggestionBox = document.getElementById("suggestions");

// 🌍 GLOBAL USER LOCATION
let userLat = null;
let userLng = null;

// 📍 GET USER LOCATION
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
        (position) => {
            userLat = position.coords.latitude;
            userLng = position.coords.longitude;
            console.log("User Location:", userLat, userLng);
        },
        (error) => {
            console.log("Location permission denied");
        }
    );
}

// 🔍 SEARCH FUNCTION
if (searchBox) {
    searchBox.addEventListener("input", function () {

        let term = this.value.trim();

        if (term.length === 0) {
            suggestionBox.innerHTML = "";
            return;
        }

        fetch("search.php?term=" + term)
            .then(res => res.json())
            .then(data => {

                suggestionBox.innerHTML = "";

                data.forEach(c => {

                    let div = document.createElement("div");
                    div.innerText = c;

                    div.style.padding = "8px";
                    div.style.cursor = "pointer";

                    div.onclick = () => {
                        searchBox.value = c;
                        suggestionBox.innerHTML = "";

                        // 👉 OPTIONAL: Redirect with location
                        if (userLat && userLng) {
                            window.location.href =
                                "explore_colleges.php?field=" + encodeURIComponent(c) +
                                "&lat=" + userLat +
                                "&lng=" + userLng;
                        } else {
                            window.location.href =
                                "explore_colleges.php?field=" + encodeURIComponent(c);
                        }
                    };

                    suggestionBox.appendChild(div);
                });
            });
    });
}


// 📏 DISTANCE FUNCTION (USE ANYWHERE)
function getDistance(lat1, lon1, lat2, lon2) {
    let R = 6371;

    let dLat = (lat2 - lat1) * Math.PI / 180;
    let dLon = (lon2 - lon1) * Math.PI / 180;

    let a =
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(lat1 * Math.PI / 180) *
        Math.cos(lat2 * Math.PI / 180) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2);

    let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return (R * c).toFixed(2);
}