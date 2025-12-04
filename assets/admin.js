document.addEventListener("DOMContentLoaded", () => {
    /** IP SEARCH **/
    const ipSearch = document.querySelector("#ipSearch");
    if (ipSearch) {
        ipSearch.addEventListener("keyup", function () {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll("#ipListTable tbody tr");

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });
    }

    /** COUNTRY SEARCH **/
    const countrySearch = document.querySelector("#countrySearch");
    if (countrySearch) {
        countrySearch.addEventListener("keyup", function () {
            const filter = this.value.toLowerCase();
            const items = document.querySelectorAll(".wicb-country-item");

            items.forEach(item => {
                const text = item.innerText.toLowerCase();
                item.style.display = text.includes(filter) ? "flex" : "none";
            });
        });
    }

    /** COUNTRY UNCHECK ALL LOGIC **/
    const uncheckBtn = document.querySelector("#uncheckAllBtn");
    const checkboxes = document.querySelectorAll(".wicb-country-item input[type='checkbox']");
    const form = document.querySelector("#countriesForm");

    if (uncheckBtn && checkboxes.length && form) {

        // Function to enable/disable button
        const toggleButtonState = () => {
            const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
            uncheckBtn.disabled = !anyChecked;
        };

        // Initial state
        toggleButtonState();

        // Checkbox change listener
        checkboxes.forEach(cb => {
            cb.addEventListener("change", () => {
                toggleButtonState();

                // If user checks again after unchecking all, remove hidden field
                const hidden = document.querySelector("#wicb_no_country");
                if (hidden) hidden.remove();
            });
        });

        // Uncheck All button click
        uncheckBtn.addEventListener("click", function () {
            checkboxes.forEach(cb => {
                cb.checked = false;           // visually uncheck
                cb.removeAttribute("checked"); // remove persisted state
            });

            // Create hidden input so empty list gets saved
            let hidden = document.querySelector("#wicb_no_country");
            if (!hidden) {
                hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.name = "wicb_countries[]";
                hidden.id = "wicb_no_country";
                hidden.value = "";
                form.appendChild(hidden);
            }

            toggleButtonState();
        });
    }
});

