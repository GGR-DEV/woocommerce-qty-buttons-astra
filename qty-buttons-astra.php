<?php
/**
 * Plugin Name: Qty Buttons Astra WooCommerce
 * Description: Agrega botones + y - a los campos de cantidad en WooCommerce (compatible con Astra).
 * Version: 1.0
 * Author: Gonzalo Rolon
 */
/**
 * Botones + y - para WooCommerce con tema Astra
 * Método: inyección por JavaScript (compatible con cualquier tema)
 * Agregar en: functions.php del tema hijo
 */

add_action( 'wp_footer', 'qty_buttons_astra_script' );
function qty_buttons_astra_script() {
    if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) return;
    ?>
    <style>
        /* Wrapper del campo de cantidad */
        .quantity {
            display: inline-flex !important;
            align-items: center !important;
			gap: 1em;
        }

        /* Botones + y - */
        .qty-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 40px;
            background: #f5f5f5;
            border: 1px solid #ddd;
            font-size: 22px;
            line-height: 1;
            cursor: pointer;
            user-select: none;
            transition: background 0.15s ease, color 0.15s ease;
            padding: 0;
            color: #333;
            flex-shrink: 0;
        }
        .qty-btn:hover {
            background: #e8e8e8;
            color: #000;
        }
        .qty-btn:active {
            background: #d5d5d5;
        }
        .qty-minus {
            border-radius: 4px 0 0 4px;
            order: -1; /* Siempre antes del input */
        }
        .qty-plus {
            border-radius: 0 4px 4px 0;
            order: 1; /* Siempre después del input */
        }

        /* Input de cantidad */
        .quantity input.qty,
        .quantity input[type="number"] {
            text-align: center !important;
            border-radius: 0 !important;
            width: 52px !important;
            height: 40px !important;
            padding: 0 4px !important;
            margin: 0 !important;
            -moz-appearance: textfield !important;
            order: 0;
        }
        .quantity input.qty::-webkit-inner-spin-button,
        .quantity input.qty::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>

    <script>
    (function() {
        'use strict';

        // Inyectar botones en todos los campos de cantidad presentes
        function inyectarBotones() {
            document.querySelectorAll('.quantity').forEach(function(wrapper) {
                // Evitar duplicados
                if (wrapper.querySelector('.qty-btn')) return;

                var input = wrapper.querySelector('input.qty, input[type="number"]');
                if (!input) return;

                // Botón MENOS
                var btnMenos = document.createElement('button');
                btnMenos.type        = 'button';
                btnMenos.className   = 'qty-btn qty-minus';
                btnMenos.setAttribute('aria-label', 'Reducir cantidad');
                btnMenos.innerHTML   = '&#8722;'; // signo −

                // Botón MÁS
                var btnMas = document.createElement('button');
                btnMas.type        = 'button';
                btnMas.className   = 'qty-btn qty-plus';
                btnMas.setAttribute('aria-label', 'Aumentar cantidad');
                btnMas.innerHTML   = '&#43;'; // signo +

                wrapper.insertBefore(btnMenos, input);
                input.after(btnMas);
            });
        }

        // Manejar clicks en los botones
        document.body.addEventListener('click', function(e) {
            var btn = e.target.closest('.qty-btn');
            if (!btn) return;

            var wrapper = btn.closest('.quantity');
            if (!wrapper) return;

            var input = wrapper.querySelector('input.qty, input[type="number"]');
            if (!input) return;

            var current = parseFloat(input.value) || 0;
            var step    = parseFloat(input.getAttribute('step'))  || 1;
            var min     = parseFloat(input.getAttribute('min'));
            var max     = parseFloat(input.getAttribute('max'));

            if (isNaN(min)) min = 0;

            var newVal;
            if (btn.classList.contains('qty-plus')) {
                newVal = (!isNaN(max) && current + step > max) ? max : current + step;
            } else {
                newVal = current - step < min ? min : current - step;
            }

            input.value = newVal;

            // Disparar eventos para que WooCommerce y Astra detecten el cambio
            input.dispatchEvent(new Event('change',  { bubbles: true }));
            input.dispatchEvent(new Event('input',   { bubbles: true }));
            input.dispatchEvent(new Event('keyup',   { bubbles: true }));
        });

        // Ejecutar al cargar la página
        document.addEventListener('DOMContentLoaded', inyectarBotones);

        // Re-ejecutar tras actualizaciones AJAX del carrito (Astra + WooCommerce)
        document.body.addEventListener('updated_cart_totals',      inyectarBotones);
        document.body.addEventListener('wc_fragments_refreshed',   inyectarBotones);
        document.body.addEventListener('wc_fragment_refresh',      inyectarBotones);
        document.body.addEventListener('updated_wc_div',           inyectarBotones);

        // Fallback: observar cambios en el DOM por si Astra renderiza tarde
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(m) {
                m.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) {
                        if (node.classList && node.classList.contains('quantity')) {
                            inyectarBotones();
                        } else if (node.querySelector && node.querySelector('.quantity')) {
                            inyectarBotones();
                        }
                    }
                });
            });
        });
        observer.observe(document.body, { childList: true, subtree: true });

    })();
    </script>
    <?php
}