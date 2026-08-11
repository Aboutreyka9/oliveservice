<?= breakcrumb($title, 'fa-shopping-cart'); ?>

<div class="card mt-2">
    <div class="card-header">
        <div class="table_row_header">
            <div class="table_row_header_left">
                <h4 class="text-upper"><i class="fa fa-list"></i> &nbsp; Liste des commandes</h4>
            </div>
            <div class="table_row_header_right">
                <button class="btn btn-primary"><i class="fa fa-print"></i> &nbsp; <span class="text-uppercase">Imprimer</span></button>
                <button type="button" class="btn btn-info" id="btn_commande_addModal">
                    <i class="fa fa-plus-circle"></i> &nbsp; <span class="text-uppercase">Nouvelle commande</span>
                </button>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive table-responsive-md">
            <table id="data-table-commande" class="table table-striped table-bordered table-hover table-sm table-data">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>Articles</th>
                        <th>Montant</th>
                        <th>Date commande</th>
                        <th>Statut</th>
                        <th width="6%">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal commande -->
<div class="modal fade" id="commande-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="commandeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="commandeModalLabel">
                    <i class="fa fa-shopping-cart"></i> &nbsp; <span class="text-uppercase">Nouvelle commande</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 data-modal"></div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<script>
    $(function() {
        loadDataTable('data-table-commande', '#data-table-commande', 'charger_data_commandes');
    });
</script>
