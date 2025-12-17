document.addEventListener("DOMContentLoaded", function() {
    
    // ---------------------------------------------------------
    // 1. LÓGICA INTELIGENTE DE RUTAS (Funciona en local y servidor)
    // ---------------------------------------------------------
    
    // Lista de carpetas que están "un nivel abajo" en tu proyecto
    const subcarpetas = ["hoteles", "contactos", "reservas", "perfil", "registro", "inicio_session", "footer"];
    
    // Detectamos si la URL actual contiene alguna de esas carpetas
    const enSubcarpeta = subcarpetas.some(carpeta => window.location.href.includes("/" + carpeta + "/"));

    // Si estamos en una subcarpeta, usamos ".." para salir. Si no, usamos "." para quedarnos.
    const ruta = enSubcarpeta ? ".." : ".";


    // ---------------------------------------------------------
    // 2. DEFINIMOS EL HTML DEL MENÚ USANDO LA VARIABLE ${ruta}
    // ---------------------------------------------------------
    const menuHTML = `
    <nav>
        <div class="logo">
            <img src="${ruta}/imagenes/img/logo.png" alt="Logo">
            <h1 class="logo-title">ELEMENTAL STAY</h1>
        </div>

        <input type="checkbox" id="menu-toggle" class="menu-toggle" />
        <label for="menu-toggle" class="menu-icon">
            <span></span><span></span><span></span>
        </label>

        <ul>
            <li><a href="${ruta}/index.html" id="boton-superior"><b>INICIO</b></a></li>
            <li><a href="${ruta}/reservas/reservas.html" class="boton-superior"><b>RESERVAS</b></a></li>            
            <li><a href="${ruta}/hoteles/hoteles.html" id="boton-superior"><b>HOTELES</b></a></li>
            <li><a href="${ruta}/contactos/contactos.html" id="boton-superior"><b>CONTACTO</b></a></li>
            
            <li class="profile" id="profileLi" style="display: none;">
                <a href="javascript:void(0);" id="profileButton"><b>PERFIL</b></a>
                <div class="profile-menu" id="profileMenu">
                    <a href="${ruta}/perfil/perfil.html">Ver Perfil</a>
                    <a href="#" id="btnLogout" class="logout">Cerrar Sesión</a>
                </div>
            </li>

            <li id="loginLi">
                <a href="${ruta}/inicio_session/inicio_session.html" id="boton-superior"><b>INICIAR SESIÓN</b></a>
            </li>
        </ul>
    </nav>
    `;

    // 3. Inyectamos el menú al principio del body
    document.body.insertAdjacentHTML('afterbegin', menuHTML);

    // 4. Inicializamos la lógica (clicks, etc.)
    initMenuLogic();
});

function initMenuLogic() {
    const profileBtn = document.getElementById('profileButton');
    const logoutBtn = document.getElementById('btnLogout');
    const profileLi = document.getElementById('profileLi');
    const loginLi = document.getElementById('loginLi');

    // Toggle del menú perfil
    if(profileBtn) {
        profileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const menu = document.getElementById('profileMenu');
            if (menu) menu.classList.toggle('show');
        });
    }

    // Cerrar sesión
    if(logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            alert("Has cerrado sesión (Modo Demo)");
            if(profileLi) profileLi.style.display = 'none';
            if(loginLi) loginLi.style.display = 'block';
            const menu = document.getElementById('profileMenu');
            if (menu) menu.classList.remove('show');
        });
    }

    // Cerrar menú al hacer clic fuera
    window.addEventListener('click', function(e) {
        if (!e.target.matches('#profileButton') && !e.target.closest('.profile-menu')) {
            const menu = document.getElementById('profileMenu');
            if (menu && menu.classList.contains('show')) {
                menu.classList.remove('show');
            }
        }
    });

    // Efecto Scroll
    window.addEventListener('scroll', function() {
        const nav = document.querySelector('nav');
        if (nav) {
            if (window.scrollY > 50) nav.classList.add('scrolled');
            else nav.classList.remove('scrolled');
        }
    });
}