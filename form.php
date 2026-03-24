<?php
// Initialize variables
$name = $email = $gender = $comment = $website = "";
$phone = $password = $confirmPassword = "";
$nameErr = $emailErr = $genderErr = $phoneErr = $websiteErr = "";
$passwordErr = $confirmPasswordErr = $termsErr = "";
$submissionCount = 0;

// Helper function to sanitize input
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Process form when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Exercise 5 - Increment submission counter
    $submissionCount = isset($_POST["submission_count"]) ? intval($_POST["submission_count"]) + 1 : 1;
    
    // Validate Name (required)
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
    }
    
    // Validate Email (required and format)
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }
    
    // Exercise 1 - Validate Phone Number (required and format)
    if (empty($_POST["phone"])) {
        $phoneErr = "Phone number is required";
    } else {
        $phone = test_input($_POST["phone"]);
        // Validate phone format: digits, spaces, dashes, optional leading +
        if (!preg_match('/^[+]?[0-9 \-]{7,15}$/', $phone)) {
            $phoneErr = "Invalid phone format";
        }
    }
    
    // Exercise 2 - Validate Website (optional but must be valid if provided)
    if (!empty($_POST["website"])) {
        $website = test_input($_POST["website"]);
        if (!filter_var($website, FILTER_VALIDATE_URL)) {
            $websiteErr = "Invalid URL format";
        }
    }
    
    // Validate Gender (required)
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST["gender"]);
    }
    
    // Exercise 3 - Validate Password (required, min 8 chars, must match)
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = $_POST["password"]; // Don't sanitize password for validation
        if (strlen($password) < 8) {
            $passwordErr = "Password must be at least 8 characters long";
        }
    }
    
    if (empty($_POST["confirm_password"])) {
        $confirmPasswordErr = "Please confirm your password";
    } else {
        $confirmPassword = $_POST["confirm_password"];
        if ($password !== $confirmPassword) {
            $confirmPasswordErr = "Passwords do not match";
        }
    }
    
    // Exercise 4 - Validate Terms and Conditions checkbox
    if (!isset($_POST["terms"])) {
        $termsErr = "You must agree to the terms and conditions";
    }
    
    // Sanitize Comment (optional field)
    if (!empty($_POST["comment"])) {
        $comment = test_input($_POST["comment"]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Form Validation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
            text-align: center;
        }
        .error {
            color: #d32f2f;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        input[type="radio"],
        input[type="checkbox"] {
            margin-right: 5px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .output {
            margin-top: 30px;
            padding: 20px;
            background-color: #e8f5e9;
            border-radius: 4px;
            border-left: 4px solid #4CAF50;
        }
        .output h3 {
            margin-top: 0;
            color: #2e7d32;
        }
        .required {
            color: #d32f2f;
        }
        .submission-count {
            text-align: center;
            color: #666;
            font-style: italic;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>PHP Form Validation Lab</h2>
        
        <?php if ($submissionCount > 0): ?>
            <p class="submission-count">Submission attempt: <?= $submissionCount ?></p>
        <?php endif; ?>
        
        <p><span class="required">*</span> Required field</p>
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            
            <!-- Hidden field for submission counter (Exercise 5) -->
            <input type="hidden" name="submission_count" value="<?= $submissionCount ?>">
            
            <!-- Name Field -->
            <div class="form-group">
                <label>Name: <span class="required">*</span></label>
                <input type="text" name="name" value="<?= $name ?>">
                <span class="error"><?= $nameErr ?></span>
            </div>
            
            <!-- Email Field -->
            <div class="form-group">
                <label>Email: <span class="required">*</span></label>
                <input type="text" name="email" value="<?= $email ?>">
                <span class="error"><?= $emailErr ?></span>
            </div>
            
            <!-- Exercise 1 - Phone Number Field -->
            <div class="form-group">
                <label>Phone Number: <span class="required">*</span></label>
                <input type="text" name="phone" value="<?= $phone ?>" placeholder="+1 234-567-8900">
                <span class="error"><?= $phoneErr ?></span>
            </div>
            
            <!-- Website Field (Exercise 2 - improved validation) -->
            <div class="form-group">
                <label>Website:</label>
                <input type="text" name="website" value="<?= $website ?>" placeholder="https://example.com">
                <span class="error"><?= $websiteErr ?></span>
            </div>
            
            <!-- Exercise 3 - Password Fields -->
            <div class="form-group">
                <label>Password: <span class="required">*</span></label>
                <input type="password" name="password" placeholder="Minimum 8 characters">
                <span class="error"><?= $passwordErr ?></span>
            </div>
            
            <div class="form-group">
                <label>Confirm Password: <span class="required">*</span></label>
                <input type="password" name="confirm_password">
                <span class="error"><?= $confirmPasswordErr ?></span>
            </div>
            
            <!-- Comment Field -->
            <div class="form-group">
                <label>Comment:</label>
                <textarea name="comment" rows="5" cols="40"><?= $comment ?></textarea>
            </div>
            
            <!-- Gender Field -->
            <div class="form-group">
                <label>Gender: <span class="required">*</span></label>
                <input type="radio" name="gender" value="female" <?php if ($gender == "female") echo "checked"; ?>>
                <label style="display: inline; font-weight: normal;">Female</label>
                <input type="radio" name="gender" value="male" <?php if ($gender == "male") echo "checked"; ?>>
                <label style="display: inline; font-weight: normal;">Male</label>
                <input type="radio" name="gender" value="other" <?php if ($gender == "other") echo "checked"; ?>>
                <label style="display: inline; font-weight: normal;">Other</label>
                <br>
                <span class="error"><?= $genderErr ?></span>
            </div>
            
            <!-- Exercise 4 - Terms and Conditions Checkbox -->
            <div class="form-group">
                <input type="checkbox" name="terms" <?php if (isset($_POST["terms"])) echo "checked"; ?>>
                <label style="display: inline; font-weight: normal;">
                    I agree to the terms and conditions <span class="required">*</span>
                </label>
                <br>
                <span class="error"><?= $termsErr ?></span>
            </div>
            
            <input type="submit" name="submit" value="Submit">
        </form>
        
        <?php
        // Display output only if form is submitted and has no errors
        if ($_SERVER["REQUEST_METHOD"] == "POST" && 
            empty($nameErr) && empty($emailErr) && empty($phoneErr) && 
            empty($websiteErr) && empty($genderErr) && empty($passwordErr) && 
            empty($confirmPasswordErr) && empty($termsErr)) {
            echo "<div class='output'>";
            echo "<h3>Your Input:</h3>";
            echo "<p><strong>Name:</strong> " . $name . "</p>";
            echo "<p><strong>Email:</strong> " . $email . "</p>";
            echo "<p><strong>Phone:</strong> " . $phone . "</p>";
            if (!empty($website)) {
                echo "<p><strong>Website:</strong> " . $website . "</p>";
            }
            if (!empty($comment)) {
                echo "<p><strong>Comment:</strong> " . nl2br($comment) . "</p>";
            }
            echo "<p><strong>Gender:</strong> " . $gender . "</p>";
            echo "<p><strong>Terms:</strong> Agreed</p>";
            echo "<p style='color: #2e7d32; font-weight: bold;'>✓ Form submitted successfully!</p>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>
