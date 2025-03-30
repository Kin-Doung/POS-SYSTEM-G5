document.getElementById("profile").addEventListener("click", function(event) {
  let menu = document.getElementById("menu");
  menu.style.display = menu.style.display === "block" ? "none" : "block";
  event.stopPropagation();
});

document.addEventListener("click", function() {
  document.getElementById("menu").style.display = "none";
});

document.getElementById("menu").addEventListener("click", function(event) {
  event.stopPropagation();
});
