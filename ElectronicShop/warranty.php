<?php
require 'config.php';
include 'includes/header.php';
?>
<div class="page-container">
    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 24px;">
        <div class="page-title" style="margin-bottom:0;">
            <h1>Warranty Management</h1>
            <p>Track product warranties and return claims.</p>
        </div>
        <div style="display:flex; gap:12px;">
            <button class="btn btn-outline"><i class="fa-solid fa-magnifying-glass"></i> Check Serial</button>
            <button class="btn btn-primary"><i class="fa-solid fa-plus"></i> New Claim</button>
        </div>
    </div>
    <div class="card" style="padding:0; overflow:hidden;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Claim #</th>
                    <th>Product</th>
                    <th>Serial Number</th>
                    <th>Purchase Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-weight:600;">#WR-0992</td>
                    <td>MacBook Air M2</td>
                    <td style="font-family:monospace; color:var(--text-secondary);">C02X2349LM</td>
                    <td>Mar 15, 2025</td>
                    <td><span class="badge badge-success">Approved</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</main></div></body></html>
