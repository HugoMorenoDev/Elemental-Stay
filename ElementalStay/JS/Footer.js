document.addEventListener("DOMContentLoaded", function() {
    const subcarpetas = ["hoteles", "contactos", "reservas", "perfil", "registro", "inicio_session", "footer"];
    const enSubcarpeta = subcarpetas.some(carpeta => window.location.href.includes("/" + carpeta + "/"));
    const ruta = enSubcarpeta ? ".." : ".";

    const footerHTML = `
    <footer>
        <div class="footer-container">
            
            <div class="footer-section">
                <h4>Sobre Nosotros</h4>
                <p>Elemental Stay es tu plataforma de confianza para encontrar los mejores alojamientos. Calidad, confort y los mejores precios garantizados.</p>
            </div>

            <div class="footer-section">
                <h4>Enlaces Útiles</h4>
                <ul>
                    <li><a href="${ruta}/index.html">Inicio</a></li>
                    <li><a href="${ruta}/footer/AcercaDe.html">Acerca de nosotros</a></li>
                    <li><a href="${ruta}/contactos/contactos.html">Contacto</a></li>
                    <li><a href="${ruta}/footer/TerminosyCondiciones.html">Términos y Condiciones</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Síguenos</h4>
                <p style="margin-bottom: 15px;">Mantente al día de nuestras ofertas.</p>
                <div class="social-icons">
                    <a href="#"><img src="${ruta}/icon-facebook.svg" alt="Facebook"></a>
                    <a href="#"><img src="${ruta}/icon-twitter.svg" alt="Twitter"></a>
                    <a href="#"><img src="${ruta}/icon-instagram.svg" alt="Instagram"></a>
                    <a href="#"><img src="${ruta}/icon-youtube.svg" alt="YouTube"></a>
                </div>
            </div>

        </div>

        <div class="footer-bottom">
            <p>&copy; 2024 Elemental Stay. Todos los derechos reservados.</p>
        </div>
    </footer>
    `;

    document.body.insertAdjacentHTML('beforeend', footerHTML);
});