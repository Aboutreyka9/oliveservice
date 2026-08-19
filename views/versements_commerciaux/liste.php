<?php
$start = (new DateTime('first day of this month'))->format('Y-m-d');
$end = (new DateTime('today'))->format('Y-m-d');

$etablissementCode = auth()::user('etablissement_code');
$stats = [
    'total' => 0,
    'en_attente' => 0,
    'valide' => 0,
    'rejete' => 0,
    'montant_total' => 0,
    'montant_en_attente' => 0,
    'montant_valide' => 0,
    'montant_rejete' => 0,
];

try {
    $versementModel = new \App\Models\VersementCommercialModel();
    $stats = $versementModel->getStatsVersements($etablissementCode);
} catch (Exception $e) {
    $stats = [
        'total' => 0,
        'en_attente' => 0,
        'valide' => 0,
        'rejete' => 0,
        'montant_total' => 0,
        'montant_en_attente' => 0,
        'montant_valide' => 0,
        'montant_rejete' => 0,
    ];
}

$commercials = [];
$zones = [];
try {
    $commercials = $versementModel->getFieldsForParams(TABLES::USERS, ['etablissement_code' => $etablissementCode, 'statut_user' => 'actif'], [], true);
    $zones = $versementModel->getFieldsForParams(TABLES::ZONES, ['etablissement_code' => $etablissementCode, 'statut_zone' => 'actif'], [], true);
} catch (Exception $e) {
    // Silently fail if tables don't exist
}
?>

<?= breakcrumb($title, 'fa-money-bill-wave'); ?>

<header class="page-title-bar">
    <div class="header-dashboard d-flex align-items-center mb-4">
        <i class="fas fa-money-bill-wave mr-3 me-3" style="font-size:20px;"></i>
        <div>
            <h4 class="mb-0">Versements commerciaux</h4>
            <small>Dépôts des commerciaux au bureau</small>
        </div>
    </div>
</header>

<!-- FILTRES -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="" id="filtres-versements" class="form-inline">
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
                            <label class="form-label">Commercial</label>
                            <select name="commercial_code" class="form-control select2" id="commercial_code">
                                <option value="">Tous les commerciaux</option>
                                <?php foreach ($commercials as $commercial): ?>
                                    <option value="<?= htmlspecialchars($commercial['code_user']) ?>"><?= htmlspecialchars($commercial['nom_user'] . ' ' . $commercial['prenom_user']) ?></option>
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
                                <option value="en_attente" selected>En attente</option>
                                <option value="valide">Validé</option>
                                <option value="rejete">Rejeté</option>
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
                        <h6 class="montan-title">Total versements</h6>
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
                        <h6 class="montan-title">Validés</h6>
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
                        <h6 class="montan-title">Rejetés</h6>
                        <h5 class="montan-value"><?= number_format($stats['rejete']) ?></h5>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Montant: <strong class="text-danger"><?= number_format($stats['montant_rejete'], 0, ',', ' ') ?> FCFA</strong></small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TABLEAU -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="card-title">Liste des versements commerciaux</div>
            <div class="d-flex gap-2">
                <button type="button" id="btn_versement_addModal" class="btn btn-primary btn-sm" title="Ajouter versement">
                    <i class="fa fa-plus"></i> &nbsp; Enregistrer versement
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive bg-light py-3 px-2 border rounded">
            <table id="data-table-versement" class="table table-hover my-table">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Référence</th>
                        <th>Commercial</th>
                        <th>Zone</th>
                        <th>Montant</th>
                        <th>Période</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal versement -->
<div class="modal fade" data-backdrop="static" id="versement-modal" data-bs-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="versementModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="versementModalLabel">
                    <i class="fa fa-money-bill-wave"></i> &nbsp; 
                    <span class="text-uppercase">Formulaire de versement</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="data-versement-modal"></div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<script>
function loadStats() {
    $.post('<?= LINK ?>ajx.php', {
        action: 'get_stats_versements',
        commercial_code: $('#commercial_code').val(),
        zone_code: $('#zone_code').val(),
        statut: $('#statut').val(),
        date_debut: $('#date_debut').val(),
        date_fin: $('#date_fin').val()
    }, function(response) {
        if (response.success && response.data.stats) {
            // Les stats seront mises à jour côté serveur au rechargement de la page
            // Pour une mise à jour dynamique, on pourrait recharger la page ou mettre à jour les cartes ici
        }
    }, 'json');
}

$(function() {
    var table = $('#data-table-versement').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= LINK ?>ajx.php',
            type: 'POST',
            data: function(d) {
                d.action = 'charger_data_versements_commerciaux';
                d.commercial_code = $('#commercial_code').val();
                d.zone_code = $('#zone_code').val();
                d.statut = $('#statut').val();
                d.date_debut = $('#date_debut').val();
                d.date_fin = $('#date_fin').val();
            }
        },
        columns: [
            { data: null, render: function(data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
            { data: 'reference_versement', render: function(data) { return '<span class="badge badge-info">' + data + '</span>'; } },
            { data: 'nom_commercial' },
            { data: 'libelle_zone' },
            { data: 'montant_versement', render: function(data) { return number_format(data, 0, ',', ' ') + ' FCFA'; } },
            { data: 'periode_versement' },
            { data: 'statut_versement', render: function(data) {
                if (data === 'valide') return '<span class="badge badge-success">Validé</span>';
                if (data === 'rejete') return '<span class="badge badge-danger">Rejeté</span>';
                return '<span class="badge badge-warning">En attente</span>';
            }},
            { data: 'created_at_versement', render: function(data) { return date_formater(data, true); } },
            { data: 'actions', orderable: false, searchable: false }
        ],
        order: [[7, 'desc']]
    });

    $('#btn_versement_addModal').click(function() {
        $.post('<?= LINK ?>ajx.php', { action: 'btn_showmodal_versement_commercial_add' }, function(html) {
            $('.data-versement-modal').html(html.data);
            $('#versement-modal').modal('show');
        }, 'json');
    });

    $('#filtres-versements').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
        loadStats();
    });

    $('#reset-filtres').click(function() {
        $('#date_debut').val('<?= htmlspecialchars($start) ?>');
        $('#date_fin').val('<?= htmlspecialchars($end) ?>');
        $('#commercial_code').val('');
        $('#zone_code').val('');
        $('#statut').val('');
        table.ajax.reload();
        loadStats();
    });
});

function validateVersement(code, statut) {
    if (!confirm('Êtes-vous sûr de vouloir ' + (statut === 'valide' ? 'valider' : 'rejeter') + ' ce versement ?')) return;
    
    $.post('<?= LINK ?>ajx.php', {
        action: 'btn_validate_versement_commercial',
        code_versement: code,
        statut_versement: statut
    }, function(response) {
        if (response.success) {
            $('#data-table-versement').DataTable().ajax.reload();
            alert(response.message);
        } else {
            alert(response.message);
        }
    }, 'json');
}
</script>
