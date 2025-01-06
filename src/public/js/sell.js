document.addEventListener("DOMContentLoaded", function () {
    // 初期状態で￥マークを設定
    const input = document.getElementById("price");
    if (!input.value) {
        input.value = "￥"; // 初期値として￥0を設定
    }

    // 画像プレビューの表示
    document
        .getElementById("image-input")
        .addEventListener("change", function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    // プレビューの表示
                    const imagePreview =
                        document.getElementById("image-preview");
                    imagePreview.src = e.target.result;
                    document.getElementById(
                        "image-preview-container"
                    ).style.display = "flex";

                    // ボタンを非表示
                    document.getElementById("select-button").style.display =
                        "none";
                };
                reader.readAsDataURL(file);
            }
        });

    // カテゴリーボタンの無効化（クリックでactiveクラスをトグル）
    const categories = document.querySelectorAll(".category");

    categories.forEach((category) => {
        category.addEventListener("click", function () {
            // クリックされたボタンにactiveクラスをトグル（付け外し）
            this.classList.toggle("active");
        });
    });

    // 価格入力にカンマと￥マークを追加
    input.addEventListener("input", function () {
        addYenSymbol();
    });

    function addYenSymbol() {
        let value = input.value;

        // 全角数字を半角に変換
        value = convertToHalfWidth(value.replace(/[^\d０-９]/g, "")); // 数字以外を削除

        if (value) {
            // 数値にカンマ区切りを追加
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            input.value = "￥" + value; // ￥マークを先頭に追加
        } else {
            input.value = "￥"; // 数字が空の場合は￥0を表示
        }
    }

    // 全角数字を半角に変換する関数
    function convertToHalfWidth(value) {
        return value.replace(/[０-９]/g, function (ch) {
            return String.fromCharCode(ch.charCodeAt(0) - 0xfee0); // 全角から半角に変換
        });
    }
});
