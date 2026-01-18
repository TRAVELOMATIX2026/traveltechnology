/**
 * Template V3 - Header and Navigation JavaScript
 * Handles currency dropdown, mobile menu, theme toggle, and other template-specific functionality
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Set viewport meta tag
        $("#id").attr("content", "width=device-width, initial-scale=1");
        
        // ============================================
        // Customer Support Dropdown Handler - Hover for Desktop, Click for Mobile
        // ============================================
        var $supportDropdown = $('.customer-support-dropdown');
        var $supportMenu = $supportDropdown.find('.support-dropdown-menu');
        var $signinDropdown = $('.signin-dropdown-wrapper');
        var $signinMenu = $signinDropdown.find('.signin-dropdown-menu');
        
        var supportDropdownTimeout;
        var signinDropdownTimeout;
        
        // Hover handler for desktop - keep dropdown open when hovering over it
        $supportDropdown.on('mouseenter', function() {
            if ($(window).width() > 767) {
                clearTimeout(supportDropdownTimeout);
                $supportMenu.addClass('show');
                $('#supportDropdown').attr('aria-expanded', 'true');
                // Close other dropdowns if open
                $('.currency-dropdown-menu, .currency-selector .dropdown-menu').removeClass('open');
                $('#currencyDropdown').attr('aria-expanded', 'false');
                $signinMenu.removeClass('show');
                $('#signinDropdown').attr('aria-expanded', 'false');
                $('.language-dropdown-menu').removeClass('open');
                $('#languageDropdown').attr('aria-expanded', 'false');
            }
        });
        
        // Also handle hover on the dropdown menu itself
        $supportMenu.on('mouseenter', function() {
            if ($(window).width() > 767) {
                clearTimeout(supportDropdownTimeout);
                $(this).addClass('show');
                $('#supportDropdown').attr('aria-expanded', 'true');
            }
        });
        
        // Hide when leaving both the trigger and the dropdown with a small delay
        $supportDropdown.on('mouseleave', function(e) {
            if ($(window).width() > 767) {
                var relatedTarget = e.relatedTarget;
                // If moving to the dropdown menu, don't hide
                if (relatedTarget && $(relatedTarget).closest('.support-dropdown-menu').length) {
                    return;
                }
                // Small delay to allow mouse to reach dropdown
                supportDropdownTimeout = setTimeout(function() {
                    if (!$supportMenu.is(':hover') && !$supportDropdown.is(':hover')) {
                        $supportMenu.removeClass('show');
                        $('#supportDropdown').attr('aria-expanded', 'false');
                    }
                }, 100);
            }
        });
        
        $supportMenu.on('mouseleave', function(e) {
            if ($(window).width() > 767) {
                var relatedTarget = e.relatedTarget;
                // If moving back to the trigger, don't hide
                if (relatedTarget && $(relatedTarget).closest('.customer-support-dropdown').length) {
                    return;
                }
                // Small delay
                supportDropdownTimeout = setTimeout(function() {
                    if (!$supportMenu.is(':hover') && !$supportDropdown.is(':hover')) {
                        $supportMenu.removeClass('show');
                        $('#supportDropdown').attr('aria-expanded', 'false');
                    }
                }, 100);
            }
        });
        
        // Click handler for mobile
        $('#supportDropdown').on('click', function(e) {
            if ($(window).width() <= 767) {
                e.preventDefault();
                e.stopPropagation();
                var $menu = $(this).next('.support-dropdown-menu');
                var isOpen = $menu.hasClass('show');
                
                // Close all other dropdowns
                $('.support-dropdown-menu').removeClass('show');
                $('.currency-selector .dropdown-menu').removeClass('open');
                $('.dropdown-toggle').not(this).attr('aria-expanded', 'false');
                
                if (!isOpen) {
                    $menu.addClass('show');
                    $(this).attr('aria-expanded', 'true');
                } else {
                    $(this).attr('aria-expanded', 'false');
                }
            }
        });
        
        // Close support dropdown when clicking outside (mobile only)
        $(document).on('click', function(e) {
            if ($(window).width() <= 767) {
                // Check if click is outside support dropdown
                if (!$(e.target).closest('.customer-support-dropdown').length) {
                    if ($supportMenu.hasClass('show')) {
                        $supportMenu.removeClass('show');
                        $('#supportDropdown').attr('aria-expanded', 'false');
                    }
                }
            }
        });
        
        // Prevent dropdown from closing when clicking inside menu (mobile)
        $('.customer-support-dropdown .support-dropdown-menu').on('click', function(e) {
            if ($(window).width() <= 767) {
                e.stopPropagation();
            }
        });
        
        // ============================================
        // Signin Dropdown Handler - Hover for Desktop, Click for Mobile
        // ============================================
        
        // Hover handler for desktop - keep dropdown open when hovering over it
        $signinDropdown.on('mouseenter', function() {
            if ($(window).width() > 767) {
                clearTimeout(signinDropdownTimeout);
                $signinMenu.addClass('show');
                $('#signinDropdown').attr('aria-expanded', 'true');
                // Close other dropdowns if open
                $('.support-dropdown-menu').removeClass('show');
                $('#supportDropdown').attr('aria-expanded', 'false');
                $('.currency-dropdown-menu, .currency-selector .dropdown-menu').removeClass('open');
                $('#currencyDropdown').attr('aria-expanded', 'false');
                $('.language-dropdown-menu').removeClass('open');
                $('#languageDropdown').attr('aria-expanded', 'false');
            }
        });
        
        // Also handle hover on the dropdown menu itself
        $signinMenu.on('mouseenter', function() {
            if ($(window).width() > 767) {
                clearTimeout(signinDropdownTimeout);
                $(this).addClass('show');
                $('#signinDropdown').attr('aria-expanded', 'true');
            }
        });
        
        // Hide when leaving both the trigger and the dropdown with a small delay
        $signinDropdown.on('mouseleave', function(e) {
            if ($(window).width() > 767) {
                var relatedTarget = e.relatedTarget;
                // If moving to the dropdown menu, don't hide
                if (relatedTarget && $(relatedTarget).closest('.signin-dropdown-menu').length) {
                    return;
                }
                // Small delay to allow mouse to reach dropdown
                signinDropdownTimeout = setTimeout(function() {
                    if (!$signinMenu.is(':hover') && !$signinDropdown.is(':hover')) {
                        $signinMenu.removeClass('show');
                        $('#signinDropdown').attr('aria-expanded', 'false');
                    }
                }, 100);
            }
        });
        
        $signinMenu.on('mouseleave', function(e) {
            if ($(window).width() > 767) {
                var relatedTarget = e.relatedTarget;
                // If moving back to the trigger, don't hide
                if (relatedTarget && $(relatedTarget).closest('.signin-dropdown-wrapper').length) {
                    return;
                }
                // Small delay
                signinDropdownTimeout = setTimeout(function() {
                    if (!$signinMenu.is(':hover') && !$signinDropdown.is(':hover')) {
                        $signinMenu.removeClass('show');
                        $('#signinDropdown').attr('aria-expanded', 'false');
                    }
                }, 100);
            }
        });
        
        // Click handler for mobile
        $('#signinDropdown').on('click', function(e) {
            if ($(window).width() <= 767) {
                e.preventDefault();
                e.stopPropagation();
                var $menu = $(this).next('.signin-dropdown-menu');
                var isOpen = $menu.hasClass('show');
                
                // Close all other dropdowns
                $('.signin-dropdown-menu').removeClass('show');
                $('.support-dropdown-menu').removeClass('show');
                $('.currency-selector .dropdown-menu').removeClass('open');
                $('.dropdown-toggle').not(this).attr('aria-expanded', 'false');
                
                if (!isOpen) {
                    $menu.addClass('show');
                    $(this).attr('aria-expanded', 'true');
                } else {
                    $(this).attr('aria-expanded', 'false');
                }
            }
        });
        
        // Close signin dropdown when clicking outside (mobile only)
        $(document).on('click', function(e) {
            if ($(window).width() <= 767) {
                // Check if click is outside signin dropdown
                if (!$(e.target).closest('.signin-dropdown-wrapper').length) {
                    if ($signinMenu.hasClass('show')) {
                        $signinMenu.removeClass('show');
                        $('#signinDropdown').attr('aria-expanded', 'false');
                    }
                }
            }
        });
        
        // Prevent dropdown from closing when clicking inside menu (mobile)
        $('.signin-dropdown-wrapper .signin-dropdown-menu').on('click', function(e) {
            if ($(window).width() <= 767) {
                e.stopPropagation();
            }
        });
        
        // Close support dropdown when hovering over signin (desktop)
        $signinDropdown.on('mouseenter', function() {
            if ($(window).width() > 767) {
                $supportMenu.removeClass('show');
                $('#supportDropdown').attr('aria-expanded', 'false');
            }
        });
        
        // ============================================
        // Currency Dropdown Handler - Hover for Desktop, Click for Mobile
        // ============================================
        var $currencyDropdown = $('.currency-dropdown-wrapper');
        var $currencyMenu = $currencyDropdown.find('.currency-dropdown-menu, .dropdown-menu');
        
        var currencyDropdownTimeout;
        
        // Hover handler for desktop
        $currencyDropdown.on('mouseenter', function() {
            if ($(window).width() > 767) {
                clearTimeout(currencyDropdownTimeout);
                $currencyMenu.addClass('open');
                $('#currencyDropdown').attr('aria-expanded', 'true');
                // Close other dropdowns
                $supportMenu.removeClass('show');
                $('#supportDropdown').attr('aria-expanded', 'false');
                $signinMenu.removeClass('show');
                $('#signinDropdown').attr('aria-expanded', 'false');
                $('.language-dropdown-menu').removeClass('open');
                $('#languageDropdown').attr('aria-expanded', 'false');
            }
        });
        
        // Also handle hover on the dropdown menu itself
        $currencyMenu.on('mouseenter', function() {
            if ($(window).width() > 767) {
                clearTimeout(currencyDropdownTimeout);
                $(this).addClass('open');
                $('#currencyDropdown').attr('aria-expanded', 'true');
            }
        });
        
        // Hide when leaving both the trigger and the dropdown with a small delay
        $currencyDropdown.on('mouseleave', function(e) {
            if ($(window).width() > 767) {
                var relatedTarget = e.relatedTarget;
                if (relatedTarget && $(relatedTarget).closest('.currency-dropdown-menu, .dropdown-menu').length) {
                    return;
                }
                currencyDropdownTimeout = setTimeout(function() {
                    if (!$currencyMenu.is(':hover') && !$currencyDropdown.is(':hover')) {
                        $currencyMenu.removeClass('open');
                        $('#currencyDropdown').attr('aria-expanded', 'false');
                    }
                }, 100);
            }
        });
        
        $currencyMenu.on('mouseleave', function(e) {
            if ($(window).width() > 767) {
                var relatedTarget = e.relatedTarget;
                if (relatedTarget && $(relatedTarget).closest('.currency-dropdown-wrapper').length) {
                    return;
                }
                currencyDropdownTimeout = setTimeout(function() {
                    if (!$currencyMenu.is(':hover') && !$currencyDropdown.is(':hover')) {
                        $currencyMenu.removeClass('open');
                        $('#currencyDropdown').attr('aria-expanded', 'false');
                    }
                }, 100);
            }
        });
        
        // Click handler for mobile
        $('#currencyDropdown').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var $menu = $(this).next('.dropdown-menu, .currency-dropdown-menu');
            var isOpen = $menu.hasClass('open');
            
            // Close all other dropdowns
            $('.dropdown-menu').removeClass('open');
            $('.support-dropdown-menu').removeClass('show');
            $('.signin-dropdown-menu').removeClass('show');
            $('.language-dropdown-menu').removeClass('open');
            $('.dropdown-toggle').not(this).attr('aria-expanded', 'false');
            $('#signinDropdown').attr('aria-expanded', 'false');
            $('#languageDropdown').attr('aria-expanded', 'false');
            
            if (!isOpen) {
                $menu.addClass('open');
                $(this).attr('aria-expanded', 'true');
                
                // On mobile, prevent body scroll and add overlay class
                if ($(window).width() <= 767) {
                    $('body').css('overflow', 'hidden').addClass('currency-dropdown-open');
                }
            } else {
                // On mobile, restore body scroll and remove overlay class
                if ($(window).width() <= 767) {
                    $('body').css('overflow', '').removeClass('currency-dropdown-open');
                }
            }
        });
        
        // Close dropdown when clicking outside (mobile only)
        $(document).on('click', function(e) {
            if ($(window).width() <= 767) {
                var $currencySelector = $('.currency-dropdown-wrapper');
                var $menu = $currencySelector.find('.dropdown-menu, .currency-dropdown-menu');
                
                // Check if click is outside currency selector
                if (!$(e.target).closest('.currency-dropdown-wrapper').length) {
                    if ($menu.hasClass('open')) {
                        $menu.removeClass('open');
                        $('#currencyDropdown').attr('aria-expanded', 'false');
                        $('body').css('overflow', '').removeClass('currency-dropdown-open');
                    }
                }
            }
        });
        
        // ============================================
        // Language Dropdown Handler - Hover for Desktop, Click for Mobile
        // ============================================
        var $languageDropdown = $('.language-dropdown-wrapper');
        var $languageMenu = $languageDropdown.find('.language-dropdown-menu');
        
        var languageDropdownTimeout;
        
        // Hover handler for desktop
        $languageDropdown.on('mouseenter', function() {
            if ($(window).width() > 767) {
                clearTimeout(languageDropdownTimeout);
                $languageMenu.addClass('open');
                $('#languageDropdown').attr('aria-expanded', 'true');
                // Close other dropdowns
                $supportMenu.removeClass('show');
                $('#supportDropdown').attr('aria-expanded', 'false');
                $signinMenu.removeClass('show');
                $('#signinDropdown').attr('aria-expanded', 'false');
                $currencyMenu.removeClass('open');
                $('#currencyDropdown').attr('aria-expanded', 'false');
            }
        });
        
        // Also handle hover on the dropdown menu itself
        $languageMenu.on('mouseenter', function() {
            if ($(window).width() > 767) {
                clearTimeout(languageDropdownTimeout);
                $(this).addClass('open');
                $('#languageDropdown').attr('aria-expanded', 'true');
            }
        });
        
        // Hide when leaving both the trigger and the dropdown with a small delay
        $languageDropdown.on('mouseleave', function(e) {
            if ($(window).width() > 767) {
                var relatedTarget = e.relatedTarget;
                if (relatedTarget && $(relatedTarget).closest('.language-dropdown-menu').length) {
                    return;
                }
                languageDropdownTimeout = setTimeout(function() {
                    if (!$languageMenu.is(':hover') && !$languageDropdown.is(':hover')) {
                        $languageMenu.removeClass('open');
                        $('#languageDropdown').attr('aria-expanded', 'false');
                    }
                }, 100);
            }
        });
        
        $languageMenu.on('mouseleave', function(e) {
            if ($(window).width() > 767) {
                var relatedTarget = e.relatedTarget;
                if (relatedTarget && $(relatedTarget).closest('.language-dropdown-wrapper').length) {
                    return;
                }
                languageDropdownTimeout = setTimeout(function() {
                    if (!$languageMenu.is(':hover') && !$languageDropdown.is(':hover')) {
                        $languageMenu.removeClass('open');
                        $('#languageDropdown').attr('aria-expanded', 'false');
                    }
                }, 100);
            }
        });
        
        // Click handler for mobile
        $('#languageDropdown').on('click', function(e) {
            if ($(window).width() <= 767) {
                e.preventDefault();
                e.stopPropagation();
                var $menu = $(this).next('.language-dropdown-menu');
                var isOpen = $menu.hasClass('open');
                
                // Close all other dropdowns
                $('.language-dropdown-menu').removeClass('open');
                $('.support-dropdown-menu').removeClass('show');
                $('.signin-dropdown-menu').removeClass('show');
                $('.currency-dropdown-menu, .dropdown-menu').removeClass('open');
                $('.dropdown-toggle').not(this).attr('aria-expanded', 'false');
                $('#signinDropdown').attr('aria-expanded', 'false');
                $('#currencyDropdown').attr('aria-expanded', 'false');
                
                if (!isOpen) {
                    $menu.addClass('open');
                    $(this).attr('aria-expanded', 'true');
                } else {
                    $(this).attr('aria-expanded', 'false');
                }
            }
        });
        
        // Language switching handler
        $('.language-item-link').on('click', function(e) {
            e.preventDefault();
            var lang = $(this).data('lang');
            if (lang) {
                // Set language in session via AJAX
                $.ajax({
                    url: app_base_url + 'auth/set_language',
                    type: 'POST',
                    data: { lang: lang },
                    success: function(response) {
                        location.reload();
                    },
                    error: function() {
                        // Fallback: reload with language parameter
                        window.location.href = app_base_url + '?lang=' + lang;
                    }
                });
            }
        });
        
        // Close language dropdown when clicking outside (mobile only)
        $(document).on('click', function(e) {
            if ($(window).width() <= 767) {
                if (!$(e.target).closest('.language-dropdown-wrapper').length) {
                    if ($languageMenu.hasClass('open')) {
                        $languageMenu.removeClass('open');
                        $('#languageDropdown').attr('aria-expanded', 'false');
                    }
                }
            }
        });
        
        // Prevent dropdown from closing when clicking inside menu
        $('.currency-selector .dropdown-menu').on('click', function(e) {
            e.stopPropagation();
        });
        
        // Close currency dropdown on mobile when selecting a currency
        $('.currency-selector .dropdown-menu li a, .currency-selector .dropdown-menu li .topa').on('click', function() {
            if ($(window).width() <= 767) {
                setTimeout(function() {
                    $('.currency-selector .dropdown-menu').removeClass('open');
                    $('#currencyDropdown').attr('aria-expanded', 'false');
                    $('body').css('overflow', '').removeClass('currency-dropdown-open');
                }, 300);
            }
        });
        
        // Close currency dropdown on window resize
        $(window).on('resize', function() {
            if ($(window).width() > 767) {
                $('body').removeClass('currency-dropdown-open');
            }
        });
        
        // ============================================
        // Mobile Menu (Burger Menu) Handler
        // ============================================
        function toggleMobileMenu() {
            var $burgerBtn = $('#burgerMenuBtn');
            var $overlay = $('#mobileMenuOverlay');
            var $panel = $('#mobileMenuPanel');
            var $body = $('body');
            
            if ($panel.hasClass('active')) {
                // Close menu
                $panel.removeClass('active');
                $overlay.removeClass('active');
                $burgerBtn.removeClass('active');
                $body.css('overflow', '');
            } else {
                // Open menu
                $panel.addClass('active');
                $overlay.addClass('active');
                $burgerBtn.addClass('active');
                $body.css('overflow', 'hidden');
            }
        }
        
        function closeMobileMenu() {
            $('#burgerMenuBtn').removeClass('active');
            $('#mobileMenuOverlay').removeClass('active');
            $('#mobileMenuPanel').removeClass('active');
            $('body').css('overflow', '');
        }
        
        // Burger button click
        $('#burgerMenuBtn').on('click', function(e) {
            e.stopPropagation();
            toggleMobileMenu();
        });
        
        // Close button click
        $('#mobileMenuClose').on('click', function(e) {
            e.stopPropagation();
            closeMobileMenu();
        });
        
        // Overlay click to close
        $('#mobileMenuOverlay').on('click', function(e) {
            e.stopPropagation();
            closeMobileMenu();
        });
        
        // Close menu when clicking on a nav link
        $('.mobile-nav-link').on('click', function() {
            setTimeout(function() {
                closeMobileMenu();
            }, 300);
        });
        
        // Close menu when clicking on register/signin buttons
        $('.mobile-register-btn, .mobile-signin-btn, .mobile-user-account').on('click', function() {
            setTimeout(function() {
                closeMobileMenu();
            }, 300);
        });
        
        // Prevent panel clicks from closing menu
        $('#mobileMenuPanel').on('click', function(e) {
            e.stopPropagation();
        });
        
        // Close menu on ESC key press
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                closeMobileMenu();
            }
        });
        
        // ============================================
        // Mobile Theme Toggle Handler
        // ============================================
        // Mobile Theme Toggle - Sync with main theme toggle
        $('#mobile-theme-toggle-icons').on('click', function(e) {
            e.stopPropagation();
            // Trigger the main theme toggle
            $('#theme-toggle-icons').trigger('click');
        });
        
        // Sync theme toggle state when menu opens
        $('#burgerMenuBtn').on('click', function() {
            // Update mobile theme toggle icons based on current theme
            var currentTheme = $('html').attr('data-bs-theme') || 'light';
            // The icons will update automatically via CSS based on data-bs-theme
        });
        
        // ============================================
        // Mobile Tab Bootstrap Dropdown Handler
        // ============================================
        // Use event delegation to handle mobile tab clicks
        $(document).on('click', '.mobile-tab-item', function(e) {
            e.preventDefault(); // Prevent default anchor behavior
            e.stopPropagation(); // Stop event bubbling
            
            var $item = $(this);
            var tabTarget = $item.attr('href'); // Get full href like "#flight"
            var tabId = tabTarget.substring(1); // Get ID without #
            var $button = $('#mobileTabDropdown');
            var $label = $button.find('.dropdown-label');
            var $icon = $button.find('.material-icons').first();
            var itemText = $item.find('span').text();
            var itemIcon = $item.find('.material-icons').text();
            
            // Method 1: Try to find and trigger desktop tab link
            var $desktopTab = $('.nav-tabs .nav-link[href="' + tabTarget + '"]');
            var tabSwitched = false;
            
            if ($desktopTab.length) {
                // Use Bootstrap Tab API to switch tabs
                try {
                    if (typeof bootstrap !== 'undefined' && bootstrap.Tab) {
                        var tab = new bootstrap.Tab($desktopTab[0]);
                        tab.show();
                        tabSwitched = true;
                    }
                } catch (err) {
                    console.log('Bootstrap Tab API error:', err);
                }
                
                // Fallback: manually trigger click if API didn't work
                if (!tabSwitched) {
                    $desktopTab[0].click();
                    tabSwitched = true;
                }
            }
            
            // Method 2: Direct tab pane manipulation (most reliable)
            if (!tabSwitched) {
                $('.tab-pane').removeClass('show active');
                var $targetPane = $('#' + tabId);
                if ($targetPane.length) {
                    $targetPane.addClass('show active');
                    // Also update desktop tab navigation
                    $('.nav-tabs .nav-link').removeClass('active');
                    $desktopTab.addClass('active');
                }
            }
            
            // Update button label and icon
            $label.text(itemText);
            $icon.text(itemIcon);
            
            // Update active state in dropdown
            $('.mobile-tab-item').removeClass('active');
            $item.addClass('active');
            
            // Close dropdown after selection
            setTimeout(function() {
                var dropdown = bootstrap.Dropdown.getInstance($button[0]);
                if (dropdown) {
                    dropdown.hide();
                }
            }, 150);
        });
        
        // Listen for Bootstrap tab shown event to sync mobile button when tabs switch
        $(document).on('shown.bs.tab', 'a[data-bs-toggle="tab"]', function(e) {
            var target = $(e.target).attr('href');
            if (!target) return;
            
            var $mobileItem = $('.mobile-tab-item[href="' + target + '"]');
            if ($mobileItem.length) {
                var $button = $('#mobileTabDropdown');
                var $label = $button.find('.dropdown-label');
                var $icon = $button.find('.material-icons').first();
                var itemText = $mobileItem.find('span').text();
                var itemIcon = $mobileItem.find('.material-icons').text();
                
                $label.text(itemText);
                $icon.text(itemIcon);
                
                $('.mobile-tab-item').removeClass('active');
                $mobileItem.addClass('active');
            }
        });
    });

    // ============================================
    // Global Functions
    // ============================================
    
    /**
     * Logout function
     */
    function do_logout() {
        $.get(app_base_url + "auth/ajax_logout", function(e) {
            location.reload();
        });
    }
    
    // Make do_logout available globally
    window.do_logout = do_logout;

    // ============================================
    // Login Modal Handler
    // ============================================
    $('#show_log').on('shown.bs.modal', function(e) {
        $('body').removeClass('modal-open');
        $('body').css("padding", "0px");
    });
    
    // ============================================
    // Material Icons Size Handler
    // ============================================
    (function() {
        function applyMaterialIconSizes() {
            var icons = document.querySelectorAll('.material-icons[size], .bi[size]');
            icons.forEach(function(icon) {
                var size = icon.getAttribute('size');
                if (size) {
                    icon.style.fontSize = size + 'px';
                }
            });
        }
        
        // Apply on DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', applyMaterialIconSizes);
        } else {
            applyMaterialIconSizes();
        }
    })();

})(jQuery);

