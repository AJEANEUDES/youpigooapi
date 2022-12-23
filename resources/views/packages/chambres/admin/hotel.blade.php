@extends('themes.master')
@section('title')
    Hotels :: Youpigoo
@endsection
@section('content')
    <div class="px-4 container-fluid ">
        <div class="card">

            <div class="card-header">
                <h4>Hotels <a href="{{ url('Admin/hotels/ajouter-hotel') }}" class="btn btn-primary btn-sm float-end">
                        Ajouter un Hotel</a> </h4>
            </div>

            <div class="card-body">
                @if (session('message'))
                    <div class="alert alert-success">{{ session('message') }} </div>
                @endif

                <table id="myDataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">ID HOTEL </th>
                            <th scope="col">NOM HOTEL</th>
                            <th scope="col">EMAIL HOTEL </th>
                            <th scope="col">NOM VILLE</th>
                            <th scope="col">NOM TYPE HEBERGEMENT</th>
                            <th scope="col">NOM PAYS</th>
                            <th scope="col">NOM USER</th>
                            <th scope="col">IMAGE HOTEL</th>
                            <th scope="col">ROLE </th>
                            <th scope="col">STATUS  </th>
                            <th scope="col">ACTIONS</th>
                            <th scope="col">DATE & HEURE</th>
                        </tr>
                    </thead>


                    <tbody>
                        @foreach ($hotels as $hotel)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                 
                                    <td>{{ $hotel->nom_hotel }}</td>
                                    <td>{{ $hotel->email_hotel }}</td>
                                    <td>{{ $hotel->nom_ville }}</td>
                                    <td>{{ $hotel->nom_typehebergement }}</td>
                                    <td>{{ $hotel->nom_pays }}</td>
                                    <td>{{ $hotel->nom_user }} {{ $hotel->prenoms_user }}</td>
                                    <td>    
                                        <img src="{{ asset('storage/uploads/' . $hotel->image_hotel) }}" 
                                        width="50px" height="50px" alt="">
                                    </td>
                                   
                                    <td>
                                        @if ($hotel->roles_user)
                                            <span class="badge bg-success">Hotel</span>
                                        @elseif ($hotel->roles_user)
                                            <span class="badge bg-danger">Admin</span>
                                        @elseif ($hotel->roles_user)
                                            <span class="badge bg-warning">Compagnie</span>
                                        @else
                                            <span class="badge bg-info">Client</span>
                                        @endif
                                    </td>

                                  
                                    <td>
                                        @if ($hotel->status_hotel)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>



                                    <td>{{ $hotel->created_at}}</td>


                                    <td>
                                        <a href="{{ url('Admin/hotels/editer-hotel/' . $hotel->id_hotel) }}"
                                            class="btn btn-success"><i class="fa fa-edit"></i></a>


                                        <form method="GET"
                                            action="{{ url('Admin/hotels/delete-hotel/' . $hotel->id_hotel) }}"
                                            class="btn btn-danger"
                                            onsubmit="return confirm('Voulez-vous supprimer cet hotel de la liste?');">
                                            <input type="hidden" name="id_hotel" value="{{ $hotel->id_hotel }}">
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
