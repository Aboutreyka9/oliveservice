
const APP = {
    origin: window.location.origin,
    home: window.location.origin + "/oliveservice/",
    ajax: window.location.origin + "/oliveservice/app/Controllers/ajx.php",
    tables: {},
    formChanged: false,
    rolesPermissions: [],
    articleSelected: [],
    dataCheck: [],
    userCode: null,
    date_start_picker:moment().startOf('month'), // 1er du mois
    date_end_picker:moment() // aujourd’hui
};


const $form = $('form');
let initialData = $form.serialize(); // capture les valeurs initiales
let formBtn = '';
$.ajaxSetup({

    beforeSend: function (xhr, settings) {

        // console.log("Début de la requête :", settings.url);
        $(".loader_backdrop2").css('display', "block");

    },

    complete: function () {

        // Cacher le loader
        $(".loader_backdrop2").css('display', "none");
    },

    error: function (xhr, status, error) {

        $(".loader_backdrop2").css('display', "none");
        console.error(error);
        $.notify("Désolé une erreur est survenue", 'error');
    }

});


detectChangeForms();

// Détection d’un changement sur n’importe quel champ
function detectChangeForms() {

    // $('button[type="submit"]').prop("disabled", true);

    $('body').on('input change', 'form :input', function () {
        const $input = $(this);
        const $form = $input.closest('form');



        if ($form.serialize() !== initialData) {
            APP.formChanged = true;
            if (formBtn !== '') {
                $(formBtn).prop('disabled', false);
            } else {
                $form.find('button[type="submit"], input[type="submit"]').prop('disabled', false);
            }
        } else {
            APP.formChanged = false;
            if (formBtn !== '') {
                $(formBtn).prop('disabled', true);
            } else {
                $form.find('button[type="submit"], input[type="submit"]').prop('disabled', true);
            }
        }


    });
}

loading();

function loading() {
    window.onload = function () {
        $(".loader_backdrop2").css('display', "none");
    }
}

function btnReq(selector, message = 'Chargement...', icon = "fa-redo fa-spin") {
    $(selector).html(`
        <i class="fa ${icon}"></i> &nbsp; ${message}
        `);
    $(selector).attr("disabled", "disabled");
}


function btnRes(selector, message = 'Ajouter', icon = "fa-plus-circle") {
    $(selector).html(` <i class="fa ${icon}"></i> &nbsp; ${message}`);
    $(selector).attr("disabled", false);
}

// RESET FORM
function resetForm() {
    // $(".reset").click(function(){
    $("form").trigger("reset");
    // $("form")[0].reset()
    $("input[type='text']").val('');
    $("input[type='number']").val('');
    // $("form").remove();
    // $(selector)[0].reset()
    //   });
}

// CLOSE MODAL
closeModal();

function closeModal() {
    $('body').on('click', '.dismiss_modal', function (e) {
        e.preventDefault();
        resetForm();
        $(".modal").modal('hide');

    })
}


// searchUser();
function searchTestInput() {
    $("body").on("keyup", $('#data-table-utilisateur').DataTable().search(), function (e) {
        e.preventDefault();
        var search = $('input[type="search"]').val();

        testDatable('bcharger_data_utilisateurs', '#data-table-utilisateur', search)
        // loadDataTable('data-table-user', '#data-table-user', 'bcharger_data_users');
    });
}

// searchTestInput();


function testDatable(action, selector, search = "") {
    // var se = $(selector).DataTable().search().value;
    $.ajax({
        method: "POST",
        url: APP.ajax,
        data: {
            action: action,
            length: 20,
            start: 0,
            search: search,
            draw: 1
        },
        // dataType: "JSON",
        beforeSend: function () {
            // $(".loader_backdrop2").css('display', "block");
            // btnReq("#" + id, "Traitement...");
        },
        success: function (data) {
            console.log("test", data);

        }
    });
}

function loadDataTableMany(tableId, visibility, selector, action) {

    if (!$(visibility + ':visible').length) {
        console.log("Tableau non visible, donc pas de chargement pour " + tableId);

        return;
    }

    // testDatable(action, selector);

    // return;

    ajaxTable(tableId, selector, action);

}


function loadDataTable(tableId, selector, action) {

    if (!$(selector + ':visible').length) {
        console.log("Tableau non visible, donc pas de chargement pour " + tableId);

        return;
    }

    // testDatable(action, selector);

    // return;

    console.log(selector, tableId, action);

    ajaxTable(tableId, selector, action);

}

function ajaxTable(tableId, selector, action) {

    if ($.fn.DataTable.isDataTable(selector)) {
        $(selector).DataTable().destroy();
        $(selector).empty(); // vide le tbody généré
    }

    APP.tables[tableId] = $(selector).DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        autoWidth: false,

        ajax: {
            url: APP.ajax,
            type: "POST",
            data: {
                action: action
            }
        }
    });

    APP.tables[tableId].columns.adjust();
}

menuNav();


function menuNav() {
    const pages = window.location.pathname.split("/");
    var currentPage = pages[2];
    if (currentPage) {
        $(".current-page").text(currentPage.toUpperCase());
    }


    // var te = window.pathname.
    $("body").on('click', '.back', function () {
        history.back();
    });
}

synchronisation();

function synchronisation(params) {
    $("body").on("click", ".synchroniser", function (e) {
        e.preventDefault();
        document.location.reload();
    });
}

function money(val) {
    if (isNaN(val) || val <= 0) return val;
    val = Number(val);
    return val.toLocaleString('fr-FR', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    })
}

function formatMontant(montant) {
    return new Intl.NumberFormat('fr-FR').format(montant) + ' FCFA';
}

formatPhoneNumber();

function formatPhoneNumber() {
    $("body").delegate('.telephone', 'input', function (e) {
        let value = e.target.value;

        // Supprimer tout sauf chiffres
        let digits = value.replace(/\D/g, '');

        // Supprimer l'indicatif s'il est tapé manuellement
        if (digits.startsWith("225")) {
            digits = digits.slice(3);
        } else if (digits.startsWith("00225")) {
            digits = digits.slice(5);
        }

        // Ajouter le zéro obligatoire
        if (!digits.startsWith('0')) {
            digits = '0' + digits;
        }

        // Limiter à 10 chiffres après le zéro
        digits = digits.substring(0, 10);

        // Ajout d'espaces tous les 2 chiffres
        let formatted = digits.match(/.{1,2}/g)?.join(' ') ?? '';

        // Mettre à jour le champ
        e.target.value = '(+225) ' + formatted;
    });
}


/***
 * menu side link active
 */

// activeMenuLink();

function activeMenuLink() {

    // Récupérer l’URL actuelle
    const currentPage = window.location.pathname.split("/").pop();

    // Récupérer tous les liens de menu
    const menuLinks = document.querySelectorAll(".item-link");

    // Parcourir tous les liens de menu
    menuLinks.forEach((link) => {
        // Récupérer l’URL du lien de menu
        const menuLinkUrl = link.getAttribute("href").split("/").pop();

        // Si l’URL du lien de menu correspond au URL actuelle
        if (menuLinkUrl === currentPage) {
            // ajouter la class active au parent de l'element link active 
            // const parent2 = link.parentElement.parentElement.parentElement.parentElement;
            const parent = link.closest(".nav-item");
            const parentMenu = link.closest(".collapse");


            parent.classList.add("active")
            parent.classList.add("submenu");
            parent.getElementsByTagName("a")[0].setAttribute("aria-expanded", "true");

            // activer aussi le parent menu si existe
            if (parentMenu) {
                parentMenu.classList.add("show");
            }
            // Ajouter la classe "active" au lien de menu
            // link.classList.add("item-link-active");
            link.classList.add("active");


            // // Si le lien est dans un menu déroulant
            // $(this).closest(".has-treeview").addClass("menu-open");
            // $(this).closest(".has-treeview").children("a").addClass("active");

        }
    });


    // For sidebar menu
    // $('ul.sidebar a').filter(function() {
    //     return this.href === url;
    // }).addClass('active').parent().parent().addClass('menu-open');


}


/*toggle sideba */

sidebarToggler();
function sidebarToggler() {
    // Sidebar Toggler
    // alert('sidebarToggler');
    $('.sidebar-toggler').click(function () {
        $('.sidebar, .content').toggleClass("open");
        return false;
    });

    $('.sidebar-toggler').on('click', function () {
        $('body').toggleClass('sidebar-expanded');
        $('.sidebar-toggler i').toggleClass('fa-times fa-bars');
    }
    );
}


