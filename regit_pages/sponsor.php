<?php    
session_start();
include './crud_functionalities/connect.php';

if (isset($_POST['delete_sponsor'])) {
    $organization_id = $_POST['delete_id'];

    if (isset($_GET['event_id']) && is_numeric($_GET['event_id'])) {
        $event_id = $_GET['event_id'];

        $delete_query = "DELETE FROM tblSponsor_Orgs WHERE event_id = ? AND organization_id = ?";

        if ($stmt = mysqli_prepare($connection, $delete_query)) {
            mysqli_stmt_bind_param($stmt, 'ii', $event_id, $organization_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // Redirect to avoid resubmission and refresh the table
            header("Location: " . $_SERVER['PHP_SELF'] . "?event_id=$event_id");
            exit();
        } else {
            echo "<p>Error deleting sponsor: " . mysqli_error($connection) . "</p>";
        }
    }
}


if (isset($_POST['add_sponsor'])) {
    $organization_id = $_POST['organization_id'];
    $contribution = $_POST['contribution'];
    $cost = $_POST['cost'];

    if (isset($_GET['event_id']) && is_numeric($_GET['event_id'])) {
        $event_id = $_GET['event_id'];

        $insert_query = "INSERT INTO tblSponsor_Orgs (event_id, organization_id, contribution, cost) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($connection, $insert_query)) {
            mysqli_stmt_bind_param($stmt, 'iiss', $event_id, $organization_id, $contribution, $cost);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // Prevent resubmission on refresh
            header("Location: " . $_SERVER['PHP_SELF'] . "?event_id=$event_id");
            exit();
        } else {
            echo "<p>Error inserting sponsor: " . mysqli_error($connection) . "</p>";
        }
    }
}

include 'header.php';
?>

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
            margin-top: 30px;
            margin-left: auto;      
            margin-right: auto;
            margin-bottom: 30px;
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
            margin-right: auto;
            margin-left: auto;
            margin-top: 40px;
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

    <!-- Sponsor Form -->
    <div class="form-container">
        <form method="POST">
            <h3>Add Sponsor</h3>

            <label for="organization_id">Organization:</label>
            <select name="organization_id" id="organization_id" required>
                <option value="">-- Select Organization --</option>
                <?php
                    $org_query = "SELECT organization_id, name FROM tblOrganization";
                    $org_result = mysqli_query($connection, $org_query);
                    while ($org = mysqli_fetch_assoc($org_result)) {
                        echo "<option value='{$org['organization_id']}'>{$org['name']}</option>";
                    }
                ?>
            </select>

            <label for="contribution">Contribution:</label>
            <input type="text" name="contribution" id="contribution" required>

            <label for="cost">Cost:</label>
            <input type="text" name="cost" id="cost" required>

            <button type="submit" name="add_sponsor">Add Sponsor</button>
        </form>
    </div>



    <div class="container">
        <h2>Sponsors for Event</h2>
    
    <?php
    // Get the event_id from the URL
    if(isset($_GET['event_id']) && is_numeric($_GET['event_id'])){
        $event_id = $_GET['event_id'];

        // Query to get sponsors for the specific event, join with tblOrganization to get the sponsor organization name
        $sponsor_query = "
            SELECT 
                o.name, 
                s.contribution, 
                s.cost,
                s.organization_id
            FROM 
                tblSponsor_Orgs s
            JOIN 
                tblOrganization o ON s.organization_id = o.organization_id
            WHERE 
                s.event_id = ?";

        // Use prepared statements to avoid SQL injection
        if ($stmt = mysqli_prepare($connection, $sponsor_query)) {
            mysqli_stmt_bind_param($stmt, 'i', $event_id);
            mysqli_stmt_execute($stmt);
            $sponsor_resultset = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($sponsor_resultset) > 0):
    ?>
                <table>
                    <thead>
                        <tr>
                            <th>Sponsor Organization</th>
                            <th>Contribution</th>
                            <th>Cost</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($sponsor_row = mysqli_fetch_assoc($sponsor_resultset)): ?>
                        <tr>
                            <td><?php echo $sponsor_row['name']; ?></td>
                            <td><?php echo $sponsor_row['contribution']; ?></td>
                            <td><?php echo $sponsor_row['cost']; ?></td>
                            <td>
                                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this sponsor?');">
                                    <input type="hidden" name="delete_id" value="<?php echo $sponsor_row['organization_id']; ?>">
                                    <button type="submit" name="delete_sponsor">Delete</button>
                                </form>
                            </td>
                        </tr>

                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No sponsors found for this event.</p>
            <?php endif; ?>
            <?php
            mysqli_stmt_close($stmt);
        } else {
            echo "<p>Error preparing query: " . mysqli_error($connection) . "</p>";
        }
    } else {
        echo "<p>Invalid Event ID!</p>";
    }
    ?>
</div>

<footer>
    James Ewican | BS Computer Science - 2
</footer>

</body>
</html>