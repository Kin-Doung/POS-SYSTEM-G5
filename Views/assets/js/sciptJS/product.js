const cartSection = document.getElementById('cartSection');
const cartToggle = document.getElementById('cartToggle');
const closeCart = document.getElementById('closeCart');
const cartCount = document.getElementById('cartCount');

const TELEGRAM_BOT_TOKEN = '7914523767:AAEJxRARlS6nn4Qggt3lw8pOYWKdjAT3FaY';
const TELEGRAM_CHAT_ID = '@engly_system_telegram';

document.addEventListener('DOMContentLoaded', function() {
    loadCartFromLocalStorage();
});

function showCart() {
    cartSection.classList.add('visible');
    document.body.classList.add('cart-visible');
}

function hideCart() {
    cartSection.classList.remove('visible');
    document.body.classList.remove('cart-visible');
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
    return total;
}

function updateCartCount() {
    const itemCount = document.querySelectorAll('#cartBody tr').length;
    const cartCountElement = document.getElementById('cartCount');
    cartCountElement.textContent = itemCount;
    if (itemCount > 0) {
        cartCountElement.classList.add('visible');
    } else {
        cartCountElement.classList.remove('visible');
    }
    updateGrandTotal();
}

function saveCartToLocalStorage() {
    const cartItems = [];
    document.querySelectorAll('#cartBody tr').forEach(row => {
        cartItems.push({
            inventoryId: row.dataset.id,
            productName: row.cells[0].textContent,
            quantity: parseInt(row.querySelector('.cart-qty').value),
            price: parseFloat(row.querySelector('.cart-price').value),
            maxQty: parseInt(row.querySelector('.cart-qty').max)
        });
    });
    localStorage.setItem('cartItems', JSON.stringify(cartItems));
}

function loadCartFromLocalStorage() {
    const savedItems = localStorage.getItem('cartItems');
    if (savedItems) {
        const cartItems = JSON.parse(savedItems);
        cartItems.forEach(item => {
            const row = document.createElement('tr');
            row.dataset.id = item.inventoryId;
            row.innerHTML = `
                <td style="vertical-align: middle;">${item.productName}</td>
                <td><input type="number" class="cart-qty" min="1" max="${item.maxQty}" value="${item.quantity}"></td>
                <td><input type="number" class="cart-price" min="0" step="0.01" value="${item.price.toFixed(2)}"></td>
                <td><span class="remove-item">✖</span></td>
            `;
            document.getElementById('cartBody').appendChild(row);
        });
        updateCartCount();
    }
}

async function sendToTelegram(message) {
    const url = `https://api.telegram.org/bot${TELEGRAM_BOT_TOKEN}/sendMessage`;
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                chat_id: TELEGRAM_CHAT_ID,
                text: message,
                parse_mode: 'Markdown'
            })
        });
        const data = await response.json();
        if (!data.ok) {
            throw new Error(`Telegram API error: ${data.description}`);
        }
        return true;
    } catch (error) {
        console.error('Failed to send message to Telegram:', error);
        return false;
    }
}

cartToggle.addEventListener('click', () => {
    if (cartSection.classList.contains('visible')) {
        hideCart();
    } else {
        showCart();
    }
});

closeCart.addEventListener('click', hideCart);

