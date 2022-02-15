 <?php 
 $enCoursLike = isset($_POST['like']);
                    if ($enCoursLike) {
                        $jeSuisConnecte = isset ($_SESSION["connected_id"]);
                        if ($jeSuisConnecte) {
                            // verifier si deja like
                            $likedPost = intval($mysqli->real_escape_string($_POST['post_id']));
                            $dejaLike = "SELECT * FROM likes 
                            WHERE user_id = " . $_SESSION["connected_id"] . " AND post_id = " . $_POST["post_id"] . ";";
                            // si deja like 
                            $dejaLikeResult = $mysqli->query($dejaLike);
                            $bidon = $dejaLikeResult->fetch_assoc();
                            //if (in_array($_SESSION["connected_id"], $bidon)) {
                            //    echo "blabla"; 
                            // };
                            // echo "<pre> OKKK" . print_r($bidon) . "</pre>";
                            
                            if (!$bidon) {
                                $likeSql = "INSERT INTO likes "
                               . "(id, user_id, post_id) "
                               . "VALUES (NULL, "
                               . $_SESSION["connected_id"] . ", "
                               . $likedPost . ")"; 
                               $ok = $mysqli->query($likeSql);
                                if ( ! $ok) {
                                        echo "Votre 'like' n'a pas été pris en compte" . $mysqli->error;
                                    } else {
                                        echo "Vous avez liké ce post";
                                    };
                            } else {
                                $unlikeSql = "DELETE FROM likes
                                        WHERE post_id = " . $likedPost . " AND user_id = " . $_SESSION["connected_id"] . ";"; 
                                        $ok = $mysqli->query($unlikeSql);
                            } 
                        } else {
                           echo "Connectez-vous !";
                        }
                    }
					?> 