@extends('layouts.master')

@section('title', 'Tableau d\'Administration de Youpigoo -- Gestion des Permissions')


@section('content')


    <div class="card">
        
        <div class="card-header d-sm-flex d-block">
            <div class="mr-auto mb-sm-0 mb-3">
                <h4 class="card-title mb-2">Liste des Permissions</h4>
                <span>Liste des Permissions</span>
            </div>
            <a href="#" class="btn btn-info light mr-3"><i class="las la-download scale3 mr-2"></i>Importer en Csv</a>


            @can('role-create')
                <a href="{{ url('admin/roles/create-permission') }}" class="btn btn-info">+ Créer un Nouvelle Permission</a>
            @endcan

        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif



        <div class="card-body">
            <div class="table-responsive">
                <table class="table style-1" id="example">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($permissions as $key => $permission)
                            <tr>
                                <td>
                                    <h6>{{ ++$i }}</h6>
                                </td>

                                <td>
                                    <div>
                                        <h6>
                                            <a href="{{ url('admin/permissions/show-permission', $permission->id) }}">
                                                {{ $permission->name }}
                                            </a>
                                        </h6>
                                    </div>
                                </td>

                                <td>
                                    <div class="d-flex action-button">
                                        @can('permission-edit')
                                            <a href="{{ url('admin/permissions/edit', $permission->id) }}"
                                                class="btn btn-info btn-xs light px-2">
                                                <svg width="20" height="20" viewbox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M17 3C17.2626 2.73735 17.5744 2.52901 17.9176 2.38687C18.2608 2.24473 18.6286 2.17157 19 2.17157C19.3714 2.17157 19.7392 2.24473 20.0824 2.38687C20.4256 2.52901 20.7374 2.73735 21 3C21.2626 3.26264 21.471 3.57444 21.6131 3.9176C21.7553 4.26077 21.8284 4.62856 21.8284 5C21.8284 5.37143 21.7553 5.73923 21.6131 6.08239C21.471 6.42555 21.2626 6.73735 21 7L7.5 20.5L2 22L3.5 16.5L17 3Z"
                                                        stroke="#fff" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                </svg>
                                            </a>
                                        @endcan



                                        @can('permission-delete')
                                            <div class="bootstrap-modal">

                                                <!-- Button trigger modal -->
                                                <button type="button" class="btn btn-danger btn-xs light px-2"
                                                    data-toggle="modal" data-target="#basicModal"><svg width="20"
                                                        height="20" viewbox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M3 6H5H21" stroke="#fff" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                        </path>
                                                        <path
                                                            d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z"
                                                            stroke="#fff" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                        </path>
                                                    </svg></button>
                                                <!-- Modal -->
                                                <div class="modal fade" id="basicModal">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Supprimer la permission</h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal"><span>&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">

                                                                <p style="text-align: center">Voulez-vous Supprimer
                                                                    la permission "{{ $permission->name }}" <br>
                                                                    de la
                                                                    liste ?</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger light"
                                                                    data-dismiss="modal">Annuler
                                                                </button>

                                                                <a href="{{ url('admin/permissions/delete', $permission->id) }}"
                                                                    class="btn btn-danger btn-xs light px-2">
                                                                    <svg width="20" height="20" viewbox="0 0 24 24"
                                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M3 6H5H21" stroke="#fff" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round">
                                                                        </path>
                                                                        <path
                                                                            d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z"
                                                                            stroke="#fff" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round">
                                                                        </path>
                                                                    </svg>
                                                                </a>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>



                                            </div>
                                        @endcan


                                    </div>
                                </td>
                            </tr>
                        @endforeach


                    </tbody>
                </table>

            </div>
        </div>
    </div>

@endsection
