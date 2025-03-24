document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const categorySelect = document.getElementById("categorySelect");
  const tableRows = document.querySelectorAll("tbody tr");

  function filterTable() {
    const searchValue = searchInput.value.toLowerCase();
    const selectedCategory = categorySelect.value;

    tableRows.forEach((row) => {
      const productName = row.children[2].textContent.toLowerCase();
      const categoryId = row.dataset.category; // Make sure category ID is stored in the row

      const matchesSearch = productName.includes(searchValue);
      const matchesCategory =
        selectedCategory === "" || categoryId === selectedCategory;

      if (matchesSearch && matchesCategory) {
        row.style.display = "";
      } else {
        row.style.display = "none";
      }
    });
  }

  searchInput.addEventListener("input", filterTable);
  categorySelect.addEventListener("change", filterTable);
});
