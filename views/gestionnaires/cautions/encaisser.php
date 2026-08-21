<?= breakcrumb($title, 'fa-hand-holding-usd'); ?>

<header class="page-title-bar">
    <div class="header-dashboard d-flex align-items-center mb-4">
        <i class="fas fa-hand-holding-usd mr-3 me-3" style="font-size:20px;"></i>
        <div>
            <h4 class="mb-0">Encaisser caution</h4>
            <small>Enregistrement des paiements journaliers de caution</small>
        </div>
    </div>
</header>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Rechercher un client</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" id="search_client" class="form-control" placeholder="Rechercher par nom, téléphone ou code client...">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="btn_search_client">
                                    <i class="fas fa-search"></i> Rechercher
                                </button>
                            </div>
                        </div>
                        <div id="search_results" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal encaissement -->
<div class="modal fade" data-backdrop="static" id="encaissement-modal" data-bs-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="encaissementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
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
                <div class="data-encaissement-modal"></div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<script>
var selectedInscription = null;

function searchClient() {
    var search = $('#search_client').val();
    if (search.length < 2) {
        $('#search_results').html('<div class="alert alert-warning">Veuillez saisir au moins 2 caractères</div>');
        return;
    }

    $.post('<?= LINK ?>ajx.php', {
        action: 'search_client_cautisation',
        search: search
    }, function(response) {
        if (response.success && response.data.clients.length > 0) {
            var html = '<div class="list-group">';
            response.data.clients.forEach(function(client) {
                html += '<div class="list-group-item list-group-item-action" data-code="' + client.code_client + '" data-nom="' + client.nom_client + '" data-telephone="' + client.telephone_client + '">';
                html += '<strong>' + client.nom_client + '</strong> - ' + client.telephone_client;
                html += '<br><small class="text-muted">Code: ' + client.code_client + ' | ' + client.sexe_client + ' | ' + (client.lieu_residence_client || '-') + '</small>';
                html += '</div>';
            });
            html += '</div>';
            $('#search_results').html(html);

            $('.list-group-item').click(function() {
                var code = $(this).data('code');
                var nom = $(this).data('nom');
                $('#selected_client').val(code);
                $('#selected_client_nom').val(nom);
                $('#search_results').html('<div class="alert alert-success"><i class="fas fa-check"></i> Client sélectionné: <strong>' + nom + '</strong> (' + code + ')</div>');
                loadInscriptions(code);
            });
        } else {
            $('#search_results').html('<div class="alert alert-info">Aucun client trouvé</div>');
        }
    }, 'json');
}

function loadInscriptions(clientCode) {
    $.post('<?= LINK ?>ajx.php', {
        action: 'get_souscriptions_client',
        client_code: clientCode
    }, function(response) {
        if (response.success && response.data.souscriptions.length > 0) {
            var html = '<div class="card mt-3"><div class="card-header"><strong>Souscriptions actives</strong></div><div class="card-body">';
            html += '<div class="table-responsive"><table class="table table-hover"><thead><tr><th>Session</th><th>Année</th><th>Zone</th><th>Montant pack</th><th>Total payé</th><th>Reste dû</th><th>Montant journalier</th><th>Action</th></tr></thead><tbody>';
            
            response.data.souscriptions.forEach(function(ins) {
                var montantPack = parseFloat(ins.montant_pack) || 0;
                var totalPaye = parseFloat(ins.total_paye_valide) || 0;
                var reste = Math.max(0, montantPack - totalPaye);
                var montantJournalier = ins.duree_jours_pack > 0 ? Math.ceil(montantPack / ins.duree_jours_pack) : 0;
                var joursRestants = montantJournalier > 0 ? Math.ceil(reste / montantJournalier) : 0;
                
                html += '<tr>';
                html += '<td>' + ins.libelle_session + '</td>';
                html += '<td>' + ins.libelle_annee + '</td>';
                html += '<td>' + ins.libelle_zone + '</td>';
                html += '<td>' + number_format(montantPack, 0, ',', ' ') + ' FCFA</td>';
                html += '<td>' + number_format(totalPaye, 0, ',', ' ') + ' FCFA</td>';
                html += '<td class="text-danger">' + number_format(reste, 0, ',', ' ') + ' FCFA</td>';
                html += '<td>' + number_format(montantJournalier, 0, ',', ' ') + ' FCFA/jour</td>';
                html += '<td><button class="btn btn-primary btn-sm" onclick="openEncaissementModal(\'' + ins.code_inscription + '\', \'' + ins.nom_client + '\', ' + montantPack + ', ' + totalPaye + ', ' + reste + ', ' + montantJournalier + ', ' + (ins.duree_jours_pack || 0) + ')"><i class="fas fa-money-bill-wave"></i> Encaisser</button></td>';
                html += '</tr>';
            });
            
            html += '</tbody></table></div></div></div>';
            $('#search_results').append(html);
        } else {
            $('#search_results').append('<div class="alert alert-warning mt-3">Aucune souscription active pour ce client</div>');
        }
    }, 'json');
}

