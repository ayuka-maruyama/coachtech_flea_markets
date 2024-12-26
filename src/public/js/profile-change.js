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