toggleSideBar();

function toggleSideBar() {
    var saveOption = localStorage.getItem("toggleSideBar");
    if (saveOption == 'true') {
        $(".toggle-sidebar").removeClass("toggled");
        $(".wrapper").removeClass("sidebar_minimize");
    }
}

// activeTabsMenu();

function activeTabsMenu() {

    // Récupérer l’URL actuelle
    // $("body").on("click", ".nav-tabs li", function (e) { 
    //     $('.nav-tabs li').removeClass('tabs_menu');

    //     $(this).addClass("tabs_menu");
    //     counterTableDataCommon();
    //     // setTimeout(counterTableDataCommon, 200);


    // });
}

// debut des fonctions


deconnecter();

function deconnecter() {
    $('.btn_deconnect').click(function (e) {
        e.preventDefault();
        $.ajax({
            url: APP.ajax,
            method: 'POST',
            dataType: "JSON",
            data: {
                action: "btn_user_deconnect"
            },
            beforeSend: function () {
                $(".loader_backdrop2").css('display', "block");
            },
            success: function (data) {
                console.log(data);

                $(".loader_backdrop2").css('display', "none");

                if (data.success) {
                    history.go(0);
                }
            }
        })
    });
}


/** DEBUT SECTION UTILISATEUR */

loadDataTable('data-table-utilisateur', '#data-table-utilisateur', 'charger_data_utilisateurs');


openModalAddUtilisateur();
function openModalAddUtilisateur() {
    $('.btn_utilisateur_addModal').click(function (e) {
        e.preventDefault();

        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: {
                action: 'btn_showmodal_utilisateur_add'
            },
            dataType: "JSON",
            beforeSend: function () {
                $(".loader_backdrop2").css('display', "block");
                // btnReq("#ClientAddModal", "Traitement...");

            },
            success: function (data) {
                // btnRes("#ClientAddModal", 'Ajouter un client', 'fa-plus');
                // ;

                $(".loader_backdrop2").css('display', "none");
                if (data.success) {
                    var output = data.data;
                    $(".data-modal").html(output.data);
                    $("#user-modal").modal("show");


                } else {
                    $.notify(data.message);

                }

            }
        })
    });
}


ajouterUtilisateur();
function ajouterUtilisateur() {
    $("body").on("submit", "#frmAddUser", function (e) {
        e.preventDefault();
        var data = $(this).serialize();

        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: data,
            dataType: "JSON",
            beforeSend: function () {
                // $(".loader_backdrop2").css('display', "block");

                btnReq("#btnSubmitForm", "Enregistrement...");
            },
            success: function (data) {
                // $(".loader_backdrop2").css('display', "none");

                btnRes("#btnSubmitForm", "Enregistrer", "fa-save");
                if (data.success) {
                    APP.tables['data-table-utilisateur'].ajax.reload(null, false);
                    $.notify(data.message, "success");
                    $("#user-modal").modal("hide");
                } else {
                    $.notify(data.message);
                }
            }
        })
    });
}

function modalUpdatedUtilisateurr(code) {
    // let btn = btn_action.id;

    $.ajax({
        method: "POST",
        url: APP.ajax,
        data: {
            action: 'btn_showmodal_utilisateur_update',
            codeUtilisateur: code
        },
        dataType: 'JSON',
        beforeSend: function () {
            $(".loader_backdrop2").css('display', "block");
            // btnReq(".modal_footer", "Traitement...");
        },
        success: function (data) {

            $(".loader_backdrop2").css('display', "none");

            if (data.success) {
                $(".data-modal").html(data.data);
                $("#user-modal").modal("show");

            } else {
                $.notify(data.message);

            }
        }
    });
}

updatedUtilisateur();
function updatedUtilisateur() {
    $("body").on("submit", "#frmUpdateUser", function (e) {
        e.preventDefault();
        var data = $(this).serialize();


        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: data,
            dataType: "json",
            beforeSend: function () {
                // $(".loader_backdrop2").css('display', "block");

                btnReq("#btnSubmitForm", "Mise à jour en cours...");
            },
            success: function (data) {
                // $(".loader_backdrop2").css('display', "none");

                btnRes("#btnSubmitForm", "Enregistrer", "fa-save");
                if (data.success) {
                    APP.tables['data-table-utilisateur'].ajax.reload(null, false);
                    $.notify(data.message, "success");
                    $("#user-modal").modal("hide");

                } else {
                    $.notify(data.message);
                }
            }
        })
    });
}

function changeStatutUser(code, statut) {
    swal({
        title: "Notification",
        text: "Voulez-vous vraiment modifier le statut de cet utilisateur?",
        icon: "warning",
        dangerMode: true,
        closeOnClickOutside: false,
        buttons: {
            cancel: true,
            confirm: "Confirmer",
        },
    })
        .then(willDelete => {
            if (willDelete) {


                $.ajax({
                    url: APP.ajax,
                    method: 'POST',
                    data: {
                        action: 'change_statut_utilisateurs',
                        code_utilisateur: code,
                        statut_utilisateur: statut
                    },
                    dataType: 'JSON',
                    beforeSend: function () {
                        $(".loader_backdrop2").css('display', "block");
                    },
                    success: function (data) {
                        $(".loader_backdrop2").css('display', "none");

                        if (data.success) {
                            $.notify(data.message, "success");
                            APP.tables['data-table-utilisateur'].ajax.reload(null, false);
                        } else {
                            $.notify(data.message);
                        }
                    }
                });;
            }
        });
}

//SEXION ROLE PERMISSIONS AND ROLES
function ModalAddrolePermissionUser(code) {
    // let btn = btn_action.id;

    APP.userCode = code;


    $.ajax({
        method: "POST",
        url: APP.ajax,
        data: {
            action: 'btn_showmodal_role_permission_utilisateur',
            codeUtilisateur: code
        },
        dataType: 'JSON',
        success: function (data) {

            console.log(data);

            // $(".loader_backdrop2").css('display', "none");

            if (data.success) {

                $('.role-permission-data-modal').html(data.data);
                $('#user-info').text(data.user);
                $("#role-permission-modal").modal("show");

            } else {
                $.notify(data.message);

            }
        }
    });
}


btnCloseModalPermission();

function btnCloseModalPermission() {
    $("body").on("click", "#btn-close-modal", function (e) {
        // e.preventDefault();  

        APP.dataCheck = [];

    });
}

menuRole();

function menuRole() {
    $("body").on("click change", ".toggle-role", function (e) {


        const permissionsDiv = document.querySelector('#permissions-' + this.id);

        const code = $(this).data("role");
        const groupe = $(this).data("groupe");
        var checked = $(this).data("checked");
        // const user = $(this).data("user");

        console.log(checked);


        if (!checked) {

            checked = false;
            $(this).data("checked", true);
            if (!APP.dataCheck.includes(groupe)) {
                loadDataRole(APP.userCode, groupe, code, permissionsDiv); // Rendre visible
            } else {

                permissionsDiv.style.maxHeight = permissionsDiv.scrollHeight + 'px'; // Permet de déployer
                permissionsDiv.style.opacity = 1; // Rendre visible
            }


        } else {
            $(this).data("checked", false);

            $("#btn-r" + code).prop("disabled", "true")
            permissionsDiv.style.maxHeight = 0; // Réduire à 0 pour effacer
            permissionsDiv.style.opacity = 0; // Rendre invisible
        }

    });
}


function loadDataRole(user, groupe, code, permissionsDiv) {

    $.ajax({
        url: APP.ajax,
        method: 'POST',
        data: {
            action: 'btn_load_data_role',
            code_user: user,
            code_role: groupe
        },
        dataType: 'JSON',
        success: function (data) {
            console.log(data);

            if (data.success) {
                $("#sexion-r" + code).html(data.data);
                APP.dataCheck.push(groupe);
                permissionsDiv.style.maxHeight = permissionsDiv.scrollHeight + 'px'; // Permet de déployer
                permissionsDiv.style.opacity = 1;


            }
        }
    });




}



// checkPermissionOld();

