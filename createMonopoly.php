<?php
/*================================================================
=======                                                    =======
=======        Initialisation des tables                   =======
=======                                                    =======
================================================================*/

if(isset($_POST['nbJoueurs']) && $_POST['nbJoueurs'])
{
    initMonopoly();                                         //Création de la table Monopoly
    remplisMonopoly($_POST['nbJoueurs']);                   //Rentre les donnéees des joueurs

    initDes();                                              //Création de la table Des
    remplisDes();                                           //Rentre les données des dés
    header("Location: Monopoly.php");                //Redirection pour être sûr que le bouton 'jouer' apparaît
}

/*================================================================
=======                                                    =======
=======        Récupération des données des joueurs        =======
=======                                                    =======
================================================================*/

$nbJoueurs = getNbJoueurs();
$infos = array();
for($i=1 ; $i <= $nbJoueurs ; $i++)
{
    $info = getInfosFromidJoueur($i);
    array_push($infos, array(
        "idJoueur" => $i,
        "nomJoueur" => $info[2],
        "placeJoueur" => $info[1],
        "argentJoueur" => $info[0]
    ));
}

/*================================================================
=======                                                    =======
=======           Le joueur a cliqué sur "Jouer"           =======
=======                                                    =======
================================================================*/
if(empty($_GET))
{
    unset($_SESSION['prison']);
    if((getCarteCaisseComTiree(getLastPlayer()) || getCarteChanceTiree(getLastPlayer())) && !isOnChance(getLastPlayer()) && !isOnCaisseCom(getLastPlayer()))
    {
        resetCarteTiree();
        header("Location: Monopoly.php?demande=1");
    }
}
if(isset($_GET['Tours']) && $_GET['Tours'] != NULL)
{
    $de1 = getDesFromidDes(1);
    $de2 = getDesFromidDes(2);
    resetCarteTiree();

    if($de1 != $de2)
    {
        $Tours = getPlaceFromidJoueur((getNbJoueurs() + 1)) +1 ;              //Itération du nombre de tours s'il n'a pas fait de double
        changePlaceFromidJoueur($Tours, (getNbJoueurs() + 1));                //Changement du nombre de tours dans la BDD
    }

    $Tours = getPlaceFromidJoueur(getNbJoueurs()+1);                  //Récupération du nombre de tours
    $nbJoueurs = getNbJoueurs();                                              //Récupération du nombre de joueurs

    $de1 = random_int(1, 6);                                                  //Affectation d'un chiffre aléatoire au dé n° 1
    $de2 = random_int(1, 6);                                                  //Affectation d'un chiffre aléatoire au dé n° 2
    changeDes($de1, $de2);                                                    //Changement des valeurs des dés dans la Base de données


    if ($Tours % $nbJoueurs == 0)                                             //Si le nombre de tours = le nombre de joueurs
    {
        $idJoueur = $nbJoueurs;                                               //C'est au dernier joueur de jouer
    }
    else                                                                      //Sinon
    {
        $idJoueur = $Tours % $nbJoueurs;                                      //c'est au joueur N° (Tours modulo nbJoueurs)
    }

    if(getLastPlayer()!=NULL)                                                 //S'il existe un 'dernier joueur' :
    {
        resetLastPlayer();                                                    //On lui retire ce rôle
    }
    changeLastPlayer($idJoueur);                                              //Et on le donne au joueur actuel

    if($de1 == $de2)
    {
        ajouteDouble($idJoueur);
    }
    else
    {
        resetDouble($idJoueur);
    }

    $place = getPlaceFromidJoueur($idJoueur);                                 //Récupération de la place du joueur
    $place = $place + $de1 + $de2;                                            //Affectation de la nouvelle place

    if ($place > 39)                                                          //Si le n° de la place est > au nb de Cases du plateau
    {
        $place = $place - 40;                                                 //On remet le joueur au début du plateau (modulo)
        $argent = getArgentFromidJoueur($idJoueur);                           //On lui donne 200€ pour être passé
        $argent += 200;                                                       //Sur la case départ
        changeArgentFromidJoueur($argent, $idJoueur);                         //Affectation de son argent dans la BDD
    }

    changePlaceFromidJoueur($place, $idJoueur);                               //On lui affecte sa nouvelle place dans la BDD

    changePrison(getLastPlayer());                                            //Si le joueur  est en prison, on lui retire un tour à y passer
    if(getPlaceFromidJoueur(getLastPlayer()) == 30)                           //Si le joueur tombe sur 'Allez en prison' :
    {
        putInJail(getLastPlayer());                                           //On le met en prison
        $_SESSION['prison'] = "Allez en prison";
    }
    if(getNbDouble(getLastPlayer())>=3)
    {
        putInJail(getLastPlayer());
        resetDouble(getLastPlayer());
    }

    header('Location: Monopoly.php?demande=1');
}

