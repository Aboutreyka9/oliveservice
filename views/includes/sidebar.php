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
                    <a style="background: #db241df1;" class="" href="<?= route('home') ?>">
                        <i style="color: #fff!important;" class="fas fa-home"></i>
                        <p style="color: #fff!important;">TABLEAU DE BORD</p>
                    </a>
                </li>


                <!-- Groupes::Gestion académique  => -->

                <?php //if(auth()->hasGroupe(Groupes::ADMIN)): 
                ?>

                <li class="nav-item">
                    <a data-toggle="collapse" href="#académique">
                        <i class="fas fa-pen-square"></i>
                        <p class="text-upper">Activités</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="académique">
                        <ul class="nav nav-collapse">
                            <!-- 👉 Caissier, Gérant, Admin -->
                            <?php //if(auth()->hasRole(Roles::ADMIN_H)): 
                            ?>
                            <li>
                                <a class="item-link" href="<?= url('packs') ?>">
                                    <span class="sub-item">Packs</span>
                                </a>
                            </li>
                            <li>
                                <a class="item-link" href="<?= url('categories-packs') ?>">
                                    <span class="sub-item">Categories Packs</span>
                                </a>
                            </li>
                            <li>
                                <a class="item-link" href="<?= url('zones') ?>">
                                    <span class="sub-item">Zones</span>
                                </a>
                            </li>

                           
                            <?php //endif; 
                            ?>

                        </ul>
                    </div>
                </li>
                <?php //endif; 
                ?>


                <!-- Groupes::Étudiants  => -->

                <?php //if(auth()->hasGroupe(Groupes::ADMIN)): 
                ?>

                <li class="nav-item">
                    <a data-toggle="collapse" href="#etudiants">
                        <i class="fas fa-pen-square"></i>
                        <p class="text-upper">Clients</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="etudiants">
                        <ul class="nav nav-collapse">
                            <!-- 👉 Caissier, Gérant, Admin -->
                            <?php //if(auth()->hasRole(Roles::ADMIN_H)): 
                            ?>
                            <li>
                                <a class="item-link" href="<?= url('inscriptions') ?>">
                                    <span class="sub-item">Inscriptions</span>
                                </a>
                            </li>
                            <li>
                                <a class="item-link" href="<?= url('reinscriptions') ?>">
                                    <span class="sub-item">Réinscriptions</span>
                                </a>
                            </li>
                           
                            <?php //endif; 
                            ?>

                        </ul>
                    </div>
                </li>
                <?php //endif; 
                ?>

        
                <!-- Groupes::Finance  => -->

                <?php //if(auth()->hasGroupe(Groupes::ADMIN)): 
                ?>

                <li class="nav-item">
                    <a data-toggle="collapse" href="#finance">
                        <i class="fas fa-pen-square"></i>
                        <p class="text-upper">Finance </p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="finance">
                        <ul class="nav nav-collapse">
                            <!--  Gérant, Admin -->
                            <?php //if(auth()->hasRole(Roles::ADMIN_H)): 
                            ?>
                            <li>
                                <a class="item-link" href="<?= url('frais-de-scolarité') ?>">
                                    <span class="sub-item"> Frais de scolarité</span>
                                </a>
                            </li>

                            <?php //endif; 
                            ?>
                            <!-- 👉 Caissier, Gérant, Admin -->
                            <?php //if(auth()->hasRole(Roles::ADMIN_H)): 
                            ?>
                            <li>
                                <a class="item-link" href="<?= url('paiements') ?>">
                                    <span class="sub-item"> Paiements</span>
                                </a>
                            </li>

                            <?php //endif; 
                            ?>
                            <!-- 👉 Caissier, Gérant, Admin -->
                            <?php //if(auth()->hasRole(Roles::ADMIN_H)): 
                            ?>
                            <li>
                                <a class="item-link" href="<?= url('caisse') ?>">
                                    <span class="sub-item"> Caisse</span>
                                </a>
                            </li>

                            <?php //endif; 
                            ?>
                            <!-- 👉 facturation, Gérant, Admin -->
                            <?php //if(auth()->hasRole(Roles::ADMIN_H)): 
                            ?>
                            <li>
                                <a class="item-link" href="<?= url('depenses') ?>">
                                    <span class="sub-item"> Facturation</span>
                                </a>
                            </li>

                            <?php //endif; 
                            ?>
                            <!-- 👉 facturation, Gérant, Admin -->
                            <?php //if(auth()->hasRole(Roles::ADMIN_H)): 
                            ?>
                            <li>
                                <a class="item-link" href="<?= url('salaires') ?>">
                                    <span class="sub-item"> Salaires</span>
                                </a>
                            </li>
                            <li>
                                <a class="item-link" href="<?= url('historique-salaires') ?>">
                                    <span class="sub-item"> Historique salaires</span>
                                </a>
                            </li>

                            <?php //endif; 
                            ?>

                        </ul>
                    </div>
                </li>
                <?php //endif; 
                ?>

                <!-- Groupes::Ressources humaines  => -->

                <?php //if(auth()->hasGroupe(Groupes::ADMIN)): 
                ?>

                <li class="nav-item">
                    <a data-toggle="collapse" href="#grh">
                        <i class="fas fa-pen-square"></i>
                        <p class="text-upper">Ressources humaines </p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="grh">
                        <ul class="nav nav-collapse">
                            <!--  Gérant, Admin -->
                            <?php //if(auth()->hasRole(Roles::ADMIN_H)): 
                            ?>
                            <li>
                                <a class="item-link" href="<?= url('personnel-comercials') ?>">
                                    <span class="sub-item"> Commercials</span>
                                </a>
                            </li>

                            <?php //endif; 
                            ?>
                            <!-- 👉 Caissier, Gérant, Admin -->
                            <?php //if(auth()->hasRole(Roles::ADMIN_H)): 
                            ?>
                            <li>
                                <a class="item-link" href="<?= url('recrutements/personnel') ?>">
                                    <span class="sub-item"> Recrutement</span>
                                </a>
                            </li>

                            <?php //endif; 
                            ?>
                            <!-- 👉 Caissier, Gérant, Admin -->
                            <?php //if(auth()->hasRole(Roles::ADMIN_H)): 
                            ?>
                            <!-- <li>
                                <a class="item-link" href="<?php // url('congés') 
                                                            ?>">
                                    <span class="sub-item"> Congés</span>
                                </a>
                            </li> -->

                            <?php //endif; 
                            ?>

                        </ul>
                    </div>
                </li>
                <?php //endif; 
                ?>


                <!-- Groupes::Administration  => -->

                <?php //if(auth()->hasGroupe(Groupes::ADMIN)): 
                ?>

                <li class="nav-item">
                    <a data-toggle="collapse" href="#administration">
                        <i class="fas fa-pen-square"></i>
                        <p class="text-upper">Administration </p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="administration">
                        <ul class="nav nav-collapse">
                            <!--  Gérant, Admin -->
                            <?php //if(auth()->hasRole(Roles::ADMIN_H)): 
                            ?>
                            <li>
                                <a class="item-link" href="<?= url('utilisateurs') ?>">
                                    <span class="sub-item"> personnel administratif</span>
                                </a>
                            </li>
                            <li>
                                <a class="item-link" href="<?= url('services-fonctions') ?>">
                                    <span class="sub-item"> Fonctions & services</span>
                                </a>
                            </li>
                            <li>
                                <a class="item-link" href="<?= url('annees-sessions') ?>">
                                    <span class="sub-item"> Années & Sessions</span>
                                </a>
                            </li>

                            <?php //endif; 
                            ?>
                            <!-- 👉 Caissier, Gérant, Admin -->
                            <?php //if(auth()->hasRole(Roles::ADMIN_H)): 
                            ?>
                            <li>
                                <a class="item-link" href="<?= url('roles') ?>">
                                    <span class="sub-item"> Rôles & permissions</span>
                                </a>
                            </li>

                            <?php //endif; 
                            ?>
                            <!-- 👉 Caissier, Gérant, Admin -->
                            <?php //if(auth()->hasRole(Roles::ADMIN_H)): 
                            ?>
                            <li>
                                <a class="item-link" href="<?= url('parametres') ?>">
                                    <span class="sub-item"> Paramètres</span>
                                </a>
                            </li>

                            <?php //endif; 
                            ?>
                            <!-- 👉 Caissier, Gérant, Admin -->
                            <?php //if(auth()->hasRole(Roles::ADMIN_H)): 
                            ?>
                            <li>
                                <a class="item-link" href="<?= url("journaux-activités") ?>">
                                    <span class="sub-item"> Journaux d'activités</span>
                                </a>
                            </li>
                            <li>
                                <a class="item-link" href="<?= url("sauvegardes") ?>">
                                    <span class="sub-item"> Sauvegardes</span>
                                </a>
                            </li>
                            <?php //endif; 
                            ?>

                        </ul>
                    </div>
                </li>
                <?php //endif; 
                ?>

            </ul>

            <!-- END ADMIN SEXION -->
        </div>
    </div>
</div>