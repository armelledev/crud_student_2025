<?php
include "database.php";

$error = [];
$id_student = $_GET['id_student'] ?? '';
$query="SELECT * FROM students WHERE id_student=? ";
$req=$pdoconnect->prepare($query);
$req->execute([$id_student]);
$student =$req->fetch();
if(!$student){
    die("etudiant introuvable");
}
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])){
 
    $nom = trim ($_POST['nom_student'] ?? '');
    $email = trim($_POST['email_student'] ?? '');
    $password = trim($_POST['password_student'] ?? '');
    $password_confirm = trim($_POST['password_confirm'] ?? '');
    //validation du nom
    if(empty($nom)){
        $error['nom_student'] = 'le nom est obligatoire';
    }elseif(strlen($nom) < 3){
         $error['nom_student'] = "le nom doit containir aumoins 3 caracters";
    }elseif(preg_match('/^\d/',$nom)){
             $error['nom_student'] = "le nom doit pas commencer par un chiffre";
    }else{ 
        $query="SELECT * FROM students WHERE nom_student=? AND id_student !=?";
        $req =$pdoconnect->prepare($query);
        $req->execute([$nom, $id_student]);
        $student=$req->fetch();
        if($student){
           $error['nom_student']= "le nom existe deja"; 
        }


    }
    //validation du email
if(empty($email)){
     $error['email_student'] ="l'email est obligatoire";

}elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $error['email_student']= "l'email nes pas valider";
}else{ 
        $query="SELECT * FROM students WHERE email_student=? AND id_student !=? ";
        $req =$pdoconnect->prepare($query);
        $req->execute([$email, $id_student]);
        $student=$req->fetch();
        if($student){
           $error['email_student']= "l'email existe deja"; 
        }

 //validation du password

    }
if(!empty($password) && strlen($password) <8){
    $error['password_student'] ="le mot de passe doit containir aumoins 8 caracter";

}elseif ($password !== $password_confirm) {
 $error['password_student'] = "le mot de passe doit contenir au moins 8 caracteres";

}

if(empty($error)){
  $sql ="UPDATE students SET nom_student=?, email_student= ?" . (!empty($password) ? ",
  password_student = ?": "") . " WHERE id_student = ?";

  $query=$pdoconnect->prepare($sql);
  $params= [$nom, $email];
  if(!empty($password)){
    $params[] = password_hash($password, PASSWORD_DEFAULT );
  }
    $params[] = $id_student;
    $query->execute($params);
    header("location: index.php");
    exit();

  
  
}

}


?>




<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création Étudiant | Minimaliste</title>
    <style>
        :root {
            --primary: #7B2CBF;
            --text: #2D3748;
            --border: #E2E8F0;
            --bg: #F8FAFC;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background: var(--bg);
            display: grid;
            place-items: center;
            min-height: 100vh;
            padding: 1rem;
            line-height: 1.5;
        }

        .form-container {
            background: white;
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        h1 {
            color: var(--primary);
            font-size: 1.5rem;
            font-weight: 500;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            color: var(--text);
            font-size: 0.875rem;
            margin-bottom: 0.375rem;
        }

        input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: 0.375rem;
            font-size: 0.9375rem;
            transition: border 0.2s;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
        }

        input::placeholder {
            color: #A0AEC0;
        }

        .submit-btn {
            width: 100%;
            padding: 0.875rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 0.375rem;
            font-size: 0.9375rem;
            font-weight: 500;
            margin-top: 0.5rem;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .submit-btn:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>
    <div class="form-container">

        <a href="index.php">
            <button class="submit-btn">
                Retour
            </button>
        </a>

        <h1>Modification d'un étudiant</h1>

        <form action='' method="POST">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom_student" 

                value="<?= isset($nom) ? $nom  : $student['nom_student']?>"
                placeholder="Entrez le nom">
                <?php if(isset($error['nom_student'])): ?>
                    <p style='color:red;'><?= $error['nom_student'] ?></p>
                    <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email_student"
                value="<?= isset($email) ? $email : $student['email_student']?>"
                placeholder="Entrez l'email">

                 <?php if(isset($error['email_student'])): ?>
                    <p style='color:red;'><?= $error['email_student'] ?></p>
                    <?php endif; ?>
            </div>
           
            <div class="form-group">
                <label for="prenom">password</label>
                <input type="password" id="prenom"
                
                name="password_student" placeholder="Entrez le password">
                
                 <?php if(isset($error['password_student'])): ?>
                    <p style='color:red;'><?= $error['password_student'] ?></p>
                    <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password_confirm">password confirm</label>
                <input type="password" id="password_confirm"
                
                name="password_confirm" placeholder="confirmer le password">
    
            </div>
            

            <button type="submit" name="update" class="submit-btn">Modifier</button>
        </form>

    </div>
</body>

</html>