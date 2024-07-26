<!DOCTYPE html>
<html>
<head>
    <title>Company Details</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="nav_style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
<h1>Company Details</h1>
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateModalLabel">Update Company Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="updateForm">
          <div class="form-group">
            <label for="updateCompanyName">Company Name</label>
            <input type="text" class="form-control" id="updateCompanyName" name="updateCompanyName" required>
          </div>
          <div class="form-group">
            <label for="updateLocation">Location</label>
            <input type="text" class="form-control" id="updateLocation" name="updateLocation" required>
          </div>
          <div class="form-group">
            <label for="updateWebsite">Website</label>
            <input type="text" class="form-control" id="updateWebsite" name="updateWebsite" required>
          </div>
          <div class="form-group">
            <label for="updateContactEmail">Contact Email</label>
            <input type="email" class="form-control" id="updateContactEmail" name="updateContactEmail" required>
          </div>
          <div class="form-group">
            <label for="updatePackage">Package</label>
            <input type="text" class="form-control" id="updatePackage" name="updatePackage" required>
          </div>
          <input type="hidden" id="companyId" name="companyId">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" onclick="updateCompany()">Save changes</button>
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
                            <th>Company Name</th>
                            <th>Location</th>
                            <th>Website</th>
                            <th>Contact Email</th>
                            <th>Package</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>';

    $query = "SELECT * FROM company ORDER  BY company_name ASC "; 

    $result = mysqli_query($conn, $query); 
    if (!$result) { 
        exit(mysqli_error($conn));
    }

    if(mysqli_num_rows($result) > 0){
        $number = 1;
        while($row = mysqli_fetch_assoc($result)){   
            $data .= '<tr>
                <td>'.$number.'</td>
                <td>'.$row['company_name'].'</td>
                <td>'.$row['location'].'</td>
                <td>'.$row['website'].'</td>
                <td>'.$row['contact_email'].'</td>
                <td>'.$row['package'].'</td>
                <td>
  <button onclick="populateUpdateForm('.$row['company_id'].')" class="btn btn-warning" data-toggle="modal" data-target="#updateModal">Update</button>
</td>
                <td>
                    <button onclick="deleteCompany('.$row['company_id'].')" class="btn btn-danger">Delete</button>
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
    function deleteCompany(companyId) {
        if (confirm('Are you sure you want to delete this company?')) {
          
            var xhrp = new XMLHttpRequest();
            xhrp.open('POST', 'DeleteCompany.php', true);
            xhrp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhrp.onload = function() {
                if (xhrp.status === 200) {
                   
                    location.reload();
                } else {
                    alert('Error: ' + xhrp.responseText);
                }
            };
            xhrp.send('id=' + companyId);
        }
    }
    function populateUpdateForm(companyId) {
    var xhr1 = new XMLHttpRequest();
    xhr1.open('GET', 'GetCompanyDetails.php?companyId=' + companyId, true);
    xhr1.onload = function() {
        if (xhr1.status === 200) {
            var companyData = JSON.parse(xhr1.responseText);
            document.getElementById('updateCompanyName').value = companyData.company_name;
            document.getElementById('updateLocation').value = companyData.location;
            document.getElementById('updateWebsite').value = companyData.website;
            document.getElementById('updateContactEmail').value = companyData.contact_email;
            document.getElementById('updatePackage').value = companyData.package;
            document.getElementById('companyId').value = companyId;
        } else {
            console.error('Error fetching company details: ' + xhr1.responseText);
        }
    };
    xhr1.send();
}

function updateCompany() {
    var companyId = document.getElementById('companyId').value;
    var companyName = document.getElementById('updateCompanyName').value;
    var companyLocation = document.getElementById('updateLocation').value;
    var website = document.getElementById('updateWebsite').value;
    var contactEmail = document.getElementById('updateContactEmail').value;
    var package = document.getElementById('updatePackage').value;
    if (!companyId || !companyName || !companyLocation || !website || !contactEmail || !package) {
        alert("Please fill in all fields.");
        return;
    }
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'UpdateCompany.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            window.location.reload();
        } else {
            console.error('Error updating company details: ' + xhr.responseText);
        }
    };
    xhr.send('company_id=' + companyId + '&company_name=' + encodeURIComponent(companyName) + '&location=' + encodeURIComponent(companyLocation) + '&website=' + encodeURIComponent(website) + '&contact_email=' + encodeURIComponent(contactEmail) + '&package=' + encodeURIComponent(package));
}

</script>
</body>
</html>