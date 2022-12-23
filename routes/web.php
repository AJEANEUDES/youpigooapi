<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\TableaudebordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CategoriechambreController;
use App\Http\Controllers\Admin\FactureController;
use App\Http\Controllers\Admin\ChambreController;
use App\Http\Controllers\Admin\HotelController as AdminHotelController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\PaysController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Client\ReservationController as ClientReservationController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TypehebergementController;
use App\Http\Controllers\Admin\VilleController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\FactureController as ClientFactureController;
use App\Http\Controllers\GestionnaireHotelController;
use App\Http\Controllers\Hotel\CategoriechambreController as HotelCategoriechambreController;
use App\Http\Controllers\Hotel\ChambreController as HotelChambreController;
use App\Http\Controllers\Hotel\FactureController as HotelFactureController;
use App\Http\Controllers\Hotel\HotelController as HotelHotelController;
use App\Http\Controllers\Hotel\ImageController as HotelImageController;
use App\Http\Controllers\Hotel\ReservationController as HotelReservationController;
use App\Http\Controllers\Hotel\ServiceController as HotelServiceController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\PrivateController;
use App\Http\Controllers\SiteWebController;
use App\Http\Controllers\SocieteController;





//ROUTE AUTH
Auth::routes(['register' => true, 'verify' => false, 'reset' => false]);

// Auth::routes();

// Route::get('/', function () {
//     return view('welcome');
// });

// require __DIR__ . '/auth.php';



//ROUTE SITEWEB PAGE

//      Route::get('admin/permissions', [PermissionController::class, 'index']);

Route::get('/', [SiteWebController::class, 'accueil'])->name('accueil.get');
Route::get('/reserver-chambre', [SiteWebController::class, 'reserverChambre'])->name('reserver.chambre.get');
Route::get('/paginate-chambre', [SiteWebController::class, 'fetchDataChambre'])->name('fetch.chambre');
Route::get('/paginate-hotel', [SiteWebController::class, 'fetchDataHotel'])->name('fetch.hotel');
Route::get('/paginate-categorie_chambre', [SiteWebController::class, 'fetchDataCatChambre'])->name('fetch.catchambre');
Route::get('/paginate-type_hebergement', [SiteWebController::class, '@fetchDataTypeheb'])->name('fetch.typeheb');
// Route::get('/comment-ca-marche', 'SiteWebController@commentCaMarche')->name('comment.marche.get');
Route::get('/contactez-nous', [SiteWebController::class, 'contactezNous'])->name('contactez.nous.get');
Route::post('/contactez-nous', [SiteWebController::class, 'contactSendMail'])->name('contacts.sends.mails');
// Route::get('/a-propos', 'SiteWebController@about')->name('about.get');

Route::get('/details-data-chambre', [SiteWebController::class, 'detailsChambre']);
Route::get('/details-chambre/{slug_categoriechambre}/{slug_hotel}/{id_chambre}', [SiteWebController::class, 'viewDetailsChambre']);
//Route::any('/se-connecter', 'SiteWebController@viewLogin')->name('login');
Route::get('/inscription', [SiteWebController::class, 'viewInscription'])->name('inscription.view');
Route::post('/inscription', [SiteWebController::class, 'inscription'])->name('inscriptions.clients');
Route::post('/connexion', [SiteWebController::class, 'connexion']);



//ROUTE PAYEMENT
// Route::get('/reservation', 'ReservationController@payementByPayDunya')->name('reservation');

//Route::get('/js/{files}', 'PrivateController@fileJs');



//ROUTE LOGIN ADMIN

//Route::get('/mon-compte/se-connecter', 'Auth\\LoginController@viewLogin')->name('login-admin.view');
//Route::post('/se-connecter', 'Auth\\LoginController@login')->name('login');

// Route::controller(WishlistController::class)->group(function (){
//     //wishlist page
//   Route::get('/wishlist','viewWishlist')->name('wishlist');
// });



////////////////////////////////////////////////ROUTE LOGIN ADMIN//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN ADMIN//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN ADMIN//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN ADMIN//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN ADMIN//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN ADMIN//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN ADMIN//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN ADMIN//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN ADMIN//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN ADMIN//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN ADMIN//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN ADMIN//////////////////////////////////////////////////////////////
////////////////////////////////////////////////ROUTE LOGIN ADMIN//////////////////////////////////////////////////////////////



// Route::controller(AdminController::class)

