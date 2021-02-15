<?php
if(isset($_POST['nbJoueurs'])&&$_POST['nbJoueurs']!=NULL) {
$SQL_query = "DROP TABLE IF EXISTS Rues;
CREATE TABLE Rues(
idRue INT AUTO_INCREMENT
,nomRue VARCHAR(40)
,valeurRue INT
,loyerNu INT
,loyer1maison INT
,loyer2maisons INT
,loyer3maisons INT
,loyer4maisons INT
,loyerHotel INT
,prixMaison INT
,prixHotel INT
,id_possesseur INT
,idCouleur VARCHAR(10)
,nbMaisons INT(1)
,nbHotel INT(1)
,couleurRue VARCHAR(6)
, PRIMARY KEY (idRue)
);";

    $prep = $bdd->prepare($SQL_query);
    $prep->execute();
    $prep->closeCursor();

$SQL_query ="INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'BOULEVARD DE BELLEVILLE', 60, 2, 10, 30, 90, 160, 250, 50, 50); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'CAISSE DE COMMUNAUTE', 0, 0, 0, 0, 0, 0, 0, 0, 0); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'RUE LECOURBE', 60, 4, 20, 60, 180, 320, 450, 50, 50); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'IMPOTS SUR LE REVENU', 200, 0, 0, 0, 0, 0, 0, 0, 0); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'GARE MONTPARNASSE', 200, 25, 50, 100, 200, 0, 0, 0, 0); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'RUE DE VAUGIRARD', 100, 6, 30, 90, 270, 400, 550, 50, 50); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'CHANCE', 0, 0, 0, 0, 0, 0, 0, 0, 0); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'RUE DE COURCELLES', 100, 6, 30, 90, 270, 400, 550, 50, 50); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'AVENUE DE LA REPUBLIQUE', 120, 8, 40, 100, 300, 450, 600, 50, 50); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'PRISON', 0, 0, 0, 0, 0, 0, 0, 0, 0); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'BOULEVARD DE LA VILLETTE', 140, 10, 50, 150, 450, 625, 750, 100, 100); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'COMPAGNIE DE DISTRIBUTION D ELECTRICITE', 150, 0, 0, 0, 0, 0, 0, 0, 0); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'AVENUE DE NEUILLY', 140, 10, 50, 150, 450, 625, 750, 100, 100); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'RUE DE PARADIS', 160, 12, 60, 180, 500, 700, 900, 100, 100); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'GARE DE LYON', 200, 25, 50, 100, 200, 0, 0, 0, 0); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'AVENUE MOZART', 180, 14, 70, 200, 550, 750, 950, 100, 100); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'CAISSE DE COMMUNAUTE', 0, 0, 0, 0, 0, 0, 0, 0, 0); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'BOULEVARD SAINT-MICHEL', 180, 14, 70, 200, 550, 750, 950, 100, 100); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'PLACE PIGALLE', 200, 16, 80, 220, 600, 800, 1000, 100, 100); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'PARC GRATUIT', 0, 0, 0, 0, 0, 0, 0, 0, 0); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'AVENUE MATIGNON', 220, 18, 90, 250, 700, 875, 1050, 150, 150); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'CHANCE', 0, 0, 0, 0, 0, 0, 0, 0, 0); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'BOULEVARD MALESHERBES', 220, 18, 90, 250, 700, 875, 1050, 150, 150); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'AVENUE HENRI-MARTIN', 240, 20, 100, 300, 750, 925, 1100, 150, 150); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'GARE DU NORD', 200, 25, 50, 100, 200, 0, 0, 0, 0); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'FAUBOURG SAINT-HONORE', 260, 22, 110, 330, 800, 975, 1150, 150, 150); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'PLACE DE LA BOURSE', 260, 22, 110, 330, 800, 975, 1150, 150, 150); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'COMPAGNIE DE DISTRIBUTION DES EAUX', 150, 0, 0, 0, 0, 0, 0, 0, 0); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'RUE LA FAYETTE', 280, 24, 120, 360, 850, 1025, 1200, 150, 150); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'ALLEZ EN PRISON', 0, 0, 0, 0, 0, 0, 0, 0, 0); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'AVENUE DE BRETEUIL', 300, 26, 130, 390, 900, 1100, 1275, 200, 200); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'AVENUE FOCH', 300, 26, 130, 390, 900, 1100, 1275, 200, 200); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'CAISSE DE COMMUNAUTE', 0, 0, 0, 0, 0, 0, 0, 0, 0); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'BOULEVARD DES CAPUCINES', 320, 28, 150, 450, 1000, 1200, 1400, 200, 200); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'GARE SAINT-LAZARE', 200, 25, 50, 100, 200, 0, 0, 0, 0); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'CHANCE', 0, 0, 0, 0, 0, 0, 0, 0, 0); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'AVENUE DES CHAMPS-ELYSEES', 350, 35, 175, 500, 1100, 1300, 1500, 200, 200); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'TAXE DE LUXE', 100, 0, 0, 0, 0, 0, 0, 0, 0); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'RUE DE LA PAIX', 400, 50, 200, 600, 1400, 1700, 2000, 200, 200); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
                        NULL, 'DEPART', 0, 0, 0, 0, 0, 0, 0, 0, 0); INSERT INTO Rues(idRue, nomRue, valeurRue, loyerNu, loyer1maison, loyer2maisons, loyer3maisons, loyer4maisons, loyerHotel, prixMaison, prixHotel) VALUES(
);
                      