function checkPermissionOld() {
    $("body").on("change", ".perm", function (e) {
        e.preventDefault();

        let row = $(this).closest("tr");
        let coderoleId = row.data("id");


        let show = $("#show" + coderoleId).is(":checked") ? 1 : 0;
        let edit = $("#edit" + coderoleId).is(":checked") ? 1 : 0;
        let create = $("#create" + coderoleId).is(":checked") ? 1 : 0;
        let deleted = $("#delete" + coderoleId).is(":checked") ? 1 : 0;


        let existe = APP.rolesPermissions.some(r => r.role === coderoleId);

        if (!existe) {
            let roleId = APP.rolesPermissions.length + 1;

            APP.rolesPermissions.push({
                id: roleId,
                role: coderoleId,
                create: create,
                show: show,
                edit: edit,
                delete: deleted,
            });
        }


        APP.rolesPermissions = APP.rolesPermissions.map(role => {

            if (role.role === coderoleId) {
                role["create"] = create;
                role["show"] = show;
                role["edit"] = edit;
                role["delete"] = deleted;
            }
            return role;
        });

    });
}

function getRolePermission(role) {
    return APP.rolesPermissions.find(r => r.role === role);
}

function putRolePermissionData(roleCode, permissions) {

    let role = getRolePermission(roleCode);

    if (!role) {

        APP.rolesPermissions.push({
            id: APP.rolesPermissions.length + 1,
            role: roleCode,
            ...permissions
        });

        return;
    }

    Object.assign(role, permissions);

}


function getRowPermissions(roleCode) {

    return {

        create: $("#create" + roleCode).is(":checked") ? 1 : 0,

        show: $("#show" + roleCode).is(":checked") ? 1 : 0,

        edit: $("#edit" + roleCode).is(":checked") ? 1 : 0,

        delete: $("#delete" + roleCode).is(":checked") ? 1 : 0

    };

}

checkPermission();
function checkPermission() {

    $("body").on("change", ".perm", function () {

        let row = $(this).closest("tr");

        let roleCode = row.data("id");

        let permissions = getRowPermissions(roleCode);

        putRolePermissionData(roleCode, permissions);
        refreshRoleCheckbox(row);

        console.log(APP.rolesPermissions);

    });

    $("body").on("change", ".role-check", function () {

        let row = $(this).closest("tr");

        let checked = $(this).is(":checked");

        row.find(".perm").prop("checked", checked).trigger("change");

    });

}

function refreshRoleCheckbox(row) {

    let allChecked = true;

    row.find(".perm").each(function () {

        if (!$(this).is(":checked")) {

            allChecked = false;

            return false;

        }

    });

    row.find(".role-check").prop("checked", allChecked);

}

savePermission();

function savePermission() {
    $("body").on("click", "#btnSavePermissions", function (e) {
        e.preventDefault();
        if (APP.rolesPermissions.length === 0) {
            $.notify('Aucune autoristion accordée')
            return;
        } else if (APP.userCode == "") {
            $.notify("Veuillez reprendre le processus")
        }

        $.ajax({
            url: APP.ajax,
            method: 'POST',
            dataType: 'JSON',
            data: {
                action: 'btn_add_permission',
                codeUtilisateur: APP.userCode,
                roles: JSON.stringify(APP.rolesPermissions)
            },
            beforeSend: function () {
                // $("#spinner").addClass("show");
                // $("#btn_modifier_user").html(
                //   '<i class="fa fa-refresh fa-spin fa-2x"></i> &nbsp; Modification...'
                // );
                // $("#btn_modifier_user").attr("disabled", "disabled");
            },
            success: function (data) {
                console.log(data);

                // $("#spinner").removeClass("show");

                // $("#btn_modifier_user").html(
                //     '<i class="fa fa-check-circle"></i> &nbsp; Modifier'
                //   );
                // $("#btn_modifier_user").attr("disabled", false);

                if (data.success) {
                    APP.userCode = "";
                    APP.rolesPermissions = [];
                    APP.dataCheck = [];

                    $.notify(data.message, "success");
                    $("#role-permission-modal").modal("hide");

                } else {
                    $.notify(data.message, "error");

                }
            }
        });

    });
}


// END SEXION ROLES PERMISSIONS


/** FIN SECTION UTILISATEUR */

/** DEBUT SECTION FONCTION */
loadDataTableMany('data-table-fonction', '.service-fonction', '#data-table-fonction', 'charger_data_fonctions');

openModalAddFonction();
function openModalAddFonction() {
    $('#btn_fonction_addModal').click(function (e) {
        e.preventDefault();

        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: {
                action: 'btn_showmodal_fonction_add'
            },
            dataType: "JSON",
            beforeSend: function () {
                $(".loader_backdrop2").css('display', "block");
                // btnReq("#ClientAddModal", "Traitement...");

            },
            success: function (data) {
                // btnRes("#ClientAddModal", 'Ajouter un client', 'fa-plus');
                // ;

                $(".loader_backdrop2").css('display', "none");
                if (data.success) {
                    var output = data.data;
                    $(".data-fonction-modal").html(output.data);
                    $("#fonction-modal").modal("show");


                } else {
                    $.notify(data.message);

                }

            }
        })
    });
}

ajouterFonction();
function ajouterFonction() {
    $("body").on("submit", "#frmAddFonction", function (e) {
        e.preventDefault();
        var data = $(this).serialize();

        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: data,
            dataType: "JSON",
            beforeSend: function () {
                // $(".loader_backdrop2").css('display', "block");

                btnReq("#btnSubmitFormFonction", "Enregistrement...");
            },
            success: function (data) {
                console.log(data);
                // $(".loader_backdrop2").css('display', "none");

                btnRes("#btnSubmitFormFonction", "Enregistrer", "fa-save");
                if (data.success) {
                    APP.tables['data-table-fonction'].ajax.reload(null, false);
                    $.notify(data.message, "success");
                    $("#fonction-modal").modal("hide");
                } else {
                    $.notify(data.message);
                }
            }
        })
    });
}


function modalUpdatedFonction(code) {
    // let btn = btn_action.id;

    $.ajax({
        method: "POST",
        url: APP.ajax,
        data: {
            action: 'btn_showmodal_fonction_update',
            codeFonction: code
        },
        dataType: 'JSON',
        beforeSend: function () {
            $(".loader_backdrop2").css('display', "block");
            // btnReq(".modal_footer", "Traitement...");
        },
        success: function (data) {

            $(".loader_backdrop2").css('display', "none");

            if (data.success) {
                $(".data-fonction-modal").html(data.data);
                $("#fonction-modal").modal("show");

            } else {
                $.notify(data.message);

            }
        }
    });
}

updatedFonction();
function updatedFonction() {
    $("body").on("submit", "#frmUpdateFonction", function (e) {
        e.preventDefault();
        var data = $(this).serialize();


        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: data,
            dataType: "JSON",
            beforeSend: function () {
                // $(".loader_backdrop2").css('display', "block");

                btnReq("#btnSubmitFormFonction", "Mise à jour en cours...");
            },
            success: function (data) {
                // $(".loader_backdrop2").css('display', "none");
                console.log(data);

                btnRes("#btnSubmitFormFonction", "Enregistrer", "fa-save");

                if (data.success) {
                    APP.tables['data-table-fonction'].ajax.reload(null, false);
                    $.notify(data.message, "success");
                    $("#fonction-modal").modal("hide");

                } else {
                    $.notify(data.message);
                }
            }
        })
    });
}

function changeStatutFonction(code, statut) {
    swal({
        title: "Notification",
        text: "Voulez-vous vraiment modifier le statut de cette fonction?",
        icon: "warning",
        dangerMode: true,
        closeOnClickOutside: false,
        buttons: {
            cancel: true,
            confirm: "Confirmer",
        },
    })
        .then(willDelete => {
            if (willDelete) {


                $.ajax({
                    url: APP.ajax,
                    method: 'POST',
                    data: {
                        action: 'change_statut_fonctions',
                        code_fonction: code,
                        statut_fonction: statut
                    },
                    dataType: 'JSON',
                    beforeSend: function () {
                        $(".loader_backdrop2").css('display', "block");
                    },
                    success: function (data) {
                        $(".loader_backdrop2").css('display', "none");

                        if (data.success) {
                            $.notify(data.message, "success");
                            APP.tables['data-table-fonction'].ajax.reload(null, false);
                        } else {
                            $.notify(data.message);
                        }
                    }
                });;
            }
        });
}
/** FIN SECTION FONCTION */


/** DEBUT SECTION SERVICE */
loadDataTableMany('data-table-service', '.service-fonction', '#data-table-service', 'charger_data_services');


