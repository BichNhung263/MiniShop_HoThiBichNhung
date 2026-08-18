/* MiniShop Client - JavaScript */

/**
 * Thêm sản phẩm vào giỏ hàng (placeholder - sẽ được mở rộng sau)
 */
function addToCart(productId) {
    // TODO: Gọi API hoặc redirect đến cart
    const btn = document.querySelector(`[onclick="addToCart(${productId})"]`);
    if (btn) {
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check-lg"></i>';
        btn.classList.replace('btn-primary', 'btn-success');
        setTimeout(() => {
            btn.innerHTML = original;
            btn.classList.replace('btn-success', 'btn-primary');
        }, 1200);
    }
}
