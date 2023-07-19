<?php
require 'includes/_database.php';
session_start();
$_SESSION['token'] = md5(uniqid(mt_rand(), true));
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opérations de Juillet 2023 - Mes Comptes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <?php
    require 'includes/_header.php'
    ?>

    <div class="container">
        <section class="card mb-4 rounded-3 shadow-sm">
            <div class="card-header py-3">
                <h2 class="my-0 fw-normal fs-4">Solde aujourd'hui</h2>
            </div>
            <?php
            // $query = $dbCo->prepare("SELECT country, name, description FROM languages");
            // $query->execute();
            // $languages = $query->fetchAll();
            $query = $dbCo->prepare("SELECT ")
            ?>
            <div class="card-body">
                <p class="card-title pricing-card-title text-center fs-1">625,34 €</p>
            </div>
        </section>

        <section class="card mb-4 rounded-3 shadow-sm">
            <div class="card-header py-3">
                <h1 class="my-0 fw-normal fs-4">Opérations de Juillet 2023</h1>
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
                        $query = $dbCo->prepare("SELECT name, amount, date_transaction, id_category, icon_class FROM transaction LEFT JOIN category USING (id_category) WHERE date_transaction LIKE '2023-07-%' ORDER BY date_transaction DESC");
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
                            echo '<span class="rounded-pill text-nowrap bg-warning-subtle px-2">';
                            echo $result['amount'];
                            echo '</span>';
                            echo '</td>';
                            echo '<td class="text-end text-nowrap">';
                            echo '<a href="#" class="btn btn-outline-primary btn-sm rounded-circle">';
                            echo '<i class="bi bi-pencil"></i>';
                            echo '</a>';
                            echo '<a href="#" class="btn btn-outline-danger btn-sm rounded-circle">';
                            echo '<i class="bi bi-trash"></i>';
                            echo '</a>';
                            echo '</td>';
                            echo '</tr>';
                        };

                        // var_dump($result);
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>