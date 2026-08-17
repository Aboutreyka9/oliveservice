<?= breakcrumb($title, 'fa-truck'); ?>

<header class="page-title-bar">
    <div class="header-dashboard d-flex align-items-center mb-4">
        <i class="fas fa-truck mr-3 me-3" style="font-size:20px;"></i>
        <div>
            <h4 class="mb-0">Distributions</h4>
            <small>Récupération des articles par les clients</small>
        </div>
    </div>
</header>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-end">
            <button type="button" id="btn_distribution_addModal" class="btn btn-primary w-25" title="Nouvelle distribution">
                <i class="fa fa-plus"></i> &nbsp; Nouvelle distribution
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive bg-light py-3 px-2 border rounded">
            <table id="data-table-distribution" class="table table-hover my-table">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Code distribution</th>
                        <th>Client</th>
                        <th>Inscription</th>
                        <th>Zone</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal distribution -->
<div class="modal fade" data-backdrop="static" id="distribution-modal" data-bs-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="distributionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="distributionModalLabel">
                    <i class="fa fa-truck"></i> &nbsp; 
                    <span class="text-uppercase">Formulaire de distribution</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="data-distribution-modal"></div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<script>
$(function() {
    $('#data-table-distribution').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= LINK ?>ajx.php',
            type: 'POST',
            data: function(d) {
                d.action = 'charger_data_distributions';
            }
        },
        columns: [
            { data: null, render: function(data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
            { data: 'code_distribution' },
            { data: 'nom_client' },
            { data: 'code_inscription' },
            { data: 'libelle_zone' },
            { data: 'statut_distribution', render: function(data) {
                if (data === 'valide') return '<span class="badge badge-success">Validé</span>';
                if (data === 'ennule') return '<span class="badge badge-danger">Annulé</span>';
                return '<span class="badge badge-warning">En attente</span>';
            }},
            { data: 'created_at_distribution', render: function(data) { return date_formater(data, true); } },
            { data: 'actions', orderable: false, searchable: false }
        ],
        order: [[6, 'desc']]
    });
});
</script>
