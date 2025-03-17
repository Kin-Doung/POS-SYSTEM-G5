function toggleVisibility(id, icon) {
    let element = document.getElementById(id);
    if (element.classList.contains("blur-text")) {
        element.classList.remove("blur-text"); // Remove blur
        icon.textContent = "👁️"; // Change to hidden-eye emoji
    } else {
        element.classList.add("blur-text"); // Add blur
        icon.textContent = "✖️"; // Change to eye emoji
    }
}

