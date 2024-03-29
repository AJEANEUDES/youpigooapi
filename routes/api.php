<?php

use App\Http\Controllers\Admin\CategoriechambreController;
use App\Http\Controllers\Admin\ChambreController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\CompagnieController;
use App\Http\Controllers\Admin\FactureController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Admin\HotelController as AdminHotelController;
use App\Http\Controllers\Admin\CompagnieController as AdminCompagnieController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\PaysController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TypehebergementController;
use App\Http\Controllers\Admin\VilleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Client\FactureController as ClientFactureController;
use App\Http\Controllers\Client\ReservationController as ClientReservationController;
use App\Http\Controllers\CompagnieController as ControllersCompagnieController;
use App\Http\Controllers\Hotel\CategoriechambreController as HotelCategoriechambreController;
use App\Http\Controllers\Hotel\ChambreController as HotelChambreController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PrivateController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


//ROUTE AUTH
Auth::routes(['register' => true, 'verify' => false, 'reset' => false]);

// Auth::routes();

// Route::get('/', function () {
//     return view('welcome');
// });

// require __DIR__ . '/auth.php';



//Route publiques





//ROUTE SITEWEB PAGE


//      Route::get('admin/permissions', [PermissionController::class, 'index'])->name('inscriptions.clients');


Route::group(
    [
        "middleware" => "api",
        "namespace" => "App\Http\Controllers",
        "prefix" => "auth",
    ],

    function ($router) {


        Route::post('/inscription', [UserController::class, 'inscription']);
        Route::post('/connexion', [UserController::class, 'connexion']);

        //Reset Password

        Route::post('/reinitialiser-mot-de-passe', [PasswordResetController::class, 'resetPassword'])->name('reinitialiser.reset-code');
        Route::post('/reinitialiser-mot-de-passe/{reset_code}', [PasswordResetController::class, 'getPasswordReset'])->name('reinitialiser.password-reset');
        Route::get('/confirmation-email/verificationcode/{verification_code}', [UserController::class, 'verificationUser']);

        // Route::post('/reinitialiser-mot-de-passe/{reset_code}', [PasswordResetController::class, 'reset']);
        // Route::get('/reinitialiser-mon-de-passe', [PasswordResetController::class, 'viewResetPassword'])->name('reinitialiser.mot-de-passe.get');
        // Route::get('/reinitialiser-mot-de-passe/{reset_code}', [PasswordResetController::class, 'resetNewPassword'])->name('reinitialiser.reset-code');


        //Route protégées

        Route::post('/deconnexion', [UserController::class, 'logout']);
        Route::get('/profil', [UserController::class, 'profile']);
        Route::post('/rafraichir', [UserController::class, 'refresh']);
        Route::post('/changer-mot-de-passe', [UserController::class, 'updateMotdepasse']);
        Route::post('/mon-compte', [UserController::class, 'updateCompte']);




        Route::get('/', [UserController::class, 'accueil'])->name('accueil.get');
        // Route::get('/reserver-chambre', [UserController::class, 'reserverChambre'])->name('reserver.chambre.get');
        // Route::get('/paginate-chambre', [UserController::class, 'fetchDataChambre'])->name('fetch.chambre');
        // Route::get('/paginate-hotel', [UserController::class, 'fetchDataHotel'])->name('fetch.hotel');
        // Route::get('/paginate-categorie_chambre', [UserController::class, 'fetchDataCatChambre'])->name('fetch.catchambre');
        // Route::get('/paginate-type_hebergement', [UserController::class, '@fetchDataTypeheb'])->name('fetch.typeheb');

        // Route::get('/details-data-chambre', [UserController::class, 'detailsChambre']);
        // Route::get('/details-chambre/{slug_categoriechambre}/{slug_hotel}/{id_chambre}', [UserController::class, 'viewDetailsChambre']);



    }
);












// ////////////////////////////////////////////////ROUTE LOGIN ADMIN/////////////////////////////////////////////
// ////////////////////////////////////////////////ROUTE LOGIN ADMIN/////////////////////////////////////////////
// ////////////////////////////////////////////////ROUTE LOGIN ADMIN/////////////////////////////////////////////
// ////////////////////////////////////////////////ROUTE LOGIN ADMIN/////////////////////////////////////////////
// ////////////////////////////////////////////////ROUTE LOGIN ADMIN/////////////////////////////////////////////
// ////////////////////////////////////////////////ROUTE LOGIN ADMIN/////////////////////////////////////////////
// ////////////////////////////////////////////////ROUTE LOGIN ADMIN/////////////////////////////////////////////
// ////////////////////////////////////////////////ROUTE LOGIN ADMIN/////////////////////////////////////////////
// ////////////////////////////////////////////////ROUTE LOGIN ADMIN/////////////////////////////////////////////
// ////////////////////////////////////////////////ROUTE LOGIN ADMIN/////////////////////////////////////////////
// ////////////////////////////////////////////////ROUTE LOGIN ADMIN/////////////////////////////////////////////






