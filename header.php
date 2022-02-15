<!doctype html>
   
   <header>
            <?php $jeSuisConnecte = isset ($_SESSION["connected_id"]);?>
            <a href='admin.php'><img src="resoc.jpg" alt="Logo de notre réseau social"/></a>
            <nav id="menu">
                <a href="news.php">Actualités</a>
                <a href="wall.php?user_id=5">Mur</a>
                <a href="tags.php?tag_id=1">Mots-clés</a>
                <?php if($jeSuisConnecte) { ?>
                <a href="feed.php?user_id=5">Flux</a>
                <a href="logout.php">Se déconnecter</a>
                <?php }; ?> 
            </nav>
            <nav id="user">
            <?php 
            if(!$jeSuisConnecte){
                 ?> <a href = "login.php">Se connecter</a>
                 <?php 
            } else { ?>
                <a href="#">▾ Profil</a>
                <ul>
                    <li><a href="settings.php?user_id=5">Paramètres</a></li>
                    <li><a href="followers.php?user_id=5">Mes suiveurs</a></li>
                    <li><a href="subscriptions.php?user_id=5">Mes abonnements</a></li>
                </ul>
                  <?php } ?>            
            </nav>
    </header>