document.querySelectorAll('.buy').forEach(button => {
    button.addEventListener('click', async function() {
        const card = this.closest('.product-card');
        const inventoryId = card.querySelector('input[name="inventory_id"]').value;
        const productName = card.querySelector('.card-title').textContent.trim();
        const priceText = card.querySelector('.price').textContent;
        const price = parseFloat(priceText.replace('Selling Price: $', '').replace('$', ''));
        const quantity = parseInt(card.querySelector('.quantity').textContent.replace('Qty: ', ''));
        const existingRow = document.querySelector(`#cartBody tr[data-id="${inventoryId}"]`);

        if (existingRow) {
            const qtyInput = existingRow.querySelector('.cart-qty');
            qtyInput.value = parseInt(qtyInput.value) + 1;
            qtyInput.dispatchEvent(new Event('input'));
            updateCartCount();
            saveCartToLocalStorage();
            showCart();
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
                const row = document.createElement('tr');
                row.dataset.id = inventoryId;
                row.innerHTML = `
                    <td style="vertical-align: middle;">${productName}</td>
                    <td><input type="number" class="cart-qty" min="1" max="${quantity}" value="1"></td>
                    <td><input type="number" class="cart-price" min="0" step="0.01" value="${price.toFixed(2)}"></td>
                    <td><span class="remove-item">✖</span></td>
                `;
                document.getElementById('cartBody').appendChild(row);
                updateCartCount();
                saveCartToLocalStorage();
                showCart();
            } else {
                alert(`Error syncing quantity: ${data.message}`);
            }
        } catch (error) {
            console.error('Add to cart failed:', error);
            alert(`Failed to add item: ${error.message}`);
        }
    });
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item')) {
        e.target.closest('tr').remove();
        updateCartCount();
        saveCartToLocalStorage();
    }
});

document.addEventListener('input', function(e) {
    if (e.target.classList.contains('cart-qty') || e.target.classList.contains('cart-price')) {
        updateGrandTotal();
        saveCartToLocalStorage();
    }
});

document.getElementById('submitCart').addEventListener('click', async function() {
    const cartItems = [];
    let valid = true;
    const itemsData = [];

    document.querySelectorAll('#cartBody tr').forEach(row => {
        const inventoryId = row.dataset.id;
        const productName = row.cells[0].textContent.trim();
        const quantityInput = row.querySelector('.cart-qty');
        const priceInput = row.querySelector('.cart-price');
        const quantity = parseInt(quantityInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const maxQty = parseInt(quantityInput.max);

        if (quantity > 0) {
            if (quantity > maxQty) {
                alert(`Quantity for ${productName} exceeds available stock (${maxQty})`);
                valid = false;
                return;
            }
            cartItems.push({
                inventoryId,
                quantity,
                price
            });
            itemsData.push({
                productName,
                quantity,
                price
            });
        }
    });

    if (!valid || cartItems.length === 0) {
        if (cartItems.length === 0) alert('Cart is empty! Please add items to proceed.');
        return;
    }

    this.disabled = true;
    this.textContent = 'Processing...';

    const now = new Date();
    const date = now.toLocaleDateString('en-GB', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    }).split('/').join('/');
    const time = now.toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        second: '2-digit',
        hour12: true
    });

    let telegramMessage = `*Engly_Store Receipt*\n\n`;
    telegramMessage += `Date: ${date}\n`;
    telegramMessage += `Time: ${time}\n\n`;
    telegramMessage += "```\n";
    telegramMessage += `item             Qty    Price       Total\n`;
    telegramMessage += `-------------------------------------\n`;

    itemsData.forEach(item => {
        const productName = item.productName.padEnd(16, ' ').substring(0, 16);
        const quantity = `x${item.quantity}`.padEnd(6, ' ');
        const price = `$${item.price.toFixed(2)}`.padEnd(10, ' ');
        const total = `= $${(item.quantity * item.price).toFixed(2)}`;
        telegramMessage += `${productName} ${quantity} ${price} ${total}\n`;
    });

    telegramMessage += `-------------------------------------\n`;
    const total = updateGrandTotal();
    const totalPrice = `$${total.toFixed(2)}`;
    telegramMessage += `Total: ${' '.repeat(30 - totalPrice.length)}${totalPrice}\n`;
    telegramMessage += `-------------------------------------\n`;
    telegramMessage += "```\n";
    telegramMessage += `✅ Thank you!`;

    const telegramSuccess = await sendToTelegram(telegramMessage);
    if (!telegramSuccess) {
        alert('Failed to send checkout details to Telegram. Proceeding with checkout anyway.');
    }

    fetch('/products/submitCart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                cartItems
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
                localStorage.removeItem('cartItems');
                hideCart();
                updateCartCount();
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
            this.textContent = 'Complete order';
        });
});

