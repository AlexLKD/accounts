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
                    $query = $dbCo->prepare("SELECT name, id_transaction, amount, date_transaction, id_category, icon_class FROM transaction LEFT JOIN category USING (id_category) WHERE date_transaction LIKE '2023-07-%' ORDER BY date_transaction DESC");
                    $query->execute();
                    $results = $query->fetchAll();
                    foreach ($results as $result) {
                        echo '<tr>';
                        echo '<td width="50" class="ps-3">';
                        echo '<i class="bi bi-' . $result['icon_class'] . ' fs-3"></i>';
                        echo '</td>';
                        echo '<td>';
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
                        echo '<button type="button" class="btn btn-outline-primary btn-sm rounded-circle" >';
                        echo '<i class="bi bi-pencil"></i>';
                        echo '</button>';
                        echo '<button type="button" class="btn btn-outline-danger btn-sm rounded-circle">';
                        echo '<i class="bi bi-trash"></i>';
                        echo '</button>';
                        echo '</td>';
                        echo '</tr>';
                    };
                    ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <nav class="text-center">
                <ul class="pagination d-flex justify-content-center m-2">
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="bi bi-arrow-left"></i>
                        </span>
                    </li>
                    <li class="page-item active" aria-current="page">
                        <span class="page-link">Juillet 2023</span>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="index.html">Juin 2023</a>
                    </li>
                    <li class="page-item">
                        <span class="page-link">...</span>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="index.html">
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
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