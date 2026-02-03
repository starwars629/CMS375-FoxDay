<!DOCTYPE html>
<html> 
    <head>
        <title>Fox Day Events</title>
        <link rel="stylesheet" href="styles.css">
    </head>

    <body>
        <main>
            <h1>🦊 Fox Day Events</h1>
            <p style="text-align: center; font-size: 1.1em; margin-bottom: 30px;">
                Events are listed below. Choose your adventure!
            </p>
            
            <?php        
                $jsonData = file_get_contents("events.json");
                $events = json_decode($jsonData);

                // Bubble sort to order events by time
                for ($c = 0; $c < count($events)-1; $c++) {
                    for ($i = 0; $i < count($events)-1-$c; $i++) {
                        $event1 = $events[$i]->time;
                        $parts1 = explode(":", str_replace(["am", "pm"], "", $event1));
                        $hour1 = substr($event1, -2) === "pm" ? $parts1[0]+12 : $parts1[0];
                        if ($parts1[0] == 12) $hour1 -= 12;

                        $event2 = $events[$i+1]->time;
                        $parts2 = explode(":", str_replace(["am", "pm"], "", $event2));
                        $hour2 = substr($event2, -2) === "pm" ? $parts2[0]+12 : $parts2[0];
                        if ($parts2[0] == 12) $hour2 -= 12;

                        if ($hour1 > $hour2 or ($hour1 == $hour2 and $parts1[1] > $parts2[1])) {
                            $temp = $events[$i];
                            $events[$i] = $events[$i+1];
                            $events[$i+1] = $temp;
                        }
                    }
                }
                
                // Display events in styled cards
                foreach ($events as $event) {
                    echo '<div class="event">';
                    echo '<h3>🎉 ' . htmlspecialchars($event->title) . '</h3>';
                    echo '<p><strong>🕐 Time:</strong> ' . htmlspecialchars($event->time) . '</p>';
                    echo '<p><strong>📍 Location:</strong> ' . htmlspecialchars($event->location) . '</p>';
                    echo '<p><strong>ℹ️ Description:</strong> ' . htmlspecialchars($event->description) . '</p>';
                    echo '</div>';
                }
            ?>

            <div style="text-align: center; margin-top: 30px;">
                <a href="register.html">Register for an Event!</a>
                <a href="index.html">Back to Home</a>
            </div>
        </main>
        
        <footer>Happy Fox Day! 🦊🎉</footer>
    </body>
</html>