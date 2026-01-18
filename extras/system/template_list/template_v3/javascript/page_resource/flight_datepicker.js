/**
 * Flight Datepicker Configuration
 * Enhanced range picker with smart close behavior
 * This overrides the default datepicker initialization
 */

// Wait for default datepicker initialization to complete
$(document).ready(function() {
    // Use multiple attempts to ensure default initialization runs first
    var attempts = 0;
    var maxAttempts = 10;
    
    function tryInitialize() {
        attempts++;
        // Check if datepicker is already initialized
        if ($("#flight_datepicker1").hasClass('hasDatepicker') || attempts >= maxAttempts) {
            initializeFlightDatepicker();
        } else {
            setTimeout(tryInitialize, 50);
        }
    }
    
    // Start trying after a short delay
    setTimeout(tryInitialize, 150);
});

function initializeFlightDatepicker() {
    var selectedStartDate = null;
    var selectedEndDate = null;
    var isSelectingStart = true;
    
    // Get trip type
    function getTripType() {
        return $('[name="trip_type"]:checked').val();
    }
    
    // Format date to YYYY-MM-DD
    function formatDate(date) {
        if (!date) return null;
        var d = new Date(date);
        var year = d.getFullYear();
        var month = String(d.getMonth() + 1).padStart(2, '0');
        var day = String(d.getDate()).padStart(2, '0');
        return year + '-' + month + '-' + day;
    }
    
    // Check if date is in range
    function isDateInRange(date, start, end) {
        if (!start || !end) return false;
        var d = new Date(date);
        var s = new Date(start);
        var e = new Date(end);
        return d >= s && d <= e;
    }
    
    // Refresh both datepickers
    function refreshDatepickers() {
        if ($("#flight_datepicker1").length) {
            $("#flight_datepicker1").datepicker('refresh');
        }
        if ($("#flight_datepicker2").length) {
            $("#flight_datepicker2").datepicker('refresh');
        }
    }
    
    // Update custom date display elements
    function updateCustomDateDisplay($input, dateObj) {
        if (!dateObj) return;
        
        var day = String(dateObj.getDate()).padStart(2, '0');
        var monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        var month = monthNames[dateObj.getMonth()];
        var year = dateObj.getFullYear();
        var dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        var dayName = dayNames[dateObj.getDay()];
        
        // Find the parent container with class 'plcetogo' or 'datemark'
        var $container = $input.closest('.plcetogo, .datemark');
        if ($container.length === 0) {
            $container = $input.parent();
        }
        
        // Both departure and return use checkInDateDiv, checkInMonthDiv, etc.
        // Update date display elements
        $container.find('.checkInDateDiv').text(day);
        $container.find('.checkInMonthDiv').text(month);
        $container.find('.checkInYearDiv').text(year);
        $container.find('.checkInDayDiv').text(dayName);
        
        // Trigger change event to ensure form validation works
        $input.trigger('change');
    }
    
    // Override datepicker for departure date
    if ($("#flight_datepicker1").length) {
        // Destroy existing datepicker and reinitialize with our settings
        if ($("#flight_datepicker1").hasClass('hasDatepicker')) {
            $("#flight_datepicker1").datepicker('destroy');
        }
        
        $("#flight_datepicker1").datepicker({
            dateFormat: 'dd-mm-yy',
            minDate: 0,
            numberOfMonths: $(window).width() <= 767 ? 2 : 2, // Show 2 months on mobile for side-by-side
            beforeShow: function(input, inst) {
                // Add backdrop on mobile
                if ($(window).width() <= 767) {
                    $('body').addClass('datepicker-open');
                }
            },
            onClose: function(dateText, inst) {
                // Remove backdrop on mobile
                if ($(window).width() <= 767) {
                    $('body').removeClass('datepicker-open');
                }
            },
            beforeShowDay: function(date) {
                var dateString = formatDate(date);
                var classes = '';
                var isStart = false;
                var isEnd = false;
                var inRange = false;
                
                // Check if this is the start date
                if (selectedStartDate && dateString === selectedStartDate) {
                    classes += ' ui-datepicker-range-start';
                    isStart = true;
                }
                
                // Check if this is the end date
                if (selectedEndDate && dateString === selectedEndDate) {
                    classes += ' ui-datepicker-range-end';
                    isEnd = true;
                }
                
                // Check if date is in range
                if (selectedStartDate && selectedEndDate) {
                    if (isDateInRange(date, selectedStartDate, selectedEndDate)) {
                        if (!isStart && !isEnd) {
                            classes += ' ui-datepicker-range';
                            inRange = true;
                        }
                    }
                }
                
                // For roundtrip: show preview when selecting end date
                if (getTripType() === 'circle' && selectedStartDate && !selectedEndDate && !isSelectingStart) {
                    // This will be handled by hover, but we can add a class here if needed
                }
                
                return [true, classes];
            },
            onSelect: function(dateText, inst) {
                var tripType = getTripType();
                var $input = $(this);
                var inputId = $input.attr('id');
                
                // Get the actual selected date properly
                var selectedDateObj = $input.datepicker('getDate');
                var selectedDate = null;
                if (selectedDateObj) {
                    selectedDate = formatDate(selectedDateObj);
                    // Explicitly set the input value with correct format
                    var formattedDate = $.datepicker.formatDate('dd-mm-yy', selectedDateObj);
                    $input.val(formattedDate);
                    
                    // Update custom date display for departure date
                    if (inputId === 'flight_datepicker1') {
                        updateCustomDateDisplay($input, selectedDateObj);
                    }
                } else {
                    // Fallback: use inst data
                    var year = inst.selectedYear || inst.drawYear;
                    var month = inst.selectedMonth || inst.drawMonth;
                    var day = inst.selectedDay || inst.currentDay;
                    if (year && month !== undefined && day) {
                        selectedDate = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
                        var fallbackDate = new Date(year, month, day);
                        var formattedDate = $.datepicker.formatDate('dd-mm-yy', fallbackDate);
                        $input.val(formattedDate);
                        
                        // Update custom date display
                        if (inputId === 'flight_datepicker1') {
                            updateCustomDateDisplay($input, fallbackDate);
                        }
                    }
                }
                
                if (!selectedDate) return;
                
                if (tripType === 'oneway' || tripType === 'multicity') {
                    // For oneway and multicity, close after selecting departure date
                    selectedStartDate = selectedDate;
                    selectedEndDate = null;
                    isSelectingStart = true;
                    // Close datepicker after selection
                    setTimeout(function() {
                        $input.datepicker('hide');
                    }, 10);
                } else if (tripType === 'circle') {
                    // For roundtrip, implement range selection in the same datepicker
                    if (isSelectingStart || !selectedStartDate) {
                        // Selecting start date (departure)
                        selectedStartDate = selectedDate;
                        selectedEndDate = null;
                        isSelectingStart = false;
                        
                        // Set minimum date for return datepicker
                        if ($("#flight_datepicker2").length) {
                            var minDate = new Date(selectedStartDate);
                            minDate.setDate(minDate.getDate() + 1);
                            $("#flight_datepicker2").datepicker('option', 'minDate', minDate);
                        }
                        
                    } else {
                        // Selecting end date (return)
                        var startDateObj = new Date(selectedStartDate);
                        var endDateObj = new Date(selectedDate);
                        
                        if (endDateObj > startDateObj) {
                            selectedEndDate = selectedDate;
                            isSelectingStart = true;
                            
                            // Set return datepicker value
                            if ($("#flight_datepicker2").length) {
                                var formattedDate = $.datepicker.formatDate('dd-mm-yy', endDateObj);
                                $("#flight_datepicker2").val(formattedDate);
                                updateCustomDateDisplay($("#flight_datepicker2"), endDateObj);
                            }
                            // Close datepicker after selecting return date
                            setTimeout(function() {
                                $input.datepicker('hide');
                            }, 10);
                        } else if (endDateObj < startDateObj) {
                            // If selected date is before start date, reset and select new start
                            selectedStartDate = selectedDate;
                            selectedEndDate = null;
                            isSelectingStart = false;
                        } else {
                            // Same date selected, treat as start
                            selectedStartDate = selectedDate;
                            selectedEndDate = null;
                            isSelectingStart = false;
                        }
                    }
                }
                
                refreshDatepickers();
            },
            onChangeMonthYear: function(year, month, inst) {
                setTimeout(function() {
                    refreshDatepickers();
                }, 1);
            }
        });
    }
    
    // Override datepicker for return date (roundtrip only)
    if ($("#flight_datepicker2").length) {
        // Destroy existing datepicker and reinitialize with our settings
        if ($("#flight_datepicker2").hasClass('hasDatepicker')) {
            $("#flight_datepicker2").datepicker('destroy');
        }
        
        $("#flight_datepicker2").datepicker({
            dateFormat: 'dd-mm-yy',
            minDate: 1,
            numberOfMonths: $(window).width() <= 767 ? 2 : 2, // Show 2 months on mobile
            beforeShow: function(input, inst) {
                // Add backdrop on mobile
                if ($(window).width() <= 767) {
                    $('body').addClass('datepicker-open');
                }
            },
            onClose: function(dateText, inst) {
                // Remove backdrop on mobile
                if ($(window).width() <= 767) {
                    $('body').removeClass('datepicker-open');
                }
            },
            beforeShowDay: function(date) {
                var dateString = formatDate(date);
                var classes = '';
                
                // Highlight if it's in the selected range
                if (selectedStartDate && selectedEndDate) {
                    if (dateString === selectedStartDate) {
                        classes += ' ui-datepicker-range-start';
                    } else if (dateString === selectedEndDate) {
                        classes += ' ui-datepicker-range-end';
                    } else if (isDateInRange(date, selectedStartDate, selectedEndDate)) {
                        classes += ' ui-datepicker-range';
                    }
                }
                
                return [true, classes];
            },
            onSelect: function(dateText, inst) {
                var $input = $(this);
                var selectedDateObj = $input.datepicker('getDate');
                var selectedDate = formatDate(selectedDateObj);
                
                // Explicitly set the input value with correct format
                if (selectedDateObj) {
                    var formattedDate = $.datepicker.formatDate('dd-mm-yy', selectedDateObj);
                    $input.val(formattedDate);
                    // Update custom date display for return date
                    updateCustomDateDisplay($input, selectedDateObj);
                }
                
                // Set as end date
                if (selectedStartDate && new Date(selectedDate) > new Date(selectedStartDate)) {
                    selectedEndDate = selectedDate;
                    isSelectingStart = true;
                    // Close datepicker after selection
                    setTimeout(function() {
                        $input.datepicker('hide');
                    }, 10);
                } else if (selectedStartDate) {
                    // If selected date is before start date, update start date
                    selectedStartDate = selectedDate;
                    selectedEndDate = null;
                    isSelectingStart = false;
                    
                    // Update departure datepicker
                    if ($("#flight_datepicker1").length) {
                        var formattedDate = $.datepicker.formatDate('dd-mm-yy', new Date(selectedStartDate));
                        $("#flight_datepicker1").val(formattedDate);
                        updateCustomDateDisplay($("#flight_datepicker1"), new Date(selectedStartDate));
                        var minDate = new Date(selectedStartDate);
                        minDate.setDate(minDate.getDate() + 1);
                        $("#flight_datepicker2").datepicker('option', 'minDate', minDate);
                    }
                } else {
                    // No start date yet, set as start
                    selectedStartDate = selectedDate;
                    selectedEndDate = null;
                    isSelectingStart = false;
                    
                    if ($("#flight_datepicker1").length) {
                        var formattedDate = $.datepicker.formatDate('dd-mm-yy', new Date(selectedStartDate));
                        $("#flight_datepicker1").val(formattedDate);
                        updateCustomDateDisplay($("#flight_datepicker1"), new Date(selectedStartDate));
                    }
                    // Close datepicker after selection
                    setTimeout(function() {
                        $input.datepicker('hide');
                    }, 10);
                }
                
                refreshDatepickers();
            },
            onChangeMonthYear: function(year, month, inst) {
                setTimeout(function() {
                    refreshDatepickers();
                }, 1);
            }
        });
    }
    
    // Reset range selection when trip type changes
    $('[name="trip_type"]').on('change', function() {
        selectedStartDate = null;
        selectedEndDate = null;
        isSelectingStart = true;
        refreshDatepickers();
    });
    
    // Update return datepicker minDate when departure date changes (for roundtrip)
    $("#flight_datepicker1").on('change', function() {
        var tripType = getTripType();
        if (tripType === 'circle' && $("#flight_datepicker2").length) {
            var selectedDate = $(this).datepicker('getDate');
            if (selectedDate) {
                var minDate = new Date(selectedDate);
                minDate.setDate(minDate.getDate() + 1);
                $("#flight_datepicker2").datepicker('option', 'minDate', minDate);
                selectedStartDate = formatDate(selectedDate);
                selectedEndDate = null;
                isSelectingStart = false;
                refreshDatepickers();
            }
        }
    });
}
