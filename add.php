<?php
require 'includes/_database.php';
session_start();
$_SESSION['token'] = md5(uniqid(mt_rand(), true));
require 'includes/_header.php'
?>



<div class="container">
    <section class="card mb-4 rounded-3 shadow-sm">
        <div class="card-header py-3">
            <h1 class="my-0 fw-normal fs-4">Ajouter une opération</h1>
        </div>
        <?php
        if (array_key_exists('transac_msg', $_GET)) {
            echo '<p class="task-info">' . $_GET['transac_msg'] . '</p>';
        }
        ?>
        <div class="card-body">
            <form action="add_transaction.php" method="POST" class="form-submit">
                <div class="mb-3">
                    <label for="name" class="form-label">Nom de l'opération *</label>
                    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?? '' ?>" id="token-csrf">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Opération" required>
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Date *</label>
                    <input type="date" class="form-control" name="date" id="date" required>
                </div>
                <div class="mb-3">
                    <label for="amount" class="form-label">Montant *</label>
                    <div class="input-group">
                        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?? '' ?>" id="token-csrf">
                        <input type="text" class="form-control" name="amount" id="amount" required>
                        <span class="input-group-text">€</span>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Catégorie</label>
                    <select class="form-select" name="category" id="category">
                        <option value="0" selected>Aucune catégorie</option>
                        <option value="1">Nourriture</option>
                        <option value="2">Loisir</option>
                        <option value="3">Travail</option>
                        <option value="4">Voyage</option>
                        <option value="5">Sport</option>
                        <option value="6">Habitat</option>
                        <option value="7">Cadeaux</option>
                    </select>
                </div>
                <div class="text-center">
                    <button type="button" class="btn btn-secondary btn-lg" id="add-category-btn">Ajouter une catégorie</button>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg">Ajouter</button>
                </div>
            </form>
        </div>
    </section>
</div>

<div class="position-fixed bottom-0 end-0 m-3">
    <a href="add.html" class="btn btn-primary btn-lg rounded-circle">
        <i class="bi bi-plus fs-1"></i>
    </a>
</div>

<?php
require 'includes/_footer.php'
?>