document.getElementById("image").addEventListener("change", function (event) {
    const file = event.target.files[0];
    const preview = document.getElementById("image-preview");
    const previewArea = document.getElementById("preview-area");

    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = "block";
            previewArea.style.backgroundColor = "transparent";
        };

        reader.readAsDataURL(file);
    } else {
        preview.src = preview.dataset.default;
        preview.style.display = "block";
        previewArea.style.backgroundColor = "transparent";
    }
});

// 郵便番号の自動補正
document
    .getElementById("postal_number")
    .addEventListener("input", function (e) {
        let input = e.target.value;

        input = input.replace(/[０-９]/g, function (s) {
            return String.fromCharCode(s.charCodeAt(0) - 0xfee0);
        });

        input = input.replace(/[ー―‐－]/g, "-");

        e.target.value = input;
    });
