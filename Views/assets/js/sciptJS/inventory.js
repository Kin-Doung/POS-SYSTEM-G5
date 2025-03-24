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


// nav bar active js

document.addEventListener("DOMContentLoaded", function () {
  let currentPath = window.location.pathname;

  document.querySelectorAll(".nav-link").forEach(link => {
    let icon = link.querySelector("i"); // Get the icon inside the link

    if (link.getAttribute("href") === currentPath) {
      link.classList.add("active");
      link.style.backgroundColor = "#6f42c1"; 
      link.style.color = "white"; 

      let span = link.querySelector(".nav-link-text");
      if (span) span.style.color = "white";
      if (icon) icon.style.color = "white"; // Change icon color
    } else {
      link.classList.remove("active");
      link.style.backgroundColor = "transparent"; 
      link.style.color = "black"; 

      let span = link.querySelector(".nav-link-text");
      if (span) span.style.color = "black";
      if (icon) icon.style.color = "black"; // Reset icon color
    }
  });
});


// the style of the table

// 1. Searching for specific data of HTML table
const search = document.querySelector('.input-group input'),
    tableRows = document.querySelectorAll('tbody tr'),
    tableHeadings = document.querySelectorAll('thead th');

search.addEventListener('input', searchTable);

function searchTable() {
    tableRows.forEach((row, i) => {
        let tableData = row.textContent.toLowerCase(),
            searchData = search.value.toLowerCase();

        row.classList.toggle('hide', tableData.indexOf(searchData) < 0);
        row.style.setProperty('--delay', i / 25 + 's');
    });

    document.querySelectorAll('tbody tr:not(.hide)').forEach((visibleRow, i) => {
        visibleRow.style.backgroundColor = (i % 2 === 0) ? 'transparent' : '#0000000b';
    });
}

// 2. Sorting the table data based on column header click
tableHeadings.forEach((head, i) => {
    let sortAsc = true;
    head.onclick = () => {
        tableHeadings.forEach(head => head.classList.remove('active'));
        head.classList.add('active');

        document.querySelectorAll('td').forEach(td => td.classList.remove('active'));
        tableRows.forEach(row => {
            row.querySelectorAll('td')[i].classList.add('active');
        });

        head.classList.toggle('asc', sortAsc);
        sortAsc = head.classList.contains('asc') ? false : true;

        sortTable(i, sortAsc);
    }
});

function sortTable(column, sortAsc) {
    [...tableRows].sort((a, b) => {
        let firstRow = a.querySelectorAll('td')[column].textContent.toLowerCase(),
            secondRow = b.querySelectorAll('td')[column].textContent.toLowerCase();

        return sortAsc ? (firstRow < secondRow ? 1 : -1) : (firstRow < secondRow ? -1 : 1);
    })
    .map(sortedRow => document.querySelector('tbody').appendChild(sortedRow));
}



// JavaScript to apply quantity background color
const inventoryRows = document.querySelectorAll('tbody tr');

inventoryRows.forEach(row => {
    const quantityCell = row.querySelector('td:nth-child(4)'); // Assuming Quantity is the 4th column
    const quantitySpan = quantityCell.querySelector('.quantity-text'); // Get the span containing the quantity

    const quantity = parseInt(quantitySpan.textContent, 10); // Get the quantity as a number

    // Remove any previously applied quantity background classes
    quantitySpan.classList.remove('quantity-green', 'quantity-blue', 'quantity-orange', 'quantity-red');

    // Add the appropriate class based on the quantity value
    if (quantity >= 80) {
        quantitySpan.classList.add('quantity-green');
    } else if (quantity >= 60) {
        quantitySpan.classList.add('quantity-blue');
    } else if (quantity >= 40) {
        quantitySpan.classList.add('quantity-orange');
    } else {
        quantitySpan.classList.add('quantity-red');
    }
});