openModalAddService();
function openModalAddService() {
    $('#btn_service_addModal').click(function (e) {
        e.preventDefault();

        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: {
                action: 'btn_showmodal_service_add'
            },
            dataType: "JSON",
            beforeSend: function () {
                $(".loader_backdrop2").css('display', "block");
                // btnReq("#ClientAddModal", "Traitement...");

            },
            success: function (data) {
                // btnRes("#ClientAddModal", 'Ajouter un client', 'fa-plus');
                // ;

                $(".loader_backdrop2").css('display', "none");
                if (data.success) {
                    var output = data.data;
                    $(".data-service-modal").html(output.data);
                    $("#service-modal").modal("show");


                } else {
                    $.notify(data.message);

                }

            }
        })
    });
}

ajouterService();
function ajouterService() {
    $("body").on("submit", "#frmAddService", function (e) {
        e.preventDefault();
        var data = $(this).serialize();

        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: data,
            dataType: "JSON",
            beforeSend: function () {
                // $(".loader_backdrop2").css('display', "block");

                btnReq("#btnSubmitFormService", "Enregistrement...");
            },
            success: function (data) {
                console.log(data);
                // $(".loader_backdrop2").css('display', "none");

                btnRes("#btnSubmitFormService", "Enregistrer", "fa-save");
                if (data.success) {
                    $.notify(data.message, "success");
                    APP.tables['data-table-service'].ajax.reload(null, false);

                    $("#service-modal").modal("hide");
                } else {
                    $.notify(data.message);
                }
            }
        })
    });
}


function modalUpdatedService(code) {
    // let btn = btn_action.id;

    $.ajax({
        method: "POST",
        url: APP.ajax,
        data: {
            action: 'btn_showmodal_service_update',
            codeService: code
        },
        dataType: 'JSON',
        beforeSend: function () {
            $(".loader_backdrop2").css('display', "block");
            // btnReq(".modal_footer", "Traitement...");
        },
        success: function (data) {

            $(".loader_backdrop2").css('display', "none");

            if (data.success) {
                $(".data-service-modal").html(data.data);
                $("#service-modal").modal("show");

            } else {
                $.notify(data.message);

            }
        }
    });
}

updatedService();
function updatedService() {
    $("body").on("submit", "#frmUpdateService", function (e) {
        e.preventDefault();
        var data = $(this).serialize();


        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: data,
            dataType: "JSON",
            beforeSend: function () {
                // $(".loader_backdrop2").css('display', "block");

                btnReq("#btnSubmitFormService", "Mise à jour en cours...");
            },
            success: function (data) {
                // $(".loader_backdrop2").css('display', "none");
                console.log(data);

                btnRes("#btnSubmitFormService", "Enregistrer", "fa-save");

                if (data.success) {
                    APP.tables['data-table-service'].ajax.reload(null, false);
                    $.notify(data.message, "success");
                    $("#service-modal").modal("hide");

                } else {
                    $.notify(data.message);
                }
            }
        })
    });
}

function changeStatutService(code, statut) {
    swal({
        title: "Notification",
        text: "Voulez-vous vraiment modifier le statut de ce service?",
        icon: "warning",
        dangerMode: true,
        closeOnClickOutside: false,
        buttons: {
            cancel: true,
            confirm: "Confirmer",
        },
    })
        .then(willDelete => {
            if (willDelete) {


                $.ajax({
                    url: APP.ajax,
                    method: 'POST',
                    data: {
                        action: 'change_statut_services',
                        code_service: code,
                        statut_service: statut
                    },
                    dataType: 'JSON',
                    beforeSend: function () {
                        $(".loader_backdrop2").css('display', "block");
                    },
                    success: function (data) {
                        $(".loader_backdrop2").css('display', "none");

                        if (data.success) {
                            $.notify(data.message, "success");
                            APP.tables['data-table-service'].ajax.reload(null, false);
                        } else {
                            $.notify(data.message);
                        }
                    }
                });;
            }
        });
}
/** FIN SECTION SERVICE */


/** DEBUT SECTION ANNEE */
loadDataTableMany('data-table-annee', '.session-annee', '#data-table-annee', 'charger_data_annees');

openModalAddAnnee();
function openModalAddAnnee() {
    $('#btn_annee_addModal').click(function (e) {
        e.preventDefault();

        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: {
                action: 'btn_showmodal_annee_add'
            },
            dataType: "JSON",
            beforeSend: function () {
                $(".loader_backdrop2").css('display', "block");
                // btnReq("#ClientAddModal", "Traitement...");

            },
            success: function (data) {
                // btnRes("#ClientAddModal", 'Ajouter un client', 'fa-plus');
                // ;

                $(".loader_backdrop2").css('display', "none");
                if (data.success) {
                    var output = data.data;
                    $(".data-annee-modal").html(output.data);
                    $("#annee-modal").modal("show");


                } else {
                    $.notify(data.message);

                }

            }
        })
    });
}

ajouterAnnee();
function ajouterAnnee() {
    $("body").on("submit", "#frmAddAnnee", function (e) {
        e.preventDefault();
        var data = $(this).serialize();

        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: data,
            dataType: "JSON",
            beforeSend: function () {
                // $(".loader_backdrop2").css('display', "block");

                btnReq("#btnSubmitFormAnnee", "Enregistrement...");
            },
            success: function (data) {
                console.log(data);
                btnRes("#btnSubmitFormAnnee", "Enregistrer", "fa-save");
                // $(".loader_backdrop2").css('display', "none");

                if (data.success) {
                    APP.tables['data-table-annee'].ajax.reload(null, false);
                    $.notify(data.message, "success");
                    $("#annee-modal").modal("hide");
                } else {
                    $.notify(data.message);
                }
            }
        })
    });
}


function modalUpdatedAnnee(code) {
    // let btn = btn_action.id;

    $.ajax({
        method: "POST",
        url: APP.ajax,
        data: {
            action: 'btn_showmodal_annee_update',
            codeAnnee: code
        },
        dataType: 'JSON',
        beforeSend: function () {
            $(".loader_backdrop2").css('display', "block");
            // btnReq(".modal_footer", "Traitement...");
        },
        success: function (data) {

            $(".loader_backdrop2").css('display', "none");

            if (data.success) {
                $(".data-annee-modal").html(data.data);
                $("#annee-modal").modal("show");

            } else {
                $.notify(data.message);

            }
        }
    });
}

updatedAnnee();
function updatedAnnee() {
    $("body").on("submit", "#frmUpdateAnnee", function (e) {
        e.preventDefault();
        var data = $(this).serialize();


        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: data,
            dataType: "JSON",
            beforeSend: function () {
                // $(".loader_backdrop2").css('display', "block");

                btnReq("#btnSubmitFormAnnee", "Mise à jour en cours...");
            },
            success: function (data) {
                // $(".loader_backdrop2").css('display', "none");
                console.log(data);

                btnRes("#btnSubmitFormAnnee", "Enregistrer", "fa-save");
                if (data.success) {
                    APP.tables['data-table-annee'].ajax.reload(null, false);
                    $.notify(data.message, "success");
                    $("#annee-modal").modal("hide");

                } else {
                    $.notify(data.message);
                }
            }
        })
    });
}

function changeStatutAnnee(code, statut) {
    swal({
        title: "Notification",
        text: "Voulez-vous vraiment modifier le statut de cette annee?",
        icon: "warning",
        dangerMode: true,
        closeOnClickOutside: false,
        buttons: {
            cancel: true,
            confirm: "Confirmer",
        },
    })
        .then(willDelete => {
            if (willDelete) {


                $.ajax({
                    url: APP.ajax,
                    method: 'POST',
                    data: {
                        action: 'change_statut_annees',
                        code_annee: code,
                        statut_annee: statut
                    },
                    dataType: 'JSON',
                    beforeSend: function () {
                        $(".loader_backdrop2").css('display', "block");
                    },
                    success: function (data) {
                        $(".loader_backdrop2").css('display', "none");

                        if (data.success) {
                            $.notify(data.message, "success");
                            APP.tables['data-table-annee'].ajax.reload(null, false);
                        } else {
                            $.notify(data.message);
                        }
                    }
                });;
            }
        });
}
/** FIN SECTION ANNEE */



/** DEBUT SECTION SEMESTRES */

loadDataTableMany('data-table-session', '.session-annee', '#data-table-session', 'charger_data_sessions');

openModalAddSemestre();
function openModalAddSemestre() {
    $('#btn_session_addModal').click(function (e) {
        e.preventDefault();

        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: {
                action: 'btn_showmodal_session_add'
            },
            dataType: "JSON",
            beforeSend: function () {
                $(".loader_backdrop2").css('display', "block");
                // btnReq("#ClientAddModal", "Traitement...");

            },
            success: function (data) {
                console.log(data);

                // btnRes("#ClientAddModal", 'Ajouter un client', 'fa-plus');
                // ;

                $(".loader_backdrop2").css('display', "none");
                if (data.success) {
                    var output = data.data;
                    $(".data-session-modal").html(output.data);
                    $("#session-modal").modal("show");


                } else {
                    $.notify(data.message);

                }

            }
        })
    });
}

