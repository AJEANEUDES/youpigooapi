<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $permissions = [
            //roles
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            //users

            'user-list',
            'user-create',
            'user-edit',
            'user-delete',

            //hotel

            'hotel-list',
            'hotel-create',
            'hotel-edit',
            'hotel-delete',


            //chambre

            'chambre-list',
            'chambre-create',
            'chambre-edit',
            'chambre-delete',


            //compagnie

            'compagnie-list',
            'compagnie-create',
            'compagnie-edit',
            'compagnie-delete',

            //bus

            'bus-list',
            'bus-create',
            'bus-edit',
            'bus-delete',


            //trajet

            'trajet-list',
            'trajet-create',
            'trajet-edit',
            'trajet-delete',


            //client

            'client-list',
            'client-create',
            'client-edit',
            'client-delete',


            //modePayement

            'modePayement-list',
            'modePayement-create',
            'modePayement-edit',
            'modePayement-delete',


            //reglement

            'reglement-list',
            'reglement-create',
            'reglement-edit',
            'reglement-delete',


            //facture

            'facture-list',
            'facture-create',
            'facture-edit',
            'facture-delete',


            //reservation_hotel

            'reservation_hotel-list',
            'reservation_hotel-create',
            'reservation_hotel-edit',
            'reservation_hotel-delete',


            //reservation_bus

            'reservation_bus-list',
            'reservation_bus-create',
            'reservation_bus-edit',
            'reservation_bus-delete',


            //voyage

            'voyage-list',
            'voyage-create',
            'voyage-edit',
            'voyage-delete',


            //service

            'service-list',
            'service-create',
            'service-edit',
            'service-delete',


            //temoignage

            'temoignage-list',
            'temoignage-create',
            'temoignage-edit',
            'temoignage-delete',


            //categorie_hotel

            'categorie_hotel-list',
            'categorie_hotel-create',
            'categorie_hotel-edit',
            'categorie_hotel-delete',


            //categorie_article

            'categorie_article-list',
            'categorie_article-create',
            'categorie_article-edit',
            'categorie_article-delete',


            //article

            'article-list',
            'article-create',
            'article-edit',
            'article-delete',


            //evenement

            'evenement-list',
            'evenement-create',
            'evenement-edit',
            'evenement-delete',

            

        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
