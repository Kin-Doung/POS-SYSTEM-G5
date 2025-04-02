document.addEventListener("DOMContentLoaded", function () {
  // Edit Modal Population
  document.querySelectorAll('.dropdown-item[data-bs-target="#editModal"]').forEach((link) => {
    link.addEventListener("click", function (event) {
      const productName = event.target.getAttribute("data-product_name");
      const categoryId = event.target.getAttribute("data-category_id");
      const quantity = event.target.getAttribute("data-quantity");
      const amount = event.target.getAttribute("data-amount");
      const expirationDate = event.target.getAttribute("data-expiration_date");
      const image = event.target.getAttribute("data-image");
      const id = event.target.getAttribute("data-id");

      document.getElementById("product_name").value = productName;
      document.getElementById("category_id").value = categoryId;
      document.getElementById("quantity").value = quantity;
      document.getElementById("amount").value = amount;
      document.getElementById("expiration_date").value = expirationDate;
      document.getElementById("imagePreview").src = image ? image : "";
      document.querySelector("#editModal form").action = "/inventory/update?id=" + id;
    });
  });

  // Table Filtering and Sorting Setup
  const searchInput = document.getElementById("searchInput");
  const categorySelect = document.getElementById("categorySelect");
  const tableRows = document.querySelectorAll("tbody tr");
  const tableHeadings = document.querySelectorAll("thead th");

  // Filter Table Function
  function filterTable() {
    const searchValue = searchInput.value.toLowerCase();
    const selectedCategory = categorySelect.value;

    tableRows.forEach((row) => {
      const productName = row.children[2].textContent.toLowerCase(); // Assuming product name is 3rd column
      const categoryId = row.getAttribute("data-category-id");

      const matchesSearch = productName.includes(searchValue);
      const matchesCategory = selectedCategory === "" || categoryId === selectedCategory;

      row.style.display = matchesSearch && matchesCategory ? "" : "none";
    });
  }

  // Search and Category Filter Event Listeners
  searchInput.addEventListener("input", filterTable);
  categorySelect.addEventListener("change", filterTable);

  // Quantity Background Color Logic
  tableRows.forEach((row) => {
    const quantityCell = row.querySelector("td:nth-child(4)"); // Quantity is 4th column
    const quantitySpan = quantityCell.querySelector(".quantity-text");
    const quantity = parseInt(quantitySpan.textContent, 10);

    // Remove previous classes
    quantitySpan.classList.remove("quantity-red", "quantity-orange", "quantity-green");

    // Apply new background color based on quantity
    if (quantity < 20) {
      quantitySpan.classList.add("quantity-red");
    } else if (quantity > 50) {
      quantitySpan.classList.add("quantity-green");
    } else {
      quantitySpan.classList.add("quantity-orange");
    }
  });

  // Total Price Calculation
  const quantityInput = document.getElementById("quantity");
  const priceInput = document.getElementById("amount");
  const totalPriceInput = document.getElementById("total_price");

  function calculateTotalPrice() {
    const quantity = parseFloat(quantityInput.value) || 0;
    const price = parseFloat(priceInput.value) || 0;
    const totalPrice = quantity * price;
    totalPriceInput.value = totalPrice.toFixed(2);
  }

  if (quantityInput && priceInput) {
    quantityInput.addEventListener("input", calculateTotalPrice);
    priceInput.addEventListener("input", calculateTotalPrice);
  }

  // Navbar Active Link
  const currentPath = window.location.pathname;
  document.querySelectorAll(".nav-link").forEach((link) => {
    const icon = link.querySelector("i");
    const span = link.querySelector(".nav-link-text");

    if (link.getAttribute("href") === currentPath) {
      link.classList.add("active");
      link.style.backgroundColor = "#6f42c1";
      link.style.color = "white";
      if (span) span.style.color = "white";
      if (icon) icon.style.color = "white";
    } else {
      link.classList.remove("active");
      link.style.backgroundColor = "transparent";
      link.style.color = "black";
      if (span) span.style.color = "black";
      if (icon) icon.style.color = "black";
    }
  });

  // Table Sorting
  tableHeadings.forEach((head, i) => {
    let sortAsc = true;
    head.onclick = () => {
      tableHeadings.forEach((h) => h.classList.remove("active"));
      head.classList.add("active");

      tableRows.forEach((row) => {
        row.querySelectorAll("td").forEach((td) => td.classList.remove("active"));
        row.querySelectorAll("td")[i].classList.add("active");
      });

      head.classList.toggle("asc", sortAsc);
      sortAsc = head.classList.contains("asc") ? false : true;

      sortTable(i, sortAsc);
    };
  });

  function sortTable(column, sortAsc) {
    const sortedRows = Array.from(tableRows).sort((a, b) => {
      const firstRow = a.querySelectorAll("td")[column].textContent.toLowerCase();
      const secondRow = b.querySelectorAll("td")[column].textContent.toLowerCase();
      return sortAsc
        ? firstRow < secondRow ? 1 : -1
        : firstRow < secondRow ? -1 : 1;
    });
    sortedRows.forEach((row) => document.querySelector("tbody").appendChild(row));
  }
});

// CSS Styles for Quantity Background Colors (Add this to your existing CSS)
const quantityStyles = `
  .quantity-red {
    background-color: red;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
  }
  .quantity-orange {
    background-color: #fd7e14;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
  }
  .quantity-green {
    background-color: #28a745;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
  }
`;

// Inject styles into the document (if not already in your CSS file)
const styleSheet = document.createElement("style");
styleSheet.textContent = quantityStyles;
document.head.appendChild(styleSheet);