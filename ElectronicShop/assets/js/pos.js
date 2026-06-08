let cart = [];
let ALL_PRODUCTS = [];
let currentPaymentMethod = 'CASH';

document.addEventListener('DOMContentLoaded', () => {
    loadProducts();

    // Bind Search
    document.getElementById('pos-search').addEventListener('input', (e) => {
        renderProducts(e.target.value);
    });

    // Bind Payment Methods
    const payBtns = document.querySelectorAll('.pay-btn');
    payBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            payBtns.forEach(b => b.classList.remove('active'));
            const target = e.currentTarget;
            target.classList.add('active');
            currentPaymentMethod = target.dataset.method;
        });
    });

    // Park Sale
    document.getElementById('btn-park-sale').addEventListener('click', () => {
        if(cart.length === 0) {
            alert("Cart is empty.");
            return;
        }
        localStorage.setItem('parkedSale', JSON.stringify(cart));
        clearCart();
        alert("Sale parked successfully.");
    });

    // Print Receipt
    document.getElementById('btn-print-receipt').addEventListener('click', () => {
        if(cart.length === 0) {
            alert("Cart is empty.");
            return;
        }
        
        let subtotal = 0;
        let itemsHtml = '';
        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            itemsHtml += `
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #f3f4f6;">
                        <div style="font-weight: 600; font-size: 0.85rem; color: #111;">${item.title}</div>
                        <div style="font-size: 0.65rem; color: #6b7280; margin-top: 2px; text-transform: uppercase;">${item.variants}</div>
                    </td>
                    <td style="text-align: center; padding: 10px 0; border-bottom: 1px solid #f3f4f6; font-size: 0.85rem;">${item.quantity}</td>
                    <td style="text-align: right; padding: 10px 0; border-bottom: 1px solid #f3f4f6; font-size: 0.85rem; font-weight: 500; color: #111;">$${itemTotal.toFixed(2)}</td>
                </tr>
            `;
        });

        const taxRate = 0.085;
        const tax = subtotal * taxRate;
        const total = subtotal + tax;

        const receiptId = 'TS-' + Math.floor(Math.random() * 90000 + 10000) + '-01';
        
        // Format Date
        const now = new Date();
        const dateStr = now.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        const timeStr = now.toLocaleTimeString('en-US', { hour12: false });
        
        const printWin = window.open('', '', 'width=400,height=600');
        printWin.document.write(`
            <html>
            <head>
                <style>
                    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
                    body { font-family: 'Inter', sans-serif; margin: 0; padding: 20px; color: #374151; }
                    @media print { body { padding: 0; } }
                </style>
            </head>
            <body>
            <div style="width: 300px; margin: 0 auto;">
                <!-- Header -->
                <div style="text-align: center; margin-bottom: 24px;">
                    <h2 style="margin: 0; font-size: 1.5rem; display: flex; align-items: center; justify-content: center; gap: 8px; color: #0d2238;">
                         ShopTech Pro
                    </h2>
                    <p style="margin: 6px 0 0 0; font-size: 0.65rem; font-weight: 700; letter-spacing: 1.5px; color: #4b5563;">ELECTRONICS & REPAIR HUB</p>
                    <p style="margin: 6px 0 0 0; font-size: 0.75rem; color: #6b7280; line-height: 1.4;">1284 Innovation Blvd, Suite 400<br>Silicon Valley, CA 94043<br>(555) 012-3456</p>
                </div>

                <!-- Meta Details -->
                <div style="font-size: 0.75rem; display: flex; flex-direction: column; gap: 4px; border-bottom: 1px solid #e5e7eb; padding-bottom: 16px; margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between;"><span>Receipt #:</span> <span style="font-weight: 500;">${receiptId}</span></div>
                    <div style="display: flex; justify-content: space-between;"><span>Date:</span> <span style="font-weight: 500;">${dateStr} ${timeStr}</span></div>
                    <div style="display: flex; justify-content: space-between;"><span>Cashier:</span> <span style="font-weight: 500;">Alex Rivera</span></div>
                    <div style="display: flex; justify-content: space-between;"><span>Register:</span> <span style="font-weight: 500;">POS-02</span></div>
                </div>

                <!-- Items Table -->
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 16px;">
                    <thead>
                        <tr>
                            <th style="text-align: left; padding-bottom: 8px; font-size: 0.75rem; color: #6b7280; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Item / SKU</th>
                            <th style="text-align: center; padding-bottom: 8px; font-size: 0.75rem; color: #6b7280; font-weight: 600; border-bottom: 1px solid #e5e7eb; width: 40px;">Qty</th>
                            <th style="text-align: right; padding-bottom: 8px; font-size: 0.75rem; color: #6b7280; font-weight: 600; border-bottom: 1px solid #e5e7eb; width: 60px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${itemsHtml}
                    </tbody>
                </table>

                <!-- Summary -->
                <div style="border-top: 1px dashed #9ca3af; padding-top: 16px; font-size: 0.85rem; display: flex; flex-direction: column; gap: 8px;">
                    <div style="display: flex; justify-content: space-between; color: #4b5563;"><span>Subtotal</span> <span>$${subtotal.toFixed(2)}</span></div>
                    <div style="display: flex; justify-content: space-between; color: #4b5563;"><span>Sales Tax (8.5%)</span> <span>$${tax.toFixed(2)}</span></div>
                    <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 1.15rem; margin-top: 8px; align-items: center;">
                        <span>Grand Total</span> <span style="color: #0d2238;">$${total.toFixed(2)}</span>
                    </div>
                </div>

                <!-- Payment info -->
                <div style="margin-top: 20px; font-size: 0.75rem; font-style: italic; color: #4b5563; line-height: 1.5;">
                    Paid via: ${currentPaymentMethod}<br>
                    Auth Code: ${Math.floor(Math.random() * 900000) + 100000}
                </div>

                <!-- QR / Footer -->
                <div style="text-align: center; margin-top: 32px; border-top: 1px solid #e5e7eb; padding-top: 24px;">
                    <div style="width: 70px; height: 70px; background: #f3f4f6; margin: 0 auto; display: flex; align-items: center; justify-content: center; padding: 4px;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=${receiptId}" style="width: 60px; height: 60px;">
                    </div>
                    <p style="font-size: 0.65rem; margin: 12px 0; letter-spacing: 2px; color: #6b7280;">* ${receiptId} *</p>
                    <p style="font-weight: 700; font-style: italic; font-size: 0.95rem; margin: 16px 0 6px 0; color: #111;">Thank you for your business!</p>
                    <p style="font-size: 0.55rem; color: #6b7280; letter-spacing: 1px; font-weight: 600;">RETURN POLICY: 14 DAYS WITH RECEIPT</p>
                </div>
            </div>
            </body>
            </html>
        `);
        printWin.document.close();
        printWin.focus();
        setTimeout(() => {
            printWin.print();
            printWin.close();
        }, 500); // Small delay to allow font & QR code image to load before printing
    });

    // Complete Payment
    document.getElementById('btn-complete-payment').addEventListener('click', completePayment);
});

