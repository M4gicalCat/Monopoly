<?php
include('connexion.php');
include ('BDD_Rues.php');
include('BDD_ParcGratuit.php');
$title = "Monopoly";
$style = "monopoly.css";
include('head.php');
?>
<script>
    function remove(id)
    {
        document.getElementById(id).style = "display='none';";
        document.getElementById(id).innerHTML.style = "display='none';";
        document.getElementById(id).firstChild.style.display = "none"
        document.getElementById(id).children[1].style.display = "none"
    }
</script>
<?php
include('fonctionsMonopoly.php');
include('createMonopoly.php');
include('footer.php');