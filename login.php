<?php
    session_start();

    if(!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password']))
    {
        require_once 'include/db.php';
        require_once 'function.php';

        $req = $pdo->prepare('SELECT * FROM users WHERE (username= :username OR email = :username) AND confirmed_at IS NOT NULL');
        $req->execute(['username' => $_POST['username']]);
        $user = $req->fetch();
        if(password_verify($_POST['password'], $user->password))
        {
            $_SESSION['auth'] = $user;
            $_SESSION['flash']['success'] = 'Vous etes bien connecter ';
           
            if($_POST['remember'])
            {
                $remember_token = str_random(250);
                $pdo->prepare('UPDATE FROM users SET remenber_token = ? WHERE id= ?')->execute([$remember_token, $user->id]);
            
            }
            die();
            
            header('Location: account.php');
            exit();
        }
        else
        {
            $_SESSION['flash']['danger'] = 'Identifiant ou Mot de pass incorrecte';
        }
    }
?>


<?php require 'include/header.php' ?>


<h1>Se connecter</h1>


<form action="" method="POST">
    <div class="form-group">
        <label for="">Pseudo ou Email</label>
        <input type="text" name="username" class="form-control"/>
    </div>
    <div class="form-group">
        <label for="">Password <a href="forget.php">(J'ai oublier mon mots de passe)</a></label>
        <input type="password" name="password" class="form-control"/>
    </div>
    <div class="form-group">
        <label>
            <input type="checkbox" name="remember" value="1"/> Se souvenir de moi
        </label>
    </div>
    <button type="submit" class="btn btn-primary btn-s" name="">Se connecter</button>
</form>


<?php require 'include/footer.php';?>