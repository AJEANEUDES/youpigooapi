<!--=============== FOOTER ===============-->
<footer class="footer section">
    <div class="footer__container container grid">
        <!--FOOTER CONTENT 1-->
        <div class="footer__content">
            <a href="javascript:void(0)" class="footer__logo">
                <i class="bx bxs-car footer__logo-icon"></i>Youpigoo
            </a>
            <p class="footer__description">Plateforme de Reservation .</p>
            <div class="footer__social">
                <a href="javascript:void(0)" class="footer__social-link">
                    <i class="bx bxl-facebook"></i>
                </a>
                <a href="javascript:void(0)" class="footer__social-link">
                    <i class="bx bxl-instagram-alt"></i>
                </a>
                <a href="javascript:void(0)" class="footer__social-link">
                    <i class="bx bxl-twitter"></i>
                </a>
            </div>
        </div>

        <!--FOOTER CONTENT 2-->
        <div class="footer__content">
            <h3 class="footer__title">Informations</h3>

            <ul class="footer__links">
                <li><a href="{{ url('inscription.view') }}" class="footer__link">S'inscrire</a></li>
                <li><a href="{{ url('login') }}" class="footer__link">Se connecter</a></li>
            </ul>
        </div>

        <!--FOOTER CONTENT 3-->
        <div class="footer__content">
            <h3 class="footer__title">Nos services</h3>

            <ul class="footer__links">
                <li><a href="{{ url('reserver.chambre.get') }}" class="footer__link">Reserver une chambre</a></li>
                <li><a href="javascript:void(0)" class="footer__link">Conditions générales d'utilisation</a></li>
            </ul>
        </div>

        <!--FOOTER CONTENT 4-->
        {{-- <div class="footer__content">
            <h3 class="footer__title">Notre compagnie</h3>

            <ul class="footer__links">
                <li><a href="{{ route('contactez.nous.get') }}" class="footer__link">Nous contactez</a></li>
                <li><a href="{{ route('about.get') }}" class="footer__link">À propos de nous</a></li>
            </ul>
        </div> --}}

        
    </div>
    <span class="footer__copy">&#169; Youpigoo. Tous les droits sont réservés</span>
</footer>