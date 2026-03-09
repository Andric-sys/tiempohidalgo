    </div>
    <!-- End content-wrapper -->

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>Sobre Nosotros</h3>
                <p>El Tiempo de Hidalgo es tu fuente confiable de noticias y reportajes de la región.</p>
            </div>
            
            <div class="footer-section">
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li><a href="<?php echo $base_path; ?>index.php">Inicio</a></li>
                    <li><a href="<?php echo $base_path; ?>index.php#noticias">Noticias</a></li>
                    <li><a href="<?php echo $base_path; ?>galeria.php">Galería</a></li>
                    <li><a href="<?php echo $base_path; ?>files/contacto.php">Contacto</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Síguenos</h3>
                <div class="social-links">
                    <a href="https://www.facebook.com" class="social-link" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.x.com" class="social-link" target="_blank" rel="noopener noreferrer" aria-label="X"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.instagram.com" class="social-link" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div class="footer-section">
                <h3>Contacto Editorial</h3>
                <div class="footer-contact-list">
                    <div class="footer-contact-item">
                        <i class="fas fa-user-edit"></i>
                        <span>José Enrique Flores León</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Puerto Madero Edif. 20 Dep. 3 Fracc. El Palmar, Pachuca de Soto, Hgo.</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fas fa-phone"></i>
                        <span>7141740</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fas fa-mobile-alt"></i>
                        <span>7711812072</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>enryflo@prodigy.net.mx</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2026 El Tiempo de Hidalgo. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="<?php echo $base_path ?? (strpos($_SERVER['PHP_SELF'], '/files/') ? '../' : ''); ?>assets/js/script.js"></script>
</body>
</html>
