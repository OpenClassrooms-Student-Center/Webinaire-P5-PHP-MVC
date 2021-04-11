<?php
session_start();

$categoryFilter = $_GET['category'] ?? 0; // si on a un parametre ?category dans l'url, on le prend sinon on met 0 par défaut dans la variable
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webinaires</title>
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php">Webinaires</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01"
                aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card bg-light mb-3">
                <div class="card-body">
                    <a class="btn <?php if ($categoryFilter == 0) {
                        echo 'btn-primary';
                    } else {
                        echo 'btn-outline-primary';
                    } ?>" href="index.php">Toutes les catégories</a>
                    <?php
                    // Récuparation des catégories
                    try {
                        $bdd = new PDO('mysql:dbname=webinaires;host=127.0.0.1;charset=utf8', 'phpmyadmin', 'root');
                        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    } catch (PDOException $e) {
                        echo 'Échec lors de la connexion : ' . $e->getMessage();
                    }

                    $req = $bdd->query('SELECT * FROM categories');
                    while ($category = $req->fetch()) {
                        if ($category['id'] == $categoryFilter) {
                            ?>
                            <a href="index.php?category=<?php echo $category['id']; ?>" class="btn"
                               style="background-color: <?php echo $category['color']; ?>; color: #fff;">
                                <?php echo $category['name']; ?>
                            </a>

                        <?php } else { ?>
                            <a href="index.php?category=<?php echo $category['id']; ?>" class="btn"
                               style="color: <?php echo $category['color']; ?>; border-color: <?php echo $category['color']; ?>;">
                                <?php echo $category['name']; ?>
                            </a>

                            <?php
                        }
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>

    <table class="table table-hover table-striped">
        <thead>
        <tr>
            <th scope="col">Date</th>
            <th scope="col">Webinaire</th>
            <th scope="col">Lien</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $request = 'SELECT w.id, w.name as webinar_name, w.date, w.description, c.name as category_name, c.color, u.login FROM webinars w
        INNER JOIN categories c ON c.id = w.category
        INNER JOIN users u ON u.id = w.user '; // on construit notre requête : on récupère les webinaires, avec la catégorie et l'auteur associé dont le nom correspond à l'éventuelle recherche

        $parameters = []; // on construit notre tableau de paramètres (à mettre dans execute) en fonction des filtres

        if ($categoryFilter !== 0) { // si on a une catégorie
            $request .= "WHERE w.category = ? ";
            $parameters[] = $categoryFilter;
        }

        $request .= ' ORDER BY w.date DESC';
        // Récuparation des Webinaires
        $req = $bdd->prepare($request);
        $req->execute($parameters);

        while ($webinar = $req->fetch()) {
            ?>

            <tr>
                <td scope="row"><?php echo date('d/m/Y - h\hi', strtotime($webinar['date'])); ?></td>
                <td>
                    <a href="#" data-toggle="modal" data-target="#webinar-<?php echo $webinar['id']; ?>">
                        <?php if (strtotime($webinar['date']) > time()) { // si la date est future ?>
                            <span class="badge badge-secondary align-top">À venir</span>
                        <?php } ?>
                        <?php echo htmlspecialchars($webinar['webinar_name']); ?>
                    </a>
                    <br/>
                    <span class="badge badge-pill"
                          style="background-color: <?php echo $webinar['color']; ?>; color: #fff;"><?php echo $webinar['category_name']; ?></span>
                </td>
                <td>
                    <a href="#" class="btn btn-sm btn-primary">
                        <?php if (strtotime($webinar['date']) > time()) { // si la date est future ?>
                            Rejoindre le webinaire
                        <?php } else { ?>
                            Replay
                        <?php } ?>
                    </a>

                    <div class="modal fade" id="webinar-<?php echo $webinar['id']; ?>" tabindex="-1" role="dialog"
                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"
                                        id="exampleModalLabel"><?php echo htmlspecialchars($webinar['webinar_name']); ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h5>Description</h5>
                                    <p><?php echo htmlspecialchars($webinar['description']); ?></p>
                                    <p><em>Webinaire créé par <?php echo $webinar['login']; ?></em></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<!-- Footer -->
<footer class="bg-primary text-center text-lg-start">
    <!-- Copyright -->
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
        <a class="text-dark" href="new_webinar.php">Administration</a>
    </div>
    <!-- Copyright -->
</footer>
<!-- Footer -->

</body>
</html>