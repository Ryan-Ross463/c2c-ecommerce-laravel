<?php
$page_title = "Register - C2C eCommerce";
$custom_css = '<link href="' . my_url('/assets/css/forms.css') . '?v=' . time() . '" rel="stylesheet">
<link href="' . my_url('/assets/css/register.css') . '?v=' . time() . '" rel="stylesheet">';
include(resource_path('views/layouts/header.php'));
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="form-card shadow">
                <h2 class="form-title">Create Account</h2>
                
                <?php if (!empty($errors)): ?>
                    <div class="form-error">
                        <span class="form-error-icon"><i class="fas fa-exclamation-circle"></i></span>
                        Please correct the errors in the form.
                    </div>
                <?php endif; ?>
                
                <form class="register-form" method="POST" action="<?php echo url('/register'); ?>" enctype="multipart/form-data" novalidate>                    <?php echo csrf_field(); ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input 
                                    type="text" 
                                    class="form-control <?php echo isset($errors['first_name']) ? 'is-invalid' : ''; ?>" 
                                    id="first_name" 
                                    name="first_name" 
                                    value="<?php echo htmlspecialchars($formFields['first_name'] ?? ''); ?>"
                                    required
                                >
                                <?php if (isset($errors['first_name'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['first_name']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input 
                                    type="text" 
                                    class="form-control <?php echo isset($errors['last_name']) ? 'is-invalid' : ''; ?>" 
                                    id="last_name" 
                                    name="last_name" 
                                    value="<?php echo htmlspecialchars($formFields['last_name'] ?? ''); ?>"
                                    required
                                >
                                <?php if (isset($errors['last_name'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['last_name']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input 
                                    type="email" 
                                    class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                    id="email" 
                                    name="email" 
                                    value="<?php echo htmlspecialchars($formFields['email'] ?? ''); ?>"
                                    placeholder="you@example.com"
                                    required
                                >
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input 
                                    type="text" 
                                    class="form-control <?php echo isset($errors['phone']) ? 'is-invalid' : ''; ?>" 
                                    id="phone" 
                                    name="phone" 
                                    value="<?php echo htmlspecialchars($formFields['phone'] ?? ''); ?>"
                                    placeholder="10-digit number"
                                    required
                                >
                                <?php if (isset($errors['phone'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['phone']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input 
                                    type="date" 
                                    class="form-control <?php echo isset($errors['dob']) ? 'is-invalid' : ''; ?>" 
                                    id="dob" 
                                    name="dob" 
                                    value="<?php echo htmlspecialchars($formFields['dob'] ?? ''); ?>"
                                    required
                                >
                                <?php if (isset($errors['dob'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['dob']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select 
                                    class="form-select <?php echo isset($errors['gender']) ? 'is-invalid' : ''; ?>" 
                                    id="gender" 
                                    name="gender"
                                    required
                                >
                                    <option value="" disabled <?php echo empty($formFields['gender'] ?? '') ? 'selected' : ''; ?>>Select gender</option>
                                    <option value="Male" <?php echo ($formFields['gender'] ?? '') === 'Male' ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo ($formFields['gender'] ?? '') === 'Female' ? 'selected' : ''; ?>>Female</option>
                                </select>
                                <?php if (isset($errors['gender'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['gender']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="account_type" class="form-label">Account Type</label>
                                <select 
                                    class="form-select <?php echo isset($errors['account_type']) ? 'is-invalid' : ''; ?>" 
                                    id="account_type" 
                                    name="account_type"
                                    required
                                >
                                    <option value="" disabled <?php echo empty($formFields['account_type'] ?? '') ? 'selected' : ''; ?>>Select account type</option>
                                    <option value="Buyer" <?php echo ($formFields['account_type'] ?? '') === 'Buyer' ? 'selected' : ''; ?>>Buyer</option>
                                    <option value="Seller" <?php echo ($formFields['account_type'] ?? '') === 'Seller' ? 'selected' : ''; ?>>Seller</option>
                                </select>
                                <?php if (isset($errors['account_type'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['account_type']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="city" class="form-label">City</label>
                                <input 
                                    type="text" 
                                    class="form-control <?php echo isset($errors['city']) ? 'is-invalid' : ''; ?>" 
                                    id="city" 
                                    name="city" 
                                    value="<?php echo htmlspecialchars($formFields['city'] ?? ''); ?>"
                                    required
                                >
                                <?php if (isset($errors['city'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['city']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="business_name" class="form-label">Business Name (Optional)</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="business_name" 
                            name="business_name" 
                            value="<?php echo htmlspecialchars($formFields['business_name'] ?? ''); ?>"
                        >
                    </div>
                    
                    <div class="form-group mb-3 file-upload">
                        <label for="profile_picture" class="form-label">Profile Picture (Optional)</label>
                        <input 
                            type="file" 
                            class="form-control" 
                            id="profile_picture" 
                            name="profile_picture" 
                            accept="image/*"
                        >
                        <img class="preview" id="image-preview" alt="Profile picture preview" style="display: none; max-width: 100px; margin-top: 0.5rem; border-radius: 0.5rem; border: 2px solid #dee2e6;">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input 
                                    type="password" 
                                    class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                                    id="password" 
                                    name="password"
                                    placeholder="Enter your password"
                                    required
                                >
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input 
                                    type="password" 
                                    class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>" 
                                    id="confirm_password" 
                                    name="confirm_password"
                                    placeholder="Confirm your password"
                                    required
                                >
                                <?php if (isset($errors['confirm_password'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['confirm_password']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-4 terms-container">
                        <div class="form-check">
                            <input 
                                type="checkbox" 
                                class="form-check-input <?php echo isset($errors['terms']) ? 'is-invalid' : ''; ?>" 
                                id="terms" 
                                name="terms"
                                required
                            >
                            <label class="form-check-label" for="terms">
                                I accept the <a href="<?php echo url('/terms'); ?>">terms and privacy policy</a>.
                            </label>
                            <?php if (isset($errors['terms'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['terms']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                      <div class="form-group mb-3">
                        <button type="submit" class="btn btn-dark w-100 py-2 register-btn">Create Account</button>
                    </div>
                </form>
                
                <div class="text-center mt-4 pt-3 border-top">
                    <p class="mb-0">Already have an account? <a href="<?php echo url('/login'); ?>" class="text-dark fw-semibold text-decoration-none">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo url('/assets/js/register.js'); ?>"></script>

<?php include(resource_path('views/layouts/footer.php')); ?>