ajouterSemestre();
function ajouterSemestre() {
    $("body").on("submit", "#frmAddSemestre", function (e) {
        e.preventDefault();
        var data = $(this).serialize();

        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: data,
            dataType: "JSON",
            beforeSend: function () {
                // $(".loader_backdrop2").css('display', "block");

                btnReq("#btnSubmitFormSemestre", "Enregistrement...");
            },
            success: function (data) {
                console.log(data);
                btnRes("#btnSubmitFormSemestre", "Enregistrer", "fa-save");
                // $(".loader_backdrop2").css('display', "none");

                if (data.success) {
                    APP.tables['data-table-session'].ajax.reload(null, false);
                    $.notify(data.message, "success");
                    $("#session-modal").modal("hide");
                } else {
                    $.notify(data.message);
                }
            }
        })
    });
}


function modalUpdatedSemestre(code) {
    // let btn = btn_action.id;

    $.ajax({
        method: "POST",
        url: APP.ajax,
        data: {
            action: 'btn_showmodal_session_update',
            codesession: code
        },
        dataType: 'JSON',
        beforeSend: function () {
            $(".loader_backdrop2").css('display', "block");
            // btnReq(".modal_footer", "Traitement...");
        },
        success: function (data) {

            $(".loader_backdrop2").css('display', "none");

            if (data.success) {
                $(".data-session-modal").html(data.data);
                $("#session-modal").modal("show");

            } else {
                $.notify(data.message);

            }
        }
    });
}

updatedSemestre();
function updatedSemestre() {
    $("body").on("submit", "#frmUpdateSemestre", function (e) {
        e.preventDefault();
        var data = $(this).serialize();


        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: data,
            dataType: "JSON",
            beforeSend: function () {
                // $(".loader_backdrop2").css('display', "block");

                btnReq("#btnSubmitFormSemestre", "Mise à jour en cours...");
            },
            success: function (data) {
                // $(".loader_backdrop2").css('display', "none");
                console.log(data);

                btnRes("#btnSubmitFormSemestre", "Enregistrer", "fa-save");
                return
                if (data.success) {
                    APP.tables['data-table-session'].ajax.reload(null, false);
                    $.notify(data.message, "success");
                    $("#session-modal").modal("hide");

                } else {
                    $.notify(data.message);
                }
            }
        })
    });
}

function changeStatutSemestre(code, statut) {
    swal({
        title: "Notification",
        text: "Voulez-vous vraiment modifier le statut de cette session?",
        icon: "warning",
        dangerMode: true,
        closeOnClickOutside: false,
        buttons: {
            cancel: true,
            confirm: "Confirmer",
        },
    })
        .then(willDelete => {
            if (willDelete) {

                $.ajax({
                    url: APP.ajax,
                    method: 'POST',
                    data: {
                        action: 'change_statut_sessions',
                        code_session: code,
                        statut_session: statut
                    },
                    dataType: 'JSON',
                    beforeSend: function () {
                        $(".loader_backdrop2").css('display', "block");
                    },
                    success: function (data) {
                        $(".loader_backdrop2").css('display', "none");

                        if (data.success) {
                            $.notify(data.message, "success");
                            APP.tables['data-table-session'].ajax.reload(null, false);
                        } else {
                            $.notify(data.message);
                        }
                    }
                });;
            }
        });
}
/** FIN SECTION SEMESTRES */


/** DEBUT SECTION ZONE */

loadDataTable('data-table-zone', '#data-table-zone', 'charger_data_zones');

openModalAddZone();
function openModalAddZone() {
    $('#btn_zone_addModal').click(function (e) {
        e.preventDefault();

        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: {
                action: 'btn_showmodal_zone_add'
            },
            dataType: "JSON",
            beforeSend: function () {
                $(".loader_backdrop2").css('display', "block");
                // btnReq("#ClientAddModal", "Traitement...");

            },
            success: function (data) {
                console.log(data);

                // btnRes("#ClientAddModal", 'Ajouter un client', 'fa-plus');
                // ;

                $(".loader_backdrop2").css('display', "none");
                if (data.success) {
                    var output = data.data;
                    $(".data-zone-modal").html(output.data);
                    $("#zone-modal").modal("show");


                } else {
                    $.notify(data.message);

                }

            }
        })
    });
}

ajouterZone();
function ajouterZone() {
    $("body").on("submit", "#frmAddZone", function (e) {
        e.preventDefault();
        var data = $(this).serialize();

        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: data,
            // dataType: "JSON",
            beforeSend: function () {
                // $(".loader_backdrop2").css('display', "block");

                btnReq("#btnSubmitFormZone", "Enregistrement...");
            },
            success: function (data) {
                console.log(data);
                btnRes("#btnSubmitFormZone", "Enregistrer", "fa-save");
                // $(".loader_backdrop2").css('display', "none");

                if (data.success) {
                    APP.tables['data-table-zone'].ajax.reload(null, false);
                    $.notify(data.message, "success");
                    $("#zone-modal").modal("hide");
                } else {
                    $.notify(data.message);
                }
            }
        })
    });
}


function modalUpdatedZone(code) {
    // let btn = btn_action.id;

    $.ajax({
        method: "POST",
        url: APP.ajax,
        data: {
            action: 'btn_showmodal_session_update',
            codesession: code
        },
        dataType: 'JSON',
        beforeSend: function () {
            $(".loader_backdrop2").css('display', "block");
            // btnReq(".modal_footer", "Traitement...");
        },
        success: function (data) {

            $(".loader_backdrop2").css('display', "none");

            if (data.success) {
                $(".data-zone-modal").html(data.data);
                $("#zone-modal").modal("show");

            } else {
                $.notify(data.message);

            }
        }
    });
}

updatedZone();
function updatedZone() {
    $("body").on("submit", "#frmUpdateZone", function (e) {
        e.preventDefault();
        var data = $(this).serialize();


        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: data,
            dataType: "JSON",
            beforeSend: function () {
                // $(".loader_backdrop2").css('display', "block");

                btnReq("#btnSubmitFormZone", "Mise à jour en cours...");
            },
            success: function (data) {
                // $(".loader_backdrop2").css('display', "none");
                console.log(data);

                btnRes("#btnSubmitFormZone", "Enregistrer", "fa-save");
                return
                if (data.success) {
                    APP.tables['data-table-zone'].ajax.reload(null, false);
                    $.notify(data.message, "success");
                    $("#zone-modal").modal("hide");

                } else {
                    $.notify(data.message);
                }
            }
        })
    });
}

function changeStatutSemestre(code, statut) {
    swal({
        title: "Notification",
        text: "Voulez-vous vraiment modifier le statut de cette session?",
        icon: "warning",
        dangerMode: true,
        closeOnClickOutside: false,
        buttons: {
            cancel: true,
            confirm: "Confirmer",
        },
    })
        .then(willDelete => {
            if (willDelete) {

                $.ajax({
                    url: APP.ajax,
                    method: 'POST',
                    data: {
                        action: 'change_statut_sessions',
                        code_session: code,
                        statut_session: statut
                    },
                    dataType: 'JSON',
                    beforeSend: function () {
                        $(".loader_backdrop2").css('display', "block");
                    },
                    success: function (data) {
                        $(".loader_backdrop2").css('display', "none");

                        if (data.success) {
                            $.notify(data.message, "success");
                            APP.tables['data-table-zone'].ajax.reload(null, false);
                        } else {
                            $.notify(data.message);
                        }
                    }
                });;
            }
        });
}
/** FIN SECTION ZONE */

/** DEBUT SECTION CATEGORIES PACK */

loadDataTable('data-table-categorie-pack', '#data-table-categorie-pack', 'charger_data_categorie_packs');

openModalAddCategoriePack();
function openModalAddCategoriePack() {
    $('#btn_categorie_pack_addModal').click(function (e) {
        e.preventDefault();

        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: {
                action: 'btn_showmodal_categoriePack_add'
            },
            dataType: "JSON",
            beforeSend: function () {
                $(".loader_backdrop2").css('display', "block");
                // btnReq("#ClientAddModal", "Traitement...");

            },
            success: function (data) {
                console.log(data);

                // btnRes("#ClientAddModal", 'Ajouter un client', 'fa-plus');
                // ;

                $(".loader_backdrop2").css('display', "none");
                if (data.success) {
                    var output = data.data;
                    $(".data-categorie-pack-modal").html(output.data);
                    $("#categorie-pack-modal").modal("show");


                } else {
                    $.notify(data.message);

                }

            }
        })
    });
}

