<?php
// === DONNEES FICTIVES (demo) ===
$client = [
    'code_client' => 'CL_0042',
    'nom_client'  => 'Mboum Andre',
    'sexe_client' => 'M',
    'telephone_client' => '672501234',
    'lieu_residence_client' => 'Yaoundé, Cameroun',
    'email_client' => 'mboum.andre@example.com',
    'profession_client' => 'Commercial',
];

$cautions = [
    [
        'code_inscription' => 'INS_00042',
        'code_cautisation_client' => 'CCT_00018',
        'montant_cautisation_client' => 150000,
        'montant_restant' => 50000,
        'statut_cautisation_client' => 'partiel',
        'date_creation' => '2025-08-26 09:30:00',
        'session' => 'SESSION A',
    ],
    [
        'code_inscription' => 'INS_00041',
        'code_cautisation_client' => 'CCT_00017',
        'montant_cautisation_client' => 120000,
        'montant_restant' => 120000,
        'statut_cautisation_client' => 'non_soldee',
        'date_creation' => '2025-08-20 11:00:00',
        'session' => 'SESSION B',
    ],
];

$transactions = [
    [
        'code_transaction' => 'TR_00010',
        'montant' => 100000,
        'moyen' => 'espèces',
        'reference' => 'CNUM_0001',
        'date_transaction' => '2025-08-26 10:15:00',
        'operateur' => 'Commerçant Jean',
        'statut' => 'valide',
    ],
    [
        'code_transaction' => 'TR_00009',
        'montant' => 50000,
        'moyen' => 'mobile',
        'reference' => 'MTN_99887766',
        'date_transaction' => '2025-08-25 16:40:00',
        'operateur' => 'Commerçant Jean',
        'statut' => 'valide',
    ],
];

$montantTotalCautions = array_sum(array_column($cautions, 'montant_cautisation_client'));
$montantTotalPaye = array_sum(array_column($transactions, 'montant'));
$montantRestant = max(0, $montantTotalCautions - $montantTotalPaye);
?>

<?= breakcrumb($title ?? "Encaissement client", 'fa-hand-holding-usd'); ?>

<header class="page-title-bar">
    <div class="header-dashboard d-flex align-items-center mb-4">
        <i class="fas fa-hand-holding-usd mr-3 me-3" style="font-size:20px;"></i>
        <div>
            <h4 class="mb-0">Encaissement client</h4>
            <small>Gestion des paiements et cautions du client</small>
        </div>
    </div>
