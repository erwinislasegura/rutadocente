<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="Recursos y acompañamiento para docentes de Chile">
    <title>Inicio | Ruta Docente 2026</title>
    <link rel="stylesheet" href="<?=url('/assets/css/site.css?v=20260818-public-v5')?>">
    <link rel="icon" href="<?=url('/assets/img/logo-ruta-docente.png')?>">
</head>
<body>
<?php require __DIR__.'/_header.php'; ?>

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
                <aside class="hero-quality-seal" aria-label="Un proyecto más de Aula Entretenida">
                    <span>UN PROYECTO MÁS DE</span>
                    <img src="<?=url('/assets/img/aula-entretenida-sello.webp')?>" alt="Sello de calidad Aula Entretenida" width="480" height="480">
                </aside>
                <div class="float-card">
                    <span>✓</span>
                    <div><b>Aprende a tu ritmo</b><small>Material claro y actualizado</small></div>
                </div>
            </div>
        </div>
    </section>

    <section class="access-hub" aria-labelledby="access-hub-title">
        <div class="container">
            <div class="access-hub-heading">
                <div><span>ACCESOS RUTA DOCENTE</span><h2 id="access-hub-title">Elige cómo quieres avanzar.</h2></div>
                <p>Ingresa a tus herramientas personales o reserva tu cupo en nuestros talleres.</p>
            </div>
            <div class="access-hub-grid">
                <article class="access-card access-card-tests">
                    <span class="access-card-icon" aria-hidden="true">✓</span>
                    <div><small>EVALUACIÓN Y PRÁCTICA</small><h3>Tests por asignatura</h3><p>Practica con evaluaciones organizadas para tu área y revisa tus recursos disponibles.</p></div>
                    <a href="<?=url('/login')?>">Acceder a mis tests <span>→</span></a>
                </article>
                <article class="access-card access-card-tabs">
                    <span class="access-card-icon" aria-hidden="true">▦</span>
                    <div><small>ANÁLISIS PEDAGÓGICO</small><h3>Talleres asincrónicos</h3><p>Descarga plantillas o utiliza herramientas en línea para analizar tus resultados.</p></div>
                    <a href="<?=url('/login')?>">Abrir mis talleres asincrónicos <span>→</span></a>
                </article>
                <article class="access-card access-card-workshops">
                    <span class="access-card-icon" aria-hidden="true">★</span>
                    <div><small>FORMACIÓN DOCENTE</small><h3>Comprar talleres</h3><p>Conoce los talleres disponibles, revisa sus detalles y completa tu inscripción.</p></div>
                    <a href="<?=url('/inscripcion')?>">Ver talleres e inscribirme <span>→</span></a>
                </article>
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
                    <h3>Talleres asincrónicos</h3>
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

    <section class="activaweb-credit" aria-labelledby="gocreative-title">
        <div class="container activaweb-credit-inner">
            <div class="activaweb-copy">
                <span class="activaweb-kicker">COLABORADOR TECNOLÓGICO</span>
                <h2 id="gocreative-title">Colaborador tecnológico y desarrollado por <strong>GoCreative</strong></h2>
                <p>Soluciones web escalables que respaldan una experiencia digital clara, segura y preparada para crecer.</p>
            </div>
            <a class="activaweb-logo" href="https://gocreative.cl/" target="_blank" rel="noopener" aria-label="Visitar GoCreative">
                <img src="<?=url('/assets/img/gocreative-logo.png')?>" alt="GoCreative, diseño y desarrollo digital">
            </a>
            <a class="activaweb-link" href="https://gocreative.cl/" target="_blank" rel="noopener">Conocer GoCreative <span>↗</span></a>
        </div>
    </section>
</main>

<?php require __DIR__.'/_footer.php'; ?>

<script src="<?=url('/assets/js/site-public.js')?>"></script>
</body>
</html>
