function addToCart(productId) {
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

// Chức năng Thêm / Bỏ Yêu Thích Sản Phẩm (Wishlist)
function toggleFavorite(btn, productId) {
    let favorites = JSON.parse(localStorage.getItem('minishop_favorites') || '[]');
    const icon = btn.querySelector('i');
    
    if (favorites.includes(productId)) {
        // Đã yêu thích -> Bỏ yêu thích
        favorites = favorites.filter(id => id !== productId);
        icon.className = 'bi bi-heart';
        btn.classList.remove('btn-danger', 'text-white');
        btn.classList.add('btn-outline-danger');
        alert('Đã xóa sản phẩm khỏi danh sách yêu thích!');
    } else {
        // Chưa yêu thích -> Thêm vào yêu thích
        favorites.push(productId);
        icon.className = 'bi bi-heart-fill';
        btn.classList.remove('btn-outline-danger');
        btn.classList.add('btn-danger', 'text-white');
        alert('Đã thêm sản phẩm vào danh sách yêu thích!');
    }
    localStorage.setItem('minishop_favorites', JSON.stringify(favorites));
}

// Khôi phục trạng thái nút Yêu Thích khi tải trang
document.addEventListener('DOMContentLoaded', () => {
    const favorites = JSON.parse(localStorage.getItem('minishop_favorites') || '[]');
    document.querySelectorAll('.btn-favorite').forEach(btn => {
        const id = parseInt(btn.dataset.productid);
        if (favorites.includes(id)) {
            const icon = btn.querySelector('i');
            if (icon) icon.className = 'bi bi-heart-fill';
            btn.classList.remove('btn-outline-danger');
            btn.classList.add('btn-danger', 'text-white');
        }
    });
});
