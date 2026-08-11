
<?= breakcrumb($title, 'fa-chart-bar '); ?>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-end">
            <button type="button" id="btn_pack_addModal" class="btn btn-primary w-25" title="Ajouter pack"
                aria-label="Close"> <i class="fa fa-plus"></i> &nbsp; Créer</button>
        </div>
    </div>
    <div class="card-body">

        <div class="table-responsive bg-light py-3 px-2 border rounded">
            <!-- .table -->
            <table id="data-table-pack" class="table table-hover my-table">
                <!-- thead -->
                <thead class="thead-light">
                    <tr>
                        <th> #</th>
                        <th> Statut</th>
                        <th> Libelle</th>
                        <th> Session</th>
                        <th> Categorie</th>
                        <th> Articles</th>
                        <th> Cautisation</th>
                        <th> Créer par</th>
                        <th> Créer le</th>
                        <th> Actions</th>
                    </tr>
                </thead><!-- /thead -->
            </table><!-- /.table -->
        </div><!-- /.table-responsive bg-light py-3 px-2 border rounded -->
    </div>
</div>



<!-- Modal pack-->
<div class="modal fade" data-backdrop="static" id="pack-modal" data-bs-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="packModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="packModalLabel"><i class="fa fa-user-circle"></i> &nbsp; <span
                        class="text-uppercase">Formulaire d'enregistrement</span> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="data-pack-modal">


                </div>
            </div>
            <!-- .modal-footer -->
            <div class="modal-footer mt-4">

            </div><!-- /.modal-footer -->
        </div>
    </div>
</div>