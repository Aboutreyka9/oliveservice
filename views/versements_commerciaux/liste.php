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

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-end">
            <button type="button" id="btn_versement_addModal" class="btn btn-primary w-25" title="Ajouter versement">
                <i class="fa fa-plus"></i> &nbsp; Enregistrer versement
            </button>
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
$(function() {
    $('#data-table-versement').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= LINK ?>ajx.php',
            type: 'POST',
            data: function(d) {
                d.action = 'charger_data_versements_commerciaux';
            }
        },
        columns: [
            { data: null, render: function(data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
            { data: 'reference_versement' },
            { data: 'nom_commercial' },
            { data: 'libelle_zone' },
            { data: 'montant_versement', render: function(data) { return number_format(data, 0, ',', ' ') + ' FCFA'; } },
            { data: 'periode_versement' },
            { data: 'statut_versement' },
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
