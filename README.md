# woocommerce-qty-buttons-astra
Custom WooCommerce enhancement that injects + and - quantity buttons using JavaScript. Designed for Astra theme compatibility, with full AJAX support and clean integration without overriding core templates.
# WooCommerce Qty Buttons (Astra Compatible)

Enhance the user experience in WooCommerce by adding intuitive **+ and - quantity buttons** to product fields.

This solution uses JavaScript injection, making it fully compatible with the Astra theme and any WooCommerce setup without overriding templates.

---

## 🚀 Features

* ➕ Adds plus and minus buttons to quantity inputs
* ⚡ Works with AJAX (cart updates, fragments, etc.)
* 🎯 Compatible with Astra theme
* 🧩 No template overrides required
* 🔁 Automatically applies to dynamically loaded content
* 🪶 Lightweight and optimized

---

## 📦 Installation

### Option 1: As a Plugin (Recommended)

1. Download or clone this repository
2. Upload the plugin folder to `/wp-content/plugins/`
3. Activate the plugin from the WordPress dashboard

### Option 2: Manual (functions.php)

Copy the code into your child theme’s `functions.php` file.

---

## 🛠️ How It Works

* Injects + and - buttons into `.quantity` fields
* Listens for click events to increase/decrease values
* Respects `min`, `max`, and `step` attributes
* Triggers WooCommerce events to ensure compatibility
* Uses MutationObserver to support dynamic DOM changes

---

## 💡 Use Case

Perfect for improving UX in WooCommerce stores where default quantity inputs are not user-friendly.

---

## 👨‍💻 Author

**Gonzalo Rolon**
Web Developer
Portfolio: https://ggrdev.site

---

## 📄 License

This project is open-source and available under the MIT License.