";

    $prep = $bdd -> prepare($SQL_query);
    $prep ->  execute();
    $prep ->closeCursor();

$SQL_query = "UPDATE Rues SET id_possesseur = 999 WHERE nomRue = 'CAISSE DE COMMUNAUTE';
              UPDATE Rues SET id_possesseur = 999 WHERE nomRue = 'CHANCE';
              UPDATE Rues SET id_possesseur = 999 WHERE nomRue = 'TAXE DE LUXE';
              UPDATE Rues SET id_possesseur = 999 WHERE nomRue = 'IMPOTS SUR LE REVENU';
              UPDATE Rues SET id_possesseur = 999 WHERE nomRue = 'PARC GRATUIT';
              UPDATE Rues SET id_possesseur = 999 WHERE nomRue = 'PRISON';
              UPDATE Rues SET id_possesseur = 999 WHERE nomRue = 'ALLEZ EN PRISON';
              UPDATE Rues SET id_possesseur = 999 WHERE nomRue = 'DEPART';";
    $prep = $bdd -> prepare($SQL_query);
    $prep ->  execute();
    $prep ->closeCursor();

    $SQL_query = "UPDATE Rues SET idCouleur = 'rose' WHERE idRue = 1; UPDATE Rues SET idCouleur = 'rose' WHERE idRue = 3;
                  UPDATE Rues SET idCouleur = 'bleu clair' WHERE idRue = 6; UPDATE Rues SET idCouleur = 'bleu clair' WHERE idRue = 8; UPDATE Rues SET idCouleur = 'bleu clair' WHERE idRue = 9;
                  UPDATE Rues SET idCouleur = 'violet' WHERE idRue = 11; UPDATE Rues SET idCouleur = 'violet' WHERE idRue = 13; UPDATE Rues SET idCouleur = 'violet' WHERE idRue = 14;
                  UPDATE Rues SET idCouleur = 'orange' WHERE idRue = 16; UPDATE Rues SET idCouleur = 'orange' WHERE idRue = 18; UPDATE Rues SET idCouleur = 'orange' WHERE idRue = 19;
                  UPDATE Rues SET idCouleur = 'rouge' WHERE idRue = 21; UPDATE Rues SET idCouleur = 'rouge' WHERE idRue = 23; UPDATE Rues SET idCouleur = 'rouge' WHERE idRue = 24; 
                  UPDATE Rues SET idCouleur = 'jaune' WHERE idRue = 26; UPDATE Rues SET idCouleur = 'jaune' WHERE idRue = 27; UPDATE Rues SET idCouleur = 'jaune' WHERE idRue = 29;
                  UPDATE Rues SET idCouleur = 'vert' WHERE idRue = 31; UPDATE Rues SET idCouleur = 'vert' WHERE idRue = 32; UPDATE Rues SET idCouleur = 'vert' WHERE idRue = 34;
                  UPDATE Rues SET idCouleur = 'bleu foncé' WHERE idRue = 37; UPDATE Rues SET idCouleur = 'bleu foncé' WHERE idRue = 39;
                  UPDATE Rues SET idCouleur = 'gare' WHERE idRue = 5; UPDATE Rues SET idCouleur = 'gare' WHERE idRue = 15; UPDATE Rues SET idCouleur = 'gare' WHERE idRue = 25; UPDATE Rues SET idCouleur = 'gare' WHERE idRue = 35;";
    $prep = $bdd -> prepare($SQL_query);
    $prep ->  execute();
    $prep ->closeCursor();

        $SQL_query = "UPDATE Rues SET couleurRue = 'CC33CC' WHERE idRue = 1 OR idRue = 3;
                  UPDATE Rues SET couleurRue = '1ab3fe' WHERE idRue = 6 OR idRue = 8 OR idRue = 9;
                  UPDATE Rues SET couleurRue = '660099' WHERE idRue = 11 OR idRue = 13 OR idRue = 14;
                  UPDATE Rues SET couleurRue = 'FF6633' WHERE idRue = 16 OR idRue = 18 OR idRue = 19;
                  UPDATE Rues SET couleurRue = 'EE0000' WHERE idRue = 21 OR idRue = 23 OR idRue = 24;
                  UPDATE Rues SET couleurRue = 'FFCC33' WHERE idRue = 26 OR idRue = 27 OR idRue = 29;
                  UPDATE Rues SET couleurRue = '3F745A' WHERE idRue = 31 OR idRue = 32 OR idRue = 34;
                  UPDATE Rues SET couleurRue = '08209B' WHERE idRue = 37 OR idRue = 39;";
    $prep = $bdd -> prepare($SQL_query);
    $prep ->  execute();
    $prep ->closeCursor();
}