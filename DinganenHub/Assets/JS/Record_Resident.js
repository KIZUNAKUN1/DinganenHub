// Record_Resident.js

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    
    // Auto-calculate age based on date of birth
    document.getElementById('birthday').addEventListener('change', function() {
        const birthday = new Date(this.value);
        const today = new Date();
        let age = today.getFullYear() - birthday.getFullYear();
        const monthDiff = today.getMonth() - birthday.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthday.getDate())) {
            age--;
        }
        
        document.getElementById('age').value = age >= 0 ? age : '';
    });

    // Dropdown functionality
    document.querySelectorAll('li.dropdown').forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            e.stopPropagation();
            
            // Close other dropdowns
            document.querySelectorAll('li.dropdown').forEach(other => {
                if (other !== this) {
                    other.classList.remove('show');
                }
            });
            
            // Toggle current dropdown
            this.classList.toggle('show');
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        document.querySelectorAll('li.dropdown').forEach(dropdown => {
            dropdown.classList.remove('show');
        });
    });

    // Form validation and submission
    // Modified to allow normal form submission to backend
    document.getElementById('residentForm').addEventListener('submit', function(e) {
        // Remove e.preventDefault() to allow form submission
        // You can add client-side validation here if needed before submission
    });

    // Phone number formatting and validation
    document.getElementById('phoneNumber').addEventListener('input', function(e) {
        // Remove any non-numeric characters
        let value = e.target.value.replace(/\D/g, '');
        
        // Limit to 11 digits for Philippine phone numbers
        if (value.length > 11) {
            value = value.slice(0, 11);
        }
        
        // Format the number (09XX-XXX-XXXX)
        if (value.length >= 4) {
            if (value.length <= 7) {
                value = value.replace(/(\d{4})(\d{1,3})/, '$1-$2');
            } else {
                value = value.replace(/(\d{4})(\d{3})(\d{1,4})/, '$1-$2-$3');
            }
        }
        
        e.target.value = value;
    });

    // Form input validation
    const requiredInputs = document.querySelectorAll('input[required], select[required]');
    requiredInputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('error')) {
                validateField(this);
            }
        });
    });

    // Real-time form validation
    function validateField(field) {
        const value = field.value.trim();
        const isValid = field.checkValidity() && value !== '';
        
        if (isValid) {
            field.classList.remove('error');
            field.classList.add('valid');
        } else {
            field.classList.remove('valid');
            field.classList.add('error');
        }
    }

    // Name field validation (letters only)
    const nameFields = ['firstName', 'middleName', 'lastName'];
    nameFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', function(e) {
                // Allow only letters, spaces, and common name characters
                e.target.value = e.target.value.replace(/[^a-zA-Z\s\-'\.]/g, '');
            });
        }
    });

    // Citizenship field - auto-capitalize
    document.getElementById('citizenship').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\b\w/g, l => l.toUpperCase());
    });

    // Auto-format text inputs to proper case
    const textFields = ['placeOfBirth', 'occupation'];
    textFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('blur', function(e) {
                e.target.value = toProperCase(e.target.value);
            });
        }
    });

    function toProperCase(str) {
        return str.replace(/\w\S*/g, (txt) => {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
    }

});

// Reset form function (global scope for onclick attribute)
function resetForm() {
    if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
        document.getElementById('residentForm').reset();
        document.getElementById('age').value = '';
        
        // Remove validation classes
        document.querySelectorAll('.error, .valid').forEach(element => {
            element.classList.remove('error', 'valid');
        });
    }
}
