<?php
// ============================================
// SUBHAN PRINTERS - Footer Template
// Use this on ALL pages
// ============================================

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$year = date('Y');
$pageScripts = $pageScripts ?? 'index.js';
?>
<!-- ==========================================
     FOOTER
     ========================================== -->
<footer>
    <div class="container">
        <div class="footer-grid">
            <!-- Brand -->
            <div>
                <div class="footer-logo">
                    <img src="/SP/images/logo.png" alt="Subhan Printers" width="36" onerror="this.style.display='none'" />
                    Subhan <span>Printers</span>
                </div>
                <p class="footer-desc">
                    Your trusted partner for professional graphic designing and printing services in Gawalmandi, Lahore, Pakistan.
                </p>
                <div class="footer-socials">
                    <a href="#" class="footer-social" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="footer-social" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://wa.me/923001234567" target="_blank" rel="noopener" class="footer-social" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <a href="mailto:info@subhanprinters.com" class="footer-social" aria-label="Email"><i class="fas fa-envelope"></i></a>
                </div>
            </div>

            <!-- Quick links -->
            <div>
                <h4 class="footer-col-title">Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="/SP/"><i class="fas fa-chevron-right"></i> Home</a></li>
                    <li><a href="/SP/services"><i class="fas fa-chevron-right"></i> Services</a></li>
                    <li><a href="/SP/portfolio"><i class="fas fa-chevron-right"></i> Portfolio</a></li>
                    <li><a href="/SP/about"><i class="fas fa-chevron-right"></i> About Us</a></li>
                    <li><a href="/SP/contact"><i class="fas fa-chevron-right"></i> Contact</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div>
                <h4 class="footer-col-title">Our Services</h4>
                <ul class="footer-links">
                    <li><a href="/SP/services"><i class="fas fa-check" style="color:#8b5cf6;"></i> Graphic Designing</a></li>
                    <li><a href="/SP/services"><i class="fas fa-check" style="color:#8b5cf6;"></i> Printing Solutions</a></li>
                    <li><a href="/SP/services"><i class="fas fa-check" style="color:#8b5cf6;"></i> Packaging Designs</a></li>
                    <li><a href="/SP/services"><i class="fas fa-check" style="color:#8b5cf6;"></i> Corporate Branding</a></li>
                    <li><a href="/SP/services"><i class="fas fa-check" style="color:#8b5cf6;"></i> Wedding Cards</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="footer-col-title">Contact Info</h4>
                <div class="footer-contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Abaseen Center, Gawalmandi, Lahore, Pakistan</span>
                </div>
                <div class="footer-contact-item">
                    <i class="fas fa-phone"></i>
                    <a href="tel:+923001234567">+92 300 4197033</a>
                </div>
                <div class="footer-contact-item">
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:info@subhanprinters.com">info@subhanprinters.com</a>
                </div>
                <div class="footer-contact-item">
                    <i class="fas fa-clock"></i>
                    <span>Mon–Sat:10 AM – 8 PM</span>
                </div>
            </div>
        </div>

        <!-- Payment logos -->
        <div class="footer-payments">
            <span class="footer-payments-label">We Accept</span>
            <div class="footer-payments-logos">
                <!-- Mastercard -->
                <div class="pay-logo-img" title="Mastercard">
                    <img src="/SP/images/pngwing.com.png" alt="Mastercard" />
                </div>
                <!-- VISA -->
                <div class="pay-logo-img" title="VISA">
                    <img src="/SP/images/pngwing.com%20(1).png" alt="Visa" />
                </div>
                <!-- Sadapay -->
                <div class="pay-logo-img" title="Sadapay">
                    <img src="/SP/images/Sadapay-Logo.png" alt="Sadapay" />
                </div>
                <!-- JazzCash -->
                <div class="pay-logo-img" title="JazzCash">
                    <img src="/SP/images/jazzcash.png" alt="JazzCash" />
                </div>
                <!-- EasyPaisa -->
                <div class="easypaisa-bg-wrapper" title="EasyPaisa">
                    <div class="easypaisa-logo-img">
                        <img src="/SP/images/easypaisa.png" alt="EasyPaisa" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <p class="footer-bottom-text">
                © <?php echo $year; ?> <strong>Subhan Printers</strong>. All rights reserved. | Hamza Center, Gawalmandi, Pakistan
            </p>
            <div class="footer-bottom-links">
                <?php if ($isLoggedIn): ?>
                    <a href="/SP/dashboard"><i class="fas fa-th-large"></i> Dashboard</a>
                <?php endif; ?>
                <a href="/SP/privacy">Privacy Policy</a>
                <a href="/SP/terms">Terms of Service</a>
                <a href="/SP/contact">Contact</a>
            </div>
        </div>
    </div>
</footer>

<!-- WhatsApp floating button -->
<a href="https://wa.me/923004197033" target="_blank" rel="noopener" class="wa-float" aria-label="Chat with us on WhatsApp">
    <i class="fab fa-whatsapp" aria-hidden="true"></i>
</a>

<!-- JavaScript -->
<script src="/SP/assets/js/<?php echo $pageScripts; ?>"></script>
<?php if (isset($extraScripts)): ?>
    <?php foreach ($extraScripts as $script): ?>
        <script src="/SP/assets/js/<?php echo $script; ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>