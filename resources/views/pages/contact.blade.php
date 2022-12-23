@extends('layouts.app')
@section('title')
Contactez-nous :: Youpigoo
@endsection
@section('content')
<!--=============== CONTACT US ===============-->
<section class="contact section container">
    <h2 class="breadcrumb__title">Contactez-nous</h2>
    <h3 class="breadcrumb__subtitle">Accueil > <span>Contactez-nous</span></h3>

    <div class="contact__container grid">
        <div>
            <div class="contact__information">
                <i class="bx bx-phone contact__icon"></i>
                <div>
                    <h3 class="contact__title">Appelez Nous</h3>
                    <span class="contact__subtitle">999-777-666</span>
                </div>
            </div>

            <div class="contact__information">
                <i class="bx bx-envelope contact__icon"></i>
                <div>
                    <h3 class="contact__title">Email</h3>
                    <span class="contact__subtitle">contact@youpigoo.com</span>
                </div>
            </div>

            <div class="contact__information">
                <i class="bx bx-map contact__icon"></i>
                <div>
                    <h3 class="contact__title">Location</h3>
                    <span class="contact__subtitle">Lome - Togo, Adidoadin, Carrefour Bodjona</span>
                </div>
            </div>
        </div>

        <form action="{{ route('contacts.sends.mails') }}" method="post" class="contact__form grid" id="contacts-sends-mails">
            @csrf
            <div class="contact__inputs grid">
                <div class="contact__content">
                    <label for="nom" class="contact__label">Nom</label>
                    <input type="text" class="contact__input" name="nom"/>
                </div>
                <div class="contact__content">
                    <label for="text" class="contact__label">Email</label>
                    <input type="email" class="contact__input" name="email"/>
                </div>
            </div>

            <div class="contact__content">
                <label for="sujet" class="contact__label">Sujet</label>
                <input type="text" class="contact__input" name="sujet"/>
            </div>

            <div class="contact__content">
                <label for="message" class="contact__label">Message</label>
                <textarea name="message" id="message" cols="0" rows="7" class="contact__input"></textarea>
            </div>

            <div>
                <button type="submit" class="button">Envoyer le message</button>
            </div>
        </form>
    </div>
</section>
@endsection 