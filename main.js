var email = document.getElementById("email");
var password = document.getElementById("password");
var confirmPassword = document.getElementById("confirmPassword");


function isValid() {
    if (email.value.length < 6) {
        alert("Email must be at least 6 characters");
        return false;
    }
    if (password.value.length < 6) {
        alert("Password must be at least 6 characters");
        return false;
    }
    if (confirmPassword.value.length < 6) {
        alert("Confirm Password must be at least 6 characters");
        return false;
    }
    if (email.value === "") {
        alert("Email cannot be empty");
        return false;
    }
    if (password.value === "") {
        alert("Password cannot be empty");
        return false;
    }
    if (confirmPassword.value === "") {
        alert("Confirm Password cannot be empty");
        return false;
    }
    if (password.value !== confirmPassword.value) {
        alert("Password and Confirm Password do not match");
        return false;
    }

    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirmPassword").value;
    if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return false;
    }
    var email = document.getElementById("email").value;
    var re = /^[^\s@]+@[^\s@]+\.[^\s@]{3}$/;
    if (!re.test(email)) {
        alert("Invalid email address.");
        return false;
    }
    return true;
}
/**
 * Calculates total rental cost
 * @param {number} dailyRate - Price per day in dollars
 * @param {number} rentalDays - Number of rental days
 * @param {number} [discount=0] - Optional discount percentage (0-100)
 * @returns {number} Total cost after applying discount
 */
function calculateRentalCost(dailyRate, rentalDays) {
    // Validate inputs
    if (dailyRate <= 0 || rentalDays <= 0) {
        throw new Error("Daily rate and rental days must be positive numbers");
    } else {
        rentalDays = startDate - endDate;
        totalAmount = rentalDays * dailyRate;
    }
    return totalAmount;
}

function ValidateVisa() {
    var card_number = getElementById("cardNumber");
    var cvc = getElementById("cvc");
    var expiry_date = getElementById("expirationDate");

    if (card_number > 16 || card_number < 16) {
        alert("Card number must be 16 digits");
        return false;
    } else if (cvc > 3 || cvc < 3) {
        alert("cvc must be 3 digits")
    } else if (expiry_date === "") {
        alert("expitation date can not be empty")
    }
    return true;

}

// function check_password() {
//     var password = document.getElementById("password").value;
//     var confirmPassword = document.getElementById("confirmPassword").value;
//     if (password !== confirmPassword) {
//         alert("Passwords do not match.");
//         return false;
//     }
//     return true;
// }

// function check_email() {
//     var email = document.getElementById("email").value;
//     var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
//     if (!re.test(email)) {
//         alert("Invalid email address.");
//         return false;
//     }
//     return true;
// }