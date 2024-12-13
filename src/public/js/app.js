document.addEventListener("DOMContentLoaded", function () {
    const itemNameInput = document.getElementById("item-name");
    const itemCards = document.querySelectorAll(".card");

    // 検索フィルタを適用
    function filterItems() {
        const itemNameFilter = itemNameInput
            ? itemNameInput.value.toLowerCase()
            : "";

        // URLクエリパラメータに検索文字列を追加
        const params = new URLSearchParams(window.location.search);
        if (itemNameFilter) {
            params.set("q", itemNameFilter);
        } else {
            params.delete("q");
        }
        const newUrl = `${window.location.pathname}?${params.toString()}`;
        window.history.replaceState(null, "", newUrl);

        itemCards.forEach(function (card) {
            const itemName = card
                .querySelector(".item-name")
                .textContent.toLowerCase();

            const matchesItemName =
                !itemNameFilter || itemName.includes(itemNameFilter);

            card.style.display = matchesItemName ? "" : "none";
        });
    }

    // URLから検索条件を適用
    function applyFilterFromUrl() {
        const params = new URLSearchParams(window.location.search);
        const searchQuery = params.get("q");
        if (searchQuery && itemNameInput) {
            itemNameInput.value = searchQuery;
            filterItems();
        }
    }

    // タブリンクに検索条件を追加
    function updateTabLinks() {
        const tabLinks = document.querySelectorAll(".tab a");
        tabLinks.forEach((link) => {
            const url = new URL(link.href);
            const params = new URLSearchParams(url.search);

            // 現在の検索条件を追加
            const currentSearchQuery = itemNameInput ? itemNameInput.value : "";
            if (currentSearchQuery) {
                params.set("q", currentSearchQuery);
            } else {
                params.delete("q");
            }

            // 更新したURLをリンクに設定
            url.search = params.toString();
            link.href = url.toString();
        });
    }

    if (itemNameInput) {
        itemNameInput.addEventListener("input", function () {
            filterItems();
            updateTabLinks(); // 検索入力の変更時にタブリンクを更新
        });
    }

    // 初期化処理
    applyFilterFromUrl();
    updateTabLinks();
});