async function loadProducts() {
    try {
        const response = await fetch('api/pos_api.php?action=get_products');
        const res = await response.json();
        if (res.status === 'success') {
            ALL_PRODUCTS = res.data;
            renderProducts();
        } else {
            document.getElementById('product-grid').innerHTML = '<div style="color:red; padding: 20px;">Failed to load products.</div>';
        }
    } catch (e) {
        console.error(e);
        document.getElementById('product-grid').innerHTML = '<div style="color:red; padding: 20px;">Network error loading products.</div>';
    }
}

function renderProducts(searchQuery = '') {
    const grid = document.getElementById('product-grid');
    grid.innerHTML = '';
    
    const query = searchQuery.toLowerCase();
    const filtered = ALL_PRODUCTS.filter(p => 
        p.product_name.toLowerCase().includes(query) || 
        p.sku.toLowerCase().includes(query)
    );

    if (filtered.length === 0) {
        grid.innerHTML = '<div style="padding: 20px; color: #666; grid-column: 1/-1; text-align:center;">No products found.</div>';
        return;
    }

    filtered.forEach(p => {
        const stockLevel = parseInt(p.stock_level) || 0;
        const lowStockThresh = parseInt(p.low_stock_threshold) || 5;
        let badgeClass = 'badge-instock';
        let badgeStyle = '';
        let badgeText = `${stockLevel} IN STOCK`;

        if (stockLevel <= 0) {
            badgeClass = 'badge-lowstock';
            badgeText = 'OUT OF STOCK';
        } else if (stockLevel <= lowStockThresh) {
            badgeClass = 'badge-lowstock';
            badgeText = 'LOW STOCK';
        } else {
            // Optional styling mapping based on product to match aesthetics
            if (p.category_name === 'Audio') badgeStyle = 'style="background:#1e3a8a;"';
        }

        const priceFormatted = parseFloat(p.unit_price).toFixed(2);
        // Use placeholder or actual image. Note: images not included, falling back to dummy if empty
        const imageUrl = p.image_url !== 'placeholder.png' ? p.image_url : 'https://placehold.co/400x300/e2e8f0/475569?text=' + encodeURIComponent(p.product_name);

        const card = document.createElement('div');
        card.className = 'product-card';
        card.innerHTML = `
            <span class="product-badge ${badgeClass}" ${badgeStyle}>${badgeText}</span>
            <img class="product-img" src="${imageUrl}" alt="${p.product_name}">
            <div class="product-info">
                <span class="product-cat">${p.category_name || 'UNCATEGORIZED'}</span>
                <h4 class="product-name">${p.product_name}</h4>
                <div class="product-bottom">
                    <span class="product-price">$${priceFormatted}</span>
                    <button class="add-btn" onclick="addToCart(${p.id}, '${p.product_name.replace(/'/g, "\\'")}', ${p.unit_price}, 'SKU: ${p.sku}', '${imageUrl}')" ${stockLevel <= 0 ? 'disabled' : ''}>
                        <i class="fa-solid fa-cart-shopping"></i>
                    </button>
                </div>
            </div>
        `;
        grid.appendChild(card);
    });
}