/*================================================================
=======                                                    =======
=======Traitement fini, demande au joueur s'il veut acheter=======
=======                                                    =======
================================================================*/

if(isset($_GET['demande']) && $_GET['demande']!=NULL)
{
    $joueurAchete = getLastPlayer();                                                        //Récupération de l'id du Joueur actuel
    echo "<div style='display: none;' id='getLastPlayer'>".$joueurAchete."</div>";
    $placeAchete = getPlaceFromidJoueur($joueurAchete, );                                   //Ainsi que de sa place
    if($placeAchete == 0){$placeAchete = 40;}                                               //S'il est sur le départ, il faut mettre sa place à 40 pour la BDD

    if(getProprietaire($placeAchete) <= getNbJoueurs())                                     //S'il y a déjà un propriétaire
    {
        $joueurAchete = getLastPlayer();                                                    //Récupération de l'id du joueur actuel
        $placeAchete = getPlaceFromidJoueur($joueurAchete);                                 //    "           sa place
        $id = getProprietaire($placeAchete);                                                //Récupération de l'id du propriétaire

        if($joueurAchete != $id)
        {
            $nom = getNomJoueurFromidJoueur($joueurAchete);                                 //Récupération du nom du joueur actuel
        }
    }
}

/*================================================================
=======                                                    =======
=======    Le joueur utilise une carte Libéré de prison    =======
=======                                                    =======
================================================================*/

elseif(isset($_GET['useCard']) && $_GET['useCard']!=NULL)
{
    utiliseCartePrison(getLastPlayer());
    header("location: Monopoly.php");
}

/*================================================================
=======                                                    =======
=======            Le joueur achète la rue                 =======
=======                                                    =======
================================================================*/

elseif(isset($_GET['achete'])&&$_GET['achete']!=NULL)
{
    $joueurAchete = getLastPlayer();                                                            //Récupération du joueur actuel
    $placeAchete = getPlaceFromidJoueur($joueurAchete);                                         //Récupération de sa place
    $argentAchete = getArgentFromidJoueur($joueurAchete);                                       //Récupération de son argent
    $prixAchete = getPrixRue($placeAchete);                                                     //Récupération du prix de la rue
}

/*================================================================
=======                                                    =======
=======           Le joueur  confirme son achat            =======
=======                                                    =======
================================================================*/

if(isset($_GET['confirmAchete']) && $_GET['confirmAchete']!=NULL) {
    $joueurAchete = getLastPlayer();                                                            //Récupération de l'id du joueur actuel
    $placeAchete = getPlaceFromidJoueur($joueurAchete);                                         //Récupération de sa place
    $argentAchete = getArgentFromidJoueur($joueurAchete);                                       //Récupération de son argent
    $prixAchete = getPrixRue($placeAchete);                                                     //Récupération du prix de la rue
    $argentAchete = $argentAchete - $prixAchete;                                                //Changement de l'argent du joueur
    changeArgentFromidJoueur($argentAchete, $joueurAchete);                                     //Changement dans la BDD
    changeProprietaireRue($placeAchete, $joueurAchete);                                         //Changement du propriétaire dans la BDD
    header("Location: Monopoly.php");
}

/*================================================================
=======                                                    =======
=======           Le joueur  doit payer un autre           =======
=======                                                    =======
================================================================*/

if(isset($_GET['payeJoueur']) && $_GET['payeJoueur']!=NULL)
{
    $joueurDonne = getLastPlayer();
    $placeDonne = getPlaceFromidJoueur($joueurDonne);
    $joueurRecoit = getProprietaire($placeDonne);

    $argentTransaction = getLoyer($placeDonne, getProprietaire($placeDonne));
    header( "refresh:5;url=Monopoly.php" );
}

