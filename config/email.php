<?php
// ============================================
// SUBHAN PRINTERS - Email Configuration
// ============================================

// Load PHPMailer (Composer required)
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    /**
     * @var PHPMailer PHPMailer instance
     */
    private $mail;
    
    /**
     * @var bool Whether email is enabled
     */
    private $enabled = true;
    
    /**
     * @var string Default from email
     */
    private $fromEmail;
    
    /**
     * @var string Default from name
     */
    private $fromName;
    
    /**
     * @var array Email templates
     */
    private $templates = [];

    /**
     * Constructor - Configure PHPMailer
     */
    public function __construct() {
        $this->mail = new PHPMailer(true);
        
        // Check if email is enabled
        $this->enabled = getenv('SMTP_ENABLED') !== 'false';
        $this->fromEmail = getenv('SMTP_FROM_EMAIL') ?: 'info@subhanprinters.com';
        $this->fromName = getenv('SMTP_FROM_NAME') ?: 'Subhan Printers';
        
        if (!$this->enabled) {
            return;
        }
        
        $this->configureSMTP();
    }
    
    /**
     * Configure SMTP settings
     */
    private function configureSMTP(): void {
        try {
            // Server settings
            $this->mail->isSMTP();
            $this->mail->Host = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = getenv('SMTP_USERNAME');
            $this->mail->Password = getenv('SMTP_PASSWORD');
            $this->mail->Port = getenv('SMTP_PORT') ?: 587;
            
            // Security
            $secure = getenv('SMTP_SECURE') ?: 'tls';
            if ($secure === 'tls') {
                $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            } elseif ($secure === 'ssl') {
                $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            }
            
            // Timeout
            $this->mail->Timeout = 30;
            
            // Character encoding
            $this->mail->CharSet = 'UTF-8';
            $this->mail->Encoding = 'base64';
            
            // Default sender
            $this->mail->setFrom($this->fromEmail, $this->fromName);
            
        } catch (Exception $e) {
            error_log("SMTP Configuration error: " . $e->getMessage());
            $this->enabled = false;
        }
    }

    // ============================================================
    // SEND METHODS
    // ============================================================

    /**
     * Send order confirmation email to customer
     * 
     * @param array $orderData Order data from database
     * @return bool
     */
    public function sendOrderConfirmation(array $orderData): bool {
        if (!$this->enabled) {
            return $this->logEmail('Order confirmation', $orderData['customer_email']);
        }
        
        try {
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();
            
            $this->mail->addAddress($orderData['customer_email'], $orderData['customer_name']);
            $this->mail->addReplyTo($this->fromEmail, $this->fromName);
            
            $this->mail->isHTML(true);
            $this->mail->Subject = "Order Confirmation #{$orderData['order_number']} - Subhan Printers";
            $this->mail->Body = $this->getOrderEmailHTML($orderData);
            $this->mail->AltBody = strip_tags($this->getOrderEmailHTML($orderData));
            
            return $this->mail->send();
            
        } catch (Exception $e) {
            error_log("Order confirmation email error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send quote response email to customer
     * 
     * @param array $quoteData Quote data from database
     * @return bool
     */
    public function sendQuoteResponse(array $quoteData): bool {
        if (!$this->enabled) {
            return $this->logEmail('Quote response', $quoteData['customer_email']);
        }
        
        try {
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();
            
            $this->mail->addAddress($quoteData['customer_email'], $quoteData['customer_name']);
            $this->mail->addReplyTo($this->fromEmail, $this->fromName);
            
            $this->mail->isHTML(true);
            $this->mail->Subject = "Your Quote #{$quoteData['quote_number']} - Subhan Printers";
            $this->mail->Body = $this->getQuoteEmailHTML($quoteData);
            $this->mail->AltBody = strip_tags($this->getQuoteEmailHTML($quoteData));
            
            return $this->mail->send();
            
        } catch (Exception $e) {
            error_log("Quote email error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send newsletter welcome email to new subscriber
     * 
     * @param string $email Subscriber email
     * @param string $name Subscriber name (optional)
     * @return bool
     */
    public function sendNewsletterWelcome(string $email, string $name = ''): bool {
        if (!$this->enabled) {
            return $this->logEmail('Newsletter welcome', $email);
        }
        
        try {
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();
            
            $this->mail->addAddress($email, $name ?: 'Subscriber');
            $this->mail->addReplyTo($this->fromEmail, $this->fromName);
            
            $this->mail->isHTML(true);
            $this->mail->Subject = "Welcome to Subhan Printers Newsletter!";
            $this->mail->Body = $this->getNewsletterWelcomeHTML($email, $name);
            $this->mail->AltBody = strip_tags($this->getNewsletterWelcomeHTML($email, $name));
            
            return $this->mail->send();
            
        } catch (Exception $e) {
            error_log("Newsletter welcome email error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send contact form email to admin
     * 
     * @param array $data Contact form data
     * @return bool
     */
    public function sendContactEmail(array $data): bool {
        if (!$this->enabled) {
            return $this->logEmail('Contact form', getenv('ADMIN_EMAIL') ?: 'info@subhanprinters.com');
        }
        
        try {
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();
            
            // Send to admin
            $adminEmail = getenv('ADMIN_EMAIL') ?: 'info@subhanprinters.com';
            $this->mail->addAddress($adminEmail, 'Subhan Printers Admin');
            $this->mail->addReplyTo($data['email'], $data['name']);
            
            $this->mail->isHTML(true);
            $this->mail->Subject = "Contact Form: " . ($data['subject'] ?: 'New Message from Website');
            $this->mail->Body = $this->getContactEmailHTML($data);
            $this->mail->AltBody = strip_tags($this->getContactEmailHTML($data));
            
            // Auto-reply to customer
            $this->mail->clearAddresses();
            $this->mail->addAddress($data['email'], $data['name']);
            $this->mail->Subject = "Thank You for Contacting Subhan Printers";
            $this->mail->Body = $this->getContactAutoReplyHTML($data);
            $this->mail->AltBody = strip_tags($this->getContactAutoReplyHTML($data));
            
            return $this->mail->send();
            
        } catch (Exception $e) {
            error_log("Contact email error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send admin notification for new quote
     * 
     * @param array $quoteData Quote data
     * @return bool
     */
    public function sendNewQuoteNotification(array $quoteData): bool {
        if (!$this->enabled) {
            return $this->logEmail('New quote notification', getenv('ADMIN_EMAIL') ?: 'info@subhanprinters.com');
        }
        
        try {
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();
            
            $adminEmail = getenv('ADMIN_EMAIL') ?: 'info@subhanprinters.com';
            $this->mail->addAddress($adminEmail, 'Subhan Printers Admin');
            $this->mail->addReplyTo($quoteData['customer_email'], $quoteData['customer_name']);
            
            $this->mail->isHTML(true);
            $this->mail->Subject = "New Quote Request #{$quoteData['quote_number']}";
            $this->mail->Body = $this->getNewQuoteNotificationHTML($quoteData);
            $this->mail->AltBody = strip_tags($this->getNewQuoteNotificationHTML($quoteData));
            
            return $this->mail->send();
            
        } catch (Exception $e) {
            error_log("New quote notification error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send order status update email
     * 
     * @param array $orderData Order data
     * @param string $oldStatus Previous status
     * @param string $newStatus New status
     * @return bool
     */
    public function sendOrderStatusUpdate(array $orderData, string $oldStatus, string $newStatus): bool {
        if (!$this->enabled) {
            return $this->logEmail('Order status update', $orderData['customer_email']);
        }
        
        try {
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();
            
            $this->mail->addAddress($orderData['customer_email'], $orderData['customer_name']);
            $this->mail->addReplyTo($this->fromEmail, $this->fromName);
            
            $this->mail->isHTML(true);
            $this->mail->Subject = "Order #{$orderData['order_number']} Status Update - Subhan Printers";
            $this->mail->Body = $this->getOrderStatusUpdateHTML($orderData, $oldStatus, $newStatus);
            $this->mail->AltBody = strip_tags($this->getOrderStatusUpdateHTML($orderData, $oldStatus, $newStatus));
            
            return $this->mail->send();
            
        } catch (Exception $e) {
            error_log("Order status update email error: " . $e->getMessage());
            return false;
        }
    }

    // ============================================================
    // EMAIL TEMPLATES
    // ============================================================

    /**
     * Get order confirmation email HTML template
     */
    private function getOrderEmailHTML(array $data): string {
        $statusColor = $this->getStatusColor($data['status'] ?? 'pending');
        $statusLabel = $this->getStatusLabel($data['status'] ?? 'pending');
        $productType = ucwords(str_replace('_', ' ', $data['product_type'] ?? ''));
        $total = number_format($data['total'] ?? 0, 0);
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Order Confirmation</title>
            <style>
                body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; margin: 0; }
                .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .header { text-align: center; border-bottom: 3px solid #8b5cf6; padding-bottom: 20px; margin-bottom: 25px; }
                .logo { font-size: 28px; font-weight: 800; color: #8b5cf6; font-family: 'Playfair Display', Georgia, serif; }
                .logo span { color: #1a1a2e; }
                .subtitle { color: #666; font-size: 14px; margin: 5px 0 0; }
                h2 { color: #1a1a2e; margin: 0 0 10px; }
                .order-details { background: #f8f8fa; border-radius: 8px; padding: 20px; margin: 20px 0; }
                .order-details table { width: 100%; border-collapse: collapse; }
                .order-details td { padding: 10px 5px; border-bottom: 1px solid #eee; font-size: 14px; }
                .order-details td:last-child { text-align: right; font-weight: 500; }
                .order-details tr:last-child td { border-bottom: none; }
                .status-badge { display: inline-block; padding: 4px 15px; border-radius: 20px; font-size: 12px; font-weight: 600; background: <?php echo $statusColor; ?>; color: #fff; }
                .contact-info { margin: 20px 0; padding: 15px; background: #f3f4f6; border-radius: 8px; font-size: 14px; }
                .contact-info a { color: #8b5cf6; text-decoration: none; }
                .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #666; }
                .button { display: inline-block; padding: 12px 30px; background: #8b5cf6; color: #fff; text-decoration: none; border-radius: 8px; font-weight: 600; }
                .button-wa { background: #25d366; }
                .tracking-info { background: #f0fdf4; border-left: 4px solid #22c55e; padding: 15px; margin: 15px 0; border-radius: 4px; }
                @media (max-width: 480px) { .container { padding: 15px; } .order-details table { font-size: 12px; } }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <div class="logo">Subhan <span>Printers</span></div>
                    <div class="subtitle">✨ Gawalmandi, Lahore</div>
                </div>
                
                <h2>🎉 Order Confirmation</h2>
                <p style="font-size: 16px;">Dear <strong><?php echo htmlspecialchars($data['customer_name']); ?></strong>,</p>
                <p>Thank you for choosing Subhan Printers! Your order has been received and is being processed.</p>
                
                <div class="order-details">
                    <table>
                        <tr><td><strong>Order Number</strong></td><td><strong>#<?php echo htmlspecialchars($data['order_number']); ?></strong></td></tr>
                        <tr><td><strong>Product Type</strong></td><td><?php echo $productType; ?></td></tr>
                        <tr><td><strong>Quantity</strong></td><td><?php echo number_format($data['quantity']); ?> pieces</td></tr>
                        <tr><td><strong>Total Amount</strong></td><td><strong>Rs. <?php echo $total; ?></strong></td></tr>
                        <tr><td><strong>Status</strong></td><td><span class="status-badge"><?php echo $statusLabel; ?></span></td></tr>
                        <?php if (!empty($data['estimated_delivery'])): ?>
                        <tr><td><strong>Estimated Delivery</strong></td><td><?php echo date('F j, Y', strtotime($data['estimated_delivery'])); ?></td></tr>
                        <?php endif; ?>
                    </table>
                </div>
                
                <?php if (!empty($data['tracking_number'])): ?>
                <div class="tracking-info">
                    <strong>📦 Tracking Number:</strong> <?php echo htmlspecialchars($data['tracking_number']); ?>
                </div>
                <?php endif; ?>
                
                <p style="margin: 20px 0;">Our team will contact you shortly with a detailed production timeline. You can also track your order status anytime.</p>
                
                <div class="contact-info">
                    <strong>📞 Have questions?</strong><br>
                    Call us: <a href="tel:+923001234567">+92 300 1234567</a><br>
                    Email: <a href="mailto:info@subhanprinters.com">info@subhanprinters.com</a><br>
                    Visit: Hamza Center, Gawalmandi, Lahore
                </div>
                
                <div style="text-align: center; margin: 25px 0;">
                    <a href="https://wa.me/923001234567?text=Order%20%23<?php echo $data['order_number']; ?>" class="button button-wa">
                        💬 Track on WhatsApp
                    </a>
                </div>
                
                <div class="footer">
                    <p>© <?php echo date('Y'); ?> Subhan Printers - All rights reserved</p>
                    <p>Hamza Center, Gawalmandi, Lahore, Pakistan</p>
                    <p style="font-size: 11px; color: #999;">This is a system-generated email. Please do not reply to this email.</p>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Get quote email HTML template
     */
    private function getQuoteEmailHTML(array $data): string {
        $amount = number_format($data['quote_amount'] ?? 0, 0);
        $deadline = !empty($data['deadline']) ? date('F j, Y', strtotime($data['deadline'])) : 'Not specified';
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Your Quote</title>
            <style>
                body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; margin: 0; }
                .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .header { text-align: center; border-bottom: 3px solid #f59e0b; padding-bottom: 20px; margin-bottom: 25px; }
                .logo { font-size: 28px; font-weight: 800; color: #8b5cf6; font-family: 'Playfair Display', Georgia, serif; }
                .logo span { color: #1a1a2e; }
                .highlight-box { background: #fffbeb; border: 2px solid #f59e0b; border-radius: 12px; padding: 25px; text-align: center; margin: 20px 0; }
                .highlight-box .amount { font-size: 42px; font-weight: 800; color: #d97706; }
                .highlight-box .label { font-size: 14px; color: #666; }
                .highlight-box .valid { font-size: 12px; color: #999; margin-top: 8px; }
                .details { margin: 20px 0; }
                .details table { width: 100%; border-collapse: collapse; }
                .details td { padding: 10px 5px; border-bottom: 1px solid #eee; font-size: 14px; }
                .details td:first-child { color: #666; width: 40%; }
                .details tr:last-child td { border-bottom: none; }
                .button { display: inline-block; padding: 14px 35px; background: #25d366; color: #fff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; }
                .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #666; }
                .whatsapp-contact { background: #f0fdf4; border-left: 4px solid #25d366; padding: 15px; margin: 20px 0; border-radius: 4px; }
                @media (max-width: 480px) { .container { padding: 15px; } .highlight-box .amount { font-size: 30px; } }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <div class="logo">Subhan <span>Printers</span></div>
                    <div class="subtitle">✨ Gawalmandi, Lahore</div>
                </div>
                
                <h2>📄 Your Quote is Ready!</h2>
                <p style="font-size: 16px;">Dear <strong><?php echo htmlspecialchars($data['customer_name']); ?></strong>,</p>
                <p>Thank you for requesting a quote from Subhan Printers. We're excited to work with you!</p>
                
                <div class="highlight-box">
                    <div class="label">Quote #<?php echo htmlspecialchars($data['quote_number']); ?></div>
                    <div class="amount">Rs. <?php echo $amount; ?></div>
                    <div class="valid">✅ Valid for 7 days from <?php echo date('F j, Y'); ?></div>
                </div>
                
                <div class="details">
                    <table>
                        <tr><td>Project Type</td><td><strong><?php echo htmlspecialchars($data['project_type']); ?></strong></td></tr>
                        <tr><td>Quantity</td><td><strong><?php echo number_format($data['quantity']); ?></strong></td></tr>
                        <tr><td>Deadline</td><td><strong><?php echo $deadline; ?></strong></td></tr>
                        <?php if (!empty($data['specifications'])): ?>
                        <tr><td>Specifications</td><td><?php echo nl2br(htmlspecialchars($data['specifications'])); ?></td></tr>
                        <?php endif; ?>
                    </table>
                </div>
                
                <div class="whatsapp-contact">
                    <strong>💬 Ready to proceed?</strong><br>
                    Simply click the button below to accept this quote on WhatsApp.
                </div>
                
                <div style="text-align: center; margin: 25px 0;">
                    <a href="https://wa.me/923001234567?text=I%20accept%20quote%20%23<?php echo $data['quote_number']; ?>" class="button">
                        💬 Accept Quote on WhatsApp
                    </a>
                </div>
                
                <p style="font-size: 14px; color: #666; text-align: center;">Or reply to this email to accept the quote.</p>
                
                <div style="background: #f8f8fa; padding: 15px; border-radius: 8px; margin: 20px 0; font-size: 14px;">
                    <strong>📞 Questions?</strong><br>
                    Call us: <a href="tel:+923001234567">+92 300 1234567</a><br>
                    Email: <a href="mailto:info@subhanprinters.com">info@subhanprinters.com</a>
                </div>
                
                <div class="footer">
                    <p>© <?php echo date('Y'); ?> Subhan Printers - All rights reserved</p>
                    <p>Hamza Center, Gawalmandi, Lahore, Pakistan</p>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Get newsletter welcome email HTML template
     */
    private function getNewsletterWelcomeHTML(string $email, string $name = ''): string {
        $greeting = $name ? "Dear $name" : "Hello";
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Welcome to Our Newsletter</title>
            <style>
                body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; margin: 0; }
                .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .header { text-align: center; border-bottom: 3px solid #8b5cf6; padding-bottom: 20px; margin-bottom: 25px; }
                .logo { font-size: 28px; font-weight: 800; color: #8b5cf6; font-family: 'Playfair Display', Georgia, serif; }
                .logo span { color: #1a1a2e; }
                .subtitle { color: #666; font-size: 14px; margin: 5px 0 0; }
                .features { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin: 25px 0; }
                .feature { background: #f8f8fa; padding: 18px; border-radius: 8px; text-align: center; }
                .feature .icon { font-size: 28px; display: block; margin-bottom: 8px; }
                .feature .title { font-weight: 600; font-size: 14px; color: #1a1a2e; }
                .feature .desc { font-size: 12px; color: #666; margin-top: 4px; }
                .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #666; }
                .unsubscribe { color: #8b5cf6; text-decoration: underline; }
                @media (max-width: 480px) { .features { grid-template-columns: 1fr; } }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <div class="logo">Subhan <span>Printers</span></div>
                    <div class="subtitle">✨ Gawalmandi, Lahore</div>
                </div>
                
                <h2>🎨 Welcome to Subhan Printers!</h2>
                <p><?php echo $greeting; ?>,</p>
                <p>Thank you for subscribing to our newsletter! You're now part of our creative community.</p>
                
                <p style="font-size: 14px; color: #666; margin: 20px 0;">Here's what you can expect from us:</p>
                
                <div class="features">
                    <div class="feature">
                        <span class="icon">🎨</span>
                        <div class="title">Design Inspiration</div>
                        <div class="desc">Latest trends and creative ideas</div>
                    </div>
                    <div class="feature">
                        <span class="icon">🏷️</span>
                        <div class="title">Exclusive Offers</div>
                        <div class="desc">Special discounts and promotions</div>
                    </div>
                    <div class="feature">
                        <span class="icon">📦</span>
                        <div class="title">New Products</div>
                        <div class="desc">Be the first to know what's new</div>
                    </div>
                    <div class="feature">
                        <span class="icon">🎉</span>
                        <div class="title">Seasonal Updates</div>
                        <div class="desc">Holiday collections and events</div>
                    </div>
                </div>
                
                <div style="background: #f8f8fa; padding: 15px; border-radius: 8px; margin: 20px 0; text-align: center;">
                    <strong>📧 Ready to place an order?</strong><br>
                    <a href="https://wa.me/923001234567" style="color: #25d366; font-weight: 600; text-decoration: none;">
                        💬 Chat with us on WhatsApp
                    </a>
                </div>
                
                <p style="font-size: 14px; color: #666;">We'll make sure to bring you only the best content. Stay tuned for our first newsletter!</p>
                
                <div class="footer">
                    <p>© <?php echo date('Y'); ?> Subhan Printers - All rights reserved</p>
                    <p>Hamza Center, Gawalmandi, Lahore, Pakistan</p>
                    <p><a href="https://yourdomain.com/unsubscribe.php?email=<?php echo urlencode($email); ?>" class="unsubscribe">Unsubscribe</a></p>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Get contact form email HTML template (admin)
     */
    private function getContactEmailHTML(array $data): string {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>New Contact Form Submission</title>
            <style>
                body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
                .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; }
                .header { border-bottom: 2px solid #8b5cf6; padding-bottom: 15px; margin-bottom: 20px; }
                .field { padding: 8px 0; }
                .field-label { font-weight: 600; color: #1a1a2e; }
                .message-box { background: #f8f8fa; padding: 15px; border-radius: 8px; margin: 10px 0; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2 style="color: #8b5cf6;">📩 New Contact Form Submission</h2>
                </div>
                
                <div class="field"><span class="field-label">Name:</span> <?php echo htmlspecialchars($data['name']); ?></div>
                <div class="field"><span class="field-label">Email:</span> <?php echo htmlspecialchars($data['email']); ?></div>
                <?php if (!empty($data['phone'])): ?>
                <div class="field"><span class="field-label">Phone:</span> <?php echo htmlspecialchars($data['phone']); ?></div>
                <?php endif; ?>
                <?php if (!empty($data['subject'])): ?>
                <div class="field"><span class="field-label">Subject:</span> <?php echo htmlspecialchars($data['subject']); ?></div>
                <?php endif; ?>
                
                <div class="message-box">
                    <strong>Message:</strong>
                    <p><?php echo nl2br(htmlspecialchars($data['message'])); ?></p>
                </div>
                
                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #666;">
                    <p>Submitted from: <?php echo $_SERVER['REMOTE_ADDR'] ?? 'Unknown'; ?></p>
                    <p>Date: <?php echo date('Y-m-d H:i:s'); ?></p>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Get contact auto-reply email HTML template (customer)
     */
    private function getContactAutoReplyHTML(array $data): string {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Thank You for Contacting Us</title>
            <style>
                body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
                .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; }
                .header { border-bottom: 2px solid #8b5cf6; padding-bottom: 15px; margin-bottom: 20px; }
                .logo { font-size: 24px; font-weight: 800; color: #8b5cf6; }
                .logo span { color: #1a1a2e; }
                .contact-info { background: #f8f8fa; padding: 15px; border-radius: 8px; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <div class="logo">Subhan <span>Printers</span></div>
                </div>
                
                <h2>Thank You for Contacting Us!</h2>
                <p>Dear <strong><?php echo htmlspecialchars($data['name']); ?></strong>,</p>
                <p>Thank you for reaching out to Subhan Printers. We've received your message and will get back to you within <strong>24 hours</strong>.</p>
                
                <div class="contact-info">
                    <strong>📞 In the meantime, you can reach us:</strong><br>
                    Call: <a href="tel:+923001234567">+92 300 1234567</a><br>
                    WhatsApp: <a href="https://wa.me/923001234567">Chat Now</a><br>
                    Visit: Hamza Center, Gawalmandi, Lahore
                </div>
                
                <p style="font-size: 14px; color: #666;">We look forward to assisting you with your printing needs!</p>
                
                <p style="font-size: 12px; color: #999; margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                    This is an automated response. Please do not reply to this email.
                </p>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Get new quote notification email HTML template (admin)
     */
    private function getNewQuoteNotificationHTML(array $data): string {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>New Quote Request</title>
            <style>
                body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
                .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; }
                .header { border-bottom: 2px solid #f59e0b; padding-bottom: 15px; margin-bottom: 20px; }
                .field { padding: 6px 0; }
                .field-label { font-weight: 600; color: #1a1a2e; }
                .action-btn { display: inline-block; padding: 12px 25px; background: #8b5cf6; color: #fff; text-decoration: none; border-radius: 6px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2 style="color: #f59e0b;">📄 New Quote Request</h2>
                    <p style="color: #666;">Quote #<?php echo htmlspecialchars($data['quote_number']); ?></p>
                </div>
                
                <div class="field"><span class="field-label">Customer:</span> <?php echo htmlspecialchars($data['customer_name']); ?></div>
                <div class="field"><span class="field-label">Email:</span> <?php echo htmlspecialchars($data['customer_email']); ?></div>
                <div class="field"><span class="field-label">Phone:</span> <?php echo htmlspecialchars($data['customer_phone']); ?></div>
                <?php if (!empty($data['customer_company'])): ?>
                <div class="field"><span class="field-label">Company:</span> <?php echo htmlspecialchars($data['customer_company']); ?></div>
                <?php endif; ?>
                <div class="field"><span class="field-label">Project Type:</span> <?php echo htmlspecialchars($data['project_type']); ?></div>
                <div class="field"><span class="field-label">Quantity:</span> <?php echo number_format($data['quantity']); ?></div>
                <?php if (!empty($data['specifications'])): ?>
                <div class="field"><span class="field-label">Specifications:</span><br><?php echo nl2br(htmlspecialchars($data['specifications'])); ?></div>
                <?php endif; ?>
                <?php if (!empty($data['budget'])): ?>
                <div class="field"><span class="field-label">Budget:</span> Rs. <?php echo number_format($data['budget'], 0); ?></div>
                <?php endif; ?>
                <?php if (!empty($data['deadline'])): ?>
                <div class="field"><span class="field-label">Deadline:</span> <?php echo date('F j, Y', strtotime($data['deadline'])); ?></div>
                <?php endif; ?>
                
                <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #eee; text-align: center;">
                    <a href="https://wa.me/923001234567?text=Quote%20%23<?php echo $data['quote_number']; ?>" class="action-btn">
                        💬 Respond on WhatsApp
                    </a>
                </div>
                
                <div style="margin-top: 20px; font-size: 12px; color: #666;">
                    <p>Submitted: <?php echo date('Y-m-d H:i:s'); ?></p>
                    <p>Source: <?php echo htmlspecialchars($data['source'] ?? 'website'); ?></p>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Get order status update email HTML template
     */
    private function getOrderStatusUpdateHTML(array $data, string $oldStatus, string $newStatus): string {
        $statusLabels = [
            'pending' => 'Pending',
            'quoted' => 'Quoted',
            'approved' => 'Approved',
            'in_production' => 'In Production',
            'quality_check' => 'Quality Check',
            'ready' => 'Ready for Delivery',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled'
        ];
        
        $oldLabel = $statusLabels[$oldStatus] ?? $oldStatus;
        $newLabel = $statusLabels[$newStatus] ?? $newStatus;
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Order Status Update</title>
            <style>
                body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
                .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; }
                .header { border-bottom: 2px solid #8b5cf6; padding-bottom: 15px; margin-bottom: 20px; }
                .status-change { background: #f8f8fa; padding: 20px; border-radius: 8px; text-align: center; margin: 20px 0; }
                .status-arrow { font-size: 24px; color: #8b5cf6; margin: 0 15px; }
                .status-old { color: #666; text-decoration: line-through; }
                .status-new { font-weight: 700; color: #22c55e; font-size: 18px; }
                .button { display: inline-block; padding: 12px 25px; background: #8b5cf6; color: #fff; text-decoration: none; border-radius: 6px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2 style="color: #8b5cf6;">📦 Order Status Update</h2>
                </div>
                
                <p>Dear <strong><?php echo htmlspecialchars($data['customer_name']); ?></strong>,</p>
                <p>Your order #<strong><?php echo htmlspecialchars($data['order_number']); ?></strong> has been updated.</p>
                
                <div class="status-change">
                    <span class="status-old"><?php echo $oldLabel; ?></span>
                    <span class="status-arrow">➜</span>
                    <span class="status-new"><?php echo $newLabel; ?></span>
                </div>
                
                <?php if ($newStatus === 'ready'): ?>
                <div style="background: #f0fdf4; padding: 15px; border-radius: 8px; border-left: 4px solid #22c55e; margin: 15px 0;">
                    <strong>🎉 Great news!</strong> Your order is ready for delivery or pickup.
                </div>
                <?php endif; ?>
                
                <?php if ($newStatus === 'delivered'): ?>
                <div style="background: #f0fdf4; padding: 15px; border-radius: 8px; border-left: 4px solid #22c55e; margin: 15px 0;">
                    <strong>✅ Delivered!</strong> We hope you love your prints. Your feedback matters to us!
                </div>
                <?php endif; ?>
                
                <div style="text-align: center; margin: 25px 0;">
                    <a href="https://wa.me/923001234567?text=Order%20%23<?php echo $data['order_number']; ?>" class="button">
                        💬 Track on WhatsApp
                    </a>
                </div>
                
                <div style="font-size: 12px; color: #666; margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px;">
                    <p>Need help? Contact us at <a href="tel:+923001234567">+92 300 1234567</a></p>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    /**
     * Get status color for badge
     */
    private function getStatusColor(string $status): string {
        $colors = [
            'pending' => '#f59e0b',
            'quoted' => '#3b82f6',
            'approved' => '#8b5cf6',
            'in_production' => '#f59e0b',
            'quality_check' => '#8b5cf6',
            'ready' => '#22c55e',
            'delivered' => '#22c55e',
            'cancelled' => '#ef4444'
        ];
        return $colors[$status] ?? '#6b7280';
    }

    /**
     * Get status label
     */
    private function getStatusLabel(string $status): string {
        $labels = [
            'pending' => 'Pending',
            'quoted' => 'Quoted',
            'approved' => 'Approved',
            'in_production' => 'In Production',
            'quality_check' => 'Quality Check',
            'ready' => 'Ready for Delivery',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled'
        ];
        return $labels[$status] ?? ucfirst($status);
    }

    /**
     * Log email when SMTP is disabled (development)
     */
    private function logEmail(string $type, string $to): bool {
        $log = date('Y-m-d H:i:s') . " | $type | To: $to | SMTP disabled\n";
        $logFile = __DIR__ . '/../logs/email.log';
        file_put_contents($logFile, $log, FILE_APPEND);
        return true;
    }

    /**
     * Test email configuration
     * 
     * @param string $testEmail Email to send test to
     * @return array Result with success and message
     */
    public function testEmail(string $testEmail): array {
        if (!$this->enabled) {
            return ['success' => false, 'message' => 'SMTP is disabled'];
        }
        
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($testEmail);
            
            $this->mail->isHTML(true);
            $this->mail->Subject = "Test Email - Subhan Printers";
            $this->mail->Body = "
                <h2>✅ Test Email Successful!</h2>
                <p>This is a test email from Subhan Printers.</p>
                <p>If you're receiving this, your email configuration is working correctly.</p>
                <p>Time: " . date('Y-m-d H:i:s') . "</p>
            ";
            $this->mail->AltBody = "Test email from Subhan Printers at " . date('Y-m-d H:i:s');
            
            $this->mail->send();
            return ['success' => true, 'message' => 'Test email sent successfully'];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}

/**
 * Helper function to send email (global shortcut)
 * 
 * @param string $type Email type (order_confirmation, quote, newsletter, contact)
 * @param array $data Email data
 * @return bool
 */
function sendEmail(string $type, array $data): bool {
    $emailService = new EmailService();
    
    switch ($type) {
        case 'order_confirmation':
            return $emailService->sendOrderConfirmation($data);
        case 'quote':
            return $emailService->sendQuoteResponse($data);
        case 'newsletter':
            return $emailService->sendNewsletterWelcome($data['email'], $data['name'] ?? '');
        case 'contact':
            return $emailService->sendContactEmail($data);
        case 'quote_notification':
            return $emailService->sendNewQuoteNotification($data);
        case 'status_update':
            return $emailService->sendOrderStatusUpdate($data['order'], $data['old_status'], $data['new_status']);
        default:
            return false;
    }
}