document.addEventListener("DOMContentLoaded", function () {
    const imageInput = document.getElementById("image");
    if (!imageInput) return;

    imageInput.addEventListener("change", function () {
        const preview = document.getElementById("preview");
        if (!preview) return;
        preview.innerHTML = "";
        const files = this.files;
        for (let i = 0; i < files.length; i++) {
            const img = document.createElement("img");
            img.src = URL.createObjectURL(files[i]);
            img.width = 200;
            img.className = "img-thumbnail me-2 mb-2";
            preview.appendChild(img);
        }
    });
});