ajouterCategoriePack();
function ajouterCategoriePack() {
    $("body").on("submit", "#frmAddCategoriePack", function (e) {
        e.preventDefault();
        var data = $(this).serialize();

        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: data,
            dataType: "JSON",
            beforeSend: function () {
                // $(".loader_backdrop2").css('display', "block");

                btnReq("#btnSubmitFormCategoriePack", "Enregistrement...");
            },
            success: function (data) {
                console.log(data);
                // return;
                btnRes("#btnSubmitFormCategoriePack", "Enregistrer", "fa-save");
                // $(".loader_backdrop2").css('display', "none");

                if (data.success) {
                    APP.tables['data-table-categorie-pack'].ajax.reload(null, false);
                    $.notify(data.message, "success");
                    $("#categorie-pack-modal").modal("hide");
                } else {
                    $.notify(data.message);
                }
            }
        })
    });
}


function modalUpdatedCategoriePack(code) {
    // let btn = btn_action.id;

    $.ajax({
        method: "POST",
        url: APP.ajax,
        data: {
            action: 'btn_showmodal_session_update',
            codesession: code
        },
        dataType: 'JSON',
        beforeSend: function () {
            $(".loader_backdrop2").css('display', "block");
            // btnReq(".modal_footer", "Traitement...");
        },
        success: function (data) {

            $(".loader_backdrop2").css('display', "none");

            if (data.success) {
                $(".data-categoriePack-modal").html(data.data);
                $("#categorie-pack-modal").modal("show");

            } else {
                $.notify(data.message);

            }
        }
    });
}

updatedCategoriePack();
function updatedCategoriePack() {
    $("body").on("submit", "#frmUpdateCategoriePack", function (e) {
        e.preventDefault();
        var data = $(this).serialize();


        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: data,
            dataType: "JSON",
            beforeSend: function () {
                // $(".loader_backdrop2").css('display', "block");

                btnReq("#btnSubmitFormCategoriePack", "Mise à jour en cours...");
            },
            success: function (data) {
                // $(".loader_backdrop2").css('display', "none");
                console.log(data);

                btnRes("#btnSubmitFormCategoriePack", "Enregistrer", "fa-save");
                return
                if (data.success) {
                    APP.tables['data-table-categorie-pack'].ajax.reload(null, false);
                    $.notify(data.message, "success");
                    $("#categorie-pack-modal").modal("hide");

                } else {
                    $.notify(data.message);
                }
            }
        })
    });
}

function changeStatutSemestre(code, statut) {
    swal({
        title: "Notification",
        text: "Voulez-vous vraiment modifier le statut de cette session?",
        icon: "warning",
        dangerMode: true,
        closeOnClickOutside: false,
        buttons: {
            cancel: true,
            confirm: "Confirmer",
        },
    })
        .then(willDelete => {
            if (willDelete) {

                $.ajax({
                    url: APP.ajax,
                    method: 'POST',
                    data: {
                        action: 'change_statut_sessions',
                        code_session: code,
                        statut_session: statut
                    },
                    dataType: 'JSON',
                    beforeSend: function () {
                        $(".loader_backdrop2").css('display', "block");
                    },
                    success: function (data) {
                        $(".loader_backdrop2").css('display', "none");

                        if (data.success) {
                            $.notify(data.message, "success");
                            APP.tables['data-table-categoriePack'].ajax.reload(null, false);
                        } else {
                            $.notify(data.message);
                        }
                    }
                });
            }
        });
}
/** FIN SECTION CATEGORIES PACKS */


/** DEBUT SECTION ARTICLES  */

loadDataTable('data-table-article', '#data-table-article', 'charger_data_articles');

openModalAddArticle();
function openModalAddArticle() {
    $('#btn_article_addModal').click(function (e) {
        e.preventDefault();

        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: {
                action: 'btn_showmodal_article_add'
            },
            dataType: "JSON",
            beforeSend: function () {
                $(".loader_backdrop2").css('display', "block");
                // btnReq("#ClientAddModal", "Traitement...");

            },
            success: function (data) {
                console.log(data);

                // btnRes("#ClientAddModal", 'Ajouter un client', 'fa-plus');
                // ;

                $(".loader_backdrop2").css('display', "none");
                if (data.success) {
                    var output = data.data;
                    $(".data-article-modal").html(output.data);
                    $("#article-modal").modal("show");


                } else {
                    $.notify(data.message);

                }

            }
        })
    });
}

ajouterArticle();
function ajouterArticle() {
    $("body").on("submit", "#frmAddArticle", function (e) {
        e.preventDefault();
        var data = $(this).serialize();

        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: data,
            dataType: "JSON",
            beforeSend: function () {
                // $(".loader_backdrop2").css('display', "block");

                btnReq("#btnSubmitFormArticle", "Enregistrement...");
            },
            success: function (data) {
                console.log(data);
                // return;
                btnRes("#btnSubmitFormArticle", "Enregistrer", "fa-save");
                // $(".loader_backdrop2").css('display', "none");

                if (data.success) {
                    APP.tables['data-table-article'].ajax.reload(null, false);
                    $.notify(data.message, "success");
                    $("#article-modal").modal("hide");
                } else {
                    $.notify(data.message);
                }
            }
        })
    });
}


function modalUpdatedArticle(code) {
    // let btn = btn_action.id;

    $.ajax({
        method: "POST",
        url: APP.ajax,
        data: {
            action: 'btn_showmodal_session_update',
            codesession: code
        },
        dataType: 'JSON',
        beforeSend: function () {
            $(".loader_backdrop2").css('display', "block");
            // btnReq(".modal_footer", "Traitement...");
        },
        success: function (data) {

            $(".loader_backdrop2").css('display', "none");

            if (data.success) {
                $(".data-article-modal").html(data.data);
                $("#article-modal").modal("show");

            } else {
                $.notify(data.message);

            }
        }
    });
}

updatedArticle();
function updatedArticle() {
    $("body").on("submit", "#frmUpdateArticle", function (e) {
        e.preventDefault();
        var data = $(this).serialize();


        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: data,
            dataType: "JSON",
            beforeSend: function () {
                // $(".loader_backdrop2").css('display', "block");

                btnReq("#btnSubmitFormArticle", "Mise à jour en cours...");
            },
            success: function (data) {
                // $(".loader_backdrop2").css('display', "none");
                console.log(data);

                btnRes("#btnSubmitFormArticle", "Enregistrer", "fa-save");
                return
                if (data.success) {
                    APP.tables['data-table-article'].ajax.reload(null, false);
                    $.notify(data.message, "success");
                    $("#article-modal").modal("hide");

                } else {
                    $.notify(data.message);
                }
            }
        })
    });
}

function changeStatutArticle(code, statut) {
    swal({
        title: "Notification",
        text: "Voulez-vous vraiment modifier le statut de cette session?",
        icon: "warning",
        dangerMode: true,
        closeOnClickOutside: false,
        buttons: {
            cancel: true,
            confirm: "Confirmer",
        },
    })
        .then(willDelete => {
            if (willDelete) {

                $.ajax({
                    url: APP.ajax,
                    method: 'POST',
                    data: {
                        action: 'change_statut_sessions',
                        code_session: code,
                        statut_session: statut
                    },
                    dataType: 'JSON',
                    beforeSend: function () {
                        $(".loader_backdrop2").css('display', "block");
                    },
                    success: function (data) {
                        $(".loader_backdrop2").css('display', "none");

                        if (data.success) {
                            $.notify(data.message, "success");
                            APP.tables['data-table-article'].ajax.reload(null, false);
                        } else {
                            $.notify(data.message);
                        }
                    }
                });
            }
        });
}
/** FIN SECTION ARTICLES */


/** DEBUT SECTION PACKS */

loadDataTable('data-table-pack', '#data-table-pack', 'charger_data_packs');

function AddNewRowTable(article) {
    let html = '';
    let index = 0;

    if (APP.articleSelected.includes(article[0])) return $.notify('Désolé,cet article existe déjà dans la liste');

    // let index = $('.table_commande tbody tr').length + 1;
    APP.articleSelected.push(article[0]);
    index = APP.articleSelected.length;
    
    html = dataRow(article);

    $('.table_add_pack tbody').append(html);
    $('#countArticle').text(index);
}

