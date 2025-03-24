function toggleDropdown(button) {
  const dropdown = button.nextElementSibling;
  dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';

  // Close other dropdowns
  const allDropdowns = document.querySelectorAll('.dropdown');
  allDropdowns.forEach(d => {
      if (d !== dropdown) {
          d.style.display = 'none';
      }
  });
}

// Close dropdown when clicking outside
window.onclick = function(event) {
  if (!event.target.matches('.action button')) {
      const dropdowns = document.querySelectorAll('.dropdown');
      dropdowns.forEach(d => d.style.display = 'none');
  }
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
  
<<<<<<< HEAD
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
=======
  const RevenueChart = () => {
    return (
      <ResponsiveContainer width="100%" height={300}>
        <BarChart data={data}>
          <CartesianGrid strokeDasharray="3 3" />
          <XAxis dataKey="name" />
          <YAxis />
          <Tooltip />
          <Bar dataKey="revenue" fill="#8884d8">
            {data.map((entry, index) => (
              <Cell key={`cell-${index}`} fill={entry.color} />
            ))}
          </Bar>
        </BarChart>
      </ResponsiveContainer>
    );
  };
  
  export default RevenueChart;

  // Close all dropdowns
  function closeAllDropdowns() {
    var dropdowns = document.getElementsByClassName("dropdown-menu");
    for (var i = 0; i < dropdowns.length; i++) {
      dropdowns[i].classList.remove("show");
    }
  }

  // Toggle dropdown visibility
  function toggleDropdown(button) {
    closeAllDropdowns();
    button.nextElementSibling.classList.toggle("show");
  }

  // Close dropdowns when clicking outside
  window.onclick = function(event) {
    if (!event.target.matches('.dropdown-button')) {
      closeAllDropdowns();
    }
  }
>>>>>>> ae620a801a1ef0c3491467cca4c97c2d6484f33d
