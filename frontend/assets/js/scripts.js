window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        document.body.classList.toggle('sb-sidenav-toggled');
        }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});


$(document).ready(function() {
    
    function getUserRole() {
        return 'employee';
    }

    function loadNavigationAndInitializeSPApp() {
        var userRole = getUserRole();
        
        if (userRole === 'admin') {
            //console.log("Displaying admin sections");

            $("#topNavigation").load("./views/admin-top-navigation.html"),
            $("#sidenavAccordion").load("./views/admin-side-navigation.html");
            
            } else if (userRole === 'employee') {
                console.log("Displaying employee sections");
                $("#topNavigation").load("./views/employee-top-navigation.html"),
                $("#sidenavAccordion").load("./views/employee-side-navigation.html");
            
            
        }
    }

    loadNavigationAndInitializeSPApp();
    
});