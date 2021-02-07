<?php
include_once ('connexion.php');
include_once ('fonctionsMonopoly.php');
$title = "Monopoly";
include_once ('../head.php');

if(empty($_POST)):

    $noms = getAllPlayersNames();
?>

    <form method="post">
        <fieldset style="width: 40%; border-radius: 10px;">
            <label>Le joueur </label>
            <select name="Achete">
                <option value="none">Choisir un joueur</option>
                <?php
                foreach($noms as $nom)
                {
                    echo "<option value='".$nom."'>".$nom."</option>";
                }
                ?>
            </select>
            <label> demande une / des rues au joueur </label>
            <select name="Vend">
                <option value="none">Choisir un joueur</option>
                <?php
                foreach($noms as $nom)
                {
                    echo "<option value='".$nom."'>".$nom."</option>";
                }
                ?>
            </select>
        </fieldset>
        <input type="submit">
    </form>
<?php else:

    if(isset($_POST['Achete'], $_POST['Vend']) && $_POST['Achete'] != $_POST['Vend'] && $_POST['Vend'] != "none" && $_POST['Achete'] != "none"):
        //Si deux joueurs différents sont choisis :
        endif;

?>


<?php
endif;
include_once ('../footer.php');
?>