// Route::controller(AdminController::class)

Route::group(
    [
        'prefix' => 'auth/Admin',
        'middleware' => 'jwt',
        "namespace" => "App\Http\Controllers",

    ],
    function ($router) {
        Route::get('/tableau-de-bord', [AdminController::class, 'tableauDeBord'])->name('admin.tableaudebord');
        Route::get('/monCompte', [AdminController::class, 'profileAdmin'])->name('admin.compte');
        Route::post('/deconnexion', [AdminController::class, 'logout']);
        Route::post('/update-profile-admin', [AdminController::class,  'updateProfileAdmin'])->name('admins.profile.update');



        //ROUTE ADMINISTRATOR
        Route::prefix('administrateur')->group(function () {

            Route::get('/', [AdminController::class,  'getAdmin']);
            Route::post('/ajouter-admin', [AdminController::class, 'storeAdmin']);
            Route::get('/info-admin/{admin_id}', [AdminController::class,  'infoAdmin'])->name('admins.info');
            Route::put('/update-admin/{admin_id}', [AdminController::class,  'updateAdmin']);
            Route::post('/mot-de-passe', [AdminController::class,  'updateMotDePasse'])->name('admins.update.mdp');
            Route::get('/delete-admin/{admin_id}', [AdminController::class,  'deleteAdmin']);
        });



        //ROUTE CATEGORIE CHAMBRE DE CHAMBRE
        Route::prefix('categoriechambres')->group(function () {
            Route::get('/', [CategoriechambreController::class, 'getCategorieChambre']);
            Route::post('/ajouter-categoriechambre', [CategoriechambreController::class, 'storeCategorieChambre']);
            Route::put('/update-categoriechambre/{id_categoriechambre}', [CategoriechambreController::class,  'updateCategorieChambre']);
            Route::get('/info-categoriechambre/{id_categoriechambre}', [CategoriechambreController::class, 'infoCategoriechambre']);
            Route::get('/delete-categoriechambre/{id_categoriechambre}', [CategoriechambreController::class, 'deleteCategorieChambre']);
        });




        //ROUTE  HOTEL
        Route::prefix('hotels')->group(function () {
            Route::get('/', [AdminHotelController::class, 'getHotel']);
            Route::post('/ajouter-hotel', [AdminHotelController::class, 'storeHotel']);
            Route::get('/editer-hotel/{id_hotel}', [AdminHotelController::class,  'editHotel']);
            Route::put('/update-hotel/{id_hotel}', [AdminHotelController::class,  'updateHotel']);
            // Route::get('/get-spec-hotel', [AdminHotelController::class, 'getSpechotelHotel']);
            Route::get('/info-hotel/{id_hotel}', [AdminHotelController::class, 'infoHotel']);
            Route::get('/delete-hotel/{id_hotel}', [AdminHotelController::class, 'deleteHotel']);
        });




        //ROUTE COMPAGNIE
        Route::prefix('compagnies')->group(function () {
            Route::get('/', [AdminCompagnieController::class, 'getCompagnie']);
            Route::post('/ajouter-compagnie', [AdminCompagnieController::class, 'storeCompagnie']);
            Route::put('/update-compagnie/{id_compagnie}', [AdminCompagnieController::class,  'updateCompagnie']);
            // Route::get('/get-spec-compagnie', [AdminCompagnieController::class, 'getSpeccompagniecompagnie']);
            Route::get('/info-compagnie/{id_compagnie}', [AdminCompagnieController::class, 'infoCompagnie']);
            Route::get('/delete-compagnie/{id_compagnie}', [AdminCompagnieController::class, 'deleteCompagnie']);
        });





        //ROUTE FACTURE
        Route::prefix('factures')->group(function () {
            Route::get('/', [FactureController::class, 'getFacture'])->name('factures.get');
            // Route::get('/get-spec-facture', '[FactureController::class, '']@getSpecParkingVoiture')->name('factures.get.spec');
            Route::get('/info-facture', [FactureController::class, 'infoFacture'])->name('factures.info');
            Route::post('/download-facture', [FactureController::class, 'downloadFacture'])->name('factures.download');
            Route::post('/update-facture', [FactureController::class, 'updateFacture'])->name('factures.update');
            Route::post('/store-facture', [FactureController::class, 'storeFacture'])->name('factures.store');
            Route::post('/delete-facture', [FactureController::class, 'deleteFacture'])->name('factures.delete');
        });




        //ROUTE CHAMBRE
        Route::prefix('chambres')->group(function () {
            Route::get('/', [ChambreController::class, 'getChambre'])->name('chambres.get');
            Route::post('/ajouter-chambre', [ChambreController::class, 'storeChambre']);
            Route::put('/update-chambre/{id_chambre}', [ChambreController::class,  'updateChambre']);
            // Route::get('/get-spec-hotel', [ChambreController::class, 'getSpechotelHotel']);
            Route::get('/info-chambre/{id_chambre}', [ChambreController::class, 'infoChambre']);
            Route::get('/delete-chambre/{id_chambre}', [ChambreController::class, 'deleteChambre']);
        });


        //ROUTE service
        Route::prefix('services')->group(function () {
            Route::get('/', [ServiceController::class, 'getServiceChambre'])->name('services-chambres.get.gestion');
            Route::post('/ajouter-service', [ServiceController::class, 'storeServiceChambre'])->name('services-chambres.store.gestion');
            Route::get('/get-spec-service', [ServiceController::class, 'getSpecChambreService'])->name('services-chambres.get.spec.gestion');
            Route::put('/update-service/{id_service}', [ServiceController::class, 'updateServiceChambre'])->name('services-chambres.update.gestion');
            Route::get('/info-service/{id_service}', [ServiceController::class, 'infoServiceChambre'])->name('services-chambres.info.gestion');
            Route::get('/delete-service/{id_service}', [ServiceController::class, 'deleteServiceChambre'])->name('services-chambres.delete.gestion');
        });





        //ROUTE IMAGE_chambre
        Route::prefix('images-chambres')->group(function () {
            Route::get('/', [ImageController::class, 'getImagechambre'])->name('images-chambres.get');
            Route::post('/store-image-chambre', [ImageController::class, 'storeImagechambre'])->name('images-chambres.store');
            Route::get('/get-spec-image-chambre', [ImageController::class, 'getSpecImagechambre'])->name('images-chambres.get.spec');
            Route::post('/update-image-chambre', [ImageController::class, 'updateImageChambre'])->name('images-chambres.update');
            Route::get('/info-image-chambre', [ImageController::class, 'infoImagechambre'])->name('images-chambres.info');
            Route::post('/delete-image-chambre', [ImageController::class, 'deleteImagechambre'])->name('images-chambres.delete');
        });


        //à faire
        //ROUTE IMAGE_HOTEL
        //ROUTE IMAGE_CATEGORIECHAMBRE
        //ROUTE IMAGE_TYPEHEBERGEMNT
        //ROUTE IMAGE_VILLE
        //ROUTE IMAGE_PAYS



        //ROUTE TYPE HEBERGEMENT
        Route::prefix('typehebergements')->group(function () {
            Route::get('/', [TypehebergementController::class, 'getTypeHebergement'])->name('typehebergements.get');
            Route::post('/ajouter-type', [TypehebergementController::class, 'storeTypeHebergement']);
            Route::put('/update-type/{id_typehebergement}', [TypehebergementController::class,  'updateTypeHebergement']);
            // Route::get('/get-spec-type', [TypehebergementController::class, 'getSpectypeHotel']);
            Route::get('/info-type/{id_typehebergement}', [TypehebergementController::class, 'infoTypeHebergement']);
            Route::get('/delete-type/{id_typehebergement}', [TypehebergementController::class, 'deleteTypeHebergement']);
        });




        //ROUTE VILLE
        Route::prefix('villes')->group(function () {
            Route::get('/', [VilleController::class, 'getVille'])->name('villes.get');
            Route::post('/ajouter-ville', [VilleController::class, 'storeVille']);
            Route::put('/update-ville/{id_ville}', [VilleController::class,  'updateVille']);
            // Route::get('/get-spec-ville', [VilleController::class, 'getSpecvilleHotel']);
            Route::get('/info-ville/{id_ville}', [VilleController::class, 'infoVille']);
            Route::get('/delete-ville/{id_ville}', [VilleController::class, 'deleteVille']);
        });


        //ROUTE PAYS
        Route::prefix('pays')->group(function () {
            Route::get('/', [PaysController::class, 'getPays'])->name('pays.get');
            Route::post('/ajouter-pays', [PaysController::class, 'storePays']);
            Route::put('/update-pays/{id_pays}', [PaysController::class,  'updatePays']);
            // Route::get('/get-spec-pays', [PaysController::class, 'getSpecpaysHotel']);
            Route::get('/info-pays/{id_pays}', [PaysController::class, 'infoPays']);
            Route::get('/delete-pays/{id_pays}', [PaysController::class, 'deletePays']);
        });


        // //ROUTE  HOTEL
        // Route::prefix('hotel')->group(function () {
        //     Route::get('/', [HotelController::class, 'getHotel'])->name('hotel.get');
        //     Route::get('/get-spec-hotel', [HotelController::class, 'getSpecinfoHotel'])->name('hotel.get.spec');
        //     Route::get('/info-hotel', [HotelController::class, 'infoHotel'])->name('hotel.info');
        //     Route::post('/update-hotel', [HotelController::class, 'updateHotel'])->name('hotel.update');
        //     Route::post('/store-hotel', [HotelController::class, 'storeHotel'])->name('hotel.store');
        //     Route::post('/delete-hotel', [HotelController::class, 'deleteHotel'])->name('hotel.delete');
        // });




        //ROUTE CLIENT
        Route::prefix('clients')->group(function () {
            Route::get('/', [AdminClientController::class, 'getClients'])->name('clients.get');
            Route::get('/info-client/{id_client}', [AdminClientController::class, 'infoClient'])->name('clients.info');
            // Route::post('/ajouter-client', [AdminClientController::class, 'storeClient']);
            Route::put('/update-client/{id_client}', [AdminClientController::class, 'updateClient'])->name('clients.update');
            Route::post('/delete-client/{id_client}', [AdminClientController::class, 'deleteClient'])->name('clients.delete');
        });


        //ROUTE RESERVATION
        Route::prefix('reservations')->group(function () {
            Route::get('/', [ReservationController::class, 'getReservations'])->name('reservations.get.gestion');
            Route::get('/get-spec-reservation', [ReservationController::class, 'getSpecreservation'])->name('reservations.get.spec.gestion');
            Route::post('/update-reservation', [ReservationController::class, 'updatereservation'])->name('reservations.update.gestion');
            Route::get('/info-reservation', [ReservationController::class, 'infoReservation'])->name('reservations.info.gestion');
            Route::post('/store-reservation', [ReservationController::class, 'storeReservation'])->name('reservations.store.gestion');
            Route::post('/delete-reservation', [ReservationController::class, 'annulationReservation'])->name('reservations.annuler.gestion');
        });





        //ROUTE FACTURE
        Route::prefix('factures')->group(function () {
            Route::get('/', [FactureController::class, 'getFacture'])->name('factures.get');
            Route::get('/get-spec-facture', [FactureController::class, ''])->name('factures.get.spec');
            Route::get('/info-facture', [FactureController::class, 'infoFacture'])->name('factures.info');
            Route::post('/download-facture', [FactureController::class, 'downloadFacture'])->name('factures.download');
            Route::post('/update-facture', [FactureController::class, 'updateFacture'])->name('factures.update');
            Route::post('/store-facture', [FactureController::class, 'storeFacture'])->name('factures.store');
            Route::post('/delete-facture', [FactureController::class, 'deleteFacture'])->name('factures.delete');
        });
    }
);




