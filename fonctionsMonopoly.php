<?php

function initMonopoly()
{
    global $bdd;
    $SQL_query = "DROP TABLE IF EXISTS Monopoly; CREATE TABLE Monopoly (
    idJoueur INT AUTO_INCREMENT
    , argentJoueur INT
    , placeJoueur INT
    , nomJoueur VARCHAR(20)
    , lastPlayer INT(1)
    , prison INT(1)
    , cartePrison INT(1)
    , nbDoubles INT(1)
    , carteChanceTiree BOOLEAN
    , carteCaisseComTiree BOOLEAN
    , PRIMARY KEY(idJoueur)
)   ;";
    $prep = $bdd -> prepare($SQL_query);
    $prep -> execute();
    $prep -> closeCursor();
}

function remplisMonopoly($nbJoueurs)
{
    global $bdd;
    for($i=1 ; $i<=$nbJoueurs ; $i++)
    {
        if(isset($_POST['nomJoueur'.$i]))
        {
            $_GET['nbTours']=0;
            $SQL_query = "INSERT INTO Monopoly(idJoueur, argentJoueur, placeJoueur, nomJoueur, lastPlayer, carteChanceTiree, carteCaisseComTiree) VALUES (NULL, :argentJoueur, :placeJoueur, :nomJoueur, NULL, FALSE, FALSE);";
            $nomJoueur = $_POST['nomJoueur'.$i];
            $argent = 1500;
            $place = 0;
            $prep = $bdd -> prepare($SQL_query);
            $prep -> bindParam(':argentJoueur', $argent);
            $prep -> bindParam(':placeJoueur', $place);
            $prep -> bindParam(':nomJoueur', $nomJoueur);
            $prep -> execute();
        }
    }
    $SQL_query = "INSERT INTO Monopoly(idJoueur, argentJoueur, placeJoueur, nomJoueur, lastPlayer) VALUES (NULL, NULL, 0, 'NombreTours', NULL);";
    $prep = $bdd -> prepare($SQL_query);
    $prep -> execute();
    $prep -> closeCursor();
}

function initDes()
{
    global $bdd;
    $SQL_query = "DROP TABLE IF EXISTS Des; CREATE TABLE Des(
    idDes INT AUTO_INCREMENT
    , valeurDes INT(1)
    , PRIMARY KEY(idDes)
);";
    $prep = $bdd -> prepare($SQL_query);
    $prep -> execute();
    $prep -> closeCursor();
}

function remplisDes()
{
    global $bdd;
    $dé1 = 6;
    $dé2 = 1;
    $SQL_query = "INSERT INTO Des(idDes, valeurDes) VALUES(NULL, :Des1), (NULL, :Des2);";
    $prep = $bdd -> prepare($SQL_query);
    $prep -> bindParam(':Des1', $dé1);
    $prep -> bindParam(':Des2', $dé2);
    $prep -> execute();
    $prep -> closeCursor();
}

function estIci($joueur, $numCaseJoueur, $numCase)
{
    if($numCaseJoueur == $numCase)
    {
        echo"<br><img src='" . $joueur . ".png' class='pion'>";
    }
}

function getNbJoueurs()
{
    global $bdd;
    $SQL_query = "SELECT COUNT(*) FROM Monopoly;";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> execute();
    $result = $prep -> fetchAll();
    $prep -> closeCursor();
    foreach ($result as $row)
    {
        $nbJoueurs = $row['COUNT(*)']-1;
    }
    return $nbJoueurs;
}

function getInfosFromidJoueur($idJoueur)
{
    global $bdd;
    $SQL_query = "SELECT * FROM Monopoly WHERE idJoueur =:idJoueur;";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> bindParam(':idJoueur', $idJoueur);
    $prep -> execute();
    $donnees = $prep -> fetchAll();


    foreach ($donnees as $row)
    {
            $row['idJoueur'] = array(
            $row['argentJoueur'],
            $row['placeJoueur'],
            $row['nomJoueur']
        );
    }
    $prep -> closeCursor();
    return $row['idJoueur'];
}

function getDesFromidDes($de)
{
    global $bdd;
    $SQL_query = "SELECT valeurDes FROM Des WHERE idDes = :de;";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> bindParam(':de', $de);
    $prep -> execute();
    $result = $prep -> fetchAll();
    foreach ($result as $row)
    {
        return $row['valeurDes'];
    }
}

function changeDes($de1, $de2)
{
    global $bdd;
    $SQL_query = "UPDATE Des SET valeurDes = :Des1 WHERE idDes = 1; UPDATE Des SET valeurDes = :Des2 WHERE idDes = 2;";
    $prep = $bdd->prepare($SQL_query);
    $prep->bindParam(':Des1', $de1);
    $prep->bindParam(':Des2', $de2);
    $prep->execute();
    $prep -> closeCursor();
}

function getPlaceFromidJoueur($idJoueur)
{
    global $bdd;
    $SQL_query = "SELECT placeJoueur FROM Monopoly WHERE idJoueur = :id;";
    $prep = $bdd->prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> bindParam(':id', $idJoueur);
    $prep->execute();
    $result = $prep->fetchAll();
    foreach ($result as $row) {
        return $row['placeJoueur'];
    }
    $prep -> closeCursor();
}

function changePlaceFromidJoueur($place,  $idJoueur)
{
    global $bdd;
    $SQL_query = "UPDATE Monopoly SET placeJoueur = :placeJoueur WHERE idJoueur = :idJoueur;";
    $prep = $bdd->prepare($SQL_query);
    $prep -> bindParam(':idJoueur', $idJoueur);
    $prep->bindParam(':placeJoueur', $place);
    $prep->execute();
    $prep -> closeCursor();
}

function getArgentFromidJoueur($idJoueur)
{
    global $bdd;
    $SQL_query = "SELECT argentJoueur FROM Monopoly WHERE idJoueur = :idJoueur;";
    $prep = $bdd->prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> bindParam('idJoueur', $idJoueur);
    $prep->execute();
    $result = $prep->fetchAll();
    foreach ($result as $row)
    {
        return $row['argentJoueur'];
    }
}

function changeArgentFromidJoueur($argent, $idJoueur)
{
    global $bdd;
    $SQL_query = "UPDATE Monopoly SET argentJoueur = :argent WHERE idJoueur = :idJoueur;";
    $prep = $bdd -> prepare($SQL_query);
    $prep -> bindParam(':idJoueur', $idJoueur);
    $prep -> bindParam(':argent', $argent);
    $prep -> execute();
    $prep -> closeCursor();

}

function getNomJoueurFromidJoueur($id)
{
    global $bdd;
    $SQL_query = "SELECT nomJoueur FROM Monopoly WHERE idJoueur = :id;";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> bindParam(':id', $id);
    $prep -> execute();
    $result = $prep -> fetchAll();
    foreach ($result as $row)
    {
        return $row['nomJoueur'];
    }
    $prep -> closeCursor();
}

function getLastPlayer()
{
    global $bdd;
    $SQL_query = "SELECT idJoueur FROM Monopoly WHERE lastPlayer = 1";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> execute();
    $result = $prep -> fetchAll();
    foreach ($result as $row)
    {
        return $row['idJoueur'];
    }
    $prep -> closeCursor();
}

