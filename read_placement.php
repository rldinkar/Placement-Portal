<!DOCTYPE html>
<html>
<head>
    <title>Placements Details</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="nav_style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div>
        <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
            <a class="navbar-brand" href="#">Placement Portal</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
    <h1>Placements Details</h1>
    <div class="modal fade" id="updatePlacementModal" tabindex="-1" role="dialog"
        aria-labelledby="updatePlacementModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updatePlacementModalLabel">Update Placement Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updatePlacementForm">
                        <div class="form-group">
                            <label for="updateStudentFirstName">Student First Name</label>
                            <input type="text" class="form-control" id="updateStudentFirstName"
                                name="updateStudentFirstName" required>
                        </div>
                        <div class="form-group">
                            <label for="updateStudentLastName">Student Last Name</label>
                            <input type="text" class="form-control" id="updateStudentLastName"
                                name="updateStudentLastName" required>
                        </div>
                        <div class="form-group">
                            <label for="updateCompanyName">Company Name</label>
                            <input type="text" class="form-control" id="updateCompanyName" name="updateCompanyName"
                                required>
                        </div>


                        <div class="form-group">
                            <label for="updateRole">Role</label>
                            <input type="text" class="form-control" id="updateRole" name="updateRole" required>
                        </div>
                        <div class="form-group">
                            <label for="updatePlacementDate">Placement Date</label>
                            <input type="date" class="form-control" id="updatePlacementDate" name="updatePlacementDate"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="updatePlacementType">Placement Type</label>
                            <input type="text" class="form-control" id="updatePlacementType" name="updatePlacementType"
                                required>
                        </div>
                        <input type="hidden" id="placementId" name="placementId">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updatePlacement()">Save changes</button>
                </div>
            </div>
        </div>
    </div>
<?php
include("database.php");
if (mysqli_connect_errno()) {
    exit("Failed to connect to MySQL: " . mysqli_connect_error());
}

$data = '<table class="table table-bordered">
    <tr>
        <th>No.</th>
        <th>Student Name</th>
        <th>Company Name</th>
        <th>Role</th>
        <th>Placement Date</th>
        <th>Placement Type</th>
        <th>Update</th>
        <th>Delete</th>
    </tr>';
$query = "
    SELECT placements.*, company.company_name, students.first_name, students.last_name
    FROM placements 
    JOIN company ON placements.company_id = company.company_id
    JOIN students ON placements.student_id = students.student_id
    ORDER BY placements.placement_date ASC;
";

$result = mysqli_query($conn, $query);

if (!$result) {
    exit(mysqli_error($conn));
}
if (mysqli_num_rows($result) > 0) {
    $number = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $data .= '<tr>
            <td>'.$number.'</td>
            <td>'.$row['first_name'].' '.$row['last_name'].'</td>
            <td>'.$row['company_name'].'</td>
            <td>'.$row['role'].'</td>
            <td>'.$row['placement_date'].'</td>
            <td>'.$row['placement_type'].'</td>
            <td>
                <button onclick="populateUpdateForm('.$row['placement_id'].')" class="btn btn-warning" data-toggle="modal" data-target="#updatePlacementModal">Update</button>
            </td>
            <td>
                <button onclick="deletePlacement('.$row['placement_id'].')" class="btn btn-danger">Delete</button>
            </td>
        </tr>';
        $number++;
    }
} else {
    $data .= '<tr><td colspan="8">Records not found!</td></tr>';
}

$data .= '</table>';

echo $data;
mysqli_free_result($result);
mysqli_close($conn);
?>

<script>
    function deletePlacement(placementId) {
        if (confirm('Are you sure you want to delete this placement?')) {
            var xhrp1 = new XMLHttpRequest();
            xhrp1.open('POST', 'DeletePlacement.php', true);
            xhrp1.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhrp1.onload = function() {
                if (xhrp1.status === 200) {
                    location.reload();
                } else {
                    alert('Error: ' + xhrp1.responseText);
                }
            };
            xhrp1.send('id=' + placementId);
        }
    }
    function populateUpdateForm(placementId) {
    var xhr11 = new XMLHttpRequest();
    xhr11.open('GET', 'GetPlacementDetails.php?placementId=' + placementId, true);
    xhr11.onload = function() {
        if (xhr11.status === 200) {
            var placementData = JSON.parse(xhr11.responseText);
            if (placementData.hasOwnProperty('error')) {
                console.error('Error: ' + placementData.error);
            } else {
                document.getElementById('updateStudentFirstName').value = placementData.first_name;
                document.getElementById('updateStudentLastName').value = placementData.last_name;
                document.getElementById('updateCompanyName').value = placementData.company_name; 
                document.getElementById('updateRole').value = placementData.role;
                document.getElementById('updatePlacementDate').value = placementData.placement_date;
                document.getElementById('updatePlacementType').value = placementData.placement_type;
                document.getElementById('placementId').value = placementId;
            }
        } else {
            console.error('Error fetching placement details: ' + xhr11.responseText);
        }
    };
    xhr11.send();
}

function updatePlacement() {
    var placementId = document.getElementById('placementId').value;
    var firstName = document.getElementById('updateStudentFirstName').value;
    var lastName = document.getElementById('updateStudentLastName').value;
    var companyName = document.getElementById('updateCompanyName').value;
    var role = document.getElementById('updateRole').value;
    var placementDate = document.getElementById('updatePlacementDate').value;
    var placementType = document.getElementById('updatePlacementType').value;

    if (!placementId || !firstName || !lastName || !companyName || !role || !placementDate || !placementType) {
        alert("Please fill in all fields.");
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'UpdatePlacement.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            if (xhr.responseText.includes("successfully")) {
                alert('Placement details updated successfully.');
                window.location.reload(); // Reload the page after successful update
            } else {
                console.error('Error updating placement details: ' + xhr.responseText);
                alert('Error updating placement details. Please check console for details.');
            }
        } else {
            console.error('Error updating placement details: ' + xhr.responseText);
            alert('Error updating placement details. Please check console for details.');
        }
    };
    xhr.onerror = function() {
        console.error('XHR request failed');
        alert('Failed to update placement details. Please try again.');
    };
    xhr.send('placement_id=' + encodeURIComponent(placementId) + 
             '&first_name=' + encodeURIComponent(firstName) + 
             '&last_name=' + encodeURIComponent(lastName) + 
             '&company_name=' + encodeURIComponent(companyName) + 
             '&role=' + encodeURIComponent(role) + 
             '&placement_date=' + encodeURIComponent(placementDate) + 
             '&placement_type=' + encodeURIComponent(placementType));
}

</script>
</body>
</html>
