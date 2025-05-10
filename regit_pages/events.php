<?php    
    session_start();
    include './crud_functionalities/connect.php';
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
        margin-top: 50px;
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

    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    .action-buttons a {
        display: inline-block;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        padding: 8px 15px;
        border: none;
        font-size: 14px;
    }

    .action-buttons a.delete {
        background-color: #f44336;
    }

    .add-logout {
        text-align: center;
        margin-top: 20px;
    }

    .add-logout a {
        background-color: #358135;
        color: white;
        text-decoration: none;
        padding: 10px 20px;
        margin: 10px;
        font-size: 16px;
        border: none;
    }

    .add-logout {
        display: flex;
        justify-content: flex-start;  
        margin-top: 20px;
        margin-left: 10%;
    }

    .graph-container {
        width: 80%;
        margin: 20px auto;
        padding: 30px 0px;
        box-shadow: 0 0 15px #ccc;
        border-radius: 8px;
        background: white;
    }

    canvas {
        width: 100%;
        max-width: 500px; /* Reduced size of the chart */
        height: 300px; /* Fixed height */
        margin: 0 auto;
    }

    .sponsor-button {
        background-color: #358135;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        padding: 8px 15px;
        border: none;
        font-size: 14px;
        display: inline-block;
        margin-top: 10px;
    }
</style>

<div class="container">
    <h2>List of Events</h2>
    <table>
        <thead>
            <tr> 
                <th>Event</th> 
                <th>Description</th>
                <th>Start_Date</th>                     
                <th>Organization</th>
                <th>Department</th>
                <th>Sponsors</th>
                <th>Organizers</th>
            </tr> 
        </thead>  
        <tbody>
            <?php
            $query = "
                SELECT 
                    e.event_id,
                    e.event_name,
                    e.event_description,
                    e.start_date,
                    o.name AS organization_name,
                    d.department_name
                FROM 
                    tblEvent e
                JOIN 
                    tblSponsor s ON e.event_id = s.event_id
                JOIN 
                    tblOrganization o ON s.organization_id = o.organization_id
                JOIN 
                    tblDepartment d ON o.department_id = d.department_id;";

            $resultset = mysqli_query($connection, $query);

            while($row = mysqli_fetch_assoc($resultset)):
            ?>
            <tr>
                <td><?php echo $row['event_name']; ?></td>
                <td><?php echo $row['event_description']; ?></td>
                <td><?php echo $row['start_date']; ?></td>
                <td><?php echo $row['organization_name']; ?></td>
                <td><?php echo $row['department_name']; ?></td>
                <td>
                    <a href="sponsor.php?event_id=<?php echo $row['event_id']; ?>" class="sponsor-button">Sponsors</a>

                </td>
                
                <td>
                    <a href="organizer.php?event_id=<?php echo $row['event_id']; ?>&organization_name=<?php echo urlencode($row['organization_name']); ?>" class="sponsor-button">Organizers</a>
                </td>


            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php 

$org_query = "
    SELECT 
        o.name AS organization_name, 
        COUNT(e.event_id) AS event_count
    FROM 
        tblEvent e
    JOIN 
        tblSponsor s ON e.event_id = s.event_id
    JOIN 
        tblOrganization o ON s.organization_id = o.organization_id
    GROUP BY 
        o.organization_id;
";

$org_resultset = mysqli_query($connection, $org_query);
$organizations = [];
$event_counts = [];

while ($row = mysqli_fetch_assoc($org_resultset)) {
    $organizations[] = $row['organization_name'];
    $event_counts[] = $row['event_count'];
}

$organizations_json = json_encode($organizations);
$event_counts_json = json_encode($event_counts);
?>

<footer>
    James Ewican | BS Computer Science - 2
</footer>

</body></html>
