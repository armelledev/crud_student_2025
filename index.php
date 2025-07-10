<?php
include 'database.php';


// initialiser la variable de la recherche
$search =isset($_GET['search']) ? $_GET['search'] : '' ;


// requet sql avec filtre de recherche
$sql = 'SELECT * FROM students';

if(!empty($_GET['search'])){
    $sql .=' WHERE nom_student LIKE :search OR  email_student LIKE :search' ;
}
  
//  2-2 preparation de la recherche
$query=$pdoconnect->prepare($sql);

// 2-3 lie le parametre
if(!empty($search)){

    $query->bindvalue(':search', '%' .$search. '%', PDO::PARAM_STR);
}

// 2-4 Execute la requet
$query->execute();
$students =$query->fetchAll(PDO::FETCH_ASSOC);


?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Tableau de Gestion - Thème Violet</title>
</head>

<body>
    <div class="data-container">
        <div class="header-actions">
            <form action="" method="GET">
            <h2>Liste des Étudiants</h2>
            <div class="search-create-container">
                <input type="text" name="search" class="search-box" value="<?= $search ?>"
                placeholder="Rechercher un étudiant...">
    
                <button type="submit" class="btn-create">search</button>
                <a href="index.php" class="btn-create">refresh</a>
                <a href="create.php" class="btn-create">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14"></path>
                        <path d="M5 12h14"></path>
                    </svg>
                    Créer un nouvel étudiant
                </a>
            </div>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($students as $student) :?>

                <tr>
            <td><?= $student['id_student'] ?></td>
            <td><?= $student['nom_student'] ?></td>
            <td><?= $student['email_student'] ?></td>
            
               
                     <td>
                    
                        <div class="action-buttons">
                            <a href="update.php?id_student=<?= $student['id_student'] ;?>">
                                <button class="btn btn-edit">
                                    <svg width="14" height="14"
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                    Éditer
                                </button>
                            </a>


                            <a href="delete.php?id_student=<?= $student['id_student'] ;?>"
                            onclick="return confirm('vous ete sue de vouloir supprimmer?')"
                            >
                                <button class="btn btn-delete">
                                    <svg width="14" height="14">
                                        <path d="M3 6h18"></path>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                    Supprimer
                                </button>
                            </a>

                        </div>
                    </td>
                </tr>
      <?php endforeach ?>
            </tbody>
        </table>
    </div>
</body>

</html>