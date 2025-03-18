document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("searchInput").addEventListener("keyup", filterProducts);
});

function filterProducts() {
    let searchInput = document.getElementById("searchInput").value.toLowerCase().replace(/[^a-z]/g, ""); // Only letters A-Z
    let products = document.querySelectorAll(".product-grid .card");

    products.forEach(product => {
        let productName = product.getAttribute("data-name").toLowerCase();
        
        // Show only if the product name contains the search input
        if (productName.includes(searchInput)) {
            product.style.display = "block";
        } else {
            product.style.display = "none";
        }
    });
}