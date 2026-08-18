<?php
$currentPublicPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
?>
<div class="topbar">
    <div class="container">
        <span class="topbar-message"><i></i>Acompañamiento docente para todo Chile</span>
        <div class="topbar-contact">
            <a href="mailto:aulaentretenida0@gmail.com">✉ aulaentretenida0@gmail.com</a>
            <a href="tel:+56975778434">☎ +56 9 7577 8434</a>
        </div>
    </div>
</div>
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="<?=url('/')?>" aria-label="Ruta Docente, inicio">
            <img src="<?=url('/assets/img/logo-ruta-docente.png')?>" alt="Logo Ruta Docente" width="190" height="84">
            <div><strong>Ruta Docente</strong><small>Portafolio Docente 2026</small></div>
        </a>
        <button class="menu" type="button" aria-label="Abrir menú" aria-expanded="false" aria-controls="public-navigation">
            <span></span><span></span><span></span>
        </button>
        <nav class="site-nav" id="public-navigation" aria-label="Navegación principal">
            <a href="<?=url('/')?>">Inicio</a>
            <a href="<?=url('/portafolio')?>">Portafolio</a>
            <a href="<?=url('/inscripcion')?>">Talleres</a>
            <a href="<?=url('/asignaturas')?>">Asignaturas</a>
            <a class="nav-classes-link" href="<?=url('/clases-asincronicas')?>">
                <span class="nav-label-wide">Clases asincrónicas</span>
                <span class="nav-label-compact">Clases</span>
            </a>
            <a href="<?=url('/tests')?>">Tests</a>
            <a href="<?=url('/recursos')?>">Recursos</a>
            <a href="<?=url('/contacto')?>">Contacto</a>
            <div class="nav-dropdown">
                <button class="nav-dropdown-toggle" type="button" aria-expanded="false">Más <span aria-hidden="true">⌄</span></button>
                <div class="nav-dropdown-menu">
                    <a href="<?=url('/talleres-asincronicos')?>"><b>Talleres asincrónicos</b><small>Herramientas de apoyo docente</small></a>
                    <a href="<?=url('/preguntas-frecuentes')?>"><b>Preguntas frecuentes</b><small>Resuelve tus dudas rápidamente</small></a>
                </div>
            </div>
        </nav>
        <a class="pill header-cta" href="<?=url('/login')?>">Acceso docente <span>→</span></a>
    </div>
</header>