function addToCart(id, title, price, variants, image) {
    const existing = cart.find(item => item.id === id);
    if (existing) {
        existing.quantity += 1;
    } else {
        cart.push({ id, title, price, variants, image, quantity: 1 });
    }
    updateCartUI();
}

function updateQuantity(id, delta) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    
    item.quantity += delta;
    if (item.quantity <= 0) {
        cart = cart.filter(i => i.id !== id);
    }
    updateCartUI();
}

function clearCart() {
    cart = [];
    updateCartUI();
}

function updateCartUI() {
    const container = document.getElementById('cart-items');
    container.innerHTML = '';
    
    let subtotal = 0;
    
    if (cart.length === 0) {
        container.innerHTML = '<div style="text-align:center; color:#9ca3af; padding: 40px 0;"><i class="fa-solid fa-cart-shopping" style="font-size:2rem; margin-bottom:12px;"></i><p>Cart is Empty</p></div>';
    } else {
        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            
            container.innerHTML += `
                <div class="cart-item">
                    <img src="${item.image}" alt="${item.title}">
                    <div class="cart-item-details">
                        <div class="cart-item-title">${item.title}</div>
                        <div class="cart-item-variants">${item.variants}</div>
                        <div style="font-weight:700; color:var(--text-primary); margin-top:4px;">$${item.price.toFixed(2)}</div>
                    </div>
                    <div class="qty-controls">
                        <button onclick="updateQuantity(${item.id}, -1)"><i class="fa-solid fa-minus"></i></button>
                        <span>${item.quantity}</span>
                        <button onclick="updateQuantity(${item.id}, 1)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            `;
        });
    }

    const taxRate = 0.085;
    const tax = subtotal * taxRate;
    const total = subtotal + tax;

    document.getElementById('subtotal-val').innerText = `$${subtotal.toFixed(2)}`;
    document.getElementById('tax-val').innerText = `$${tax.toFixed(2)}`;
    document.getElementById('total-val').innerText = `$${total.toFixed(2)}`;
}

async function completePayment() {
    if (cart.length === 0) {
        alert("Cannot complete payment, cart is empty.");
        return;
    }

    const subtotalText = document.getElementById('subtotal-val').innerText.replace('$', '');
    const taxText = document.getElementById('tax-val').innerText.replace('$', '');
    const totalText = document.getElementById('total-val').innerText.replace('$', '');

    const payload = {
        action: 'complete_sale',
        cart: cart,
        subtotal: parseFloat(subtotalText),
        tax: parseFloat(taxText),
        total: parseFloat(totalText),
        payment_method: currentPaymentMethod
    };

    try {
        const response = await fetch('api/pos_api.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(payload)
        });
        const res = await response.json();
        
        if (res.status === 'success') {
            alert("Success: " + res.message);
            clearCart();
            // Reload products to get updated stock levels
            loadProducts();
        } else {
            alert("Error: " + res.message);
        }
    } catch (e) {
        console.error(e);
        alert("A network error occurred while processing the payment.");
    }
}

// Initial draw of empty state
updateCartUI();
