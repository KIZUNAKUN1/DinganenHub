function showError() {
    const errorBox = document.getElementById('error');
    errorBox.classList.remove('hide');
    setTimeout(() => {
        errorBox.classList.add('hide');
    }, 3000); // 3000ms = 3 seconds
}


// Modal Functionality - Comprehensive Approach
const modalTriggers = {
    'visionMissionBtn': 'visionMissionModal',
    'blotterProcessBtn': 'blotterProcessModal',
    'residencyProcessBtn': 'residencyProcessModal'
};

// Function to handle modal opening
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
        // Optional: Add class to prevent background scroll
        document.body.style.overflow = 'hidden';
    }
}

// Function to handle modal closing
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        // Optional: Restore background scroll
        document.body.style.overflow = '';
    }
}

// Add event listeners for all modal triggers
Object.entries(modalTriggers).forEach(([btnId, modalId]) => {
    const btn = document.getElementById(btnId);
    const modal = document.getElementById(modalId);

    if (btn && modal) {
        // Open modal when button is clicked
        btn.addEventListener('click', () => openModal(modalId));

        // Close modal when close button is clicked
        const closeBtn = modal.querySelector('.close-btn');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => closeModal(modalId));
        }

        // Close modal when clicking outside the modal content
        modal.addEventListener('click', (e) => {
            // Assuming modal content has a class 'modal-content'
            if (e.target === modal) {
                closeModal(modalId);
            }
        });

        // Optional: Close modal on pressing ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.style.display === 'block') {
                closeModal(modalId);
            }
        });
    }
});

// Contact Form Submission (placeholder)
const contactForm = document.getElementById('contactForm');
if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
        e.preventDefault();
        alert('Thank you for your message! We will get back to you soon.');
        contactForm.reset();
        closeModal('contactModal');
    });
}
