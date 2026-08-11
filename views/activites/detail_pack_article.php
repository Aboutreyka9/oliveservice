<?= breakcrumb($title, 'fa-cubes'); ?>

<div class="row">
    <div class="col-md-4">
        <div class="card card-primary">
            <div class="card-header">
                <div class="card-title">Informations du pack</div>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="icon icon-primary icon-lg">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <h3 class="mt-2">PACK STANDARD</h3>
                    <p class="text-muted">Session Décembre 2025</p>
                    <span class="badge badge-success">Actif</span>
                </div>

                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-tag mr-2"></i>Libellé</span>
                        <strong>PACK STANDARD</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-layer-group mr-2"></i>Catégorie</span>
                        <strong>FORMATION INITIALE</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-map-marker-alt mr-2"></i>Zone</span>
                        <strong>ZONE NORD</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-file-alt mr-2"></i>Articles</span>
                        <strong>5 articles</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-money-bill-wave mr-2"></i>Montant</span>
                        <strong class="text-success">25 000 FCFA</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-calendar-alt mr-2"></i>Créé le</span>
                        <strong>10/08/2025</strong>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="<?= url('packs') ?>" class="btn btn-default btn-sm">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                    <div>
                        <button class="btn btn-warning btn-sm" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
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
                    <button type="button" class="btn btn-primary btn-sm" title="Ajouter article">
                        <i class="fas fa-plus"></i> &nbsp; Ajouter
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Code article</th>
                                <th>Libellé article</th>
                                <th>Quantité</th>
                                <th>Prix unitaire</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><span class="badge badge-info">ART-001</span></td>
                                <td>Manuel de mathématiques</td>
                                <td>2</td>
                                <td>5 000 FCFA</td>
                                <td><span class="badge badge-success">Actif</span></td>
                                <td>
                                    <button class="btn btn-light btn-sm btn-link" title="Voir">
                                        <i class="fa fa-eye text-icon-info"></i>
                                    </button>
                                    <button class="btn btn-light btn-sm btn-link" title="Modifier">
                                        <i class="fa fa-edit text-icon-primary"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td><span class="badge badge-info">ART-002</span></td>
                                <td> Cahier d'exercices</td>
                                <td>3</td>
                                <td>2 500 FCFA</td>
                                <td><span class="badge badge-success">Actif</span></td>
                                <td>
                                    <button class="btn btn-light btn-sm btn-link" title="Voir">
                                        <i class="fa fa-eye text-icon-info"></i>
                                    </button>
                                    <button class="btn btn-light btn-sm btn-link" title="Modifier">
                                        <i class="fa fa-edit text-icon-primary"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td><span class="badge badge-info">ART-003</span></td>
                                <td>Stylo à bille</td>
                                <td>5</td>
                                <td>500 FCFA</td>
                                <td><span class="badge badge-success">Actif</span></td>
                                <td>
                                    <button class="btn btn-light btn-sm btn-link" title="Voir">
                                        <i class="fa fa-eye text-icon-info"></i>
                                    </button>
                                    <button class="btn btn-light btn-sm btn-link" title="Modifier">
                                        <i class="fa fa-edit text-icon-primary"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td><span class="badge badge-info">ART-004</span></td>
                                <td>Cahier de cours</td>
                                <td>2</td>
                                <td>3 000 FCFA</td>
                                <td><span class="badge badge-success">Actif</span></td>
                                <td>
                                    <button class="btn btn-light btn-sm btn-link" title="Voir">
                                        <i class="fa fa-eye text-icon-info"></i>
                                    </button>
                                    <button class="btn btn-light btn-sm btn-link" title="Modifier">
                                        <i class="fa fa-edit text-icon-primary"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td><span class="badge badge-info">ART-005</span></td>
                                <td>Calculatrice scientifique</td>
                                <td>1</td>
                                <td>10 000 FCFA</td>
                                <td><span class="badge badge-success">Actif</span></td>
                                <td>
                                    <button class="btn btn-light btn-sm btn-link" title="Voir">
                                        <i class="fa fa-eye text-icon-info"></i>
                                    </button>
                                    <button class="btn btn-light btn-sm btn-link" title="Modifier">
                                        <i class="fa fa-edit text-icon-primary"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">Total: 5 articles</small>
                    <div class="text-right">
                        <strong class="text-primary">Total pack: 25 000 FCFA</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
