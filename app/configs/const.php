<?php

define('root', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']);
define('ASSETS', root . '/oliveservice/assets/');
define('LINK', root . '/oliveservice/');
define('HOME', root . '/oliveservice');
define('APP_NAME', 'G-EICG');
define('TWO_PIP', '/../../');
define('THREE_PIP', '/../../');

// const etat and status
define('STATUT_ACTIF', "actif");
define('STATUT_INACTIF', "inactif");
define('ETAT_INACTIF', "0");
define('ETAT_ACTIF', "1");
define('ETAT_ATTENTE', "2");
define('BOUTIQUE_CODE', "BTQ_001");
define('COMPTE_CODE', "CMP_001");


class TABLES
{
    // Gestion académique
    public const ARTICLES = 'articles';
    public const PACK_ARTICLES = 'pack_articles';
    public const ANNEES = 'annees';
    public const CAUTISATION_CLIENTS = 'cautisation_clients';
    public const CATEGORIES = 'categorie_packs';
    public const CLIENTS = 'clients';
    public const COMMERCIALS = 'commercials';
    public const DEPENSES = 'depenses';
    public const DISTRIBUTIONS = 'distributions';
    public const ETABLISSEMENTS = 'etablissements';
    public const FONCTIONS = 'fonctions';
    public const INSCRIPTIONS = 'inscriptions';
    public const PACKS = 'packs';
    public const PACK_INSCRIPTIONS = 'pack_inscriptions';
    public const RECONDUCTIONS = 'reconductions';
    public const ROLES = 'roles';
    public const SESSIONS = 'sessions';
    public const SERVICES = 'services';
    public const USERS = 'users';
    public const USER_ROLES = 'user_roles';
    public const TYPE_DEPENSES = 'type_depenses';
    public const VERSEMENTS = 'versements';
    public const VERSEMENTS_COMMERCIAUX = 'versements_commerciaux';
    public const ZONES = 'zones';
    public const VUE_TOTAL_PARK_ARTICLES = 'vue_pack_total_articles';
}

class Permissionsss
{
    const CREATE = 'create_permission';
    const EDIT = 'edit_permission';
    const VIEW = 'show_permission';
    const DELETE = 'delete_permission';
}

class Rolesss
{
    const SUPER_ADMIN = 'super_admin';
    const ADMIN_PARAM = 'admin_param';
    const ADMIN_USER = 'admin_user';
    const COMPT_CAISSE = 'compt_caisse';
    const COMPT_DEPENSE = 'compt_depense';
    const COMPT_VERSEMENT = 'compt_versement';
    const GEST_VALID = 'gest_valid';
    const GEST_DISTRIB = 'gest_distrib';
    const GEST_CAUTION = 'gest_caution';
    const COMM_CLIENT = 'comm_client';
    const COMM_CAUTION = 'comm_caution';
    const COMM_VERSEMENT = 'comm_versement';
}

class Groupesss
{
    const SUPER = 'SUPER';
    const ADMIN = 'ADMIN';
    const COMPTABLE = 'COMPTABLE';
    const GESTION = 'GESTION';
    const COMMERCIAL = 'COMMERCIAL';
}


// $sideBarData = [
//                 'test' =>[]
//             ];

const STATUT_DATA = ['actif', 'inactif', ''];
const STATUT_INSCRIPTION = ['valide', 'solde', 'reconduit','annule'];
const STATUT_DEPENSE = ['En attente', 'Confirmee', 'Annulee'];
const PAIEMENT = ['Especes', 'Carte', 'Mobile money'];
const SEXEP = ['Mr', 'Mlle', 'Mme'];
const PIECES_DATA = ["CNI" => "CNI", "PASSEPORT" => "PASSEPORT", "CMU" => "CMU", "PERMIS" => "PERMIS", "CARTE CONSLAIRE" => "CARTE CONSLAIRE", "AUTRES" => "AUTRES"];

const EXTENSION = ["jpg", "png", "jpeg", "jfif", "webp", "svg", "gif", "bmp", "ico", "heic", "heif"];
const PERIODE = "periode";
const RESERVATION = "reservation";
const OLD_URL = "old_url";
const PROJECT_NAME = "oliveservice";
const ARTICLE_CODES = "article_codes";
// CONST SEXE = ['G','F'];

const DAYS = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
const SEMESTRE_DATA = ['SEMESTRE 1', 'SEMESTRE 2'];


const MONTHS = [
    'Janvier',
    'Février',
    'Mars',
    'Avril',
    'Mai',
    'Juin',
    'Juillet',
    'Août',
    'Septembre',
    'Octobre',
    'Novembre',
    'Décembre'
];
