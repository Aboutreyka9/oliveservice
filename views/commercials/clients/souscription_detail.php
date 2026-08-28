<?php
$souscription = $souscription ?? [];
$packs = $packs ?? [];
$statCautisation = $statCautisation ?? [];
// var_dump($statCautisation);
$cautisations = $cautisations ?? [];
$distributions = $distributions ?? [];

?>

<?php if (empty($souscription)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i> Souscription non trouvée.
    </div>
<?php else: ?>

<?= breakcrumb($title, 'fa-file-alt'); ?>


<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title mb-0">Client concerné</div>
                <a href="<?= url('clients/liste') ?>" class="btn btn-default btn-sm">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-xl mr-4">
                        <div class="avatar-title bg-primary text-white rounded-circle" style="width:80px;height:80px;font-size:32px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="mb-1 text-uppercase"><?= htmlspecialchars($souscription['nom_client']) ?></h3>
                        <p class="text-muted mb-1">
                            <i class="fas fa-barcode mr-1"></i> <?= htmlspecialchars($souscription['code_client']) ?>
                            &nbsp;|&nbsp; <i class="fas fa-phone mr-1"></i> <?= htmlspecialchars($souscription['telephone_client']) ?>
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-map-marker-alt mr-1"></i> <?= htmlspecialchars($souscription['lieu_residence_client']) ?>
                            &nbsp;|&nbsp; <i class="fas fa-briefcase mr-1"></i> <?= htmlspecialchars($souscription['profession_client']) ?>
                        </p>
                    </div>
                    <div class="ml-auto text-right">
                        <span class="badge badge-success" style="padding: 8px 12px; font-size:13px;">Client actif</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title mb-0">Détails de la souscription</div>
               
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 200px;"><i class="fas fa-file-alt mr-2"></i>Code souscription</th>
                                <td><span class="font-weight-bold badge badge-danger"><?= $souscription['code_souscription'] ?></span></td>
                                <th style="width: 200px;"><i class="fas fa-calendar-alt mr-2"></i>Session</th>
                                <td><?= $souscription['libelle_session'] ?></td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-graduation-cap mr-2"></i>Année d'activité</th>
                                <td><?= $souscription['libelle_annee'] ?></td>
                                <th><i class="fas fa-map-marker-alt mr-2"></i>Zone</th>
                                <td><?= $souscription['libelle_zone'] ?></td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-info-circle mr-2"></i>Statut souscription</th>
                                <td><?= checkStatusSouscription($souscription['statut_souscription'],['en cour','valide']) ?></td>
                                <th><i class="fas fa-user-tie mr-2"></i>Commercial</th>
                                <td><?= $souscription['nom_user']. ' ' . $souscription['prenom_user']?></td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-clock mr-2"></i>Date souscription</th>
                                <td><?= date_formater($souscription['created_at_souscription'], true) ?></td>
                                <th><i class="fas fa-calendar-check mr-2"></i>Nombre de jours</th>
                                <td><?= $statCautisation['nombre_jour_paye'] ?> / <?= $statCautisation['nombre_jour_session'] ?>  <?= checkNiveauPaiement($statCautisation['nombre_jour_paye'], $statCautisation['nombre_jour_session']) ?></td>
                            </tr>
                            <tr>
                                <?= checkAjourCautisation($souscription['code_souscription']) ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-2">
    <div class="col-md-4">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-primary mr-2">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Montant total souscription</h6>
                        <h5 class="montan-value"><?= money($statCautisation['montant_total']) ?></h5>
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
                        <h6 class="montan-title">Total payé</h6>
                        <h5 class="montan-value"><?= money($statCautisation['montant_paye']) ?></h5>
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
                        <h5 class="montan-value"><?= money($statCautisation['reste_a_payer']) ?></h5>
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
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Liste des versement</h4>
                <button class="btn btn-info btn-sm"> 
                     <i class="fas fa-hand-holding-usd"></i> &nbsp; Encaisser
                </button>
            </div>
            <div class="card-body">
                <?php if (empty($cautisations)): ?>
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
                                <?php foreach ($cautisations as $i => $c): ?>
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
                                    <th>Nombre</th>
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