document.getElementById('clearCart').addEventListener('click', function() {
    if (confirm('Are you sure you want to clear the cart?')) {
        document.getElementById('cartBody').innerHTML = '';
        document.getElementById('grandTotal').textContent = '0.00';
        document.getElementById('qr-container').style.display = 'none';
        localStorage.removeItem('cartItems');
        updateCartCount();
        hideCart();
        document.getElementById('moreOptionsContainer').style.display = 'block';
    }
});

const { jsPDF } = window.jspdf;

document.getElementById('savePdf').addEventListener('click', function() {
    const doc = new jsPDF();
    doc.setFont("helvetica", "bold");
    doc.setTextColor(0, 0, 0);
    doc.setFontSize(20);
    doc.text('Engly Store', 105, 15, { align: 'center' });
    doc.setFontSize(10);
    doc.setFont("helvetica", "normal");
    doc.text('Phum TropeagnChhuk, Sangkat Tekla, Khan SenSok, Phnom Penh City', 105, 23, { align: 'center' });
    doc.text('Phone: (+855) 97 45 67 89', 105, 28, { align: 'center' });
    doc.text(`Date: ${new Date().toLocaleString()}`, 10, 35);
    doc.text(`Cashier`, 180, 35, { align: 'right' });
    doc.setLineWidth(0.5);
    doc.line(10, 40, 200, 40);
    let y = 50;
    doc.setFontSize(12);
    doc.setFont("helvetica", "bold");
    doc.text('Item', 10, y);
    doc.text('Qty', 100, y, { align: 'right' });
    doc.text('Price', 140, y, { align: 'right' });
    doc.text('Total', 180, y, { align: 'right' });
    y += 5;
    doc.setLineWidth(0.2);
    doc.line(10, y, 200, y);
    y += 5;
    doc.setFont("helvetica", "normal");
    doc.setFontSize(10);
    let totalItems = 0;
    document.querySelectorAll('#cartBody tr').forEach(row => {
        const product = row.cells[0].textContent.trim().substring(0, 20);
        const qty = row.querySelector('.cart-qty').value;
        const price = parseFloat(row.querySelector('.cart-price').value).toFixed(2);
        const total = (qty * price).toFixed(2);
        doc.text(product, 10, y);
        doc.text(qty, 100, y, { align: 'right' });
        doc.text(`$${price}`, 140, y, { align: 'right' });
        doc.text(`$${total}`, 180, y, { align: 'right' });
        totalItems += parseInt(qty);
        y += 8;
    });
    doc.setLineWidth(0.2);
    doc.line(10, y, 200, y);
    y += 10;
    doc.setFontSize(12);
    doc.setFont("helvetica", "bold");
    const grandTotal = document.getElementById('grandTotal').textContent;
    doc.text(`Total Items: ${totalItems}`, 10, y);
    doc.text(`Subtotal: $${grandTotal}`, 180, y, { align: 'right' });
    y += 10;
    doc.setLineWidth(0.5);
    doc.line(10, y, 200, y);
    y += 10;
    doc.setFontSize(12);
    doc.setFont("helvetica", "bold");
    doc.text('THANK YOU FOR SHOPPING WITH US!', 105, y, { align: 'center' });
    y += 8;
    doc.setFontSize(8);
    doc.setFont("helvetica", "normal");
    doc.text('engly@gmail.com', 105, y, { align: 'center' });
    y += 9;
    const qrImage = new Image();
    qrImage.src = '../../views/assets/images/QR-code.png';
    qrImage.crossOrigin = 'Anonymous';
    qrImage.onload = function() {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = qrImage.width;
        canvas.height = qrImage.height;
        ctx.drawImage(qrImage, 0, 0);
        const qrDataURL = canvas.toDataURL('image/png');
        doc.addImage(qrDataURL, 'PNG', 95, y, 20, 20);
        doc.save('pos-receipt.pdf');
    };
    qrImage.onerror = function() {
        console.error('Failed to load QR code image.');
        doc.text('[QR Code Failed to Load]', 105, y, { align: 'center' });
        doc.save('pos-receipt.pdf');
    };
});

