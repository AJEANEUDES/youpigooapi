@extends('layouts.app')


@section('title')
    Se connecter ::Youpigoo
@endsection


@section('content')
    <!--=============== LOGIN ===============-->

    {{-- <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <div class="text-center mb-3">
                                        <img src="images/logo-full.png" alt="">
                                    </div>
                                    <h4 class="text-center mb-4">Authentifiez-vous</h4>
                                    <p class="newsletter__description">
                                        Entrer votre adresse mail et mot de passe pour se connecter
                                    </p>

                                    <form action="{{ route('login') }}" method="post" id="login"
                                        class="login__form grid">
                                        @csrf


                                        <div class="form-group">
                                            <label class="mb-1"><strong>Email</strong></label>
                                            <input type="email"  name = "eamil" class="form-control" placeholder="hello@example.com">
                                        </div>
                                        <div class="form-group">
                                            <label class="mb-1"><strong>Mot de passe </strong></label>
                                            <input type="password" name="password" class="form-control" placeholder="Password">
                                        </div>
                                        <div class="form-row d-flex justify-content-between mt-4 mb-2">
                                           
                                            <div class="form-group">
                                                <a href="{{ route('reinitialiser.mon-de-passe.get') }}">Mot de passe oublié ?</a>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
                                        </div>
                                    </form>
                                    <div class="new-account mt-3">
                                        <p>Vous n'avez pas de compte? <a class="text-primary" href="{{ route('inscription.view') }}">Creer un compte</a></p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}



    <section class="newsletter section">
        <div class="newsletter__container container">
            <h2 class="section__title">Authentifiez-vous</h2>
            <p class="newsletter__description">
                Entrer votre adresse mail et mot de passe pour se connecter
            </p>
    
            <form action="{{ route('login') }}" method="post" id="login" class="login__form grid">
                @csrf
                
                <div class="login__content">
                    <label for="email" class="login__label">Email</label>
                    <input type="email" name="email" class="login__input">
                </div>
    
                <div class="login__content">
                    <label for="password" class="login__label">Mot de passe</label>
                    <input type="password" name="password" class="login__input">
                </div>
    
                <div>
                    <button type="submit" class="button" style="margin-right: 25px;">Se connecter</button>
                     ** <a href="{{ route('reinitialiser.mon-de-passe.get') }}">Mot de passe oublié ?</a> **
                </div>
    
                <div class="signup">Avez-vous un compte???? <a href="{{ route('inscription.view') }}">Creer un compte</a></div>
            </form>
        </div>
    </section>


{{-- 
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Connexion') }}</div>
    
                    <div class="card-body">
                        <form method="POST" action="{{ url('/inscription') }}" id="login" class="login__form grid">
                            @csrf
    
                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Adresse Email') }}</label>
    
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
    
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Mot de Passe') }}</label>
    
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
    
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
    
                                        <label class="form-check-label" for="remember">
                                            {{ __('Se rappeler de moi') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
    
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Se Connecter') }}
                                    </button>
    
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Vous avez oublié votre mot de passe?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
