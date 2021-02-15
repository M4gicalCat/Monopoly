<?php
include_once ('connexion.php');
include_once ('fonctionsMonopoly.php');
$title = "Monopoly";
include_once ('head.php');

if(empty($_POST) or !(isset($_POST['Achete'], $_POST['Vend']) && $_POST['Achete'] != $_POST['Vend'] && $_POST['Vend'] != "none" && $_POST['Achete'] != "none")):

    if((isset($_POST['Achete'], $_POST['Vend']) && !($_POST['Achete'] != $_POST['Vend'] && $_POST['Vend'] != "none" && $_POST['Achete'] != "none"))):?>
        <!--Si pas deux joueurs différents sont choisis :-->
        <p style="color: #EE0000;">Veuillez choisir deux joueurs différents.</p>
    <?php
    endif;
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
                    ?> <option value='<?php echo $nom; ?>' <?php if(isset($_POST['Achete']) && $_POST['Achete']==$nom) {echo "selected";} ?> ><?php echo $nom; ?></option>;<?php
                }
                ?>
            </select>
            <label> demande une / des rues au joueur </label>
            <select name="Vend">
                <option value="none">Choisir un joueur</option>
                <?php
                foreach($noms as $nom)
                {
                    ?> <option value='<?php echo $nom; ?>' <?php if(isset($_POST['Vend']) && $_POST['Vend']==$nom) {echo "selected";} ?> ><?php echo $nom; ?></option>;<?php
                }
                ?>
            </select>
        </fieldset>
        <input type="submit">
    </form>

<?php elseif(isset($_POST['Achete'], $_POST['Vend']) && $_POST['Achete'] != $_POST['Vend'] && $_POST['Vend'] != "none" && $_POST['Achete'] != "none" && sizeof($_POST) ==2):

    $ruesJoueur1 = getProprieteesFromidJoueur(getIdJoueurFromNomJoueur($_POST['Achete']));
    $ruesJoueur2 = getProprieteesFromidJoueur(getIdJoueurFromNomJoueur($_POST['Vend']));
    $rues = array($ruesJoueur1, $ruesJoueur2);
    echo "<form method='post'><table><thead><td style='border: solid 1px;'>".$_POST['Achete']."</td>
    <td style='border: solid 1px;'>".$_POST['Vend']."</td></thead><tbody>";
    $numJoueur = 1;
    foreach ($rues as $ruesJoueur)
    {
        echo "<td style='border: solid 1px;'>";
        foreach ($ruesJoueur as $rue)
        {
            echo "<label>".$rue."</label><input type='checkbox' name='".$rue."'><br><br>";
        }

        if($numJoueur == 1){echo "<label>Rajouter de l'argent</label>";echo "<input type='number' name='Argent'>";}
        $numJoueur = 2;
        echo "</td>";
    }
    echo "</tbody></table>";
    echo "<input name='Achete' value='".$_POST['Achete']."' hidden>";
    echo "<input name='Vend' value='".$_POST['Vend']."' hidden>";
    echo "<input type='submit'>";
    echo "</form>";

    elseif (sizeof($_POST) > 2):

    $rues = getALlRues();
    $ruesJoueur1 = array();
    $ruesJoueur2 = array();
    $id1 = getIdJoueurFromNomJoueur($_POST['Achete']);
    $id2 = getIdJoueurFromNomJoueur($_POST['Vend']);
    foreach ($rues as $rue)
    {
        $newRue = str_replace(" ", "_", $rue);
        if(isset($_POST[$newRue]))
        {
            if (getProprietaire(getIdRueFromNomRue($rue)) == $id1)
            {
                array_push($ruesJoueur1, $rue);
            }
            elseif (getProprietaire(getIdRueFromNomRue($rue)) == $id2)
            {
                array_push($ruesJoueur2, $rue);
            }
        }
    }
    if(isset($_POST['Argent']) && $_POST['Argent']!= NULL) {
        if (getArgentFromidJoueur(getIdJoueurFromNomJoueur($_POST['Achete'])) > $_POST['Argent'])
        {
            transaction(getIdJoueurFromNomJoueur($_POST['Achete']), getIdJoueurFromNomJoueur($_POST['Vend']), $_POST['Argent']);
        }
        else
        {
        $_SESSION['error'] = "Vous n'avez pas assez d'argent";
        header("Location: error.php");
        }
    }
    foreach ($ruesJoueur1 as $rue)
    {
        donneRue(getIdRueFromNomRue($rue), $id1, $id2);
    }
    foreach ($ruesJoueur2 as $rue)
    {
        donneRue(getIdRueFromNomRue($rue), $id2, $id1);
    }
    header("Location: Monopoly.php");

    ?>


<?php
endif;
include_once ('footer.php');
?>