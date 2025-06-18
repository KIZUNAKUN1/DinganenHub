/* ============================================
   ENHANCED SIDEBAR JAVASCRIPT - DinganenHub
   Fixed dropdown functionality with proper positioning
   ============================================ */

// Enhanced Dropdown Functionality
document.addEventListener('DOMContentLoaded', function() {
    const dropdowns = document.querySelectorAll('li.dropdown');
    let activeDropdown = null;
    
    // Initialize dropdowns
    dropdowns.forEach(dropdown => {
        const content = dropdown.querySelector('.dropdown-content');
        
        // Handle dropdown click
        dropdown.addEventListener('click', function(e) {
            e.stopPropagation();
            
            // If clicking on a link inside dropdown, don't toggle
            if (e.target.tagName === 'A' || e.target.closest('a')) {
                return;
            }
            
            // Close other dropdowns
            if (activeDropdown && activeDropdown !== this) {
                closeDropdown(activeDropdown);
            }
            
            // Toggle current dropdown
            if (this.classList.contains('show')) {
                closeDropdown(this);
                activeDropdown = null;
            } else {
                openDropdown(this);
                activeDropdown = this;
            }
        });
        
        // Prevent dropdown content clicks from closing
        if (content) {
            content.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (activeDropdown && !activeDropdown.contains(e.target)) {
            closeDropdown(activeDropdown);
            activeDropdown = null;
        }
    });
    
    // Close dropdown on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && activeDropdown) {
            closeDropdown(activeDropdown);
            activeDropdown = null;
        }
    });
    
    // Handle responsive behavior
    let isMobile = window.innerWidth <= 900;
    
    window.addEventListener('resize', function() {
        const wasMobile = isMobile;
        isMobile = window.innerWidth <= 900;
        
        // Reset dropdowns if switching between mobile and desktop
        if (wasMobile !== isMobile && activeDropdown) {
            closeDropdown(activeDropdown);
            activeDropdown = null;
        }
    });
    
    // Touch support for mobile
    if ('ontouchstart' in window) {
        dropdowns.forEach(dropdown => {
            dropdown.addEventListener('touchstart', function(e) {
                // Prevent double-tap zoom on dropdown toggles
                e.preventDefault();
                this.click();
            }, { passive: false });
        });
    }
    
    // Helper functions
    function openDropdown(dropdown) {
        dropdown.classList.add('show');
        
        // Accessibility
        const content = dropdown.querySelector('.dropdown-content');
        if (content) {
            content.setAttribute('aria-expanded', 'true');
            
            // Wait for CSS transition to start, then calculate height
            setTimeout(() => {
                const dropdownHeight = content.scrollHeight;
                
                // Get all siblings after this dropdown
                let sibling = dropdown.nextElementSibling;
                const siblingsToMove = [];
                
                while (sibling) {
                    siblingsToMove.push(sibling);
                    sibling = sibling.nextElementSibling;
                }
                
                // Apply transform to push down siblings
                siblingsToMove.forEach((element) => {
                    element.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                    element.style.transform = `translateY(${dropdownHeight}px)`;
                    element.classList.add('pushed-down');
                });
            }, 10);
            
            // Animate dropdown items
            const items = content.querySelectorAll('li');
            items.forEach((item, index) => {
                item.style.transitionDelay = `${index * 0.05}s`;
            });
        }
        
        // Mobile: Add backdrop
        if (isMobile) {
            document.body.style.overflow = 'hidden';
        }
    }
    
    function closeDropdown(dropdown) {
        dropdown.classList.remove('show');
        
        // Accessibility
        const content = dropdown.querySelector('.dropdown-content');
        if (content) {
            content.setAttribute('aria-expanded', 'false');
        }
        
        // Reset position of pushed siblings
        const pushedElements = document.querySelectorAll('.pushed-down');
        pushedElements.forEach(element => {
            element.style.transform = 'translateY(0)';
            // Remove class after animation
            setTimeout(() => {
                element.classList.remove('pushed-down');
                element.style.transition = '';
            }, 300);
        });
        
        // Reset transition delays
        if (content) {
            const items = content.querySelectorAll('li');
            items.forEach(item => {
                item.style.transitionDelay = '';
            });
        }
        
        // Mobile: Remove backdrop
        if (isMobile) {
            document.body.style.overflow = '';
        }
    }
    
    // Add ripple effect on click
    dropdowns.forEach(dropdown => {
        const content = dropdown.querySelector('.dropdown-content');
        if (content) {
            const links = content.querySelectorAll('a');
            
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Create ripple
                    const ripple = document.createElement('span');
                    ripple.classList.add('ripple');
                    
                    // Position ripple at click location
                    const rect = this.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    
                    this.appendChild(ripple);
                    
                    // Remove ripple after animation
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        }
    });
    
    // Keyboard navigation
    dropdowns.forEach((dropdown) => {
        const content = dropdown.querySelector('.dropdown-content');
        if (content) {
            const links = content.querySelectorAll('a');
            
            dropdown.addEventListener('keydown', function(e) {
                if (!this.classList.contains('show')) return;
                
                let currentIndex = Array.from(links).findIndex(link => link === document.activeElement);
                
                switch(e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        currentIndex = currentIndex < links.length - 1 ? currentIndex + 1 : 0;
                        if (links[currentIndex]) links[currentIndex].focus();
                        break;
                        
                    case 'ArrowUp':
                        e.preventDefault();
                        currentIndex = currentIndex > 0 ? currentIndex - 1 : links.length - 1;
                        if (links[currentIndex]) links[currentIndex].focus();
                        break;
                        
                    case 'Home':
                        e.preventDefault();
                        if (links[0]) links[0].focus();
                        break;
                        
                    case 'End':
                        e.preventDefault();
                        if (links[links.length - 1]) links[links.length - 1].focus();
                        break;
                }
            });
        }
    });
});

// Add CSS for ripple effect dynamically
const style = document.createElement('style');
style.textContent = `
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    /* Additional styles for pushed-down elements */
    .pushed-down {
        position: relative;
        z-index: 1;
    }
    
    /* Dropdown content link styles for ripple effect */
    .dropdown-content a {
        position: relative;
        overflow: hidden;
    }
    
    /* Ensure dropdown content is visible when shown */
    .dropdown.show .dropdown-content {
        visibility: visible !important;
        opacity: 1 !important;
    }
`;
document.head.appendChild(style);

// Set active menu item based on current page
function setActiveMenuItem() {
    const currentPage = window.location.pathname.split('/').pop();
    const menuItems = document.querySelectorAll('.sidebar nav ul > li');
    
    menuItems.forEach(item => {
        const onclick = item.getAttribute('onclick');
        if (onclick && onclick.includes(currentPage)) {
            item.classList.add('active');
        }
        
        // Also check dropdown items
        if (item.classList.contains('dropdown')) {
            const content = item.querySelector('.dropdown-content');
            if (content) {
                const dropdownLinks = content.querySelectorAll('li');
                dropdownLinks.forEach(link => {
                    const linkOnclick = link.getAttribute('onclick');
                    if (linkOnclick && linkOnclick.includes(currentPage)) {
                        item.classList.add('active');
                    }
                });
            }
        }
    });
}

// Initialize active menu item
setActiveMenuItem();