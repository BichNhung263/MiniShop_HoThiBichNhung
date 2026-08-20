document.querySelectorAll(".btn-add-cart").forEach(button => {
    button.addEventListener("click", function () {
        const productid = this.dataset.productid;
        // Tạo dữ liệu gửi lên Server
        const formData = new FormData();
        formData.append("productid", productid);
        // Gửi AJAX đến route /cart/add
        fetch(BASE_URL + "/cart/add", {
            // Gửi Request bằng phương thức POST
            method: "POST",
            // Dữ liệu gửi lên Server
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hiển thị thông báo
                    alert(data.message);
                    // Cập nhật số lượng trên Header
                    document.querySelector("#cartCount").textContent = data.cartCount;
                }
            })
            .catch(error => {
                console.error("Lỗi:", error);
            });
    });
});

function updateCart(productid, quantity) {
    // Tạo dữ liệu gửi lên Server
    const formData = new FormData();
    // Thêm productid vào formData
    formData.append("productid", productid);
    // Thêm quantity vào formData
    formData.append("quantity", quantity);

    // Gửi AJAX đến CartController::update()
    fetch(BASE_URL + "/cart/update", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error("Lỗi:", error);
        });
}

function removeCart(productid) {
    // Tạo dữ liệu gửi lên Server
    const formData = new FormData();
    // Thêm productid vào formData
    formData.append("productid", productid);

    // Gửi AJAX đến CartController::remove()
    fetch(BASE_URL + "/cart/remove", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            }
        })
        .catch(error => {
            console.error("Lỗi:", error);
        });
}
