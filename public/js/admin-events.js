// Admin Events JavaScript
document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // Image Preview Functionality
    const imageInput = document.getElementById('featured_image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const removeImageCheckbox = document.getElementById('remove_featured_image');

    if (imageInput && imagePreview && previewImg) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        imagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert('Veuillez sélectionner un fichier image valide.');
                    imageInput.value = '';
                    imagePreview.style.display = 'none';
                }
            } else {
                imagePreview.style.display = 'none';
            }
        });
    }

    // Remove current image checkbox
    if (removeImageCheckbox) {
        removeImageCheckbox.addEventListener('change', function() {
            if (this.checked) {
                if (imageInput) {
                    imageInput.value = '';
                }
                if (imagePreview) {
                    imagePreview.style.display = 'none';
                }
            }
        });
    }

    // Tab Navigation Enhancement
    const tabs = document.querySelectorAll('#eventLocaleTabs .nav-link');
    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Hide all tab content
            const tabContents = document.querySelectorAll('.tab-pane');
            tabContents.forEach(content => {
                content.classList.remove('show', 'active');
            });
            
            // Show selected tab content
            const targetId = this.getAttribute('href').substring(1);
            const targetContent = document.getElementById(targetId);
            if (targetContent) {
                targetContent.classList.add('show', 'active');
            }
        });
    });

    // Form Validation Enhancement
    const form = document.querySelector('form[action*="events"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            const startDateTime = document.getElementById('start_datetime');
            const endDateTime = document.getElementById('end_datetime');
            
            if (startDateTime && endDateTime && startDateTime.value && endDateTime.value) {
                const startDate = new Date(startDateTime.value);
                const endDate = new Date(endDateTime.value);
                
                if (endDate <= startDate) {
                    e.preventDefault();
                    alert('La date de fin doit être postérieure à la date de début.');
                    endDateTime.focus();
                    return false;
                }
            }
            
            // Check if at least one title is filled
            const titleInputs = document.querySelectorAll('input[name^="title["]');
            let hasTitle = false;
            titleInputs.forEach(input => {
                if (input.value.trim()) {
                    hasTitle = true;
                }
            });
            
            if (!hasTitle) {
                e.preventDefault();
                alert('Veuillez remplir au moins un titre.');
                // Focus on first title input
                if (titleInputs.length > 0) {
                    titleInputs[0].focus();
                    // Also activate the first tab
                    const firstTab = document.querySelector('#eventLocaleTabs .nav-link');
                    if (firstTab) {
                        firstTab.click();
                    }
                }
                return false;
            }
        });
    }

    // Auto-resize textareas
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });

    // Date/Time Input Enhancement
    const dateTimeInputs = document.querySelectorAll('input[type="datetime-local"]');
    dateTimeInputs.forEach(input => {
        // Set minimum date to today
        if (input.id === 'start_datetime') {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
            input.min = minDateTime;
        }
    });

    // Confirmation for delete actions
    const deleteButtons = document.querySelectorAll('[data-target*="deleteEventModal"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modalId = this.getAttribute('data-target');
            const modal = document.querySelector(modalId);
            if (modal) {
                const eventTitle = this.closest('tr').querySelector('td:nth-child(3) strong').textContent;
                const modalBody = modal.querySelector('.modal-body');
                if (modalBody && eventTitle) {
                    modalBody.innerHTML = `
                        Êtes-vous sûr de vouloir supprimer l'événement : <strong>${eventTitle}</strong> ?
                        <br><small>Cette action est irréversible.</small>
                    `;
                }
            }
        });
    });

    // Success message auto-hide
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        if (alert.classList.contains('alert-success')) {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }, 5000);
        }
    });

    // Loading state for form submission
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(button => {
        button.addEventListener('click', function() {
            setTimeout(() => {
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Traitement...';
            }, 100);
        });
    });

    // Table row hover effects
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(0, 123, 255, 0.05)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });

    console.log('Admin Events JavaScript loaded successfully');
});

// Utility Functions
function formatDateTime(dateTimeString) {
    const date = new Date(dateTimeString);
    return date.toLocaleDateString('fr-FR') + ' ' + date.toLocaleTimeString('fr-FR', {
        hour: '2-digit',
        minute: '2-digit'
    });
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}