function openEncaissementModal(codeIns, nomClient, montantPack, totalPaye, reste, montantJournalier, dureeJours) {
    selectedInscription = {
        code: codeIns,
        nom_client: nomClient,
        montant_pack: montantPack,
        total_paye: totalPaye,
        reste: reste,
        montant_journalier: montantJournalier,
        duree_jours: dureeJours
    };

    var html = '<form id="frmEncaissement">';
    html += '<input type="hidden" name="inscription_code" value="' + codeIns + '">';
    html += '<div class="row mb-3">';
    html += '<div class="col-md-12"><strong>Client: ' + nomClient + '</strong></div>';
    html += '<div class="col-md-12 mt-2"><div class="alert alert-info">';
    html += '<strong>Montant pack:</strong> ' + number_format(montantPack, 0, ',', ' ') + ' FCFA<br>';
    html += '<strong>Total payé:</strong> ' + number_format(totalPaye, 0, ',', ' ') + ' FCFA<br>';
    html += '<strong>Reste dû:</strong> <span class="text-danger">' + number_format(reste, 0, ',', ' ') + ' FCFA</span><br>';
    html += '<strong>Montant journalier:</strong> ' + number_format(montantJournalier, 0, ',', ' ') + ' FCFA<br>';
    html += '<strong>Jours restants estimés:</strong> ' + (montantJournalier > 0 ? Math.ceil(reste / montantJournalier) : 0) + ' jours';
    html += '</div></div>';
    html += '</div>';
    
    html += '<div class="row mb-3">';
    html += '<div class="col-md-12">';
    html += '<label class="form-label">Mode de calcul <strong class="text-danger">*</strong></label>';
    html += '<select class="form-control" id="mode_calcul" name="mode_calcul" onchange="toggleModeCalcul()" required>';
    html += '<option value="jours">Par nombre de jours</option>';
    html += '<option value="montant">Par montant</option>';
    html += '</select>';
    html += '</div>';
    html += '</div>';
    
    html += '<div class="row mb-3" id="div_nombre_jours">';
    html += '<div class="col-md-12">';
    html += '<label for="nombre_jours_cautisation" class="form-label">Nombre de jours <strong class="text-danger">*</strong></label>';
    html += '<input type="number" class="form-control" id="nombre_jours_cautisation" name="nombre_jours_cautisation" min="1" max="' + (dureeJours || 365) + '" onchange="calculerMontant()">';
    html += '<small class="text-muted">Max: ' + (dureeJours || 365) + ' jours</small>';
    html += '</div>';
    html += '</div>';
    
    html += '<div class="row mb-3" id="div_montant">';
    html += '<div class="col-md-12">';
    html += '<label for="montant_cautisation" class="form-label">Montant caution (FCFA) <strong class="text-danger">*</strong></label>';
    html += '<input type="number" class="form-control" id="montant_cautisation" name="montant_cautisation" min="1" max="' + reste + '" onchange="calculerJours()">';
    html += '<small class="text-muted">Max: ' + number_format(reste, 0, ',', ' ') + ' FCFA</small>';
    html += '</div>';
    html += '</div>';
    
    html += '<div class="row mb-3">';
    html += '<div class="col-md-6">';
    html += '<label for="periode_debut" class="form-label">Période début</label>';
    html += '<input type="date" class="form-control" id="periode_debut" name="periode_debut">';
    html += '</div>';
    html += '<div class="col-md-6">';
    html += '<label for="periode_fin" class="form-label">Période fin</label>';
    html += '<input type="date" class="form-control" id="periode_fin" name="periode_fin">';
    html += '</div>';
    html += '</div>';
    
    html += '<div class="row mb-3">';
    html += '<div class="col-md-12 alert alert-info" id="info_calcul">';
    html += '<strong>Calcul:</strong> ' + number_format(montantJournalier, 0, ',', ' ') + ' FCFA/jour × <span id="jours_calcules">0</span> jours = <strong id="montant_calcule">0</strong> FCFA';
    html += '</div>';
    html += '</div>';
    
    html += '<div class="row mb-3">';
    html += '<div class="col-md-12 modal_footer">';
    html += '<input type="hidden" name="action" value="btn_save_encaissement">';
    html += '<input type="hidden" name="csrf_token" value="<?= csrfToken()::token() ?>">';
    html += '<button type="submit" class="btn btn-primary" id="btnSubmitFormEncaissement">';
    html += '<i class="fas fa-save"></i> &nbsp; Enregistrer';
    html += '</button>';
    html += '<button type="button" class="btn btn-light dismiss_modal">Fermer</button>';
    html += '</div>';
    html += '</div>';
    html += '</form>';
    
    $('.data-encaissement-modal').html(html);
    $('#encaissement-modal').modal('show');
    
    $('#periode_debut').change(function() {
        var debut = $(this).val();
        if (debut && $('#nombre_jours_cautisation').val()) {
            var jours = parseInt($('#nombre_jours_cautisation').val());
            var dateFin = new Date(debut);
            dateFin.setDate(dateFin.getDate() + jours - 1);
            $('#periode_fin').val(dateFin.toISOString().split('T')[0]);
        }
    });
    
    $('#periode_fin').change(function() {
        var fin = $(this).val();
        if (fin && $('#periode_debut').val()) {
            var debut = new Date($('#periode_debut').val());
            var dateFin = new Date(fin);
            var diffTime = Math.abs(dateFin - debut);
            var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            $('#nombre_jours_cautisation').val(diffDays);
            calculerMontant();
        }
    });
}