Route::group(['prefix' => 'Admin', 'middleware' => ['auth',]], function () {
    Route::get('/tableau-de-bord', [AdminController::class, 'tableauDeBord'])->name('admin.tableaudebord');
    Route::get('/mon-compte', [AdminController::class, 'profileAdmin'])->name('admin.compte');
    Route::post('/update-profile-admin', [AdminController::class,  'updateProfileAdmin'])->name('admins.profile.update');
    Route::post('/mot-de-passe', [AdminController::class,  'updateMotDePasse'])->name('admins.update.mdp');



    //ROUTE ADMINISTRATOR
    Route::prefix('administrateur')->group(function () {
        Route::get('/', [AdminController::class,  'getAdmin']);
        // Route::get('/get-spec-admin', [AdminController::class ,  'getSpecAdmin'])->name('admins.get.spec');
        Route::get('/ajouter-admin', [AdminController::class, 'createAdmin']);
        Route::post('/ajouter-admin', [AdminController::class, 'storeAdmin']);
        Route::get('/info-admin/{admin_id}', [AdminController::class,  'infoAdmin'])->name('admins.info');
        Route::get('/editer-admin/{admin_id}', [AdminController::class,  'editAdmin']);
        Route::put('/update-admin/{admin_id}', [AdminController::class,  'updateAdmin']);
        Route::get('/delete-admin/{admin_id}', [AdminController::class,  'deleteAdmin']);
    });





    //ROUTE CATEGORIE CHAMBRE DE CHAMBRE
    Route::prefix('categoriechambres')->group(function () {
        Route::get('/', [CategoriechambreController::class, 'getCategorieChambre']);
        Route::get('/ajouter-categoriechambre', [CategoriechambreController::class, 'createCategorieChambre']);
        Route::post('/ajouter-categoriechambre', [CategoriechambreController::class, 'storeCategorieChambre']);
        Route::get('/editer-categoriechambre/{id_categoriechambre}', [CategoriechambreController::class,  'editCategorieChambre']);
        Route::put('/update-categoriechambre/{id_categoriechambre}', [CategoriechambreController::class,  'updateCategorieChambre']);
        // Route::get('/get-spec-categoriechambre', [CategoriechambreController::class, 'getSpecCategorieChambreHotel']);
        Route::get('/info-categoriechambre/{id_categoriechambre}', [CategoriechambreController::class, 'infoCategoriechambre']);
        Route::get('/delete-categoriechambre/{id_categoriechambre}', [CategoriechambreController::class, 'deleteCategorieChambre']);
    });





    //ROUTE  HOTEL
    Route::prefix('hotels')->group(function () {
        Route::get('/', [AdminHotelController::class, 'getHotel']);

        Route::get('/ajouter-hotel', [AdminHotelController::class, 'createHotel']);
        Route::post('/ajouter-hotel', [AdminHotelController::class, 'storeHotel']);
        Route::get('/editer-hotel/{id_hotel}', [AdminHotelController::class,  'editHotel']);
        Route::put('/update-hotel/{id_hotel}', [AdminHotelController::class,  'updateHotel']);
        // Route::get('/get-spec-hotel', [AdminHotelController::class, 'getSpechotelHotel']);
        Route::get('/info-hotel/{id_hotel}', [AdminHotelController::class, 'infoHotel']);
        Route::get('/delete-hotel/{id_hotel}', [AdminHotelController::class, 'deleteHotel']);


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
        // Route::get('/get-spec-chambre', [ChambreController::class, 'getSpecChambre'])->name('chambres.get.spec');
        // Route::post('/update-chambre', [ChambreController::class, 'updateChambre'])->name('chambres.update');
        // Route::get('/info-chambre', [ChambreController::class, 'infoChambre'])->name('chambres.info');
        // Route::post('/store-chambre', [ChambreController::class, 'storeChambre'])->name('chambres.store');
        // Route::post('/delete-chambre', [ChambreController::class, 'deleteChambre'])->name('chambres.delete');



        Route::get('/ajouter-chambre', [ChambreController::class, 'createChambre']);
        Route::post('/ajouter-chambre', [ChambreController::class, 'storeChambre']);
        Route::get('/editer-chambre/{id_chambre}', [ChambreController::class,  'editChambre']);
        Route::put('/update-chambre/{id_chambre}', [ChambreController::class,  'updateChambre']);
        // Route::get('/get-spec-hotel', [ChambreController::class, 'getSpechotelHotel']);
        Route::get('/info-chambre/{id_chambre}', [ChambreController::class, 'infoChambre']);
        Route::get('/delete-chambre/{id_chambre}', [ChambreController::class, 'deleteChambre']);
    });




    //ROUTE IMAGE_chambre
    Route::prefix('image-chambres')->group(function () {
        Route::get('/', [ImageController::class, 'getImagechambre'])->name('images-chambres.get');
        Route::get('/get-spec-image-chambre', [ImageController::class, 'getSpecImagechambre'])->name('images-chambres.get.spec');
        Route::post('/update-image-chambre', [ImageController::class, 'updateImageChambre'])->name('images-chambres.update');
        Route::get('/info-image-chambre', [ImageController::class, 'infoImagechambre'])->name('images-chambres.info');
        Route::post('/store-image-chambre', [ImageController::class, 'storeImagechambre'])->name('images-chambres.store');
        Route::post('/delete-image-chambre', [ImageController::class, 'deleteImagechambre'])->name('images-chambres.delete');
  
    });


    //à faire
    //ROUTE IMAGE_HOTEL
    //ROUTE IMAGE_CATEGORIECHAMBRE
    //ROUTE IMAGE_TYPEHEBERGEMNT
    //ROUTE IMAGE_VILLE
    //ROUTE IMAGE_PAYS






    //ROUTE TYPE HEBERGEMENT
    Route::prefix('types')->group(function () {
        Route::get('/', [TypehebergementController::class, 'getTypeHebergement'])->name('types.get');
        // Route::get('/get-spec-typehebergement', [TypehebergementController::class, 'getSpecTypehebergement']);
        Route::get('/ajouter-type', [TypehebergementController::class, 'createTypeHebergement']);
        Route::post('/ajouter-type', [TypehebergementController::class, 'storeTypeHebergement']);
        Route::get('/editer-type/{id_typehebergement}', [TypehebergementController::class,  'editTypeHebergement']);
        Route::put('/update-type/{id_typehebergement}', [TypehebergementController::class,  'updateTypeHebergement']);
        // Route::get('/get-spec-type', [TypehebergementController::class, 'getSpectypeHotel']);
        Route::get('/info-type/{id_typehebergement}', [TypehebergementController::class, 'infoTypeHebergement']);
        Route::get('/delete-type/{id_typehebergement}', [TypehebergementController::class, 'deleteTypeHebergement']);
    });





    //ROUTE VILLE
    Route::prefix('villes')->group(function () {
        Route::get('/', [VilleController::class, 'getVille'])->name('villes.get');

        Route::get('/ajouter-ville', [VilleController::class, 'createVille']);
        Route::post('/ajouter-ville', [VilleController::class, 'storeVille']);
        Route::get('/editer-ville/{id_ville}', [VilleController::class,  'editVille']);
        Route::put('/update-ville/{id_ville}', [VilleController::class,  'updateVille']);
        // Route::get('/get-spec-ville', [VilleController::class, 'getSpecvilleHotel']);
        Route::get('/info-ville/{id_ville}', [VilleController::class, 'infoVille']);
        Route::get('/delete-ville/{id_ville}', [VilleController::class, 'deleteVille']);
    });




    //ROUTE PAYS
    Route::prefix('pays')->group(function () {
        Route::get('/', [PaysController::class, 'getPays'])->name('pays.get');
        Route::get('/ajouter-pays', [PaysController::class, 'createPays']);
        Route::post('/ajouter-pays', [PaysController::class, 'storePays']);
        Route::get('/editer-pays/{id_pays}', [PaysController::class,  'editPays']);
        Route::put('/update-pays/{id_pays}', [PaysController::class,  'updatePays']);
        // Route::get('/get-spec-pays', [PaysController::class, 'getSpecpaysHotel']);
        Route::get('/info-pays/{id_pays}', [PaysController::class, 'infoPays']);
        Route::get('/delete-pays/{id_pays}', [PaysController::class, 'deletePays']);
    });




    //ROUTE SERVICE_chambre
    Route::prefix('services-chambres')->group(function () {
        Route::get('/', [ServiceController::class, 'getServiceChambre'])->name('services-chambres.get');
        Route::get('/get-spec-service-chambre', [ServiceController::class, 'getSpecServiceChambre'])->name('services-chambres.get.spec');
        Route::get('/info-service', [ServiceController::class, 'infoService'])->name('services.info');
        Route::post('/update-service-chambre/{id_service}', [ServiceController::class, 'updateServiceChambre'])->name('services-chambres.update');
        Route::post('/ajouter-service-chambre/{id_service}', [ServiceController::class, 'storeServiceChambre'])->name('services-chambres.store');
        Route::post('/delete-service-chambre/{id_service}', [ServiceController::class, 'deleteServiceChambre'])->name('services-chambres.delete');
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
        Route::get('/', [ClientController::class, 'getClients'])->name('clients.get');
        Route::get('/info-client', [ClientController::class, 'infoClient'])->name('clients.info');
        Route::post('/update-client', [ClientController::class, 'updateClient'])->name('clients.update');
        Route::post('/delete-client', [ClientController::class, 'deleteClient'])->name('clients.delete');
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
});
















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





//ROUTE Gestionnaire hotel  login(Informations sur l'hôtel)

Route::group(['prefix' => 'Hotel', 'middleware' => ['auth']], function () {
    Route::get('/tableau-de-bord', [GestionnaireHotelController::class, 'tableauDeBord']);
    Route::get('/mon-compte', [App\Http\Controllers\Hotel\HotelController::class, 'profileHotel'])->name('hotel.compte');

    Route::get('/info-hotel', [App\Http\Controllers\Hotel\HotelController::class, 'infoHotel'])->name('hotel.info.gestion');
    Route::post('/update-profile-hotel', [App\Http\Controllers\Hotel\HotelController::class, 'updateProfileHotel'])->name('hotel.profile.update');
    Route::post('/mot-de-passe', [App\Http\Controllers\Hotel\HotelController::class, 'updateMotDePasse'])->name('hotel.update.mdp');



    //ROUTE Categorie chambre

    Route::prefix('categoriechambres')->group(function () {
        Route::get('/', [HotelCategoriechambreController::class, 'getCategoriechambre'])->name('categoriechambres.get.gestion');
        Route::get('/get-spec-categoriechambre', [HotelCategorieChambreController::class, 'getSpecCategorieChambreHotel'])->name('categoriechambres.get.spec.gestion');
        Route::get('/info-categoriechambre', [HotelCategorieChambreController::class, 'infoCategoriechambre'])->name('categoriechambres.info.gestion');
        Route::get('/load-categoriechambre', [HotelCategorieChambreController::class, 'loadDataCategoriechambre'])->name('categoriechambres.load.gestion');
        Route::post('/update-categoriechambre', [HotelCategorieChambreController::class, 'updateCategoriechambre'])->name('categoriechambres.update.gestion');
        Route::post('/store-categoriechambre', [HotelCategorieChambreController::class, 'storeCategoriechambre'])->name('categoriechambres.store.gestion');
        Route::post('/delete-categoriechambre', [HotelCategorieChambreController::class, 'deleteCategoriechambre'])->name('categoriechambres.delete.gestion');
    });

    //ROUTE Chambre

    Route::prefix('chambres')->group(function () {
        Route::get('/', [HotelChambreController::class, 'getChambre'])->name('chambres.get.gestion');
        Route::get('/get-spec-chambre', [HotelChambreController::class, 'getSpecChambre'])->name('chambres.get.spec.gestion');
        Route::get('/info-chambre', [HotelChambreController::class, 'infoChambre'])->name('chambres.info.gestion');
        Route::post('/update-chambre', [HotelChambreController::class, 'updateChambre'])->name('chambres.update.gestion');
        Route::post('/store-chambre', [HotelChambreController::class, 'storeChambre'])->name('chambres.store.gestion');
        Route::post('/delete-chambre', [HotelChambreController::class, 'deleteChambre'])->name('chambres.delete.gestion');
    });

    //ROUTE hotel
    Route::prefix('hotels')->group(function () {
        Route::get('/get-spec-hotel', [HotelHotelController::class, 'getSpecHotelChambre'])->name('hotels.get.spec.gestion');
        Route::get('/', [HotelHotelController::class, 'getSpecHotelChambre'])->name('hotels.get.spec.gestion');
        Route::get('/get-spec-hotel', [HotelHotelController::class, 'getSpecHotelChambre'])->name('hotels.get.spec.gestion');
        Route::get('/get-spec-hotel', [HotelHotelController::class, 'getSpecHotelChambre'])->name('hotels.get.spec.gestion');
        Route::get('/get-spec-hotel', [HotelHotelController::class, 'getSpecHotelChambre'])->name('hotels.get.spec.gestion');
    });

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



});











//ROUTE CLIENT
//ROUTE CLIENT
//ROUTE CLIENT
//ROUTE CLIENT
//ROUTE CLIENT
//ROUTE CLIENT
//ROUTE CLIENT
//ROUTE CLIENT
//ROUTE CLIENT
//ROUTE CLIENT
//ROUTE CLIENT
//ROUTE CLIENT
//ROUTE CLIENT
//ROUTE CLIENT
//ROUTE CLIENT


Route::group(['prefix' => 'client', 'middleware' => ['auth']], function () {
    Route::get('/tableau-de-bord', [UserController::class, 'tableauDeBord'])->name('utilisateur.tableaudebord');
    Route::get('/profile', [UserController::class, 'profile'])->name('utilisateur.profile');
    Route::get('/info-client', [UserController::class, 'infoClient'])->name('clients.infos');
    Route::post('/mon-compte', [UserController::class, 'updateUtilisateur'])->name('utilisateur.update');
    Route::get('/mot-de-passe', [UserController::class, 'motDePasse'])->name('utilisateur.mdp');
    Route::post('/mot-de-passe', [UserController::class, 'updateMotDePasse'])->name('utilisateur.update.mdp');

    //ROUTE RESERVATION
    Route::prefix('reservations')->group(function () {
        Route::get('/', [ClientReservationController::class, 'getReservations'])->name('reservations.get');
        Route::get('/listes', [ClientReservationController::class, 'listeReservation'])->name('reservations.liste');
        Route::post('/store-reservation', [ClientReservationController::class, 'storeReservation'])->name('reservations.store');
        Route::get('/info-reservation', [ClientClientReservationController::class, 'infoReservation'])->name('reservations.info');
        Route::post('/annulation-reservation', [ClientReservationController::class, 'annulationReservation'])->name('reservations.annulation');
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
});


//Reset Password
Route::get('/reinitialiser-mon-de-passe', [PrivateController::class, 'viewResetPassword'])->name('reinitialiser.mon-de-passe.get');
Route::post('/reinitialiser-mon-de-passe', [PrivateController::class, 'resetPassword'])->name('reinitialiser.mon-de-passe.check');
Route::get('/reinitialiser-mon-de-passe/{reset_code}', [PrivateController::class, 'resetNewPassword'])->name('reinitialiser.reset-code');
Route::post('/reinitialiser-mon-de-passe/{reset_code}', [PrivateController::class, 'getPasswordReset'])->name('reinitialiser.password-reset');












// //rouets admin

// Route::group(['middleware' => ['auth', 'can:manage-users']], function () {

    
//     //lien du tableau de bord
//     Route::get('admin/tableaudebord', [TableaudebordController::class, 'index']);



//     Route::resource('admin/hotels', HotelController::class);


//     //utilisateurs

//     Route::get('admin/users', [UserController::class, 'index']);
//     Route::get('admin/users/create-user', [UserController::class, 'create']);
//     Route::post('admin/users/create-user', [UserController::class, 'store']);
//     Route::get('admin/users/show-user/{user_id}', [UserController::class, 'show']);
//     Route::get('admin/users/edit/{user_id}', [UserController::class, 'edit']);
//     Route::put('admin/users/update/{user_id}', [UserController::class, 'update']);
//     Route::get('admin/users/delete/{user_id}', [UserController::class, 'destroy']);


//      //roles

//      Route::get('admin/roles', [RoleController::class, 'index']);
//      Route::get('admin/roles/create-role', [RoleController::class, 'create']);
//      Route::post('admin/roles/create-role', [RoleController::class, 'store']);
//      Route::get('admin/roles/show-role/{role_id}', [RoleController::class, 'show']);
//      Route::get('admin/roles/edit/{role_id}', [RoleController::class, 'edit']);
//      Route::put('admin/roles/update/{role_id}', [RoleController::class, 'update']);
//      Route::get('admin/roles/delete/{role_id}', [RoleController::class, 'destroy']);


     
//      //permissions


//      Route::get('admin/permissions', [PermissionController::class, 'index']);
//      Route::get('admin/permissions/create-permission', [PermissionController::class, 'create']);
//      Route::post('admin/permissions/create-permission', [PermissionController::class, 'store']);
//      Route::get('admin/permissions/show-permission/{permission_id}', [PermissionController::class, 'show']);
//      Route::get('admin/permissions/edit/{permission_id}', [PermissionController::class, 'edit']);
//      Route::put('admin/permissions/update/{permission_id}', [PermissionController::class, 'update']);
//      Route::get('admin/permissions/delete/{permission_id}', [PermissionController::class, 'destroy']);
    
// });
