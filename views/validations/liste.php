<?= breakcrumb($title, 'fa-check-circle'); ?>

<header class="page-title-bar">
    <div class="header-dashboard d-flex align-items-center mb-4">
        <i class="fas fa-check-circle mr-3 me-3" style="font-size:20px;"></i>
        <div>
            <h4 class="mb-0">Validations</h4>
            <small>Validations des versements commerciaux en attente</small>
        </div>
    </div>
</header>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="card-title">Versements en attente de validation</div>
            <span class="badge badge-warning" id="count-pending">0</span>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive bg-light py-3 px-2 border rounded">
            <table id="data-table-validation" class="table table-hover my-table">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Référence</th>
                        <th>Commercial</th>
                        <th>Zone</th>
                        <th>Montant</th>
                        <th>Période</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
$(function() {
    var table = $('#data-table-validation').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= LINK ?>ajx.php',
            type: 'POST',
            data: function(d) {
                d.action = 'charger_data_validations';
            }
        },
        columns: [
            { data: null, render: function(data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
            { data: 'reference_versement' },
            { data: 'nom_commercial' },
            { data: 'libelle_zone' },
            { data: 'montant_versement', render: function(data) { return number_format(data, 0, ',', ' ') + ' FCFA'; } },
            { data: 'periode_versement' },
            { data: 'created_at_versement', render: function(data) { return date_formater(data, true); } },
            { data: 'actions', orderable: false, searchable: false }
        ],
        order: [[7, 'desc']],
        drawCallback: function() {
            $('#count-pending').text(this.api().data().count());
        }
    });
});
</script>
