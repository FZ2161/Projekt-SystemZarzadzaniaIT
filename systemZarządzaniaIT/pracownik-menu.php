<?php
if($_SESSION["zalogowanoJako"]=="admin")  include "admin-menu.php";
echo 
    "<ul>

        <li> <a href='./pracownik.php'> EDYCJA PROJEKTU </a> </li>
        <li> <a href='./logowanie.php'> LOGOWANIE </a> </li>
        <li> <a href='./rejestracja.php'> REJESTRACJA </a> </li>
    </ul>";
?>