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


function filterTable() {
  const searchInput = document.getElementById('searchInput').value.toLowerCase();
  const categorySelect = document.getElementById('categorySelect').value;
  const tableRows = document.querySelectorAll('#ordersTable tbody tr');

  tableRows.forEach(row => {
      const customerName = row.cells[2].textContent.toLowerCase();
      const categoryMatch = categorySelect === "" || row.getAttribute('data-category') === categorySelect;
      const searchMatch = customerName.includes(searchInput);

      row.style.display = (categoryMatch && searchMatch) ? '' : 'none';
  });
}

// Add event listener for search input
document.getElementById('searchInput').addEventListener('input', filterTable);

function toggleBatchAction(checkbox) {
  const batchActionBtn = document.getElementById('batchActionBtn');
  const updateQuantitySection = document.getElementById('updateQuantitySection');

  const checkboxes = document.querySelectorAll('.select-checkbox');
  const anyChecked = Array.from(checkboxes).some(chk => chk.checked);
  
  // Enable or disable the batch action button
  batchActionBtn.disabled = !anyChecked;
  updateQuantitySection.style.display = anyChecked ? 'block' : 'none';

  // Enable or disable quantity inputs based on checkbox selection
  checkboxes.forEach(chk => {
      const quantityInput = chk.closest('tr').querySelector('.quantity-input');
      quantityInput.disabled = !chk.checked; // Enable if checked, disable if not
  });
}

function updateQuantities() {
  const checkboxes = document.querySelectorAll('.select-checkbox:checked');
  checkboxes.forEach(checkbox => {
      const row = checkbox.closest('tr');
      const quantityInput = row.querySelector('.quantity-input');
      quantityInput.disabled = false; // Enable input for editing
  });

  // Uncheck all checkboxes after updating
  checkboxes.forEach(checkbox => {
      checkbox.checked = false;
      const row = checkbox.closest('tr');
      const quantityInput = row.querySelector('.quantity-input');
      quantityInput.disabled = true; // Disable input after update
  });

  // Disable batch action button and hide update section
  document.getElementById('batchActionBtn').disabled = true;
  document.getElementById('updateQuantitySection').style.display = 'none';
}

  searchInput.addEventListener("input", filterTable);
  categorySelect.addEventListener("change", filterTable);
});