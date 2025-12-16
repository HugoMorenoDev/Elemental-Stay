// Lista de hoteles con sus nombres y ubicaciones
const hoteles = [
    { nombre: "Hotel Sol", lat: 37.7749, lng: -122.4194 },
    { nombre: "Mar y Arena", lat: 34.0522, lng: -118.2437 },
    { nombre: "Montaña Azul", lat: 40.7128, lng: -74.006 },
    { nombre: "Rincón Colonial", lat: 41.9028, lng: 12.4964 }
];

// Elementos del DOM
const hotelSelect = document.getElementById("hotel-select");
const hotelList = document.getElementById("hotel-list");
const googleMap = document.getElementById("google-map");

// Cargar opciones en el select
hoteles.forEach((hotel) => {
    const option = document.createElement("option");
    option.value = hotel.nombre;
    option.textContent = hotel.nombre;
    hotelSelect.appendChild(option);
});

// Mostrar/ocultar hoteles según selección
hotelSelect.addEventListener("change", (event) => {
    const selectedHotel = event.target.value;
    const hotelItems = document.querySelectorAll(".result-item");

    hotelItems.forEach((item) => {
        const hotelName = item.getAttribute("data-hotel");
        if (selectedHotel === "all" || hotelName === selectedHotel) {
            item.style.display = "flex";
        } else {
            item.style.display = "none";
        }
    });

    // Actualizar mapa si se selecciona un hotel específico
    if (selectedHotel !== "all") {
        const hotelData = hoteles.find((hotel) => hotel.nombre === selectedHotel);
        if (hotelData) {
            const { lat, lng } = hotelData;
            googleMap.src = `https://www.google.com/maps/embed/v1/place?key=YOUR_API_KEY&q=${lat},${lng}`;
        }
    }
});
