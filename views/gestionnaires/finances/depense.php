<?php
// Dates par défaut
$start = (new DateTime('first day of this month'))->format('Y-m-d');
$end = (new DateTime('today'))->format('Y-m-d');

$start_last = (new DateTime('first day of last month'))->format('Y-m-d');
$end_last = (new DateTime('last day of last month'))->format('Y-m-d');
// $end_last = (new DateTime('today'))->format('Y-m-d');

$dateD = (new DateTime('first day of this month'))->format('d-m-Y');
$dateF = (new DateTime('today'))->format('d-m-Y');

$depense_annule = 0;
$depense_en_attente = 0;
$depense_approuve = 0;
// Récupérer les achats du mois courant
// $totaux = Soutra::getTotauxDepenseByMouth($start, $end); // méthode adaptée que l'on a créée

// $depense_annule = Soutra::getTotalDepenseAny($start, $end, STATUT_DEPENSE[2]); // méthode adaptée que l'on a créée
// $depense_en_attente = Soutra::getTotalDepenseAny($start, $end, STATUT_DEPENSE[0]); // méthode adaptée que l'on a créée
// $depense_approuve = Soutra::getTotalDepenseAny($start, $end, STATUT_DEPENSE[1]); // méthode adaptée que l'on a créée


?>
<?= breakcrumb($title, 'fa-chart-bar '); ?>

<header class="page-title-bar">
    <!-- d-flex flex-column flex-md-row justify-content-space-between align-items-md-center gap-2 -->
    <div class="mt-2 mb-5 row d-flex justify-content-space-between  align-items-center">

        <div class="col-md-6 activity ">
            <h4 style="font-weight: bold;" id="activityDateRange"><i class="fa fa-calendar-alt"></i> Activité du
                <?= $dateD . ' au ' . $dateF; ?> </h4>
        </div>

        <div class="col-md-6 input-group w-md-auto filter-box">
            <input type="text" name="datefilterDepense" class="form-control" id="datefilterDepense"
                placeholder="Sélectionner la période">
            <button id="filterBtn" class="btn btn-primary ml-2"><i class="fa fa-filter"></i></button>

        </div>
    </div>

</header>

<!-- STATS -->
<div class="row g-3 mb-1 mt-4">

    <div class="col-md-4">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-dark mr-2">
                        <i class="far fa-times-circle"></i>
                    </div>
                    <h6 class="montan-title"><span class="text-muted text-uppercase">Dépenses annulee </span>(<span
                            id="nombre_depense_annule"> <?= $depense_annule ?>
                        </span>)</h6>
                </div>
                <h5 class="montan-value"><span
                        id="monant_depense_annule"><?= number_format($depense_annule, 0, ',', ' ') ?>
                    </span> FCFA</h5>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-warning mr-2">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h6 class="montan-title"><span class="text-muted text-uppercase">Dépenses en attentes</span> (<span
                            id="nombre_depense_en_attente"> <?= $depense_en_attente ?>
                        </span>)</h6>
                </div>
                <h5 class="montan-value"><span
                        id="montant_depense_en_attente"><?= number_format($depense_en_attente, 0, ',', ' ') ?>
                    </span> FCFA</h5>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-success mr-2">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <h6 class="montan-title"><span class="text-muted text-uppercase">Dépenses approuvees</span> (<span
                            id="nombre_depense_approuve"> <?= $depense_approuve ?>
                        </span>)</h6>
                </div>
                <h5 class="montan-value"><span class="tester"
                        id="montant_depense_approuve"><?= number_format($depense_approuve, 0, ',', ' ') ?>
                    </span> FCFA</h5>
            </div>
        </div>
    </div>

</div>


<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-end">
            <button type="button" id="btn_depense_addModal" class="btn btn-primary w-25" title="Ajouter dépense"
                aria-label="Close"> <i class="fa fa-plus"></i> &nbsp; Créer</button>
        </div>
    </div>
    <div class="card-body">

        <div class="table-responsive bg-light py-3 px-2 border rounded">
            <!-- .table -->
            <table id="data-table-depense" class="table table-hover my-table">
                <!-- thead -->
                <thead class="thead-light">
                    <tr>
                        <th> #</th>
                        <th> Dépense</th>
                        <th> Periode</th>
                        <th> Statut</th>
                        <th> Montant</th>
                        <th> Créer par</th>
                        <th> Créer le</th>
                        <th> Actions</th>
                    </tr>
                </thead><!-- /thead -->
            </table><!-- /.table -->
        </div><!-- /.table-responsive bg-light py-3 px-2 border rounded -->
    </div>
</div>



<!-- Modal depense-->
<div class="modal fade" data-backdrop="static" id="depense-modal" data-bs-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="depenseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="depenseModalLabel"><i class="fa fa-user-circle"></i> &nbsp; <span
                        class="text-uppercase">Formulaire d'enregistrement</span> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="data-depense-modal">

                </div>
            </div>
            <!-- .modal-footer -->
            <div class="modal-footer">

            </div><!-- /.modal-footer -->
        </div>
    </div>
</div>