</header>

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
                        <h3 class="mb-1 text-uppercase"><?= htmlspecialchars($client['nom_client']) ?></h3>
                        <p class="text-muted mb-1">
                            <i class="fas fa-barcode mr-1"></i> <?= htmlspecialchars($client['code_client']) ?>
                            &nbsp;|&nbsp; <i class="fas fa-phone mr-1"></i> <?= htmlspecialchars($client['telephone_client']) ?>
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-map-marker-alt mr-1"></i> <?= htmlspecialchars($client['lieu_residence_client']) ?>
                            &nbsp;|&nbsp; <i class="fas fa-briefcase mr-1"></i> <?= htmlspecialchars($client['profession_client']) ?>
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

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-primary mr-2">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Montant total cautions</h6>
                        <h5 class="montan-value"><?= number_format($montantTotalCautions, 0, ',', ' ') ?> FCFA</h5>
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
                        <h6 class="montan-title">Total encaissé</h6>
                        <h5 class="montan-value"><?= number_format($montantTotalPaye, 0, ',', ' ') ?> FCFA</h5>
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
                        <h6 class="montan-title">Reste à encaisser</h6>
                        <h5 class="montan-value"><?= number_format($montantRestant, 0, ',', ' ') ?> FCFA</h5>
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
                <h4 class="mb-0">Récapitulatif cautions client</h4>
            </div>
            <div class="card-body">
                <div class="row text-center g-3">
                    <div class="col-md-4">
                        <h6 class="montan-title mb-1">Montant total cautions</h6>
                        <h5 class="montan-value text-primary"><?= number_format($montantTotalCautions, 0, ',', ' ') ?> FCFA</h5>
                    </div>
                    <div class="col-md-4">
                        <h6 class="montan-title mb-1">Total déjà encaissé</h6>
                        <h5 class="montan-value text-success"><?= number_format($montantTotalPaye, 0, ',', ' ') ?> FCFA</h5>
                    </div>
                    <div class="col-md-4">
                        <h6 class="montan-title mb-1">Reste à payer</h6>
                        <h5 class="montan-value text-danger"><?= number_format($montantRestant, 0, ',', ' ') ?> FCFA</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Cautions du client</h4>
                <button type="button" class="btn btn-success btn-sm" id="btn_encaisser_select">
                    <i class="fas fa-hand-holding-usd"></i> &nbsp; Encaisser
                </button>
            </div>
            <div class="card-body">
                <?php if (empty($cautions)): ?>
                    <p class="text-muted text-center py-4">Aucune caution enregistrée pour ce client.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="table-cautions-client">
                            <thead class="thead-light">
                                <tr>
                                    <th>
                                        <div class="form-check">
                                            <input class="form-check-input chk-select-all" type="checkbox">
                                        </div>
                                    </th>
                                    <th>#</th>
                                    <th>Code caution</th>
                                    <th>Inscription</th>
                                    <th>Session</th>
                                    <th>Montant total</th>
                                    <th>Restant</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cautions as $i => $c): ?>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input chk-caution" type="checkbox"
                                                    data-inscription="<?= htmlspecialchars($c['code_inscription']) ?>"
                                                    data-restant="<?= (int) $c['montant_restant'] ?>"
                                                    data-cotisation="<?= (int) $c['montant_cautisation_client'] ?>"
                                                    data-code="<?= htmlspecialchars($c['code_cautisation_client']) ?>"
                                                    data-statut="<?= htmlspecialchars($c['statut_cautisation_client']) ?>">
                                            </div>
                                        </td>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($c['code_cautisation_client']) ?></td>
                                        <td><?= htmlspecialchars($c['code_inscription']) ?></td>
                                        <td><?= htmlspecialchars($c['session']) ?></td>
                                        <td class="text-nowrap text-center"><?= number_format($c['montant_cautisation_client'], 0, ',', ' ') ?> FCFA</td>
                                        <td class="text-nowrap text-center"><?= number_format($c['montant_restant'], 0, ',', ' ') ?> FCFA</td>
                                        <td class="text-center">
                                            <?= checkStatusInscription($c['statut_cautisation_client'], ['non_soldee','partiel','valide']) ?>
                                        </td>
                                        <td><?= date_formater($c['date_creation'], true) ?></td>
                                        <td class="text-nowrap text-center">
                                            <button type="button" class="btn btn-sm btn-primary btn-encaisser-row"
                                                data-inscription="<?= htmlspecialchars($c['code_inscription']) ?>"
                                                data-restant="<?= (int) $c['montant_restant'] ?>"
                                                data-cotisation="<?= (int) $c['montant_cautisation_client'] ?>"
                                                data-code="<?= htmlspecialchars($c['code_cautisation_client']) ?>">
                                                <i class="fas fa-hand-holding-usd"></i> Encaisser
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0">Historique des encaissements</h4>
            </div>
            <div class="card-body">
                <?php if (empty($transactions)): ?>
                    <p class="text-muted text-center py-4">Aucun encaissement pour ce client.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Montant</th>
                                    <th>Moyen</th>
                                    <th>Référence</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $i => $t): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td class="text-nowrap"><?= number_format($t['montant'], 0, ',', ' ') ?> FCFA</td>
                                        <td><?= ucfirst($t['moyen']) ?></td>
                                        <td><small><?= htmlspecialchars($t['reference']) ?></small></td>
                                        <td><small><?= date_formater($t['date_transaction'], true) ?></small></td>
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

<!-- Modal encaissement -->
<div class="modal fade" data-backdrop="static" id="encaissement-modal" data-bs-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="encaissementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-encaissement" method="post" action="<?= LINK ?>ajx.php">
                <div class="modal-header">
                    <h5 class="modal-title text-dark" id="encaissementModalLabel">
                        <i class="fa fa-hand-holding-usd"></i> &nbsp;
                        <span class="text-uppercase">Encaisser caution</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="data-encaissement-modal">
                        <input type="hidden" name="action" value="btn_save_encaissement">
                        <input type="hidden" name="csrf_token" value="<?= csrfToken()::token() ?>">
                        <input type="hidden" name="code_client" id="enc_code_client" value="<?= htmlspecialchars($client['code_client']) ?>">
                        <input type="hidden" name="selected_cautions" id="enc_selected_cautions">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="enc_cotisation" class="form-label">Montant cotisation</label>
                                <input type="text" name="montant_cotisation" id="enc_cotisation" class="form-control" value="0 FCFA" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="enc_montant" class="form-label">Montant à encaisser <strong class="text-danger">*</strong></label>
                                <div class="input-group">
                                    <input type="number" name="montant" id="enc_montant" class="form-control" placeholder="0" min="1" required>
                                    <span class="input-group-text">FCFA</span>
                                </div>
                                <small class="text-muted">Montant total disponible : <strong id="enc_montant_disponible">0 FCFA</strong></small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Type d'encaissement <strong class="text-danger">*</strong></label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input enc-type" type="radio" name="type_encaissement" value="montant" id="enc_type_montant" checked>
                                        <label class="form-check-label" for="enc_type_montant">Par montant</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input enc-type" type="radio" name="type_encaissement" value="jours" id="enc_type_jours">
                                        <label class="form-check-label" for="enc_type_jours">Par nombre de jours</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" id="enc_jours_wrapper" style="display:none;">
                                <label for="enc_nb_jours" class="form-label">Nombre de jours</label>
                                <div class="input-group">
                                    <input type="number" name="nb_jours" id="enc_nb_jours" class="form-control" min="1" value="1">
                                    <span class="input-group-text">jours</span>
                                </div>
                                <small class="text-muted">Taux : <strong id="enc_taux_journalier">0 FCFA</strong></small>
                            </div>
                            <div class="col-md-6">
                                <label for="enc_moyen" class="form-label">Moyen de paiement <strong class="text-danger">*</strong></label>
                                <select name="moyen" id="enc_moyen" class="form-control select2">
                                    <option value="">--- CHOISIR ---</option>
                                    <option value="especes">Espèces</option>
                                    <option value="mobile">Mobile Money</option>
                                    <option value="cheque">Chèque</option>
                                    <option value="virement">Virement bancaire</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="enc_reference" class="form-label">Référence (optionnel)</label>
                                <input type="text" name="reference" id="enc_reference" class="form-control" placeholder="Numéro référence">
                            </div>
                            <div class="col-md-12" id="enc_cautions_list">
                                <label class="form-label">Cautions concernées</label>
                                <ul class="list-group" id="enc_caution_items"></ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-success" id="btn_submit_encaissement">
                        <i class="fas fa-save"></i> Encaisser
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

