<?php
include_once ('connexion.php');
include_once ('fonctionsMonopoly.php');
$title = "Monopoly";
include_once ('head.php');

if(isset($_SESSION['error']))
{
    header("refresh:5;url=Monopoly.php");
    echo "<p style='background-color: red; padding: 3% 10%; font-size: 5em; border-radius:15px; text-align: center;'>".$_SESSION['error']."</p>";
    unset($_SESSION['error']);
}
else{
    header('Location: Monopoly.php');
}

include_once ('footer.php');