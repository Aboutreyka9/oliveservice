
<?= breakcrumb($title, 'fa-chart-bar '); ?>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-end">
            <button type="button" id="btn_client_adddModal" class="btn btn-primary " title="Ajouter client"
                aria-label="Close"> <i class="fa fa-plus"></i> &nbsp; ENREGISTRER</button>
                <button  type="button" id="btn_client_adddModa" class="btn btn-dark " title="Ajouter client"
                aria-label="Close"> <i class="fa fa-print"></i> &nbsp; IMPRIMER</button>
        </div>
    </div>
    <div class="card-body">

        <div class="table-responsive bg-light py-3 px-2 border rounded">
            <!-- .table -->
            <table id="data-table-client" class="table table-hover my-table">
                <!-- thead -->
                <thead class="thead-light">
                    <tr>
                        <th> #</th>
                        <th>Nom & prénoms</th>
                        <th>Contact</th>
                        <th>Genre</th>
                        <th>Lieu de résidence</th>
                        <th>Code client</th>
                        <th>Enregistré par</th>
                        <th>Enregistré le</th>
                        <th> Actions</th>
                    </tr>
                </thead><!-- /thead -->
            </table><!-- /.table -->
        </div><!-- /.table-responsive bg-light py-3 px-2 border rounded -->
    </div>
</div>

<!-- Modal categorie_pack-->
<div class="modal fade" data-backdrop="static" id="client-modal" data-bs-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="clientModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="clientModalLabel"><i class="fa fa-user-circle"></i> &nbsp; <span
                        class="text-uppercase">Formulaire d'enregistrement</span> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="data-client-modal">


                </div>
            </div>
            <!-- .modal-footer -->
            <div class="modal-footer">

            </div><!-- /.modal-footer -->
        </div>
    </div>
</div>