?>


<!--
==================================================================
=======                                                    =======
=======               Création du plateau                  =======
=======                                                    =======
==================================================================
-->
<table class="monopoly">
    <tbody>
    <tr style="background-color: black;">
        <th class="cote">Parc gratuit <?php echo "<br>Valeur : ".getArgentParcGratuit()." €"; foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 20); } ?></th> <!-- case en haut à gauche : Parc gratuit -->
        <td style="background-color: black;">
            <table class="monopoly">
                <tbody>
                <tr>
                    <th class="rouge"><?php afficheMaison(21); afficheHotel(21); ?>Avenue Matignon (€<?php echo getPrixRue(21); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 21); } ?></th>
                    <th class="noColor">Chance <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 22); } ?></th>
                    <th class="rouge"><?php afficheMaison(23); afficheHotel(23); ?>Boulevard Malesherbes (€<?php echo getPrixRue(23); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 23); } ?></th>
                    <th class="rouge"><?php afficheMaison(24);  afficheHotel(24);?>Avenue Henri-Martin (€<?php echo getPrixRue(24); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 24); } ?></th>
                    <th class="Gare">Gare du Nord (€<?php echo getPrixRue(25); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 25); } ?></th>
                    <th class="jaune"><?php afficheMaison(26); afficheHotel(26);?>Faubourg Saint-Honoré (€<?php echo getPrixRue(26); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 26); } ?></th>
                    <th class="jaune"><?php afficheMaison(27);  afficheHotel(27);?>Place de la Bourse (€<?php echo getPrixRue(27); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 27); } ?></th>
                    <th class="achetable">Compagnie de distribution des eaux (€<?php echo getPrixRue(28); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 28); } ?></th>
                    <th class="jaune"><?php afficheMaison(29); afficheHotel(29); ?>Rue la Fayette (€<?php echo getPrixRue(29); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 29); } ?></th>
                </tr>
                </tbody>
            </table>
        </td> <!-- 9 cases du haut -->
        <th class="cote">Allez en Prison <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 30); } ?></th><!-- case en haut à droite : Allez en prison -->
    </tr>
    <tr>
        <td style="background-color: black;">
            <table class="monopoly">
                <tbody>
                <tr>
                    <th class="orange"><?php afficheMaison(19);  afficheHotel(19);?>Place Pigalle (€<?php echo getPrixRue(19); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 19); } ?></th>
                </tr>
                <tr>
                    <th class="orange"><?php afficheMaison(18); afficheHotel(18); ?>Boulevard Saint-Michel (€<?php echo getPrixRue(18); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 18); } ?></th>
                </tr>
                <tr>
                    <th class="noColor">Caisse de communauté <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 17); } ?></th>
                </tr>
                <tr>
                    <th class="orange"><?php afficheMaison(16);  afficheHotel(16);?>Avenue Mozart (€<?php echo getPrixRue(16); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 16); } ?></th>
                </tr>
                <tr>
                    <th class="Gare">Gare de Lyon (€<?php echo getPrixRue(15); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 15); } ?></th>
                </tr>
                <tr>
                    <th class="violet"><?php afficheMaison(14); afficheHotel(14); ?>Rue de Paradis (€<?php echo getPrixRue(14); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 14); } ?></th>
                </tr>
                <tr>
                    <th class="violet"><?php afficheMaison(13);  afficheHotel(13);?>Avenue de Neuilly (€<?php echo getPrixRue(13); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 13); } ?></th>
                </tr>
                <tr>
                    <th class="achetable">Compagnie de distribution d'électricité (€<?php echo getPrixRue(12); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 12); } ?></th>
                </tr>
                <tr>
                    <th class="violet"><?php afficheMaison(11); afficheHotel(11); ?>Boulevard de la Villette (€<?php echo getPrixRue(11); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 11); } ?></th>
                </tr>
                </tbody>
            </table>
        </td>
        <td class="Milieu">
