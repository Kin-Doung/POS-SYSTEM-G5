themeToggle.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");
    
    // Save user preference to local storage
    if (document.body.classList.contains("dark-mode")) {
        localStorage.setItem("theme", "dark");
    } else {
        localStorage.setItem("theme", "light");
    }
});