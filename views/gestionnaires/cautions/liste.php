<?php
$start = (new DateTime('first day of this month'))->format('Y-m-d');
$end = (new DateTime('today'))->format('Y-m-d');

$etablissementCode = auth()::user('etablissement_code');
$stats = [
    'total' => 0,
    'en_attente' => 0,
    'valide' => 0,
    'annule' => 0,
    'montant_total' => 0,
    'montant_en_attente' => 0,
    'montant_valide' => 0,
    'montant_annule' => 0,
];

try {
    $cautisationModel = new \App\Models\CautisationModel();
    $stats = $cautisationModel->getStatsCautions($etablissementCode);
} catch (Exception $e) {
    $stats = [
        'total' => 0,
        'en_attente' => 0,
        'valide' => 0,
        'annule' => 0,
        'montant_total' => 0,
        'montant_en_attente' => 0,
        'montant_valide' => 0,
        'montant_annule' => 0,
    ];
}

$sessions = [];
$zones = [];
try {
    $sessions = $cautisationModel->getFieldsForParams(TABLES::SESSIONS, ['etablissement_code' => $etablissementCode], [], true);
    $zones = $cautisationModel->getFieldsForParams(TABLES::ZONES, ['etablissement_code' => $etablissementCode], [], true);
} catch (Exception $e) {
    // Silently fail if tables don't exist
}
?>

<?= breakcrumb($title, 'fa-hand-holding-usd'); ?>

<header class="page-title-bar">
    <div class="header-dashboard d-flex align-items-center mb-4">
        <i class="fas fa-hand-holding-usd mr-3 me-3" style="font-size:20px;"></i>
        <div>
            <h4 class="mb-0">Cautions clients</h4>
            <small>Gestion des paiements journaliers de caution</small>
        </div>
    </div>
</header>