<?php
            if(isset($_SESSION['prison']))
            {
                echo "<div style='border-radius: 20px;margin: 1px 2px; background-color: #FF000080; padding:1em 0; text-align: center;' id='vousEtesEnPrison'><div>".$_SESSION['prison']."</div><button onclick='remove(`vousEtesEnPrison`)'>X</button></div>";
            }
            afficheCarte(getPlaceFromidJoueur(getLastPlayer()));
            $de1 = getDesFromidDes(1);                                                          //Récupération de la valeur du dé n°1
            $de2 = getDesFromidDes(2);                                                          //Récupération de la valeur du dé n°2
            echo "<img src='Dé" . $de1 . ".png' alt='".$de1."'>";                                   //Affiche l'image du dé n°1
            echo "<img src='Dé" . $de2 . ".png' alt='".$de2."'><br>";                               //Affiche l'image du dé n°2

            $nbJoueurs = getNbJoueurs();                                                            //Récupère le noubre de joueurs
            $nbTours = $nbJoueurs+1;                                                                //Variable pour connaitre le nombre de tour
            $Tours = getPlaceFromidJoueur($nbTours)+1;                                              //Récupère le nombre de tours depuis la BDD

            if(isset($_GET['demande']) && $_GET['demande']!=NULL)                                   //On demande si le joueur veut acheter une rue
            {
                $couleursPareilles = verifCouleur(getLastPlayer());                             //On regarde quelles sont les couleurs où il possède toutes les rues
                for ($i = 0; $i < sizeof($couleursPareilles); $i++)                             //Pour toutes les couleurs complètes :
                {
                    if(getPrixMaison(getNomRueFromidCouleur($couleursPareilles[$i])) < getArgentFromidJoueur(getLastPlayer())):

                        $rues = getRuesFromCouleur($couleursPareilles[$i]);
                        $maisons = false;
                        foreach ($rues as $rue)
                        {
                            if(getNbMaison($rue) != 4)
                            {
                                $maisons = true;
                            }
                        }
                        if($maisons)
                        {
                            echo "<br><a href='Monopoly.php?Maison=" . $couleursPareilles[$i] .
                                "'><button>Maison de couleur " . $couleursPareilles[$i] . "</button></a><br>";     //On propose d'acheter une maison pour une des rues
                        }
                    endif;
                    $ruesPareilles = getRuesFromCouleur($couleursPareilles[$i]);
                    $hotelDispo = true;
                    foreach ($ruesPareilles as $rue)
                    {
                        if(getNbMaison($rue) != 4 && getNbHotels($rue) != 1)
                        {
                            $hotelDispo = false;
                        }
                    }
                    foreach ($ruesPareilles as $rue)
                    {
                        if (getNbMaison($rue) == 4 && $hotelDispo && getNbHotels($rue) != 1)
                        {
                            echo "<br><a href='Monopoly.php?acheteHotel=".$rue."'><button>Hôtel : ".$rue."</button></a><br>";
                        }
                    }
                }

                if(getArgentFromidJoueur(getLastPlayer()) < 0)
                {
                    //echo "<script>alert('".getNomJoueurFromidJoueur(getLastPlayer())." passe en négatif !');</script>";
                }

                if($de1 == $de2)                                                                    //Si le joueur a fait un double
                {
                    echo "<br>Double ! Vous pourrez rejouer !<br>";                                     //Il peut rejouer
                }
                if (isOnCaisseCom(getLastPlayer()))                                                 //S'il est sur une caisse de communauté :
                {
                    if(!getCarteCaisseComTiree(getLastPlayer()))
                    {
                        tireCarteCaisseCom(getLastPlayer());                                        //Il tire une carte Caisse de communauté
                    }
                    echo "<br><a href='Monopoly.php'><button>Continuer le tour</button></a>";
                }

                elseif (isOnChance(getLastPlayer()))                                            //S'il est sur une case chance :
                {
                    if(!getCarteChanceTiree(getLastPlayer()))
                    {
                        tireCarteChance(getLastPlayer());                                           //Il tire un carte Chance
                    }
                    echo "<br><a href='Monopoly.php'><button>Continuer le tour</button></a>";
                }


                elseif (getProprietaire($placeAchete) == NULL)                                          //Si la rue où le joueur se trouve n'a pas de propriétaire :
                {
                    if(getPrixRue($placeAchete) < getArgentFromidJoueur(getLastPlayer()))
                    {
                        echo "<br>Loyer nu : " . getLoyer($placeAchete, $joueurAchete) . " €";              //Affiche le loyer nu de la rue
                        echo "<br>" . getNomJoueurFromidJoueur($joueurAchete) .
                            " : <a href='Monopoly.php?achete=1' style='margin-left: 5px;'>
                            <button>Acheter la propriété</button></a>
                            <a href='Monopoly.php'><button>Passer</button></a>";                        //Demande s'il désire acheter la rue ou pas
                    }
                    else{echo "Vous n'avez pas assez d'argent pour acheter cette rue.<br><a href='Monopoly.php'><button>Finir le tour</button></a>";}
                }
                elseif (getProprietaire($placeAchete) <= getNbJoueurs())                            //Si le propriétaire est un joueur :
                {
                    if ($joueurAchete == $id)                                                       //S'il est chez lui :
                    {
                        echo "Vous atterrissez chez Vous !<br><a href='Monopoly.php'><button>Finir le tour</button></a>";                                        //Affiche 'Vous êtes chez vous'
                    }

                    else                                                                            //S'il est chez quelqu'un d'autre :
                    {
                        echo $nom." : Vous atterrissez chez " . getNomJoueurFromidJoueur($id);      //Affiche 'Vous atterrissez chez possesseur'
                        echo "<a href='Monopoly.php?payeJoueur=1'><button>Payer " .
                            getNomJoueurFromidJoueur($id) . "</button></a>";                        //Affiche un bouton pour le payer
                    }
                }
                elseif (isInJail(getLastPlayer())==1)                                           //Si c'est son dernier tour de prison
                {
                    echo getNomJoueurFromidJoueur(getLastPlayer())." sors de prison !<br><a href='Monopoly.php'><button>Finir le tour</button></a>";         //On lui dit qu'il sort
                }
                elseif(isInJail(getLastPlayer()))                                               //Sinon :
                {
                    if(getNbCartePrison(getLastPlayer()) != NULL)                               //Si le joueur possède une/des carte(s) libéré de prison :
                    {
                        echo getNomJoueurFromidJoueur(getLastPlayer()).
                            "est en prison pour ".isInJail(getLastPlayer())." tours.";          //On affiche les tours en prison qu'il lui reste à faire
                        echo " Vous avez ".getNbCartePrison(getLastPlayer()).
                            " cartes pour sortir de prison. Voulez vous :<br>
                             <a href='Monopoly.php?useCard=1'><button>Sortir de prison</button></a>
                             <a href='Monopoly.php'><button>Rester en prison</button>";     //Demande s'il veut utiliser une carte.
                    }
                    else                                                                        //S'il ne possède pas de carte :
                    {
                        echo getNomJoueurFromidJoueur(getLastPlayer()) .
                            " est en prison pour " . isInJail(getLastPlayer()) . " tours.".
                            "<br>Vous pouvez payer € 50 pour sortir de prison : <a href='Monopoly.php?paye50prison=1'><button>Payer € 50</button></a>"
                            ."<br>Ou vous pouvez y rester : "
                            ."<a href='Monopoly.php'><button>Finir le tour</button></a>";      //Affiche le nombre de tours qu'il lui reste
                    }

                }
                elseif (getPlaceFromidJoueur(getLastPlayer()) == 20)                            //S'il tombe sur Parc Gratuit :
                {
                    recupereParcGratuit(getLastPlayer());                                       //Il récupère tout l'argent
                    echo "<br><a href='Monopoly.php'><button>Finir le tour</button></a>";
                }
                elseif (getPlaceFromidJoueur(getLastPlayer()) == 4)
                {
                    payeParcGratuit(200, getLastPlayer());
                    echo "<br><a href='Monopoly.php'><button>Finir le tour</button></a>";
                }
                elseif (getPlaceFromidJoueur(getLastPlayer()) == 38)
                {
                    payeParcGratuit(100, getLastPlayer());
                    echo "<br><a href='Monopoly.php'><button>Finir le tour</button></a>";
                }
                else                                                                            //Si rien de tout ça :
                {
                    echo getNomJoueurFromidJoueur(getLastPlayer())." ne peut rien faire";                                            //Affiche 'Joueur ne peut rien faire'
                    echo "<br><a href='Monopoly.php'><button>Finir le tour</button></a>";
                }
            }
            elseif (isset($_GET['paye50prison']))
            {
                payePrison(getLastPlayer());
                echo "Vous êtes sortis de prison !<br><a href='Monopoly.php'><button>Finir le tour</button></a>";
            }

            elseif (isset($_GET['paye10parc']))
            {
                paye10parc(getLastPlayer());
                echo "<a href='Monopoly.php'><button>Finir le tour</button></a>";
            }

            elseif(isset($_GET['Maison']) && $_GET['Maison'] != NULL)                               //S'il désire acheter une maison :
            {
                getPlaceFromCouleur($_GET['Maison']);                                               //Récupère les places des rues et propose d'y acheter une maison
                echo "<br><a href='Monopoly.php?demande=1'><button>Passer</button></a>";
            }

            elseif (isset($_GET['AcheteMaison']) && $_GET['AcheteMaison'] != NULL)                  //S'il achète une maison :
            {
                ajouteMaison($_GET['AcheteMaison'], getLastPlayer());                               //On ajoute sa maison à la bonne rue
                echo "<br><a href='Monopoly.php?demande=1&maisonAchetee=1'>
                <button>Continuer à jouer</button></a>";                                            //Sa maison est ajoutée ! bouton pour continuer son tour
            }

            elseif (isset($_GET['acheteHotel']))
            {
                afficheInfosHotel($_GET['acheteHotel']);
            }

            elseif(isset($_GET['confirmHotel']))
            {
                ajouteHotel($_GET['confirmHotel'], getLastPlayer());
            }

            elseif(isset($_GET['achete'])&&$_GET['achete']!=NULL)                                   //Si le joueur veut acheter une rue :
            {
                echo getNomJoueurFromidJoueur($joueurAchete) . " : argent restant après la transaction :" . ($argentAchete - $prixAchete) . " €";
                if ($argentAchete < $prixAchete) {
                    echo "<p>Dommage, tu n'as pas assez d'argent !!!!</p><a href='Monopoly.php'><button>Passer</button></a>";
                } else {
                    echo "<br><a href='Monopoly.php?confirmAchete=1'><button>Confirmer l'achat</button></a>" . "<a href='Monopoly.php'><button>Passer</button></a>";
                }
            }
            elseif (isset($_GET['tireCarteChance']))
            {
                tireCarteChance(getLastPlayer());                                           //Il tire un carte Chance
                echo "<br><a href='Monopoly.php?demande=1'><button>Continuer le tour</button></a>";
            }
            if(isset($_GET['payeJoueur']) && $_GET['payeJoueur']!=NULL)                             //Si le joueur est chez quelqu'un d'autre :
            {
                echo getNomJoueurFromidJoueur($joueurDonne)." doit ".$argentTransaction." € à ".getNomJoueurFromidJoueur($joueurRecoit)."<br>";
                transaction($joueurDonne, $joueurRecoit, $argentTransaction);
            }

            if(empty($_GET))                                                                        //Si $_GET est vide = Si le joueur n'a pas commencé son tour
            {
                if(getDesFromidDes(1) == getDesFromidDes(2))
                {
                    $nom = getNomJoueurFromidJoueur(getLastPlayer());
                }
                elseif ($Tours % $nbJoueurs == 0)                                             //Si le nombre de tours = le nombre de joueurs
                {
                    $nom = getNomJoueurFromidJoueur($nbJoueurs);
                }
                else                                                                      //Sinon
                {
                    $nom = getNomJoueurFromidJoueur($Tours % $nbJoueurs);
                }

                ?>
                <br>
                <a href="Monopoly.php?Tours=1"><button>Jouer !</button></a> <?php                   //C'est à lui de jouer
                echo "À ".$nom." de jouer";
                $Tours -= 1;
            }


