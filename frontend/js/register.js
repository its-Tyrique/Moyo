document.addEventListener("DOMContentLoaded", function() {
    // Retrieve form elements
    const registerForm = document.getElementById("registerForm");
    let registerButton = document.getElementById("registerButton");
    let titleInput = document.getElementById("title");
    let firstNameInput = document.getElementById("firstName");
    let lastNameInput = document.getElementById("lastName");
    let idNumberInput = document.getElementById("idNumber");
    let phoneInput = document.getElementById("phone");
    let emailInput = document.getElementById("email");
    let passwordInput = document.getElementById("password");
    let confirmPasswordInput = document.getElementById("confirmPassword");

    const inputLabels = {
        firstName: 'First name',
        lastName: 'Last name',
        email: 'Email',
        password: 'Password',
        confirmPassword: 'Confirm password',
        idNumber: 'ID number',
        phone: 'Cellphone number',
    };

    // Add event listeners to input fields for real-time validation
    firstNameInput.addEventListener("input", () => validateInput(firstNameInput, 'firstName'));
    titleInput.addEventListener("input", () => validateInput(titleInput, 'title'));
    idNumberInput.addEventListener("input", () => validateInput(idNumberInput, 'idNumber'));
    phoneInput.addEventListener("input", () => validateInput(phoneInput, 'phone'));
    lastNameInput.addEventListener("input", () => validateInput(lastNameInput, 'lastName'));
    emailInput.addEventListener("input", () => validateInput(emailInput, 'email'));
    passwordInput.addEventListener("input", () => validateInput(passwordInput, 'password'));
    confirmPasswordInput.addEventListener("input", () => validateInput(confirmPasswordInput, 'confirmPassword'));

    function validateInput(inputElement, inputName) {
        let inputValue = inputElement.value.trim();
        let inputType = inputElement.getAttribute('type');
        let isValid = true;

        // TODO: Add title validation

        switch (inputType) {
            case 'text':
                if (inputValue === '') {
                    displayError(inputName, `${inputLabels[inputName]} is required.`);
                    isValid = false;
                } else if (inputName === 'idNumber') {
                    const validationResult = validateIdNumber(inputValue);
                    if (!validationResult.valid) {
                        displayError(inputName, `Invalid ${inputLabels[inputName]} format.`);
                        isValid = false;
                    } else {
                        clearError(inputName);
                        // Store additional data in hidden fields
                        document.getElementById("gender").value = validationResult.gender;
                        console.log(validationResult.dateOfBirth);
                        document.getElementById("dateOfBirth").value = validationResult.dateOfBirth;
                    }
                } else {
                    clearError(inputName);
                }
                break;
            case 'email':
                if (inputValue === '') {
                    displayError(inputName, `${inputLabels[inputName]} is required.`);
                    isValid = false;
                } else if (!validateEmail(inputValue)) {
                    displayError(inputName, `Invalid ${inputLabels[inputName]} format.`);
                    isValid = false;
                } else {
                    clearError(inputName);
                }
                break;
            case 'password':
                let passwordValidationResult = validatePasswordStrength(inputValue);
                if (inputValue === '') {
                    displayError(inputName, `${inputLabels[inputName]} is required.`);
                    isValid = false;
                } else if (!passwordValidationResult.valid) {
                    displayError(inputName, passwordValidationResult.message);
                    isValid = false;
                } else {
                    clearError(inputName);
                }

                if (inputName === 'confirmPassword') {
                    if (inputValue === '' ) {
                        displayError(inputName, `${inputLabels[inputName]} is required.`);
                        isValid = false;
                    } else if (inputValue !== passwordInput.value.trim()) {
                        displayError(inputName, 'Passwords do not match.');
                        isValid = false;
                    } else {
                        clearError(inputName);
                    }
                }
                break;
            case 'tel':
                if (inputValue === '') {
                    displayError(inputName, `${inputLabels[inputName]} is required.`);
                    isValid = false;
                } else if (!validatePhoneNumber(inputValue)) {
                    displayError(inputName, `Invalid ${inputLabels[inputName]} format.`);
                    isValid = false;
                } else {
                    clearError(inputName);
                }
                break;
            default:
                clearError(inputName);
        }
        return isValid;
    }

    function validateIdNumber(idNumber) {
        // Check if the ID number is exactly 13 digits
        if (!/^\d{13}$/.test(idNumber)) {
            return { valid: false };
        }

        // Extract and validate the date of birth
        const year = parseInt(idNumber.substring(0, 2), 10);
        const month = parseInt(idNumber.substring(2, 4), 10) - 1; // Months are 0-11 in JavaScript
        const day = parseInt(idNumber.substring(4, 6), 10);

        // Determine century (ID numbers issued since 2000)
        const currentYear = new Date().getFullYear() % 100;
        const century = year <= currentYear ? 2000 : 1900;

        const dateOfBirth = new Date(Date.UTC(century + year, month, day));

        // Check if the date is valid
        if (dateOfBirth.getFullYear() !== century + year || dateOfBirth.getMonth() !== month || dateOfBirth.getDate() !== day) {
            return { valid: false };
        }

        // Format the date of birth in "YYYY-MM-DD" format
        const formattedDateOfBirth = dateOfBirth.toISOString().split('T')[0];

        // Extract the gender (sequence number)
        const genderSequence = parseInt(idNumber.substring(6, 10), 10);
        const gender = genderSequence >= 5000 ? 'Male' : 'Female';

        // Citizenship (11th digit)
        const citizenship = parseInt(idNumber.charAt(10), 10);
        if (citizenship !== 0 && citizenship !== 1) {
            return { valid: false };
        }

        // TODO: Add Luhn checksum validation

        // The ID number is valid
        return {
            valid: true,
            gender: gender,
            dateOfBirth: formattedDateOfBirth,
        };
    }

    function validatePhoneNumber(phoneNumber) {
        // TODO: Add removal of spaces between eg: 012 345 6789 -> 0123456789
        // Remove all spaces
        phoneNumber = phoneNumber.replace(/\s/g, '');

        // Check if the phone number is in the format '0XXXXXXXXX' or '+27XXXXXXXXX'
        const phoneRegex = /^(0|\+27)[1-9][0-9]{8}$/;
        return phoneRegex.test(phoneNumber);
    }

    function validateEmail(email) {
        // Check if email is in the format 'XYZ@XYZ.XYZ'
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(String(email).toLowerCase());
    }

    function validatePasswordStrength(password) {
        let unmetRequirements = [];

        // Minimum six characters
        if (password.length < 6) {
            unmetRequirements.push('Password must be at least 6 characters.');
        }

        // At least one uppercase letter
        if (!/[A-Z]/.test(password)) {
            unmetRequirements.push('Password must include at least one uppercase letter.');
        }

        // At least one lowercase letter
        if (!/[a-z]/.test(password)) {
            unmetRequirements.push('Password must include at least one lowercase letter.');
        }

        // At least one number
        if (!/[0-9]/.test(password)) {
            unmetRequirements.push('Password must include at least one number.');
        }

        // At least one special character
        if (!/[@$!%*?&#^()]/.test(password)) {
            unmetRequirements.push('Password must include at least one special character.');
        }

        // If all requirements are met
        if (unmetRequirements.length === 0) {
            return { valid: true, message: '' };
        } else {
            return { valid: false, message: unmetRequirements.join(' ') };
        }
    }

    function displayError(inputName, errorMessage) {
        let inputElement = document.getElementById(inputName);
        let errorElement = document.getElementById(`${inputName}-error`);
        errorElement.textContent = errorMessage;
        errorElement.style.display = 'block';
        inputElement.classList.add('border-danger');
    }

    function clearError(inputName) {
        let inputElement = document.getElementById(inputName);
        let errorElement = document.getElementById(`${inputName}-error`);
        errorElement.textContent = '';
        errorElement.style.display = 'none';
        inputElement.classList.remove('border-danger');
        inputElement.classList.add('border-success');
    }

    registerButton.addEventListener("click", function(event) {
        event.preventDefault();

        if (validateForm()) {
            // Serialize form data into JSON object
            let formData = {};
            $(registerForm).serializeArray().forEach(item => {
                formData[item.name] = item.value;
            });
            console.log('Form Data: ', formData);

            // Submit the form using jQuery Ajax
            $.ajax({
                type: "POST",
                url: "../../backend/functions/createUser.php",
                data: JSON.stringify(formData),
                dataType: "json",
                contentType: "application/json",
                success: function(response) {
                    console.log(response);
                    // Display SweetAlert to confirm successful register
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Register successful!',
                        showConfirmButton: true,
                    }).then(function() {
                        // Redirect to another page
                        // window.location.href = "login.html";
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle error
                    console.log(textStatus, errorThrown);
                    // Display SweetAlert to show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to sign up.',
                        showConfirmButton: true
                    });
                }
            });
        }
    });

    function validateForm() {
        // Validate all the form fields
        let isValid = true;
        ['firstName', 'lastName', 'title', 'idNumber', 'phone', 'email', 'password', 'confirmPassword'].forEach(inputName => {
            let inputElement = document.getElementById(inputName);
            if (!validateInput(inputElement, inputName)) {
                isValid = false;
                console.log('Invalid input: ', inputName);
            }
        });
        return isValid;
    }
});
