<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style1.css">
    <title>logowanie</title>
</head>
<body>
    <h2>LOGOWANIE</h2>
    <?php
    include "menu.php";
    ?>
    <!-- ------------------------------------------------------- -->

    <form action="" method="post">
        <input type="text" name="login">
        <input type="text" name="password">
        <input type="submit" value="zaloguj">
    </form>

    <?php
    if(!empty($_POST["login"])&&!empty($_POST["password"])){
        $login=$_POST["login"];
        $pass=$_POST["password"];

        function szyfruj_haslo($pass){
            return md5($pass);
        }
        $zaszyfrowane = szyfruj_haslo($pass);
        
        $dbHost="localhost";
        $dbUser="root";
        $dbPassword="";
        $db="system_zarzadzania_it";

        $conn=mysqli_connect($dbHost, $dbUser, $dbPassword, $db);

        if(!$conn){
            echo "err: " . mysqli_connect_error($conn);
        }

        $sql="SELECT * FROM users WHERE login='$login' && password='$zaszyfrowane'";

        $results=mysqli_query($conn, $sql);

        if(mysqli_num_rows($results)>0){
            while($row=mysqli_fetch_assoc($results)){
            $zalogowanoJako=$row["uprawnienia"];  //zapis zmiennej zalogowano jako:
            echo "zalogowano jako: ". $row["uprawnienia"];
        }
        } else{
            echo "niepoprawne dane";
        }

    } else {
        echo "podaj wszystkie dane";
    }
    ?>

</body>
</html>