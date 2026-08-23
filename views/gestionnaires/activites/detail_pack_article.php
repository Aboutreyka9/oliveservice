<?php
$pack = $pack ?? [];
$articles = $articles ?? [];
$totalArticles = 0;
$quantiteTotale = 0;
?>

<?php if (empty($pack)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i> Pack non trouvé.
    </div>
<?php else: ?>

<?= breakcrumb($title, 'fa-cubes'); ?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon icon-primary icon-xl mr-3">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="mb-1"><?= strtoupper(htmlspecialchars($pack['libelle_pack'])) ?></h3>
                        <p class="text-muted mb-0">
                            <i class="fas fa-barcode mr-1"></i> <?= htmlspecialchars($pack['code_pack']) ?>
                           
                                &nbsp;|&nbsp; <i class="fas fa-calendar-alt mr-1"></i> creer le : <?= date_formater($pack['created_at_pack'], true) ?>
                            
                           
                                &nbsp;|&nbsp; <i class="fas fa-user-alt mr-1"></i> Par : <?= date_formater($pack['created_at_pack'], true) ?>
                           
                        </p>
                    </div>
                    <div class="ml-auto">
                        <a href="<?= url('packs') ?>" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                        <?php if ($pack['statut_pack'] == 'actif'): ?>
                            <button class="btn btn-warning btn-sm ml-2" title="Modifier">
                                <i class="fas fa-edit"></i> Modifier
                            </button>
                        <?php else: ?>
                            <button class="btn btn-success btn-sm ml-2" title="Activer">
                                <i class="fas fa-check"></i> Activer
                            </button>
                        <?php endif; ?>
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
                    <div class="icon bg-info mr-2">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Articles</h6>
                        <h5 class="montan-value"><?= count($articles) ?></h5>
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
                        <i class="fas fa-sort-numeric-up"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Quantité totale</h6>
                        <h5 class="montan-value"><?= number_format($quantiteTotale) ?></h5>
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
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Montant pack</h6>
                        <h5 class="montan-value"><?= number_format($pack['montant_pack'], 0, ',', ' ') ?> FCFA</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-danger mr-2">
                        <i class="fas fa-clendar"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Nombre de souscription</h6>
                        <h5 class="montan-value">55</h5>
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
                <h4>Informations du pack</h4>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-tag mr-2"></i>Libellé</span>
                        <strong><?= strtoupper(htmlspecialchars($pack['libelle_pack'])) ?></strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-layer-group mr-2"></i>Catégorie</span>
                        <strong><?= htmlspecialchars($pack['categorie_pack_code']) ?></strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-map-marker-alt mr-2"></i>Zone</span>
                        <strong><?= htmlspecialchars($pack['zone_code']) ?></strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-calendar-alt mr-2"></i>Session</span>
                        <strong><?= htmlspecialchars($pack['session_code']) ?></strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-users mr-2"></i>Année</span>
                        <strong><?= htmlspecialchars($pack['annee_code']) ?></strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-money-bill-wave mr-2"></i>Montant</span>
                        <strong class="text-success"><?= number_format($pack['montant_pack'], 0, ',', ' ') ?> FCFA</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-clock mr-2"></i>Statut</span>
                        <?= checkEtatData($pack['statut_pack']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="card-title">Articles du pack</div>
                    <?php if (auth()::hasRole(Roles::ADMIN_PARAM) || auth()::hasRole(Roles::ADMIN_USER) || auth()::hasGroupe(Groupes::SUPER)): ?>
                    <button type="button" class="btn btn-primary btn-sm" title="Ajouter article" onclick="alert('Fonctionnalité à implémenter')">
                        <i class="fas fa-plus"></i> &nbsp; Ajouter
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($articles)): ?>
                    <p class="text-muted text-center py-4">Aucun article dans ce pack.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Code article</th>
                                    <th>Libellé</th>
                                    <th>Quantité</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                foreach ($articles as $i => $article): 
                                    $quantiteTotale += $article['quantite_article'];
                                    $totalArticles++;
                                ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><span class="badge badge-info"><?= htmlspecialchars($article['article_code']) ?></span></td>
                                        <td><?= htmlspecialchars($article['libelle_article']) ?></td>
                                        <td><?= number_format($article['quantite_article']) ?></td>
                                        <td><span class="badge badge-success">Actif</span></td>
                                    <td>
                                        <button class="btn btn-light btn-sm btn-link" title="Voir" onclick="alert('Fonctionnalité à implémenter')">
                                            <i class="fa fa-eye text-icon-info"></i>
                                        </button>
                                        <?php if (auth()::hasRole('sup1') || auth()::hasRole('para1')): ?>
                                        <button class="btn btn-light btn-sm btn-link" title="Modifier" onclick="alert('Fonctionnalité à implémenter')">
                                            <i class="fa fa-edit text-icon-primary"></i>
                                        </button>
                                        <button class="btn btn-light btn-sm btn-link" title="Supprimer" onclick="alert('Fonctionnalité à implémenter')">
                                            <i class="fa fa-trash text-icon-danger"></i>
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">Total: <?= $totalArticles ?> article(s) — Quantité totale: <?= number_format($quantiteTotale) ?></small>
                    <div class="text-right">
                        <strong class="text-primary">Montant pack: <?= number_format($pack['montant_pack'], 0, ',', ' ') ?> FCFA</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>
