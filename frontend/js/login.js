document.addEventListener("DOMContentLoaded", function() {
    let loginForm = document.getElementById("loginForm");
    let loginButton = document.getElementById("loginButton");
    let emailInput = document.getElementById("email");
    let passwordInput = document.getElementById("password");
    let remainingTimeDisplay = document.getElementById("remainingTime");

    const inputLabels = {
        email: 'Email',
        password: 'Password',
    };

    emailInput.addEventListener("input", () => validateInput(emailInput,'email'));
    passwordInput.addEventListener("input", () => validateInput(passwordInput,'password'));

    function validateInput(inputElement, inputName) {
        let inputValue = inputElement.value.trim();
        let inputType = inputElement.getAttribute('type');
        let isValid = true;

        switch (inputType) {
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
                if (inputValue === '') {
                    displayError(inputName, `${inputLabels[inputName]} is required.`);
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

    function displayError(inputName, errorMessage) {
        let inputElement = document.getElementById(inputName);
        let errorElement = document.getElementById(`${inputName}-error`);
        errorElement.textContent = errorMessage;
        errorElement.style.display = 'block';
        inputElement.classList.add('is-invalid');
    }

    function clearError(inputName) {
        let inputElement = document.getElementById(inputName);
        let errorElement = document.getElementById(`${inputName}-error`);
        errorElement.textContent = '';
        errorElement.style.display = 'none';
        inputElement.classList.remove('is-invalid');
        inputElement.classList.add('is-valid');
    }

    function validateEmail(email) {
        // Check if email is in the format 'XYZ@XYZ.XYZ'
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(String(email).toLowerCase());
    }

    loginButton.addEventListener("click",function (event) {
        event.preventDefault();

        if (validateForm()) {
            // Serialize form data into JSON object
            let formData = {};
            $(loginForm).serializeArray().forEach(item => {
                formData[item.name] = item.value;
            });

            // Submit the form using jQuery Ajax
            $.ajax({
                type: "POST",
                url: "../../backend/functions/loadUser.php",
                data: JSON.stringify(formData),
                dataType: "json",
                contentType: "application/json",
                success: function(response) {
                    console.log('Data Sent via Ajax: ', formData)
                    console.log('Data Sent via Ajax: ', response);
                    // Handle successful response from the server
                    if (response.success) {
                        // Redirect based on userRole
                        switch (response.userRole) {
                            case 1:
                                window.location.href = "../pages/booking.html";
                                break;
                            case 2:
                                window.location.href = "admin/dashboard.php";
                                break;
                            default:
                                console.log('Invalid user role: ', response.userRole);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'An unexpected error occurred. Please try again later.',
                                    showConfirmButton: true
                                });
                                break;
                        }

                        // TODO: Add countdown for remaining time and login attempts
                    } else {
                    console.log('Failed to login: ', response.message);
                    // Display error message from the server
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to login due to system error.',
                            showConfirmButton: true
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Display error message if AJAX request fails
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to authenticate. Please try again later.',
                        showConfirmButton: true
                    });
                }
            });
        }
    });

    function validateForm() {
        // Validate all the form fields
        let isValid = true;
        ['email', 'password',].forEach(inputName => {
            let inputElement = document.getElementById(inputName);
            if (!validateInput(inputElement, inputName)) {
                isValid = false;
                console.log('Invalid input: ', inputName);
            }
        });
        return isValid;
    }
});