////////////////////////////////////////////////ROUTE LOGIN HOTEL//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN HOTEL//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN HOTEL//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN HOTEL//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN HOTEL//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN HOTEL//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN HOTEL//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN HOTEL//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN HOTEL//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN HOTEL//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN HOTEL//////////////////////////////////////////////////////////////




//ROUTE Gestionnaire hotel  login



Route::group(

    [

        'prefix' => 'auth/Hotel',
        'middleware' => 'api',
        "namespace" => "App\Http\Controllers",



    ],


    function () {
        Route::get('/tableau-de-bord', [HotelController::class, 'tableauDeBord'])->name('hotel.tableaudebord');
        Route::get('/monCompte', [HotelController::class, 'profileHotel'])->name('hotel.compte');

        Route::get('/info-hotel', [HotelController::class, 'infoHotel'])->name('hotel.info.gestion');
        Route::post('/update-profile-hotel', [HotelController::class, 'updateProfileHotel'])->name('hotel.profile.update');
        Route::post('/mot-de-passe', [HotelController::class, 'updateMotDePasse'])->name('hotel.update.mdp');

        //ROUTE Categorie chambre

        //ROUTE CATEGORIE CHAMBRE DE CHAMBRE
        Route::prefix('categoriechambres')->group(function () {
            Route::get('/', [HotelCategoriechambreController::class, 'getCategorieChambre']);
            Route::post('/ajouter-categoriechambre', [HotelCategoriechambreController::class, 'storeCategorieChambre']);
            Route::get('/editer-categoriechambre/{id_categoriechambre}', [HotelCategoriechambreController::class,  'editCategorieChambre']);
            Route::put('/update-categoriechambre/{id_categoriechambre}', [HotelCategoriechambreController::class,  'updateCategorieChambre']);
            // Route::get('/get-spec-categoriechambre', [HotelCategoriechambreController::class, 'getSpecCategorieChambreHotel']);
            Route::get('/info-categoriechambre/{id_categoriechambre}', [HotelCategoriechambreController::class, 'infoCategoriechambre']);
            Route::get('/delete-categoriechambre/{id_categoriechambre}', [HotelCategoriechambreController::class, 'deleteCategorieChambre']);
        });



        //ROUTE CHAMBRE
        Route::prefix('chambres')->group(function () {
            Route::get('/', [HotelChambreController::class, 'getChambre'])->name('chambres.get');


            Route::get('/ajouter-chambre', [HotelChambreController::class, 'createChambre']);
            Route::post('/ajouter-chambre', [HotelChambreController::class, 'storeChambre']);
            Route::get('/editer-chambre/{id_chambre}', [HotelChambreController::class,  'editChambre']);
            Route::put('/update-chambre/{id_chambre}', [HotelChambreController::class,  'updateChambre']);
            // Route::get('/get-spec-hotel', [HotelChambreController::class, 'getSpechotelHotel']);
            Route::get('/info-chambre/{id_chambre}', [HotelChambreController::class, 'infoChambre']);
            Route::get('/delete-chambre/{id_chambre}', [HotelChambreController::class, 'deleteChambre']);
        });



        // //ROUTE hotel
        // Route::prefix('hotels')->group(function () {
        //     Route::get('/get-spec-hotel', [HotelHotelController::class, 'getSpecHotelChambre'])->name('hotels.get.spec.gestion');
        //     Route::get('/', [HotelHotelController::class, 'getSpecHotelChambre'])->name('hotels.get.spec.gestion');
        //     Route::get('/get-spec-hotel', [HotelHotelController::class, 'getSpecHotelChambre'])->name('hotels.get.spec.gestion');
        //     Route::get('/get-spec-hotel', [HotelHotelController::class, 'getSpecHotelChambre'])->name('hotels.get.spec.gestion');
        //     Route::get('/get-spec-hotel', [HotelHotelController::class, 'getSpecHotelChambre'])->name('hotels.get.spec.gestion');
        // });




        //ROUTE reservation
        Route::prefix('reservations')->group(function () {
            Route::get('/', [HotelReservationController::class, 'getReservations'])->name('reservations.get.gestion');
            Route::get('/get-spec-reservation', [HotelReservationController::class, 'getSpecreservation'])->name('reservations.get.spec.gestion');
            Route::post('/update-reservation', [HotelReservationController::class, 'updatereservation'])->name('reservations.update.gestion');
            Route::get('/info-reservation', [HotelReservationController::class, 'infoReservation'])->name('reservations.info.gestion');
            Route::post('/store-reservation', [HotelReservationController::class, 'storeReservation'])->name('reservations.store.gestion');
            Route::post('/delete-reservation', [HotelReservationController::class, 'annulationReservation'])->name('reservations.annuler.gestion');
        });


        //ROUTE service
        Route::prefix('services-chambres')->group(function () {
            Route::get('/', [HotelServiceController::class, 'getServiceChambre'])->name('services-chambres.get.gestion');
            Route::get('/get-spec-service', [HotelServiceController::class, 'getSpecChambreService'])->name('services-chambres.get.spec.gestion');
            Route::post('/update-service', [HotelServiceController::class, 'updateServiceChambre'])->name('services-chambres.update.gestion');
            Route::get('/info-service', [HotelServiceController::class, 'infoServiceChambre'])->name('services-chambres.info.gestion');
            Route::post('/store-service', [HotelServiceController::class, 'storeServiceChambre'])->name('services-chambres.store.gestion');
            Route::post('/delete-service', [HotelServiceController::class, 'deleteServiceChambre'])->name('services-chambres.delete.gestion');
        });







        //ROUTE facture
        Route::prefix('factures')->group(function () {
            Route::get('/', [HotelFactureController::class, 'getFacture'])->name('factures.get.gestion');
            Route::get('/get-spec-facture', [HotelFactureController::class, 'getSpecFacture'])->name('factures.get.spec.gestion');
            Route::post('/update-facture', [HotelFactureController::class, 'updatefacture'])->name('factures.update.gestion');
            Route::get('/info-facture', [HotelFactureController::class, 'infoFacture'])->name('factures.info.gestion');
            Route::post('/store-facture', [HotelFactureController::class, 'storeFacture'])->name('factures.store.gestion');
            Route::post('/delete-facture', [HotelFactureController::class, 'deleteFacture'])->name('factures.delete.gestion');
        });







        //ROUTE IMAGE_CHAMBRE

        Route::prefix('image-chambres')->group(function () {
            Route::get('/', [HotelImageController::class, 'getImageChambre'])->name('images-chambres.get.gestion');
            Route::get('/get-spec-image-chambres', [HotelImageController::class, 'getSpecImageChambre'])->name('images-chambres.get.spec.gestion');
            Route::post('/update-image-chambres', [HotelImageController::class, 'updateImageChambre'])->name('images-chambres.update.gestion');
            Route::get('/info-image-chambres', [HotelImageController::class, 'infoImageChambre'])->name('images-chambres.info.gestion');
            Route::post('/store-image-chambres', [HotelImageController::class, 'storeImageChambre'])->name('images-chambres.store.gestion');
            Route::post('/delete-image-chambres', [HotelImageController::class, 'deleteImageChambre'])->name('images-chambres.delete.gestion');
        });


        //à faire
        //ROUTE IMAGE_HOTEL
        //ROUTE IMAGE_CATEGORIECHAMBRE
        //ROUTE IMAGE_TYPEHEBERGEMNT
        //ROUTE IMAGE_VILLE
        //ROUTE IMAGE_PAYS



    }
);






