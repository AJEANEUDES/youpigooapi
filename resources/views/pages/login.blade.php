@extends('layouts.app')
@section('title')
Se connecter :: Youpigoo
@endsection
@section('content')
<!--=============== NEWSLETTER ===============-->
<section class="newsletter section">
    <div class="newsletter__container container">
        <h2 class="section__title">Authentifiez-vous</h2>
        <p class="newsletter__description">
            Veuillez saisir votre adresse mail et mot de passe.
        </p>
        
        <form action="{{ route('login') }}" method="post" class="login__form grid">
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
                <button type="submit" class="button">Se connecter</button>
            </div>

            <div class="signup">Avez-vous un compte? <a href="{{ route('inscription.view') }}">Creer un compte</a></div>
        </form>
    </div>
</section>
@endsection