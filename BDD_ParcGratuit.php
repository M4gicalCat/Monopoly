<?php
if(isset($_POST['nbJoueurs']) && $_POST['nbJoueurs']!=NULL)
{
    $SQL_query =
   "DROP TABLE IF EXISTS ParcGratuit;
    CREATE TABLE ParcGratuit(
    id INT AUTO_INCREMENT,
    argentParc INT(5),
    PRIMARY KEY(id)
    );";
    $prep = $bdd -> prepare($SQL_query);
    $prep -> execute();
    $prep ->closeCursor();

    $SQL_query = "INSERT INTO ParcGratuit VALUES(NULL, 0);";
    $prep = $bdd -> prepare($SQL_query);
    $prep -> execute();
    $prep ->closeCursor();
}