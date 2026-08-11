<?= breakcrumb($title, 'fa-chart-bar '); ?>

<header class="page-title-bar">
    <div class="header-dashboard d-flex align-items-center mb-4">
        <i class="fas fa-chart-line mr-3 me-3" style="font-size:20px;"></i>
        <div>
            <h4 class="mb-0">Tableau de bord</h4>
            <small>Vue globale de l'activité</small>
        </div>
    </div>
</header>

<!-- FILTRE PÉRIODE -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="stats-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
            <div class="activity text-md-center">
                <h4 style="font-weight: bold;" id="activityDateRange">
                    Activité du 01/08/2025 au 11/08/2025
                </h4>
            </div>
            <div class="input-group w-100 w-md-auto filter-box">
                <span class="input-group-text">
                    <i class="fa fa-calendar"></i>
                </span>
                <input type="text" class="form-control" placeholder="Sélectionner la période">
                <button class="btn btn-primary">
                    <i class="fa fa-filter"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- STATS CARDS -->
<div class="row g-3 mb-1">
    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-primary mr-2">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <h6><span class="text-muted text-uppercase montan-title">PACKS</span></h6>
                </div>
                <h5 class="montan-value">12</h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-info mr-2">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h6><span class="text-muted text-uppercase montan-title">ARTICLES</span></h6>
                </div>
                <h5 class="montan-value">48</h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-success mr-2">
                        <i class="fas fa-users"></i>
                    </div>
                    <h6><span class="text-muted text-uppercase montan-title">CLIENTS</span></h6>
                </div>
                <h5 class="montan-value">156</h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-warning mr-2">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <h6><span class="text-muted text-uppercase montan-title">CAISSE</span></h6>
                </div>
                <h5 class="montan-value">2 450 000 FCFA</h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-danger mr-2">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h6><span class="text-muted text-uppercase montan-title">VENTES</span></h6>
                </div>
                <h5 class="montan-value">89</h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-purple mr-2">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h6><span class="text-muted text-uppercase montan-title">ACHATS</span></h6>
                </div>
                <h5 class="montan-value">34</h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-orange mr-2">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h6><span class="text-muted text-uppercase montan-title">ALERTES</span></h6>
                </div>
                <h5 class="montan-value">3</h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-dark mr-2">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h6><span class="text-muted text-uppercase montan-title">INSCRIPTIONS</span></h6>
                </div>
                <h5 class="montan-value">24</h5>
            </div>
        </div>
    </div>
</div>

<!-- GRAPHIQUES -->
<div class="page-section container">
    <div class="row mt-5">
        <div class="col-12 col-lg-12 col-xl-12">
            <section class="card card-fluid">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4 col-12 col-md-12 col-lg-12">
                        <h3 class="card-title mb-0">Montant des ventes de la Semaine</h3>
                        <button class="btn btn-sm btn-info toggle-btn" type="button" data-toggle="collapse"
                            data-target="#week_chart" aria-expanded="true">+</button>
                    </div>
                    <div id="week_chart" class="collapse show col-12 col-md-12 col-lg-12">
                        <div class="chartjs">
                            <canvas id="week_canvas"></canvas>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <hr>

    <div class="row mt-5">
        <div class="col-12 col-lg-6 col-md-6">
            <section class="card card-fluid">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <h3 class="card-title mb-0">Articles par catégorie</h3>
                        <button class="btn btn-sm btn-info toggle-btn ml-auto" type="button" data-toggle="collapse"
                            data-target="#category_chart" aria-expanded="true">+</button>
                    </div>
                    <div id="category_chart" class="collapse show">
                        <div class="chartjs">
                            <canvas id="category_canvas"></canvas>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="col-12 col-lg-6 col-md-6">
            <section class="card card-fluid">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <h3 class="card-title mb-0">Packs vendus par mois</h3>
                        <button class="btn btn-sm btn-info toggle-btn ml-auto" type="button" data-toggle="collapse"
                            data-target="#pack_chart" aria-expanded="true">+</button>
                    </div>
                    <div id="pack_chart" class="collapse show">
                        <div class="chartjs">
                            <canvas id="pack_canvas"></canvas>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <hr>

    <div class="row mt-5">
        <div class="col-12 col-lg-12 col-md-12">
            <section class="card card-fluid">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <h3 class="card-title mb-0">Évolution du chiffre d'affaires</h3>
                        <div class="card-title-control ml-auto">
                            <div class="d-flex">
                                <select style="width: 15em;" class="form-control select_year" name="" id="select_year_dashboard">
                                    <option value="2025">2025</option>
                                    <option value="2024">2024</option>
                                </select>
                                <button class="btn btn-primary ml-2"><i class="fa fa-search"></i></button>
                                <button class="btn btn-sm btn-info toggle-btn ml-2" type="button" data-toggle="collapse"
                                    data-target="#revenue_chart" aria-expanded="true">+</button>
                            </div>
                        </div>
                    </div>
                    <div id="revenue_chart" class="collapse show">
                        <div class="chartjs">
                            <canvas id="revenue_canvas"></canvas>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<!-- APERÇU GÉNÉRAL -->
