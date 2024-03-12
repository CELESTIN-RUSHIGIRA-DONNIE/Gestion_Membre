<?php

if(isset($_GET['id']) && isset($_GET['token']))
{
    require 'function.php';
    require 'include/db.php';
    $req = $pdo->prepare('SELECT * FROM users WHERE id = ? AND reset_token IS NOT NULL AND reset_token = ? AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)');
    $req->execute([$_GET['id'], $_GET['token']]);
    $user = $req->fetch();
    if($user)
    {
        if(!empty($_POST))
        {
            if(!empty($_POST['password']) && $_POST['password'] == $_POST['confirm_password'])
            {
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $pdo->prepare('UPDATE users SET password = ?, reset_at = NULL, reset_token = NULL')->execute([$password]);
                session_start();
                $_SESSION['flash']['success'] = 'Votre mot de passe a bien ete modifie';
                $_SESSION['auth'] = $user;
                header('Location: account.php');
                exit();
            }
        }
    }
    else{
        session_start();
        $_SESSION['flash']['danger'] = "Ce token n'est plus valide";
        header('Location: login.php');
        exit();
    }

}
else
{
    header('Location: login.php');
    exit(); 
}
?>


<?php require 'include/header.php' ?>


<h2>Reinisialisation Mot de passe</h2>


<form action="" method="POST">

    <div class="form-group">
        <label for="">Password </label>
        <input type="password" name="password" class="form-control"/>
    </div>
    <div class="form-group">
        <label for="">Nouveau Password</label>
        <input type="password" name="confirm_password" class="form-control"/>
    </div>

    <button type="submit" class="btn btn-primary btn-s" name="">submit</button>
</form>


<?php require 'include/footer.php';?>