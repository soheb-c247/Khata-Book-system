$(document).ready(function () {

    function initValidator(formId, rules, messages) {

        function validateField(field, value) {
            const fieldRules = rules[field];
            let error = '';

            if (fieldRules) {
                for (let r of fieldRules) {
                    if (r.rule === 'required' && value.trim() === '') {
                        error = messages.required(r.field);
                        break;
                    }
                    if (r.rule === 'alpha' && !/^[A-Za-z\s]+$/.test(value)) {
                        error = messages.alpha(r.field);
                        break;
                    }
                    if (r.rule === 'numeric' && value !== '' && !/^\d+$/.test(value)) {
                        error = messages.numeric(r.field);
                        break;
                    }
                    if (r.rule === 'digits' && value.length !== r.length) {
                        error = messages.digits(r.field, r.length);
                        break;
                    }
                    if (r.rule === 'regex' && value.trim() !== '' && !r.regex.test(value.trim())) {
                        error = messages.regex(r.field);
                        break;
                    }
                    if (r.rule === 'max' && value.length > r.length) {
                        error = messages.max(r.field, r.length);
                        break;
                    }
                    if (r.rule === 'min' && value.length < r.min) {
                        error = messages.min(r.field, r.min);
                        break;
                    }
                    if (r.rule === 'match' && value !== $(r.matchWith).val()) {
                        error = messages.match(r.field, r.matchWithName);
                        break;
                    }
                    if (r.rule === 'file' && value !== '') {
                        const input = $(`${formId} #${field}`)[0];
                        if (input.files.length > 0) {
                            const file = input.files[0];

                            // Check type
                            if (!r.allowedTypes.includes(file.type)) {
                                error = messages.fileType(r.field, ['JPG', 'JPEG', 'PNG', 'PDF']);
                                break;
                            }

                            // Check size
                            if (file.size > r.maxSize) {
                                error = messages.fileSize(r.field, r.maxSize / (1024*1024));
                                break;
                            }
                        }
                    }
                }
            }

            // Only overwrite error if there's no backend error present
            const backendError = $(`#error-${field}`).data("backend");
            if (!backendError) {
                $(`#error-${field}`).text(error);
            }

            return error === '';
        }

        // Real-time validation (remove backend error only when value changes)
        Object.keys(rules).forEach(field => {
            $(`${formId} #${field}`).on('input', function () {
                const val = $(this).val();

                // Once user types, clear backend marker
                $(`#error-${field}`).removeData("backend");

                validateField(field, val);

                // Special case: update address counter live
                if (field === 'address') {
                    $(`${formId} #address-count`).text(`${val.length} / 200 characters`);
                }
            });
        });

        // ✅ Initialize address counter on load (but don’t clear backend errors)
        const addrVal = $(`${formId} #address`).val() || '';
        $(`${formId} #address-count`).text(`${addrVal.length} / 200 characters`);

        // Final validation before submit
        $(formId).on('submit', function (e) {
            let isValid = true;

            Object.keys(rules).forEach(field => {
                const val = $(`${formId} #${field}`).val() || '';
                if (!validateField(field, val)) {
                    isValid = false;
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    // Central error messages
    const errorMessages = {
        required: field => `${field} is required.`,
        alpha: field => `${field} must contain only letters and spaces.`,
        numeric: field => `${field} must contain only digits.`,
        digits: (field, len) => `${field} must be exactly ${len} digits.`,
        regex: field => `${field} format is invalid.`,
        max: (field, len) => `${field} cannot exceed ${len} characters.`,
        min: (field, min) => `${field} must be at least ${min}.`,
        match: (field, matchWithName) => `${field} must match ${matchWithName}.`,
        fileType: (field, types) => `${field} must be one of: ${types.join(', ')}.`,
        fileSize: (field, sizeMB) => `${field} must be less than ${sizeMB} MB.`
    };

    // Customer form rules
    const customerRules = {
        name: [
            { rule: 'required', field: 'Name' },
            { rule: 'alpha', field: 'Name' }
        ],
        phone: [
            { rule: 'required', field: 'Phone' },
            { rule: 'numeric', field: 'Phone' },
            { rule: 'digits', field: 'Phone', length: 10 },
            { rule: 'regex', field: 'Phone', regex: /^[6-9]\d{9}$/ }
        ],
        address: [
            { rule: 'required', field: 'Address' },
            { rule: 'max', field: 'Address', length: 200 }
        ],
        opening_balance: [
            { rule: 'regex', field: 'Amount', regex: /^(0|[1-9]\d*)(\.\d{1,2})?$/ } 
        ]
    };

    const transactionRules = {
        customer_id: [
            { rule: 'required', field: 'Customer' }
        ],
        type: [
            { rule: 'required', field: 'Type' }
        ],
        amount: [
            { rule: 'required', field: 'Amount' },
            { rule: 'min', field: 'Amount', min: 1 },
            { rule: 'regex', field: 'Amount', regex: /^(0|[1-9]\d*)(\.\d{1,2})?$/ } 
        ],
        date: [
            { rule: 'required', field: 'Date' },
            { rule: 'regex', field: 'Date', regex: /^(202[0-9]|2030)-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/ }
        ],
        notes: [
            { rule: 'max', field: 'Notes', length: 200 }
        ],
        file: [
            { 
                rule: 'file', 
                field: 'File', 
                allowedTypes: ['image/jpeg','image/jpg','image/png','application/pdf'], 
                maxSize: 2 * 1024 * 1024 // 2 MB
            }
        ]
    };
        
    const loginRules = {
        phone: [
            { rule: 'required', field: 'Phone' },
            { rule: 'numeric', field: 'Phone' },
            { rule: 'digits', field: 'Phone', length: 10 },
            { rule: 'regex', field: 'Phone', regex: /^[6-9]\d{9}$/ }
        ],
        password: [
            { rule: 'required', field: 'Password' },
        ],
    };

    const registerRules = {
        name: [
            { rule: 'required', field: 'Name' },
            { rule: 'alpha', field: 'Name' },
            { rule: 'max', field: 'Name', length: 50 }
        ],
        phone: [
            { rule: 'required', field: 'Phone' },
            { rule: 'numeric', field: 'Phone' },
            { rule: 'digits', field: 'Phone', length: 10 },
            { rule: 'regex', field: 'Phone', regex: /^[6-9]\d{9}$/ }
        ],
        email: [
            { rule: 'required', field: 'Email' },
            { rule: 'regex', field: 'Email', regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/ }
        ],
        password: [
            { rule: 'required', field: 'Password' },
            { rule: 'min', field: 'Password', min: 6 }
        ],
        password_confirmation: [
            { rule: 'required', field: 'Confirm Password' },
            { rule: 'match', field: 'Confirm Password', matchWith: '#password', matchWithName: 'Password' }
        ]
    };

    $("[id^=error-]").each(function () {
        if ($(this).text().trim() !== "") {
            $(this).data("backend", true);
        }
    });

    initValidator('#register-form', registerRules, errorMessages);
    initValidator('#login-form', loginRules, errorMessages);
    initValidator('#transaction-form', transactionRules, errorMessages);
    initValidator('#customer-form', customerRules, errorMessages);
});
