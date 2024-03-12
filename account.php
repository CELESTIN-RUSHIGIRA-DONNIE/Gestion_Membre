<?php 
require 'function.php';
logged_only();

if(!empty($_POST))
{
    if(empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm'])
    {
        $_SESSION['flash']['danger'] = "Les deux mots de passes ne correspondent pas";
    }
    else
    {
        $user_id = $_SESSION['auth']->id;
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        require_once 'include/db.php';
        $pdo->prepare('UPDATE users SET password =?')->execute([$password]);
        $_SESSION['flash']['success'] = "Votre mot de passw a bien ete mis a jour";

    }
}

require 'include/header.php'
?>


<h1>Bienvenue <?= $_SESSION['auth']->username; ?></h1>
<form action="" method="POST">
    <div class="form-group">
        <input type="password" class="form-control" name="password" placeholder="changer ton mot de passe">
    </div>
    <div class="form-group">
        <input type="password" class="form-control" name="password_confirm" placeholder="confirmation du mot de passe">
    </div>
    <button class="btn btn-primary">Ok</button>
</form>



<?php require 'include/footer.php';?>