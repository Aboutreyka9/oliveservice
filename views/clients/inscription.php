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
                <div class="row" id="packs-container">
                    <div class="col-md-4 mb-3">
                        <div class="card pack-card" data-pack-code="PACK-001" data-pack-libelle="Pack Standard" data-pack-montant="25000">
                            <div class="card-body text-center">
                                <h5 class="card-title">Pack Standard</h5>
                                <p class="text-muted">Session Décembre 2025</p>
                                <h4 class="text-primary">25 000 FCFA</h4>
                                <div class="form-check mt-3">
                                    <input class="form-check-input pack-check" type="checkbox" value="PACK-001" id="pack1">
                                    <label class="form-check-label" for="pack1">Sélectionner</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card pack-card" data-pack-code="PACK-002" data-pack-libelle="Pack Premium" data-pack-montant="45000">
                            <div class="card-body text-center">
                                <h5 class="card-title">Pack Premium</h5>
                                <p class="text-muted">Session Décembre 2025</p>
                                <h4 class="text-primary">45 000 FCFA</h4>
                                <div class="form-check mt-3">
                                    <input class="form-check-input pack-check" type="checkbox" value="PACK-002" id="pack2">
                                    <label class="form-check-label" for="pack2">Sélectionner</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card pack-card" data-pack-code="PACK-003" data-pack-libelle="Pack Excellence" data-pack-montant="75000">
                            <div class="card-body text-center">
                                <h5 class="card-title">Pack Excellence</h5>
                                <p class="text-muted">Session Décembre 2025</p>
                                <h4 class="text-primary">75 000 FCFA</h4>
                                <div class="form-check mt-3">
                                    <input class="form-check-input pack-check" type="checkbox" value="PACK-003" id="pack3">
                                    <label class="form-check-label" for="pack3">Sélectionner</label>
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
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Pack</th>
                                    <th>Montant</th>
                                </tr>
                            </thead>
                            <tbody id="recap-packs">
                                <tr>
                                    <td colspan="2" class="text-center text-muted">Aucun pack sélectionné</td>
                                </tr>
                            </tbody>
                        </table>
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

<style>
.timeline-steps {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px 0;
}

.timeline-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    opacity: 0.5;
    transition: all 0.3s ease;
}

.timeline-step.active {
    opacity: 1;
}

.timeline-step.completed {
    opacity: 1;
}

.step-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #e9ecef;
    border: 3px solid #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 18px;
    color: #6c757d;
    transition: all 0.3s ease;
}

.timeline-step.active .step-circle {
    background: #007bff;
    border-color: #007bff;
    color: #fff;
    box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.2);
}

.timeline-step.completed .step-circle {
    background: #28a745;
    border-color: #28a745;
    color: #fff;
}

.step-label {
    margin-top: 10px;
    font-size: 14px;
    font-weight: 600;
    color: #495057;
    text-align: center;
    white-space: nowrap;
}

.timeline-connector {
    flex: 1;
    height: 3px;
    background: #dee2e6;
    margin: 0 10px;
    margin-bottom: 25px;
    min-width: 80px;
}

.timeline-step.completed + .timeline-connector {
    background: #28a745;
}

.pack-card {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.pack-card:hover {
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
}

.pack-card.selected {
    border-color: #007bff;
    background-color: #f0f7ff;
}

.step-content {
    animation: fadeIn 0.4s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .timeline-steps {
        flex-direction: column;
        align-items: flex-start;
        padding: 10px 0;
        gap: 0;
    }
    
    .timeline-step {
        flex-direction: row;
        align-items: center;
        width: 100%;
        padding: 10px 0;
    }
    
    .step-circle {
        width: 40px;
        height: 40px;
        font-size: 16px;
        min-width: 40px;
    }
    
    .step-label {
        margin-top: 0;
        margin-left: 15px;
        font-size: 13px;
        text-align: left;
    }
    
    .timeline-connector {
        width: 3px;
        height: 35px;
        margin: 0;
        margin-left: 19px;
        min-width: auto;
    }
}

@media (max-width: 576px) {
    .step-circle {
        width: 35px;
        height: 35px;
        font-size: 14px;
        min-width: 35px;
    }
    
    .step-label {
        font-size: 12px;
        margin-left: 10px;
    }
    
    .timeline-connector {
        height: 25px;
        margin-left: 15px;
    }
}
</style>

