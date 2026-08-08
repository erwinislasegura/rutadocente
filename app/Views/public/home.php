<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="Recursos y acompañamiento para docentes de Chile">
    <title>Inicio | Ruta Docente 2026</title>
    <link rel="stylesheet" href="<?=url('/assets/css/site.css?v=20260808-legibilidad1')?>">
    <link rel="icon" href="<?=url('/assets/img/logo-ruta-docente.png')?>">
</head>
<body>
<div class="topbar">
    <div class="container">
        <span>Apoyo docente para todo Chile</span>
        <div>
            <a href="mailto:aulaentretenida0@gmail.com">✉ aulaentretenida0@gmail.com</a>
            <a href="tel:+56975778434">☎ +56 9 7577 8434</a>
        </div>
    </div>
</div>

<header>
    <div class="container header-inner">
        <a class="brand" href="<?=url('/')?>">
            <img src="<?=url('/assets/img/logo-ruta-docente.png')?>" alt="Logo Ruta Docente">
            <div>
                <strong>Ruta Docente</strong>
                <small>Portafolio Docente 2026</small>
            </div>
        </a>
        <button class="menu" aria-label="Abrir menú" aria-expanded="false">☰</button>
        <nav>
            <a href="<?=url('/')?>">Inicio</a>
            <a href="<?=url('/asignaturas')?>">Asignaturas</a>
            <a href="<?=url('/portafolio')?>">Portafolio</a>
            <a href="<?=url('/clases-asincronicas')?>">Clases asincrónicas</a>
            <a href="<?=url('/tests')?>">Tus test</a>
            <a href="<?=url('/tabuladores')?>">Tabuladores</a>
            <a href="<?=url('/recursos')?>">Recursos</a>
            <a href="<?=url('/inscripcion')?>">Inscripción</a>
            <a href="<?=url('/contacto')?>">Contacto</a>
            <a href="<?=url('/preguntas-frecuentes')?>">Preguntas frecuentes</a>
        </nav>
        <a class="pill header-cta" href="<?=url('/login')?>">Acceso docente</a>
    </div>
</header>

