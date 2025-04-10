<?php    
    include 'connect.php';
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
        margin: 20px auto;
        background: white;
        padding: 20px;
        box-shadow: 0 0 15px #ccc;
        border-radius: 8px;
    }

    h2 {
        text-align: center;
        font-size: 40px;
        color: #358135;
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

    /* Added styles for the graph container */
    .graph-container {
        width: 80%;
        margin: 0px auto;
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
</style>

<div class="add-logout">
    <a href="addnewstudent.php">Add New Student</a>
    <a href="index.php">Logout</a>
</div>

<div>
    <div class="graph-container">
        <h2>Event Bargraph</h2>
        <canvas id="eventChart"></canvas>
    </div>
</div>

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
            </tr> 
        </thead>  
        <tbody>
            <?php
            $query = "
                SELECT 
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// PHP data passed to JavaScript
var organizations = <?php echo $organizations_json; ?>;
var eventCounts = <?php echo $event_counts_json; ?>;

// Create a bar chart using Chart.js
var ctx = document.getElementById('eventChart').getContext('2d');
var eventChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: organizations, // Organization names on X-axis
        datasets: [{
            label: 'Number of Events',
            data: eventCounts, // Event counts on Y-axis
            backgroundColor: 'rgba(0, 100, 0, 0.8)',
            borderColor: 'rgba(53, 129, 53, 0.2)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1  
                }
            }
        }
    }
});
</script>

<?php include 'footer.php'; ?>
