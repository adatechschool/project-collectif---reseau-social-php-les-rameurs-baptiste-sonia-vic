<?php


function findTags($message){
   $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
   if (strstr($message, "#")) {
    $message = explode(" ", $message);
    for($i = 0; $i < count($message); $i++) {
       if (strstr($message[$i], "#")) {
            $tag = substr($message[$i], 1);
            echo $tag;
            $addTagSql = "INSERT INTO tags "
                                . "(id, label) "
                                . "VALUES (NULL, "
                                . "'" . $tag . "');"; 
           // echo $addTagSql; 
            $thisok = $mysqli->query($addTagSql);
                        if ( ! $thisok)
                        {
                            echo "Impossible d'ajouter le tag: " . $mysqli->error;
                        } else
                        {
                            echo "Tag ajoute";
                        }
            // $gettagID = "SELE" 
          //  $addTagPost = "INSERT INTO posts_tags "
          //                      . "(id, post_id, tag_id)"
           }
       }
   }
}

?>