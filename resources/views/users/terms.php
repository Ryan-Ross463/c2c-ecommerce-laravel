<?php
$page_title = "Terms and Conditions - C2C eCommerce";
$custom_css = '<link href="' . my_url('/assets/css/terms.css') . '?v=' . time() . '" rel="stylesheet">';
include(resource_path('views/layouts/header.php'));
?>
<div class="container py-4">
    <div class="terms-container form-card shadow">
        <h1 class="terms-title">Terms and Privacy Policy</h1>
        <p class="terms-subtitle">Please read these terms and privacy policy carefully before using our C2C E-commerce platform. By accessing or using our service, you agree to be bound by these terms.</p>
    
    <div class="terms-section">
        <h2 class="terms-section-title"><i class="fas fa-file-contract"></i>Terms of Service</h2>
        <p>Welcome to our C2C E-commerce platform. These Terms of Service govern your use of our website, services, and applications (collectively, the "Service").</p>
        <p>By accessing or using our Service, you agree to comply with and be bound by these terms. If you do not agree with all the terms and conditions, you should not use our Service.</p>
        
        <h3>1. User Accounts</h3>
        <p>When you create an account with us, you must provide information that is accurate, complete, and current at all times. Failure to do so constitutes a breach of the Terms, which may result in immediate termination of your account on our Service.</p>
        
        <h3>2. Content</h3>
        <p>Our Service allows you to post, link, store, share and otherwise make available certain information, text, graphics, videos, or other material. You are responsible for the content that you post to the Service, including its legality, reliability, and appropriateness.</p>
        
        <h3>3. Prohibited Uses</h3>
        <p>You may use our Service only for lawful purposes and in accordance with these Terms. You agree not to use the Service:</p>
        <ul>
            <li>In any way that violates any applicable national or international law or regulation.</li>
            <li>To transmit, or procure the sending of, any advertising or promotional material, including any "junk mail", "chain letter," "spam," or any other similar solicitation.</li>
            <li>To impersonate or attempt to impersonate another user or person.</li>
            <li>In any way that infringes upon the rights of others.</li>
        </ul>
    </div>
    
    <div class="section-divider"></div>
    
    <div class="terms-section">
        <h2 class="terms-section-title"><i class="fas fa-shield-alt"></i>Privacy Policy</h2>
        <p>We are committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our Service.</p>
        
        <h3>1. Information Collection</h3>
        <p>We may collect personal information that you voluntarily provide to us when you register with the Service, express an interest in obtaining information about us or our products and services, or otherwise contact us.</p>
        
        <h3>2. Use of Your Information</h3>
        <p>We may use the information we collect or receive:</p>
        <ul>
            <li>To facilitate account creation and authentication.</li>
            <li>To send you emails about updates, security alerts, and administrative messages.</li>
            <li>To improve our Service and customize your user experience.</li>
            <li>To respond to your inquiries and solve potential issues you might have with our Service.</li>
        </ul>
        
        <h3>3. Disclosure of Your Information</h3>
        <p>We will not share your personal information with any third parties without your consent, except as necessary to provide you with the services offered by us or to comply with the law.</p>
        
        <h3>4. Security of Your Information</h3>
        <p>We use administrative, technical, and physical security measures designed to help protect your personal information. While we have taken reasonable steps to secure the personal information you provide to us, please be aware that no security measures are perfect or impenetrable.</p>
    </div>
    
    <div class="terms-footer">
        <p>If you have any questions about these Terms or Privacy Policy, please contact us.</p>
          <div class="terms-actions">
            <a href="<?php echo url('/register'); ?>" class="btn-terms btn-accept">Accept & Register</a>
            <a href="<?php echo url('/'); ?>" class="btn-terms btn-decline">Return to Homepage</a>
        </div>
        
        <p class="last-updated">Last Updated: May 29, 2025</p>
    </div>
</div>

<?php
include(resource_path('views/layouts/footer.php'));
?>