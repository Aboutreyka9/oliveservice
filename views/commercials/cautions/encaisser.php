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
                    <div class="col-md-12">
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

