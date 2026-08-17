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

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-end">
            <button type="button" id="btn_cautisation_addModal" class="btn btn-primary w-25" title="Ajouter cautisation">
                <i class="fa fa-plus"></i> &nbsp; Enregistrer paiement
            </button>
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
                        <th>Zone</th>
                        <th>Montant payé</th>
                        <th>Total payé</th>
                        <th>Reste dû</th>
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
$(function() {
    $('#data-table-cautisation').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= LINK ?>ajx.php',
            type: 'POST',
            data: function(d) {
                d.action = 'charger_data_cautions';
            }
        },
        columns: [
            { data: null, render: function(data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
            { data: 'code_cautisation_client' },
            { data: 'nom_client' },
            { data: 'telephone_client' },
            { data: 'libelle_session' },
            { data: 'libelle_zone' },
            { data: 'montant_cautisation_client', render: function(data) { return number_format(data, 0, ',', ' ') + ' FCFA'; } },
            { data: 'total_paye', render: function(data) { return number_format(data, 0, ',', ' ') + ' FCFA'; } },
            { data: 'reste', render: function(data) { return number_format(data, 0, ',', ' ') + ' FCFA'; } },
            { data: 'created_at_cautisation_client', render: function(data) { return date_formater(data, true); } },
            { data: 'actions', orderable: false, searchable: false }
        ],
        order: [[9, 'desc']]
    });

    $('#btn_cautisation_addModal').click(function() {
        $.post('<?= LINK ?>ajx.php', { action: 'btn_showmodal_cautisation_add' }, function(html) {
            $('.data-cautisation-modal').html(html.data);
            $('#cautisation-modal').modal('show');
        }, 'json');
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
