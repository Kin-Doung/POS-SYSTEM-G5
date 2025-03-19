// cart.js
function updateTotals(productName, productPrice) {
    const quantityInput = document.getElementById('quantity' + productName);
    const quantity = parseInt(quantityInput.value) || 0;

    const totalCell = document.getElementById('details');
    let productRow = document.getElementById('row-' + productName);
    if (!productRow) {
        productRow = document.createElement('tr');
        productRow.id = 'row-' + productName;
        productRow.innerHTML = `
            <td>${productName}</td>
            <td>${quantity}</td>
            <td>$${productPrice.toFixed(2)}</td>
            <td>$${(productPrice * quantity).toFixed(2)}</td>`;
        totalCell.appendChild(productRow);
    } else {
        productRow.cells[1].innerText = quantity;
        productRow.cells[3].innerText = `$${(productPrice * quantity).toFixed(2)}`;
    }

    // Update overall total
    let overallTotal = 0;
    const rows = totalCell.getElementsByTagName('tr');
    for (let row of rows) {
        const total = parseFloat(row.cells[3].innerText.replace('$', '')) || 0;
        overallTotal += total;
    }
    document.getElementById('totalPrice').innerText = 'Cart Total: $' + overallTotal.toFixed(2);
}


// scripts.js

// JavaScript to handle the button click and show details
document.querySelector('.btn-info').addEventListener('click', function() {
    const rows = document.querySelectorAll('tbody tr');
    let details = "";

    rows.forEach(row => {
        const imgSrc = row.cells[0].querySelector('img').src;
        const name = row.cells[1].innerText;
        const price = row.cells[2].innerText;
        const quantity = row.querySelector('input[type="number"]').value;

        details += `
            <tr>
                <td><img src="${imgSrc}" alt="${name}" class="img-fluid rounded-circle" style="max-width: 50px;"></td>
                <td>${name}</td>
                <td>${price}</td>
                <td>${quantity}</td>
            </tr>
        `;
    });

    document.getElementById('modalBodyContent').innerHTML = details;
});

// JavaScript for handling button actions
document.getElementById('savePdfBtn').addEventListener('click', function() {
    alert('Save PDF functionality not implemented yet.');
});

document.getElementById('restockBtn').addEventListener('click', function() {
    alert('Restock functionality not implemented yet.');
});