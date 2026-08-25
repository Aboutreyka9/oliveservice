

<?= breakcrumb($title, 'fa-clipboard-list'); ?>

<!-- FILTRES -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row my-flex">

                     <div class="col-md-3 mb-3">
                    <label class="form-label">Session</label>
                    <select id="session_filter_commercial" class="form-control select2">
                        <option value="">-- CHOISIR --</option>
                        <?= chargerSessions() ?>
                        
                    </select>
                    <!-- <button type="button" id="" class="btn btn-link btn-primary"> <i class="fas fa-filter"></i></button> -->
                </div>
                <div class="col-md-2 mb-3 ">
                    <button type="button" class="btn btn-dark">
                        <i class="fas fa-print"></i> Imprimer
                    </button>
                </div>
            </div>
               
            </div>
        </div>
    </div>
</div>

<!-- STATS CARDS SOUSCRIPTION COMMERCIAL -->
     <div class="row g-3 mb-4" id="sexion_stats_soucription_commercial">
        <?=    chargerStateSouscriptionForComercial() ?>
    </div>


<!-- TABLEAU -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="card-title">Liste des souscriptions</div>
            <div class="d-flex gap-4">
                <a href="<?= url('commercial/souscriptions')?>" type="button" id="btn_inscription_addModal" class="btn btn-primary mr-2" title="Ajouter souscription">
                    <i class="fa fa-plus"></i> &nbsp; Créer
                </a>
                <button type="button" class="btn btn-warning " title="Imprimer" onclick="imprimerListeInscription()">
                    <i class="fa fa-print"></i> &nbsp; Imprimer
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive bg-light py-3 px-2 border rounded">
            <table id="data-table-souscription-commercial" class="table table-hover my-table">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Client</th>
                        <th>Contact</th>
                        <th>Session</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tbody-souscription-commercial">
                    <?= chargerListeSouscriptionForComercial() ?>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal souscription-->
<div class="modal fade" data-backdrop="static" id="souscription-modal" data-bs-backdrop="static" tabindex="-1" role="dialog"
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
                <div class="data-souscription-modal"></div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<script>
$(function() {
    $('#data-table-souscription').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= LINK ?>ajx.php',
            type: 'POST',
            data: function(d) {
                d.action = 'charger_data_souscriptions';
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
            $('.data-souscription-modal').html(html.data);
            $('#souscription-modal').modal('show');
        }, 'json');
    });

    $('form').on('submit', function(e) {
        e.preventDefault();
        $('#data-table-souscription').DataTable().ajax.reload();
    });
});

function imprimerListeInscription() {
    var dateDebut = $('input[name="date_debut"]').val();
    var dateFin = $('input[name="date_fin"]').val();
    var zoneCode = $('select[name="zone_code"]').val();
    
    var url = '<?= LINK ?>ajx.php?action=imprimer_liste_souscriptions&date_debut=' + dateDebut + '&date_fin=' + dateFin + '&zone_code=' + zoneCode;
    window.open(url, '_blank');
}
</script>
