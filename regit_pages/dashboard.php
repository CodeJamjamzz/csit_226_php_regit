<?php    
    include './crud_functionalities/connect.php';
    include './crud_functionalities/readrecords.php';   
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
        margin: 30px auto;
        background: white;
        padding: 20px;
        box-shadow: 0 0 15px #ccc;
        border-radius: 8px;
    }

    h2 {
        text-align: center;
        font-size: 40px;
        color: #358135;
        margin-bottom: 30px;
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
        background-color: #4CAF50;
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
        background-color: #4CAF50;
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
</style>

<div class="add-logout">
    <a href="addnewstudent.php">Add New Student</a>
    <a href="index.php">Logout</a>
</div>

<div class="container">
    <h2>List of Students</h2>

    <table>
        <thead>
            <tr> 
                <th>ID Number</th> 
                <th>Firstname</th> 
                <th>Lastname</th>
                <th>Program</th>                     
                <th>Action</th>
            </tr> 
        </thead>  
        <tbody>
            <?php
                while($row = $resultset->fetch_assoc()):
                    $id = $row['uid'];
            ?>
            <tr>
                <td><?php echo $id ?></td>
                <td><?php echo $row['firstname'] ?></td>
                <td><?php echo $row['lastname'] ?></td>
                <td><?php echo $row['program'] ?></td> 
                <td class="action-buttons">
                    <a href="update.php">UPDATE</a>
                    <a href="delete.php" class="delete">DELETE</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>         
    </table>


</div>

<footer>
    James Ewican | BS Computer Science - 2
</footer>
