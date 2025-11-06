// Mobile menu functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navMenu = document.querySelector('.nav-menu');
    
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });
    }
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        if (navMenu && mobileMenuBtn) {
            if (!navMenu.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                navMenu.classList.remove('active');
            }
        }
    });
    
    // Form validation for ticket submission
    const ticketForm = document.querySelector('.ticket-form form');
    if (ticketForm) {
        ticketForm.addEventListener('submit', function(e) {
            const description = document.getElementById('description');
            if (description.value.length < 10) {
                e.preventDefault();
                alert('Please provide a more detailed description of the problem (at least 10 characters).');
                description.focus();
            }
        });
    }
    
    // Simulate ticket submission
    const submitTicketForm = document.querySelector('form[action="submit-ticket.php"]');
    if (submitTicketForm) {
        submitTicketForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // In a real application, this would submit to a server
            // For demo purposes, we'll just show a success message
            alert('Ticket submitted successfully! Your ticket ID is #TKT-' + Math.floor(1000 + Math.random() * 9000));
            this.reset();
        });
    }
});