$(function() {
    const $selects = $('.chk-select-all');
    const $checkboxes = $('.chk-caution');
    const $montantDispo = $('#enc_montant_disponible');
    let montantTotalDispo = 0;

    // Calcul du montant disponible
    function updateMontantDisponible() {
        montantTotalDispo = 0;
        $('.chk-caution:checked').each(function() {
            montantTotalDispo += parseInt($(this).data('restant')) || 0;
        });
        $montantDispo.text(addSeparator(montantTotalDispo) + ' FCFA');
        $('#enc_montant').attr('max', montantTotalDispo);
        const codes = [];
        $('.chk-caution:checked').each(function() {
            codes.push($(this).data('code'));
        });
        $('#enc_selected_cautions').val(JSON.stringify(codes));
    }

    function addSeparator(nbr) {
        nbr += '';
        let sep = '';
        let partie1 = '';
        let partie2 = '';
        if (nbr.length > 3) {
            partie1 = nbr.slice(0, (nbr.length % 3));
            if (partie1.length === 0 && nbr.length > 0) {
                partie1 = nbr.slice(0, 3);
            }
            for (let i = 0; i < Math.floor(nbr.length / 3); i++) {
                if (i === 0 && nbr.length % 3 === 0) {
                    sep = '';
                } else {
                    sep += ' ';
                }
                partie2 = sep + nbr.slice(partie1.length + (i * 3) - (nbr.length % 3 || 3), partie1.length + (i + 1) * 3 - (nbr.length % 3 || 3));
            }
        }
        return (partie1 + (partie2 || '')).trim();
    }

    $selects.on('change', function() {
        const check = this.checked;
        $checkboxes.prop('checked', check);
        $('.btn-encaisser-row').prop('disabled', check);
        updateMontantDisponible();
    });

    $checkboxes.on('change', function() {
        $selects.prop('checked', $checkboxes.length === $('.chk-caution:checked').length);
        updateMontantDisponible();
    });

    // Ouvrir le modal en sélectionnant une ligne directement
    $('.btn-encaisser-row').on('click', function() {
        const inscription = $(this).data('inscription');
        const restant = parseInt($(this).data('restant')) || 0;
        const cotisation = parseInt($(this).data('cotisation')) || 0;
        const code = $(this).data('code');

        $checkboxes.prop('checked', false);
        $selects.prop('checked', false);

        const $cb = $('.chk-caution[data-code="' + code + '"]');
        $cb.prop('checked', true);
        $('.btn-encaisser-row').prop('disabled', false);

        const items = '<li class="list-group-item d-flex justify-content-between align-items-center">' +
            '<span>' + inscription + '</span>' +
            '<span class="badge badge-info">' + addSeparator(restant) + ' FCFA</span>' +
            '</li>';
        $('#enc_caution_items').html(items);

        $checkboxes.not($cb).prop('disabled', true);
        updateMontantDisponible();
        $('#enc_montant').val(restant);
        $('#enc_cotisation').val(addSeparator(cotisation) + ' FCFA');
        $('#enc_type_montant').prop('checked', true);
        $('#enc_jours_wrapper').hide();
        toggleMontantField();
    });

    // Ouvrir le modal en sélectionnant les cautions cochées
    $('#btn_encaisser_select').on('click', function() {
        const checked = $('.chk-caution:checked');
        if (checked.length === 0) {
            swal("Attention", "Veuillez sélectionner au moins une caution à encaisser.", "warning");
            return false;
        }

        let items = '';
        let total = 0;
        let cotisation = 0;
        checked.each(function() {
            const inscription = $(this).data('inscription');
            const restant = parseInt($(this).data('restant')) || 0;
            cotisation = parseInt($(this).data('cotisation')) || cotisation;
            total += restant;
            items += '<li class="list-group-item d-flex justify-content-between align-items-center">' +
                '<span>' + inscription + '</span>' +
                '<span class="badge badge-info">' + addSeparator(restant) + ' FCFA</span>' +
                '</li>';
        });
        $('#enc_caution_items').html(items);

        updateMontantDisponible();
        $('#enc_montant').val(total);
        $('#enc_cotisation').val(addSeparator(cotisation) + ' FCFA');
        $('#enc_type_montant').prop('checked', true);
        $('#enc_jours_wrapper').hide();
        toggleMontantField();
        $checkboxes.prop('disabled', true);

        $('#encaissement-modal').modal('show');
    });

    // Type d'encaissement : Par montant / Par nombre de jours
    function toggleMontantField() {
        const isJours = $('#enc_type_jours').is(':checked');
        if (isJours) {
            $('#enc_montant').prop('readonly', true);
            $('#enc_jours_wrapper').show();
            const cotisation = parseInt($('#enc_montant').val()) || 0;
            const dispo = montantTotalDispo || 0;
            updateTauxJournalier(cotisation, dispo);
        } else {
            $('#enc_montant').prop('readonly', false);
            $('#enc_jours_wrapper').hide();
        }
    }

    function updateTauxJournalier(cotisation, dispo) {
        const nbJours = parseInt($('#enc_nb_jours').val()) || 1;
        const taux = nbJours > 0 ? Math.round(dispo / nbJours) : 0;
        $('#enc_taux_journalier').text(addSeparator(taux) + ' FCFA');
        $('#enc_montant').val(taux * nbJours);
    }

    $('.enc-type').on('change', toggleMontantField);
    $('#enc_nb_jours').on('input change', function() {
        const isJours = $('#enc_type_jours').is(':checked');
        if (isJours) {
            const cotisation = parseInt($('#enc_montant').val()) || 0;
            const dispo = montantTotalDispo || 0;
            updateTauxJournalier(cotisation, dispo);
        }
    });

    // Reset du modal à la fermeture
    $('#encaissement-modal').on('hidden.bs.modal', function() {
        $checkboxes.prop('disabled', false);
        $checkboxes.prop('checked', false);
        $selects.prop('checked', false);
        montantTotalDispo = 0;
        $montantDispo.text('0 FCFA');
        $('#enc_caution_items').html('');
        $('#enc_selected_cautions').val('');
        $('#enc_montant').val('');
        $('#enc_cotisation').val('0 FCFA');
        $('#enc_montant').prop('readonly', false);
        $('#enc_type_montant').prop('checked', true);
        $('#enc_jours_wrapper').hide();
        $('#enc_nb_jours').val(1);
        $('#enc_taux_journalier').text('0 FCFA');
        $('#enc_moyen').val('').trigger('change');
        $('#enc_reference').val('');
    });

    // Soumission du formulaire (demo)
    $('#form-encaissement').on('submit', function(e) {
        e.preventDefault();
        const montant = parseInt($('#enc_montant').val()) || 0;
        const typeEncaissement = $('input[name="type_encaissement"]:checked').val();
        if (montant <= 0) {
            swal("Erreur", "Veuillez saisir un montant valide.", "error");
            return false;
        }
        const moyen = $('#enc_moyen').val();
        if (!moyen) {
            swal("Erreur", "Veuillez sélectionner un moyen de paiement.", "error");
            return false;
        }

        const typeLabel = typeEncaissement === 'jours' ? 'par ' + $('#enc_nb_jours').val() + ' jours' : 'par montant';
        const textConfirm = "Encaisser " + addSeparator(montant) + " FCFA (" + typeLabel + ") ?";
        swal({
            title: "Confirmation",
            text: textConfirm,
            icon: "info",
            buttons: ["Annuler", "Confirmer"],
            dangerMode: false
        }).then((confirm) => {
            if (confirm) {
                $.post($(this).attr('action'), $(this).serialize(), function(resp) {
                    if (resp && resp.success) {
                        swal("Succès", resp.message || "Encaissement effectué.", "success").then(() => {
                            $('#encaissement-modal').modal('hide');
                            location.reload();
                        });
                    } else {
                        swal("Erreur", (resp && resp.message) || "Une erreur est survenue.", "error");
                    }
                }, 'json');
            }
        });
    });
});

</script>
