<?= breakcrumb($title, 'fa-user'); ?>

<div class="row">
    <div class="col-md-4">
        <div class="card card-primary">
            <div class="card-header">
                <div class="card-title">Profil client</div>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="icon icon-primary icon-lg mx-auto">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3 class="mt-2">KOUAME JEAN</h3>
                    <p class="text-muted">CLI-001</p>
                    <span class="badge badge-success">Client actif</span>
                </div>

                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-phone mr-2"></i>Contact</span>
                        <strong>0102030405</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-envelope mr-2"></i>Email</span>
                        <strong>jean@email.com</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-map-marker-alt mr-2"></i>Résidence</span>
                        <strong>Cocody</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-briefcase mr-2"></i>Profession</span>
                        <strong>Commerçant</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-calendar-alt mr-2"></i>Inscrit le</span>
                        <strong>15/01/2025</strong>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="<?= url('clients') ?>" class="btn btn-default btn-sm">
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
                    <div class="card-title">Historique des commandes</div>
                    <button type="button" class="btn btn-primary btn-sm" title="Nouvelle commande">
                        <i class="fas fa-plus"></i> &nbsp; Nouvelle commande
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Date commande</th>
                                <th>Articles</th>
                                <th>Montant total</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>10/08/2025</td>
                                <td>Pack Standard + Manuel maths</td>
                                <td>25 000 FCFA</td>
                                <td><span class="badge badge-success">Payée</span></td>
                                <td>
                                    <button class="btn btn-light btn-sm btn-link" title="Voir">
                                        <i class="fa fa-eye text-icon-info"></i>
                                    </button>
                                    <button class="btn btn-light btn-sm btn-link" title="Imprimer">
                                        <i class="fa fa-print text-icon-primary"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>05/08/2025</td>
                                <td>Pack Premium</td>
                                <td>45 000 FCFA</td>
                                <td><span class="badge badge-warning">En attente</span></td>
                                <td>
                                    <button class="btn btn-light btn-sm btn-link" title="Voir">
                                        <i class="fa fa-eye text-icon-info"></i>
                                    </button>
                                    <button class="btn btn-light btn-sm btn-link" title="Imprimer">
                                        <i class="fa fa-print text-icon-primary"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>28/07/2025</td>
                                <td>Pack Standard</td>
                                <td>25 000 FCFA</td>
                                <td><span class="badge badge-success">Payée</span></td>
                                <td>
                                    <button class="btn btn-light btn-sm btn-link" title="Voir">
                                        <i class="fa fa-eye text-icon-info"></i>
                                    </button>
                                    <button class="btn btn-light btn-sm btn-link" title="Imprimer">
                                        <i class="fa fa-print text-icon-primary"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">Total: 3 commandes</small>
                    <div class="text-right">
                        <strong class="text-primary">Total dépensé: 95 000 FCFA</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
