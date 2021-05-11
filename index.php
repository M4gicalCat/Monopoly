<?php
$title = "Monopoly";
$style = "monopoly.css";
include('head.php');
?>
<form method="post" action="Monopoly.php">
    <label>Nombre de joueurs ?</label>
    <select name="nbJoueurs">
        <option> 1 Joueur</option>
        <option> 2 Joueurs</option>
        <option> 3 Joueurs</option>
        <option> 4 Joueurs</option>
        <option> 5 Joueurs</option>
        <option> 6 Joueurs</option>
    </select><br>
    <label>Nom du joueur 1</label>
    <input type="text" name="nomJoueur1" value="joueur 1"><img class='pion' src="0.png"><br>
    <label>Nom du joueur 2</label>
    <input type="text" name="nomJoueur2" value="joueur 2"><img class='pion' src="1.png"><br>
    <label>Nom du joueur 3</label>
    <input type="text" name="nomJoueur3" value="joueur 3"><img class='pion' src="2.png"><br>
    <label>Nom du joueur 4</label>
    <input type="text" name="nomJoueur4" value="joueur 4"><img class='pion' src="3.png"><br>
    <label>Nom du joueur 5</label>
    <input type="text" name="nomJoueur5" value="joueur 5"><img class='pion' src="4.png"><br>
    <label>Nom du joueur 6</label>
    <input type="text" name="nomJoueur6" value="joueur 6"><img class='pion' src="5.png"><br>
    <input type="submit">
</form>
<?php
include('footer.php');
