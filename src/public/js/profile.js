document.getElementById("image").addEventListener("change", function (event) {
    const file = event.target.files[0];
    const preview = document.getElementById("image-preview");
    const previewArea = document.getElementById("preview-area");

    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = "block";
            previewArea.style.backgroundColor = "transparent"; // 背景色をリセット
        };

        reader.readAsDataURL(file);
    } else {
        // ファイルが選択されていない場合
        preview.src = ""; // プレビュー画像をクリア
        preview.style.display = "none"; // 非表示にする
        previewArea.style.backgroundColor = "gray"; // 背景色を灰色にする
    }
});
