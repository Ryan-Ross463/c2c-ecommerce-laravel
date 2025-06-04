document.getElementById('profile_picture').addEventListener('change', function(e) {
    const preview = document.getElementById('image-preview');
    const file = e.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});

document.querySelector('.register-form').addEventListener('submit', function(e) {
    let hasErrors = false;
    const errors = {};
    
    const firstName = document.getElementById('first_name').value.trim();
    if (!firstName) {
        errors.first_name = "First name is required.";
        hasErrors = true;
    }
    
    const lastName = document.getElementById('last_name').value.trim();
    if (!lastName) {
        errors.last_name = "Last name is required.";
        hasErrors = true;
    }
    
    const email = document.getElementById('email').value.trim();
    if (!email) {
        errors.email = "Email is required.";
        hasErrors = true;
    } else if (email.indexOf('@') === -1) {
        errors.email = "Please include an '@' in the email address.";
        hasErrors = true;
    } else {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errors.email = "Invalid email format. Please enter a valid email address (e.g., user@example.com).";
            hasErrors = true;
        }
    }

    const phone = document.getElementById('phone').value.replace(/[^0-9]/g, '');
    if (!phone) {
        errors.phone = "Phone number is required.";
        hasErrors = true;
    } else if (phone.length !== 10) {
        errors.phone = "Phone number must be 10 digits.";
        hasErrors = true;
    }

    const accountType = document.getElementById('account_type').value;
    if (!accountType) {
        errors.account_type = "Please select an account type.";
        hasErrors = true;
    }

    const dob = document.getElementById('dob').value;
    if (!dob) {
        errors.dob = "Date of birth is required.";
        hasErrors = true;
    } else {
        let dobDate;
        if (/^\d{4}\/\d{2}\/\d{2}$/.test(dob)) {
            dobDate = new Date(dob.replace(/\//g, '-'));
        } else {
            dobDate = new Date(dob);
        }
        
        if (isNaN(dobDate.getTime())) {
            errors.dob = "Please enter a valid date in YYYY-MM-DD format.";
            hasErrors = true;
        } else {
            const eighteenYearsAgo = new Date();
            eighteenYearsAgo.setFullYear(eighteenYearsAgo.getFullYear() - 18);
            if (dobDate > eighteenYearsAgo) {
                errors.dob = "You must be at least 18 years old to register.";
                hasErrors = true;
            }
        }
    }

    const gender = document.getElementById('gender').value;
    if (!gender) {
        errors.gender = "Please select your gender.";
        hasErrors = true;
    }

    const city = document.getElementById('city').value.trim();
    if (!city) {
        errors.city = "City is required.";
        hasErrors = true;
    }

    const password = document.getElementById('password').value;
    if (!password) {
        errors.password = "Password is required.";
        hasErrors = true;
    } else if (!/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(password)) {
        errors.password = "Password must be at least 8 characters long, include an uppercase letter, a lowercase letter, a number, and a special character.";
        hasErrors = true;
    }

    const confirmPassword = document.getElementById('confirm_password').value;
    if (!confirmPassword) {
        errors.confirm_password = "Confirm password is required.";
        hasErrors = true;
    } else if (password !== confirmPassword) {
        errors.confirm_password = "Passwords do not match.";
        hasErrors = true;
    }

    const terms = document.getElementById('terms').checked;
    if (!terms) {
        errors.terms = "You must accept the terms and privacy policy.";
        hasErrors = true;
    }

    if (hasErrors) {
        e.preventDefault();
        
        document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        
        Object.keys(errors).forEach(field => {
            const input = document.getElementById(field);
            input.classList.add('is-invalid');
            
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = errors[field];
            input.parentNode.appendChild(errorDiv);
        });
        
        const firstError = document.querySelector('.is-invalid');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }
    }
});