<main>
    <section class="home-hero">
        <div class="container hero-grid">
            <div class="hero-copy">
                <span class="eyebrow">PORTAFOLIO DOCENTE 2026</span>
                <h1>Avanza con claridad en tu <em>ruta docente.</em></h1>
                <p>Recursos, herramientas inteligentes y acompañamiento práctico para transformar tu experiencia pedagógica.</p>
                <div class="actions">
                    <a class="btn" href="<?=url('/portafolio')?>">Explorar portafolio →</a>
                    <a class="text-link" href="<?=url('/recursos')?>">Ver recursos gratuitos ↗</a>
                </div>
                <div class="trust">
                    <div><b>+500</b><span>docentes apoyados</span></div>
                    <div><b>100%</b><span>contenido práctico</span></div>
                    <div><b>24/7</b><span>acceso a recursos</span></div>
                </div>
            </div>
            <div class="hero-visual">
                <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1600&q=85" alt="Docente guiando a estudiantes">
                <div class="float-card">
                    <span>✓</span>
                    <div><b>Aprende a tu ritmo</b><small>Material claro y actualizado</small></div>
                </div>
            </div>
        </div>
    </section>

    <section class="tests-promo" aria-labelledby="tests-promo-title">
        <div class="container tests-promo-inner">
            <div class="tests-promo-icon" aria-hidden="true">✓</div>
            <div class="tests-promo-copy">
                <span>TESTS RUTA DOCENTE</span>
                <h2 id="tests-promo-title">Practica y fortalece tu preparación docente.</h2>
                <p>Accede a evaluaciones y actividades organizadas según tu asignatura desde tu espacio personal.</p>
            </div>
            <ul class="tests-promo-features" aria-label="Características de los tests">
                <li>Por asignatura</li>
                <li>Acceso personalizado</li>
                <li>Disponibles 24/7</li>
            </ul>
            <div class="tests-promo-actions">
                <a href="<?=url('/login')?>">Ver mis tests <span>→</span></a>
                <small>¿Aún no tienes acceso? <a href="<?=url('/inscripcion')?>">Inscríbete aquí</a></small>
            </div>
        </div>
    </section>

    <section class="section" id="contenido">
        <div class="container">
            <div class="intro">
                <span class="eyebrow">UNA RUTA COMPLETA</span>
                <h2>Todo lo que necesitas para crecer profesionalmente.</h2>
                <p>Ruta Docente reúne recursos para Educación Parvularia, Matemática, Historia, Inglés, Portafolio Docente, clases asincrónicas, tabulación de resultados y tests organizados por asignatura.</p>
            </div>
            <div class="cards">
                <article class="card">
                    <span class="icon">⌁</span>
                    <h3>Portafolio Docente</h3>
                    <p>Orientaciones, ejemplos y materiales para construir evidencias con sentido.</p>
                    <a class="static-card-link" href="<?=url('/contacto')?>">Conocer más →</a>
                </article>
                <article class="card">
                    <span class="icon">▶</span>
                    <h3>Clases asincrónicas</h3>
                    <p>Contenidos en video para aprender donde estés y avanzar a tu propio ritmo.</p>
                    <a class="static-card-link" href="<?=url('/contacto')?>">Conocer más →</a>
                </article>
                <article class="card">
                    <span class="icon">✓</span>
                    <h3>Tests docentes</h3>
                    <p>Evaluaciones para practicar, reconocer avances y fortalecer tu preparación.</p>
                    <a class="static-card-link" href="<?=url('/tests')?>">Explorar tests →</a>
                </article>
                <article class="card">
                    <span class="icon">▦</span>
                    <h3>Tabuladores</h3>
                    <p>Organiza resultados y visualiza avances de forma clara y accionable.</p>
                    <a class="static-card-link" href="<?=url('/contacto')?>">Conocer más →</a>
                </article>
            </div>
        </div>
    </section>

    <section class="split-section">
        <div class="container rich-grid">
            <img src="https://images.unsplash.com/photo-1544717305-2782549b5136?auto=format&fit=crop&w=1000&q=85" alt="Docente preparando recursos">
            <div class="rich-text">
                <span class="eyebrow">APRENDIZAJE SIGNIFICATIVO</span>
                <h2>Recursos creados para los desafíos reales del aula.</h2>
                <p>Creemos en una enseñanza cercana, reflexiva y capaz de reconocer la diversidad. Por eso proponemos materiales fáciles de adaptar, estrategias activas y herramientas que permiten poner el foco en el aprendizaje.</p>
                <div class="fact-row">
                    <div class="fact"><b>Claridad</b><span>Contenidos ordenados y directos.</span></div>
                    <div class="fact"><b>Flexibilidad</b><span>Avanza según tu tiempo.</span></div>
                    <div class="fact"><b>Impacto</b><span>Ideas listas para aplicar.</span></div>
                </div>
            </div>
        </div>
    </section>

    <section class="activaweb-credit" aria-labelledby="activaweb-title">
        <div class="container activaweb-credit-inner">
            <div class="activaweb-copy">
                <span class="activaweb-kicker">COLABORADOR TECNOLÓGICO</span>
                <h2 id="activaweb-title">Colaborador y desarrollado por <strong>Activa Web</strong></h2>
                <p>Soluciones web escalables que respaldan una experiencia digital clara, segura y preparada para crecer.</p>
            </div>
            <a class="activaweb-logo" href="https://activa-web.cl/" target="_blank" rel="noopener" aria-label="Visitar Activa Web">
                <img src="<?=url('/assets/img/activaweb-logo.png')?>" alt="Activa Web, soluciones web escalables para empresas">
            </a>
            <a class="activaweb-link" href="https://activa-web.cl/" target="_blank" rel="noopener">Conocer Activa Web <span>↗</span></a>
        </div>
    </section>
</main>

<a class="whatsapp" href="https://wa.me/56975778434" aria-label="WhatsApp">◉</a>

<footer>
    <div class="container footer-grid">
        <div>
            <a class="brand light" href="<?=url('/')?>">
                <img src="<?=url('/assets/img/logo-ruta-docente.png')?>" alt="Ruta Docente">
                <div><strong>Ruta Docente</strong><small>Enseñar, avanzar, transformar.</small></div>
            </a>
            <p>Recursos claros y acompañamiento cercano para fortalecer tu práctica, tu portafolio y el aprendizaje de tus estudiantes.</p>
        </div>
        <div>
            <h3>Explora</h3>
            <a href="<?=url('/asignaturas')?>">Asignaturas</a>
            <a href="<?=url('/portafolio')?>">Portafolio</a>
            <a href="<?=url('/clases-asincronicas')?>">Clases asincrónicas</a>
            <a href="<?=url('/tests')?>">Tus test</a>
        </div>
        <div>
            <h3>Ayuda</h3>
            <a href="<?=url('/recursos')?>">Recursos</a>
            <a href="<?=url('/inscripcion')?>">Inscripción</a>
            <a href="<?=url('/preguntas-frecuentes')?>">Preguntas frecuentes</a>
            <a href="<?=url('/contacto')?>">Contacto</a>
        </div>
        <div>
            <h3>Conversemos</h3>
            <a href="mailto:aulaentretenida0@gmail.com">aulaentretenida0@gmail.com</a>
            <a href="tel:+56975778434">+56 9 7577 8434</a>
            <a href="https://www.facebook.com/AulaEntretenida">Facebook · Aula Entretenida</a>
        </div>
    </div>
    <div class="container copyright">
        <span>© 2026 Ruta Docente. Todos los derechos reservados.</span>
        <span>Hecho con dedicación para docentes de Chile 🇨🇱</span>
    </div>
</footer>

<script src="<?=url('/assets/js/site-public.js')?>"></script>
</body>
</html>
