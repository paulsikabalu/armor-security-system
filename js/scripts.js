// Wait for the DOM to fully load before running scripts
$(document).ready(function() {
    // Show dropdown on hover for desktop screens
    $(".navbar .nav-item").hover(
        function() {
            $(this).find(".dropdown-menu").stop(true, true).delay(100).fadeIn(200);
        },
        function() {
            $(this).find(".dropdown-menu").stop(true, true).delay(100).fadeOut(200);
        }
    );

    // Additional custom JavaScript can be added here
});
