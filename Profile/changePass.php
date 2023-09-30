<?php
   /* session_start();

    require '../db.php';


    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $user = dataFilter($_POST['uname']);
        $currPass = $_POST['currPass'];
        $newPass = $_POST['newPass'];
        $conNewPass = $_POST['conNewPass'];
        $newHash = dataFilter( md5( rand(0,1000) ) );
    }

    $sql = "SELECT * FROM members WHERE Username='$user'";
    $result = mysqli_query($conn, $sql);
    $num_rows = mysqli_num_rows($result);


    if($num_rows == 0)
    {
        $_SESSION['message'] = "Invalid User Credentials!";
        header("location: error.php");
    }
    else
    {
        $User = $result->fetch_assoc();

        if(password_verify($_POST['currPass'], $User['Password']))
        {
            if($newPass == $conNewPass)
            {
                $conNewPass = dataFilter(password_hash($_POST['conNewPass'], PASSWORD_BCRYPT));
                $currHash = $_SESSION['Hash'];
                $sql = "UPDATE members SET Password='$conNewPass', Hash='$newHash' WHERE Hash='$currHash';";

                $result = mysqli_query($conn, $sql);

                if($result)
                {
                    $_SESSION['message'] = "Password changed Successfully!";
                    header("location: ../Login/success.php");
                }
                else
                {
                    $_SESSION['message'] = "Error occurred while changing password<br>Please try again!";
                    header("location: ../Login/error.php");
                }
            }
        }
        else
        {
            $_SESSION['message'] = "Invalid current User Credentials!";
            header("location: ../Login/error.php");
        }
    }

    function dataFilter($data)
    {
    	$data = trim($data);
     	$data = stripslashes($data);
    	$data = htmlspecialchars($data);
      	return $data;
    }

?>*/
session_start();

require '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $username = $_POST['uname'];
    $currentPassword = $_POST['currPass'];
    $newPassword = $_POST['newPass'];
    $confirmNewPassword = $_POST['conNewPass'];
}

$stmt = $conn->prepare("SELECT * FROM members WHERE Username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0)
{
    $_SESSION['message'] = "Invalid User Credentials!";
    header("location: error.php");
    exit();
}

$user = $result->fetch_assoc();

if (!password_verify($currentPassword, $user['Password']))
{
    $_SESSION['message'] = "Invalid current User Credentials!";
    header("location: ../Login/error.php");
    exit();
}

if ($newPassword !== $confirmNewPassword)
{
    $_SESSION['message'] = "New passwords do not match!";
    header("location: ../Login/error.php");
    exit();
}

// Validate new password
if (strlen($newPassword) < 8)
{
    $_SESSION['message'] = "New password must be at least 8 characters long!";
    header("location: ../Login/error.php");
    exit();
}

if (!preg_match('/[A-Za-z]/', $newPassword) || !preg_match('/\d/', $newPassword))
{
    $_SESSION['message'] = "New password must contain both letters and numbers!";
    header("location: ../Login/error.php");
    exit();
}

$newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);
$currentHash = $_SESSION['Hash'];
$stmt = $conn->prepare("UPDATE members SET Password = ?, Hash = ? WHERE Hash = ?");
$stmt->bind_param("sss", $newPasswordHash, $currentHash, $currentHash);
$result = $stmt->execute();

if ($result)
{
    $_SESSION['message'] = "Password changed successfully!";
    header("location: ../Login/success.php");
}
else
{
    $_SESSION['message'] = "Error occurred while changing password<br>Please try again!";
    header("location: ../Login/error.php");
}

