document.addEventListener("DOMContentLoaded", () => {
    const select = document.querySelector(".payment-select");
    const selectWrapper = document.querySelector(".select");

    if (selectWrapper && select) {
        selectWrapper.addEventListener("click", () => {
            const event = new MouseEvent("mousedown", {
                bubbles: true,
                cancelable: true,
            });
            select.dispatchEvent(event);
        });
    }

    const paymentSelect = document.getElementById("payment");
    const purchaseView = document.querySelector(".payment-method");

    function updatePurchaseView() {
        const selectedPayment =
            paymentSelect.options[paymentSelect.selectedIndex].text;

        purchaseView.innerHTML = `
            <p class="method-ttl">支払い方法</p>
            <p class="selected">${selectedPayment}</p>
        `;
    }

    updatePurchaseView();

    paymentSelect.addEventListener("change", updatePurchaseView);
});