<div class="row g-3 mt-2">
    <div class="col-md-12">
        <h4>APERCU GENERAL</h4>
    </div>

    <div class="col-md-4 col-sm-4 col-xl-3">
        <div class="card stat-card">
            <div class="icon bg-info"><i class="fas fa-box"></i></div>
            <h6 class="montan-title">PRODUITS</h6>
            <h5>48</h5>
        </div>
    </div>

    <div class="col-md-4 col-sm-4 col-xl-3">
        <div class="card stat-card">
            <div class="icon bg-purple"><i class="fas fa-user-friends"></i></div>
            <h6 class="montan-title">CLIENTS</h6>
            <h5>156</h5>
        </div>
    </div>

    <div class="col-md-4 col-sm-4 col-xl-3">
        <div class="card stat-card">
            <div class="icon bg-dark"><i class="fas fa-user-tie"></i></div>
            <h6 class="montan-title">EMPLOYÉS</h6>
            <h5>24</h5>
        </div>
    </div>

    <div class="col-md-4 col-sm-4 col-xl-3">
        <div class="card stat-card">
            <div class="icon bg-success"><i class="fas fa-building"></i></div>
            <h6 class="montan-title">ZONES</h6>
            <h5>8</h5>
        </div>
    </div>
</div>

<!-- TABLEAU DERNIÈRES ACTIVITÉS -->
<div class="row mt-5">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="card-title">Dernières activités</div>
                    <button class="btn btn-sm btn-primary">Voir tout</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Libellé</th>
                                <th>Utilisateur</th>
                                <th>Date</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><span class="badge badge-success">Pack</span></td>
                                <td>Pack Standard Décembre</td>
                                <td>Admin Principal</td>
                                <td>10/08/2025 14:30</td>
                                <td><span class="badge badge-success">Actif</span></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td><span class="badge badge-info">Article</span></td>
                                <td>Manuel mathématiques</td>
                                <td>Admin Principal</td>
                                <td>10/08/2025 13:15</td>
                                <td><span class="badge badge-success">Actif</span></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td><span class="badge badge-warning">Client</span></td>
                                <td>Inscription - Jean Dupont</td>
                                <td>Secrétaire</td>
                                <td>10/08/2025 11:20</td>
                                <td><span class="badge badge-success">Confirmé</span></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td><span class="badge badge-primary">Paiement</span></td>
                                <td>Paiement inscription #INV-001</td>
                                <td>Caissier</td>
                                <td>09/08/2025 16:45</td>
                                <td><span class="badge badge-success">Payé</span></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td><span class="badge badge-danger">Dépense</span></td>
                                <td>Fournitures bureau</td>
                                <td>Comptable</td>
                                <td>09/08/2025 10:00</td>
                                <td><span class="badge badge-warning">En attente</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPTS GRAPHIQUES -->
<script>
    $(function() {
        // Toggle buttons
        document.querySelectorAll('.toggle-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                setTimeout(() => {
                    if (this.getAttribute('aria-expanded') === 'true') {
                        this.textContent = '−';
                    } else {
                        this.textContent = '+';
                    }
                }, 200);
            });
        });

        // Graphique ventes semaine
        var weekCtx = document.getElementById('week_canvas').getContext('2d');
        new Chart(weekCtx, {
            type: 'line',
            data: {
                labels: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'],
                datasets: [{
                    label: 'Ventes (FCFA)',
                    data: [120000, 190000, 150000, 250000, 220000, 300000, 280000],
                    backgroundColor: 'rgba(54, 162, 235, 0.1)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value / 1000 + 'k';
                            }
                        }
                    }
                }
            }
        });

        // Graphique articles par catégorie
        var categoryCtx = document.getElementById('category_canvas').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Fournitures', 'Électronique', 'Vêtements', 'Alimentation', 'Autres'],
                datasets: [{
                    data: [35, 25, 20, 15, 5],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });

        // Graphique packs vendus par mois
        var packCtx = document.getElementById('pack_canvas').getContext('2d');
        new Chart(packCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août'],
                datasets: [{
                    label: 'Packs vendus',
                    data: [12, 19, 15, 25, 22, 30, 28, 35],
                    backgroundColor: 'rgba(75, 192, 192, 0.8)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Graphique évolution CA
        var revenueCtx = document.getElementById('revenue_canvas').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
                datasets: [{
                    label: 'Chiffre d\'affaires (FCFA)',
                    data: [6500000, 5900000, 8000000, 8100000, 5600000, 9500000, 11000000, 12500000, 10500000, 13000000, 14000000, 15500000],
                    backgroundColor: 'rgba(153, 102, 255, 0.1)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Dépenses (FCFA)',
                    data: [4000000, 3500000, 5000000, 5200000, 3800000, 6000000, 7000000, 7500000, 6500000, 8000000, 8500000, 9000000],
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value / 1000000 + 'M';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
