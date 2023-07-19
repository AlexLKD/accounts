<?php
require 'includes/_database.php';
session_start();
$_SESSION['token'] = md5(uniqid(mt_rand(), true));
require 'includes/_header.php'
?>

<div class="container">
    <section class="card mb-4 rounded-3 shadow-sm">
        <div class="card-header py-3">
            <h2 class="my-0 fw-normal fs-4">Solde aujourd'hui</h2>
        </div>
        <?php
        // Récupérer le total des montants depuis la base de données
        $query = $dbCo->prepare("SELECT SUM(CASE WHEN amount >= 0 THEN amount ELSE 0 END) - SUM(CASE WHEN amount < 0 THEN -amount ELSE 0 END) AS total FROM transaction");
        $isOk = $query->execute();
        $result = $query->fetch();
        $totalAmount = $result['total'];
        echo '            <div class="card-body">
            <p class="card-title pricing-card-title text-center fs-1">' . $totalAmount . ' €</p>
        </div>';
        ?>

    </section>

    <section class="card mb-4 rounded-3 shadow-sm">
        <div class="card-header py-3">
            <h1 class="my-0 fw-normal fs-4">Opérations de <?= date("F Y") ?></h1>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col" colspan="2">Opération</th>
                        <th scope="col" class="text-end">Montant</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = $dbCo->prepare("SELECT name, id_transaction, amount, date_transaction, id_category, icon_class FROM transaction LEFT JOIN category USING (id_category) WHERE MONTH(date_transaction) = MONTH(now()) ORDER BY date_transaction DESC");
                    $query->execute();
                    $results = $query->fetchAll();
                    foreach ($results as $result) {
                        echo '<tr>';
                        echo '<td width="50" class="ps-3">';
                        echo '<i class="bi bi-' . $result['icon_class'] . ' fs-3"></i>';
                        echo '</td>';
                        echo '<td id="dealText">';
                        echo '<time datetime="' . $result['date_transaction'] . '" class="d-block fst-italic fw-light">' . $result['date_transaction'] . '</time>';
                        echo $result['name'];
                        echo '</td>';
                        echo '<td class="text-end">';
                        if (str_contains($result['amount'], "-")) {
                            echo '<span class="rounded-pill text-nowrap bg-warning-subtle px-2">';
                            echo $result['amount'];
                        } else {
                            echo '<span class="rounded-pill text-nowrap bg-success-subtle px-2">';
                            echo '+' . $result['amount'];
                        }
                        echo '</span>';
                        echo '</td>';
                        echo '<td class="text-end text-nowrap">';
                        echo '<div id="update-form" class="card-body">
                        <form action="update_transaction.php" method="POST" id="update-form" class="update-form form-submit" data-form-id="' . $result['id_transaction'] . '?>" >
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom de l\'opération *</label>
                                    <input type="hidden" name="token" value="' . $_SESSION['token'] . '" id="token-csrf">
                                    <input type="hidden" name="update" value="' . $result['id_transaction'] . '">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="' . $result['name'] . '" required>
                                </div>
                                <div class="mb-3">
                                    <label for="date" class="form-label">Date *</label>
                                    <input type="date" class="form-control" name="date" id="date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Montant *</label>
                                    <div class="input-group">
                                        <input type="hidden" name="token" value="' . $_SESSION['token'] . '" id="token-csrf">
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
                                <button type="submit" class="btn btn-primary btn-lg" data-deal-id="' . $result['id_transaction'] . '?>">Mettre à jour</button>
                                </div>
                            </form>
                        </div>';
                        echo '<button type="button" class="btn btn-outline-primary btn-sm rounded-circle edit-button" data-deal-id="hidden" data-target="update-form">';
                        echo '<i class="bi bi-pencil"></i>';
                        echo '</button>';
                        echo '<button type="button" class="btn btn-danger btn-sm rounded-circle" name="delete" value ="' . $result['id_transaction'] . '">
                        <a href="delete_transaction.php?delete=' . $result['id_transaction'] . '&token=' . $_SESSION['token'] . '" class="delete-link"><i class="bi bi-trash"></i></a>';
                        echo '</button>';
                        echo '</td>';
                        echo '</tr>';
                    };
                    ?>
                    <!-- <button type="submit" class="delete-button button" name="delete" value="' . $task['Id_task'] . '"> -->
                    <!-- </button> -->
                </tbody>
            </table>
        </div>
        <?php
        require 'includes/_pagination.php'
        ?>
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