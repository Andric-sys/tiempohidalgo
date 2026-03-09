// Inicializar Swiper (Carrusel)
document.addEventListener('DOMContentLoaded', function() {
    const swiper = new Swiper('.mySwiper', {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        spaceBetween: 0,
        slidesPerView: 1,
    });

    // Búsqueda en tiempo real
    const searchInput = document.getElementById('mainSearchInput');
    const noticiasCards = document.querySelectorAll('.noticia-card');

    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            let visibleCount = 0;

            noticiasCards.forEach(card => {
                const titulo = card.getAttribute('data-titulo');
                if (titulo && titulo.includes(searchTerm)) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Mostrar mensaje si no hay resultados
            const grid = document.getElementById('noticiasGrid');
            const noResults = grid.querySelector('.sin-noticias');
            
            if (visibleCount === 0 && grid.children.length > 1) {
                if (!noResults) {
                    const p = document.createElement('p');
                    p.className = 'sin-noticias';
                    p.textContent = 'No se encontraron noticias';
                    p.style.gridColumn = '1 / -1';
                    grid.appendChild(p);
                }
            } else if (noResults && visibleCount > 0) {
                noResults.remove();
            }
        });
    }

    // Búsqueda desde el navbar
    const searchBtn = document.querySelector('.search-btn');
    const searchNavbar = document.getElementById('searchInput');

    if (searchBtn && searchNavbar) {
        searchBtn.addEventListener('click', function() {
            const searchTerm = searchNavbar.value.toLowerCase();
            if (searchInput) {
                searchInput.value = searchTerm;
                searchInput.dispatchEvent(new Event('keyup'));
                searchInput.focus();
            }
        });

        // Buscar al presionar Enter en el navbar
        searchNavbar.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchBtn.click();
            }
        });
    }

    // Smooth scroll para los enlaces de navegación
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href.includes('#')) {
                const target = href.split('#')[1];
                if (target) {
                    e.preventDefault();
                    const element = document.getElementById(target);
                    if (element) {
                        element.scrollIntoView({ behavior: 'smooth' });
                    }
                }
            }
        });
    });
});
