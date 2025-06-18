// Auto-calculate age based on birthday
document.getElementById('birthday').addEventListener('change', function() {
    const birthday = new Date(this.value);
    const today = new Date();
    let age = today.getFullYear() - birthday.getFullYear();
    const monthDiff = today.getMonth() - birthday.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthday.getDate())) {
        age--;
    }
    
    document.getElementById('age').value = age;
});

// Form submission handler
document.getElementById('registrationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = new FormData(this);
    const userData = Object.fromEntries(formData);
    
    // Basic validation
    if (!userData.fullname || !userData.username || !userData.birthday || !userData.password || !userData.role) {
        alert('Please fill in all required fields.');
        return;
    }
    
    // Validate age (must be 18 or older)
    const age = parseInt(userData.age);
    if (age < 18) {
        alert('User must be at least 18 years old.');
        return;
    }
    
    // Validate username (basic check for special characters)
    const usernameRegex = /^[a-zA-Z0-9_]+$/;
    if (!usernameRegex.test(userData.username)) {
        alert('Username can only contain letters, numbers, and underscores.');
        return;
    }
    
    // Validate password strength (minimum 8 characters)
    if (userData.password.length < 8) {
        alert('Password must be at least 8 characters long.');
        return;
    }
    
    // Here you would typically send the data to your server
    console.log('User registration data:', userData);
    alert('User registered successfully!');
    
    // Reset form
    this.reset();
    document.getElementById('age').value = '';
});

// Cancel button handler
document.querySelector('.btn-secondary').addEventListener('click', function() {
    if (confirm('Are you sure you want to cancel? All entered data will be lost.')) {
        document.getElementById('registrationForm').reset();
        document.getElementById('age').value = '';
    }
});



// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strength = {
        weak: password.length < 6,
        medium: password.length >= 6 && password.length < 8,
        strong: password.length >= 8 && /(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(password)
    };
    
    // Remove existing strength indicator
    const existingIndicator = document.querySelector('.password-strength');
    if (existingIndicator) {
        existingIndicator.remove();
    }
    
    if (password.length > 0) {
        const indicator = document.createElement('small');
        indicator.className = 'password-strength';
        
        if (strength.strong) {
            indicator.style.color = '#27ae60';
            indicator.textContent = 'Strong password';
        } else if (strength.medium) {
            indicator.style.color = '#f39c12';
            indicator.textContent = 'Medium strength - add uppercase, lowercase, and numbers';
        } else {
            indicator.style.color = '#e74c3c';
            indicator.textContent = 'Weak password - minimum 8 characters required';
        }
        
        this.parentNode.appendChild(indicator);
    }
});