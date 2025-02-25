<?php 
session_start();
include "koneksi.php";

if (isset($_POST['loginAdmin'])) {
    $Email = $_POST['Email'];
    $password = $_POST['password'];

    // Cek apakah email ada di database
    $sql = "SELECT * FROM admin WHERE Email = '$Email'";
    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if ($password === $admin['password']) {
            // Set session setelah login sukses
            $_SESSION['admin_id'] = $admin['id'];  // Simpan user_id dalam session
            $_SESSION['admin_nama'] = $admin['nama'];
            $_SESSION['Admin_Email'] = $admin['Email'];  // Simpan nama user dalam session

            // Redirect ke halaman user setelah login
            header("Location: Admin/halamanAdmin.php");
            exit();
        } else {
            echo "<script>alert('Password salah. Silakan coba lagi')</script>";
            echo "<script>window.location.href='loginAdmin.php';</script>";
        }
    } else {
        echo "<script>alert('Email tidak terdaftar')</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="Admin/admin.css">
    <style>
        .login-page {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-size: cover;
    background-position: center;
}

.wrapper {
    width: 420px;
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(20px);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    color: #fff;
    border-radius: 10px;
    padding: 30px;
}

.wrapper h3 {
    font-size: 36px;
    text-align: center;
}

.input-box {
    position: relative;
    width: 100%;
    height: 50px;
    margin: 30px 0;
}

.input-box input {
    width: 100%;
    height: 50px;
    background: transparent;
    border: none;
    outline: none;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 40px;
    font-size: 16px;
    color: #fff;
    padding: 0 45px 0 20px;
}

.input-box input::placeholder {
    color: #fff;
}

.input-box i {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 20px;
}

.btn {
    width: 100%;
    height: 45px;
    background: #fff;
    border: none;
    border-radius: 40px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    color: #333;
}


    </style>
</head>
<body>
<div class="login-page" style="background-image: url('Admin/Asset/loginadmin.jpg'); height: fit-content; background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="wrapper">
            <form action="loginAdmin.php" method="POST">
                <h3>Login</h3>
                <div class="input-box">
                    <input type="Email" placeholder="email" name="Email" required>
                    <i class='bx bx-envelope'></i>
                </div>
                <div class="input-box">
                    <input type="password" placeholder="password" name="password" required>
                    <i class='bx bxs-lock'></i>
                </div>
                <button type="submit" class="btn" name="loginAdmin">Login</button>
            </form>
        </div>
    </div>
</body>
</html>