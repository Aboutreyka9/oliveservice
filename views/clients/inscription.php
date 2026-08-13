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
    <input type="hidden" name="session_code" id="session_code" value="">
    <input type="hidden" name="categorie_pack_code" id="categorie_pack_code" value="">

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
                <div style="display: flex;align-items: flex-end;" class="row mb-5">
                    <div class="col-md-5">
                        <label for="">SESSION</label>
                        <select style="background: #c5c5c5; color:#000" name="" id="session_inscription" class="form-control">
                            <option >--CHOISIR--</option>
                            <?= chargerSessions(); ?>
                        </select>
                    </div>
                    <div class="col-md-5">
                         <label for="">CATEGORIE</label>
                        <select style="background: #ccc; color:#000" name="" id="categorie_inscription" class="form-control">
                            <option >Aucune donnée disponible</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button disabled type="button" class="btn btn-outline-dark" id="btn_selection_choix"><i class="fa fa-search"></i> Rechercher</button>
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

<script>
    $(function() {
        let currentStep = 1;
        const totalSteps = 3;

        $('.btn-next').click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (validateStep(currentStep)) {
                goToStep(currentStep + 1);
            }
        });

        $('.btn-prev').click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            goToStep(currentStep - 1);
        });

        function goToStep(step) {
            if (step < 1 || step > totalSteps) return;

            $('#step' + currentStep).addClass('d-none');
            $('#step' + step).removeClass('d-none');
            
            currentStep = step;
            updateTimeline();
            updateRecap();
            
            $('html, body').animate({
                scrollTop: $('.timeline-steps').offset().top - 20
            }, 300);
        }

        function updateTimeline() {
            for (let i = 1; i <= totalSteps; i++) {
                const indicator = $('#step' + i + '-indicator');
                indicator.removeClass('active completed');
                
                if (i < currentStep) {
                    indicator.addClass('completed');
                } else if (i === currentStep) {
                    indicator.addClass('active');
                }
            }
        }

        function validateStep(step) {
            if (step === 1) {
                let valid = true;
                const requiredFields = ['nom_client', 'telephone_client', 'lieu_client', 'code_client'];
                
                requiredFields.forEach(function(field) {
                    const value = $('#' + field).val().trim();
                    if (!value) {
                        valid = false;
                        $('#' + field).addClass('is-invalid');
                    } else {
                        $('#' + field).removeClass('is-invalid');
                    }
                });

                const genreVal = $('#genre_client').val();
                if (!genreVal) {
                    valid = false;
                    $('#genre_client').addClass('is-invalid');
                } else {
                    $('#genre_client').removeClass('is-invalid');
                }
                
                if (!valid) {
                    $.notify('Veuillez remplir tous les champs obligatoires', 'error');
                    $('html, body').animate({
                        scrollTop: $('.is-invalid').first().offset().top - 100
                    }, 300);
                }
                
                return valid;
            }
            
            if (step === 2) {
                const checkedPacks = $('.pack-check:checked').length;
                if (checkedPacks === 0) {
                    $.notify('Veuillez sélectionner au moins un pack', 'error');
                    return false;
                }
                return true;
            }
            
            return true;
        }

        function updateRecap() {
            if (currentStep === 3) {
                $('#recap-nom').text($('#nom_client').val());
                $('#recap-contact').text($('#telephone_client').val());
                $('#recap-genre').text($('#genre_client').find('option:selected').text());
                $('#recap-lieu').text($('#lieu_client').val());
                $('#recap-code').text($('#code_client').val());
                $('#recap-email').text($('#email_client').val() || 'Non renseigné');
                $('#recap-profession').text($('#profession_client').val() || 'Non renseigné');

                const selectedPacks = [];
                let totalMontant = 0;
                
                $('.pack-check:checked').each(function() {
                    const card = $(this).closest('.pack-card');
                    const libelle = card.data('pack-libelle');
                    const montant = card.data('pack-montant');
                    selectedPacks.push({ libelle: libelle, montant: montant });
                    totalMontant += parseInt(montant);
                });

                const tbody = $('#recap-packs');
                tbody.empty();
                
                if (selectedPacks.length === 0) {
                    tbody.append('<tr><td colspan="2" class="text-center text-muted">Aucun pack sélectionné</td></tr>');
                } else {
                    selectedPacks.forEach(function(pack) {
                        tbody.append('<tr><td>' + pack.libelle + '</td><td>' + Number(pack.montant).toLocaleString('fr-FR') + ' FCFA</td></tr>');
                    });
                    tbody.append('<tr class="table-active"><td class="font-weight-bold">Total</td><td class="font-weight-bold">' + totalMontant.toLocaleString('fr-FR') + ' FCFA</td></tr>');
                }
            }
        }

        $('.pack-card').click(function(e) {
            if (e.target.type !== 'checkbox') {
                const checkbox = $(this).find('.pack-check');
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
            $(this).toggleClass('selected', $(this).find('.pack-check').prop('checked'));
        });

        $('.pack-check').change(function() {
            $(this).closest('.pack-card').toggleClass('selected', $(this).prop('checked'));
        });

        $('#session_inscription').change(function() {
            const sessionCode = $(this).val();
            $('#session_code').val(sessionCode);
            loadCategoriesBySession(sessionCode, '#categorie_inscription');
            $('#categorie_pack_code').val('');
        });

        $('#categorie_inscription').change(function() {
            $('#categorie_pack_code').val($(this).val());
        });

        $('form[id="frmAddClient"]').submit(function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (!validateStep(currentStep)) {
                return;
            }

            const selectedPacks = [];
            $('.pack-check:checked').each(function() {
                selectedPacks.push($(this).val());
            });
            $('#selected_packs').val(JSON.stringify(selectedPacks));

            var form = $(this);
            var btn = $('#btnSubmitFormClient');
            var originalText = btn.html();
            
            btn.html('<i class="fas fa-spinner fa-spin"></i> &nbsp; Enregistrement...').prop('disabled', true);
            
            $.ajax({
                url: APP.ajax,
                method: 'POST',
                data: form.serialize(),
                dataType: 'JSON',
                success: function(data) {
                    if (data.success) {
                        $.notify(data.message, 'success');
                        setTimeout(function() {
                            window.location.href = '<?= url('clients') ?>';
                        }, 1500);
                    } else {
                        $.notify(data.message, 'error');
                        btn.html(originalText).prop('disabled', false);
                    }
                },
                error: function() {
                    $.notify('Désolé, une erreur est survenue', 'error');
                    btn.html(originalText).prop('disabled', false);
                }
            });
        });

        $('.select2').select2({
            tags: "false",
            placeholder: "----CHOISIR----",
            allowClear: true,
            language: {
                noResults: function() {
                    return "Aucun résultat";
                }
            },
            createTag: function(params) {
                return null;
            }
        });
    });
</script>


