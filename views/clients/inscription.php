<?= breakcrumb($title, 'fa-user-plus'); ?>

<header class="page-title-bar">
    <div class="header-dashboard d-flex align-items-center mb-4">
        <i class="fas fa-user-plus mr-3 me-3" style="font-size:20px;"></i>
        <div>
            <h4 class="mb-0">Inscription client</h4>
            <small>Enregistrement d'un nouveau client</small>
        </div>
    </div>
</header>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Informations du client</div>
            </div>
            <div class="card-body">
                <form method="post" id="frmAddClient">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom_client" class="form-label">Nom complet <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control" id="nom_client" name="nom_client" placeholder="Ex: KOUAME Jean" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telephone_client" class="form-label">Contact <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control telephone" id="telephone_client" name="telephone_client" placeholder="Ex: 0102030405" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="genre_client" class="form-label">Genre <strong class="text-danger">*</strong></label>
                            <select class="form-control select2" id="genre_client" name="genre_client" required>
                                <option value="">--- CHOISIR ---</option>
                                <option value="Masculin">Masculin</option>
                                <option value="Féminin">Féminin</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="lieu_client" class="form-label">Lieu de résidence <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control" id="lieu_client" name="lieu_client" placeholder="Ex: Cocody" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="code_client" class="form-label">Code client <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control" id="code_client" name="code_client" placeholder="Ex: CLI-001" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email_client" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email_client" name="email_client" placeholder="Ex: client@email.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="profession_client" class="form-label">Profession</label>
                            <input type="text" class="form-control" id="profession_client" name="profession_client" placeholder="Ex: Commerçant">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12 modal_footer">
                            <button type="submit" class="btn btn-primary" id="btnSubmitFormClient">
                                <i class="fas fa-save"></i> &nbsp; Enregistrer
                            </button>
                            <button type="button" class="btn btn-light dismiss_modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('.select2').select2({
            tags: "false",
            placeholder: "----CHOISIR----",
            allowClear: true,
            language: {
                noResults: function() {
                    return "Aucun résultat";
                }
            },
            createTag: function(params) {
                return null;
            }
        });

        $('form[id="frmAddClient"]').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = $('#btnSubmitFormClient');
            var originalText = btn.html();
            
            btn.html('<i class="fas fa-spinner fa-spin"></i> &nbsp; Enregistrement...').prop('disabled', true);
            
            $.ajax({
                url: APP.ajax,
                method: 'POST',
                data: form.serialize() + '&action=btn_add_client',
                dataType: 'JSON',
                success: function(data) {
                    if (data.success) {
                        $.notify(data.message, 'success');
                        form.trigger('reset');
                    } else {
                        $.notify(data.message, 'error');
                    }
                },
                error: function() {
                    $.notify('Désolé, une erreur est survenue', 'error');
                },
                complete: function() {
                    btn.html(originalText).prop('disabled', false);
                }
            });
        });
    });
</script>
