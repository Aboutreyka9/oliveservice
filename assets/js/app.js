
const APP = {

    origin: window.location.origin,

    home: window.location.origin + "/oliveservice/",

    ajax: window.location.origin + "/oliveservice/app/Controllers/ajx.php",

    tables: {},

    formChanged: false,

    rolesPermissions: [],

    articleSelected: [],

    packSelected: [],

    montantPackSelected: 0,
    selectedInscriptionForEncaissement : null,

    dataCheck: [],

    userCode: null,

    date_start_picker:moment().startOf('month'), // 1er du mois

    date_end_picker:moment() // aujourd'hui

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



        testDatable('charger_data_packs', '#data-table-pack', search)

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

activeMenuLink();





function menuNav() {

    const pages = window.location.pathname.split("/");

    var currentPage = pages[2];

    if (currentPage) {

        $(".current-page").text(currentPage.toUpperCase());

    }



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

// loadDataTable('data-table-commercial', '#data-table-commercial', 'charger_data_commercials');





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

        });

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



openModalAddSession();

function openModalAddSession() {

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



ajouterSession();

function ajouterSession() {

    $("body").on("submit", "#frmAddSession", function (e) {

        e.preventDefault();

        var data = $(this).serialize();



        $.ajax({

            method: "POST",

            url: APP.ajax,

            data: data,

            dataType: "JSON",

            beforeSend: function () {

                // $(".loader_backdrop2").css('display', "block");



                btnReq("#btnSubmitFormSession", "Enregistrement...");

            },

            success: function (data) {

                console.log(data);

                btnRes("#btnSubmitFormSession", "Enregistrer", "fa-save");

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





function modalUpdatedSession(code) {

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



updatedSession();

function updatedSession() {

    $("body").on("submit", "#frmUpdateSession", function (e) {

        e.preventDefault();

        var data = $(this).serialize();





        $.ajax({

            method: "POST",

            url: APP.ajax,

            data: data,

            dataType: "JSON",

            beforeSend: function () {

                // $(".loader_backdrop2").css('display', "block");



                btnReq("#btnSubmitFormSession", "Mise à jour en cours...");

            },

            success: function (data) {

                // $(".loader_backdrop2").css('display', "none");

                console.log(data);



                btnRes("#btnSubmitFormSession", "Enregistrer", "fa-save");


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



function changeStatutSession(code, statut) {

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

/** FIN SECTION SESSION */





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

            action: 'btn_showmodal_zone_update',

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

            action: 'btn_showmodal_categoriePack_update',

            codecategoriepack: code

        },

        dataType: 'JSON',

        beforeSend: function () {

            $(".loader_backdrop2").css('display', "block");

            // btnReq(".modal_footer", "Traitement...");

        },

        success: function (data) {



            $(".loader_backdrop2").css('display', "none");


            console.log(data);
            // return;
            
            if (data.success) {

                $(".data-categorie-pack-modal").html(data.data);

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



function changeStatutCategoriePack(code, statut) {

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

            action: 'btn_showmodal_article_update',

            codearticle: code

        },

        dataType: 'JSON',

        beforeSend: function () {

            $(".loader_backdrop2").css('display', "block");


            // btnReq(".modal_footer", "Traitement...");

        },

        success: function (data) {



            $(".loader_backdrop2").css('display', "none");

// console.log(data); return;


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

                        action: 'change_statut_articles',

                        code_article: code,

                        statut_article: statut

                    },

                    dataType: 'JSON',

                    beforeSend: function () {

                        $(".loader_backdrop2").css('display', "block");

                    },

                    success: function (data) {

                        $(".loader_backdrop2").css('display', "none");

                        console.log(data);
                        

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



    if (APP.articleSelected.includes(article[0])) return $.notify('Désolé,cet article existe déjà dans la liste','warn');



    // let index = $('.table_commande tbody tr').length + 1;

    APP.articleSelected.push(article[0]);

    index = APP.articleSelected.length;

    

    html = dataRow(article);



    $('.table_add_pack tbody').append(html);

    $('#countArticle').text(index);

    $.notify('Article : '+article[1]+' ajouté dans la liste de selectionne.','success');

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



function pushDataPack(selector) {

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

    function calculerMontantTotal() {


    const montant = parseFloat($("#montant_pack").val()) || 0;
    const nombreJour = parseInt($("#nombre_jour").val()) || 0;

    const total = montant * nombreJour;

    $("#montant_total").val(
        total.toLocaleString("fr-FR")+' FCFA'
    );
}

    $(document).on("input", "#montant_pack", function () {
        calculerMontantTotal();
    });

    getNombreJourPack();

    function getNombreJourPack() {
        $(document).on("change", "#libelle_session_pack", function () {
            var sessionCode = $(this).val();
            if(sessionCode){

                $.ajax({

                method: "POST",

                url: APP.ajax,

                data: {

                    action: 'get_nombre_jour_session_pack',
                    session_code: sessionCode

                },

                dataType: "JSON",

                beforeSend: function () {},

                success: function (data) {

                    console.log(data.jour);

                    if (data.success) {
                        $('#nombre_jour').val(data.jour);
                        calculerMontantTotal();

                    }

                }

            });
            }
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

// return;

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

        });

    });

}





ajouterPack();

function ajouterPack() {

    $("body").on("submit", "#frmAddPack", function (e) {

        e.preventDefault();

        var packArticles = pushDataPack('qte');



        var data = $(this).serializeArray();

        data.push({

            name: 'articles',

            value: JSON.stringify(packArticles)

        });



        $.ajax({

            method: "POST",

            url: APP.ajax,

            data: data,

            dataType: "JSON",

            beforeSend: function () {

                // $(".loader_backdrop2").css('display', "block");



                btnReq("#btnSubmitFormPack", "Enregistrement...");

            },

            success: function (data) {

                console.log(data);

                btnRes("#btnSubmitFormPack", "Enregistrer", "fa-save");

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

            action: 'btn_showmodal_pack_update',

            codepack: code

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

                APP.articleSelected = data.articleCodes;

                var index = APP.articleSelected.length;

                $('#countArticle').text(index);



            } else {

                $.notify(data.message);



            }

        }

    });

}



updatedPack();

function updatedPack() {

    $("body").on("submit", "#frmUpdatePack", function (e) {

        e.preventDefault();

        var packArticles = pushDataPack('qte');



        var data = $(this).serializeArray();

        data.push({

            name: 'articles',

            value: JSON.stringify(packArticles)

        });



        $.ajax({

            method: "POST",

            url: APP.ajax,

            data: data,

            dataType: "JSON",

            beforeSend: function () {

                // $(".loader_backdrop2").css('display', "block");



                btnReq("#btnSubmitFormPack", "Mise à jour en cours...");

            },

            success: function (data) {

                // $(".loader_backdrop2").css('display', "none");

                btnRes("#btnSubmitFormPack", "Enregistrer", "fa-save");

                console.log(data);



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

                        action: 'change_statut_packs',

                        code_pack: code,

                        statut_pack: statut

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







/** DEBUT SECTION CLIENT */



loadDataTable('data-table-client', '#data-table-client', 'charger_data_clients');



function modalUpdatedClient(code) {

    // let btn = btn_action.id;



    $.ajax({

        method: "POST",

        url: APP.ajax,

        data: {

            action: 'btn_showmodal_client_update',

            codeclient: code

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

                $(".data-client-modal").html(data.data);

                $("#client-modal").modal("show");



            } else {

                $.notify(data.message);



            }

        }

    });

}



updatedClient();

function updatedClient() {

    $("body").on("submit", "#frmUpdateClient", function (e) {

        e.preventDefault();

        var data = $(this).serialize();





        $.ajax({

            method: "POST",

            url: APP.ajax,

            data: data,

            // dataType: "JSON",

            beforeSend: function () {

                // $(".loader_backdrop2").css('display', "block");



                btnReq("#btnSubmitFormClient", "Mise à jour en cours...");

            },

            success: function (data) {

                // $(".loader_backdrop2").css('display', "none");

                console.log(data);



                btnRes("#btnSubmitFormClient", "Enregistrer", "fa-save");

                return

                if (data.success) {

                    APP.tables['data-table-client'].ajax.reload(null, false);

                    $.notify(data.message, "success");

                    $("#client-modal").modal("hide");



                } else {

                    $.notify(data.message);

                }

            }

        })

    });

}



/** FIN SECTION CLIENT */







/** DEBUT SECTION INSCRIPTION */

// liste souscription
    chargerListeSouscriptionComercial() ;

 function chargerListeSouscriptionComercial() {
        $(document).on("change", "#session_filter_commercial", function () {
            var sessionCode = $(this).val();

                $.ajax({

                method: "POST",

                url: APP.ajax,

                data: {

                    action: 'charger_souscription_commercial_for_session',
                    session_code: sessionCode

                },

                dataType: "JSON",

                beforeSend: function () {},

                success: function (data) {

                    console.log(data);

                    if (data.success) {
                        $('#tbody-souscription-commercial').html(data.data);
                        $('#sexion_stats_soucription_commercial').html(data.stats);

                    }

                }

            });
        });
    }


// loadDataTable('data-table-souscription', '#data-table-souscription-commercial', 'charger_data_souscriptions');



// Conditionnellement initialiser les étapes : souscription OU réinscription

if (!$('#frmAddResouscription').length) {

    allStepInscription()

}



function allStepInscription () {



        let currentStep = 1;

        const totalSteps = 3;



        $('.btn-next').click(function(e) {

            e.preventDefault();

            e.stopPropagation();

            if (validateStep(currentStep)) {

                goToStep(currentStep + 1);

            }

        });



        $('.btn-prev').click(function(e) {

            e.preventDefault();

            e.stopPropagation();

            goToStep(currentStep - 1);

        });



        function goToStep(step) {

            if (step < 1 || step > totalSteps) return;



            $('#step' + currentStep).addClass('d-none');

            $('#step' + step).removeClass('d-none');

            

            currentStep = step;

            updateTimeline();

            updateRecap();

            

            $('html, body').animate({

                scrollTop: $('.timeline-steps').offset().top - 20

            }, 300);

        }



        function updateTimeline() {

            for (let i = 1; i <= totalSteps; i++) {

                const indicator = $('#step' + i + '-indicator');

                indicator.removeClass('active completed');

                

                if (i < currentStep) {

                    indicator.addClass('completed');

                } else if (i === currentStep) {

                    indicator.addClass('active');

                }

            }

        }



        function validateStep(step) {

            if (step === 1) {

                let valid = true;

                const requiredFields = ['nom_client', 'telephone_client', 'lieu_client', 'code_client'];

                

                requiredFields.forEach(function(field) {

                    const value = $('#' + field).val().trim();

                    if (!value) {

                        valid = false;

                        $('#' + field).addClass('is-invalid');

                    } else {

                        $('#' + field).removeClass('is-invalid');

                    }

                });



                const genreVal = $('#genre_client').val();

                if (!genreVal) {

                    valid = false;

                    $('#genre_client').addClass('is-invalid');

                } else {

                    $('#genre_client').removeClass('is-invalid');

                }

                

                if (!valid) {

                    $.notify('Veuillez remplir tous les champs obligatoires', 'error');

                    $('html, body').animate({

                        scrollTop: $('.is-invalid').first().offset().top - 100

                    }, 300);

                }

                

                return valid;

            }

            

            if (step === 2) {

                const checkedPacks = $('.pack-check:checked').length;

                if (checkedPacks === 0) {

                    $.notify('Veuillez sélectionner au moins un pack', 'error');

                    return false;

                }

                return true;

            }

            

            return true;

        }



        function updateRecap() {

            if (currentStep === 3) {

                $('#recap-nom').text($('#nom_client').val());

                $('#recap-contact').text($('#telephone_client').val());

                $('#recap-genre').text($('#genre_client').find('option:selected').text());

                $('#recap-lieu').text($('#lieu_client').val());

                $('#recap-code').text($('#code_client').val());

                $('#recap-email').text($('#email_client').val() || 'Non renseigné');

                $('#recap-profession').text($('#profession_client').val() || 'Non renseigné');



                   tableRecapData();

    

            }

        }



        function tableRecapData(){

             const tbody = $('#recap-packs');



                    tbody.empty();



                    if (APP.packSelected.length === 0) {



                        tbody.append(`

                            <tr>

                                <td colspan="2" class="text-center text-muted">

                                    Aucun pack sélectionné

                                </td>

                            </tr>

                        `);



                        return;

                    }



                    let total = 0;

                    let index = 0;



                    APP.packSelected.forEach(function(pack) {

                        index ++;

                        const montant = Number(pack.montant) || 0;



                        total += montant;



                        tbody.append(`

                            <tr>

                                <td>${index}</td>

                                <td>${pack.libelle}</td>

                                <td>${montant.toLocaleString('fr-FR')} FCFA</td>

                                <td class="text-center">

                                    <button 

                                        type="button"

                                        class="btn btn-sm btn-danger btn-remove-pack"

                                        data-pack-code="${pack.packCode}"

                                        title="Supprimer">

                                        <i class="fa fa-trash"></i>

                                    </button>

                                </td>

                            </tr>

                        `);



                    });



                    tbody.append(`

                        <tr class="table-active">

                            <td colspan="2" class="font-weight-bold">Total</td>

                            <td colspan="2" class="font-weight-bold">

                                ${total.toLocaleString('fr-FR')} FCFA

                            </td>

                        </tr>

                    `);

        }



        $('body').on('click', '.btn-remove-pack', function(e) {



            e.preventDefault();



            if(APP.packSelected.length == 1) return  $.notify('Le nombre minimum de selection ne doit pas êtres inferieur à 1.', "warn");



             const row = $(this).closest('tr');

                const packCode = $(this).data('pack-code');



                row.fadeOut(250, function() {



                    removePack(packCode);



                });

            // const packCode = $(this).data('pack-code');



            // removePack(packCode);



        });



        function removePack(packCode) {



            APP.packSelected = APP.packSelected.filter(function(pack) {

                return String(pack.packCode) !== String(packCode);

            });



            tableRecapData();



            updatePackCardsUI();



        }



        //  $("body").on("click",".pack-card",function(e) {

            

        //     if (e.target.type !== 'checkbox') {

        //         const checkbox = $(this).find('.pack-check');

        //         checkbox.prop('checked', !checkbox.prop('checked'));



        //     }

        //     // $(this).toggleClass('selected', $(this).find('.pack-check').prop('checked'));

        // });



        // $("body").on("change",".pack-check",function() {

        //     $(this).closest('.pack-card').toggleClass('selected', $(this).prop('checked'));

        // });



        $('form[id="frmAddClient"]').submit(function(e) {

            e.preventDefault();

            e.stopPropagation();

            

            if (!validateStep(currentStep)) {

                return;

            }



            // const selectedPacks = [];

            // $('.pack-check:checked').each(function() {

            //     selectedPacks.push($(this).val());

            // });

            const packCodes = APP.packSelected.map(pack => pack.packCode);

            $('#selected_packs').val(JSON.stringify(packCodes));



            var form = $(this);

            var btn = $('#btnSubmitFormClient');

            var originalText = btn.html();

            

            // btn.html('<i class="fas fa-spinner fa-spin"></i> &nbsp; Enregistrement...').prop('disabled', true);

            

            $.ajax({

                url: APP.ajax,

                method: 'POST',

                data: form.serialize(),

                dataType: 'JSON',

                success: function(data) {

                    

                    if (data.success) {



                        swal({

                                title: "Notification",

                                text: data.message,

                                icon: "success"

                        }).

                        then((result)=>{

                            if(result)

                                history.go(0);

                        })

                        // setTimeout(function() {

                        //     window.location.href = 'urlllll';

                        // }, 1500);

                    } else {

                        $.notify(data.message, 'error');

                        btn.html(originalText).prop('disabled', false);

                    }

                },

                error: function() {

                    $.notify('Désolé, une erreur est survenue', 'error');

                    btn.html(originalText).prop('disabled', false);

                }

            });

        });



        $('.select2').select2({

            tags: "false",

            placeholder: "----CHOISIR----",

            allowClear: true,

            language: {

                noResults: function() {

                    return "Aucun résultat";

                }

            },

            createTag: function(params) {

                return null;

        }

    });

}



// Chargement des catégories par session

loadCategoriesBySession();

function loadCategoriesBySession() {

    $("body").on("change", "#session_inscription", function (e) {



        var sessionCode = $(this).val();

        if (!sessionCode) {

            // $('#btn_selection_choix').prop('disabled',true);

            // $('#categorie_inscription').append('<option value="">--- CHOISIR ---</option>');

            return;

        }



        APP.packSelected = [];

        chargerSessionData(sessionCode);

});

}





function chargerSessionData(sessionCode) {

    $.ajax({

        url: APP.ajax,

        method: 'POST',

        data: {

            action: 'get_categories_by_session',

            session_code: sessionCode

        },

        dataType: 'JSON',

        success: function (data) {

            console.log(data);

            // return;

            if (data.success) {

                // const categories = data.data.data;

                // $("#categorie_inscription").html('<option value="">--- CHOISIR ---</option>');

                $("#categorie_inscription").html(data.categories);

                $("#packs-container").html(data.packs);

                updatePackCardsUI();



            }

        }

    });

}



loadPacksByCategories();

function loadPacksByCategories() {

    $("body").on("change", "#categorie_inscription", function (e) {



        var categorieCode = $(this).val();

        var sessionCode = $("#session_inscription").val();

        if (!categorieCode) {

                // updatePackCardsUI();

                if (sessionCode) 

                chargerSessionData(sessionCode);



            // $('#btn_selection_choix').prop('disabled',true);

            // $('#categorie_inscription').append('<option value="">--- CHOISIR ---</option>');

            return;

        }



        $.ajax({

            url: APP.ajax,

            method: 'POST',

            data: {

                action: 'get_packs_by_categorie',

                categorie_code: categorieCode,

                session_code: sessionCode

            },

            dataType: 'JSON',

            success: function(data) {

                console.log(data);

                // return;

                

                if (data.success) {

                    // const categories = data.data.data;



                // $("#categorie_inscription").html('<option value="">--- CHOISIR ---</option>');

                $("#packs-container").html(data.packs);

                updatePackCardsUI();

                }

            }

            // error: function() {

            //     // $("#categorie_inscription").html(categories);

            // }

        });

    });

}



// ================= GESTION SELECTION PACKS =================

// Initialiser la sélection des packs

initPackSelection();



function togglePackSelection(packCode, montant, libelle, isChecked) {



    montant = parseInt(montant) || 0;



    if (isChecked) {



        // Vérifier si le pack n'est pas déjà sélectionné

        const exists = isPackSelected(packCode);

        // APP.packSelected.some(pack => pack.packCode === packCode);



        if (!exists) {



            APP.packSelected.push({

                packCode: packCode,

                libelle: libelle,

                montant: montant,

                isChecked: true

            });



            APP.montantPackSelected += montant;

        }



    } else {



        // Supprimer le pack

        APP.packSelected = APP.packSelected.filter(

            pack => pack.packCode !== packCode

        );



        APP.montantPackSelected -= montant;



        // Éviter d'avoir un montant négatif

        if (APP.montantPackSelected < 0) {

            APP.montantPackSelected = 0;

        }

    }



    updatePackCardsUI();



}



function ttogglePackSelectionTest(packCode, montant,libelle, isChecked) {

    if (isChecked) {

        if (!APP.packSelected['packCode'].includes(packCode)) {

            APP.packSelected.push({packCode: packCode, libelle: libelle, montant: montant,isChecked: isChecked});

        }

        APP.montantPackSelected += parseInt(montant);

    } else {

        APP.packSelected = APP.packSelected.filter(code => code !== packCode);

        APP.montantPackSelected -= parseInt(montant);

    }





    updatePackCardsUI();

}



function tupdatePackCardsUtITest() {



    $('.pack-card').each(function() {

        const packCode = $(this).data('pack-code');

        const checkbox = $(this).find('.pack-check');

        

        // console.log(APP.packSelected);

        

        if (APP.packSelected.includes(packCode)) {



            $(this).addClass('selected');

            checkbox.prop('checked', true);

        } else {

            $(this).removeClass('selected');

            checkbox.prop('checked', false);

        }

    });

}



function isPackSelected(packCode) {



    return APP.packSelected.some(

        pack => String(pack.packCode) === String(packCode)

    );



}



function updatePackCardsUI() {



    $('.pack-card').each(function() {



        const packCode = $(this).data('pack-code');

        const checkbox = $(this).find('.pack-check');



        const selected = isPackSelected(packCode);



        $(this).toggleClass('selected', selected);

        checkbox.prop('checked', selected);



    });



}



function initPackSelection() {

    if (!APP.packSelected) {

        APP.packSelected = [];

    }

    if (!APP.montantPackSelected) {

        APP.montantPackSelected = 0;

    }



    $("body").on("click", ".pack-card", function(e) {

        if (e.target.type !== 'checkbox') {

            const checkbox = $(this).find('.pack-check');

            const packCode = $(this).data('pack-code');

            const montant = $(this).data('pack-montant');

            const libelle = $(this).data('pack-libelle');

            

            checkbox.prop('checked', !checkbox.prop('checked'));

            togglePackSelection(packCode, montant,libelle, checkbox.prop('checked'));

        }

    });



    // $("body").on("change", ".pack-card", function() {

    //     const packCard = $(this).closest('.pack-card');

    //     const packCode = packCard.data('pack-code');

    //     const montant = packCard.data('pack-montant');

    //     const libelle = packCard.data('pack-libelle');

        

    //     togglePackSelection(packCode, montant, $(this).prop('checked'));

    // });

}





/** FIN SECTION INSCRIPTION */



/** DEBUT SECTION RESOUSCRIPTION */



if ($('#frmAddResouscription').length) {

    initResouscriptionSteps();

}



function initResouscriptionSteps() {

    $('.btn-next').click(function() {

        const nextStep = $(this).data('step');

        $('.step-content').addClass('d-none');

        $('#step' + nextStep).removeClass('d-none');

        $('.timeline-step').removeClass('active');

        $('#step' + nextStep + '-indicator').addClass('active');

    });



    $('.btn-prev').click(function() {

        const prevStep = $(this).data('step');

        $('.step-content').addClass('d-none');

        $('#step' + prevStep).removeClass('d-none');

        $('.timeline-step').removeClass('active');

        $('#step' + prevStep + '-indicator').addClass('active');

    });

}



// Recherche client pour réinscription

$('#btn_search_client').click(function() {

    const searchValue = $('#search_client').val().trim();

    if (!searchValue) {

        $.notify('Veuillez saisir un code client ou un numéro de téléphone', 'error');

        return;

    }



    $.ajax({

        url: APP.ajax,

        method: 'POST',

        data: {

            action: 'search_client',

            search_value: searchValue

        },

        dataType: 'JSON',

        success: function(data) {

            if (data.success) {

                const client = data.data.client;

                $('#found_nom').text(client.nom_client);

                $('#found_telephone').text(client.telephone_client);

                $('#found_lieu').text(client.lieu_residence_client);

                $('#client_code').val(client.code_client);

                $('#client_found').removeClass('d-none');

                $('#client_not_found').addClass('d-none');

                $('#btn_next_step2').prop('disabled', false);

            } else {

                $('#client_found').addClass('d-none');

                $('#client_not_found').removeClass('d-none');

                $('#btn_next_step2').prop('disabled', true);

            }

        }

    });

});



// Soumission formulaire réinscription

$('#frmAddResouscription').submit(function(e) {

    e.preventDefault();



    const selectedPacks = [];

    $('.pack-check:checked').each(function() {

        selectedPacks.push($(this).val());

    });



    if (selectedPacks.length === 0) {

        $.notify('Veuillez sélectionner au moins un pack', 'error');

        return;

    }



    $('#selected_packs').val(JSON.stringify(selectedPacks));



    const formData = $(this).serialize();



    $.ajax({

        url: APP.ajax,

        method: 'POST',

        data: formData + '&action=btn_add_resouscription',

        dataType: 'JSON',

        beforeSend: function() {

            $(".loader_backdrop2").css('display', "block");

        },

        success: function(data) {

            $(".loader_backdrop2").css('display', "none");

            if (data.success) {

                $.notify(data.message, "success");

                setTimeout(function() {

                    history.go(0);

                }, 1500);

            } else {

                $.notify(data.message, "error");

            }

        },

        error: function() {

            $(".loader_backdrop2").css('display', "none");

            $.notify("Une erreur est survenue lors de l'opération", "error");

        }

    });

});



// Recap réinscription

function updateRecapResouscription() {

    const nom = $('#found_nom').text();

    const telephone = $('#found_telephone').text();

    const lieu = $('#found_lieu').text();

    const code = $('#client_code').val();



    $('#recap-nom').text(nom);

    $('#recap-contact').text(telephone);

    $('#recap-lieu').text(lieu);

    $('#recap-code').text(code);



    const tbody = $('#recap-packs');

    tbody.empty();



    if (APP.packSelected.length === 0) {

        tbody.html('<tr><td colspan="4" class="text-center text-muted">Aucun pack sélectionné</td></tr>');

        return;

    }



    let total = 0;

    APP.packSelected.forEach(function(pack, index) {

        total += pack.montant;

        tbody.append(`

            <tr>

                <td>${index + 1}</td>

                <td>${pack.libelle}</td>

                <td>${pack.montant.toLocaleString()} FCFA</td>

                <td><button type="button" class="btn btn-sm btn-danger" onclick="removePackRecap('${pack.packCode}')">Supprimer</button></td>

            </tr>

        `);

    });

}



function removePackRecap(packCode) {

    APP.packSelected = APP.packSelected.filter(p => p.packCode !== packCode);

    updatePackCardsUI();

    updateRecapResouscription();

}



// Override next button for step 3 to update recap

$(document).ready(function() {

    const originalNext = $.fn.dataTable ? null : null;

    

    $('body').on('click', '#step2 .btn-next', function() {

        updateRecapResouscription();

    });

});



/** FIN SECTION RESOUSCRIPTION */

// ENCAISSER COMMERCIAL

encaisseCotisationClient();
function encaisseCotisationClient() { 

            // $('#div_nombre_jours').hide();

        $(document).on('click','#btn_search_client',function() {
            searchClient();
        });
        
        $(document).on('keypress','#search_client',function(e) {
            if (e.which == 13) {
                searchClient();
            }
        });
        
        $(document).on("input", ".montant_cautisation:visible", function () {

            const nombreJours = calculerNombreJours();

            if (nombreJours > 0) {
                calculerDateProchainPaiement();
            }

        });

        $(document).on("input", ".nombre_jours_cautisation:visible", function () {

            calculerMontantDepuisJours();
            calculerDateProchainPaiement();

        });

        // $(document).on('change','#periode_debut',function() {
        //     var debut = $(this).val();
        //     if (debut && $('#nombre_jours_cautisation').val()) {
        //         var jours = parseInt($('#nombre_jours_cautisation').val());
        //         var dateFin = new Date(debut);
        //         dateFin.setDate(dateFin.getDate() + jours - 1);
        //         $('#periode_fin').val(dateFin.toISOString().split('T')[0]);
        //     }
        // });
        
        // $(document).on('change','#periode_fin',function() {
        //     var fin = $(this).val();
        //     if (fin && $('#periode_debut').val()) {
        //         var debut = new Date($('#periode_debut').val());
        //         var dateFin = new Date(fin);
        //         var diffTime = Math.abs(dateFin - debut);
        //         var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        //         $('#nombre_jours_cautisation').val(diffDays);
        //         calculerMontant();
        //     }
        // });

        $(document).on('submit','#frmEncaissement',function(e) {
            e.preventDefault();
            var formData = $(this).serializeArray();
            formData.push(
                {name: 'action', value: 'btn_save_encaissement'},
                {name: 'nombre_jours_cautisation', value: $(".nombre_jours_cautisation:visible").val()},
                {name: 'montant_cautisation', value: $(".montant_cautisation:visible").val()}
            );

            $.ajax({

                method: "POST",
                url: APP.ajax,
                data: formData,
                // dataType: "JSON",
                beforeSend: function () {},
                success: function (data) {
                    console.log(data);
                    return
                    
                if (data.success) {
                    alert(data.message);
                    $('#encaissement-modal').modal('hide');
                    location.reload();
                } else {
                    alert(data.message);
                }
                }
            });
            
        
        });

    }

    function toggleModeCalcul() {
        var mode = $('#mode_calcul').val();
        if (mode === 'jours') {
            $('#div_nombre_jours').toggleClass('d-none');
            $('#div_montant').toggleClass('d-none');
        } else {
            $('#div_nombre_jours').toggleClass('d-none');
            $('#div_montant').toggleClass('d-none');
        }
    }

    function calculerMontanttest() {
        var jours = parseInt($('#nombre_jours_cautisation').val()) || 0;
        var montant = jours * APP.selectedInscriptionForEncaissement.montant_journalier;
        $('#montant_cautisation').val(montant);
        $('#jours_calcules').text(jours);
        html += '<strong>Total payé:</strong> ' + totalPaye.toLocaleString('fr-FR') + ' FCFA<br>';
        $('#montant_calcule').text(montant.toLocaleString('fr-FR') + ' FCFA');
        
        if (jours > 0 && APP.selectedInscriptionForEncaissement.montant_journalier > 0) {
            var debut = $('#periode_debut').val();
            if (debut) {
                var dateFin = new Date(debut);
                dateFin.setDate(dateFin.getDate() + jours - 1);
                $('#periode_fin').val(dateFin.toISOString().split('T')[0]);
            }
        }
    }

    function calculerJourstest() {
        var html = '';
        var montant = parseFloat($('#montant_cautisation').val()) || 0;
        var jours = APP.selectedInscriptionForEncaissement.montant_journalier > 0 ? Math.ceil(montant / APP.selectedInscriptionForEncaissement.montant_journalier) : 0;
        $('#nombre_jours_cautisation').val(jours);
        $('#jours_calcules').text(jours);
        html += '<strong>Total payé:</strong> ' + totalPaye.toLocaleString('fr-FR') + ' FCFA<br>';
        $('#montant_calcule').text(montant.toLocaleString('fr-FR') + ' FCFA');
        
        if (jours > 0) {
            var debut = $('#periode_debut').val();
            if (debut) {
                var dateFin = new Date(debut);
                dateFin.setDate(dateFin.getDate() + jours - 1);
                $('#periode_fin').val(dateFin.toISOString().split('T')[0]);
            }
        }
    }

    function loadInscriptions(clientCode) {

        $.ajax({

            method: "POST",
            url: APP.ajax,
            data: {
                action: 'get_souscriptions_client',
                client_code: clientCode
            },
            dataType: "JSON",
            beforeSend: function () {},
            success: function (data) {
                console.log(data);
                
            if (data.success && data.data.souscriptions.length > 0) {
            var html = '<div class="card mt-3"><div class="card-header"><strong>Souscriptions actives</strong></div><div class="card-body">';
                html += '<div class="table-responsive"><table class="table table-hover"><thead><tr><th>Session</th><th>Année</th><th>Zone</th><th>Montant pack</th><th>Total payé</th><th>Reste dû</th><th>Montant journalier</th><th>Action</th></tr></thead><tbody>';
                
                data.data.souscriptions.forEach(function(ins) {
                    var montantPack = parseFloat(ins.montant_pack) || 0;
                    var totalPaye = parseFloat(ins.total_paye_valide) || 0;
                    var reste = Math.max(0, montantPack - totalPaye);
                    var montantJournalier = ins.duree_jours_pack > 0 ? Math.ceil(montantPack / ins.duree_jours_pack) : 0;
                    var joursRestants = montantJournalier > 0 ? Math.ceil(reste / montantJournalier) : 0;
                    
                    html += '<tr>';
                    html += '<td>' + ins.libelle_session + '</td>';
                    html += '<td>' + ins.libelle_annee + '</td>';
                    html += '<td>' + ins.libelle_zone + '</td>';
                    html += '<td>' + montantPack.toLocaleString('fr-FR') + ' FCFA</td>';
                    html += '<td>' + totalPaye.toLocaleString('fr-FR') + ' FCFA</td>';
                    html += '<td class="text-danger">' + reste.toLocaleString('fr-FR') + ' FCFA</td>';
                    html += '<td>' + montantJournalier.toLocaleString('fr-FR') + ' FCFA/jour</td>';
                    html += '<td><button class="btn btn-primary btn-sm" onclick="openEncaissementModal(\'' + ins.code_inscription + '\', \'' + ins.nom_client + '\', ' + montantPack + ', ' + totalPaye + ', ' + reste + ', ' + montantJournalier + ', ' + (ins.duree_jours_pack || 0) + ')"><i class="fas fa-money-bill-wave"></i> Encaisser</button></td>';
                    html += '</tr>';
                });
                
                html += '</tbody></table></div></div></div>';
                $('#search_results').append(html);
            }else {
                $('#search_results').append('<div class="alert alert-warning mt-3">Aucune souscription active pour ce client</div>');
            }
            },
            error: function (data) {}

        });

    }

    function searchClient() {
        var search = $('#search_client').val();
        if (search.length < 2) {
            $('#search_results').html('<div class="alert alert-warning">Veuillez saisir au moins 2 caractères</div>');
            return;
        }

        $.ajax({

            method: "POST",
            url: APP.ajax,
            data: {
                action: 'search_client_cautisation',
                search: search
            },
            dataType: "JSON",
            beforeSend: function () {},
            success: function (data) {
                // console.log(data);
                // return;
                

                if (data.success && data.data.clients.length > 0) {
                            var html = '<div class="list-group">';

                    data.data.clients.forEach(function(client) {
                        html += '<div class="list-group-item list-group-item-action" data-code="' + client.code_client + '" data-nom="' + client.nom_client + '" data-telephone="' + client.telephone_client + '">';
                        html += '<strong>' + client.nom_client + '</strong> - ' + client.telephone_client;
                        html += '<br><small class="text-muted">Code: ' + client.code_client + ' | ' + client.sexe_client + ' | ' + (client.lieu_residence_client || '-') + '</small>';
                        html += '</div>';
                    });

                    html += '</div>';

                    $('#search_results').html(html);

                    $('.list-group-item').click(function() {
                        var code = $(this).data('code');
                        var nom = $(this).data('nom');
                        $('#selected_client').val(code);
                        $('#selected_client_nom').val(nom);
                        $('#search_results').html('<div class="alert alert-success"><i class="fas fa-check"></i> Client sélectionné: <strong>' + nom + '</strong> (' + code + ')</div>');
                        loadInscriptions(code);
                    });

                    // $.notify(data.message, "success");

                    // $("#fonction-modal").modal("hide");

                } else {
                    $('#search_results').html('<div class="alert alert-info">Aucun client trouvé</div>');

                    $.notify(data.message);

                }

            },error:function(){}

            });
    }

    function openEncaissementModal(codeIns, nomClient, montantPack, totalPaye, reste, montantJournalier, dureeJours) {
        APP.selectedInscriptionForEncaissement = {
            code: codeIns,
            nom_client: nomClient,
            montant_pack: montantPack,
            total_paye: totalPaye,
            reste: reste,
            montant_journalier: montantJournalier,
            duree_jours: dureeJours
        };

        var html = `
        <form id="frmEncaissement">
        <input type="hidden" name="inscription_code" value="${codeIns}">
        <div class="row mb-3">
        <div class="col-md-12"><strong>Client: ${nomClient}</strong></div>
        <div class="col-md-12 mt-2"><div class="alert alert-info">
        <strong>Montant Total session:</strong> 10${montantPack.toLocaleString('fr-FR')} FCFA<br>
        <strong>Total payé:</strong> ${totalPaye.toLocaleString('fr-FR')} FCFA<br>
        <strong>Reste dû:</strong> <span class="text-danger"> 10${reste.toLocaleString('fr-FR')} FCFA</span><br>
        <strong>Cautisation :</strong> 500${montantJournalier.toLocaleString('fr-FR')} FCFA<br>
        <strong>Jours restants estimés:</strong> 0/178 jours
        // <strong>Jours restants estimés:</strong> ' + (montantJournalier > 0 ? Math.ceil(reste / montantJournalier) : 0) + ' jours
        </div></div>
        </div>
        
        <div class="row">
            <div class="col-md-12 mb-3">
            <label for="periode_fin" class="form-label">Montant cautisation</label>
            <input type="number" readonly class="form-control" value="500" id="" name="">
            </div>
        
        <div class="col-md-6 ">
        <label class="form-label">Mode de calcul <strong class="text-danger">*</strong></label>
        <select class="form-control" id="mode_calcul" name="mode_calcul" onchange="toggleModeCalcul()" required>
        <option value="montant">Par montant</option>
        <option value="jours">Par nombre de jours</option>
        </select>
        </div>
         <div class="col-md-6 mb-3">
        <label for="mode_paiement" class="form-label">Mode paiement  <strong class="text-danger">*</strong></label>
         <select class="form-control" id="mode_paiement" name="mode_paiement"  required>
        <option value="En especes">EN ESPECES</option>
        <option value="Mobile money">MOBILE MONEY</option>
        </select>
        </div>
        </div>
        
        <div class="row mb-3 d-none" id="div_nombre_jours">
        <div class="col-md-12">
        <label for="nombre_jours_cautisation" class="form-label">Nombre de jours <strong class="text-danger">*</strong></label>
        <input type="number" class="form-control nombre_jours_cautisation id="nombre_jours_cautisation" name="nombre_jours_cautisation" min="1" max="' + (dureeJours || 178) + '" onchange="calculerMontant()">
        <small class="text-muted text-danger">Max: ' + (dureeJours || 365) + ' jours</small>
        </div>

          <div class="col-md-6">
        <label for="montant" class="form-label">Montant à encaissé (FCFA)</label>
        <input readonly type="number" class="form-control montant_cautisation" id="" name="">
        </div>

         <div class="col-md-6">
        <label for="periode_debut" class="form-label">Date RDV</label>
        <input readonly type="date" class="form-control date_rdv" id="date_rdv" name="date_rdv">
        </div>
        </div>
        
        <div class="row mb-3" id="div_montant">
        <div class="col-md-12">
        <label for="montant_cautisation" class="form-label">Montant à encaissé (FCFA) <strong class="text-danger">*</strong></label>
        <input type="number" class="form-control montant_cautisation" id="montant_cautisation" name="montant_cautisation" min="1" max="' + reste + '" onchange="calculerJours()">
        <small class="text-muted text-danger">Max: 10${reste.toLocaleString('fr-FR')} FCFA</small>
        </div>

         <div class="col-md-6">
        <label for="nombre_jours" class="form-label">Nombre jours</label>
        <input readonly type="number" class="form-control nombre_jours_cautisation" id="nombre_jours" name="nombre_jours">
        </div>

         <div class="col-md-6">
        <label for="date_rdv" class="form-label">Date RDV</label>
        <input readonly type="date" class="form-control date_rdv" id="date_rdv" name="date_rdv">
        </div>

        </div>
        
       
        <div class="row mt-4">
        <div class="col-md-12 alert alert-warning" id="info_calcul">
        <strong>Calcul:</strong> ${montantJournalier.toLocaleString('fr-FR')} FCFA/jour * <span id="jours_calcules">0</span> jours = <strong id="montant_calcule">0</strong> FCFA
        </div>
        </div>
        
        <div class="row mb-3">
        <div class="col-md-12 modal_footer">
        <input type="hidden" name="action" value="btn_save_encaissement">
        <input type="hidden" name="csrf_token" value="">
        <button type="submit" class="btn btn-primary" id="btnSubmitFormEncaissement">
        <i class="fas fa-save"></i> &nbsp; Enregistrer
        </button>
        <button type="button" class="btn btn-light dismiss_modal">Fermer</button>
        </div>
        </div>
        </form>`;
        
        $('.data-encaissement-modal').html(html);
        $('#encaissement-modal').modal('show');
    }

    function calculerNombreJours() {

        // APP.selectedInscriptionForEncaissement = {
        //     code: codeIns,
        //     nom_client: nomClient,
        //     montant_pack: montantPack,
        //     total_paye: totalPaye,
        //     reste: reste,
        //     montant_journalier: montantJournalier,
        //     duree_jours: dureeJours
        // };

    const montantJournalier = Number(APP.selectedInscriptionForEncaissement.montant_pack) || 0;
    const montantPaye = Number($(".montant_cautisation:visible").val()) || 0;

    $(".nombre_jours_cautisation:visible").val("");
    $(".date_rdv:visible").val("");
    $(".montant-error:visible").text("");

    if (montantJournalier <= 0 || montantPaye <= 0) {
        return 0;
    }

    if (montantPaye % montantJournalier !== 0) {

        $(".montant_cautisation:visible").addClass("is-invalid");

        $(".montant-error:visible").text(
            `Le montant doit être un multiple de ${montantJournalier.toLocaleString("fr-FR")} FCFA.`
        );

        return 0;
    }

    $(".montant_cautisation:visible").removeClass("is-invalid");

    const nombreJours = montantPaye / montantJournalier;

    $(".nombre_jours_cautisation:visible").val(nombreJours);

    return nombreJours;
    }

    function calculerDateProchainPaiement() {

    const nombreJours = Number($(".nombre_jours_cautisation:visible").val()) || 0;

    if (nombreJours <= 0) {
        $(".date_rdv:visible").val("");
        return "";
    }

    const dateProchainPaiement = moment()
        .add(nombreJours, "days")
        .format("YYYY-MM-DD");

    $(".date_rdv:visible").val(dateProchainPaiement);

    return dateProchainPaiement;
    }

    function calculerDepuisNombreJoursTest() {

    const montantJournalier = Number(APP.selectedInscriptionForEncaissement.montant_pack) || 0;
    const nombreJours = Number($("#nombre_jours").val()) || 0;

    if (montantJournalier <= 0 || nombreJours <= 0) {
        $(".montant_cautisation:visible").val("");
        $(".date_rdv:visible").val("");
        return;
    }

    // Montant à encaisser
    const montant = montantJournalier * nombreJours;

    $(".montant_cautisation:visible").val(montant);

    // Date du prochain RDV
    const dateRdv = moment()
        .add(nombreJours, "days")
        .format("YYYY-MM-DD");

    $(".date_rdv:visible").val(dateRdv);
}

function calculerMontantDepuisJours() {

    const montantJournalier = Number(APP.selectedInscriptionForEncaissement.montant_pack) || 0;
    const nombreJours = Number($(".nombre_jours_cautisation:visible").val()) || 0;

    if (montantJournalier <= 0 || nombreJours <= 0) {
        $(".montant_cautisation:visible").val("");
        return 0;
    }

    const montant = montantJournalier * nombreJours;

    $(".montant_cautisation:visible").val(montant);

    return montant;
}


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






$(function() {
    const $selects = $('.chk-select-all');
    const $checkboxes = $('.chk-caution');
    const $montantDispo = $('#enc_montant_disponible');
    let montantTotalDispo = 0;

    // Calcul du montant disponible
    function updateMontantDisponible() {
        montantTotalDispo = 0;
        $('.chk-caution:checked').each(function() {
            montantTotalDispo += parseInt($(this).data('restant')) || 0;
        });
        $montantDispo.text(addSeparator(montantTotalDispo) + ' FCFA');
        $('#enc_montant').attr('max', montantTotalDispo);
        const codes = [];
        $('.chk-caution:checked').each(function() {
            codes.push($(this).data('code'));
        });
        $('#enc_selected_cautions').val(JSON.stringify(codes));
    }

    function addSeparator(nbr) {
        nbr += '';
        let sep = '';
        let partie1 = '';
        let partie2 = '';
        if (nbr.length > 3) {
            partie1 = nbr.slice(0, (nbr.length % 3));
            if (partie1.length === 0 && nbr.length > 0) {
                partie1 = nbr.slice(0, 3);
            }
            for (let i = 0; i < Math.floor(nbr.length / 3); i++) {
                if (i === 0 && nbr.length % 3 === 0) {
                    sep = '';
                } else {
                    sep += ' ';
                }
                partie2 = sep + nbr.slice(partie1.length + (i * 3) - (nbr.length % 3 || 3), partie1.length + (i + 1) * 3 - (nbr.length % 3 || 3));
            }
        }
        return (partie1 + (partie2 || '')).trim();
    }

    $selects.on('change', function() {
        const check = this.checked;
        $checkboxes.prop('checked', check);
        $('.btn-encaisser-row').prop('disabled', check);
        updateMontantDisponible();
    });

    $checkboxes.on('change', function() {
        $selects.prop('checked', $checkboxes.length === $('.chk-caution:checked').length);
        updateMontantDisponible();
    });

    // Ouvrir le modal en sélectionnant une ligne directement
    $('.btn-encaisser-row').on('click', function() {
        const inscription = $(this).data('inscription');
        const restant = parseInt($(this).data('restant')) || 0;
        const code = $(this).data('code');

        $checkboxes.prop('checked', false);
        $selects.prop('checked', false);

        const $cb = $('.chk-caution[data-code="' + code + '"]');
        $cb.prop('checked', true);
        $('.btn-encaisser-row').prop('disabled', false);

        const items = '<li class="list-group-item d-flex justify-content-between align-items-center">' +
            '<span>' + inscription + '</span>' +
            '<span class="badge badge-info">' + addSeparator(restant) + ' FCFA</span>' +
            '</li>';
        $('#enc_caution_items').html(items);

        $checkboxes.not($cb).prop('disabled', true);
        updateMontantDisponible();
        $('#enc_montant').val(restant);

        $('#encaissement-modal').modal('show');
    });

    // Ouvrir le modal en sélectionnant les cautions cochées
    $('#btn_encaisser_select').on('click', function() {
        const checked = $('.chk-caution:checked');
        if (checked.length === 0) {
            swal("Attention", "Veuillez sélectionner au moins une caution à encaisser.", "warning");
            return false;
        }

        let items = '';
        let total = 0;
        checked.each(function() {
            const inscription = $(this).data('inscription');
            const restant = parseInt($(this).data('restant')) || 0;
            total += restant;
            items += '<li class="list-group-item d-flex justify-content-between align-items-center">' +
                '<span>' + inscription + '</span>' +
                '<span class="badge badge-info">' + addSeparator(restant) + ' FCFA</span>' +
                '</li>';
        });
        $('#enc_caution_items').html(items);

        updateMontantDisponible();
        $('#enc_montant').val(total);
        $checkboxes.prop('disabled', true);

        $('#encaissement-modal').modal('show');
    });

    // Reset du modal à la fermeture
    $('#encaissement-modal').on('hidden.bs.modal', function() {
        $checkboxes.prop('disabled', false);
        $checkboxes.prop('checked', false);
        $selects.prop('checked', false);
        montantTotalDispo = 0;
        $montantDispo.text('0 FCFA');
        $('#enc_caution_items').html('');
        $('#enc_selected_cautions').val('');
        $('#enc_montant').val('');
        $('#enc_moyen').val('').trigger('change');
        $('#enc_reference').val('');
    });

    // Soumission du formulaire (demo)
    $('#form-encaissement').on('submit', function(e) {
        e.preventDefault();
        const montant = parseInt($('#enc_montant').val()) || 0;
        if (montant <= 0) {
            swal("Erreur", "Veuillez saisir un montant valide.", "error");
            return false;
        }
        const moyen = $('#enc_moyen').val();
        if (!moyen) {
            swal("Erreur", "Veuillez sélectionner un moyen de paiement.", "error");
            return false;
        }

        swal({
            title: "Confirmation",
            text: "Encaisser " + addSeparator(montant) + " FCFA ?",
            icon: "info",
            buttons: ["Annuler", "Confirmer"],
            dangerMode: false
        }).then((confirm) => {
            if (confirm) {
                $.post($(this).attr('action'), $(this).serialize(), function(resp) {
                    if (resp && resp.success) {
                        swal("Succès", resp.message || "Encaissement effectué.", "success").then(() => {
                            $('#encaissement-modal').modal('hide');
                            location.reload();
                        });
                    } else {
                        swal("Erreur", (resp && resp.message) || "Une erreur est survenue.", "error");
                    }
                }, 'json');
            }
        });
    });
});







