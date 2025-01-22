document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("price");
    if (!input.value) {
        input.value = "￥";
    }

    let selectedCategories = [];

    const categories = document.querySelectorAll(".category");
    const categoryInput = document.getElementById("category-input");

    categories.forEach((category) => {
        category.addEventListener("click", function () {
            this.classList.toggle("active");

            const categoryId = this.getAttribute("data-category-id");

            if (this.classList.contains("active")) {
                selectedCategories.push(Number(categoryId));
            } else {
                selectedCategories = selectedCategories.filter(
                    (id) => id !== Number(categoryId)
                );
            }

            categoryInput.value = JSON.stringify(selectedCategories);
        });
    });

    // 画像プレビューの表示とボタン非表示
    const imageInput = document.getElementById("image-input");
    const imagePreview = document.getElementById("image-preview");
    const imagePreviewContainer = document.getElementById(
        "image-preview-container"
    );
    const imageUploadBtn = document.getElementById("image-upload-btn");

    imageInput.addEventListener("change", function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                imagePreviewContainer.style.display = "flex";
                imageUploadBtn.style.display = "none";

                imagePreview.onload = function () {
                    const maxWidth = 590;
                    const maxHeight = 290;

                    const originalWidth = imagePreview.naturalWidth;
                    const originalHeight = imagePreview.naturalHeight;

                    const aspectRatio = originalWidth / originalHeight;

                    let newWidth = originalWidth;
                    let newHeight = originalHeight;

                    if (newWidth > maxWidth) {
                        newWidth = maxWidth;
                        newHeight = newWidth / aspectRatio;
                    }

                    if (newHeight > maxHeight) {
                        newHeight = maxHeight;
                        newWidth = newHeight * aspectRatio;
                    }

                    imagePreview.style.width = `${newWidth}px`;
                    imagePreview.style.height = `${newHeight}px`;

                    imagePreview.style.objectFit = "contain";
                };
            };
            reader.readAsDataURL(file);
        }
    });

    // 価格入力にカンマと￥マークを追加
    input.addEventListener("input", function () {
        addYenSymbol();
    });

    function addYenSymbol() {
        let value = input.value;

        value = convertToHalfWidth(value.replace(/[^\d０-９]/g, ""));

        if (value) {
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            input.value = "￥" + value;
        } else {
            input.value = "￥";
        }
    }

    function convertToHalfWidth(value) {
        return value.replace(/[０-９]/g, function (ch) {
            return String.fromCharCode(ch.charCodeAt(0) - 0xfee0);
        });
    }
});