function changeLastPlayer($idJoueur)
{
    global $bdd;
    $SQL_query = "UPDATE Monopoly SET lastPlayer = 1 WHERE idJoueur = :idJoueur;";
    $prep = $bdd -> prepare($SQL_query);
    $prep -> bindParam(':idJoueur', $idJoueur);
    $prep -> execute();
    $prep -> closeCursor();
}

function resetLastPlayer()
{
    global $bdd;
    $SQL_query = "UPDATE Monopoly SET lastPlayer = NULL WHERE lastPlayer = 1;";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> execute();
    $prep -> closeCursor();
}

function getPrixRue($idRue)
{
    global $bdd;
    $SQL_query = "SELECT valeurRue FROM Rues WHERE idRue = :idRue";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> bindParam(':idRue', $idRue);
    $prep -> execute();
    $result = $prep -> fetchAll();
    foreach ($result as $row)
    {
        return $row['valeurRue'];
    }
}

function changeProprietaireRue($idRue, $idJoueur)
{
    global $bdd;
    $SQL_query = "UPDATE Rues SET id_possesseur = :idJoueur WHERE idRue = :idRue;";
    $prep = $bdd -> prepare($SQL_query);
    $prep -> bindParam(':idJoueur', $idJoueur);
    $prep -> bindParam('idRue', $idRue);
    $prep -> execute();
    $prep ->  closeCursor();
}

function getProprietaire($idRue)
{
    global $bdd;
    $SQL_query = "SELECT id_possesseur FROM Rues WHERE idRue = :idRue;";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> bindParam('idRue', $idRue);
    $prep -> execute();
    $result = $prep -> fetchAll();
    foreach ($result as $row)
    {
        return $row['id_possesseur'];
    }
}

function afficheProprietesFromIdJoueur($idJoueur)
{
    global $bdd;
    $SQL_query = "SELECT nomRue FROM Rues WHERE id_possesseur = :idJoueur ORDER BY idCouleur AND idCouleur;";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> bindParam(':idJoueur', $idJoueur);
    $prep -> execute();
    $result = $prep -> fetchAll();
    $possessions = array();
    foreach ($result as $row)
    {
        array_push($possessions, $row['nomRue']);
    }
    foreach ($possessions as $rue)
    {
        echo"<li>".$rue." : ".getLoyer(getIdRueFromNomRue($rue), $idJoueur)."€</li>";
    }
}

function getLoyer($idRue, $idJoueur)
{
    global $bdd;

    if(getHotelFromIdRue($idRue) == 1)
    {
        $SQL_query = "SELECT loyerHotel FROM Rues WHERE idRue = :idRue;";
        $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $prep -> bindParam('idRue', $idRue);
        $prep -> execute();
        $result = $prep -> fetchAll();
        foreach ($result as $row)
        {
            return $row['loyerHotel'];
        }
    }

    $nbMaisons = -1;

    if($idRue == 5 || $idRue == 15 || $idRue == 25 || $idRue == 35)
    {
        $SQL_query = "SELECT id_possesseur FROM Rues WHERE idRue = 5;";
        $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $prep -> execute();
        $result = $prep -> fetchAll();
        foreach ($result as $row)
        {
            if($row['id_possesseur'] == $idJoueur)
            {
                $nbMaisons += 1;
            }
        }
        $SQL_query = "SELECT id_possesseur FROM Rues WHERE idRue = 15;";
        $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $prep -> execute();
        $result = $prep -> fetchAll();
        foreach ($result as $row)
        {
            if($row['id_possesseur'] == $idJoueur)
            {
                $nbMaisons += 1;
            }
        }
        $SQL_query = "SELECT id_possesseur FROM Rues WHERE idRue = 25;";
        $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $prep -> execute();
        $result = $prep -> fetchAll();
        foreach ($result as $row)
        {
            if($row['id_possesseur'] == $idJoueur)
            {
                $nbMaisons += 1;
            }
        }
        $SQL_query = "SELECT id_possesseur FROM Rues WHERE idRue = 35;";
        $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $prep -> execute();
        $result = $prep -> fetchAll();
        foreach ($result as $row)
        {
            if($row['id_possesseur'] == $idJoueur)
            {
                $nbMaisons += 1;
            }
        }

    }

    elseif ($idRue == 12 || $idRue == 28)
    {
        $nbMaisons = 0;
        $SQL_query = "SELECT id_possesseur FROM Rues WHERE idRue = 12;";
        $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $prep -> execute();
        $result = $prep -> fetchAll();
        foreach ($result as $row)
        {
            if($row['id_possesseur'] == $idJoueur)
            {
                $nbMaisons += 1;
            }
        }
        $SQL_query = "SELECT id_possesseur FROM Rues WHERE idRue = 28;";
        $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $prep -> execute();
        $result = $prep -> fetchAll();
        foreach ($result as $row)
        {
            if($row['id_possesseur'] == $idJoueur)
            {
                $nbMaisons += 1;
            }
        }
        if($nbMaisons == 2)
        {
            global $de1, $de2;
            return (10 * ($de1 + $de2));
        }
        if($nbMaisons == 1)
        {
            global $de1, $de2;
            return (4 * ($de1 + $de2));
        }

    }

    else
    {
        if($nbMaisons == -1){ $nbMaisons = 0; }
        $nbMaisons = getNbMaison(getNomRueFromIdRue($idRue));
    }
    if($nbMaisons == 0)
    {
        $SQL_query = "SELECT loyerNu FROM Rues WHERE idRue = :idRue";
        $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $prep -> bindParam('idRue', $idRue);
        $prep -> execute();
        $result = $prep -> fetchAll();
        foreach ($result as $row)
        {
            $loyerNu = $row['loyerNu'];
        }
        $SQL_query = "SELECT idCouleur FROM Rues WHERE idRue = :idRue;";
        $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $prep -> bindParam('idRue', $idRue);
        $prep -> execute();
        $result = $prep -> fetchAll();
        foreach ($result as $row)
        {
            $idCouleur = $row['idCouleur'];
        }
        $SQL_query = "SELECT id_possesseur FROM Rues WHERE idCouleur = :idCouleur;";
        $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $prep -> bindParam(':idCouleur', $idCouleur);
        $prep -> execute();
        $result = $prep -> fetchAll();
        foreach ($result as $row)
        {
            if($row['id_possesseur'] != $idJoueur)
            {
                return $loyerNu;
            }
        }
        return ($loyerNu*2);
    }
    if($nbMaisons == 1)
    {
        $SQL_query = "SELECT loyer1maison FROM Rues WHERE idRue = :idRue";
        $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $prep -> bindParam('idRue', $idRue);
        $prep -> execute();
        $result = $prep -> fetchAll();
        foreach ($result as $row)
        {
            return $row['loyer1maison'];
        }
    }
    if($nbMaisons == 2)
    {
        $SQL_query = "SELECT loyer2maisons FROM Rues WHERE idRue = :idRue";
        $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $prep -> bindParam('idRue', $idRue);
        $prep -> execute();
        $result = $prep -> fetchAll();
        foreach ($result as $row)
        {
            return $row['loyer2maisons'];
        }
    }
    if($nbMaisons == 3)
    {
        $SQL_query = "SELECT loyer3maisons FROM Rues WHERE idRue = :idRue";
        $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $prep -> bindParam('idRue', $idRue);
        $prep -> execute();
        $result = $prep -> fetchAll();
        foreach ($result as $row)
        {
            return $row['loyer3maisons'];
        }
    }
    if($nbMaisons == 4)
    {
        $SQL_query = "SELECT loyer4maisons FROM Rues WHERE idRue = :idRue";
        $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $prep -> bindParam('idRue', $idRue);
        $prep -> execute();
        $result = $prep -> fetchAll();
        foreach ($result as $row)
        {
            return $row['loyer4maisons'];
        }
    }
}

