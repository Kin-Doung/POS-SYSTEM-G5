const profileButton = document.getElementById("profile");
const menu = document.getElementById("menu");

// Show menu on hover
profileButton.addEventListener("mouseenter", function() {
    menu.style.display = "block";
});

// Hide menu when mouse leaves profile button or menu
profileButton.addEventListener("mouseleave", function() {
    menu.style.display = "none";
});

menu.addEventListener("mouseenter", function() {
    menu.style.display = "block"; // Keep the menu open when hovering over it
});

menu.addEventListener("mouseleave", function() {
    menu.style.display = "none"; // Hide the menu when not hovering
});

// Prevent clicks from closing the menu
menu.addEventListener("click", function(event) {
    event.stopPropagation();
});