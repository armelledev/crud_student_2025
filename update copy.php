<?php
include "database.php";

if(isset($_GET['id_student'])){
$id= $_GET['id_student']; 

$sql= "SELECT * FROM students WHERE id_student=?";

$querry=$pdoconnect->prepare($sql);
$querry->execute([$id]);
$person= $querry->fetch(PDO::FETCH_ASSOC);

if($_SERVER['REQUEST_METHOD'] === 'POST'){

 $nom = trim ($_POST['nom_student'] ?? '');
    $email = trim($_POST['email_student'] ?? '');
    $password = trim($_POST['password_student'] ?? '');
     $password_confirm = trim($_POST['password_confirm'] ?? '');
     if($password !== $password_confirm){
        echo "le mot de passe ne correspond pas";
     }

  $sql="UPDATE students SET nom_student=?, email_student=?, password_student=? WHERE id_student = ?";

  $query=$pdoconnect->prepare($sql);
  $query->execute([ $nom , $email, $password, $_GET['id']]);
  
  header('location: index.php');
  exit();

}
    
}

?>




<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MODIFIER  Étudiant </title>
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

        <h1>Mofification d'un étudiant</h1>

        <form action='' method="POST">
            <div class="form-group">
         <input type="hidden" name="id_student" value=" <?= $_GET['id_student']?>">

                <label for="nom">Nom</label>
                <input type="text" id="nom_student" name="nom_student" 
                value="<?= isset($nom) ? $nom : '' ?>"
                placeholder="Entrez le nom">
            </div>
           
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email_student" 
                 value="<?= isset($email) ? $email : '' ?>"
                placeholder="Entrez l'email">
            </div>
             <div class="form-group">
                <label for="password">password</label>
                <input type="password" id="password_student" name="password_student" 
                 value=""
                placeholder="Entrez le mot de passe">
            </div>

            <button type="submit" name="update" class="submit-btn">Modifier</button>
        </form>

    </div>
</body>

</html>