<?php
session_start();
include './crud_functionalities/connect.php';

if (isset($_GET['event_id']) && is_numeric($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
} else {
    echo "<p>Invalid Event ID!</p>";
    exit();
}

if (isset($_GET['organization_name'])) {
    $organization_name = $_GET['organization_name'];
} else {
    echo "<p>Organization name not provided!</p>";
    exit();
}

// Get organization_id from the passed organization name
$org_query = "SELECT organization_id FROM tblOrganization WHERE name = ?";
if ($stmt = mysqli_prepare($connection, $org_query)) {
    mysqli_stmt_bind_param($stmt, 's', $organization_name);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $organization_id);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    
    if (!$organization_id) {
        echo "<p>Organization not found!</p>";
        exit();
    }
} else {
    echo "<p>Error fetching organization: " . mysqli_error($connection) . "</p>";
    exit();
}

// Handle deletion of an organizer
if (isset($_POST['delete_organizer'])) {
    $organizer_id = $_POST['delete_id'];

    // Delete the organizer from the database
    $delete_query = "DELETE FROM tblOrganizer WHERE id = ?";

    if ($stmt = mysqli_prepare($connection, $delete_query)) {
        mysqli_stmt_bind_param($stmt, 'i', $organizer_id);
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to refresh the page and reflect changes
            header("Location: " . $_SERVER['PHP_SELF'] . "?event_id=$event_id&organization_name=$organization_name");
            exit();
        } else {
            echo "<p>Error deleting organizer: " . mysqli_error($connection) . "</p>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<p>Error preparing query: " . mysqli_error($connection) . "</p>";
    }
}

// Handle adding a new organizer
if (isset($_POST['add_organizer'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $position = $_POST['position'];
    $role = $_POST['role'];

    // Insert new organizer into tblOrganizer
    $insert_organizer = "INSERT INTO tblOrganizer (event_id, firstname, lastname, position, role) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($connection, $insert_organizer)) {
        mysqli_stmt_bind_param($stmt, 'issss', $event_id, $firstname, $lastname, $position, $role);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: " . $_SERVER['PHP_SELF'] . "?event_id=$event_id&organization_name=$organization_name");
        exit();
    } else {
        echo "<p>Error inserting organizer: " . mysqli_error($connection) . "</p>";
    }
}

include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Organizers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F0EBCE;
            color: #333;
        }

        .container {
            width: 80%;
            margin-right: auto;
            margin-left: auto;
            margin-bottom: 20px;
            background: white;
            padding: 20px;
            box-shadow: 0 0 15px #ccc;
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            font-size: 40px;
            color: black;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #358135;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .form-container {
            width: 80%;
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin: 20px auto;
        }

        .form-container h3 {
            font-size: 24px;
            color: #358135;
            margin-bottom: 15px;
        }

        .form-container label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        .form-container select,
        .form-container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #aaa;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-container button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #358135;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: auto;
        }

        .form-container button:hover {
            background-color: #2e6c2e;
        }
    </style>
</head>
<body>

<div class="form-container">
    <form method="POST">
        <h3>Add Organizer</h3>

        <!-- Input fields for Organizer details -->
        <label for="firstname">First Name:</label>
        <input type="text" name="firstname" id="firstname" required>

        <label for="lastname">Last Name:</label>
        <input type="text" name="lastname" id="lastname" required>

        <label for="position">Position:</label>
        <input type="text" name="position" id="position" required>

        <label for="role">Role:</label>
        <input type="text" name="role" id="role" required>

        <button type="submit" name="add_organizer">Add Organizer</button>
    </form>
</div>

<div class="container">
    <h2>Organizers for Event</h2>

    <?php
    // Displaying organizers
    $organizer_query = "
        SELECT o.id, o.firstname, o.lastname, o.position, o.role
        FROM tblOrganizer o
        WHERE o.event_id = ?
    ";

    if ($stmt = mysqli_prepare($connection, $organizer_query)) {
        mysqli_stmt_bind_param($stmt, 'i', $event_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0):
    ?>
        <table>
            <thead>
                <tr>
                    <th>Organizer Name</th>
                    <th>Position</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['firstname'] . ' ' . $row['lastname']; ?></td>
                    <td><?php echo $row['position']; ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td>
                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this organizer?');">
                            <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete_organizer">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No organizers found for this event.</p>
    <?php endif; ?>
    <?php
        mysqli_stmt_close($stmt);
    } else {
        echo "<p>Error preparing query: " . mysqli_error($connection) . "</p>";
    }
    ?>
</div>


<footer>
    James Ewican | BS Computer Science - 2
</footer>

</body>
</html>






