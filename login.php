<?php
session_start();

//koneksi database
$host_db    = "localhost";
$user_db    = "root";
$pass_db    = "";
$nama_db    = "login";
$koneksi    = mysqli_connect($host_db,$user_db,$pass_db,$nama_db);

$err        = "";
$username   = "";
$remember   = "";

if(isset($_COOKIE['cookie_username'])){
    $cookie_username = $_COOKIE['cookie_username'];
    $cookie_password = $_COOKIE['cookie_password'];

    $sql1 = "select * from login where username = '$cookie_username'";
    $q1   = mysqli_query($koneksi,$sql1);
    $r1   = mysqli_fetch_array($q1);
    if($r1['password'] == $cookie_password){
        $_SESSION['session_username'] = $cookie_username;
        $_SESSION['session_password'] = $cookie_password;
    }
}

if(isset($_SESSION['session_username'])){
    header("location:user.php");
    exit();
}

if(isset($_POST['login'])){
    $username   = $_POST['username'];
    $password   = $_POST['password'];
    $remember   = $_POST['remember'];

    if($username == '' or $password == ''){
        $err .= "<li>Silahkan masukkan username dan password.</li>";
    }else{
        $sql1 = "select * from login where username = '$username'";
        $q1   = mysqli_query($koneksi,$sql1);
        $r1   = mysqli_fetch_array($q1);

        if($r1['username'] == ''){
            $err .= "<li>Username <b>$username</b> tidak tersedia.</li>";
        }elseif($r1['password'] != md5($password)){
            $err .= "<li>Password tidak sesuai.</li>";
        }       
        
        if(empty($err)){
            $_SESSION['session_username'] = $username; 
            $_SESSION['session_password'] = md5($password);

            if($remember == 1){
                $cookie_name = "cookie_username";
                $cookie_value = $username;
                $cookie_time = time() + (60 * 60 * 24 * 30);
                setcookie($cookie_name,$cookie_value,$cookie_time,"/");

                $cookie_name = "cookie_password";
                $cookie_value = md5($password);
                $cookie_time = time() + (60 * 60 * 24 * 30);
                setcookie($cookie_name,$cookie_value,$cookie_time,"/");
            }
            header("location:user.php");
        }
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://unpkg.com/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="h-screen overflow-hidden flex items-center justify-center" style="background: #edf2f7;">
 <div class="font-sans">
            <div class="relative min-h-screen flex flex-col sm:justify-center items-center bg-gray-100 ">
                <div class="relative sm:max-w-sm w-full">
                    <div class="card bg-blue-400 shadow-lg  w-full h-full rounded-3xl absolute  transform -rotate-6"></div>
                    <div class="card bg-red-400 shadow-lg  w-full h-full rounded-3xl absolute  transform rotate-6"></div>
                    <div class="relative w-full rounded-3xl  px-6 py-4 bg-gray-100 shadow-md">
                        <label for="" class="block mt-3 text-sm text-gray-700 text-center font-semibold">
                            LOGIN
                        </label>
                        <?php if($err){ ?>
                            <div id="login-alert" class="alert alert-danger col-sm-12">
                                <ul><?php echo $err ?></ul>
                            </div>
                        <?php } ?> 
                        <form method="post" action="#" class="mt-10">
                                           
                            <div>
                                <input id="login-username" type="text" placeholder="username" class="mt-1 block w-full border-none bg-gray-100 h-11 rounded-xl shadow-lg hover:bg-blue-100 focus:bg-blue-100 focus:ring-0" name="username" value="<?php echo $username ?>">
                            </div>
                
                            <div class="mt-7">                
                                <input id="login-password" type="password" placeholder="password" class="mt-1 block w-full border-none bg-gray-100 h-11 rounded-xl shadow-lg hover:bg-blue-100 focus:bg-blue-100 focus:ring-0" name="password">                           
                            </div>

                            <div class="mt-7 flex">
                                <label for="remember_me" class="inline-flex items-center w-full cursor-pointer">
                                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember" value="1" <?php if($remember == '1') echo "checked"?>>
                                    <span class="ml-2 text-sm text-gray-600">
                                        Remember Me
                                    </span>
                                </label>
                            </div>
                
                            <div class="mt-7">
                                <button name="login" class="bg-blue-500 w-full py-3 rounded-xl text-white shadow-xl hover:shadow-inner focus:outline-none transition duration-500 ease-in-out  transform hover:-translate-x hover:scale-105">
                                    Login
                                </button>
                            </div>
                
                            <div class="flex mt-7 items-center text-center">
                                <hr class="border-gray-300 border-1 w-full rounded-md">
                                <label class="block font-medium text-sm text-gray-600 w-full">
                                    192410102051 IkfiraAgustinaPutri
                                </label>
                                <hr class="border-gray-300 border-1 w-full rounded-md">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
</body>
</html>