function transaction($donneur, $receveur, $montant)
{
    global $bdd;
    $SQL_query = "UPDATE Monopoly SET argentJoueur = :argentDonneur WHERE idJoueur= :donneur;
                  UPDATE Monopoly SET argentJoueur = :argentReceveur WHERE idJoueur = :receveur;";
    $argentDonneur = getArgentFromidJoueur($donneur) - $montant;
    $argentReceveur = getArgentFromidJoueur($receveur) + $montant;
    $prep = $bdd -> prepare($SQL_query);
    $prep -> bindParam(':argentDonneur' , $argentDonneur);
    $prep -> bindParam(':argentReceveur' , $argentReceveur);
    $prep -> bindParam(':donneur', $donneur);
    $prep -> bindParam(':receveur', $receveur);
    $prep -> execute();
    $prep -> closeCursor();
}

function getIdRueFromNomRue($nomRue)
{
    global $bdd;
    $SQL_query = "SELECT idRue FROM Rues WHERE nomRue = '${nomRue}';";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> execute();
    $result = $prep -> fetchAll();
    $prep -> closeCursor();
    foreach ($result as $row)
    {
        return $row['idRue'];
    }
}

function isInJail($idJoueur)
{
    global $bdd;
    $SQL_query = "SELECT prison FROM Monopoly WHERE idJoueur = '${idJoueur}';";
    $prep = $bdd->prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep->execute();
    $result = $prep->fetchAll();
    $prep->closeCursor();
    foreach ($result as $row) {
        return $row['prison'];
    }
}

function changePrison($idJoueur)
{
    global $bdd;
    if(isInJail($idJoueur) != NULL)
    {
        if(getDesFromidDes(1) == getDesFromidDes(2))
        {
            $place = getDesFromidDes(1)*2 + 10;
            $SQL_query = "UPDATE Monopoly SET placeJoueur = :place WHERE idJoueur = :idJoueur; UPDATE Monopoly SET prison = NULL WHERE idJoueur= :idJoueur;";
            $prep = $bdd -> prepare($SQL_query);
            $prep -> bindParam(':idJoueur', $idJoueur);
            $prep -> bindParam(':place', $place);
            $prep -> execute();
            $prep -> closeCursor();
            return;
        }
        if(isInJail($idJoueur) == 1)
        {
            $SQL_query = "UPDATE Monopoly SET prison = NULL WHERE idJoueur = :idJoueur;";
            $prep = $bdd -> prepare($SQL_query);
            $prep -> bindParam(':idJoueur', $idJoueur);
            $prep -> execute();
            $prep -> closeCursor();
            return;
        }
            $SQL_query = "UPDATE Monopoly SET placeJoueur = 10 WHERE idJoueur = :idJoueur; UPDATE Monopoly SET prison = prison-1 WHERE idJoueur= :idJoueur;";
            $prep = $bdd -> prepare($SQL_query);
        $prep -> bindParam(':idJoueur', $idJoueur);
            $prep -> execute();
            $prep -> closeCursor();
            return;
    }
}

function putInJail($idJoueur)
{
    global $bdd;
    $SQL_query = "UPDATE Monopoly SET prison = 3 WHERE idJoueur = :idJoueur;";
    $prep = $bdd -> prepare($SQL_query);
    $prep -> bindParam(':idJoueur', $idJoueur);
    $prep -> execute();
    $prep -> closeCursor();
    $SQL_query = "UPDATE Monopoly SET placeJoueur = 10 WHERE idJoueur = :idJoueur;";
    $prep = $bdd -> prepare($SQL_query);
    $prep -> bindParam(':idJoueur', $idJoueur);
    $prep -> execute();
    $prep -> closeCursor();
}

function recupereParcGratuit($idJoueur)
{
    global $bdd;
    $SQL_query = "SELECT argentParc FROM ParcGratuit WHERE id = 1;";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> execute();
    $result = $prep -> fetchAll();
    $prep -> closeCursor();
    foreach ($result as $row)
    {
        $argentParc = $row['argentParc'];
    }
    echo getNomJoueurFromidJoueur($idJoueur)." récupère les € ".$argentParc." du parc gratuit !";
    $argentJoueur = getArgentFromidJoueur($idJoueur) + $argentParc;

    $SQL_query = "UPDATE ParcGratuit SET argentParc = 0 WHERE id = 1;";
    $prep = $bdd -> prepare($SQL_query);
    $prep -> execute();
    $prep -> closeCursor();

    changeArgentFromidJoueur($argentJoueur, $idJoueur);
}

function getArgentParcGratuit()
{
    global $bdd;
    $SQL_query = "SELECT argentParc FROM ParcGratuit WHERE id = 1;";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> execute();
    $result = $prep -> fetchAll();
    $prep -> closeCursor();
    foreach ($result as $row)
    {
        return $row['argentParc'];
    }
}

function isOnChance($idJoueur): bool
{
    global $bdd;
    $place = getPlaceFromidJoueur($idJoueur);
    if($place == 7 || $place == 22 || $place == 36)
    {
        return true;
    }
    return false;
}

function isOnCaisseCom($idJoueur): bool
{
    $place = getPlaceFromidJoueur($idJoueur);
    if($place == 2 || $place == 17 || $place == 33)
    {
        return true;
    }
    return false;
}

