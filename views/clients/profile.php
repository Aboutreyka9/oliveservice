<?php
$client = $client ?? [];
$souscriptions = $souscriptions ?? [];
$packInscriptions = $pack_souscriptions ?? [];
$distributions = $distributions ?? [];
$cautisations = $cautisations ?? [];
?>

<?php if (empty($client)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i> Client non trouvé.
    </div>
<?php else: ?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-xl mr-4">
                        <?php if (!empty($client['photo_client'])): ?>
                            <img src="<?= ASSETS ?>uploads/clients/<?= $client['photo_client'] ?>" alt="Photo" class="avatar-img rounded-circle" width="80" height="80">
                        <?php else: ?>
                            <div class="avatar-title bg-primary text-white rounded-circle" style="width:80px;height:80px;font-size:32px;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="mb-1"><?= strtoupper(htmlspecialchars($client['nom_client'])) ?></h3>
                        <p class="text-muted mb-1">
                            <i class="fas fa-barcode mr-1"></i> <?= htmlspecialchars($client['code_client']) ?>
                            <?php if (!empty($client['telephone_client'])): ?>
                                &nbsp;|&nbsp; <i class="fas fa-phone mr-1"></i> <?= htmlspecialchars($client['telephone_client']) ?>
                            <?php endif; ?>
                        </p>
                        <span class="badge badge-success">Client actif</span>
                    </div>
                    <div class="ml-auto">
                        <a href="<?= url('clients') ?>" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-primary mr-2">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Souscriptions</h6>
                        <h5 class="montan-value"><?= count($souscriptions) ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-success mr-2">
                        <i class="fas fa-box"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Packs</h6>
                        <h5 class="montan-value"><?= count($packInscriptions) ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-warning mr-2">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Distributions</h6>
                        <h5 class="montan-value"><?= count($distributions) ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-info mr-2">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Cautions</h6>
                        <h5 class="montan-value"><?= count($cautisations) ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$totalPacks = 0;
$totalPaye = 0;
foreach ($souscriptions as $ins) {
    foreach ($packInscriptions as $pi) {
        if ($pi['inscription_code'] === $ins['code_inscription']) {
            $totalPacks += $pi['montant_pack'] ?? 0;
        }
    }
}
foreach ($cautisations as $c) {
    if ($c['statut_cautisation_client'] === 'valide') {
        $totalPaye += $c['montant_cautisation_client'];
    }
}
$resteDu = max(0, $totalPacks - $totalPaye);
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
                        <h6 class="montan-title">Total payé</h6>
                        <h5 class="montan-value"><?= number_format($totalPaye, 0, ',', ' ') ?> FCFA</h5>
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
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4>Informations personnelles</h4>
            </div>
            <div class="card-body">
                <form method="post" action="" id="form_update_client">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Nom complet <strong class="text-danger">*</strong></label>
                            <input type="text" name="nom_client" class="form-control" value="<?= htmlspecialchars($client['nom_client']) ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Contact <strong class="text-danger">*</strong></label>
                            <input type="text" name="telephone_client" class="form-control telephone" value="<?= htmlspecialchars($client['telephone_client']) ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Genre</label>
                            <select name="sexe_client" class="form-control select2">
                                <option value="">--- CHOISIR ---</option>
                                <?php foreach (SEXEP as $genre): ?>
                                    <option <?= selected($client['sexe_client'], $genre) ?> value="<?= $genre ?>"><?= $genre ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Lieu de résidence</label>
                            <input type="text" name="lieu_residence_client" class="form-control" value="<?= htmlspecialchars($client['lieu_residence_client']) ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Numéro CNI</label>
                            <input type="text" name="numero_cni" class="form-control" value="<?= $client['numero_cni'] ?>">
                        </div>
                        <div class="col-md-12">
                            <input type="hidden" name="code_client" value="<?= $client['code_client'] ?>">
                            <input type="hidden" name="action" value="btn_update_client">
                            <input type="hidden" name="csrf_token" value="<?= csrfToken()::token() ?>">
                            <button type="submit" class="btn btn-primary w-100" form="form_update_client" id="btn_update_client">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h4>Historique des souscriptions</h4>
            </div>
            <div class="card-body">
                <?php if (empty($souscriptions)): ?>
                    <p class="text-muted text-center py-4">Aucune souscription enregistrée pour ce client.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Session</th>
                                    <th>Année</th>
                                    <th>Zone</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($souscriptions as $i => $ins): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($ins['code_inscription']) ?></td>
                                        <td><?= htmlspecialchars($ins['libelle_session']) ?></td>
                                        <td><?= htmlspecialchars($ins['libelle_annee']) ?></td>
                                        <td><?= htmlspecialchars($ins['libelle_zone']) ?></td>
                                        <td><?= checkStatusInscription($ins['statut_inscription']) ?></td>
                                        <td><?= date_formater($ins['created_at_inscription'], true) ?></td>
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
                <h4>Packs souscrits</h4>
            </div>
            <div class="card-body">
                <?php if (empty($packInscriptions)): ?>
                    <p class="text-muted text-center py-4">Aucun pack souscrit.</p>
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
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($packInscriptions as $i => $pi): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($pi['libelle_pack']) ?></td>
                                        <td><?= number_format($pi['montant_pack'], 0, ',', ' ') ?> FCFA</td>
                                        <td><?= htmlspecialchars($pi['libelle_article'] ?? '-') ?></td>
                                        <td><?= $pi['quantite_article'] ?? '-' ?></td>
                                        <td><span class="badge badge-success"><?= htmlspecialchars($pi['statut_pack_inscription']) ?></span></td>
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
                <h4>Distributions</h4>
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
                                    <th>Zone</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($distributions as $i => $d): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($d['code_distribution']) ?></td>
                                        <td><?= htmlspecialchars($d['zone_code']) ?></td>
                                        <td><?= checkStatusInscription($d['statut_distribution'], ['En attente', 'valide', 'ennule']) ?></td>
                                        <td><?= date_formater($d['created_at_distribution'], true) ?></td>
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
                <h4>Cautions</h4>
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
                                    <th>Code</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Date création</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cautisations as $i => $c): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($c['code_cautisation_client']) ?></td>
                                        <td><?= number_format($c['montant_cautisation_client'], 0, ',', ' ') ?> FCFA</td>
                                        <td><?= checkStatusInscription($c['statut_cautisation_client'], ['En attente', 'valide', 'ennule']) ?></td>
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

<?php endif; ?>