document.getElementById('completeCart').addEventListener('click', function() {
    const qrContainer = document.getElementById('qr-container');
    const moreOptionsBtn = document.getElementById('moreOptionsBtn');
    const optionsDropdown = document.getElementById('optionsDropdown');
    const moreOptionsContainer = document.getElementById('moreOptionsContainer');
    qrContainer.style.display = 'block';
    moreOptionsContainer.style.display = 'none';
    qrContainer.innerHTML = `
        <img id="qr-code-img" src="../../views/assets/images/QR-code.png" alt="QR Code" style="width: 80px; height: 80px; margin-bottom: 15px;" />
        <input type="text" id="inputField" placeholder="Enter your details" style="margin-bottom: 15px;" />
        <button class="cart-btn cart-btn-info" id="savePdf2">Save PDF</button>
        <button class="cart-btn cart-btn-primary" id="completeCart2">Complete</button>
        <button class="cart-btn cart-btn-danger" id="clearCart2">Clear</button>
    `;
    document.getElementById('savePdf2').addEventListener('click', function() {
        document.getElementById('savePdf').click();
    });
    document.getElementById('completeCart2').addEventListener('click', function() {
        alert('Order completed!');
        qrContainer.style.display = 'none';
        moreOptionsContainer.style.display = 'block';
    });
    document.getElementById('clearCart2').addEventListener('click', function() {
        document.getElementById('clearCart').click();
    });
    optionsDropdown.classList.remove('visible');
    showCart();
});

const searchInput = document.querySelector('.search-container input');
const productCards = document.querySelectorAll('.product-col');

searchInput.addEventListener('input', function() {
    const searchTerm = this.value.trim().toLowerCase();
    productCards.forEach(card => {
        const productName = card.querySelector('.card-title').textContent.trim().toLowerCase();
        if (productName.includes(searchTerm)) {
            card.classList.remove('hidden');
        } else {
            card.classList.add('hidden');
        }
    });
});

const moreOptionsBtn = document.getElementById('moreOptionsBtn');
const optionsDropdown = document.getElementById('optionsDropdown');

moreOptionsBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    optionsDropdown.classList.toggle('visible');
    moreOptionsBtn.classList.toggle('active');
});

document.addEventListener('click', function(e) {
    if (!moreOptionsBtn.contains(e.target) && !optionsDropdown.contains(e.target)) {
        optionsDropdown.classList.remove('visible');
        moreOptionsBtn.classList.remove('active');
    }
});

// Kebab Menu Functionality
document.querySelectorAll('.kebab-menu').forEach(menu => {
    menu.addEventListener('click', function(e) {
        e.stopPropagation();
        const dropdown = this.nextElementSibling;
        document.querySelectorAll('.dropdown-menu').forEach(d => {
            if (d !== dropdown) d.classList.remove('visible');
        });
        dropdown.classList.toggle('visible');
    });
});

document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', async function(e) {
        e.stopPropagation();
        const inventoryId = this.dataset.id;
        if (confirm('Are you sure you want to delete this product?')) {
            try {
                const response = await fetch('/products/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        inventoryId
                    })
                });
                if (!response.ok) throw new Error(`Server error: ${response.status}`);
                const data = await response.json();
                if (data.success) {
                    this.closest('.product-col').remove();
                    alert('Product deleted successfully!');
                } else {
                    alert(`Error: ${data.message}`);
                }
            } catch (error) {
                console.error('Delete failed:', error);
                alert(`Failed to delete product: ${error.message}`);
            }
        }
        this.closest('.dropdown-menu').classList.remove('visible');
    });
});

document.addEventListener('click', function(e) {
    if (!e.target.closest('.kebab-menu') && !e.target.closest('.dropdown-menu')) {
        document.querySelectorAll('.dropdown-menu').forEach(dropdown => {
            dropdown.classList.remove('visible');
        });
    }
});