function dataRow(article) {
    html = `
        <tr data-code="${article[0]}">
            <td>${article[1]}</td>
            <td class="text-dark text-center qte" contenteditable="true">1</td>
            
            <td> 
                <button data-id="${article[0]}" title="Retirer l\'article de la liste" class="btn btn-danger btn-sm btn_remove_data_article">
                    <i class="fa fa-trash"></i> 
                </button>
            </td>
        </tr>`;
    return html;
}

addDataPack();
function addDataPack() {
    $("body").on("click", "#btnAddDataPack", function (e) {
        // e.preventDefault();
        var data = $('#dataPack').val();
                    
        if(data == "") return $.notify('Désolé,aucun article selectionner.');
        let article = data.split('&');
        // let code = val[0];
        // let libelle = val[1];
        // console.log(libelle,code);
        AddNewRowTable(article);
        
    });
}

function pushData(selector) {
    let dataselector = [];
    $('.table_add_pack tbody tr').each(function () {
        var code = $(this).data('code');
        var el = $(this).find('.' + selector).text();
        
        dataselector.push({code:code,qte:Number(el)});
        // dataselector.push(el);
    });
    return dataselector
}

   btn_suprimer_data_pack();
    function btn_suprimer_data_pack() {
        $('body').on('click','.btn_remove_data_article', function (e) {
            e.preventDefault();
            var element = $(this);
            var code_article = $(this).data('id');

            // console.log(id_vente,id_article);
            // return:
            

          swal({
                title: "Etes vous sure",
                text: "de vouloir retirer cet article ?",
                icon: "warning",
                buttons: ['Non', 'Oui'],
                dangerMode: true,
            }).then((a) => {
                if (a) {
                    element.closest('tr').remove();
                    APP.articleSelected = APP.articleSelected.filter(a => a != code_article);
                    index = APP.articleSelected.length;
                    $('#countArticle').text(index);


                    $.notify("Article retiré avec succès", "success");
                }
            })
        });
    }

openModalAddPack();


function openModalAddPack() {
    $('#btn_pack_addModal').click(function (e) {
        e.preventDefault();

        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: {
                action: 'btn_showmodal_pack_add'
            },
            dataType: "JSON",
            beforeSend: function () {
                $(".loader_backdrop2").css('display', "block");
                // btnReq("#ClientAddModal", "Traitement...");

            },
            success: function (data) {
                console.log(data);

                // btnRes("#ClientAddModal", 'Ajouter un client', 'fa-plus');
                // ;

                $(".loader_backdrop2").css('display', "none");
                if (data.success) {
                    var output = data.data;
                    APP.articleSelected = [];
                    $(".data-pack-modal").html(output.data);
                    $("#pack-modal").modal("show");


                } else {
                    $.notify(data.message);

                }

            }
        })
    });
}


ajouterPack();
function ajouterPack() {
    $("body").on("submit", "#frmAddPack", function (e) {
        e.preventDefault();
        var data = $(this).serialize();
        var packArticles = pushData('qte');
        console.log(packArticles);
        return;
        

        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: data,
            // dataType: "JSON",
            beforeSend: function () {
                // $(".loader_backdrop2").css('display', "block");

                // btnReq("#btnSubmitFormPack", "Enregistrement...");
            },
            success: function (data) {
                console.log(data);
                // btnRes("#btnSubmitFormPack", "Enregistrer", "fa-save");
                // $(".loader_backdrop2").css('display', "none");

                if (data.success) {
                    APP.tables['data-table-pack'].ajax.reload(null, false);
                    $.notify(data.message, "success");
                    $("#pack-modal").modal("hide");
                } else {
                    $.notify(data.message);
                }
            }
        })
    });
}


function modalUpdatedPack(code) {
    // let btn = btn_action.id;

    $.ajax({
        method: "POST",
        url: APP.ajax,
        data: {
            action: 'btn_showmodal_session_update',
            codesession: code
        },
        dataType: 'JSON',
        beforeSend: function () {
            $(".loader_backdrop2").css('display', "block");
            // btnReq(".modal_footer", "Traitement...");
        },
        success: function (data) {

            $(".loader_backdrop2").css('display', "none");

            if (data.success) {
                $(".data-pack-modal").html(data.data);
                $("#pack-modal").modal("show");

            } else {
                $.notify(data.message);

            }
        }
    });
}

updatedPack();
function updatedPack() {
    $("body").on("submit", "#frmUpdateCategoriePack", function (e) {
        e.preventDefault();
        var data = $(this).serialize();

        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: data,
            dataType: "JSON",
            beforeSend: function () {
                // $(".loader_backdrop2").css('display', "block");

                btnReq("#btnSubmitFormCategoriePack", "Mise à jour en cours...");
            },
            success: function (data) {
                // $(".loader_backdrop2").css('display', "none");
                console.log(data);

                btnRes("#btnSubmitFormCategoriePack", "Enregistrer", "fa-save");
                return
                if (data.success) {
                    APP.tables['data-table-pack'].ajax.reload(null, false);
                    $.notify(data.message, "success");
                    $("#pack-modal").modal("hide");

                } else {
                    $.notify(data.message);
                }
            }
        })
    });
}

function changeStatutPack(code, statut) {
    swal({
        title: "Notification",
        text: "Voulez-vous vraiment modifier le statut de ce pack?",
        icon: "warning",
        dangerMode: true,
        closeOnClickOutside: false,
        buttons: {
            cancel: true,
            confirm: "Confirmer",
        },
    })
        .then(willDelete => {
            if (willDelete) {

                $.ajax({
                    url: APP.ajax,
                    method: 'POST',
                    data: {
                        action: 'change_statut_sessions',
                        code_session: code,
                        statut_session: statut
                    },
                    dataType: 'JSON',
                    beforeSend: function () {
                        $(".loader_backdrop2").css('display', "block");
                    },
                    success: function (data) {
                        $(".loader_backdrop2").css('display', "none");

                        if (data.success) {
                            $.notify(data.message, "success");
                            APP.tables['data-table-pack'].ajax.reload(null, false);
                        } else {
                            $.notify(data.message);
                        }
                    }
                });;
            }
        });
}
/** FIN SECTION PACKS */

/** DEBUT SECTION DEPENSE */

loadDataTable('data-table-depense', '#data-table-depense', 'charger_data_depenses');

openModalAddDepense();
function openModalAddDepense() {
    $('#btn_depense_addModal').click(function (e) {
        e.preventDefault();

        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: {
                action: 'btn_showmodal_depense_add'
            },
            dataType: "JSON",
            beforeSend: function () {
                $(".loader_backdrop2").css('display', "block");
                // btnReq("#ClientAddModal", "Traitement...");

            },
            success: function (data) {
                console.log(data);

                // btnRes("#ClientAddModal", 'Ajouter un client', 'fa-plus');
                // ;

                $(".loader_backdrop2").css('display', "none");
                if (data.success) {
                    var output = data.data;
                    $(".data-depense-modal").html(output.data);
                    $("#depense-modal").modal("show");


                } else {
                    $.notify(data.message);

                }

            }
        })
    });
}

ajouterDepense();
function ajouterDepense() {
    $("body").on("submit", "#frmAddDepense", function (e) {
        e.preventDefault();
        var data = $(this).serialize();

        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: data,
            dataType: "JSON",
            beforeSend: function () {
                // $(".loader_backdrop2").css('display', "block");

                btnReq("#btnSubmitFormDepense", "Enregistrement...");
            },
            success: function (data) {
                console.log(data);
                btnRes("#btnSubmitFormDepense", "Enregistrer", "fa-save");
                // $(".loader_backdrop2").css('display', "none");

                if (data.success) {
                    APP.tables['data-table-depense'].ajax.reload(null, false);
                    $.notify(data.message, "success");
                    $("#depense-modal").modal("hide");
                } else {
                    $.notify(data.message);
                }
            }
        })
    });
}


function modalUpdatedDepense(code) {
    // let btn = btn_action.id;

    $.ajax({
        method: "POST",
        url: APP.ajax,
        data: {
            action: 'btn_showmodal_depense_update',
            codedepense: code
        },
        dataType: 'JSON',
        beforeSend: function () {
            $(".loader_backdrop2").css('display', "block");
            // btnReq(".modal_footer", "Traitement...");
        },
        success: function (data) {
            console.log(data);

            $(".loader_backdrop2").css('display', "none");

            if (data.success) {
                $(".data-depense-modal").html(data.data);
                $("#depense-modal").modal("show");

            } else {
                $.notify(data.message);

            }
        }
    });
}

