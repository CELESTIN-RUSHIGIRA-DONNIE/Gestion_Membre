<?Php 
    require 'include/header.php';
?>

<?php
    if(!empty($_POST)){

        $errors = array();

        require_once 'include/db.php';

        if(empty($_POST['username']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])){
            $errors['username'] = "Votre Pseudo n'est pas valide (Alphanumerique)";
        }else
        {
            $req = $pdo->prepare('SELECT id FROM users WHERE username = ?');
            $req -> execute([$_POST['username']]);
            $user = $req->fetch();
            if($user){
                $errors['username'] = 'CE pseudo est deja pris';
            }
            
        }
        if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $errors['email'] = "Votre email n'est pas valide";
        }
        else
        {
            $req = $pdo->prepare('SELECT id FROM users WHERE email = ?');
            $req -> execute([$_POST['email']]);
            $user = $req->fetch();
            if($user){
                $errors['email'] = 'Cet email est deja pris';
            }
            
        }
        if(empty($_POST['password']) || $_POST['password'] != $_POST['confirmPassword']){
            $errors['password'] = "Vous devez entrez un mot de passe valide";
        }

        if(empty($errors)){
            
            $req= $pdo->prepare("INSERT INTO users SET username = ?, email = ?, password = ?, confirmation_token = ?");
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            
            $token = str_random(60);
            //debug($token);
            $req->execute([$_POST['username'], $_POST['email'], $password, $token]);
            $user_id = $pdo->lastInsertId();
            mail($_POST['email'], 'confirmation de votre compte',"Afin de valider votre compter Merci de cliquer sur ce lien\n\nhttp://localhost/donnie/Gestion_inscription/confirm.php?id=$user_id&token=$token");
            header('location: login.php');
            exit();
        }
    } 
?>

    <h1>S'inscrire</h1>

    <?php if(!empty($errors)): ?>
        <div class="alert alert-danger">
            <p>Verifier bien vos donnees</p>
            <?php foreach($errors as $error): ?>
                <ul><li><?= $error; ?></li></ul>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label for="">Pseudo</label>
            <input type="text" name="username" class="form-control"/>
        </div>
        <div class="form-group">
            <label for="">Email</label>
            <input type="email" name="email" class="form-control"/>
        </div>
        <div class="form-group">
            <label for="">Password</label>
            <input type="password" name="password" class="form-control"/>
        </div>
        <div class="form-group">
            <label for="">Confirm Password</label>
            <input type="password" name="confirmPassword" class="form-control"/>
        </div>
        <button type="submit" class="btn btn-primary btn-s" name="">S'incrire</button>
    </form>

<?Php 
    require 'include/footer.php';
?>