function tireCarteCaisseCom($idJoueur)
{
    global $bdd;
    $numCarte = random_int(1,16);
    switch ($numCarte)
    {
        case 1:
            echo "Payez à l'Hôpital € 100.";
            $SQL_query = "UPDATE Monopoly SET argentJoueur = argentJoueur - 100 WHERE idJoueur = :idJoueur; UPDATE ParcGratuit SET argentParc = argentParc + 100 WHERE id = 1;";
            $prep = $bdd -> prepare($SQL_query);
            $prep -> bindParam(':idJoueur', $idJoueur);
            $prep -> execute();
            $prep -> closeCursor();
            break;
        case 2:
            echo "Avancez jusqu'à la case Départ.";
            $SQL_query = "UPDATE Monopoly SET placeJoueur = 0 WHERE idJoueur = :idJoueur;";
            $prep = $bdd -> prepare($SQL_query);
            $prep -> bindParam(':idJoueur', $idJoueur);
            $prep -> execute();
            $prep -> closeCursor();
            $SQL_query = "UPDATE Monopoly SET argentJoueur = argentJoueur + 200 WHERE idJoueur = :idJoueur;";
            $prep = $bdd -> prepare($SQL_query);
            $prep -> bindParam(':idJoueur', $idJoueur);
            $prep -> execute();
            $prep -> closeCursor();
            break;
        case 3:
            echo "Vous êtes libéré de prison.<br>Cette carte peut être conservée jusqu'à ce qu'elle soit utilisée.";
            if(getNbCartePrison($idJoueur)==NULL) {
                $SQL_query = "UPDATE Monopoly SET cartePrison = 1 WHERE idJoueur = :idJoueur;";
            }
            else
            {
                $SQL_query = "UPDATE Monopoly SET cartePrison = cartePrison + 1 WHERE idJoueur = :idJoueur;";
            }
            $prep = $bdd->prepare($SQL_query);
            $prep -> bindParam(':idJoueur', $idJoueur);
            $prep->execute();
            $prep->closeCursor();
            break;
        case 4:
            echo "Erreur de la banque en votre faveur.<br>Recevez € 200.";
            $SQL_query = "UPDATE Monopoly SET argentJoueur = argentJoueur + 200 WHERE idJoueur = ${idJoueur};";
            $prep = $bdd -> prepare($SQL_query);
            $prep -> execute();
            $prep -> closeCursor();
            break;
        case 5:
            echo "Payez votre police d'assurance s'élevant à € 50.";
            $SQL_query = "UPDATE Monopoly SET argentJoueur = argentJoueur - 50 WHERE idJoueur = ${idJoueur}; UPDATE ParcGratuit SET argentParc = argentParc + 50 WHERE id = 1;";
            $prep = $bdd -> prepare($SQL_query);
            $prep -> execute();
            $prep -> closeCursor();
            break;
        case 6:
            echo "Retournez à Belleville.";
            $SQL_query = "UPDATE Monopoly SET placeJoueur = 1 WHERE idJoueur = ${idJoueur};";
            $prep = $bdd -> prepare($SQL_query);
            $prep -> execute();
            $prep -> closeCursor();
            break;
        case 7:
            echo 'Payez une amende de € 10 ou tirez une carte "CHANCE".';
            echo "<a href='Monopoly.php?tireCarteChance=1'><button>Tirer une carte chance</button></a><a href='Monopoly.php?paye10parc'><button>Payer € 10</button></a>";
            break;
        case 8:
            echo "Allez en prison.<br>Avancez tout droit en prison.<br>Ne passez pas par la case Départ.<br>Ne recevez pas € 200.";
            putInJail($idJoueur, $bdd);
            break;
        case 9:
            echo "Payez la note du médecin<br>€ 50.";
            $SQL_query = "UPDATE Monopoly SET argentJoueur = argentJoueur - 50 WHERE idJoueur = ${idJoueur}; UPDATE ParcGratuit SET argentParc = argentParc + 50 WHERE id = 1;";
            $prep = $bdd -> prepare($SQL_query);
            $prep -> execute();
            $prep -> closeCursor();
            break;
        case 10:
            echo "Vous héritez € 100.";
            $SQL_query = "UPDATE Monopoly SET argentJoueur = argentJoueur + 100 WHERE idJoueur = ${idJoueur};";
            $prep = $bdd -> prepare($SQL_query);
            $prep -> execute();
            $prep -> closeCursor();
            break;
        case 11:
            echo "C'est votre anniversaire :<br>Chaque joueur doit vous donner<br>€ 10.";
            for($i = 1; $i < getNbJoueurs()+1; $i++)
            {
                $SQL_query = "UPDATE Monopoly SET argentJoueur = argentJoueur - 10 WHERE idJoueur = ${i};";
                $prep = $bdd -> prepare($SQL_query);
                $prep -> execute();
                $prep -> closeCursor();
            }
            $SQL_query = "UPDATE Monopoly SET argentJoueur = :argent WHERE idJoueur = ${idJoueur};";
            $prep = $bdd -> prepare($SQL_query);
            $argent = (getNbJoueurs()*10 + getArgentFromidJoueur($idJoueur));
            $prep -> bindParam(':argent', $argent);
            $prep -> execute();
            $prep -> closeCursor();
            break;
        case 12:
            echo "Recevez votre intérêt sur l'emprunt à 7%.<br>€ 25.";
            $SQL_query = "UPDATE Monopoly SET argentJoueur = argentJoueur + 25 WHERE idJoueur = ${idJoueur};";
            $prep = $bdd -> prepare($SQL_query);
            $prep -> execute();
            $prep -> closeCursor();
            break;
        case 13:
            echo "Recevez votre revenu annuel<br>€ 100.";
            $SQL_query = "UPDATE Monopoly SET argentJoueur = argentJoueur + 100 WHERE idJoueur = ${idJoueur};";
            $prep = $bdd -> prepare($SQL_query);
            $prep -> execute();
            $prep -> closeCursor();
            break;
        case 14:
            echo "Vous avez gagné le deuxième Prix de Beauté.<br>Recevez € 10.";
            $SQL_query = "UPDATE Monopoly SET argentJoueur = argentJoueur + 10 WHERE idJoueur = ${idJoueur};";
            $prep = $bdd -> prepare($SQL_query);
            $prep -> execute();
            $prep -> closeCursor();
            break;
        case 15:
            echo "La vente de votre stock vous rapporte<br>€ 50.";
            $SQL_query = "UPDATE Monopoly SET argentJoueur = argentJoueur + 50 WHERE idJoueur = ${idJoueur};";
            $prep = $bdd -> prepare($SQL_query);
            $prep -> execute();
            $prep -> closeCursor();
            break;
        case 16:
            echo "Les Contributions vous rapportent la somme de € 20.";
            $SQL_query = "UPDATE Monopoly SET argentJoueur = argentJoueur + 20 WHERE idJoueur = ${idJoueur};";
            $prep = $bdd -> prepare($SQL_query);
            $prep -> execute();
            $prep -> closeCursor();
            break;
    }
    setCarteCaisseComTireeTrue($idJoueur);
}

