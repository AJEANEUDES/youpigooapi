@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4>Détails des informations sur un rôle</h4>
                        <span>Voir le  Rôle</span>
                    </div>
                    <a href="{{ url('admin/roles') }}" class="btn btn-danger light">Retour</a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-4 col-sm-6-center">
            <div class="card user-card">
                <div class="card-body pb-0-center">

                    <p class="fs-12">
                    <h5 class="title"><a href="javascript:void(0);">{{ $role->name }}</a></h5>
                    </p>
                    <ul class="list-group list-group-flush">

                        <li class="list-group-item">
                            <span class="mb-0 title">Permissions</span> :
                            <span class="text-black desc-text ml-2">
                                @if (!empty($rolePermissions))
                                    @foreach ($rolePermissions as $v)
                                        <label class="label label-success">{{ $v->name }},</label>
                                    @endforeach
                                @endif
                            </span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
@endsection
