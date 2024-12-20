document.getElementById("image").addEventListener("change", function (event) {
    const file = event.target.files[0];
    const preview = document.getElementById("image-preview");
    const previewArea = document.getElementById("preview-area");

    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = "block"; // 画像を表示
            previewArea.style.backgroundColor = "transparent"; // 背景色を透明に変更
        };

        reader.readAsDataURL(file);
    } else {
        // ファイルが選択されていない場合
        preview.src = ""; // プレビュー画像をクリア
        preview.style.display = "none"; // 非表示にする
        previewArea.style.backgroundColor = "gray"; // 背景色を灰色にする
    }
});

// 郵便番号の自動補正
document
    .getElementById("postal_number")
    .addEventListener("input", function (e) {
        let input = e.target.value;

        // 全角数字を半角数字に変換
        input = input.replace(/[０-９]/g, function (s) {
            return String.fromCharCode(s.charCodeAt(0) - 0xfee0);
        });

        // 入力値を更新
        e.target.value = input;
    });