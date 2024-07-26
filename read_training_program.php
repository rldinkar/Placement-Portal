<!DOCTYPE html>
<html>
<head>
    <title>Training Programs</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="nav_style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<div>
<nav class="navbar navbar-expand-lg navbar-light navbar-custom">
    <a class="navbar-brand" href="#">Placement Portal</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="add_student.html">Add Student</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="add_company.html">Add Company</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="add_placement.html">Add Placement</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="add_training_program.html">Add Programs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>
</div>
<h1>Training Programs</h1>
<div class="modal fade" id="updateProgramModal" tabindex="-1" role="dialog" aria-labelledby="updateProgramModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateProgramModalLabel">Update Program Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateProgramForm">
                    <div class="form-group">
                        <label for="updateName">Name</label>
                        <input type="text" class="form-control" id="updateName" name="updateName" required>
                    </div>
                    <div class="form-group">
                        <label for="updateDescription">Description</label>
                        <textarea class="form-control" id="updateDescription" name="updateDescription" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="updateStartDate">Start Date</label>
                        <input type="date" class="form-control" id="updateStartDate" name="updateStartDate" required>
                    </div>
                    <div class="form-group">
                        <label for="updateEndDate">End Date</label>
                        <input type="date" class="form-control" id="updateEndDate" name="updateEndDate">
                    </div>
                    <input type="hidden" id="programId" name="programId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" onclick="updateProgram()">Save changes</button>
            </div>
        </div>
    </div>
</div>

<?php
    include("database.php");

    if (mysqli_connect_errno()) {
        exit("Failed to connect to MySQL: " . mysqli_connect_error());
    }

    $data = '<table>
                        <tr>
                            <th>No.</th>
                            <th>Program ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>';

    $query = "SELECT * FROM training_programs ORDER BY program_id ASC";

    $result = mysqli_query($conn, $query);
    if (!$result) {
        exit(mysqli_error($conn));
    }

    if(mysqli_num_rows($result) > 0){
        $number = 1;
        while($row = mysqli_fetch_assoc($result)){
            $data .= '<tr>
                <td>'.$number.'</td>
                <td>'.$row['program_id'].'</td>
                <td>'.$row['name'].'</td>
                <td>'.$row['description'].'</td>
                <td>'.$row['start_date'].'</td>
                <td>'.$row['end_date'].'</td>
                <td>
                    <button onclick="populateUpdateForm('.$row['program_id'].')" class="btn btn-warning" data-toggle="modal" data-target="#updateProgramModal">Update</button>
                </td>
                <td>
                    <button onclick="deleteProgram('.$row['program_id'].')" class="btn btn-danger">Delete</button>
                </td>
            </tr>';
            $number++;
        }
    }else{
        $data .= '<tr><td colspan="8">Records not found!</td></tr>';
    }

    $data .= '</table>';

    echo $data;
?>

<script>
    function deleteProgram(programId) {
        if (confirm('Are you sure you want to delete this program?')) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'DeleteProgram.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert(xhr.responseText);
                    location.reload();
                } else {
                    alert('Error: ' + xhr.responseText);
                }
            };
            var requestData = 'id=' + programId;
            xhr.send(requestData);
        }
    }

    function populateUpdateForm(programId) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'GetProgramDetails.php?programId=' + programId, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var programData = JSON.parse(xhr.responseText);
                document.getElementById('updateName').value = programData.name;
                document.getElementById('updateDescription').value = programData.description;
                document.getElementById('updateStartDate').value = programData.start_date;
                document.getElementById('updateEndDate').value = programData.end_date;
                document.getElementById('programId').value = programId;
            } else {
                console.error('Error fetching program details: ' + xhr.responseText);
            }
        };
        xhr.send();
    }

    function updateProgram() {
        var programId = document.getElementById('programId').value;
        var name = document.getElementById('updateName').value;
        var description = document.getElementById('updateDescription').value;
        var startDate = document.getElementById('updateStartDate').value;
        var endDate = document.getElementById('updateEndDate').value;

        if (!programId || !name || !description || !startDate) {
            alert("Please fill in all required fields.");
            return;
        }

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'UpdateProgram.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                window.location.reload();
            } else {
                console.error('Error updating program details: ' + xhr.responseText);
            }
        };
        xhr.send('program_id=' + programId + '&name=' + encodeURIComponent(name) + '&description=' + encodeURIComponent(description) + '&start_date=' + startDate + '&end_date=' + endDate);
    }
</script>

</body>
</html>
