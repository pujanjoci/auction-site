<?php
$error = $_GET["error"];

if ($error === "useremailerror") {
    echo '<p class="error-message">Username or Email already exists.</p>';
} elseif ($error === "registrationerror") {
    echo '<p class="error-message">Verification code has been already sent.</p>';
    echo '<p class="error-message"><a href="verify.html">Verify Now</a></p>';
} else {
    // Handle other cases or unknown errors
    echo '<p class="error-message">An error occurred.</p>';
}