function tireCarteChance($idJoueur)
{
    global $bdd;
    $numCarte = random_int(1,16);
    switch ($numCarte)
    {
        case 1:
            echo "La banque vous verse<br>un dividende de € 50.";
            $SQL_query = "UPDATE Monopoly SET argentJoueur = :argent WHERE idJoueur = :idJoueur;";
            $prep = $bdd -> prepare($SQL_query);
            $prep ->  bindParam('idJoueur', $idJoueur);
            $argent = getArgentFromidJoueur(getLastPlayer()) + 50;
            $prep -> bindParam(':argent', $argent);
            $prep -> execute();
            $prep -> closeCursor();
            break;
        case 2:
            echo "Votre immeuble et votre prêt rapportent.<br>Vous devez toucher<br>€ 150.";
            $SQL_query = "UPDATE Monopoly SET argentJoueur = :argent WHERE idJoueur = :idJoueur;";
            $prep = $bdd -> prepare($SQL_query);
            $prep ->  bindParam('idJoueur', $idJoueur);
            $argent = getArgentFromidJoueur(getLastPlayer()) + 150;
            $prep -> bindParam(':argent', $argent);
            $prep -> execute();
            $prep -> closeCursor();
            break;
        case 3:
            echo "Allez à la gare de Lyon.<br>Si vous passez par la case Départ<br>recevez € 200.";
            if(getPlaceFromidJoueur($idJoueur) >15)
            {
                $SQL_query = "UPDATE Monopoly SET argentJoueur = :argent WHERE idJoueur = :idJoueur;";
                $prep = $bdd -> prepare($SQL_query);
                $prep ->  bindParam('idJoueur', $idJoueur);
                $argent = getArgentFromidJoueur(getLastPlayer()) + 200;
                $prep -> bindParam(':argent', $argent);
                $prep -> execute();
                $prep -> closeCursor();
            }
            $SQL_query = "UPDATE Monopoly SET placeJoueur = :place WHERE idJoueur = :idJoueur;";
            $prep = $bdd -> prepare($SQL_query);
            $prep ->  bindParam('idJoueur', $idJoueur);
            $place = 15;
            $prep -> bindParam(':place', $place);
            $prep -> execute();
            $prep -> closeCursor();
            break;
        case 4:
            echo "Allez en prison.<br>Avancez tout droit en prison.<br>Ne passez pas par la case Départ.<br>Ne recevez pas € 200.";
            putInJail($idJoueur);
            break;
        case 5:
            echo "Rendez-vous à l'Avenue Henri-Martin.<br>Si vous passez par la case Départ<br>Recevez € 200.";
            if(getPlaceFromidJoueur($idJoueur) >24)
            {
                $SQL_query = "UPDATE Monopoly SET argentJoueur = :argent WHERE idJoueur = :idJoueur;";
                $prep = $bdd -> prepare($SQL_query);
                $prep ->  bindParam('idJoueur', $idJoueur);
                $argent = getArgentFromidJoueur(getLastPlayer()) + 200;
                $prep -> bindParam(':argent', $argent);
                $prep -> execute();
                $prep -> closeCursor();
            }
            $SQL_query = "UPDATE Monopoly SET placeJoueur = :place WHERE idJoueur = :idJoueur;";
            $prep = $bdd -> prepare($SQL_query);
            $prep ->  bindParam('idJoueur', $idJoueur);
            $place = 24;
            $prep -> bindParam(':place', $place);
            $prep -> execute();
            $prep -> closeCursor();
            break;
        case 6:
            global $bdd;
            echo "Vous êtes imposé pour les réparations<br>de voirie à raison de<br>€ 40 par maison et<br>€ 115 par hôtel.";
            $SQL_query = "SELECT nomRue FROM Rues WHERE id_possesseur = :idJoueur;";
            $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $prep ->  bindParam('idJoueur', $idJoueur);
            $prep -> execute();
            $result = $prep -> fetchAll();
            $prep -> closeCursor();
            $maisons = 0;
            foreach ($result as $row)
            {
                foreach ($row as $nom)
                {
                    if(getNbMaison($nom) != NULL)
                    {
                        $maisons += getNbMaison($nom);
                    }
                }
            }
            $hotels = 0;
            foreach ($result as $row)
            {
                foreach ($row as $nom)
                {
                    if(getHotelFromNomRue($nom) != NULL)
                    {
                        $hotels += getHotelFromNomRue($nom);
                    }
                }
            }
            $argent = getArgentFromidJoueur($idJoueur) - 40*$maisons - 115*$hotels;
            changeArgentFromidJoueur($argent, $idJoueur);
            echo "Vous devez payer € ".(40*$maisons + 115*$hotels).".";
            break;
        case 7:
            echo "Payez pour frais de scolarité<br>€ 150.";
            $SQL_query = "UPDATE Monopoly SET argentJoueur = :argent WHERE idJoueur = :idJoueur; UPDATE ParcGratuit SET argentParc = :argentParc WHERE id = 1;";
            $prep = $bdd -> prepare($SQL_query);
            $prep ->  bindParam('idJoueur', $idJoueur);
            $argent = getArgentFromidJoueur(getLastPlayer())-150;
            $prep -> bindParam(':argent', $argent);
            $argentParc = getArgentParcGratuit()+150;
            $prep ->  bindParam(':argentParc', $argentParc);
            $prep -> execute();
            $prep -> closeCursor();
            break;
        case 8:
            echo "Amende pour excès de vitesse :<br>€ 15.";
            $SQL_query = "UPDATE Monopoly SET argentJoueur = :argent WHERE idJoueur = :idJoueur; UPDATE ParcGratuit SET argentParc = :argentParc WHERE id = 1;";
            $prep = $bdd -> prepare($SQL_query);
            $prep ->  bindParam('idJoueur', $idJoueur);
            $argent = getArgentFromidJoueur(getLastPlayer())-15;
            $prep -> bindParam('argent', $argent);
            $argentParc = getArgentParcGratuit()+15;
            $prep -> bindParam('argentParc', $argentParc);
            $prep -> execute();
            $prep -> closeCursor();
            break;
        case 9:
            echo "Avancez au Boulevard de la Villette.<br>Si vous passez par la case Départ<br>recevez € 200.";
            if(getPlaceFromidJoueur($idJoueur) > 11)
            {
                $SQL_query = "UPDATE Monopoly SET argentJoueur = argentJoueur + 200 WHERE idJoueur = :idJoueur;";
                $prep = $bdd -> prepare($SQL_query);
                $prep ->  bindParam('idJoueur', $idJoueur);
                $prep -> execute();
                $prep -> closeCursor();
            }
            $SQL_query = "UPDATE Monopoly SET placeJoueur = 11 WHERE idJoueur = :idJoueur;";
            $prep = $bdd -> prepare($SQL_query);
            $prep ->  bindParam('idJoueur', $idJoueur);
            $prep -> execute();
            $prep -> closeCursor();
            break;
        case 10:
            echo "Amende pour ivresse :<br>€ 20.";
            $SQL_query = "UPDATE Monopoly SET argentJoueur = argentJoueur - 20 WHERE idJoueur = :idJoueur; UPDATE ParcGratuit SET argentParc = argentParc + 20 WHERE id = 1;";
            $prep = $bdd -> prepare($SQL_query);
            $prep ->  bindParam('idJoueur', $idJoueur);
            $prep -> execute();
            $prep -> closeCursor();
            break;
        case 11:
            echo "Avancez jusqu'à la case Départ.";
            $SQL_query = "UPDATE Monopoly SET placeJoueur = 0 WHERE idJoueur = :idJoueur;";
            $prep = $bdd -> prepare($SQL_query);
            $prep ->  bindParam('idJoueur', $idJoueur);
            $prep -> execute();
            $prep -> closeCursor();
            $SQL_query = "UPDATE Monopoly SET argentJoueur = argentJoueur + 200 WHERE idJoueur = :idJoueur;";
            $prep = $bdd -> prepare($SQL_query);
            $prep ->  bindParam('idJoueur', $idJoueur);
            $prep -> execute();
            $prep -> closeCursor();
            break;
        case 12:
            echo "Vous êtes libéré de prison.<br>Cette carte peut être conservée jusqu'à ce qu'elle soit utilisée.";
            if(getNbCartePrison($idJoueur)==NULL) {
                $SQL_query = "UPDATE Monopoly SET cartePrison = 1 WHERE idJoueur = :idJoueur;";
            }
            else
            {
                $SQL_query = "UPDATE Monopoly SET cartePrison = cartePrison + 1 WHERE idJoueur = :idJoueur;";
            }
            $prep = $bdd->prepare($SQL_query);
            $prep ->  bindParam('idJoueur', $idJoueur);
            $prep->execute();
            $prep->closeCursor();
            break;
        case 13:
            echo "Faites des réparations dans toutes vos maisons.<br>Versez pour chaque maison €25.<br>Versez pour chaque hôtel € 100.";
            global $bdd;
            $SQL_query = "SELECT nomRue FROM Rues WHERE id_possesseur = :idJoueur;";
            $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $prep ->  bindParam('idJoueur', $idJoueur);
            $prep -> execute();
            $result = $prep -> fetchAll();
            $prep -> closeCursor();
            $maisons = 0;
            foreach ($result as $row)
            {
                foreach ($row as $nom)
                {
                    if(getNbMaison($nom) != NULL)
                    {
                        $maisons += getNbMaison($nom);
                    }
                }
            }
            $hotels = 0;
            foreach ($result as $row)
            {
                foreach ($row as $nom)
                {
                    if(getHotelFromNomRue($nom) != NULL)
                    {
                        $hotels += getHotelFromNomRue($nom);
                    }
                }
            }
            $argent = getArgentFromidJoueur($idJoueur) - 25*$maisons - 100*$hotels;
            changeArgentFromidJoueur($argent, $idJoueur);
            echo "Vous devez payer € ".(25*$maisons + 100*$hotels).".";
            break;
        case 14:
            echo "Reculez de trois cases.";
            if(getPlaceFromidJoueur($idJoueur) > 2)
            {
                $SQL_query = "UPDATE Monopoly SET placeJoueur = (placeJoueur - 3) WHERE idJoueur = :idJoueur;";
                $prep = $bdd -> prepare($SQL_query);
                $prep ->  bindParam('idJoueur', $idJoueur);
                $prep -> execute();
                $prep -> closeCursor();
                break;
            }
            else {
                $SQL_query = "UPDATE Monopoly SET placeJoueur = (40 + placeJoueur - 3) WHERE idJoueur = :idJoueur;";
                $prep = $bdd->prepare($SQL_query);
                $prep ->  bindParam('idJoueur', $idJoueur);
                $prep->execute();
                $prep->closeCursor();
                break;
            }
        case 15:
            echo "Vous avez gagné le prix de mots croisés.<br>Recevez € 100.";
            $SQL_query = "UPDATE Monopoly SET argentJoueur = argentJoueur + 100 WHERE idJoueur = :idJoueur;";
            $prep = $bdd -> prepare($SQL_query);
            $prep ->  bindParam('idJoueur', $idJoueur);
            $prep -> execute();
            $prep -> closeCursor();
            break;
        case 16:
            echo "Rendez-vous à la Rue de la Paix.";
            $SQL_query = "UPDATE Monopoly SET placeJoueur = 39 WHERE idJoueur = :idJoueur;";
            $prep = $bdd->prepare($SQL_query);
            $prep ->  bindParam('idJoueur', $idJoueur);
            $prep->execute();
            $prep->closeCursor();
            break;
    }
    setCarteChanceTireeTrue($idJoueur);
}

