// Show Modal
function openStatementModal() {
    document.getElementById('statementModal').classList.remove('hidden');
}

// Close Modal
function closeStatementModal() {
    document.getElementById('statementModal').classList.add('hidden');
}

// Handle custom date toggle
document.addEventListener('change', function (e) {
    if (e.target.name === "predefined_range") {
        let customDateFields = document.getElementById('customDateFields');
        if (e.target.value === "custom") {
            customDateFields.classList.remove('hidden');
        } else {
            customDateFields.classList.add('hidden');
        }
    }
});

// Form Submit with validation
document.getElementById('statementForm').addEventListener('submit', function (e) {
    e.preventDefault();

    let range = document.querySelector('input[name="predefined_range"]:checked')?.value;
    let fileType = document.querySelector('input[name="file_type"]:checked')?.value;
    let action = document.querySelector('input[name="action"]:checked')?.value;

    // ✅ Validate custom range
    if (range === "custom") {
        let fromDate = document.querySelector('input[name="from_date"]').value;
        let toDate   = document.querySelector('input[name="to_date"]').value;

        if (!fromDate || !toDate) {
            toastr.error("Please select both From and To dates for custom range.");
            return;
        }

        if (new Date(fromDate) > new Date(toDate)) {
            toastr.error("From Date cannot be later than To Date.");
            return;
        }
    }

    // ✅ Validate file type
    if (!fileType) {
        toastr.error("Please select a file type (PDF, Excel, CSV).");
        return;
    }

    // ✅ Validate action
    if (!action) {
        toastr.error("Please select an action (Download or Email).");
        return;
    }

    // If all good → submit via fetch
    let btn = document.getElementById('generateBtn');
    btn.disabled = true;
    btn.innerText = "Generating...";

    let data = new FormData(this);
    let url = this.getAttribute('action');

    fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value },
        body: data
    })
    .then(res => {
        if (!res.ok) {
            throw new Error("Server returned an error response.");
        }
        return res.blob();
    })
    .then(blob => {
        if (action === "download") {
            let a = document.createElement('a');
            a.href = window.URL.createObjectURL(blob);
            a.download = "statement." + fileType;
            document.body.appendChild(a);
            a.click();
            a.remove();
            toastr.success("Statement downloaded successfully.");
        } else {
            toastr.info("Email functionality not implemented yet.");
        }
        closeStatementModal();
    })
    .catch(err => {
        console.error(err);
        toastr.error("Something went wrong. Please try again.");
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerText = "Generate";
    });
});
