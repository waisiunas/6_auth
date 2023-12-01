<?php require_once './database/connection.php'; ?>

<?php
session_start();
if (isset($_SESSION['user'])) {
    header('location: ./dashboard.php');
}

$name = $email = "";
if (isset($_POST['submit'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $password_confirmation = htmlspecialchars($_POST['password_confirmation']);

    if (empty($name)) {
        $error = "Provide your name!";
    } elseif (empty($email)) {
        $error = "Provide your email!";
    } elseif (empty($password)) {
        $error = "Provide your password!";
    } elseif ($password !== $password_confirmation) {
        $error = "Your password does not match!";
    } else {
        $sql = "SELECT * FROM `users` WHERE `email` = '$email'";
        $result = $conn->query($sql);
        if ($result->num_rows === 0) {
            $hashed_password = sha1($password);
            $sql = "INSERT INTO `users`( `name`, `email`, `password`) VALUES ('$name','$email','$hashed_password')";
            if ($conn->query($sql)) {
                $success = "Magic has been spelled!";
                $name = $email = "";
            } else {
                $error = "Magic has failed to spell!";
            }
        } else {
            $error = "Email already exists!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-7 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Register</h3>
                    </div>

                    <div class="card-body">
                        <?php
                        if (isset($error)) { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $error ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php
                        }

                        if (isset($success)) { ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo $success ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php
                        }
                        ?>
                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" id="name" placeholder="Enter your name!" value="<?php echo $name ?>" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" placeholder="Enter your email!" value="<?php echo $email ?>" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" placeholder="Enter your passsword!" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Password Confirmation</label>
                                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm your password">
                            </div>

                            <div class="mb-3">
                                <input type="submit" value="Register" name="submit" class="btn btn-primary">
                            </div>

                            <div>
                                Already have an account? <a href="./">Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>

</html>