function getNbCartePrison($idJoueur)
{
    global $bdd;
    $SQL_query = "SELECT cartePrison FROM Monopoly WHERE idJoueur = ${idJoueur};";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> execute();
    $result = $prep -> fetchAll();
    foreach ($result as $row)
    {
        return $row['cartePrison'];
    }
}

function utiliseCartePrison($idJoueur)
{
    global $bdd;
    if(getNbCartePrison($idJoueur)==1) {
        $SQL_query = "UPDATE Monopoly SET cartePrison = NULL WHERE idJoueur = ${idJoueur};";
    }
    else
    {
        $SQL_query = "UPDATE Monopoly SET cartePrison = cartePrison - 1 WHERE idJoueur = ${idJoueur};";
    }
    $prep = $bdd->prepare($SQL_query);
    $prep->execute();
    $prep->closeCursor();
    $SQL_query = "UPDATE Monopoly SET prison = NULL WHERE idJoueur = ${idJoueur};";
    $prep = $bdd->prepare($SQL_query);
    $prep->execute();
    $prep->closeCursor();
}

function verifCouleur($idJoueur): array
{
    global $bdd;
    $couleursMatch = array();
    if(getProprietaire(1) == getProprietaire(3) && getProprietaire(3) == $idJoueur)
    {
        array_push($couleursMatch,'rose');
    }

    if(getProprietaire(6) == getProprietaire(8) && getProprietaire(8) == getProprietaire(9) && getProprietaire(9) == $idJoueur)
    {
        array_push($couleursMatch,'bleu clair');
    }

    if(getProprietaire(11) == getProprietaire(13) && getProprietaire(13) == getProprietaire(14) && getProprietaire(14) == $idJoueur)
    {
        array_push($couleursMatch,'violet');
    }

    if(getProprietaire(16) == getProprietaire(18) && getProprietaire(18) == getProprietaire(19) && getProprietaire(19) == $idJoueur)
    {
        array_push($couleursMatch,'orange');
    }

    if(getProprietaire(21) == getProprietaire(23) && getProprietaire(23) == getProprietaire(24) && getProprietaire(24) == $idJoueur)
    {
        array_push($couleursMatch,'rouge');
    }

    if(getProprietaire(26) == getProprietaire(27) && getProprietaire(27) == getProprietaire(29) && getProprietaire(29) == $idJoueur)
    {
        array_push($couleursMatch,'jaune');
    }

    if(getProprietaire(31) == getProprietaire(32) && getProprietaire(32) == getProprietaire(34) && getProprietaire(34) == $idJoueur)
    {
        array_push($couleursMatch,'vert');
    }

    if(getProprietaire(37) == getProprietaire(39) && getProprietaire(39) == $idJoueur)
    {
        array_push($couleursMatch,'bleu foncé');
    }
    return $couleursMatch;
}

function getPlaceFromCouleur($idCouleur)
{
    global $bdd;
    $SQL_query = "SELECT nomRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, prixMaison FROM Rues WHERE idCouleur = '${idCouleur}' ORDER BY idRue;";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> execute();
    $result = $prep -> fetchAll();
    $nbMaisons = array();
    foreach ($result as $row)
    {
        array_push($nbMaisons, getNbMaison($row['nomRue']));
    }
    $mini = min($nbMaisons);
    foreach ($result as $row)
    {
        if(getNbMaison($row['nomRue']) == $mini):
        echo "<div class='buyMaison'><table><tr><td>".$row['nomRue']."</td></tr>
              <tr><td>Loyer nu : € ".$row['loyerNu']."</td></tr>
              <tr><td>1 maison : € ".$row['loyer1maison']."</td></tr>
              <tr><td>2 maisons : € ".$row['loyer2maisons']."</td></tr>
              <tr><td>3 maisons : € ".$row['loyer3maisons']."</td></tr>
              <tr><td>4 maisons : € ".$row['loyer4maisons']."</td></tr>
              <tr><td>Prix d'une maison : € ".$row['prixMaison']."</td></tr></table>";
        echo "<a href='Monopoly.php?AcheteMaison=".$row['nomRue']."'><button>Acheter une maison</button></a></div>";
        endif;
    }
}

