<!DOCTYPE html>
<html>
<head>
    <title>Student Details</title>
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
<h1>Student Details</h1>

<div class="modal fade" id="updateStudentModal" tabindex="-1" role="dialog" aria-labelledby="updateStudentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateStudentModalLabel">Update Student Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="updateStudentForm">
          <div class="form-group">
            <label for="updateFirstName">First Name</label>
            <input type="text" class="form-control" id="updateFirstName" name="updateFirstName" required>
          </div>
          <div class="form-group">
            <label for="updateMiddleName">Middle Name</label>
            <input type="text" class="form-control" id="updateMiddleName" name="updateMiddleName">
          </div>
          <div class="form-group">
            <label for="updateLastName">Last Name</label>
            <input type="text" class="form-control" id="updateLastName" name="updateLastName" required>
          </div>
          <div class="form-group">
            <label for="updateEmail">Email</label>
            <input type="email" class="form-control" id="updateEmail" name="updateEmail" required>
          </div>
          <div class="form-group">
            <label for="updateMobileNo">Mobile No</label>
            <input type="text" class="form-control" id="updateMobileNo" name="updateMobileNo" required>
          </div>
          <div class="form-group">
            <label for="updateGender">Gender</label>
            <select class="form-control" id="updateGender" name="updateGender" required>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </div>
          <div class="form-group">
            <label for="updateCourse">Course</label>
            <input type="text" class="form-control" id="updateCourse" name="updateCourse" required>
          </div>
          <div class="form-group">
            <label for="updateYear">Year</label>
            <input type="text" class="form-control" id="updateYear" name="updateYear" required>
          </div>
          <div class="form-group">
            <label for="updateCGPA">CGPA</label>
            <input type="text" class="form-control" id="updateCGPA" name="updateCGPA" required>
          </div>
          <input type="hidden" id="studentId" name="studentId">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" onclick="updateStudent()">Save changes</button>
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
                            <th>Student ID</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Mobile No</th>
                            <th>Gender</th>
                            <th>Course</th>
                            <th>Year</th>
                            <th>CGPA</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>';

    $query = "SELECT * FROM students ORDER BY student_id ASC"; 

    $result = mysqli_query($conn, $query); 
    if (!$result) { 
        exit(mysqli_error($conn));
    }

    if(mysqli_num_rows($result) > 0){
        $number = 1;
        while($row = mysqli_fetch_assoc($result)){   
            $data .= '<tr>
                <td>'.$number.'</td>
                <td>'.$row['student_id'].'</td>
                <td>'.$row['first_name'].'</td>
                <td>'.$row['middle_name'].'</td>
                <td>'.$row['last_name'].'</td>
                <td>'.$row['email'].'</td>
                <td>'.$row['mobile_no'].'</td>
                <td>'.$row['gender'].'</td>
                <td>'.$row['course'].'</td>
                <td>'.$row['year'].'</td>
                <td>'.$row['cgpa'].'</td>
                <td>
                    <button onclick="populateUpdateForm('.$row['student_id'].')" class="btn btn-warning" data-toggle="modal" data-target="#updateStudentModal">Update</button>
                </td>
                <td>
                    <button onclick="deleteStudent('.$row['student_id'].')" class="btn btn-danger">Delete</button>
                    
                </td>
            </tr>';
            $number++;
        }
    }else{
        $data .= '<tr><td colspan="13">Records not found!</td></tr>';
    }

    $data .= '</table>';

    echo $data;
?>

<script>
 

 function deleteStudent(studentId) {
    if (confirm('Are you sure you want to delete this student?')) {
        var xhr12 = new XMLHttpRequest();
        xhr12.open('POST', 'DeleteStudent.php', true);
        xhr12.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr12.onload = function() {
            if (xhr12.status === 200) {
                alert(xhr12.responseText);
                location.reload();
            } else {
                alert('Error: ' + xhr12.responseText); 
            }
        };
        var requestData = 'id=' + studentId;
        xhr12.send(requestData);
    }
}

function populateUpdateForm(studentId) {
    var xhr2 = new XMLHttpRequest();
    xhr2.open('GET', 'GetStudentDetails.php?studentId=' + studentId, true);
    xhr2.onload = function() {
    if (xhr2.status === 200) {
        var studentData = JSON.parse(xhr2.responseText);
        document.getElementById('updateFirstName').value = studentData.first_name;
        document.getElementById('updateMiddleName').value = studentData.middle_name;
        document.getElementById('updateLastName').value = studentData.last_name;
        document.getElementById('updateEmail').value = studentData.email;
        document.getElementById('updateMobileNo').value = studentData.mobile_no;
        document.getElementById('updateGender').value = studentData.gender;
        document.getElementById('updateCourse').value = studentData.course;
        document.getElementById('updateYear').value = studentData.year;
        document.getElementById('updateCGPA').value = studentData.cgpa;
        document.getElementById('studentId').value = studentId;
        } else {
                console.error('Error fetching student details: ' + xhr2.responseText);
            }
        };
        xhr2.send();
}

function updateStudent() {
        var studentId = document.getElementById('studentId').value;
        var firstName = document.getElementById('updateFirstName').value;
        var middleName = document.getElementById('updateMiddleName').value;
        var lastName = document.getElementById('updateLastName').value;
        var email = document.getElementById('updateEmail').value;
        var mobileNo = document.getElementById('updateMobileNo').value;
        var gender = document.getElementById('updateGender').value;
        var course = document.getElementById('updateCourse').value;
        var year = document.getElementById('updateYear').value;
        var cgpa = document.getElementById('updateCGPA').value;

        if (!studentId || !firstName || !lastName || !email || !mobileNo || !gender || !course || !year || !cgpa) {
            alert("Please fill in all fields.");
            return;
        }

       var xhrp1 = new XMLHttpRequest();
        xhrp1.open('POST', 'UpdateStudent.php', true);
        xhrp1.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhrp1.onload = function() {
            if (xhrp1.status === 200) {
                window.location.reload();
            } else {
                console.error('Error updating student details: ' + xhrp1.responseText);
            }
        };
        xhrp1.send('student_id=' + studentId + '&first_name=' + encodeURIComponent(firstName) + '&middle_name=' + encodeURIComponent(middleName) + '&last_name=' + encodeURIComponent(lastName) + '&email=' + encodeURIComponent(email) + '&mobile_no=' + encodeURIComponent(mobileNo) + '&gender=' + encodeURIComponent(gender) + '&course=' + encodeURIComponent(course) + '&year=' + encodeURIComponent(year) + '&cgpa=' + encodeURIComponent(cgpa));
    }
</script>

</body>
</html>
