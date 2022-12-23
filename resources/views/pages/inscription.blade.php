@extends('layouts.app')
@section('title')
    Inscription :: Youpigoo
@endsection
@section('content')
    <!--=============== CONTACT US ===============-->
    <section class="newsletter section">
        <div class="newsletter__container container">
            <h2 class="section__title">Inscription</h2>
            <p class="newsletter__description">
                Veuillez renseigner ces champs pour creer votre compte
            </p>

            <form action="{{ route('inscriptions.clients') }}" method="post" class="contact__form grid"
                id="inscriptions-clients">
                @csrf
                <div class="contact__inputs grid">
                    <div class="contact__content">
                        <label for="nom_user" class="contact__label">Nom</label>
                        <input type="text" name="nom_user" id="nom_user" class="contact__input" />
                    </div>
                    <div class="contact__content">
                        <label for="prenoms_user" class="contact__label">Prenoms</label>
                        <input type="text" name="prenoms_user" id="prenoms_user" class="contact__input" />
                    </div>
                </div>

                <div class="contact__inputs grid">
                    <div class="contact__content">
                        <label for="email_user" class="contact__label">Email</label>
                        <input type="text" name="email_user" id="email_user" class="contact__input" />
                    </div>
                    <div class="contact__content">
                        <label for="telephone_user" class="contact__label">Telephone</label>
                        <input type="text" name="telephone_user" id="telephone_user" class="contact__input" />
                    </div>
                </div>

                <div class="contact__inputs grid">
                    <div class="contact__content">
                        <label for="pays_user" class="contact__label">Pays</label>
                        <select name="pays_user" id="pays_user" class="contact__input">
                            <option value="Bénin">Bénin</option>
                            <option value="Burkina Faso">Burkina Faso</option>
                            <option value="Guinée">Guinée</option>
                            <option value="Mali">Mali</option>
                            <option value="Niger">Niger</option>
                            <option value="Sénégal">Sénégal</option>
                            <option value="Togo">Togo</option>
                        </select>
                        <input type="hidden" name="prefix_user" id="prefix_user" value="">
                    </div>

                   



                    <div class="contact__content">
                        <label for="adresse_user" class="contact__label">Adresse de residence</label>
                        <input type="text" name="adresse_user" id="adresse_user" class="contact__input" />
                    </div>
                </div>

                
                <div class="contact__content">
                    <label for="roles_user" class="contact__label">Type de Compte</label>

                    <select name="roles_user" id="roles_user" class="contact__input">
                        <option value="Hotel">Hotel</option>
                        <option value="Compagnie">Compagnie</option>
                        <option value="Client">Client</option>
                    </select>
                </div>



                <div class="contact__inputs grid">
                    <div class="contact__content">
                        <label for="password" class="contact__label">Mot de passe</label>
                        <input type="password" name="password" id="password" class="contact__input" />
                    </div>
                    <div class="contact__content">
                        <label for="confirmation_password" class="contact__label">Retaper votre mot de passe</label>
                        <input type="password" name="confirmation_password" id="confirmation_password"
                            class="contact__input" />
                    </div>
                </div>

                <div>
                    <button type="submit" class="button">S'inscrire</button>
                </div>
                <div class="signup">Avez-vous un compte? <a href="{{ route('login') }}">Connectez-vous</a></div>
            </form>
        </div>
    </section>
@endsection
@push('scripts-inscription-check-countries')
    <script>
        $(document).ready(function() {
            $("#pays_user").on("change", function() {
                let pays_user = $("#pays_user").val()
                switch (pays_user) {
                    case "Bénin":
                        $("#prefix_user").val(229)
                        break;
                    case "Burkina Faso":
                        $("#prefix_user").val(226)
                        break;
                    case "Guinée":
                        $("#prefix_user").val(224)
                        break;
                    case "Mali":
                        $("#prefix_user").val(223)
                        break;
                    case "Niger":
                        $("#prefix_user").val(227)
                        break;
                    case "Sénégal":
                        $("#prefix_user").val(221)
                        break;
                    case "Togo":
                        $("#prefix_user").val(228)
                        break;
                }
            })
        })
    </script>
@endpush
