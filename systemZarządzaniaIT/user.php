<?php 
session_start();
if(!isset($_SESSION["zalogowanoJako"])){
    $_SESSION["zalogowanoJako"] = "nie zalogowano";
}


if (isset($_GET["wyloguj"])) {
    $_SESSION["zalogowanoJako"] = "nie zalogowano";
    // Przekierowanie do tej samej strony po wylogowaniu
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style1.css">
    <link rel="stylesheet" href="prism.css">
    <link rel="stylesheet" href="user.css">
    <title>index</title>
</head>
<body>
    <header data-plugin-header="line-numbers"></header>


    <?php
         $dbHost="localhost";
         $dbUser="root";
         $dbPassword="";
         $db="system_zarzadzania_it";
 
         $conn=mysqli_connect($dbHost, $dbUser, $dbPassword, $db);
 
         if(!$conn){
             echo "Nie można połączyć się z bazą danych";
         }
    ?>


    <h2>USER</h2>
    <div id="menu">
        <?php 
            if($_SESSION["zalogowanoJako"] == "admin") {
                include "admin-menu.php";
            } else if ($_SESSION["zalogowanoJako"] == "user"){
                include "user-menu.php";
            } else if ($_SESSION["zalogowanoJako"] == "pracownik"){
                include "pracownik-menu.php";
            } else { 
                include "nMenu.php";
            }
        ?>
    </div>
    <div id="user-glowny">
        <div id="lewy">
            <h3>
                <?php
                    if(!empty($_SESSION["zalogowanoJako"])){
                        echo "";
                    } else {
                        echo "Nie zalogowano";
                    }
                ?>
            </h3>

            <form action="" method="get">
                <label for="projekty">Dołącz do projektu:</label>
                <select name="projekty" id="projekty">
                    <option value="">----------</option>
                    <?php
                        $sql = "SELECT * FROM projects";
                        $result = mysqli_query($conn, $sql);

                        if(mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['id'] . "'>" . "Projekt " . $row['id'] . "</option>";
                            }
                        }
                    ?>
                </select><br><br>
                <input type="submit" value="Dołącz">  <br><br>
            </form>

            <?php
                if(!empty($_GET["projekty"])){
                    $wybranyProjekt = $_GET["projekty"];
                    $user = $_SESSION["zalogowanoJako"];
                    $sql = "INSERT INTO dolaczeni (project_id, user) VALUES ('$wybranyProjekt', '$user')";

                    if(mysqli_query($conn, $sql)){
                        echo "<p style='color: green;'>Dołączono do projektu</p>";
                    } else {
                        echo "<p style='color: red;'>Nie udało się dołączyć do projektu</p>";

                    }
                }
            ?>

        </div>
        <div id="prawy">
            <div class="border" id="user-gora">
                <div id="userGL">
                    <?php
                    $sql="SELECT DISTINCT dolaczeni.user, projects.id, projects.kod FROM projects JOIN dolaczeni ON projects.id = dolaczeni.project_id";

                    $results = mysqli_query($conn, $sql);
                    // // //  FORMULARZ WYBIERAJĄCY PROJEKT  // // //

                    if(mysqli_num_rows($results)>0){
                        echo "<form action='' method='post'>";
                        echo "<label for='projekt'>Wybierz projekt: </label>";
                        echo "<select name='projekt'>";
                        while($row = mysqli_fetch_assoc($results)) {
                            echo "<option value='" . $row['id'] . "'>" . ("Projekt ") . $row['id'] . "</option>";
                        }
                        $kod = $row["kod"];
                        echo "</select><br><br>";
                        echo "<input type='submit' value='Zobacz kod projektu'>";
                    } else{
                        echo "<p>Brak danych</p>";
                    }
                    ?>
                </div>
                <div id="userGP">
                    <h3>Dodaj komentarz</h3>
                    <?php
                    if(!empty($_POST["projekt"])){
                        $id=$_POST["projekt"];
                        echo $id;
                    }
                    ?>
                    
                </div>
            </div>
            <div>
                <pre class="line-numbers" data-line="1"><code class="language-php">
                    <?php
                    if(!empty($_POST["projekt"])){
                    $id = $_POST["projekt"];
                        $sql="SELECT DISTINCT kod from projects where id = $id";
                        $results = mysqli_query($conn, $sql);
                        while($row = mysqli_fetch_assoc($results)) {
                            echo $row["kod"];
                        }
                    } else {
                        echo "#####  TUTAJ ZOSTANIE WYŚWIETLONY KOD PROJEKTU  #####";
                    }
                    ?>
                </code></pre>
            </div>

            <div class="border">
                    <h3>Komentarze</h3>
                    <?php
                    ////////////////////////////////////    do zmiany
                    $id = isset($_POST["projekt"]) ? $_POST["projekt"] : 0;
                    $sql="SELECT DISTINCT * FROM comments WHERE `project-id` = $id";
                    $results = mysqli_query($conn, $sql);
                    
                    if(mysqli_num_rows($results)>0){
                        while($row = mysqli_fetch_assoc($results)) {
                            $linia = $row['line'];
                            echo "<div class='komentarz'> <h4>";
                            echo "komentarz do linii: $linia";
                            echo "</h4>";
                            echo "<p>";
                            echo $row["tresc"];
                            echo "</p></div>";
                        }
                    } else{
                        echo "<p>Brak komentarzy</p>";
                    }
                    ?>
            </div>
        </div>
    </div>

    <?php
    mysqli_close($conn)
    ?>



    <script src="prism.js"></script>
</body>
</html>