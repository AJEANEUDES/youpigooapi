@extends('themes.master')
@section('title')
    Administrateurs :: Youpigoo
@endsection
@section('content')
    <div class="px-4 container-fluid ">
        <div class="card">

            <div class="card-header">
                <h4>Administrateurs <a href="{{ url('Admin/administrateur/ajouter-admin') }}"
                        class="btn btn-primary btn-sm float-end">
                        Ajouter un Administrateur</a> </h4>
            </div>

            <div class="card-body">
                @if (session('message'))
                    <div class="alert alert-success">{{ session('message') }} </div>
                @endif

                <table id="myDataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">NOM</th>
                            <th scope="col">PRENOMS</th>
                            <th scope="col">EMAIL</th>
                            <th scope="col">IMAGE</th>
                            <th scope="col">ROLE</th>
                            <th scope="col">STATUS</th>
                            <th scope="col">DATE & HEURE</th>
                            <th scope="col">ACTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($admins as $admin)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>

                                <td>{{ $admin->nom_user }}</td>
                                <td>{{ $admin->prenoms_user }}</td>
                                <td>{{ $admin->email_user }}</td>
                                <td>
                                    <img src="{{ asset('storage/uploads/' . $admin->image_user) }}" 
                                    width="50px" height="50px" alt="">
                                </td>
                               
                                <td>
                                    @if ($admin->roles_user)
                                        <span class="badge bg-success">Admin</span>
                                    @elseif ($admin->roles_user)
                                        <span class="badge bg-danger">Hotel</span>
                                    @elseif ($admin->roles_user)
                                        <span class="badge bg-warning">Compagnie</span>
                                    @else
                                        <span class="badge bg-info">Client</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($admin->status_user)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>

                                <td>{{ $admin->created_at->format('d/m/Y à H:i:s') }}</td>


                                <td>
                                    <a href="{{ url('Admin/administrateur/editer-admin/' . $admin->id) }}"
                                        class="btn btn-success"><i class="fa fa-edit"></i></a>
                                
                                        
                                        <form method="GET" action="{{ url('Admin/administrateur/delete-admin/' . $admin->id) }}"
                                            class="btn btn-danger"
                                             onsubmit="return confirm('Voulez-vous supprimer cet Administrateur de la liste?');">
                                            <input type="hidden" name="id_admin" value="{{$admin->id}}">
                                            <button class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                        </form> 
                                        
                                </td>




                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    </div>





@endsection

    