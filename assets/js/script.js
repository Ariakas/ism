window.onload = () => {
    let balance_table = $("#balance_table");
    $("#users").addEventListener("change", function () {
        balance_table.innerHTML = "";
        let user_id = this.value;
        if (user_id) {
            this.disabled = true;
            request("ajax/", { op: "get_balance", user_id }, "POST")
                .then(response => {
                    if (response.status === "success") {
                        for (let [key, value] of Object.entries(response.detail)) {
                            balance_table.insertAdjacentHTML("afterbegin",
                                `<tr><td>${key}</td><td>${value}</td></tr>`);
                        }
                    }
                    this.disabled = false;
                });
        }
    })
}