////////////////////////////////////////////////ROUTE LOGIN COMPAGNIE//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN COMPAGNIE//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN COMPAGNIE//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN COMPAGNIE//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN COMPAGNIE//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN COMPAGNIE//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN COMPAGNIE//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN COMPAGNIE//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN COMPAGNIE//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN COMPAGNIE//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN COMPAGNIE//////////////////////////////////////////////////////////////




Route::group(

    [

        'prefix' => 'auth/Compagnie',
        'middleware' => 'api',
        "namespace" => "App\Http\Controllers",



    ],


    function () {
        Route::get('/tableau-de-bord', [ControllersCompagnieController::class, 'tableauDeBord'])->name('hotel.tableaudebord');
        Route::get('/monCompte', [ControllersCompagnieController::class, 'profileHotel'])->name('hotel.compte');

        Route::get('/info-hotel', [ControllersCompagnieController::class, 'infoHotel'])->name('hotel.info.gestion');
        Route::post('/update-profile-hotel', [CompagnieController::class, 'updateProfileHotel'])->name('hotel.profile.update');
        Route::post('/mot-de-passe', [ControllersCompagnieController::class, 'updateMotDePasse'])->name('hotel.update.mdp');

        //ROUTE Categorie chambre

        //ROUTE CATEGORIE CHAMBRE DE CHAMBRE
        Route::prefix('categoriechambres')->group(function () {
            Route::get('/', [HotelCategoriechambreController::class, 'getCategorieChambre']);
            Route::post('/ajouter-categoriechambre', [HotelCategoriechambreController::class, 'storeCategorieChambre']);
            Route::get('/editer-categoriechambre/{id_categoriechambre}', [HotelCategoriechambreController::class,  'editCategorieChambre']);
            Route::put('/update-categoriechambre/{id_categoriechambre}', [HotelCategoriechambreController::class,  'updateCategorieChambre']);
            // Route::get('/get-spec-categoriechambre', [HotelCategoriechambreController::class, 'getSpecCategorieChambreHotel']);
            Route::get('/info-categoriechambre/{id_categoriechambre}', [HotelCategoriechambreController::class, 'infoCategoriechambre']);
            Route::get('/delete-categoriechambre/{id_categoriechambre}', [HotelCategoriechambreController::class, 'deleteCategorieChambre']);
        });



        //ROUTE CHAMBRE
        Route::prefix('chambres')->group(function () {
            Route::get('/', [HotelChambreController::class, 'getChambre'])->name('chambres.get');


            Route::get('/ajouter-chambre', [HotelChambreController::class, 'createChambre']);
            Route::post('/ajouter-chambre', [HotelChambreController::class, 'storeChambre']);
            Route::get('/editer-chambre/{id_chambre}', [HotelChambreController::class,  'editChambre']);
            Route::put('/update-chambre/{id_chambre}', [HotelChambreController::class,  'updateChambre']);
            // Route::get('/get-spec-hotel', [HotelChambreController::class, 'getSpechotelHotel']);
            Route::get('/info-chambre/{id_chambre}', [HotelChambreController::class, 'infoChambre']);
            Route::get('/delete-chambre/{id_chambre}', [HotelChambreController::class, 'deleteChambre']);
        });



        // //ROUTE hotel
        // Route::prefix('hotels')->group(function () {
        //     Route::get('/get-spec-hotel', [HotelHotelController::class, 'getSpecHotelChambre'])->name('hotels.get.spec.gestion');
        //     Route::get('/', [HotelHotelController::class, 'getSpecHotelChambre'])->name('hotels.get.spec.gestion');
        //     Route::get('/get-spec-hotel', [HotelHotelController::class, 'getSpecHotelChambre'])->name('hotels.get.spec.gestion');
        //     Route::get('/get-spec-hotel', [HotelHotelController::class, 'getSpecHotelChambre'])->name('hotels.get.spec.gestion');
        //     Route::get('/get-spec-hotel', [HotelHotelController::class, 'getSpecHotelChambre'])->name('hotels.get.spec.gestion');
        // });




        //ROUTE reservation
        Route::prefix('reservations')->group(function () {
            Route::get('/', [HotelReservationController::class, 'getReservations'])->name('reservations.get.gestion');
            Route::get('/get-spec-reservation', [HotelReservationController::class, 'getSpecreservation'])->name('reservations.get.spec.gestion');
            Route::post('/update-reservation', [HotelReservationController::class, 'updatereservation'])->name('reservations.update.gestion');
            Route::get('/info-reservation', [HotelReservationController::class, 'infoReservation'])->name('reservations.info.gestion');
            Route::post('/store-reservation', [HotelReservationController::class, 'storeReservation'])->name('reservations.store.gestion');
            Route::post('/delete-reservation', [HotelReservationController::class, 'annulationReservation'])->name('reservations.annuler.gestion');
        });


        //ROUTE service
        Route::prefix('services-chambres')->group(function () {
            Route::get('/', [HotelServiceController::class, 'getServiceChambre'])->name('services-chambres.get.gestion');
            Route::get('/get-spec-service', [HotelServiceController::class, 'getSpecChambreService'])->name('services-chambres.get.spec.gestion');
            Route::post('/update-service', [HotelServiceController::class, 'updateServiceChambre'])->name('services-chambres.update.gestion');
            Route::get('/info-service', [HotelServiceController::class, 'infoServiceChambre'])->name('services-chambres.info.gestion');
            Route::post('/store-service', [HotelServiceController::class, 'storeServiceChambre'])->name('services-chambres.store.gestion');
            Route::post('/delete-service', [HotelServiceController::class, 'deleteServiceChambre'])->name('services-chambres.delete.gestion');
        });







        //ROUTE facture
        Route::prefix('factures')->group(function () {
            Route::get('/', [HotelFactureController::class, 'getFacture'])->name('factures.get.gestion');
            Route::get('/get-spec-facture', [HotelFactureController::class, 'getSpecFacture'])->name('factures.get.spec.gestion');
            Route::post('/update-facture', [HotelFactureController::class, 'updatefacture'])->name('factures.update.gestion');
            Route::get('/info-facture', [HotelFactureController::class, 'infoFacture'])->name('factures.info.gestion');
            Route::post('/store-facture', [HotelFactureController::class, 'storeFacture'])->name('factures.store.gestion');
            Route::post('/delete-facture', [HotelFactureController::class, 'deleteFacture'])->name('factures.delete.gestion');
        });







        //ROUTE IMAGE_CHAMBRE

        Route::prefix('image-chambres')->group(function () {
            Route::get('/', [HotelImageController::class, 'getImageChambre'])->name('images-chambres.get.gestion');
            Route::get('/get-spec-image-chambres', [HotelImageController::class, 'getSpecImageChambre'])->name('images-chambres.get.spec.gestion');
            Route::post('/update-image-chambres', [HotelImageController::class, 'updateImageChambre'])->name('images-chambres.update.gestion');
            Route::get('/info-image-chambres', [HotelImageController::class, 'infoImageChambre'])->name('images-chambres.info.gestion');
            Route::post('/store-image-chambres', [HotelImageController::class, 'storeImageChambre'])->name('images-chambres.store.gestion');
            Route::post('/delete-image-chambres', [HotelImageController::class, 'deleteImageChambre'])->name('images-chambres.delete.gestion');
        });


        //à faire
        //ROUTE IMAGE_HOTEL
        //ROUTE IMAGE_CATEGORIECHAMBRE
        //ROUTE IMAGE_TYPEHEBERGEMNT
        //ROUTE IMAGE_VILLE
        //ROUTE IMAGE_PAYS



    }
);



