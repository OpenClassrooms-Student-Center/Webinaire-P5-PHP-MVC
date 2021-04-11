<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
}

try {
    $bdd = new PDO('mysql:dbname=webinaires;host=127.0.0.1;charset=utf8', 'phpmyadmin', 'root');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Échec lors de la connexion : ' . $e->getMessage();
}

if (isset($_POST['name']) && isset($_POST['category']) && isset($_POST['link']) && isset($_POST['date'])) {
    $req = $bdd->prepare('INSERT INTO webinars (name, description, category, user, date, link) VALUES (?, ?, ?, ?, ?, ?)');
    $req->execute([$_POST['name'], $_POST['description'] ?? '', $_POST['category'], $_SESSION['user'], $_POST['date'], $_POST['link']]);

    header('Location: index.php');
}
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

<div class="container mt-5 mb-5">
    <form class="mx-auto w-50" method="POST">
        <h1 class="h3 mb-3 font-weight-normal">Ajouter un webinaire</h1>
        <p><?php echo $error; ?></p>
        <div class="row mt-2 mb-2">
            <div class="col-12">
                <label for="name">Nom du webinaire</label>
                <input type="text" id="name" name="name" class="form-control" required autofocus>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-6">
                <label for="category">Catégorie</label>
                <select id="category" name="category" class="form-control" required>
                    <?php
                    $req = $bdd->query('SELECT * FROM categories');
                    while ($category = $req->fetch()) { ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-6">
                <label for="date">Date</label>
                <input type="datetime-local" name="date" id="date" class="form-control" required>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-12">
                <label for="link">Lien du webinaire</label>
                <input type="text" name="link" id="link" class="form-control" required>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-12">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Créer le webinaire</button>
    </form>
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