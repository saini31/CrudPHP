<?php
include "connection.php";

$errors = []; // Initialize an array to store validation errors

if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['conform_pass']);

    // Validate input
    if (empty($name)) {
        $errors['name'] = "Name is required.";
    } else {
        // Additional validation for alphabets only
        if (!preg_match("/^[a-zA-Z]+$/", $name)) {
            $errors['name'] = "Only alphabets are allowed for the name.";
        }
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Valid email is required.";
    }

    if (empty($phone)) {
        $errors['phone'] = "Phone number is required.";
    } else {
        // Additional validation for a 10-digit number
        if (!ctype_digit($phone) || strlen($phone) !== 10) {
            $errors['phone'] = "Valid 10-digit phone number is required.";
        }
    }

    if (empty($password) || empty($confirmPassword) || $password !== $confirmPassword) {
        $errors['password'] = "Passwords do not match.";
    }

    // If there are no validation errors, proceed with database insertion
    if (empty($errors)) {
        // Hash the password before inserting into the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $q = "INSERT INTO `crud2`(`name`, `email`, `phone`, `password`) VALUES ('$name', '$email', '$phone', '$hashedPassword')";
        $query = mysqli_query($conn, $q);

        if ($query) {
            header('location:/CrudPHP/index.php');
            exit;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>CRUD APPLICATION</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>

<body>
    <!-- Include your body content here -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">PHP CRUD OPERATION</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a type="button" class="btn btn-primary nav-link active" href="create.php">Add New</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="col-lg-6 m-auto">
        <form method="post">

            <br><br>
            <div class="card">

                <div class="card-header bg-primary">
                    <h1 class="text-white text-center">Create New Member</h1>
                </div><br>

                <label> NAME: </label>
                <input type="text" name="name" class="form-control" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>">
                <?php if (isset($errors['name'])) echo '<p class="text-danger">' . $errors['name'] . '</p>'; ?> <br>

                <label> EMAIL: </label>
                <input type="email" name="email" class="form-control" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                <?php if (isset($errors['email'])) echo '<p class="text-danger">' . $errors['email'] . '</p>'; ?> <br>

                <label> PHONE: </label>
                <input type="number" name="phone" class="form-control" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : ''; ?>">
                <?php if (isset($errors['phone'])) echo '<p class="text-danger">' . $errors['phone'] . '</p>'; ?> <br>
                <label>PASSWORD:</label>
                <input type="text" name="password" class="form-control" value="<?php echo isset($_POST['password']) ? $_POST['password'] : ''; ?>">
                <?php if (isset($errors['password'])) echo '<p class="text-danger">' . $errors['password'] . '</p>'; ?>
                <label>CONFORM PASSWORD:</label>
                <input type="text" name="conform_pass" class="form-control" value="<?php echo isset($_POST['conform_pass']) ? $_POST['conform_pass'] : ''; ?>">
                <?php if (isset($errors['conform_pass'])) echo '<p class="text-danger">' . $errors['password'] . '</p>'; ?>
                <button class="btn btn-success" type="submit" name="submit" style="margin-top:20px;"> Submit </button><br>
                <a class="btn btn-info" href="index.php"> Cancel </a><br>

            </div>
        </form>
    </div>
</body>
<script>
    function B() {
        var m1, m2, c1, c2, a, b;
        m1 = document.getElementById("d1");
        m2 = document.getElementById("d2");
        c1 = document.getElementById("e1");
        c2 = document.getElementById("e2");

        a = m1.value;
        b = m2.value;

        c1.innerHTML = "";
        c2.innerHTML = "";

        if (a == null || a == "") {
            c1.innerHTML = "*Enter Email";
            m1.focus();
            return false;
        } else if (b == null || b == "") {
            c2.innerHTML = "*Enter Password";
            m2.focus();
            return false;
        }
    }
</script>


</html>