<?php
    session_start();
    //登出
    if (isset($_POST["logout"])) {
        session_destroy();
        header("Location:login.php");
        exit();
    }
    //登入檢查
    if (!isset($_SESSION["user"])) {
        header("LOcation:login.php");
        exit();
    }
    try {
        $pdo=new PDO("mysql:host=localhost;dbname=mos;","root","");
    } catch (PDOException $err) {
        die("資料庫無法連接");
    }
?>
<html>
    <head>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="scripts/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <?php
            $err='';
            if ($_SERVER["REQUEST_METHOD"]=="POST") {
                $stmt=$pdo->prepare('select * from musers where account=? and password=?');
                $stmt->execute(array($_POST["account"],$_POST["password"]));

                $rows=$stmt->fetchAll();
                if (count($rows)>0) {
                    $_SESSION["user"]=$rows[0]["id"];
                    
                    header("Location:stores.php");
                    exit();
                } else {
                    $err="帳號/密碼錯誤";
                }
            }
        ?>
        <header>
            <div>
                <form method="post" action="stores.php">
                <button type="submit" class="btn btn-danger" name="logout" value="logout">登出</button>
                </form>
            </div>
        </header>
        <h1>Stores</h1>
        <form method="post" action="login.php">
            <div class="form-group">
                <label for="f1">帳號</label>
                <input type="text" class="form-control" id="f1" name="account" required>
            </div>
            <div class="form-group">
                <label for="f2">密碼</label>
                <input type="text" class="form-control" id="f2" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="btn">登入</button>
        </form>
        <?php echo "<h1 style='color:#f00'>$err </h1>";?>

    </body>
</html>