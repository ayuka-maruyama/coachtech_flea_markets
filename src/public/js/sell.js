document.addEventListener("DOMContentLoaded", function () {
    console.log("スクリプトが正しく読み込まれました");

    // 初期状態で￥マークを設定
    const input = document.getElementById("price");
    if (!input.value) {
        input.value = "￥"; // 初期値として￥を設定
    }

    // 選択されたカテゴリーIDを格納する配列
    let selectedCategories = [];

    // カテゴリーのボタンイベント
    const categories = document.querySelectorAll(".category");
    const categoryInput = document.getElementById("category-input");

    categories.forEach((category) => {
        category.addEventListener("click", function () {
            // クリックされたボタンに active クラスをトグル
            this.classList.toggle("active");

            const categoryId = this.getAttribute("data-category-id");

            if (this.classList.contains("active")) {
                // active クラスが付いた場合は配列に追加
                selectedCategories.push(Number(categoryId));
            } else {
                // active クラスが外れた場合は配列から削除
                selectedCategories = selectedCategories.filter(
                    (id) => id !== Number(categoryId)
                );
            }

            // hidden input に選択されたカテゴリーIDを保存
            categoryInput.value = JSON.stringify(selectedCategories);
            console.log("選択されたカテゴリーID:", selectedCategories);
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
                imagePreviewContainer.style.display = "flex"; // プレビューコンテナを表示
                imageUploadBtn.style.display = "none"; // ボタンを非表示にする

                // 画像が読み込まれたときにサイズを調整
                imagePreview.onload = function () {
                    // 最大幅と最大高さを設定
                    const maxWidth = 590; // 最大幅
                    const maxHeight = 290; // 最大高さ

                    // 現在の画像の幅と高さ
                    const originalWidth = imagePreview.naturalWidth;
                    const originalHeight = imagePreview.naturalHeight;

                    // アスペクト比を計算
                    const aspectRatio = originalWidth / originalHeight;

                    // 新しい幅と高さを設定
                    let newWidth = originalWidth;
                    let newHeight = originalHeight;

                    // 画像が最大幅より大きい場合
                    if (newWidth > maxWidth) {
                        newWidth = maxWidth;
                        newHeight = newWidth / aspectRatio;
                    }

                    // 画像が最大高さより大きい場合
                    if (newHeight > maxHeight) {
                        newHeight = maxHeight;
                        newWidth = newHeight * aspectRatio;
                    }

                    // 新しいサイズを画像に適用
                    imagePreview.style.width = `${newWidth}px`;
                    imagePreview.style.height = `${newHeight}px`;

                    // 画像がコンテナ内に収まるように設定
                    imagePreview.style.objectFit = "contain"; // アスペクト比を維持して収める
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

        // 全角数字を半角に変換し、数字以外を削除
        value = convertToHalfWidth(value.replace(/[^\d０-９]/g, ""));

        if (value) {
            // 数値にカンマ区切りを追加
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            input.value = "￥" + value; // ￥マークを先頭に追加
        } else {
            input.value = "￥"; // 数字が空の場合は￥のみ表示
        }
    }

    // 全角数字を半角に変換する関数
    function convertToHalfWidth(value) {
        return value.replace(/[０-９]/g, function (ch) {
            return String.fromCharCode(ch.charCodeAt(0) - 0xfee0); // 全角から半角に変換
        });
    }
});
