document.getElementById('busquedaForm').addEventListener('submit', function(e) {
    // Limpiar alertas previas
    const alertaExistente = document.getElementById('errorMsg');
    if (alertaExistente) alertaExistente.remove();

    var entrada = document.getElementById('entrada').value;
    var salida = document.getElementById('salida').value;
    var precioMin = parseFloat(document.getElementById('precio_min').value);
    var precioMax = parseFloat(document.getElementById('precio_max').value);
    let mensaje = '';

    if (entrada >= salida) {
      mensaje = 'La fecha de entrada debe ser anterior a la fecha de salida.';
    } else if (!isNaN(precioMin) && !isNaN(precioMax) && precioMin >= precioMax) {
      mensaje = 'El precio mínimo debe ser menor que el precio máximo.';
    }

    if (mensaje !== '') {
      e.preventDefault();

      const alert = document.createElement('div');
      alert.className = 'alert';
      alert.id = 'errorMsg';
      alert.innerHTML = `
        <svg viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M18 10A8 8 0 112 10a8 8 0 0116 0zm-8-.75a.75.75 0 00-.75.75v3a.75.75 0 001.5 0v-3A.75.75 0 0010 9.25zm0 6a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"/>
        </svg>
        <span>${mensaje}</span>
        <button class="close-btn" aria-label="Cerrar alerta">&times;</button>
      `;
      document.body.insertBefore(alert, document.body.firstChild);

      alert.querySelector('.close-btn').addEventListener('click', function() {
        alert.remove();
      });
    }
  });