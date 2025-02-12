<?php
include "connection.php";
$id = "";
$name = "";
$email = "";
$phone = "";
$password = "";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == 'GET') {
    if (!isset($_GET['id'])) {
        header("location:CrudPHP/index.php");
        exit;
    }
    $id = $_GET['id'];
    $sql = "select * from crud2 where id=$id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    if (!$row) {
        header("location: CrudPHP/index.php");
        exit;
    }
    $name = $row["name"];
    $email = $row["email"];
    $phone = $row["phone"];
} else {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];

    $errors = [];
    if (empty($name)) {
        $errors[] = "Enter name";
    } else {
        // Additional validation for alphabets only
        if (!preg_match("/^[a-zA-Z]+$/", $name)) {
            $errors['name'] = "Only alphabets are allowed for the name.";
        }
    }
    if (empty($email)) {
        $errors[] = "Enter email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    if (empty($phone)) {
        $errors[] = "Enter phone number";
    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
        $errors[] = "Invalid phone number format";
    }

    if (count($errors) == 0) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "update crud2 set name='$name', email='$email', phone='$phone', password='$hashed_password' where id='$id'";
        $result = $conn->query($sql);
        $success = "Record updated successfully";
        header("location:/CrudPHP/index.php");
        exit;
    } else {
        $error = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>CRUD</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" class="fw-bold">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">PHP CRUD OPERATION</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="create.php">Add New</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="col-lg-6 m-auto">
        <form method="post">
            <br><br>
            <div class="card">
                <div class="card-header bg-warning">
                    <h1 class="text-white text-center"> Update Member </h1>
                </div><br>
                <input type="hidden" name="id" value="<?php echo $id; ?>" class="form-control" required> <br>
                <?php if (!empty($error)) : ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <label> NAME: </label>
                <input type="text" name="name" value="<?php echo $name; ?>" class="form-control" required> <br>
                <label> EMAIL: </label>
                <input type="email" name="email" value="<?php echo $email; ?>" class="form-control" required> <br>
                <label> PHONE: </label>
                <input type="text" name="phone" value="<?php echo $phone; ?>" class="form-control" required> <br>
                <label> RESET PASSWORD: </label>
                <input type="password" name="password" class="form-control"> <br>
                <button class="btn btn-success" type="submit" name="submit"> Submit </button><br>
                <a class="btn btn-info" href="index.php"> Cancel </a><br>
            </div>
        </form>
    </div>
</body>

</html>