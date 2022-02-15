<?php
session_start();
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Mur</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
         <?php include('header.php');?>
        <div id="wrapper">
            <?php
            /**
             * Etape 1: Le mur concerne un utilisateur en particulier
             * La première étape est donc de trouver quel est l'id de l'utilisateur
             * Celui ci est indiqué en parametre GET de la page sous la forme user_id=...
             * Documentation : https://www.php.net/manual/fr/reserved.variables.get.php
             * ... mais en résumé c'est une manière de passer des informations à la page en ajoutant des choses dans l'url
             */
            $userId =intval($_GET['user_id']);
            ?>
            <?php
            /**
             * Etape 2: se connecter à la base de donnée
             */
            $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
            ?>

            <aside>
                <?php
                /**
                 * Etape 3: récupérer le nom de l'utilisateur
                 */                
                $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $user = $lesInformations->fetch_assoc();
                //@todo: afficher le résultat de la ligne ci dessous, remplacer XXX par l'alias et effacer la ligne ci-dessous
                ?>
                <img src="user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez tous les message de l'utilisatrice : <?php echo $user['alias'] ?>
                        (n° <?php echo $userId ?>)
                    </p>
                </section>
            </aside>
            <main>
                <?php 
                $enCoursDeTraitement = isset($_POST['message']);
                    if ($enCoursDeTraitement)
                    {
                        $authorId = $userId;
                        $postContent = $_POST['message'];


                        //Etape 3 : Petite sécurité
                        // pour éviter les injections sql : https://www.w3schools.com/sql/sql_injection.asp
                        $authorId = intval($mysqli->real_escape_string($authorId));
                        $postContent = $mysqli->real_escape_string($postContent);
                        //Etape 4 : construction de la requete
                        $lInstructionSql = "INSERT INTO posts "
                                . "(id, user_id, content, created, parent_id) "
                                . "VALUES (NULL, "
                                . $authorId . ", "
                                . "'" . $postContent . "', "
                                . "NOW()," 
                                . "NULL". ")";
                        echo $lInstructionSql;
                        // Etape 5 : execution
                        $ok = $mysqli->query($lInstructionSql);
                        if ( ! $ok)
                        {
                            echo "Impossible d'ajouter le message: " . $mysqli->error;
                        } else
                        {
                            echo "Message posté";
                        }
                    }
                    
                 $enCoursFollow = isset($_POST['follow']);
                    if ($enCoursFollow) {
                      $followSql = "INSERT INTO followers "
                                . "(id, followed_user_id, following_user_id) "
                                . "VALUES (NULL, "
                                . $userId . ", "
                                . $_SESSION["connected_id"] . ")"; 
                        echo $followSql;
                         $ok = $mysqli->query($followSql);
                        if ( ! $ok)
                        {
                            echo "L'abonnement a échoué" . $mysqli->error;
                        } else
                        {
                            echo "Vous êtes abonné";
                        }
                    }
                    
                   include('like.php');
                    
                /**
                 * Etape 3: récupérer tous les messages de l'utilisatrice
                 */
                $laQuestionEnSql = "
                    SELECT posts.content, posts.created, users.alias as author_name, posts.id as post_id,
                    COUNT(likes.id) as like_number, GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE posts.user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }

                /**
                 * Etape 4: @todo Parcourir les messsages et remplir correctement le HTML avec les bonnes valeurs php
                 */
        
               while ($post = $lesInformations->fetch_assoc())
                {
                    
                    $mytags = explode(',', $post[taglist]); 
                    ?>                
                    <article>
                        <h3>
                            <?php echo $post['created'] ?>
                        </h3>
                        <address>par <a href = "wall.php?user_id=<?php echo $userId?>"><?php echo $post['author_name'] ?></a></address>
                        <div>
                            <?php echo $post['content'] ?> </div>                                            
                        <footer>
                            <small>♥ <?php echo $post['like_number'] ?></small>
                            <form action="wall.php?user_id=<?php echo $userId?>" method="post">
                            <input type='hidden' name='like' value='true'>
                            <input type='hidden' name='post_id' value="<?php echo $post['post_id']?>">
                            <input type='submit' value='like'>
                            </form>
                            <?php for($i = 0;$i < count($mytags) ; $i++){
                                echo "<a href=''> #"
                                . $mytags[$i] .
                                "</a>";
                            }?>
                        </footer>
                    </article>
                <?php } 
                    
                if ($_SESSION["connected_id"] == $userId) {
                    ?>  
                   <form action="wall.php?user_id=<?php echo $userId?>" method="post">
                        <input type='hidden' name='???' value='achanger'>
                        <dl>
                            <dt><label for='message'>Message</label></dt>
                            <dd><textarea name='message'></textarea></dd>
                        </dl>
                        <input type='submit'>
                    </form>              
                
                <?php } else { ?>
                    <form action="wall.php?user_id=<?php echo $userId?>" method="post">
                        <input type='hidden' name='follow' value='true'>
                        <input type='submit' value='follow'>
                    </form>              
                <?php } 
                
                ?>

            </main>
        </div>
    </body>
</html>
