// This script checks for matching passwords

// Variables
var password1 = document.getElementById("password1"); // password
var password2 = document.getElementById("password2"); // confirm password
var passwordtip = document.getElementById("passwordtip"); // password tip

// Function to validate the password
function validatePassword(){
  // Check if the passwords match
  if(password1.value != password2.value) {
    // If they do not match, change the confirm password field to invalid
    password2.setCustomValidity("Passwords do not match");
  } else {
    // But if they do match, change it to valid
	  password2.setCustomValidity('');
  }

  if (password1.value.length < 8) {
    // Password is less than 8 characters
    password1.setCustomValidity("Invalid password");
    passwordtip.classList.add("text-danger");
  } else if (password1.value.search(/[a-z]/) < 0) {
    // Password does not contain a lowercase
    password1.setCustomValidity("Invalid password");
    passwordtip.classList.add("text-danger");
  } else if(password1.value.search(/[A-Z]/) < 0) {
    // Password does not contain an uppercase
    password1.setCustomValidity("Invalid password");
    passwordtip.classList.add("text-danger");
  } else  if (password1.value.search(/[0-9]/) < 0) {
    // Password does not contain a number/digit
    password1.setCustomValidity("Invalid password");
    passwordtip.classList.add("text-danger");
  } else {
    // Password is valid! :)
    password1.setCustomValidity('');
    passwordtip.classList.remove("text-danger");
    passwordtip.classList.add("text-success");
  }
}

// Call the function on key up
password1.onkeyup = validatePassword;
password2.onkeyup = validatePassword;
