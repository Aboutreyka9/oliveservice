<?php
$souscription = $souscription ?? [];
$packs = $packs ?? [];
// var_dump($articles);
$cautions = $cautions ?? [];
$distributions = $distributions ?? [];
?>

<?php if (empty($souscription)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i> Souscription non trouvée.
    </div>
<?php else: ?>

<?= breakcrumb($title, 'fa-file-alt'); ?>

<div class="row mb-2">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Détails de l'souscription - <?= $souscription['code_souscription'] ?></h4>
                <div>
                    <a href="<?= url('souscriptions/liste') ?>" class="btn btn-default btn-sm">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card custom-card-detail h-100">
                            <div class="card-body text-center">
                                <div class="icon bg-primary mb-2">
                                    <i class="fas fa-user-circle" style="font-size: 32px;"></i>
                                </div>
                                <h6 class="montan-title">Client</h6>
                                <h5 class="montan-value"><?= strtoupper($souscription['nom_client']) ?></h5>
                                <p class="text-muted mb-1"><?= $souscription['code_client'] ?></p>
                                <p class="text-muted">
                                    <i class="fas fa-phone mr-1"></i> <?= $souscription['telephone_client'] ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card custom-card-detail h-100">
                            <div class="card-body text-center">
                                <div class="icon bg-info mb-2">
                                    <i class="fas fa-calendar-check" style="font-size: 32px;"></i>
                                </div>
                                <h6 class="montan-title">Date souscription</h6>
                                <h5 class="montan-value"><?= date_formater($souscription['created_at_souscription'], true) ?></h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive mt-4">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 200px;">Code souscription</th>
                                <td><?= $souscription['code_souscription'] ?></td>
                                <th style="width: 200px;">Session</th>
                                <td><?= $souscription['libelle_session'] ?></td>
                            </tr>
                            <tr>
                                <th>Année d'activité</th>
                                <td><?= $souscription['libelle_annee'] ?></td>
                                <th>Zone</th>
                                <td><?= $souscription['libelle_zone'] ?></td>
                            </tr>
                            <tr>
                                <th>Statut souscription</th>
                                <td><?= checkStatusSouscription($souscription['statut_souscription']) ?></td>
                                <th>Commercial</th>
                                <td><?= $souscription['nom_user']. ' ' . $souscription['prenom_user']?></td>
                            </tr>
                            <tr>
                                <th>Date création</th>
                                <td><?= date_formater($souscription['created_at_souscription'], true) ?></td>
                                <th>Mise à jour</th>
                                <td><?= $souscription['updated_at_souscription'] ? date_formater($souscription['updated_at_souscription'], true) : '-' ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$totalPacks = 0;
$montantPayeTotal = 0;
foreach ($packs as $pc) {
    $totalPacks += (float) ($pc['montant_pack'] ?? 0);
}
foreach ($cautions as $c) {
    if (($c['statut_cautisation_client'] ?? '') === 'valide') {
        $montantPayeTotal += (float) ($c['montant_cautisation_client'] ?? 0);
    }
}
$resteDu = max(0, $totalPacks - $montantPayeTotal);
?>

<div class="row g-3 mb-2">
    <div class="col-md-4">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-primary mr-2">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Montant total packs</h6>
                        <h5 class="montan-value"><?= number_format($totalPacks, 0, ',', ' ') ?> FCFA</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-success mr-2">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Total payé (cautions)</h6>
                        <h5 class="montan-value"><?= number_format($montantPayeTotal, 0, ',', ' ') ?> FCFA</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-danger mr-2">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Reste dû</h6>
                        <h5 class="montan-value"><?= number_format($resteDu, 0, ',', ' ') ?> FCFA</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0">Packs souscrits</h4>
            </div>
            <div class="card-body">
                <?php if (empty($packs)): ?>
                    <p class="text-muted text-center py-4">Aucun pack associé à cette souscription.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Pack</th>
                                    <th>Categorie</th>
                                    <th>Montant</th>
                                    <th>Article</th>
                                    <th>Qté</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $output = '';
                                    $i = 0;
                                 foreach ($packs as $i => $pc){ 
                                    $output.= ' 
                                    <tr>
                                        <td>'. $i + 1 .'</td>
                                        <td> <span class="badge badge-info">'. $pc['libelle_pack'] .'</span> </td>
                                        <td class="text-center">'. $pc['libelle_categorie_pack'] .'</td>
                                        <td class="text-nowrap">'. money($pc['montant_pack']) .' </td>
                                        <td class="text-center">'. $pc['nombre_article'] .'</td>
                                        <td>'. $pc['quantite'] .'</td>
                                    </tr>';
                                 } 
                                 echo $output ;?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0">Cautions</h4>
            </div>
            <div class="card-body">
                <?php if (empty($cautions)): ?>
                    <p class="text-muted text-center py-4">Aucune caution enregistrée.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Date création</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cautions as $i => $c): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td class="text-nowrap"><?= money($c['montant_cautisation_client']) ?> </td>
                                        <td><?= checkStatusSouscription($c['statut_cautisation_client'], ['En attente', 'valide', 'annule']) ?></td>
                                        <td><?= date_formater($c['created_at_cautisation_client'], true) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Articles -->
<div class="row">
    <div class="col-md-7">
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0">Liste des articles de l'souscription</h4>
            </div>
            <div class="card-body">
                <?php if (empty($articles)): ?>
                    <p class="text-muted text-center py-4">Aucun article associé à cette souscription.</p>
                <?php else: ?>
               
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Libellé article</th>
                                    <th>Description</th>
                                    <th>Session</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                foreach ($articles as $article): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= $article['libelle_article']?></td>
                                        <td><?= $article['description_article'] ?></td>
                                        <td><?= $article['quantite_totale']?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-5">
         <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0">Distributions</h4>
            </div>
            <div class="card-body">
                <?php if (empty($distributions)): ?>
                    <p class="text-muted text-center py-4">Aucune distribution enregistrée.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($distributions as $i => $d): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($d['code_distribution']) ?></td>
                                        <td><?= checkStatusSouscription($d['statut_distribution'], ['En attente', 'valide', 'annule']) ?></td>
                                        <td><?= date_formater($d['created_at_distribution'], true) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>
