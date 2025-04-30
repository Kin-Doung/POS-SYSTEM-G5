  // Navbar Active Link
  const currentPath = window.location.pathname;
  document.querySelectorAll(".nav-link").forEach((link) => {
    const icon = link.querySelector("i");
    const span = link.querySelector(".nav-link-text");
  
    if (link.getAttribute("href") === currentPath) {
      link.classList.add("active");
      link.style.backgroundColor = "#6f42c1"; // Purple background
      link.style.color = "white"; // White text
      if (span) span.style.color = "white";
      if (icon) icon.style.color = "white";
    } else {
      link.classList.remove("active");
      link.style.backgroundColor = "transparent"; // No background
      link.style.color = "black"; // Black text
      if (span) span.style.color = "black";
      if (icon) icon.style.color = "black";
    }
  });
  