<div class="sidebar sidebar-style-2" data-background-color="dark2">

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <!-- user connected -->
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    <div class="name-user">
                        <span style="font-size: 16px; font-weight: bold;"
                            class=""><?= shortName(auth()->user('nom')) ?></span>
                    </div>
                </div>

                <div class="info">
                    <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                        <span>
                            <?= (string) auth()->user("nom")
                            ?>
                            <span class="user-level text-success"><?= (string) auth()->user("fonction") ?></span>
                            <span class="caret"></span>
                        </span>
                    </a>
                    <div class="clearfix"></div>

                    <div class="collapse in" id="collapseExample">
                        <ul class="nav">
                            <li>
                                <a class="item-link"
                                    href="<?= route('user.profile', ['code' => auth()->user('id')]) ?>">
                                    <span class="link-collapse">Profile</span>
                                </a>
                            </li>
                            <li>
                                <a class="btn_deconnect" href="javascript:void();">
                                    <span class="link-collapse">Deconnexion</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
            <!-- menu lateral -->
            <!-- START ADMIN MENU -->

            <ul class="nav nav-primary">
                <li class="nav-item">
                    <a style="background: #db241df1;" class="" href="<?= url('dashboard') ?>">
                        <i style="color: #fff!important;" class="fas fa-home"></i>
                        <p style="color: #fff!important;">TABLEAU DE BORD</p>
                    </a>
                </li>

                <?php if (has_groupe(Groupes::SUPER) || has_groupe(Groupes::ADMIN) || has_groupe(Groupes::GESTION) || has_groupe(Groupes::COMMERCIAL)): ?>
                <!-- Groupes::Clients  => -->

                <li class="nav-item">
                    <a data-toggle="collapse" href="#clients">
                        <i class="fas fa-users"></i>
                        <p class="text-upper">Clients</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="clients">
                        <ul class="nav nav-collapse">
                            <?php if (has_groupe(Groupes::SUPER) || has_groupe(Groupes::ADMIN) || has_groupe(Groupes::COMMERCIAL)): ?>
                            <li>
                                <a class="item-link" href="<?= url('inscriptions') ?>">
                                    <span class="sub-item">Souscriptions</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if (has_groupe(Groupes::SUPER) || has_groupe(Groupes::ADMIN) || has_groupe(Groupes::GESTION) || has_groupe(Groupes::COMMERCIAL)): ?>
                            <li>
                                <a class="item-link" href="<?= url('inscriptions/liste') ?>">
                                    <span class="sub-item">Liste souscriptions</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if (has_groupe(Groupes::SUPER) || has_groupe(Groupes::ADMIN) || has_groupe(Groupes::GESTION) || has_groupe(Groupes::COMMERCIAL)): ?>
                            <li>
                                <a class="item-link" href="<?= url('clients') ?>">
                                    <span class="sub-item">Liste clients</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if (has_groupe(Groupes::SUPER) || has_groupe(Groupes::ADMIN) || has_groupe(Groupes::GESTION) || has_groupe(Groupes::COMMERCIAL)): ?>
                            <li>
                                <a class="item-link" href="<?= url('cautions') ?>">
                                    <span class="sub-item">Cautions</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>

                <?php if (has_groupe(Groupes::SUPER) || has_groupe(Groupes::ADMIN) || has_groupe(Groupes::GESTION) || has_groupe(Groupes::COMMERCIAL)): ?>
                <!-- Groupes::Activités  => -->

                <li class="nav-item">
                    <a data-toggle="collapse" href="#activites">
                        <i class="fas fa-cubes"></i>
                        <p class="text-upper">Activités</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="activites">
                        <ul class="nav nav-collapse">
                            <?php if (has_groupe(Groupes::SUPER) || has_groupe(Groupes::ADMIN)): ?>
                            <li>
                                <a class="item-link" href="<?= url('packs') ?>">
                                    <span class="sub-item">Packs</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if (has_groupe(Groupes::SUPER) || has_groupe(Groupes::ADMIN)): ?>
                            <li>
                                <a class="item-link" href="<?= url('articles') ?>">
                                    <span class="sub-item">Articles</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if (has_groupe(Groupes::SUPER) || has_groupe(Groupes::ADMIN)): ?>
                            <li>
                                <a class="item-link" href="<?= url('categories-packs') ?>">
                                    <span class="sub-item">Catégories packs</span>
                                </a>
                            </li>
                            <?php endif; ?>
                           
                        </ul>
                    </div>
                </li>
                <?php endif; ?>

                <?php if (has_groupe(Groupes::SUPER) || has_groupe(Groupes::ADMIN) || has_groupe(Groupes::COMPTABLE) || has_groupe(Groupes::GESTION) || has_groupe(Groupes::COMMERCIAL)): ?>
                <!-- Groupes::Finance  => -->

                <li class="nav-item">
                    <a data-toggle="collapse" href="#finance">
                        <i class="fas fa-money-bill-wave"></i>
                        <p class="text-upper">Finance</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="finance">
                        <ul class="nav nav-collapse">
                            <?php if (has_groupe(Groupes::SUPER) || has_groupe(Groupes::ADMIN) || has_groupe(Groupes::COMPTABLE) || has_groupe(Groupes::COMMERCIAL)): ?>
                            <li>
                                <a class="item-link" href="<?= url('versements-commerciaux') ?>">
                                    <span class="sub-item">Versements commerciaux</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if (has_groupe(Groupes::SUPER) || has_groupe(Groupes::ADMIN) || has_groupe(Groupes::GESTION)): ?>
                            <li>
                                <a class="item-link" href="<?= url('validations') ?>">
                                    <span class="sub-item">Validations</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if (has_groupe(Groupes::SUPER) || has_groupe(Groupes::ADMIN) || has_groupe(Groupes::GESTION)): ?>
                            <li>
                                <a class="item-link" href="<?= url('distributions') ?>">
                                    <span class="sub-item">Distributions</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if (has_groupe(Groupes::SUPER) || has_groupe(Groupes::ADMIN) || has_groupe(Groupes::COMPTABLE)): ?>
                            <li>
                                <a class="item-link" href="<?= url('depenses') ?>">
                                    <span class="sub-item">Dépenses</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>

                <?php if (has_groupe(Groupes::SUPER) || has_groupe(Groupes::ADMIN)): ?>
                <!-- Groupes::Ressources humaines  => -->

                <li class="nav-item">
                    <a data-toggle="collapse" href="#grh">
                        <i class="fas fa-user-tie"></i>
                        <p class="text-upper">Ressources humaines</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="grh">
                        <ul class="nav nav-collapse">
                            <li>
                                <a class="item-link" href="<?= url('personnel-commercials') ?>">
                                    <span class="sub-item">Commerciaux</span>
                                </a>
                            </li>
                            <li>
                                <a class="item-link" href="<?= url('recrutements/personnel') ?>">
                                    <span class="sub-item">Recrutement</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>

                <?php if (has_groupe(Groupes::SUPER) || has_groupe(Groupes::ADMIN)): ?>
                <!-- Groupes::Administration  => -->

                <li class="nav-item">
                    <a data-toggle="collapse" href="#administration">
                        <i class="fas fa-cog"></i>
                        <p class="text-upper">Administration</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="administration">
                        <ul class="nav nav-collapse">
                            <li>
                                <a class="item-link" href="<?= url('utilisateurs') ?>">
                                    <span class="sub-item">Personnel administratif</span>
                                </a>
                            </li>
                            <li>
                                <a class="item-link" href="<?= url('services-fonctions') ?>">
                                    <span class="sub-item">Fonctions & services</span>
                                </a>
                            </li>
                            <li>
                                <a class="item-link" href="<?= url('annees-sessions') ?>">
                                    <span class="sub-item">Années & Sessions</span>
                                </a>
                            </li>
                            <li>
                                <a class="item-link" href="<?= url('zones') ?>">
                                    <span class="sub-item">Zones</span>
                                </a>
                            </li>
                            <li>
                                <a class="item-link" href="<?= url('roles') ?>">
                                    <span class="sub-item">Rôles & permissions</span>
                                </a>
                            </li>
                            <li>
                                <a class="item-link" href="<?= url('parametres') ?>">
                                    <span class="sub-item">Paramètres</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>

            </ul>

            <!-- END ADMIN SEXION -->
        </div>
    </div>
</div>