function ajouteMaison($nomRue, $idJoueur)
{
    global $bdd;

    if(getNbMaison($nomRue)==4){
        echo "Vous avez déjà le nombre maximum de maisons !";
    }

    else
    {
        $prixMaison = getPrixMaison($nomRue);
        $argentJoueur = getArgentFromidJoueur($idJoueur) - $prixMaison;

        if ($argentJoueur > 0)
        {
            changeArgentFromidJoueur($argentJoueur, $idJoueur);

            if (getNbMaison($nomRue) == NULL) {
                $SQL_query = "UPDATE Rues SET nbMaisons = 1 WHERE nomRue = '${nomRue}';";
            } else {
                $SQL_query = "UPDATE Rues SET nbMaisons = nbMaisons + 1 WHERE nomRue = '${nomRue}';";
            }
            $prep = $bdd->prepare($SQL_query);
            $prep->execute();
            $prep->closeCursor();
            echo "Maison ajoutée !";
            return;
        }
        echo "Mais... tu n'as pas assez d'argent !";
    }
}

function getNbMaison($nomRue)
{
    global $bdd;
    $SQL_query = "SELECT nbMaisons FROM Rues WHERE nomRue = '${nomRue}'";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> execute();
    $result = $prep -> fetchAll();
    foreach ($result as $row)
    {
        return $row['nbMaisons'];
    }
}

function payeParcGratuit($argent, $idJoueur)
{
    global $bdd;
    $SQL_query = "UPDATE Monopoly SET argentJoueur = :argentJoueur WHERE idJoueur = ${idJoueur}; UPDATE ParcGratuit SET argentParc = :argentParc WHERE id = 1;";
    $prep = $bdd -> prepare($SQL_query);
    $argentJoueur = getArgentFromidJoueur($idJoueur) - $argent;
    $argentParc = getArgentParcGratuit() + $argent;
    $prep -> bindParam(':argentJoueur', $argentJoueur);
    $prep -> bindParam('argentParc', $argentParc);
    $prep -> execute();
    $prep -> closeCursor();
    echo getNomJoueurFromidJoueur($idJoueur)." a payé € ".$argent." au parc gratuit ! La cagnotte passe maintenant à : € ".$argentParc;
}

function getNomRueFromIdRue($id)
{
    global $bdd;
    $SQL_query = "SELECT nomRue FROM Rues WHERE idRue = ${id};";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> execute();
    $result = $prep -> fetchAll();
    foreach ($result as $row)
    {
        return $row['nomRue'];
    }
}

function getHotelFromIdRue($idRue)
{
    global $bdd;
    $SQL_query = "SELECT nbHotel FROM Rues WHERE idRue = '${idRue}';";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> execute();
    $result = $prep -> fetchAll();
    foreach ($result as $row)
    {
        return $row['nbHotel'];
    }
}

function getHotelFromNomRue($nomRue)
{
    global $bdd;
    $SQL_query = "SELECT nbHotel FROM Rues WHERE nomRue = '${nomRue}';";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> execute();
    $result = $prep -> fetchAll();
    foreach ($result as $row)
    {
        return $row['nbHotel'];
    }
}

function afficheMaison($idRue)
{
    if(getHotelFromIdRue($idRue)==1)
    {
        return;
    }
    $nbMaison = getNbMaison(getNomRueFromIdRue($idRue));
    for($i = 0; $i < $nbMaison; $i++)
    {
        echo "<img src='maison.jpeg' style='width: 3em; height:3em;'>";
    }
    echo "<br>";
}

function getPrixMaison($nomRue)
{
    global $bdd;
    $SQL_query = "SELECT prixMaison FROM Rues WHERE nomRue = '${nomRue}';";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> execute();
    $result = $prep -> fetchAll();
    foreach ($result as $row)
    {
        return $row['prixMaison'];
    }
}

function getNbDouble($idJoueur)
{
    global $bdd;
    $SQL_query = "SELECT nbDoubles FROM Monopoly WHERE idJoueur = ${idJoueur};";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> execute();
    $result = $prep -> fetchAll();
    foreach ($result as $row)
    {
        return $row['nbDoubles'];
    }
}

function ajouteDouble($idJoueur)
{
    global $bdd;
    $nbDouble = getNbDouble($idJoueur) + 1;
    $SQL_query = "UPDATE Monopoly SET nbDoubles = ${nbDouble} WHERE idJoueur = ${idJoueur};";
    $prep = $bdd -> prepare($SQL_query);
    $prep -> execute();
    $prep -> closeCursor();
}

function resetDouble($idJoueur)
{
    global $bdd;
    $SQL_query = "UPDATE Monopoly SET nbDoubles = NULL WHERE idJoueur = ${idJoueur};";
    $prep = $bdd -> prepare($SQL_query);
    $prep -> execute();
    $prep -> closeCursor();
}

function getRuesLibres() :array
{
    global $bdd;
    $SQL_query = "SELECT nomRue FROM Rues WHERE id_possesseur IS NULL ORDER BY idCouleur;";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> execute();
    $result = $prep -> fetchAll();
    $tab = array();
    foreach ($result as $row)
    {
        array_push($tab, $row['nomRue']);
    }
    return $tab;
}

function ajouteHotel($nomRue, $idJoueur)
{
    global $bdd;
    if(getNbMaison($nomRue)!=4)
    {
        echo "Bah... t'as pas toutes tes maison... CHEH";
        return;
    }
    $prix = getPrixHotel($nomRue);
    $argentJoueur = getArgentFromidJoueur($idJoueur) - $prix;
    if($argentJoueur < 0)
    {
        echo "Bah...t'as pas d'argent !";
        return;
    }
    if(getNbHotels($nomRue)==1)
    {
        echo "Tu possèdes déjà cet hotel !";
        return;
    }
    $SQL_query = "UPDATE Monopoly SET argentJoueur = ${argentJoueur} WHERE idJoueur = ${idJoueur}; UPDATE Rues SET nbHotel = 1 WHERE nomRue = '${nomRue}';";
    $prep = $bdd -> prepare($SQL_query);
    $prep -> execute();
    $prep -> closeCursor();
    echo "Hotel construit !<br><a href='Monopoly.php'><button>Continuer à jouer</button></a>";
}

function getPrixHotel($nomRue)
{
    global $bdd;
    $SQL_query = "SELECT prixHotel FROM Rues WHERE nomRue = '${nomRue}';";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> execute();
    $result = $prep -> fetchAll();
    foreach ($result as $row)
    {
        return $row['prixHotel'];
    }
}

function getNbHotels($nomRue)
{
    global $bdd;
    $SQL_query = "SELECT nbHotel FROM Rues WHERE nomRue = '${nomRue}';";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> execute();
    $result = $prep -> fetchAll();
    foreach ($result as $row)
    {
        return $row['nbHotel'];
    }
}

function getRuesFromCouleur($couleur) :array
{
    global $bdd;
    $SQL_query = "SELECT nomRue FROM Rues WHERE idCouleur = '${couleur}';";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> execute();
    $result = $prep -> fetchAll();
    $Rues = array();
    foreach ($result as $row)
    {
        array_push($Rues, $row['nomRue']);
    }
    return $Rues;
}

function afficheInfosHotel($nomRue)
{
    global $bdd;
    $SQL_query = "SELECT loyer4maisons, loyerHotel, prixHotel FROM Rues WHERE nomRue = '${nomRue}';";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> execute();
    $result = $prep -> fetchAll();
    foreach ($result as $row)
    {
        echo "<div class='buyHotel'><table><tr><td>".$nomRue."</td></tr>
              <tr><td>4 maisons : € ".$row['loyer4maisons']."</td></tr>
              <tr><td>1 Hotel : € ".$row['loyerHotel']."</td></tr>
              <tr><td>Prix d'un hôtel : € ".$row['prixHotel']."</td></tr></table>";
        echo "<a href='Monopoly.php?confirmHotel=".$nomRue."'><button>Acheter un hôtel</button></a></div>";
    }
}

