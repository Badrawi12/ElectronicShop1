<?php
require 'config.php';
include 'includes/header.php';
?>
<style>
/* POS Specific Styles */
.pos-container { display:flex; height: calc(100vh - var(--header-height)); overflow:hidden; }
.pos-main { flex: 1; padding: 24px; overflow-y: auto; display:flex; flex-direction:column; gap:20px; }
.pos-sidebar { width: 400px; background: white; border-left: 1px solid var(--border-color); display:flex; flex-direction:column; }

.search-filter-bar { display:flex; gap:12px; }
.barcode-search { flex:1; display:flex; align-items:center; background:white; border:1px solid var(--border-color); border-radius:8px; padding:12px 16px; position:relative; }
.barcode-search input { border:none; outline:none; background:transparent; margin-left:12px; width:100%; font-size:1rem; }
.filter-btn { background:white; border:1px solid var(--border-color); padding:0 24px; border-radius:8px; display:flex; align-items:center; gap:8px; font-weight:600; cursor:pointer;}

.product-grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap:12px; }
.product-card { background:white; border-radius:12px; border:1px solid var(--border-color); overflow:hidden; display:flex; flex-direction:column; position:relative; box-shadow:var(--card-shadow); transition: transform 0.2s; }
.product-card:hover { transform: translateY(-2px); }
.product-img { width:100%; height:110px; object-fit:cover; background:#f3f4f6;}
.product-badge { position:absolute; top:8px; right:8px; font-size:0.7rem; padding:4px 10px; border-radius:12px; font-weight:700; color:white; }
.badge-instock { background: var(--sidebar-bg); }
.badge-lowstock { background: var(--danger); }
.product-info { padding:8px; flex:1; display:flex; flex-direction:column;}
.product-cat { font-size:0.75rem; color:var(--text-secondary); text-transform:uppercase; font-weight:600; letter-spacing:0.5px;}
.product-name { font-weight:600; font-size:0.75rem; margin:2px 0 6px 0; flex:1;}
.product-bottom { display:flex; justify-content:space-between; align-items:center; }
.product-price { font-size:0.9rem; font-weight:700; color:var(--success); }
.add-btn { background:#285c96; color:white; border:none; border-radius:6px; width:28px; height:28px; display:flex; align-items:center; justify-content:center; cursor:pointer;}
.add-btn:hover { background:#1e40af; }

/* Cart */
.cart-header { padding:12px 16px; border-bottom:1px solid var(--border-color); display:flex; justify-content:space-between; align-items:center; }
.cart-header h3 { margin:0; font-size:1rem; }
.clear-all { color:var(--danger); font-size:0.8rem; font-weight:600; cursor:pointer; background:none; border:none;}
.cart-customer { padding:8px 16px; border-bottom:1px solid var(--border-color); }
.customer-search { display:flex; align-items:center; background:#f9fafb; border:1px solid var(--border-color); border-radius:6px; padding:6px 12px; gap:8px;}
.customer-search input { border:none; outline:none; background:transparent; width:100%; }

.cart-items { flex:1; overflow-y:auto; padding:16px; display:flex; flex-direction:column; gap:12px; }
.cart-item { display:flex; gap:16px; }
.cart-item img { width:60px; height:60px; border-radius:8px; border:1px solid var(--border-color); object-fit:cover; }
.cart-item-details { flex:1; }
.cart-item-title { font-weight:600; font-size:0.95rem; }
.cart-item-variants { font-size:0.8rem; color:var(--text-secondary); }
.qty-controls { display:flex; align-items:center; gap:12px; border:1px solid var(--border-color); border-radius:6px; padding:2px; }
.qty-controls button { background:white; border:none; width:24px; height:24px; cursor:pointer; color:var(--text-secondary); border-radius:4px;}
.qty-controls button:hover { background:#f3f4f6; }
.qty-controls span { font-size:0.85rem; font-weight:600; min-width:16px; text-align:center;}

.cart-summary { padding:12px 16px; border-top:1px solid var(--border-color); background:#f9fafb; display:flex; flex-direction:column; gap:4px; margin-top: auto;}
.summary-row { display:flex; justify-content:space-between; font-size:0.8rem; color:var(--text-secondary); }
.summary-row.total { font-size:1rem; font-weight:700; color:var(--text-primary); margin-top:4px; border-top:1px dashed var(--border-color); padding-top:8px;}
.payment-methods { display:flex; gap:8px; margin-top:8px; }
.pay-btn { flex:1; padding:8px 6px; border:1px solid var(--border-color); background:white; border-radius:6px; display:flex; flex-direction:column; align-items:center; gap:4px; cursor:pointer; font-weight:600; color:var(--text-secondary); font-size:0.65rem; text-transform:uppercase; transition: 0.2s;}
.pay-btn:hover { border-color:var(--sidebar-bg); color:var(--sidebar-bg); }
.pay-btn i { font-size:0.9rem; }
.pay-btn.active { border-color:var(--sidebar-bg); color:var(--sidebar-bg); border-width: 1px; box-shadow: 0 0 0 1px var(--sidebar-bg); }

.secondary-actions { display:flex; gap:8px; margin-top:8px; }
.action-btn-outline { flex:1; padding:8px; border:1px solid var(--border-color); border-radius:6px; cursor:pointer; font-weight:600; display:flex; align-items:center; justify-content:center; gap:6px; color: #0f766e; background: white; font-size: 0.75rem;}
.action-btn-outline:hover { background: #f0fdfa; border-color: #0f766e; }
.action-btn-solid { flex:1; padding:8px; border:none; border-radius:6px; cursor:pointer; font-weight:600; display:flex; align-items:center; justify-content:center; gap:6px; background: #0f766e; color: white; font-size: 0.75rem;}
.action-btn-solid:hover { background: #0d9488; }

.pay-now-btn { background:var(--sidebar-bg); color:white; border:none; width:100%; padding:12px; border-radius:6px; font-weight:700; font-size:0.9rem; margin-top:8px; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; box-shadow: 0 4px 6px -1px rgba(13, 34, 56, 0.4);}
.pay-now-btn:hover { background:var(--sidebar-hover); }
</style>

<div class="pos-container">
    <div class="pos-main">
        <div class="search-filter-bar">
            <div class="barcode-search">
                <i class="fa-solid fa-barcode" style="color:var(--text-secondary); font-size:1.2rem;"></i>
                <input type="text" id="pos-search" placeholder="Scan Barcode or Type Product Name...">
            </div>
            <button class="filter-btn"><i class="fa-solid fa-filter"></i> Filter</button>
        </div>

        <div class="product-grid" id="product-grid">
            <div style="text-align:center; padding: 40px; color: var(--text-secondary); grid-column: 1 / -1;">
                <i class="fa-solid fa-spinner fa-spin" style="font-size: 2rem; margin-bottom:16px;"></i><br>
                Loading products...
            </div>
        </div>
    </div>

    <!-- Right Sidebar Cart -->
    <div class="pos-sidebar">
        <div class="cart-header">
            <h3>Current Order</h3>
            <button class="clear-all" onclick="clearCart()">Clear All</button>
        </div>
        <div class="cart-customer">
            <div class="customer-search">
                <i class="fa-solid fa-user-plus" style="color:var(--text-secondary)"></i>
                <input type="text" placeholder="Add Customer...">
            </div>
        </div>
        
        <div class="cart-items" id="cart-items">
            <!-- JS Will populate -->
        </div>

        <div class="cart-summary">
            <div class="summary-row">
                <span>Subtotal</span>
                <span id="subtotal-val">$0.00</span>
            </div>
            <div class="summary-row">
                <span>Tax (8.5%)</span>
                <span id="tax-val">$0.00</span>
            </div>
            <div class="summary-row total">
                <span>Total</span>
                <span id="total-val">$0.00</span>
            </div>

            <div class="payment-methods">
                <div class="pay-btn active" data-method="CASH"><i class="fa-solid fa-money-bill"></i> Cash</div>
                <div class="pay-btn" data-method="CARD"><i class="fa-regular fa-credit-card"></i> Card</div>
                <div class="pay-btn" data-method="ONLINE"><i class="fa-solid fa-globe"></i> Online</div>
            </div>

            <div class="secondary-actions">
                <button class="action-btn-outline" id="btn-park-sale"><i class="fa-regular fa-circle-pause"></i> Park Sale</button>
                <button class="action-btn-solid" id="btn-print-receipt"><i class="fa-solid fa-print"></i> Print Receipt</button>
            </div>

            <button class="pay-now-btn" id="btn-complete-payment"><i class="fa-regular fa-circle-check"></i> COMPLETE PAYMENT</button>
        </div>
    </div>
</div>
<script src="assets/js/pos.js?v=<?= time() ?>"></script>
</main>
</div>
</body>
</html>
