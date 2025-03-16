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