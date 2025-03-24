document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const categorySelect = document.getElementById("categorySelect");
  const tableRows = document.querySelectorAll("tbody tr");

  function filterTable() {
    const searchValue = searchInput.value.toLowerCase();
    const selectedCategory = categorySelect.value;

    tableRows.forEach((row) => {
      const productName = row.children[2].textContent.toLowerCase();
      const categoryId = row.dataset.category;

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

function toggleSelectAll(selectAllCheckbox) {
  const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
  checkboxes.forEach((checkbox) => {
    checkbox.checked = selectAllCheckbox.checked;
    toggleEdit(checkbox);
  });
  toggleUpdateButton();
}

function toggleEdit(checkbox) {
  const row = checkbox.closest("tr");
  const quantityInput = row.querySelector(".quantity-input");
  const priceInput = row.querySelector(".price-input");
  quantityInput.disabled = !checkbox.checked; // Enable/disable editing based on checkbox state
  priceInput.disabled = !checkbox.checked; // Enable/disable price editing based on checkbox state

  if (checkbox.checked) {
    // If checked, make the price editable
    priceInput.removeAttribute("disabled");
  } else {
    // If unchecked, disable the price editing
    priceInput.setAttribute("disabled", "true");
  }
}

function toggleUpdateButton() {
  const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
  const updateButtonContainer = document.getElementById("updateButtonContainer");
  const anyChecked = Array.from(checkboxes).some((checkbox) => checkbox.checked);
  updateButtonContainer.style.display = anyChecked ? "block" : "none"; // Show/hide the button
}

// Function to update total price dynamically when quantity or price changes
function updateTotalPrice(input) {
  const row = input.closest('tr');
  const quantity = parseFloat(row.querySelector('.quantity-input').value); // Get the updated quantity
  const pricePerUnit = parseFloat(row.querySelector('.price-input').value); // Get the price per unit
  const totalPriceCell = row.querySelector('.total-price'); // Get the total price cell
  const totalPrice = quantity * pricePerUnit; // Calculate the total price
  totalPriceCell.textContent = totalPrice.toFixed(2); // Display the total price with 2 decimals
}

// Function to handle the update button click
function handleUpdateClick() {
  const updatedItems = [];
  const selectedCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]:checked');
  
  selectedCheckboxes.forEach((checkbox) => {
    const row = checkbox.closest('tr');
    const id = row.dataset.id; // Assuming you store the ID in data-id attribute
    const quantity = row.querySelector('.quantity-input').value;
    const price = row.querySelector('.price-input').value;
    const totalPrice = row.querySelector('.total-input').value;

    updatedItems.push({
      id: id,
      quantity: quantity,
      price: price, // Include price for update
      total_price: totalPrice
    });
  });

  // Send the updated items data to the server via AJAX
  updateInventoryData(updatedItems);
}

// Function to send updated inventory data to the server
function updateInventoryData(updatedItems) {
  const data = {
    updatedItems: JSON.stringify(updatedItems) // Convert the updated items to JSON
  };

  $.ajax({
    type: 'POST',
    url: '/inventory/update', // Assuming this is the correct endpoint to update inventory
    data: data,
    success: function(response) {
      console.log('Inventory updated successfully');
      // Optionally, handle response and update UI
    },
    error: function(error) {
      console.log('Error updating inventory:', error);
    }
  });
}

// Function to release the checked checkboxes after updating
function releaseCheckedCheckboxes() {
  const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]:checked');
  checkboxes.forEach((checkbox) => {
    checkbox.checked = false; // Uncheck the checkbox
    toggleEdit(checkbox); // Disable editing
  });
  toggleUpdateButton(); // Hide the update button after updating
}
