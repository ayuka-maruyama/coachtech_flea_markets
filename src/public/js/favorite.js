document.addEventListener("DOMContentLoaded", function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]'); // CSRFトークン
    const favoriteButtons = document.querySelectorAll(".favorite-btn"); // お気に入りボタン

    // ログイン状態を確認
    const isLoggedIn =
        document
            .querySelector('meta[name="is-logged-in"]')
            .getAttribute("content") === "true";

    favoriteButtons.forEach((button) => {
        const svgIcon = button.querySelector("svg path");
        const itemId = button.getAttribute("data-item-id");
        const countElement =
            button.parentElement.querySelector(".favorite-count"); // 件数を表示している要素

        // 初期状態でお気に入りかどうかをチェックして色を設定
        if (button.classList.contains("favorite")) {
            svgIcon.setAttribute("fill", "gold"); // お気に入りの場合はオレンジ色
        } else {
            svgIcon.setAttribute("fill", "gray"); // お気に入りでない場合は灰色
        }

        // ボタンがクリックされたときの処理
        button.addEventListener("click", function () {
            if (!isLoggedIn) {
                // ログインしていない場合、ログイン画面にリダイレクト
                window.location.href = "/login"; // ログイン画面のURLにリダイレクト
                return;
            }

            if (!itemId) {
                console.error("Item ID not found on the button.");
                return;
            }

            // トグル処理（お気に入りを追加または削除）
            fetch(`/favorite/toggle/${itemId}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken.getAttribute("content"),
                },
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(
                            `HTTP error! status: ${response.status}`
                        );
                    }
                    return response.json();
                })
                .then((data) => {
                    // ボタンのクラスをトグルしてお気に入り状態を反映
                    this.classList.toggle("favorite", data.isFavorited);

                    // SVGの色を変更
                    if (data.isFavorited) {
                        svgIcon.setAttribute("fill", "gold"); // お気に入りの場合はオレンジ色
                    } else {
                        svgIcon.setAttribute("fill", "gray"); // お気に入りでない場合は灰色
                    }

                    // お気に入り数を更新
                    console.log(countElement); // countElementがnullでないか確認
                    if (countElement) {
                        countElement.textContent = data.favoriteCount;
                        console.log("Updated count: ", data.favoriteCount); // 件数を更新
                    } else {
                        console.log("countElement is not found!"); // countElementが見つからない場合
                    }
                })
                .catch((error) => console.error("Error:", error));
        });
    });
});