?>
        </td>
        <td style="background-color: black;">
            <table class="monopoly">
                <tbody>
                <tr>
                    <th class="vert"><?php afficheMaison(31);  afficheHotel(31);?>Avenue de Breteuil (€<?php echo getPrixRue(31); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 31); } ?></th>
                </tr>
                <tr>
                    <th class="vert"><?php afficheMaison(32); afficheHotel(32); ?>Avenue Foch (€<?php echo getPrixRue(32); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 32); } ?></th>
                </tr>
                <tr>
                    <th class="noColor">Caisse de communauté <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 33); } ?></th>
                </tr>
                <tr>
                    <th class="vert"><?php afficheMaison(34);  afficheHotel(34);?>Boulevard des Capucines (€<?php echo getPrixRue(34); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 34); } ?></th>
                </tr>
                <tr>
                    <th class="Gare"><?php afficheMaison(35);  afficheHotel(35);?>Gare Saint-Lazare (€<?php echo getPrixRue(35); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 35); } ?></th>
                </tr>
                <tr>
                    <th class="noColor">Chance <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 36); } ?></th>
                </tr>
                <tr>
                    <th class="BLEU"><?php afficheMaison(37);  afficheHotel(37);?>Avenue des Champs-Élysées (€<?php echo getPrixRue(37); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 37); } ?></th>
                </tr>
                <tr>
                    <th class="noColor">Taxe de Luxe (€<?php echo getPrixRue(38);  afficheHotel(38);?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 38); } ?></th>
                </tr>
                <tr>
                    <th class="BLEU"><?php afficheMaison(39);  afficheHotel(39);?>Rue de la Paix (€<?php echo getPrixRue(39); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 39); } ?></th>
                </tr>
                </tbody>
            </table>
        </td> <!-- 9 cases de droite -->
    </tr>
    <tr>
        <th class="cote">Prison <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 10); } ?></th> <!-- case en bas à gauche : prison -->
        <td style="background-color: black;">                    <!-- ===================== 9 cases du bas ============================= -->
            <table class="monopoly">
                <tbody>
                <tr>
                    <th class="bleu"><?php afficheMaison(9); afficheHotel(9); ?>Avenue de la République (€<?php echo getPrixRue(9); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 9); } ?></th>
                    <th class="bleu"><?php afficheMaison(8);  afficheHotel(8);?>Rue de Courcelles (€<?php echo getPrixRue(8); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 8); } ?></th>
                    <th class="noColor">Chance <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 7); } ?></th>
                    <th class="bleu"><?php afficheMaison(6); afficheHotel(6); ?>Rue de Vaugirard (€<?php echo getPrixRue(6); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 6); } ?></th>
                    <th class="Gare">Gare Montparnasse (€<?php echo getPrixRue(5); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 5); } ?></th>
                    <th class="noColor">Impôts sur le revenu (€<?php echo getPrixRue(4); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 4); } ?></th>
                    <th class="rose"><?php afficheMaison(3); afficheHotel(3); ?>Rue Lecourbe (€<?php echo getPrixRue(3); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 3); } ?></th>
                    <th class="noColor">Caisse de communauté <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 2); } ?></th>
                    <th class="rose"><?php afficheMaison(1); afficheHotel(1); ?>Boulevard de Belleville (€<?php echo getPrixRue(1); ?>) <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 1); } ?></th>
                </tr>
                </tbody>
            </table>
        </td>
        <th class="cote">Départ <?php foreach ($infos as $joueur => $donnees){ estIci($joueur, $donnees['placeJoueur'], 0); } ?></th> <!-- case en bas à droite : Départ =========================================================== DÉPART    0 -->
    </tr>
    </tbody>
</table>
<table>
    <?php
    foreach ($infos as $donnees)
    {
        echo "<td><div style='display:inline-block; margin-top: 0; margin-right: 3%;'>";
        echo "<img src='".($donnees['idJoueur']-1).".png' style='margin:4px 10px; width: 4em; height: 4em;'>";
        echo "<div class='donneesJoueurs'>".$donnees['nomJoueur']." : ".$donnees['argentJoueur']." €</div>";
        if(isInJail($donnees['idJoueur'])!=NULL) {
            echo "<li>En prison pour " . isInJail($donnees['idJoueur']) . " tours.</li>";
        }
        echo "<ul>";
        afficheProprietesFromIdJoueur($donnees['idJoueur']);
        echo "</ul>";
        echo "</div></td>";
    }
    ?>
</table>
<?php
    $ruesLibres = getRuesLibres();
    echo "<select><option>Rues libres</option>";
    foreach ($ruesLibres as $rue)
    {
        echo "<option>".$rue."</option>";
    }
    echo "</select>";
    if(empty(getRuesLibres()))
    {
        echo "<a href='echangeCarte.php'><button>Échanger des cartes</button></a>";
    }
?>