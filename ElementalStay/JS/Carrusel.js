// --- Carruseles Hoteles (Arriba) ---
const carousels = [
  { 
    id: 'carousel1', 
    folder: 'img/', // IMPORTANTE: Sin barra al principio
    prefix: 'hotelPrincipal', // Ajusta esto si tus fotos se llaman diferente
    extension: '.jpg', 
    total: 1, // Ajusta si tienes más fotos
    interval: 5000 
  },
  { 
    id: 'carousel2', 
    folder: 'imagenes/images/', 
    prefix: 's', 
    extension: '.jpg', 
    total: 4, 
    interval: 5000 
  },
  { 
    id: 'carousel3', 
    folder: 'imagenes/images/', 
    prefix: 's', 
    extension: '.jpg', 
    total: 4, 
    interval: 5000 
  }
];

// --- Carousel Habitaciones (Abajo) ---
const roomConfig = {
  wrapperId: 'roomWrapper',
  folder: 'imagenes/img_habitaciones/', // IMPORTANTE: Sin barra al principio
  extension: '.jpg',
  totalRooms: 6,     // Ajustado a 6 según tus carpetas
  photosPerRoom: 3,  // Fotos por habitación
  scrollAmount: 400
};

// Inicialización
document.addEventListener('DOMContentLoaded', () => {
  // Iniciar carruseles superiores
  carousels.forEach(cfg => {
      if(document.getElementById(cfg.id)) initCarousel(cfg);
  });

  // Iniciar carrusel de habitaciones
  if(document.getElementById(roomConfig.wrapperId)) {
      initRoomCarousel(roomConfig);
  }
});

function initCarousel({ id, folder, prefix, extension, total, interval }) {
  let current = 1; 
  const container = document.getElementById(id);
  const img = container ? container.querySelector('.carousel-image') : null;
  
  if(!img) return;

  // Si solo hay 1 foto, no hacemos intervalo
  if(total <= 1) return;

  setInterval(() => { 
    current = (current % total) + 1; 
    img.src = `${folder}${prefix}${current}${extension}`;
  }, interval);
}

function initRoomCarousel({ wrapperId, folder, extension, totalRooms, photosPerRoom, scrollAmount }) {
  const wrapper = document.getElementById(wrapperId);
  if (!wrapper) return;

  // Crear tarjetas
  for (let roomId = 1; roomId <= totalRooms; roomId++) {
    for (let photoIndex = 1; photoIndex <= photosPerRoom; photoIndex++) {
      
      const card = document.createElement('div');
      card.className = 'room-card'; // Usamos la clase de tu CSS

      const img = document.createElement('img');
      // CORRECCIÓN: Quitada la barra / del principio
      img.src = `${folder}id${roomId}foto${photoIndex}${extension}`;
      img.alt = `Habitación ${roomId} - Foto ${photoIndex}`;
      
      // Si la imagen falla, quitamos la tarjeta
      img.onerror = () => {
        console.warn(`No se encontró la imagen: ${img.src}`);
        card.remove(); 
      };

      card.appendChild(img);
      wrapper.appendChild(card);
    }
  }

  // Flechas
  const prevBtn = document.getElementById('room-prev');
  const nextBtn = document.getElementById('room-next');

  if(prevBtn) prevBtn.addEventListener('click', () => {
    wrapper.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
  });
  
  if(nextBtn) nextBtn.addEventListener('click', () => {
    wrapper.scrollBy({ left: scrollAmount, behavior: 'smooth' });
  });
}