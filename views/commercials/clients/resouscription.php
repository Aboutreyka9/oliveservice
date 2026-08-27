<?= breakcrumb($title, 'fa-user-plus'); ?>

<header class="page-title-bar">
    <div class="header-dashboard d-flex align-items-center mb-4">
        <div>
            <a href="<?= url('souscriptions')?>" class="btn btn-secondary">
                <i class="fas fa-plus mr-2"></i>  NOUVEAU CLIENT
            </a>
        </div>
    </div>
</header>

<!-- TIMELINE -->
<div class="row mb-5">
    <div class="col-md-12">
        <div class="timeline-steps">
            <div class="timeline-step active" id="step1-indicator">
                <div class="step-circle">1</div>
                <div class="step-label">Recherche client</div>
            </div>
            <div class="timeline-connector"></div>
            <div class="timeline-step" id="step2-indicator">
                <div class="step-circle">2</div>
                <div class="step-label">Choix des packs</div>
            </div>
            <div class="timeline-connector"></div>
            <div class="timeline-step" id="step3-indicator">
                <div class="step-circle">3</div>
                <div class="step-label">Récapitulatif</div>
            </div>
        </div>
    </div>
</div>

<form method="post" id="frmAddResouscription">
    <input type="hidden" name="action" value="btn_add_resouscription">
    <input type="hidden" name="csrf_token" value="<?= csrfToken()::token() ?>">
    <input type="hidden" name="selected_packs" id="selected_packs" value="">

    <!-- ETAPE 1 : Recherche client existant -->
    <div class="step-content" id="step1">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Rechercher un client existant</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10 mb-3">
                        <label for="search_client" class="form-label">Code client ou téléphone <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" id="search_client" placeholder="Ex: CLI-001 ou 0102030405" required>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-warning" id="btn_search_client">
                            <i class="fas fa-search"></i> &nbsp; Rechercher
                        </button>
                    </div>
                </div>

                <div class="row d-none" id="client_found">
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            <h5 class="alert-heading">Client trouvé</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Nom :</strong> <span id="found_nom"></span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Contact :</strong> <span id="found_telephone"></span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Lieu :</strong> <span id="found_lieu"></span>
                                </div>
                            </div>
                            <input type="hidden" name="client_code" id="client_code" value="">
                        </div>
                    </div>
                </div>

                <div class="row d-none" id="client_not_found">
                    <div class="col-md-12">
                        <div class="alert alert-danger">
                            Aucun client trouvé avec ce code ou ce numéro de téléphone.
                        </div>
                    </div>
                </div>

                <div class="row mb-3 mt-3">
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-primary btn-next" data-step="2" id="btn_next_step2" disabled>
                            Suivant <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ETAPE 2 : Choix des packs -->
    <div class="step-content d-none" id="step2">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Sélection des packs</div>
            </div>
            <div class="card-body">
                <div class="row mb-5">
                    <div class="col-md-6">
                        <label for="">SESSION</label>
                        <select style="background: #c5c5c5; color:#000" name="session_code" id="session_souscription" class="form-control">
                            <option >--CHOISIR--</option>
                            <?= chargerSessions(); ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                         <label for="">CATEGORIE</label>
                        <select style="background: #ccc; color:#000" name="categorie_code" id="categorie_souscription" class="form-control">
                            <option >Aucune donnée disponible</option>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="row" id="packs-container">
                    <div class="col-md-12 text-center text-muted">
                        Veuillez sélectionner une session pour afficher les packs disponibles.
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i> Vous pouvez sélectionner plusieurs packs si nécessaire.
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12 d-flex justify-content-between">
                        <button type="button" class="btn btn-light btn-prev" data-step="1">
                            <i class="fas fa-arrow-left mr-2"></i> Précédent
                        </button>
                        <button type="button" class="btn btn-primary btn-next" data-step="3">
                            Suivant <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ETAPE 3 : Récapitulatif -->
    <div class="step-content d-none" id="step3">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Informations client</div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush" id="recap-infos">
                            <li class="list-group-item"><strong>Nom :</strong> <span id="recap-nom"></span></li>
                            <li class="list-group-item"><strong>Contact :</strong> <span id="recap-contact"></span></li>
                            <li class="list-group-item"><strong>Lieu de résidence :</strong> <span id="recap-lieu"></span></li>
                            <li class="list-group-item"><strong>Code client :</strong> <span id="recap-code"></span></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Packs sélectionnés</div>
                    </div>
                    <div class="card-body">
                        <div class="recap-packs-container">
                        <table class="table table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Pack</th>
                                    <th>Montant</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="recap-packs">
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Aucun pack sélectionné</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3 mt-3">
            <div class="col-md-12 d-flex justify-content-between">
                <button type="button" class="btn btn-light btn-prev" data-step="2">
                    <i class="fas fa-arrow-left mr-2"></i> Précédent
                </button>
                <button type="submit" class="btn btn-success" id="btnSubmitFormResouscription">
                    <i class="fas fa-save"></i> &nbsp; Enregistrer la résouscription
                </button>
            </div>
        </div>
    </div>
</form>