<!-- FILTRES -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="" id="filtres-cautions" class="form-inline">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Date début</label>
                            <input type="date" name="date_debut" class="form-control" id="date_debut" value="<?= htmlspecialchars($start) ?>">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Date fin</label>
                            <input type="date" name="date_fin" class="form-control" id="date_fin" value="<?= htmlspecialchars($end) ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Session</label>
                            <select name="session_code" class="form-control select2" id="session_code">
                                <option value="">Toutes les sessions</option>
                                <?php foreach ($sessions as $session): ?>
                                    <option value="<?= htmlspecialchars($session['code_session']) ?>"><?= htmlspecialchars($session['libelle_session']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Zone</label>
                            <select name="zone_code" class="form-control select2" id="zone_code">
                                <option value="">Toutes les zones</option>
                                <?php foreach ($zones as $zone): ?>
                                    <option value="<?= htmlspecialchars($zone['code_zone']) ?>"><?= htmlspecialchars($zone['libelle_zone']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Statut</label>
                            <select name="statut" class="form-control select2" id="statut">
                                <option value="">Tous</option>
                                <option value="En attente" selected>En attente</option>
                                <option value="valide">Validé</option>
                                <option value="ennule">Annulé</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-filter"></i> Filtrer
                            </button>
                            <button type="button" class="btn btn-default btn-sm" id="reset-filtres">
                                <i class="fas fa-undo"></i> Réinitialiser
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- STATS CARDS -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-primary mr-2">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Total cautions</h6>
                        <h5 class="montan-value"><?= number_format($stats['total']) ?></h5>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Montant total: <strong><?= number_format($stats['montant_total'], 0, ',', ' ') ?> FCFA</strong></small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-warning mr-2">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">En attente</h6>
                        <h5 class="montan-value"><?= number_format($stats['en_attente']) ?></h5>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Montant: <strong class="text-warning"><?= number_format($stats['montant_en_attente'], 0, ',', ' ') ?> FCFA</strong></small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-success mr-2">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Validées</h6>
                        <h5 class="montan-value"><?= number_format($stats['valide']) ?></h5>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Montant: <strong class="text-success"><?= number_format($stats['montant_valide'], 0, ',', ' ') ?> FCFA</strong></small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-danger mr-2">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Annulées</h6>
                        <h5 class="montan-value"><?= number_format($stats['annule']) ?></h5>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Montant: <strong class="text-danger"><?= number_format($stats['montant_annule'], 0, ',', ' ') ?> FCFA</strong></small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TABLEAU -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="card-title">Liste des cautions</div>
            <div class="d-flex gap-2">
                <a href="<?= url('cautions/encaisser') ?>" class="btn btn-success btn-sm" title="Encaisser une caution">
                    <i class="fa fa-money-bill-wave"></i> &nbsp; Encaisser caution
                </a>
                <button type="button" id="btn_cautisation_addModal" class="btn btn-primary btn-sm" title="Ajouter paiement">
                    <i class="fa fa-plus"></i> &nbsp; Enregistrer paiement
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive bg-light py-3 px-2 border rounded">
            <table id="data-table-cautisation" class="table table-hover my-table">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Code caution</th>
                        <th>Client</th>
                        <th>Contact</th>
                        <th>Session</th>
                        <th>Année</th>
                        <th>Zone</th>
                        <th>Montant payé</th>
                        <th>Total payé</th>
                        <th>Reste dû</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal cautisation -->
<div class="modal fade" data-backdrop="static" id="cautisation-modal" data-bs-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="cautisationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="cautisationModalLabel">
                    <i class="fa fa-hand-holding-usd"></i> &nbsp; 
                    <span class="text-uppercase">Formulaire de paiement caution</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="data-cautisation-modal"></div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<script>
function loadStats() {
    $.post('<?= LINK ?>ajx.php', {
        action: 'get_stats_cautions',
        date_debut: $('#date_debut').val(),
        date_fin: $('#date_fin').val(),
        session_code: $('#session_code').val(),
        zone_code: $('#zone_code').val()
    }, function(response) {
        if (response.success && response.data.stats) {
            // Les stats seront mises à jour côté serveur au rechargement de la page
            // Pour une mise à jour dynamique, on pourrait recharger la page ou mettre à jour les cartes ici
        }
    }, 'json');
}

$(function() {
    var table = $('#data-table-cautisation').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= LINK ?>ajx.php',
            type: 'POST',
            data: function(d) {
                d.action = 'charger_data_cautions';
                d.date_debut = $('#date_debut').val();
                d.date_fin = $('#date_fin').val();
                d.session_code = $('#session_code').val();
                d.zone_code = $('#zone_code').val();
                d.statut = $('#statut').val();
            }
        },
        columns: [
            { data: null, render: function(data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
            { data: 'code_cautisation_client', render: function(data) { return '<span class="badge badge-info">' + data + '</span>'; } },
            { data: 'nom_client' },
            { data: 'telephone_client' },
            { data: 'libelle_session' },
            { data: 'libelle_annee' },
            { data: 'libelle_zone' },
            { data: 'montant_cautisation_client', render: function(data) { return number_format(data, 0, ',', ' ') + ' FCFA'; } },
            { data: 'total_paye', render: function(data) { return number_format(data, 0, ',', ' ') + ' FCFA'; } },
            { data: 'reste', render: function(data) { 
                if (data > 0) {
                    return '<span class="text-danger">' + number_format(data, 0, ',', ' ') + ' FCFA</span>';
                }
                return '<span class="text-success">SOLDÉ</span>';
            }},
            { data: 'statut_cautisation_client', render: function(data) {
                if (data === 'valide') return '<span class="badge badge-success">Validé</span>';
                if (data === 'ennule') return '<span class="badge badge-danger">Annulé</span>';
                return '<span class="badge badge-warning">En attente</span>';
            }},
            { data: 'created_at_cautisation_client', render: function(data) { return date_formater(data, true); } },
            { data: 'actions', orderable: false, searchable: false }
        ],
        order: [[11, 'desc']]
    });

    $('#btn_cautisation_addModal').click(function() {
        $.post('<?= LINK ?>ajx.php', { action: 'btn_showmodal_cautisation_add' }, function(html) {
            $('.data-cautisation-modal').html(html.data);
            $('#cautisation-modal').modal('show');
        }, 'json');
    });

    $('#filtres-cautions').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
        loadStats();
    });

    $('#reset-filtres').click(function() {
        $('#date_debut').val('<?= htmlspecialchars($start) ?>');
        $('#date_fin').val('<?= htmlspecialchars($end) ?>');
        $('#session_code').val('');
        $('#zone_code').val('');
        $('#statut').val('');
        table.ajax.reload();
        loadStats();
    });
});

function validateCautisation(code, statut) {
    if (!confirm('Êtes-vous sûr de vouloir ' + (statut === 'valide' ? 'valider' : 'annuler') + ' cette cautisation ?')) return;
    
    $.post('<?= LINK ?>ajx.php', {
        action: 'btn_validate_cautisation',
        code_cautisation: code,
        statut_cautisation: statut
    }, function(response) {
        if (response.success) {
            $('#data-table-cautisation').DataTable().ajax.reload();
            alert(response.message);
        } else {
            alert(response.message);
        }
    }, 'json');
}
</script>
