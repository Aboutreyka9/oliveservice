<?= breakcrumb($title, 'fa-user-plus'); ?>

<header class="page-title-bar">
    <div class="header-dashboard d-flex align-items-center mb-4">
        <i class="fas fa-user-plus mr-3 me-3" style="font-size:20px;"></i>
        <div>
            <h4 class="mb-0">Inscription client</h4>
            <small>Enregistrement d'un nouveau client en 3 étapes</small>
        </div>
    </div>
</header>

<!-- TIMELINE -->
<div class="row mb-5">
    <div class="col-md-12">
        <div class="timeline-steps">
            <div class="timeline-step active" id="step1-indicator">
                <div class="step-circle">1</div>
                <div class="step-label">Informations personnelles</div>
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

<form method="post" id="frmAddClient">
    <input type="hidden" name="action" value="btn_add_client">
    <input type="hidden" name="csrf_token" value="<?= csrfToken()::token() ?>">
    <input type="hidden" name="selected_packs" id="selected_packs" value="">
    <!-- <input type="hidden" name="session_code" id="session_code" value="">
    <input type="hidden" name="categorie_pack_code" id="categorie_pack_code" value=""> -->

    <!-- ETAPE 1 : Informations personnelles -->
    <div class="step-content" id="step1">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Informations personnelles du client</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nom_client" class="form-label">Nom complet <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" id="nom_client" name="nom_client" placeholder="Ex: KOUAME Jean" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="telephone_client" class="form-label">Contact <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control telephone" id="telephone_client" name="telephone_client" placeholder="Ex: 0102030405" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="genre_client" class="form-label">Genre <strong class="text-danger">*</strong></label>
                        <select class="form-control select2" id="genre_client" name="genre_client" required>
                            <option value="">--- CHOISIR ---</option>
                            <option value="Masculin">Masculin</option>
                            <option value="Féminin">Féminin</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="lieu_client" class="form-label">Lieu de résidence <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" id="lieu_client" name="lieu_client" placeholder="Ex: Cocody" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="code_client" class="form-label">Code client <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" id="code_client" name="code_client" placeholder="Ex: CLI-001" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email_client" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email_client" name="email_client" placeholder="Ex: client@email.com">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="profession_client" class="form-label">Profession</label>
                        <input type="text" class="form-control" id="profession_client" name="profession_client" placeholder="Ex: Commerçant">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-primary btn-next" data-step="2">
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
                        <select style="background: #c5c5c5; color:#000" name="session_code" id="session_inscription" class="form-control">
                            <option >--CHOISIR--</option>
                            <?= chargerSessions(); ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                         <label for="">CATEGORIE</label>
                        <select style="background: #ccc; color:#000" name="categorie_code" id="categorie_inscription" class="form-control">
                            <option >Aucune donnée disponible</option>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="row" id="packs-container">
                    <div class="col-md-4 mb-4">
                        <div class="pack-card h-100" data-pack-code="PACK-001" data-pack-libelle="Pack Standard" data-pack-montant="25000">
                            <div class="card h-100 border-0 shadow-sm pack-card-inner">
                                <div class="card-body text-center py-4">
                                    <div class="pack-icon mb-3">
                                        <i class="fas fa-cube fa-3x text-primary"></i>
                                    </div>
                                    <h5 class="card-title font-weight-bold mb-2">Pack Standard</h5>
                                    <p class="text-muted small mb-3">Session Décembre 2025</p>
                                    <div class="pack-price mb-3">
                                        <span class="h4 text-primary font-weight-bold">25 000</span>
                                        <span class="text-muted">FCFA</span>
                                    </div>
                                    <div class="pack-features text-left mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-check text-success mr-1"></i> Accès aux cours
                                            <i class="fas fa-check text-success mr-1 ml-2"></i> Support pédagogique
                                            <i class="fas fa-check text-success mr-1 ml-2"></i> 1 mois d'accès
                                        </small>
                                    </div>
                                    <div class="form-check mt-auto">
                                        <input class="form-check-input pack-check" type="checkbox" value="PACK-001" id="pack1">
                                        <label class="form-check-label" for="pack1">Sélectionner ce pack</label>
                                    </div>
                                </div>
                                <div class="pack-badge">
                                    <span class="badge badge-primary">Populaire</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="pack-card h-100" data-pack-code="PACK-002" data-pack-libelle="Pack Premium" data-pack-montant="45000">
                            <div class="card h-100 border-0 shadow-sm pack-card-inner">
                                <div class="card-body text-center py-4">
                                    <div class="pack-icon mb-3">
                                        <i class="fas fa-crown fa-3x text-warning"></i>
                                    </div>
                                    <h5 class="card-title font-weight-bold mb-2">Pack Premium</h5>
                                    <p class="text-muted small mb-3">Session Décembre 2025</p>
                                    <div class="pack-price mb-3">
                                        <span class="h4 text-warning font-weight-bold">45 000</span>
                                        <span class="text-muted">FCFA</span>
                                    </div>
                                    <div class="pack-features text-left mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-check text-success mr-1"></i> Tout du Standard
                                            <i class="fas fa-check text-success mr-1 ml-2"></i> + Accès prioritaire
                                            <i class="fas fa-check text-success mr-1 ml-2"></i> 3 mois d'accès
                                        </small>
                                    </div>
                                    <div class="form-check mt-auto">
                                        <input class="form-check-input pack-check" type="checkbox" value="PACK-002" id="pack2">
                                        <label class="form-check-label" for="pack2">Sélectionner ce pack</label>
                                    </div>
                                </div>
                                <div class="pack-badge">
                                    <span class="badge badge-warning">Recommandé</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="pack-card h-100" data-pack-code="PACK-003" data-pack-libelle="Pack Excellence" data-pack-montant="75000">
                            <div class="card h-100 border-0 shadow-sm pack-card-inner">
                                <div class="card-body text-center py-4">
                                    <div class="pack-icon mb-3">
                                        <i class="fas fa-gem fa-3x text-danger"></i>
                                    </div>
                                    <h5 class="card-title font-weight-bold mb-2">Pack Excellence</h5>
                                    <p class="text-muted small mb-3">Session Décembre 2025</p>
                                    <div class="pack-price mb-3">
                                        <span class="h4 text-danger font-weight-bold">75 000</span>
                                        <span class="text-muted">FCFA</span>
                                    </div>
                                    <div class="pack-features text-left mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-check text-success mr-1"></i> Tout du Premium
                                            <i class="fas fa-check text-success mr-1 ml-2"></i> + Coaching individuel
                                            <i class="fas fa-check text-success mr-1 ml-2"></i> Accès illimité
                                        </small>
                                    </div>
                                    <div class="form-check mt-auto">
                                        <input class="form-check-input pack-check" type="checkbox" value="PACK-003" id="pack3">
                                        <label class="form-check-label" for="pack3">Sélectionner ce pack</label>
                                    </div>
                                </div>
                                <div class="pack-badge">
                                    <span class="badge badge-danger">Premium</span>
                                </div>
                            </div>
                        </div>
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
                        <div class="card-title">Informations personnelles</div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush" id="recap-infos">
                            <li class="list-group-item"><strong>Nom :</strong> <span id="recap-nom"></span></li>
                            <li class="list-group-item"><strong>Contact :</strong> <span id="recap-contact"></span></li>
                            <li class="list-group-item"><strong>Genre :</strong> <span id="recap-genre"></span></li>
                            <li class="list-group-item"><strong>Lieu de résidence :</strong> <span id="recap-lieu"></span></li>
                            <li class="list-group-item"><strong>Code client :</strong> <span id="recap-code"></span></li>
                            <li class="list-group-item"><strong>Email :</strong> <span id="recap-email"></span></li>
                            <li class="list-group-item"><strong>Profession :</strong> <span id="recap-profession"></span></li>
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
                <button type="submit" class="btn btn-success" id="btnSubmitFormClient">
                    <i class="fas fa-save"></i> &nbsp; Enregistrer l'inscription
                </button>
            </div>
        </div>
    </div>
</form>


