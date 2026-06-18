import re

with open('wp-content/plugins/scoopl-core/includes/checkout-campos.php', 'r', encoding='utf-8') as f:
    content = f.read()

replacements = [
    (r'style="font-family:\'Montserrat\', sans-serif;"', ''),
    (r'style="font-family:\'Montserrat\',sans-serif; background: #fff; padding: 20px; border-radius: 15px;"', 'class="scoopl-custom-checkout"'),
    (r'style="margin-bottom:15px;"', 'class="scoopl-field-group"'),
    (r'style="display:block; font-weight:700; margin-bottom:8px;"', 'class="scoopl-label"'),
    (r'style="width:100%; padding:15px; border:1px solid #e0e0e0; border-radius:10px;"', 'class="scoopl-input"'),
    (r'style="display: flex; gap: 15px;"', 'class="scoopl-flex-row"'),
    (r'style="flex: 1; margin-bottom:15px;"', 'class="scoopl-flex-col"'),
    (r'style="font-size:12px; color:#666;"', 'class="scoopl-help-text"'),
    (r'style="margin-top:20px; font-family:\'Montserrat\', sans-serif;"', ''),
    (r'style="display:block; font-size:14px; color:#000000; margin-bottom:10px;"', 'class="scoopl-nota-label"'),
    (r'style="width: 100%; box-sizing: border-box; display: block; padding: 10px; border: 1px solid #e0e0e0; border-radius: 10px; resize: none; font-family: \'Montserrat\', sans-serif;"', 'class="scoopl-textarea"'),
    (r'style="clear:both; margin-top:20px; padding-top:10px; border-top:1px solid #eee;"', 'class="scoopl-order-details"'),
    (r'style="color:#d06d8a;"', 'class="scoopl-order-title"'),
    (r'style="text-align:center; color:#999; padding:20px;"', 'class="scoopl-empty-bag"'),
    (r'style="display: flex; align-items: center; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #f0f0f0;"', ''),
    (r'style="display: flex; align-items: center; gap: 15px;"', 'class="scoopl-item-info"'),
    (r'style="width: 75px; height: 75px; border-radius: 12px; object-fit: contain; background: #FFC2D1;"', 'class="scoopl-item-img"'),
    (r'style="margin: 0; font-size: 16px; font-weight: 600; color: #000;"', 'class="scoopl-item-name"'),
    (r'style="margin: 4px 0 0; font-size: 13px; color: #d06d8a; font-weight: 500;"', 'class="scoopl-item-flavors"'),
    (r'style="display: block; margin-top: 6px; font-size: 12px; color: #999;"', 'class="scoopl-item-qty"'),
    (r'style="text-align: right;"', 'class="scoopl-item-price-container"'),
    (r'style="font-weight: 600; font-size: 16px; color: #1a1a1a;"', 'class="scoopl-item-price"'),
    (r'style="text-align: right; margin-bottom: 15px;"', 'class="scoopl-empty-cart-btn-container"'),
    (r'style="display: inline-block; padding: 8px 16px; background: #FFF0F3; color: #d06d8a; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; border: 1px solid #FFC2D1;"', 'class="scoopl-empty-cart-btn"'),
    (r'style="font-size: 25px; font-weight: 700;"', 'class="scoopl-resumen-title"'),
    (r'style="display:flex; justify-content:space-between; margin: 15px 0; font-size: 16px;"', 'class="scoopl-resumen-row"'),
    (r'style="color: #666;"', 'class="scoopl-resumen-label"'),
    (r'style="margin: 25px 0;"', 'class="scoopl-progress-container"'),
    (r'style="display:flex; justify-content:space-between; margin-bottom: 12px; font-size: 14px; color: #666;"', 'class="scoopl-progress-header"'),
    (r'style="display: grid; grid-template-columns: repeat\(4, 1fr\); gap: 8px;"', 'class="scoopl-progress-bar"'),
    (r'style="display:flex; justify-content:space-between; font-size: 20px; font-weight: 700; border-top: 1px solid #eee; padding-top: 20px;"', 'class="scoopl-resumen-total"'),
    (r'style="font-size: 20px; font-weight: 700;"', 'class="scoopl-checkout-title"'),
    (r'style="display: flex; gap: 10px; margin: 15px 0;"', 'class="scoopl-payment-methods"'),
    (r'style="margin-top:20px;"', 'class="scoopl-payment-details-container"'),
    (r'style="display:block; font-weight:700; font-size:12px; margin-bottom:5px;"', 'class="scoopl-payment-ref-label"'),
    (r'style="width:100%; padding:12px; border-radius:8px; border:1px solid #ddd;"', 'class="scoopl-payment-ref-input"'),
    (r'style="font-size:11px;"', 'class="scoopl-admin-customer-info"'),
    (r'style="background:#e5e5e5; padding:5px; border-radius:5px; font-weight:bold;"', 'class="scoopl-admin-payment-ref"'),
]

for old, new in replacements:
    content = re.sub(old, new, content)

with open('wp-content/plugins/scoopl-core/includes/checkout-campos.php', 'w', encoding='utf-8') as f:
    f.write(content)