////////////////////////////////////////////////ROUTE LOGIN CLIENT//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN CLIENT//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN CLIENT//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN CLIENT//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN CLIENT//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN CLIENT//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN CLIENT//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN CLIENT//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN CLIENT//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN CLIENT//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN CLIENT//////////////////////////////////////////////////////////////




Route::group(

    [
        'prefix' => 'auth/Client',
        'middleware' => 'api',
        "namespace" => "App\Http\Controllers",



    ],
    function () {

        Route::get('/deconnexion', [UserController::class, 'deconnexion']);
        Route::get('/profil', [UserController::class, 'profileClient']);

        Route::get('/tableau-de-bord', [UserController::class, 'tableauDeBord'])->name('utilisateur.tableaudebord');
        // Route::get('/profile', [UserController::class, 'profile'])->name('utilisateur.profile');
        Route::get('/info-client', [UserController::class, 'infoClient'])->name('clients.infos');
        Route::post('/mon-compte', [UserController::class, 'updateUtilisateur'])->name('utilisateur.update');
        Route::get('/mot-de-passe', [UserController::class, 'motDePasse'])->name('utilisateur.mdp');
        Route::post('/mot-de-passe', [UserController::class, 'updateMotDePasse'])->name('utilisateur.update.mdp');

        //ROUTE RESERVATION
        Route::prefix('reservations')->group(function () {
            Route::get('/', [ClientReservationController::class, 'getReservations'])->name('reservations.get');
            Route::get('/listes', [ClientReservationController::class, 'listeReservation'])->name('reservations.liste');
            Route::post('/ajouter-reservation', [ClientReservationController::class, 'storeReservation'])->name('reservations.store');
            Route::get('/info-reservation', [ClientClientReservationController::class, 'infoReservation'])->name('reservations.info');
            Route::post('/annulation-reservation', [ClientReservationController::class, 'annulationReservation'])->name('reservations.annulation');
            Route::post('/{datearrivee}/disponibilite-chambre', [ClientReservationController::class, 'disponibiliteChambre'])->name('reservations.disponibilitechambre');
            Route::get('/payementsuccess', [ClientReservationController::class, 'reservation_payement_sucess']);
            Route::get('/echecpayement', [ClientReservationController::class, 'reservation_payement_annuler']);
            Route::post('/payementparstripe', [ClientReservationController::class, 'stripePost']);
            Route::get('/checkout', [ClientReservationController::class, 'checkout'])->name('reservations.checkout');
            Route::get('/success', [ClientReservationController::class, 'success'])->name('reservations.success');
            Route::get('/cancel', [ClientReservationController::class, 'cancel'])->name('reservations.cancel');
        });



        //ROUTE FACTURE
        Route::prefix('factures')->group(function () {
            Route::get('/', [ClientFactureController::class, 'getFacture'])->name('factures.get');
            Route::get('/listes', [ClientFactureController::class, 'listeFacture'])->name('factures.liste');
            // Route::get('/get-spec-facture', '[ClientFactureController::class, '']@getSpecParkingVoiture')->name('factures.get.spec');
            Route::get('/info-facture', [ClientFactureController::class, 'infoFacture'])->name('factures.info');
            Route::post('/download-facture', [ClientFactureController::class, 'downloadFacture'])->name('factures.download');
            Route::post('/update-facture', [ClientFactureController::class, 'updateFacture'])->name('factures.update');
        });


        //LIENS DE PAIEMENT



        Route::get('/notifications-payement', [ClientReservationController::class, 'notificationPayement']);
        Route::any('/payement-callback', [ClientReservationController::class, 'payementCallback'])->name('payements.callback');
        Route::get('/payement-cancel', [ClientReservationController::class, 'payementCancel'])->name('payements.cancel');
        Route::any('/payement-return', [ClientReservationController::class, 'payementReturn'])->name('payements.return');
    }
);
