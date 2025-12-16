// --- Carruseles Hoteles (inalterado) ---
const carousels = [
  { 
    id: 'carousel1', 
    folder: 'mvc/img/ciudad/', 
    prefix: 'img', 
    extension: '.jpg', 
    total: 5, 
    interval: 5000 
  },
  { 
    id: 'carousel2', 
    folder: 'mvc/img/playa/', 
    prefix: 'img', 
    extension: '.jpg', 
    total: 5, 
    interval: 5000 
  },
  { 
    id: 'carousel3', 
    folder: 'mvc/img/montaña/', 
    prefix: 'img', 
    extension: '.jpg', 
    total: 5, 
    interval: 5000 
  }
];

document.addEventListener('DOMContentLoaded', () => carousels.forEach(cfg => initCarousel(cfg)));
function initCarousel({ id, folder, prefix, extension, total, interval }) {
  let current=1; const container=document.getElementById(id), img=container.querySelector('.carousel-image');
  img.addEventListener('load', ()=>img.classList.add('show'),{once:true}); 
  img.src=`${folder}${prefix}${current}${extension}`;
  setInterval(()=>{ 
    img.classList.remove('show'); current=(current%total)+1; setTimeout(()=>img.src=`${folder}${prefix}${current}${extension}`,500);
  },interval);
}

// --- Carousel Habitaciones Dinámico de Varias Fotos por Habitación ---
const roomConfig = {
  wrapperId: 'roomWrapper',
  folder: 'mvc/img_habitaciones/',   // Carpeta correcta
  extension: '.jpg',
  totalRooms: 20,       // Número de habitaciones
  photosPerRoom: 3,     // Número de fotos por habitación
  scrollAmount: 400
};

// Combinar inicialización en un solo DOMContentLoaded
document.addEventListener('DOMContentLoaded', () => {
  carousels.forEach(cfg => initCarousel(cfg));
  initRoomCarousel(roomConfig);
});

function initRoomCarousel({ wrapperId, folder, extension, totalRooms, photosPerRoom, scrollAmount }) {
  const wrapper = document.getElementById(wrapperId);
  if (!wrapper) return;

  // Crear e insertar tarjetas dinámicamente: idNfotoM para cada habitación y foto
  for (let roomId = 1; roomId <= totalRooms; roomId++) {
    for (let photoIndex = 1; photoIndex <= photosPerRoom; photoIndex++) {
      const card = document.createElement('div');
      card.className = 'room-card';

      const img = document.createElement('img');
      img.src = `${folder}id${roomId}foto${photoIndex}${extension}`;
      img.alt = `Habitación ${roomId} - Foto ${photoIndex}`;
      img.onerror = () => card.remove(); // Elimina tarjeta si no existe la imagen

      card.appendChild(img);
      wrapper.appendChild(card);
    }
  }

  // Botones de navegación
  const prevBtn = document.getElementById('room-prev');
  const nextBtn = document.getElementById('room-next');

  prevBtn.addEventListener('click', () => {
    wrapper.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
  });
  nextBtn.addEventListener('click', () => {
    wrapper.scrollBy({ left: scrollAmount, behavior: 'smooth' });
  });
}
