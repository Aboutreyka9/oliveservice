<?php
$start = (new DateTime('first day of this month'))->format('Y-m-d');
$end = (new DateTime('today'))->format('Y-m-d');
$dateD = (new DateTime('first day of this month'))->format('d-m-Y');
$dateF = (new DateTime('today'))->format('d-m-Y');

$etablissementCode = auth()::user('etablissement_code');
$anneeCode = auth()::user('annee_code');
$zoneCode = auth()::user('zone_code');

$stats = [
    'total' => 0,
    'valide' => 0,
    'en_attente' => 0,
    'annule' => 0,
    'montant_total' => 0,
    'montant_valide' => 0,
    'montant_en_attente' => 0,
    'montant_annule' => 0,
];

$clientModel = new \App\Models\ClientModel();
$stats = $clientModel->getStatsInscriptions($etablissementCode, $anneeCode, $zoneCode, $start, $end);
?>

<?= breakcrumb($title, 'fa-clipboard-list'); ?>

<header class="page-title-bar">
    <div class="header-dashboard d-flex align-items-center mb-4">
        <i class="fas fa-clipboard-list mr-3 me-3" style="font-size:20px;"></i>
        <div>
            <h4 class="mb-0">Liste des souscriptions</h4>
            <small>Gestion des inscriptions clients</small>
        </div>
    </div>
</header>

<!-- FILTRES -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="" class="form-inline">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Date début</label>
                            <input type="date" name="date_debut" class="form-control" value="<?= htmlspecialchars($start) ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Date fin</label>
                            <input type="date" name="date_fin" class="form-control" value="<?= htmlspecialchars($end) ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Zone</label>
                            <select name="zone_code" class="form-control select2">
                                <option value="">Toutes les zones</option>
                                <?php
                                $zones = $clientModel->getFieldsForParams(TABLES::ZONES, ['etablissement_code' => $etablissementCode], [], true);
                                foreach ($zones as $zone) {
                                    $selected = selected($zone['code_zone'], $zoneCode);
                                    echo '<option ' . $selected . ' value="' . $zone['code_zone'] . '">' . htmlspecialchars($zone['libelle_zone']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter"></i> Filtrer
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
                        <h6 class="montan-title">Total inscriptions</h6>
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
            <div class="card-title">Liste des souscriptions</div>
            <div class="d-flex gap-2">
                <button type="button" id="btn_inscription_addModal" class="btn btn-primary btn-sm" title="Ajouter inscription">
                    <i class="fa fa-plus"></i> &nbsp; Créer
                </button>
                <button type="button" class="btn btn-success btn-sm" title="Imprimer" onclick="imprimerListeInscription()">
                    <i class="fa fa-print"></i> &nbsp; Imprimer
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive bg-light py-3 px-2 border rounded">
            <table id="data-table-inscription" class="table table-hover my-table">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Code inscription</th>
                        <th>Client</th>
                        <th>Contact</th>
                        <th>Session</th>
                        <th>Année</th>
                        <th>Zone</th>
                        <th>Montant pack</th>
                        <th>Montant payé</th>
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

<!-- Modal inscription-->
<div class="modal fade" data-backdrop="static" id="inscription-modal" data-bs-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="inscriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="inscriptionModalLabel">
                    <i class="fa fa-user-circle"></i> &nbsp; 
                    <span class="text-uppercase">Formulaire d'enregistrement</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="data-inscription-modal"></div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<script>
$(function() {
    $('#data-table-inscription').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= LINK ?>ajx.php',
            type: 'POST',
            data: function(d) {
                d.action = 'charger_data_inscriptions';
                d.date_debut = $('input[name="date_debut"]').val();
                d.date_fin = $('input[name="date_fin"]').val();
                d.zone_code = $('select[name="zone_code"]').val();
            }
        },
        columns: [
            { data: null, render: function(data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
            { data: 'code_inscription', render: function(data) { return '<span class="badge badge-info">' + data + '</span>'; } },
            { data: 'nom_client' },
            { data: 'telephone_client' },
            { data: 'libelle_session' },
            { data: 'libelle_annee' },
            { data: 'libelle_zone' },
            { data: 'montant_pack', render: function(data) { return number_format(data, 0, ',', ' ') + ' FCFA'; } },
            { data: 'montant_paye', render: function(data) { return number_format(data, 0, ',', ' ') + ' FCFA'; } },
            { data: 'reste_du', render: function(data) { 
                if (data > 0) {
                    return '<span class="text-danger">' + number_format(data, 0, ',', ' ') + ' FCFA</span>';
                }
                return '<span class="text-success">SOLDÉ</span>';
            }},
            { data: 'statut_inscription', render: function(data) { return checkStatusInscription(data); } },
            { data: 'created_at_inscription', render: function(data) { return date_formater(data, true); } },
            { data: 'actions', orderable: false, searchable: false }
        ],
        order: [[11, 'desc']]
    });

    $('#btn_inscription_addModal').click(function() {
        $.post('<?= LINK ?>ajx.php', { action: 'btn_showmodal_inscription_add' }, function(html) {
            $('.data-inscription-modal').html(html.data);
            $('#inscription-modal').modal('show');
        }, 'json');
    });

    $('form').on('submit', function(e) {
        e.preventDefault();
        $('#data-table-inscription').DataTable().ajax.reload();
    });
});

function imprimerListeInscription() {
    var dateDebut = $('input[name="date_debut"]').val();
    var dateFin = $('input[name="date_fin"]').val();
    var zoneCode = $('select[name="zone_code"]').val();
    
    var url = '<?= LINK ?>ajx.php?action=imprimer_liste_inscriptions&date_debut=' + dateDebut + '&date_fin=' + dateFin + '&zone_code=' + zoneCode;
    window.open(url, '_blank');
}
</script>
