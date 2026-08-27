<?php
$inscription = $inscription ?? [];
$packs = $packs ?? [];
$cautions = $cautions ?? [];
$distributions = $distributions ?? [];
?>

<?php if (empty($inscription)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i> Inscription non trouvée.
    </div>
<?php else: ?>

<?= breakcrumb($title, 'fa-file-alt'); ?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Détails de l'inscription - <?= htmlspecialchars($inscription['code_inscription']) ?></h4>
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
                                <h5 class="montan-value"><?= strtoupper(htmlspecialchars($inscription['nom_client'])) ?></h5>
                                <p class="text-muted mb-1"><?= htmlspecialchars($inscription['code_client']) ?></p>
                                <p class="text-muted">
                                    <i class="fas fa-phone mr-1"></i> <?= htmlspecialchars($inscription['telephone_client']) ?>
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
                                <h6 class="montan-title">Date inscription</h6>
                                <h5 class="montan-value"><?= date_formater($inscription['created_at_inscription'], true) ?></h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive mt-4">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 200px;">Code inscription</th>
                                <td><?= htmlspecialchars($inscription['code_inscription']) ?></td>
                                <th style="width: 200px;">Session</th>
                                <td><?= htmlspecialchars($inscription['libelle_session']) ?></td>
                            </tr>
                            <tr>
                                <th>Année scolaire</th>
                                <td><?= htmlspecialchars($inscription['libelle_annee']) ?></td>
                                <th>Zone</th>
                                <td><?= htmlspecialchars($inscription['libelle_zone']) ?></td>
                            </tr>
                            <tr>
                                <th>Statut inscription</th>
                                <td><?= checkStatusInscription($inscription['statut_inscription']) ?></td>
                                <th>Commercial</th>
                                <td><?= htmlspecialchars(($inscription['nom_user'] ?? '') . ' ' . ($inscription['prenom_user'] ?? '')) ?: '-' ?></td>
                            </tr>
                            <tr>
                                <th>Date création</th>
                                <td><?= date_formater($inscription['created_at_inscription'], true) ?></td>
                                <th>Mise à jour</th>
                                <td><?= $inscription['updated_at_inscription'] ? date_formater($inscription['updated_at_inscription'], true) : '-' ?></td>
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

<div class="row g-3 mb-4">
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
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0">Packs souscrits</h4>
            </div>
            <div class="card-body">
                <?php if (empty($packs)): ?>
                    <p class="text-muted text-center py-4">Aucun pack associé à cette inscription.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Pack</th>
                                    <th>Montant</th>
                                    <th>Article</th>
                                    <th>Qté</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $test = [];  foreach ($test as $i => $pc): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td>
                                            <span class="badge badge-info"><?= htmlspecialchars($pc['libelle_pack']) ?></span>
                                            <small class="text-muted">(<?= htmlspecialchars($pc['code_pack']) ?>)</small>
                                        </td>
                                        <td class="text-nowrap"><?= number_format($pc['montant_pack'], 0, ',', ' ') ?> FCFA</td>
                                        <td><?= htmlspecialchars($pc['libelle_article'] ?? '-') ?></td>
                                        <td class="text-center"><?= $pc['quantite_article'] ?? '-' ?></td>
                                        <td><?= checkStatusInscription($pc['statut_pack_inscription'], ['En attente', 'valide', 'rejeté']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
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
                                    <th>Code</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Date création</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cautions as $i => $c): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($c['code_cautisation_client']) ?></td>
                                        <td class="text-nowrap"><?= number_format($c['montant_cautisation_client'], 0, ',', ' ') ?> FCFA</td>
                                        <td><?= checkStatusInscription($c['statut_cautisation_client'], ['En attente', 'valide', 'annule']) ?></td>
                                        <td><?= date_formater($c['created_at_cautisation_client'], true) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

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
                                        <td><?= checkStatusInscription($d['statut_distribution'], ['En attente', 'valide', 'annule']) ?></td>
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

<!-- Articles -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0">Articles de l'inscription</h4>
            </div>
            <div class="card-body">
                <?php if (empty($packs)): ?>
                    <p class="text-muted text-center py-4">Aucun article associé à cette inscription.</p>
                <?php else: ?>
                    <?php
                    $articles = [];
                    foreach ($packs as $pc) {
                        $articles[] = [
                            'libelle_article' => $pc['libelle_article'] ?? '-',
                            'description_article' => $pc['description_article'] ?? '',
                            'libelle_categorie_pack' => $pc['libelle_categorie_pack'] ?? '-',
                            'libelle_session' => $inscription['libelle_session'] ?? '-',
                            'quantite_article' => $pc['quantite_article'] ?? 0,
                        ];
                    }
                    $articlesUniques = [];
                    foreach ($articles as $art) {
                        $key = $art['libelle_article'] . '|' . $art['description_article'] . '|' . $art['libelle_categorie_pack'];
                        $articlesUniques[$key] = $art;
                    }
                    ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Libellé article</th>
                                    <th>Description</th>
                                    <th>Catégorie</th>
                                    <th>Session</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_values($articlesUniques) as $i => $article): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($article['libelle_article']) ?></td>
                                        <td><?= htmlspecialchars($article['description_article'] ?: '-') ?></td>
                                        <td><?= htmlspecialchars($article['libelle_categorie_pack']) ?></td>
                                        <td><?= htmlspecialchars($article['libelle_session']) ?></td>
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
