

<?= breakcrumb($title, 'fa-clipboard-list'); ?>




<!-- TABLEAU -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="card-title">Liste des annee</div>
            <div class="">
                 <button type="button" id="btn_annee_addModal" class="btn btn-primary  text-uppercase" title="Ajouter annee" aria-label="Close"> <i class="fa fa-plus-circle"></i> &nbsp; Créer
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive bg-light py-3 px-2 border rounded">
            <table id="data-table-annee" class="table table-striped table-hover bg-light">

                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th> STATUT </th>
                        <th> LIBELLE </th>
                        <th> DEBUT </th>
                        <th> FIN </th>
                        <th> TAUX </th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


<!-- Modal annee-->
<div class="modal fade" data-backdrop="static" id="annee-modal" data-bs-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="anneeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="anneeModalLabel"><i class="fa fa-user-circle"></i> &nbsp; <span
                        class="text-uppercase">Formulaire d'enregistrement</span> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="data-annee-modal">

                </div>
            </div>
            <!-- .modal-footer -->
            <div class="modal-footer">

            </div><!-- /.modal-footer -->
        </div>
    </div>
</div>
