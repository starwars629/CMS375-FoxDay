<!DOCTYPE html>
<html>
    <head>
        <title>Registration Result</title>
        <link rel="stylesheet" href="styles.css">
    </head>

    <body>
        <main>
            <?php
            // Check if form was submitted via POST
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                
                // Initialize error array
                $errors = [];
                
                // Validate Name
                if (empty($_POST["name"])) {
                    $errors[] = "Name is required";
                } else {
                    $name = trim($_POST["name"]); // Remove whitespace
                    $name = htmlspecialchars($name); // Prevent XSS attacks
                    
                    // Check if name contains only letters and spaces
                    if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
                        $errors[] = "Name can only contain letters and spaces";
                    }
                }
                
                // Validate Email
                if (empty($_POST["email"])) {
                    $errors[] = "Email is required";
                } else {
                    $email = trim($_POST["email"]);
                    $email = htmlspecialchars($email);
                    
                    // Check if email is valid format
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $errors[] = "Invalid email format";
                    }
                }
                
                // Validate Event Selection
                if (empty($_POST["event"])) {
                    $errors[] = "Please select an event";
                } else {
                    $event = htmlspecialchars($_POST["event"]);
                    
                    // Optional: Verify the event exists in your events.json
                    $eventsData = json_decode(file_get_contents('events.json'), true);
                    $validEvents = array_column($eventsData, 'title');
                    
                    if (!in_array($event, $validEvents)) {
                        $errors[] = "Invalid event selected";
                    }
                }
                
                // Validate Year (radio button)
                if (empty($_POST["year"])) {
                    $errors[] = "Please select your year";
                } else {
                    $year = $_POST["year"];
                    $validYears = ["freshman", "sophomore", "junior", "senior"];
                    
                    if (!in_array($year, $validYears)) {
                        $errors[] = "Invalid year selected";
                    }
                }
                
                // Clean message
                $message = trim($_POST["message"]);
                $message = htmlspecialchars($message);

                // Validate Checkbox (promise to keep secret)
                if (!isset($_POST["promise"])) {
                    $errors[] = "You must promise to keep the Fox Day surprise secret";
                }
                
                // Check if there are any errors
                if (empty($errors)) {
                    // SUCCESS - No errors, process the data
                    
                    echo '<div class="success">';
                    echo '<h2>✅ Registration Successful!</h2>';
                    echo '<h3>🦊 Welcome to Fox Day! Your adventure is booked!</h3>';
                    echo '<div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0;">';
                    echo '<p><strong>Name:</strong> ' . $name . '</p>';
                    echo '<p><strong>Email:</strong> ' . $email . '</p>';
                    echo '<p><strong>Event:</strong> ' . $event . '</p>';
                    echo '<p><strong>Year:</strong> ' . ucfirst($year) . '</p>';
                    if (!empty($message)) {
                        echo '<p><strong>What you\'re excited about:</strong> ' . $message . '</p>';
                    }
                    echo '</div>';
                    echo '<p style="font-size: 1.1em; margin: 20px 0;">🎉 Thank you for registering! We can\'t wait to see you there!</p>';
                    echo '<div style="margin-top: 25px;">';
                    echo '<a href="events.php">📅 View All Events</a>';
                    echo '<a href="register.html">📝 Register for Another Event</a>';
                    echo '<a href="index.html">🏠 Back to Home</a>';
                    echo '</div>';
                    echo '</div>';

                    // Optional: Save to a file
                    $registration = [
                        'name' => $name,
                        'email' => $email,
                        'event' => $event,
                        'year' => $year,
                        'message' => $message,
                        'timestamp' => date('Y-m-d H:i:s')
                    ];
                    
                    // Append to registrations file
                    file_put_contents('registrations.json', json_encode($registration) . "\n", FILE_APPEND);
                    
                } else {
                    // ERRORS - Display them
                    echo '<div class="error">';
                    echo '<h2>❌ Registration Failed</h2>';
                    echo '<p style="font-size: 1.1em; margin-bottom: 15px;">Please fix the following errors:</p>';
                    echo '<ul>';
                    foreach ($errors as $error) {
                        echo '<li>' . $error . '</li>';
                    }
                    echo '</ul>';
                    echo '<div style="margin-top: 25px;">';
                    echo '<a href="register.html">← Go Back to Form</a>';
                    echo '</div>';
                    echo '</div>';
                }
                
            } else {
                // If someone tries to access this page directly
                echo '<div class="error">';
                echo '<h2>⚠️ Invalid Access</h2>';
                echo '<p>This page can only be accessed by submitting the registration form.</p>';
                echo '<a href="register.html">Go to Registration Form</a>';
                echo '</div>';
            }
            ?>
        </main>
        
        <footer>Happy Fox Day! 🦊🎉</footer>
    </body>
</html>
