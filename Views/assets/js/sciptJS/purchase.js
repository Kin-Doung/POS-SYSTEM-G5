function filterTable() {
  const searchInput = document.getElementById("searchInput").value.toLowerCase();
  const categorySelect = document.getElementById("categorySelect").value;
  const tableRows = document.querySelectorAll("tbody tr");

  tableRows.forEach(row => {
      const productName = row.querySelector("td:nth-child(3)").textContent.toLowerCase();
      const categoryId = row.getAttribute("data-category-id");
      const quantity = row.querySelector(".quantity-text").textContent.toLowerCase();

      // Check if category or search input matches
      const matchesSearch = productName.includes(searchInput) || quantity.includes(searchInput);
      const matchesCategory = categorySelect === "" || categorySelect === categoryId;

      if (matchesSearch && matchesCategory) {
          row.style.display = "";
      } else {
          row.style.display = "none";
      }
  });
}

// Optionally, add event listener for initial filtering after page load
document.getElementById("searchInput").addEventListener("input", filterTable);
document.getElementById("categorySelect").addEventListener("change", filterTable);
