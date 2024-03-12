<?php
session_start();

if(!empty($_POST) && !empty($_POST['email']))
{
    require_once 'include/db.php';
    require_once 'function.php';

    $req = $pdo->prepare('SELECT * FROM users WHERE email = ?  AND confirmed_at IS NOT NULL');
    $req->execute([$_POST['email']]);
    $user = $req->fetch();
    if($user)
    {
        $reset_token = str_random(60);
        $pdo->prepare('UPDATE users SET reset_token = ?, reset_at = NOw() WHERE id = ?')->execute([$reset_token,$user->id]);   
        $_SESSION['flash']['success'] = 'Les instruction du rapppel de mot de pass vous ont ete envoyer par email';
        mail($_POST['email'], 'Reinitialisation de votre mot de passe',"Afin de reinitialiser votre mot de passe Merci de cliquer sur ce lien\n\nhttp://localhost/donnie/Gestion_inscription/reset.php?id={$user->id}&token=$reset_token");
        header('Location: login.php');
        exit();
    }
      else{
        $_SESSION['flash']['danger'] = 'Identifiant ou Mot de pass incorrecte';
    }
}
?>


<?php require 'include/header.php' ?>


<h3>Mots de passe Oublier</h3>


<form action="" method="POST">
    <div class="form-group">
        <label for="">Email</label>
        <input type="email" name="email" class="form-control"/>
    </div>
    <button type="submit" class="btn btn-primary btn-s" name="">Se connecter</button>
</form>


<?php require 'include/footer.php';?>