/*
Template Name: ZOPA - Food Drop
Author: Web Mahal Web Service
Website: https://webmahal.com/
Contact: webmahal@gmail.com
File: password addon Js File
*/

// password addon
document.getElementById('password-addon').addEventListener('click', function () {
	var passwordInput = document.getElementById("password-input");
	if (passwordInput.type === "password") {
		passwordInput.type = "text";
	} else {
		passwordInput.type = "password";
	}
});
