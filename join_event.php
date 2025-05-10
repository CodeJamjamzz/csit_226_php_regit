<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_POST['event_id'];
    $attendee_id = 1; // You can replace this with a session variable if it's dynamic

    // Ensure that the event and attendee are valid
    if (!empty($event_id) && !empty($attendee_id)) {
        // Check if the attendee has already joined the event
        $check_query = "
            SELECT status
            FROM tblTicket
            WHERE event_id = '$event_id' AND attendee_id = '$attendee_id'
        ";
        $check_result = mysqli_query($connection, $check_query);

        if (mysqli_num_rows($check_result) == 0) {
            // Insert the attendee into the tblTicket table with a default status (e.g., 'joined')
            $insert_query = "
                INSERT INTO tblTicket (event_id, attendee_id, status)
                VALUES ('$event_id', '$attendee_id', 'joined')
            ";
            if (mysqli_query($connection, $insert_query)) {
                echo 'success';
            } else {
                echo 'error';
            }
        } else {
            // If already joined, check the status to see if the attendee has already completed the event
            $row = mysqli_fetch_assoc($check_result);
            if ($row['status'] == 'joined') {
                echo 'already_joined';
            } else {
                echo 'error';
            }
        }
    } else {
        echo 'invalid_data';
    }
}
?>
