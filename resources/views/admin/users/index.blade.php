@extends('layouts.master')

@section('title', 'Tableau d\'Administration de Youpigoo -- Gestion des Utilisateurs')


@section('content')


    <div class="card">

        <div class="card-header d-sm-flex d-block">
            <div class="mr-auto mb-sm-0 mb-3">
                <h4 class="card-title mb-2">Liste des Utilisateurs</h4>
                <span>Liste des Utilisateurs</span>
            </div>

            <a href="#" class="btn btn-info light mr-3"><i class="las la-download scale3 mr-2"></i>Importer en
                Csv</a>

            @can('user-create')
                <a href="{{ url('admin/users/create-user') }}" class="btn btn-info  mr-3">+ Créer un Nouvel Utilisateur</a>
            @endcan



        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif


        <div class="card-body">

            <div class="table-responsive">
                <table id="example" class="table style-1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th></th>
                            <th>UTILISATEUR</th>
                            <th>EMAIL</th>
                            <th>PAYS</th>
                            <th>VILLE</th>
                            <th>ROLE</th>
                            <th>STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $user)
                            <tr>
                                <td>
                                    <h6>{{ ++$i }}</h6>
                                </td>
                                <td>
                                    <img class="rounded-circle" width="35"
                                        src="{{ asset('admin/images/profile/small/pic1.jpg') }}" alt="">
                                </td>

                                <td>
                                    <a href="{{ url('admin/users/show-user', $user->id) }}">

                                        {{ $user->name }}
                                    </a>

                                </td>
                                <td>{{ $user->email }}</td>
                                <td>

                                    {{ $user->pays }}
                                </td>
                                <td>
                                    {{ $user->ville }}
                                </td>



                                <td>
                                    @if (!empty($user->getRoleNames()))
                                        @foreach ($user->getRoleNames() as $v)
                                            {{ $v }}
                                        @endforeach
                                    @endif

                                </td>
                                <td><span class="badge badge-success">status</span></td>
                                <td>

                                    <div class="d-flex">
                                        @can('edit-users')
                                            <a href="{{ url('admin/users/edit', $user->id) }}"
                                                class="btn btn-primary shadow btn-xs sharp mr-1"><i
                                                    class="fa fa-pencil"></i></a>
                                        @endcan



                                        @can('delete-users')
                                            <div class="bootstrap-modal">

                                                <!-- Button trigger modal -->
                                                <button type="button" class="btn btn-danger shadow btn-xs sharp"
                                                    data-toggle="modal" data-target="#basicModal"><i
                                                        class="fa fa-trash"></i></button>
                                                <!-- Modal -->
                                                <div class="modal fade" id="basicModal">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Supprimer l'Utilisateur</h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal"><span>&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">

                                                                <p style="text-align: center">Voulez-vous Supprimer
                                                                    l'Utilisateur "{{ $user->name }}" <br>
                                                                    de la
                                                                    liste ?</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger light"
                                                                    data-dismiss="modal">Annuler
                                                                </button>

                                                                <a href="{{ url('admin/users/delete', $user->id) }}"
                                                                    class="btn btn-danger shadow btn-xs sharp">
                                                                    <i class="fa fa-trash"></i>
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
