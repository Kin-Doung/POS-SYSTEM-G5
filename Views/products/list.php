<?php
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';
?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

    <!-- Navbar -->
    <?php require_once './views/layouts/nav.php' ?>
    <!-- Remove Nav bar that code with html
        using import navbar instead -->
    <!-- End Navbar -->
    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Product List Section -->
            <div class="col-lg-8 col-md-7">
                <h3 class="mb-3 text-dark">Order Products</h3>
                <div class="row">
                    <?php foreach ($inventory as $item): ?>
                        <div class="col-6 col-sm-4 col-md-3 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="image-wrapper">
                                    <?php if (!empty($item['image'])): ?>
                                        <img src="<?= htmlspecialchars($item['image']) ?>"
                                            class="card-img-top"
                                            alt="<?= htmlspecialchars($item['inventory_product_name']) ?>"
                                            onerror="this.src='/views/assets/images/default-product.jpg'">
                                    <?php else: ?>
                                        <img src="/views/assets/images/default-product.jpg"
                                            class="card-img-top"
                                            alt="Default Product Image">
                                    <?php endif; ?>
                                </div>
                                <div class="card-body text-center p-2">
                                    <h6 class="card-title mb-1"><?= htmlspecialchars($item['inventory_product_name']) ?></h6>
                                    <p class="card-text text-success mb-1 price" data-id="<?= htmlspecialchars($item['inventory_id']) ?>">
                                        $<?= htmlspecialchars($item['amount']) ?>
                                    </p>
                                    <p style="display: none;" class="card-text text-muted mb-2 quantity" data-id="<?= htmlspecialchars($item['inventory_id']) ?>">
                                        Qty: <?= htmlspecialchars($item['quantity']) ?>
                                    </p>
                                    <input type="hidden" name="inventory_id" value="<?= htmlspecialchars($item['inventory_id']) ?>" />
                                    <button class="buy btn btn-primary btn-sm w-100">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Cart Section -->
            <div class="col-lg-4 col-md-5">
                <div class="card shadow-sm border-0" id="cartSection" style="display: none;">
                    <div class="card-header bg-dark text-white text-center">
                        <h4 class="mb-0">POS Terminal</h4>
                    </div>
                    <div class="card-body p-3">
                        <table class="table table-hover table-bordered text-center" id="cartTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Price ($)</th>
                                </tr>
                            </thead>
                            <tbody id="cartBody"></tbody>
                        </table>
                        <div class="text-end mt-3">
                            <h5 class="fw-bold">Total: $<span id="grandTotal">0.00</span></h5>
                        </div>
                    </div>
                    <div class="card-footer bg-light p-3 text-center">
                        <button class="btn btn-success btn-sm mx-1" id="submitCart">Checkout</button>
                        <button class="btn btn-info btn-sm mx-1" id="savePdf">Save Receipt</button>
                        <button class="btn btn-secondary btn-sm mx-1" id="printCart">Print Receipt</button>
                        <button class="btn btn-danger btn-sm mx-1" id="clearCart">Clear</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'views/layouts/footer.php'; ?>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    function showCart() {
        document.getElementById('cartSection').style.display = 'block';
    }

    function updateCard(inventoryId, newQuantity) {
        const qtyElement = document.querySelector(`.quantity[data-id="${inventoryId}"]`);
        if (qtyElement) qtyElement.textContent = `Qty: ${newQuantity}`;
    }

    function updateGrandTotal() {
        const total = Array.from(document.querySelectorAll('#cartBody tr'))
            .reduce((sum, row) => {
                const qty = parseInt(row.querySelector('.cart-qty').value) || 1;
                const price = parseFloat(row.querySelector('.cart-price').value) || 0;
                return sum + (qty * price);
            }, 0);
        document.getElementById('grandTotal').textContent = total.toFixed(2);
    }

    document.querySelectorAll('.buy').forEach(button => {
        button.addEventListener('click', async function() {
            const card = this.closest('.card');
            const inventoryId = card.querySelector('input[name="inventory_id"]').value;
            const productName = card.querySelector('.card-title').textContent.trim();
            const price = parseFloat(card.querySelector('.price').textContent.replace('$', ''));
            const quantity = parseInt(card.querySelector('.quantity').textContent.replace('Qty: ', ''));

            const existingRow = document.querySelector(`#cartBody tr[data-id="${inventoryId}"]`);
            if (existingRow) {
                const qtyInput = existingRow.querySelector('.cart-qty');
                qtyInput.value = parseInt(qtyInput.value) + 1;
                qtyInput.dispatchEvent(new Event('input'));
                return;
            }

            try {
                const response = await fetch('/products/syncQuantity', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        inventoryId,
                        quantity
                    })
                });

                if (!response.ok) throw new Error(`Server error: ${response.status}`);
                const data = await response.json();

                if (data.success) {
                    showCart();

                    const row = document.createElement('tr');
                    row.dataset.id = inventoryId;
                    const initialQty = 1;
                    const initialPrice = price;

                    row.innerHTML = `
                        <td class="align-middle">${productName}</td>
                        <td><input type="number" class="cart-qty form-control form-control-sm d-inline text-center" min="1" max="${quantity}" value="${initialQty}" style="width: 60px;"></td>
                        <td><input type="number" class="cart-price form-control form-control-sm d-inline text-center" min="0" step="0.01" value="${initialPrice.toFixed(2)}" style="width: 80px;"></td>
                    `;
                    document.getElementById('cartBody').appendChild(row);
                    updateGrandTotal();
                } else {
                    alert(`Error syncing quantity: ${data.message}`);
                }
            } catch (error) {
                console.error('Add to cart failed:', error);
                alert(`Failed to add item: ${error.message}`);
            }
        });
    });

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('cart-qty') || e.target.classList.contains('cart-price')) {
            const row = e.target.closest('tr');
            const qtyInput = row.querySelector('.cart-qty');
            const priceInput = row.querySelector('.cart-price');
            const newQty = parseInt(qtyInput.value) || 1;
            const newPrice = parseFloat(priceInput.value) || 0;

            updateGrandTotal();

            if (e.target.classList.contains('cart-qty')) {
                qtyInput.dataset.lastValue = newQty;
            }
        }
    });

    document.getElementById('submitCart').addEventListener('click', function() {
        const cartItems = [];
        let valid = true;

        document.querySelectorAll('#cartBody tr').forEach(row => {
            const inventoryId = row.dataset.id;
            const quantityInput = row.querySelector('.cart-qty');
            const priceInput = row.querySelector('.cart-price');
            const quantity = parseInt(quantityInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const maxQty = parseInt(quantityInput.max);

            if (quantity > 0) {
                if (quantity > maxQty) {
                    alert(`Quantity for ${row.cells[0].textContent} exceeds available stock (${maxQty})`);
                    valid = false;
                    return;
                }
                cartItems.push({
                    inventoryId: inventoryId,
                    quantity: quantity,
                    price: price
                });
            }
        });

        if (!valid || cartItems.length === 0) {
            if (cartItems.length === 0) alert('Cart is empty! Please add items to proceed.');
            return;
        }

        this.disabled = true;
        this.textContent = 'Processing...';

        fetch('/products/submitCart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    cartItems: cartItems
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('Server error: ' + response.status);
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    cartItems.forEach(item => {
                        const qtyElement = document.querySelector(`.quantity[data-id="${item.inventoryId}"]`);
                        const currentQty = parseInt(qtyElement.textContent.replace('Qty: ', ''));
                        updateCard(item.inventoryId, currentQty - item.quantity);
                    });
                    document.getElementById('cartBody').innerHTML = '';
                    document.getElementById('cartSection').style.display = 'none';
                    alert('Checkout completed successfully! Inventory updated.');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to submit cart: ' + error.message);
            })
            .finally(() => {
                this.disabled = false;
                this.textContent = 'Checkout';
            });
    });

    // Clear Cart
    document.getElementById('clearCart').addEventListener('click', function() {
        if (confirm('Are you sure you want to clear the cart?')) {
            document.getElementById('cartBody').innerHTML = '';
            document.getElementById('grandTotal').textContent = '0.00';
            document.getElementById('cartSection').style.display = 'none';
        }
    });

    // Save as PDF (Professional Receipt)
    const {
        jsPDF
    } = window.jspdf;
    document.getElementById('savePdf').addEventListener('click', function() {
        const doc = new jsPDF();

        // Header
        doc.setFontSize(18);
        doc.text('Store Name POS', 105, 15, {
            align: 'center'
        });
        doc.setFontSize(10);
        doc.text('123 Business Ave, City, ST 12345', 105, 23, {
            align: 'center'
        });
        doc.text(`Date: ${new Date().toLocaleString()}`, 105, 31, {
            align: 'center'
        });
        doc.line(10, 35, 200, 35); // Horizontal line

        // Table Header
        let y = 45;
        doc.setFontSize(12);
        doc.text('Item', 10, y);
        doc.text('Qty', 100, y, {
            align: 'right'
        });
        doc.text('Price', 140, y, {
            align: 'right'
        });
        doc.text('Total', 180, y, {
            align: 'right'
        });
        y += 5;
        doc.line(10, y, 200, y);

        // Table Content
        y += 5;
        document.querySelectorAll('#cartBody tr').forEach(row => {
            const product = row.cells[0].textContent.trim().substring(0, 20); // Truncate long names
            const qty = row.querySelector('.cart-qty').value;
            const price = parseFloat(row.querySelector('.cart-price').value).toFixed(2);
            const total = (qty * price).toFixed(2);

            doc.text(product, 10, y);
            doc.text(qty, 100, y, {
                align: 'right'
            });
            doc.text(`$${price}`, 140, y, {
                align: 'right'
            });
            doc.text(`$${total}`, 180, y, {
                align: 'right'
            });
            y += 10;
        });

        // Footer
        doc.line(10, y, 200, y);
        y += 10;
        const grandTotal = document.getElementById('grandTotal').textContent;
        doc.setFontSize(14);
        doc.text(`Grand Total: $${grandTotal}`, 180, y, {
            align: 'right'
        });
        y += 10;
        doc.setFontSize(10);
        doc.text('Thank you for shopping with us!', 105, y, {
            align: 'center'
        });

        doc.save('pos-receipt.pdf');
    });

    // Print Receipt
    document.getElementById('printCart').addEventListener('click', function() {
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
            <head>
                <title>POS Receipt</title>
                <style>
                    body { font-family: Arial, sans-serif; width: 300px; margin: 10px auto; font-size: 12px; }
                    h4 { text-align: center; margin: 0; font-size: 16px; }
                    p { text-align: center; margin: 5px 0; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { padding: 5px; text-align: right; }
                    th:first-child, td:first-child { text-align: left; }
                    .total { font-weight: bold; font-size: 14px; margin-top: 10px; text-align: right; }
                    hr { border: 0; border-top: 1px dashed #000; margin: 10px 0; }
                </style>
            </head>
            <body>
                <h4>Store Name POS</h4>
                <p>123 Business Ave, City, ST 12345</p>
                <p>Date: ${new Date().toLocaleString()}</p>
                <hr>
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
        `);

        document.querySelectorAll('#cartBody tr').forEach(row => {
            const product = row.cells[0].textContent.trim().substring(0, 15);
            const qty = row.querySelector('.cart-qty').value;
            const price = parseFloat(row.querySelector('.cart-price').value).toFixed(2);
            const total = (qty * price).toFixed(2);
            printWindow.document.write(`
                <tr>
                    <td>${product}</td>
                    <td>${qty}</td>
                    <td>$${price}</td>
                    <td>$${total}</td>
                </tr>
            `);
        });

        const grandTotal = document.getElementById('grandTotal').textContent;
        printWindow.document.write(`
                    </tbody>
                </table>
                <hr>
                <p class="total">Grand Total: $${grandTotal}</p>
                <p>Thank you for shopping with us!</p>
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    });
</script>