/**
 * Dark Mode Toggle Functionality
 * Handles theme switching between light and dark modes using Bootstrap 5
 * Works with icon-based theme toggle (sun/moon icons)
 */

(function() {
    'use strict';
    
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDarkMode);
    } else {
        initDarkMode();
    }
    
    function initDarkMode() {
        const themeToggleIcons = document.getElementById('theme-toggle-icons');
        const htmlElement = document.documentElement;
        
        if (!themeToggleIcons) {
            return; // Theme toggle not found
        }
        
        // Get saved theme preference or default to light
        const savedTheme = localStorage.getItem('theme') || localStorage.getItem('bs-theme') || 'light';
        htmlElement.setAttribute('data-bs-theme', savedTheme);
        
        // Update icon visibility based on saved theme
        updateThemeIcons(savedTheme);
        
        // Toggle theme on icon click
        themeToggleIcons.addEventListener('click', function() {
            const currentTheme = htmlElement.getAttribute('data-bs-theme') || 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            // Update theme
            htmlElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            localStorage.setItem('bs-theme', newTheme); // For backward compatibility
            updateThemeIcons(newTheme);
        });
        
        function updateThemeIcons(theme) {
            const sunIcon = themeToggleIcons.querySelector('.theme-icon-sun');
            const moonIcon = themeToggleIcons.querySelector('.theme-icon-moon');
            
            if (theme === 'dark') {
                if (sunIcon) sunIcon.style.display = 'none';
                if (moonIcon) moonIcon.style.display = 'inline-block';
            } else {
                if (sunIcon) sunIcon.style.display = 'inline-block';
                if (moonIcon) moonIcon.style.display = 'none';
            }
        }
    }
})();