function toggleModeCalcul() {
    var mode = $('#mode_calcul').val();
    if (mode === 'jours') {
        $('#div_nombre_jours').show();
        $('#div_montant').hide();
    } else {
        $('#div_nombre_jours').hide();
        $('#div_montant').show();
    }
}

function calculerMontant() {
    var jours = parseInt($('#nombre_jours_cautisation').val()) || 0;
    var montant = jours * selectedInscription.montant_journalier;
    $('#montant_cautisation').val(montant);
    $('#jours_calcules').text(jours);
    $('#montant_calcule').text(number_format(montant, 0, ',', ' ') + ' FCFA');
    
    if (jours > 0 && selectedInscription.montant_journalier > 0) {
        var debut = $('#periode_debut').val();
        if (debut) {
            var dateFin = new Date(debut);
            dateFin.setDate(dateFin.getDate() + jours - 1);
            $('#periode_fin').val(dateFin.toISOString().split('T')[0]);
        }
    }
}

function calculerJours() {
    var montant = parseFloat($('#montant_cautisation').val()) || 0;
    var jours = selectedInscription.montant_journalier > 0 ? Math.ceil(montant / selectedInscription.montant_journalier) : 0;
    $('#nombre_jours_cautisation').val(jours);
    $('#jours_calcules').text(jours);
    $('#montant_calcule').text(number_format(montant, 0, ',', ' ') + ' FCFA');
    
    if (jours > 0) {
        var debut = $('#periode_debut').val();
        if (debut) {
            var dateFin = new Date(debut);
            dateFin.setDate(dateFin.getDate() + jours - 1);
            $('#periode_fin').val(dateFin.toISOString().split('T')[0]);
        }
    }
}

$(function() {
    $('#btn_search_client').click(function() {
        searchClient();
    });
    
    $('#search_client').keypress(function(e) {
        if (e.which == 13) {
            searchClient();
        }
    });
    
    $('#frmEncaissement').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serializeArray();
        formData.push({name: 'action', value: 'btn_save_encaissement'});
        
        $.post('<?= LINK ?>ajx.php', formData, function(response) {
            if (response.success) {
                alert(response.message);
                $('#encaissement-modal').modal('hide');
                location.reload();
            } else {
                alert(response.message);
            }
        }, 'json');
    });
});
</script>