function afficheHotel($idRue)
{
    if(getHotelFromIdRue($idRue)==1)
    {
        echo "<img src='hotel.jpeg' style='width;3em;height:3em;'><br>";
    }
}

function afficheCarte($idRue)
{
    global $bdd;
    $SQL_query = "SELECT nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel FROM Rues WHERE idRue = :id;";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> bindParam(':id', $idRue);
    $prep -> execute();
    $result = $prep -> fetchAll();
    $prep ->  closeCursor();
    foreach ($result as $row)
    {
        echo "<div class='carteMaison'><table><tr><td>".$row['nomRue']."</td></tr>
              <tr><td>1 maison : € ".$row['loyer1maison']."</td></tr>
              <tr><td>2 maisons : € ".$row['loyer2maisons']."</td></tr>
              <tr><td>3 maisons : € ".$row['loyer3maisons']."</td></tr>
              <tr><td>4 maisons : € ".$row['loyer4maisons']."</td></tr>
              <tr><td>1 Hôtel : € ".$row['loyerHotel']."</td></tr>
              <tr><td>Prix : € ".$row['valeurRue']."</td></tr>
              </table>";
    }
}

function getIdJoueurFromNomJoueur($nom)
{
    global $bdd;
    $SQL_query = "SELECT idJoueur FROM Monopoly WHERE nomJoueur = :nom;";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> bindParam(':nom', $nom);
    $prep -> execute();
    $result = $prep -> fetchAll();
    foreach ($result as $row)
    {
        return $row['idJoueur'];
    }
}

function resetCarteTiree()
{
    global $bdd;
    $SQL_query = "UPDATE Monopoly SET carteCaisseComTiree = FALSE WHERE carteCaisseComTiree = TRUE OR carteCaisseComTiree IS NULL ;UPDATE Monopoly SET carteChanceTiree = FALSE WHERE carteChanceTiree = TRUE OR carteChanceTiree IS NULL ;";
    $prep = $bdd -> prepare($SQL_query);
    $prep -> execute();
    $prep -> closeCursor();
}

function getCarteChanceTiree($idJoueur)
{
    global $bdd;
    $SQL_query = "SELECT carteChanceTiree FROM Monopoly WHERE idJoueur = :id";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> bindParam(':id', $idJoueur);
    $prep ->  execute();
    $result = $prep -> fetchAll();
    $prep -> closeCursor();
    foreach ($result as $row)
    {
        return $row['carteChanceTiree'];
    }
}

function getCarteCaisseComTiree($idJoueur)
{
    global $bdd;
    $SQL_query = "SELECT carteCaisseComTiree FROM Monopoly WHERE idJoueur = :id ;";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> bindParam(':id', $idJoueur);
    $prep ->  execute();
    $result = $prep -> fetchAll();
    $prep -> closeCursor();
    foreach ($result as $row)
    {
        return $row['carteCaisseComTiree'];
    }
}

function setCarteChanceTireeTrue($idJoueur)
{
    global $bdd;
    $SQL_query = "UPDATE Monopoly SET carteChanceTiree = TRUE WHERE idJoueur = :id ;";
    $prep = $bdd -> prepare($SQL_query);
    $prep -> bindParam(':id', $idJoueur);
    $prep -> execute();
    $prep -> closeCursor();
}

function setCarteCaisseComTireeTrue($idJoueur)
{
    global $bdd;
    $SQL_query = "UPDATE Monopoly SET carteCaisseComTiree = TRUE WHERE idJoueur = :id ;";
    $prep = $bdd -> prepare($SQL_query);
    $prep -> bindParam(':id', $idJoueur);
    $prep -> execute();
    $prep -> closeCursor();
}

function payePrison($idJoueur)
{
    global $bdd;
    $argent = getArgentFromidJoueur($idJoueur) - 50;
    changeArgentFromidJoueur($argent, $idJoueur);
    $SQL_query = "UPDATE Monopoly SET prison = NULL WHERE idJoueur = :id;";
    $prep = $bdd ->  prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> bindParam(':id', $idJoueur);
    $prep -> execute();
    $prep -> closeCursor();
}

function paye10parc($idJoueur)
{
    global $bdd;
    $argent = getArgentFromidJoueur($idJoueur) - 10;
    changeArgentFromidJoueur($argent, $idJoueur);
    $argent = getArgentParcGratuit() + 10;
    $SQL_query = "UPDATE ParcGratuit SET argentParc = :argent WHERE id = 1;";
    $prep = $bdd -> prepare($SQL_query);
    $prep -> bindParam(':argent', $argent);
    $prep -> execute();
    $prep -> closeCursor();
}

function getNomRueFromidCouleur($idCouleur)
{
    global $bdd;
    $SQL_query = "SELECT nomRue FROM Rues WHERE idCouleur = '${idCouleur}';";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> execute();
    $result = $prep -> fetchAll();
    $prep -> closeCursor();
    foreach ($result as $row)
    {
        foreach($row as $oui)
        {
            return $oui;
        }
    }
}

function getAllPlayersNames()
{
    global $bdd;
    $SQL_query = "SELECT nomJoueur FROM Monopoly WHERE idJoueur <= :nbJoueurs ORDER BY idJoueur ASC;";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $nbJoueurs = getNbJoueurs();
    $prep -> bindParam(':nbJoueurs', $nbJoueurs);
    $prep -> execute();
    $result = $prep -> fetchAll();
    $prep -> closeCursor();
    $noms = array();
    foreach ($result as $row)
    {
        foreach ($row as $nom)
        {
            array_push($noms, $nom);
        }
    }
    return $noms;
}

function getProprieteesFromIdJoueur($idJoueur):array
{
    global $bdd;
    $SQL_query = "SELECT nomRue FROM Rues WHERE id_possesseur = :idJoueur ORDER BY idCouleur ASC;";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> bindParam(':idJoueur', $idJoueur);
    $prep ->  execute();
    $result = $prep -> fetchAll();
    $prep -> closeCursor();
    $rues = array();
    foreach ($result as $row)
    {
        foreach ($row as $nomRue)
        {
            array_push($rues, $nomRue);
        }
    }
    return $rues;
}

function getALlRues():array
{
    global $bdd;
    $SQL_query = "SELECT nomRue FROM Rues;";
    $prep = $bdd -> prepare($SQL_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $prep -> execute();
    $result = $prep -> fetchAll();
    $rues = array();
    foreach ($result as $row)
    {
        foreach ($row as $rue)
        {
            array_push($rues, $rue);
        }
    }
    return $rues;
}

function donneRue($idRue, $idActuel, $idFutur)
{
    global $bdd;
    $SQL_query = "UPDATE Rues SET id_possesseur = :futur WHERE id_possesseur = :actuel AND idRue = :idRue ;";
    $prep = $bdd -> prepare($SQL_query);
    $prep -> bindParam(':futur', $idFutur);
    $prep -> bindParam(':actuel', $idActuel);
    $prep -> bindParam(':idRue', $idRue);
    $prep -> execute();
    $prep -> closeCursor();

}