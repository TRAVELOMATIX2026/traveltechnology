/**
 * Enhanced Menu Dropdown Functionality
 * Ensures submenu dropdowns work perfectly
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        console.log('Menu Dropdown Fix Loaded');
        
        // Initialize dropdown functionality
        initMenuDropdowns();
    });

    function initMenuDropdowns() {
        // Target all menu items with dropdowns
        $('.sidebar-menu .treeview > a').each(function() {
            var $link = $(this);
            var $parent = $link.parent('.treeview');
            
            // Find the wrapper div and dropdown
            var $wrapperDiv = $parent.find('> div').first();
            var $dropdown = $wrapperDiv.find('.menu-dropdown').first();
            
            // Fallback: check if dropdown is direct child
            if ($dropdown.length === 0) {
                $dropdown = $parent.find('> .menu-dropdown').first();
            }
            
            if ($dropdown.length > 0 || $wrapperDiv.length > 0) {
                // Remove any existing click handlers
                $link.off('click.menuDropdown');
                
                // Add our enhanced click handler
                $link.on('click.menuDropdown', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    
                    // Prevent action if currently animating
                    if ($parent.hasClass('menu-opening') || $parent.hasClass('menu-closing')) {
                        return false;
                    }
                    
                    var isOpen = $parent.hasClass('menu-open');
                    var $arrow = $link.find('.menu-item-arrow');
                    
                    console.log('Menu clicked:', $link.find('.menu-item-text').text(), 'isOpen:', isOpen);
                    
                    if (isOpen) {
                        // Close this menu
                        closeMenuDropdown($parent, $wrapperDiv, $dropdown, $arrow);
                    } else {
                        // Close all sibling menus first
                        $parent.siblings('.treeview.menu-open').each(function() {
                            var $siblingParent = $(this);
                            if (!$siblingParent.hasClass('menu-closing')) {
                                var $siblingWrapper = $siblingParent.find('> div').first();
                                var $siblingDropdown = $siblingWrapper.find('.menu-dropdown').first();
                                var $siblingArrow = $siblingParent.find('> a .menu-item-arrow');
                                closeMenuDropdown($siblingParent, $siblingWrapper, $siblingDropdown, $siblingArrow);
                            }
                        });
                        
                        // Small delay to ensure sibling menus start closing first
                        setTimeout(function() {
                            if (!$parent.hasClass('menu-opening') && !$parent.hasClass('menu-closing')) {
                                openMenuDropdown($parent, $wrapperDiv, $dropdown, $arrow);
                            }
                        }, 50);
                    }
                    
                    return false;
                });
            }
        });
        
        console.log('Initialized', $('.sidebar-menu .treeview > a').length, 'menu items');
    }

    function openMenuDropdown($parent, $wrapperDiv, $dropdown, $arrow) {
        // Prevent multiple rapid clicks
        if ($parent.hasClass('menu-opening') || $parent.hasClass('menu-open')) {
            return;
        }
        
        $parent.addClass('menu-opening menu-open');
        $arrow.removeClass('menu-item-arrow-inactive').addClass('menu-item-arrow-active');
        
        // Disable CSS transitions during animation
        if ($wrapperDiv.length > 0) {
            $wrapperDiv.css({
                'display': 'block',
                'transition': 'none'
            });
        }
        
        // Then animate dropdown
        if ($dropdown.length > 0) {
            $dropdown.css({
                'display': 'flex',
                'transition': 'none',
                'height': '0',
                'overflow': 'hidden'
            });
            
            $dropdown.stop(true, true).animate({
                height: $dropdown[0].scrollHeight
            }, 300, function() {
                $dropdown.css({
                    'height': 'auto',
                    'overflow': 'visible',
                    'transition': ''
                });
                $parent.removeClass('menu-opening');
            });
        } else {
            $parent.removeClass('menu-opening');
        }
        
        console.log('Opened menu');
    }

    function closeMenuDropdown($parent, $wrapperDiv, $dropdown, $arrow) {
        // Prevent multiple rapid clicks
        if ($parent.hasClass('menu-closing') || !$parent.hasClass('menu-open')) {
            return;
        }
        
        $parent.addClass('menu-closing').removeClass('menu-open');
        $arrow.removeClass('menu-item-arrow-active').addClass('menu-item-arrow-inactive');
        
        // Animate dropdown close
        if ($dropdown.length > 0) {
            $dropdown.css({
                'height': $dropdown[0].scrollHeight + 'px',
                'overflow': 'hidden',
                'transition': 'none'
            });
            
            $dropdown.stop(true, true).animate({
                height: 0
            }, 300, function() {
                $dropdown.css({
                    'display': 'none',
                    'height': '',
                    'overflow': '',
                    'transition': ''
                });
                
                // Hide wrapper div after animation completes
                if ($wrapperDiv.length > 0) {
                    $wrapperDiv.css({
                        'display': 'none',
                        'transition': ''
                    });
                }
                
                $parent.removeClass('menu-closing');
            });
        } else if ($wrapperDiv.length > 0) {
            // If no dropdown, just hide the wrapper
            $wrapperDiv.stop(true, true).animate({
                height: 0
            }, 300, function() {
                $wrapperDiv.css('display', 'none');
                $parent.removeClass('menu-closing');
            });
        } else {
            $parent.removeClass('menu-closing');
        }
        
        console.log('Closed menu');
    }

})(jQuery);
