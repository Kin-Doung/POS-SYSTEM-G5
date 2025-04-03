// const body = document.querySelector("body");
// const toggle = document.querySelector("#toggle");
// const sunIcon = document.querySelector(".toggle .bxs-sun");
// const moonIcon = document.querySelector(".toggle .bx-moon");

// toggle.addEventListener("change", () => {
    
//     body.classList.toggle("dark");
//     sunIcon.className = sunIcon.className == "bx bxs-sun" ? "bx bx-sun" : "bx bxs-sun";
//     moonIcon.className = moonIcon.className == "bx bxs-moon" ? "bx bx-moon" : "bx bxs-moon";

// });

// Get the toggle checkbox
const toggle = document.getElementById("toggle")

// Add event listener for the toggle
toggle.addEventListener("change", function () {
  if (this.checked) {
    document.body.classList.replace("light", "dark")
    // Save user preference to localStorage
    localStorage.setItem("darkMode", "enabled")
  } else {
    document.body.classList.replace("dark", "light")
    // Save user preference to localStorage
    localStorage.setItem("darkMode", "disabled")
  }
})

// Check for saved user preference on page load
window.addEventListener("DOMContentLoaded", () => {
  const darkMode = localStorage.getItem("darkMode")

  if (darkMode === "enabled") {
    toggle.checked = true
    document.body.classList.replace("light", "dark")
  }
})
