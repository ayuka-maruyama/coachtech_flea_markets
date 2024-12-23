document.addEventListener("DOMContentLoaded", () => {
    const select = document.querySelector(".payment-select");
    const selectWrapper = document.querySelector(".select");

    // ▼ボタンと select のクリックイベントを統一
    if (selectWrapper && select) {
        selectWrapper.addEventListener("click", () => {
            const event = new MouseEvent("mousedown", {
                bubbles: true,
                cancelable: true,
            });
            select.dispatchEvent(event);
        });
    }

    const paymentSelect = document.getElementById("payment"); // 支払い方法のセレクトボックス
    const purchaseView = document.querySelector(".payment-method"); // 表示エリア

    // 表示を更新する関数
    function updatePurchaseView() {
        const selectedPayment =
            paymentSelect.options[paymentSelect.selectedIndex].text; // 選択した支払い方法

        // 表示エリアのHTMLを動的に更新
        purchaseView.innerHTML = `
            <p class="method-ttl">支払い方法</p>
            <p class="selected">${selectedPayment}</p>
        `;
    }

    // 初期表示
    updatePurchaseView();

    // 支払い方法を変更したときに更新
    paymentSelect.addEventListener("change", updatePurchaseView);
});