updatedDepense();
function updatedDepense() {
    $("body").on("submit", "#frmUpdateDepense", function (e) {
        e.preventDefault();
        var data = $(this).serialize();


        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: data,
            // dataType: "JSON",
            beforeSend: function () {
                // $(".loader_backdrop2").css('display', "block");

                btnReq("#btnSubmitFormDepense", "Mise à jour en cours...");
            },
            success: function (data) {
                // $(".loader_backdrop2").css('display', "none");
                console.log(data);

                btnRes("#btnSubmitFormDepense", "Enregistrer", "fa-save");
                return
                if (data.success) {
                    APP.tables['data-table-depense'].ajax.reload(null, false);
                    $.notify(data.message, "success");
                    $("#depense-modal").modal("hide");

                } else {
                    $.notify(data.message);
                }
            }
        })
    });
}

function changeStatutDepense(code, statut) {
    swal({
        title: "Notification",
        text: "Voulez-vous vraiment modifier le statut de cette depense?",
        icon: "warning",
        dangerMode: true,
        closeOnClickOutside: false,
        buttons: {
            cancel: true,
            confirm: "Confirmer",
        },
    })
        .then(willDelete => {
            if (willDelete) {

                $.ajax({
                    url: APP.ajax,
                    method: 'POST',
                    data: {
                        action: 'change_statut_depenses',
                        code_depense: code,
                        statut_depense: statut
                    },
                    dataType: 'JSON',
                    beforeSend: function () {
                        $(".loader_backdrop2").css('display', "block");
                    },
                    success: function (data) {
                        $(".loader_backdrop2").css('display', "none");

                        if (data.success) {
                            $.notify(data.message, "success");
                            APP.tables['data-table-depense'].ajax.reload(null, false);
                        } else {
                            $.notify(data.message);
                        }
                    }
                });;
            }
        });
}
/** FIN SECTION DEPENSE */



/** DEBUT SECTION INSCRIPTION */

loadDataTable('data-table-inscription', '#data-table-inscription', 'charger_data_inscriptions');

openModalAddInscription();
function openModalAddInscription() {
    $('#btn_inscription_addModal').click(function (e) {
        e.preventDefault();

 
        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: {
                action: 'btn_showmodal_inscription_add'
            },
            dataType: "JSON",
            beforeSend: function () {
                $(".loader_backdrop2").css('display', "block");
                // btnReq("#ClientAddModal", "Traitement...");

            },
            success: function (data) {
                console.log(data);

                // btnRes("#ClientAddModal", 'Ajouter un client', 'fa-plus');
                // ;

                $(".loader_backdrop2").css('display', "none");
                if (data.success) {
                    var output = data.data;
                    $(".data-inscription-client-modal").html(output.data);
                    $("#inscription-client-modal").modal("show");


                } else {
                    $.notify(data.message);

                }

            }
        })
    });
}

ajouterinscription();
function ajouterinscription() {
    $("body").on("submit", "#frmAddInscription", function (e) {
        e.preventDefault();
        var data = $(this).serialize();

        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: data,
            dataType: "JSON",
            beforeSend: function () {
                // $(".loader_backdrop2").css('display', "block");

                btnReq("#btnSubmitFormInscription", "Enregistrement...");
            },
            success: function (data) {
                console.log(data);
                btnRes("#btnSubmitFormInscription", "Enregistrer", "fa-save");
                // $(".loader_backdrop2").css('display', "none");

                if (data.success) {
                    APP.tables['data-table-inscription'].ajax.reload(null, false);
                    $.notify(data.message, "success");
                    $("#inscription-modal").modal("hide");
                } else {
                    $.notify(data.message);
                }
            }
        })
    });
}


function modalUpdatedInscription(code) {
    // let btn = btn_action.id;

    $.ajax({
        method: "POST",
        url: APP.ajax,
        data: {
            action: 'btn_showmodal_depense_update',
            codedepense: code
        },
        dataType: 'JSON',
        beforeSend: function () {
            $(".loader_backdrop2").css('display', "block");
            // btnReq(".modal_footer", "Traitement...");
        },
        success: function (data) {
            console.log(data);

            $(".loader_backdrop2").css('display', "none");

            if (data.success) {
                $(".data-depense-modal").html(data.data);
                $("#depense-modal").modal("show");

            } else {
                $.notify(data.message);

            }
        }
    });
}

updatedinscription();
function updatedinscription() {
    $("body").on("submit", "#frmUpdateinscription", function (e) {
        e.preventDefault();
        var data = $(this).serialize();


        $.ajax({
            method: "POST",
            url: APP.ajax,
            data: data,
            // dataType: "JSON",
            beforeSend: function () {
                // $(".loader_backdrop2").css('display', "block");

                btnReq("#btnSubmitFormInscription", "Mise à jour en cours...");
            },
            success: function (data) {
                // $(".loader_backdrop2").css('display', "none");
                console.log(data);

                btnRes("#btnSubmitFormInscription", "Enregistrer", "fa-save");
                return
                if (data.success) {
                    APP.tables['data-table-depense'].ajax.reload(null, false);
                    $.notify(data.message, "success");
                    $("#depense-modal").modal("hide");

                } else {
                    $.notify(data.message);
                }
            }
        })
    });
}

function changeStatutinscription(code, statut) {
    swal({
        title: "Notification",
        text: "Voulez-vous vraiment modifier le statut de cette depense?",
        icon: "warning",
        dangerMode: true,
        closeOnClickOutside: false,
        buttons: {
            cancel: true,
            confirm: "Confirmer",
        },
    })
        .then(willDelete => {
            if (willDelete) {

                $.ajax({
                    url: APP.ajax,
                    method: 'POST',
                    data: {
                        action: 'change_statut_depenses',
                        code_depense: code,
                        statut_depense: statut
                    },
                    dataType: 'JSON',
                    beforeSend: function () {
                        $(".loader_backdrop2").css('display', "block");
                    },
                    success: function (data) {
                        $(".loader_backdrop2").css('display', "none");

                        if (data.success) {
                            $.notify(data.message, "success");
                            APP.tables['data-table-depense'].ajax.reload(null, false);
                        } else {
                            $.notify(data.message);
                        }
                    }
                });;
            }
        });
}
/** FIN SECTION INSCRIPTION */




// SEXION FILTER DATA

initDateRangeFilterDepense(APP.date_start_picker, APP.date_end_picker);

function initDateRangeFilterDepense(startDate, endDate) {

    $('#datefilterDepense').daterangepicker({
        startDate: startDate,
        endDate: endDate,
        autoUpdateInput: true,
        locale: {
            // format: 'YYYY-MM-DD',
            format: 'DD-MM-YYYY',
            cancelLabel: 'Clear'
        }
    });

    $('#datefilterDepense').on('apply.daterangepicker', function (ev, picker) {
        let dateDebut = picker.startDate.format('YYYY-MM-DD 00:00:00');
        let dateFin = picker.endDate.format('YYYY-MM-DD 23:59:59');
        let dateD = picker.startDate.format('DD-MM-YYYY');
        let dateF = picker.endDate.format('DD-MM-YYYY');
        $(this).val(dateD + ' - ' + dateF);
        // Appeler la fonction de recherche avec les dates sélectionnées
        $('#activityDateRange').text("Activité du " + dateD + ' au ' + dateF);

        $.ajax({
            url: "../partials/rooter.php",
            method: "POST",
            data: {
                dateDebut: dateDebut,
                dateFin: dateFin,
                btn_filter_depense: 1
            },
            dataType: "JSON",
            success: function (data) {


                // $('#montant_depense_approuve').text("008888000");
                $('#montant_depense_approuve').text(data.depense_approuve.montant_depense_approuve);
                $('#nombre_depense_approuve').text(data.depense_approuve.nombre_depense_approuve);

                $('#montant_depense_en_attente').text(data.depense_en_attente.montant_depense_en_attente);
                $('#nombre_depense_en_attente').text(data.depense_en_attente.nombre_depense_en_attente);

                $('#montant_depense_annule').text(data.depense_annule.montant_depense_annule);
                $('#nombre_depense_annule').text(data.depense_annule.nombre_depense_annule);


            }
        });
    });

    $('#datefilterDepense').on('cancel.daterangepicker', function (ev, picker) {
        // $(this).val('');
    });

}
