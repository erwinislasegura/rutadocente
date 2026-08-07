#!/usr/bin/env python3
from pathlib import Path
from textwrap import shorten

from PIL import Image as PILImage, ImageDraw, ImageFont
from reportlab.lib import colors
from reportlab.lib.enums import TA_CENTER, TA_LEFT
from reportlab.lib.pagesizes import A4
from reportlab.lib.styles import ParagraphStyle, getSampleStyleSheet
from reportlab.lib.units import mm
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont
from reportlab.platypus import (
    BaseDocTemplate,
    Frame,
    Image,
    KeepTogether,
    PageBreak,
    PageTemplate,
    Paragraph,
    Spacer,
    Table,
    TableStyle,
)


ROOT = Path(__file__).resolve().parents[1]
OUT = ROOT / "output" / "pdf"
TMP = ROOT / "tmp" / "pdfs"
LOGO = ROOT / "public" / "assets" / "img" / "logo-ruta-docente.png"
TEACHER_SCREENSHOT = ROOT.parent / "upload" / "image(20260807-224444).png"

NAVY = "#031E4C"
BLUE = "#0B5FBD"
GOLD = "#EDA915"
SKY = "#EAF3FB"
PALE_BLUE = "#F4F9FF"
PALE_GOLD = "#FFF7DF"
INK = "#173B63"
MUTED = "#61798E"
LINE = "#D7E3ED"
WHITE = "#FFFFFF"
GREEN = "#2C8767"
RED = "#B6515E"

FONT = "/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf"
FONT_BOLD = "/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf"
pdfmetrics.registerFont(TTFont("RutaSans", FONT))
pdfmetrics.registerFont(TTFont("RutaSans-Bold", FONT_BOLD))


def pil_font(size, bold=False):
    return ImageFont.truetype(FONT_BOLD if bold else FONT, size)


def text(draw, xy, value, size, fill=INK, bold=False, anchor=None):
    draw.text(xy, value, font=pil_font(size, bold), fill=fill, anchor=anchor)


def rounded(draw, box, radius, fill, outline=None, width=1):
    draw.rounded_rectangle(box, radius=radius, fill=fill, outline=outline, width=width)


def create_desktop_mock(filename, title, active, cards, admin=False):
    path = TMP / filename
    canvas = PILImage.new("RGB", (1440, 780), "#EFF5FB")
    draw = ImageDraw.Draw(canvas)
    sidebar_w = 255
    draw.rectangle((0, 0, sidebar_w, 780), fill="#EAF3FB")
    draw.line((sidebar_w, 0, sidebar_w, 780), fill="#D6E3EE", width=2)
    rounded(draw, (30, 26, 222, 104), 13, WHITE)
    logo = PILImage.open(LOGO).convert("RGB")
    logo.thumbnail((155, 70))
    canvas.paste(logo, (49, 31))
    text(draw, (25, 133), "ADMINISTRACIÓN" if admin else "ESPACIO DOCENTE", 12, "#647F98", True)
    menus = ["Resumen", "Usuarios y docentes", "Tus test", "Tabuladores", "Formulario público", "Catálogos"] if admin else ["Inicio", "Tus test", "Tabuladores", "Mi perfil"]
    y = 155
    for idx, item in enumerate(menus):
        if item == active:
            rounded(draw, (15, y, 238, y + 46), 10, BLUE)
            rounded(draw, (26, y + 8, 56, y + 38), 7, GOLD)
            text(draw, (70, y + 23), item, 14, WHITE, True, "lm")
        else:
            rounded(draw, (26, y + 8, 56, y + 38), 7, "#DCEBFA")
            text(draw, (41, y + 23), item[0], 11, BLUE, True, "mm")
            text(draw, (70, y + 23), item, 14, INK, True, "lm")
        y += 53
    rounded(draw, (18, 684, 238, 755), 12, "#F7FBFF", "#CFDDE9", 1)
    rounded(draw, (29, 700, 62, 733), 8, BLUE)
    text(draw, (45, 717), "RD", 10, WHITE, True, "mm")
    text(draw, (74, 705), "Administrador" if admin else "Docente Demostración", 12, INK, True)
    text(draw, (74, 724), "Cerrar sesión", 10, MUTED)

    draw.rectangle((sidebar_w, 0, 1440, 72), fill=WHITE)
    draw.line((sidebar_w, 71, 1440, 71), fill=GOLD, width=3)
    text(draw, (290, 22), "RUTA DOCENTE", 11, BLUE, True)
    text(draw, (290, 42), title, 22, NAVY, True)
    rounded(draw, (1215, 14, 1405, 57), 12, WHITE, "#D4DFE8", 1)
    text(draw, (1235, 35), "RD", 11, BLUE, True, "mm")
    text(draw, (1260, 29), "Cuenta de demostración", 12, NAVY, True)
    text(draw, (1260, 45), "Perfil activo", 9, MUTED)

    x0, y0, content_w = 288, 105, 1116
    rounded(draw, (x0, y0, x0 + content_w, y0 + 116), 16, WHITE, "#DCE5ED", 1)
    draw.rectangle((x0, y0, x0 + 5, y0 + 116), fill=GOLD)
    text(draw, (x0 + 28, y0 + 25), title.upper(), 11, BLUE, True)
    text(draw, (x0 + 28, y0 + 52), cards[0][0], 26, NAVY, True)
    text(draw, (x0 + 28, y0 + 85), cards[0][1], 13, MUTED)

    content_cards = cards[1:]
    if len(content_cards) <= 3:
        widths = [content_w // max(1, len(content_cards)) - 10] * len(content_cards)
        xs = [x0 + i * (content_w // max(1, len(content_cards))) for i in range(len(content_cards))]
        for i, ((heading, desc), x, w) in enumerate(zip(content_cards, xs, widths)):
            rounded(draw, (x, 247, x + w, 388), 14, WHITE, "#D8E3EC", 1)
            draw.rectangle((x, 247, x + w, 251), fill=GOLD if i % 2 else BLUE)
            rounded(draw, (x + 18, 270, x + 60, 312), 10, GOLD if i % 2 else "#DCEBFA")
            text(draw, (x + 39, 291), str(i + 1), 15, NAVY if i % 2 else BLUE, True, "mm")
            text(draw, (x + 18, 329), heading, 16, NAVY, True)
            text(draw, (x + 18, 355), shorten(desc, width=56, placeholder="..."), 11, MUTED)
    else:
        for i, (heading, desc) in enumerate(content_cards[:4]):
            row, col = divmod(i, 2)
            x = x0 + col * 560
            yy = 247 + row * 160
            rounded(draw, (x, yy, x + 540, yy + 140), 14, WHITE, "#D8E3EC", 1)
            rounded(draw, (x + 18, yy + 20, x + 62, yy + 64), 10, GOLD if i % 2 else "#DCEBFA")
            text(draw, (x + 40, yy + 42), str(i + 1), 15, NAVY if i % 2 else BLUE, True, "mm")
            text(draw, (x + 78, yy + 24), heading, 16, NAVY, True)
            text(draw, (x + 78, yy + 52), shorten(desc, width=68, placeholder="..."), 11, MUTED)
            rounded(draw, (x + 78, yy + 86, x + 190, yy + 120), 8, BLUE)
            text(draw, (x + 134, yy + 103), "Abrir", 11, WHITE, True, "mm")
    canvas.save(path, quality=95)
    return path


def create_mobile_mock(filename, title, items):
    path = TMP / filename
    canvas = PILImage.new("RGB", (540, 980), "#EDF5FC")
    draw = ImageDraw.Draw(canvas)
    draw.rectangle((0, 0, 540, 78), fill=WHITE)
    draw.line((0, 77, 540, 77), fill=GOLD, width=3)
    rounded(draw, (18, 17, 61, 60), 10, "#E1EFFE")
    text(draw, (39, 39), "≡", 22, BLUE, True, "mm")
    text(draw, (82, 23), "RUTA DOCENTE", 10, BLUE, True)
    text(draw, (82, 42), title, 18, NAVY, True)
    rounded(draw, (468, 18, 511, 61), 10, BLUE)
    text(draw, (489, 39), "RD", 10, WHITE, True, "mm")
    y = 104
    rounded(draw, (18, y, 522, y + 142), 16, BLUE)
    text(draw, (40, y + 29), "ESPACIO PERSONAL", 10, "#FFE09A", True)
    text(draw, (40, y + 57), title, 24, WHITE, True)
    text(draw, (40, y + 93), "Contenido organizado y disponible desde tu celular.", 12, "#D9E8F6")
    y += 168
    for i, (heading, desc) in enumerate(items):
        rounded(draw, (18, y, 522, y + 135), 15, WHITE, "#D9E4ED", 1)
        draw.rectangle((18, y, 23, y + 135), fill=GOLD if i % 2 else BLUE)
        rounded(draw, (40, y + 22, 84, y + 66), 10, "#FFF1C4" if i % 2 else "#E1EFFE")
        text(draw, (62, y + 44), str(i + 1), 14, NAVY if i % 2 else BLUE, True, "mm")
        text(draw, (102, y + 23), heading, 15, NAVY, True)
        text(draw, (102, y + 50), shorten(desc, width=54, placeholder="..."), 11, MUTED)
        rounded(draw, (366, y + 88, 496, y + 119), 8, BLUE)
        text(draw, (431, y + 104), "Abrir", 10, WHITE, True, "mm")
        y += 150
    rounded(draw, (12, 897, 528, 966), 17, NAVY)
    text(draw, (32, 919), "Ruta Docente", 13, WHITE, True)
    rounded(draw, (356, 909, 505, 952), 10, BLUE)
    text(draw, (430, 931), "Acceso docente", 10, WHITE, True, "mm")
    canvas.save(path, quality=95)
    return path


def build_assets():
    TMP.mkdir(parents=True, exist_ok=True)
    assets = {}
    assets["login"] = create_desktop_mock(
        "login.png", "Acceso a la plataforma", "Inicio",
        [("Ingresa con tu cuenta", "Utiliza el correo y la contraseña entregados por Ruta Docente."),
         ("Correo", "Escribe tu correo registrado."), ("Contraseña", "Ingresa tu clave personal."),
         ("Acceso seguro", "No compartas tus datos de acceso.")], False)
    assets["teacher_tests"] = create_desktop_mock(
        "teacher-tests.png", "Tus test", "Tus test",
        [("Tests para avanzar con seguridad", "Evaluaciones asociadas a tu asignatura y perfil."),
         ("Buscar", "Encuentra un test por su nombre."), ("Descargar", "Guarda el archivo en tu dispositivo."),
         ("Abrir en línea", "Accede a la actividad desde el botón Abrir.")], False)
    assets["teacher_tabs"] = create_desktop_mock(
        "teacher-tabulators.png", "Tabuladores", "Tabuladores",
        [("Organiza tus resultados", "Herramientas agrupadas para analizar avances."),
         ("Elegir grupo", "Ubica el grupo correspondiente."), ("Descargar", "Obtén la plantilla disponible."),
         ("Completar", "Registra y analiza tus resultados.")], False)
    assets["teacher_profile"] = create_desktop_mock(
        "teacher-profile.png", "Mi perfil", "Mi perfil",
        [("Actualiza tu información", "Mantén tus datos personales al día."),
         ("Foto", "Sube una imagen JPG, PNG o WebP."), ("Datos", "Revisa nombre, apellido y teléfono."),
         ("Guardar", "Confirma los cambios antes de salir.")], False)
    assets["teacher_mobile"] = create_mobile_mock(
        "teacher-mobile.png", "Área docente",
        [("Inicio", "Resumen de recursos disponibles."), ("Tus test", "Evaluaciones habilitadas."),
         ("Tabuladores", "Herramientas de análisis."), ("Mi perfil", "Datos personales y fotografía.")])
    assets["admin_dashboard"] = create_desktop_mock(
        "admin-dashboard.png", "Panel de control", "Resumen",
        [("Resumen de la plataforma", "Indicadores de usuarios, docentes y recursos."),
         ("Usuarios", "Cuentas registradas en el sistema."), ("Tests", "Evaluaciones disponibles."),
         ("Tabuladores", "Herramientas de análisis publicadas.")], True)
    assets["admin_users"] = create_desktop_mock(
        "admin-users.png", "Usuarios y docentes", "Usuarios y docentes",
        [("Gestiona las cuentas", "Crea usuarios y controla sus permisos."),
         ("Nuevo usuario", "Registra datos y asigna un rol."), ("Permisos", "Habilita tests y tabuladores."),
         ("Estado", "Activa, edita o elimina una cuenta.")], True)
    assets["admin_resources"] = create_desktop_mock(
        "admin-resources.png", "Gestión de recursos", "Tus test",
        [("Publica contenido", "Administra tests y tabuladores por asignatura."),
         ("Nombre y descripción", "Identifica claramente el recurso."), ("Archivo o enlace", "Adjunta un archivo o URL."),
         ("Asignación", "Selecciona asignatura, grupo y estado.")], True)
    assets["admin_catalogs"] = create_desktop_mock(
        "admin-catalogs.png", "Catálogos", "Catálogos",
        [("Configuración general", "Valores utilizados en usuarios y recursos."),
         ("Roles", "Niveles de acceso."), ("Asignaturas", "Áreas disponibles."),
         ("Grupos", "Organización de tabuladores.")], True)
    assets["admin_form"] = create_desktop_mock(
        "admin-form.png", "Formulario público", "Formulario público",
        [("Configura las inscripciones", "Define información, campos y datos bancarios."),
         ("Información", "Título, introducción y estado."), ("Campos", "Crea y ordena preguntas."),
         ("Cuenta bancaria", "Edita valor y datos de transferencia.")], True)
    assets["admin_mobile"] = create_mobile_mock(
        "admin-mobile.png", "Administración",
        [("Resumen", "Indicadores de la plataforma."), ("Usuarios", "Cuentas y permisos."),
         ("Recursos", "Tests y tabuladores."), ("Formulario", "Inscripciones y respuestas.")])
    return assets


styles = getSampleStyleSheet()
styles.add(ParagraphStyle(name="CoverTitle", fontName="RutaSans-Bold", fontSize=28, leading=34, textColor=colors.white, spaceAfter=10))
styles.add(ParagraphStyle(name="CoverSub", fontName="RutaSans", fontSize=12.5, leading=19, textColor=colors.HexColor("#D7E7F5"), spaceAfter=18))
styles.add(ParagraphStyle(name="Kicker", fontName="RutaSans-Bold", fontSize=8, leading=10, textColor=colors.HexColor(BLUE), tracking=1.4, spaceAfter=5))
styles.add(ParagraphStyle(name="SectionTitle", fontName="RutaSans-Bold", fontSize=22, leading=27, textColor=colors.HexColor(NAVY), spaceAfter=8))
styles.add(ParagraphStyle(name="BodyRuta", fontName="RutaSans", fontSize=9.4, leading=14, textColor=colors.HexColor(MUTED), spaceAfter=8))
styles.add(ParagraphStyle(name="BodyStrong", fontName="RutaSans-Bold", fontSize=9.4, leading=14, textColor=colors.HexColor(INK)))
styles.add(ParagraphStyle(name="Caption", fontName="RutaSans", fontSize=7.6, leading=10, textColor=colors.HexColor(MUTED), alignment=TA_CENTER, spaceBefore=4, spaceAfter=8))
styles.add(ParagraphStyle(name="StepTitle", fontName="RutaSans-Bold", fontSize=9, leading=12, textColor=colors.HexColor(NAVY)))
styles.add(ParagraphStyle(name="StepBody", fontName="RutaSans", fontSize=8.1, leading=11, textColor=colors.HexColor(MUTED)))
styles.add(ParagraphStyle(name="Tip", fontName="RutaSans", fontSize=8.4, leading=12, textColor=colors.HexColor(INK)))
styles.add(ParagraphStyle(name="IndexTitle", fontName="RutaSans-Bold", fontSize=11, leading=14, textColor=colors.HexColor(NAVY)))
styles.add(ParagraphStyle(name="IndexBody", fontName="RutaSans", fontSize=8.4, leading=12, textColor=colors.HexColor(MUTED)))


def scaled_image(path, max_width=510, max_height=285):
    with PILImage.open(path) as img:
        w, h = img.size
    scale = min(max_width / w, max_height / h)
    return Image(str(path), width=w * scale, height=h * scale)


def framed_image(path, caption, max_height=278):
    image = scaled_image(path, 505, max_height)
    frame = Table([[image]], colWidths=[image.drawWidth + 10], rowHeights=[image.drawHeight + 10])
    frame.setStyle(TableStyle([
        ("BACKGROUND", (0, 0), (-1, -1), colors.white),
        ("BOX", (0, 0), (-1, -1), 1, colors.HexColor(LINE)),
        ("LEFTPADDING", (0, 0), (-1, -1), 5), ("RIGHTPADDING", (0, 0), (-1, -1), 5),
        ("TOPPADDING", (0, 0), (-1, -1), 5), ("BOTTOMPADDING", (0, 0), (-1, -1), 5),
    ]))
    return [frame, Paragraph(caption, styles["Caption"])]


def step_table(steps):
    rows = []
    for i, (title, body) in enumerate(steps, 1):
        number = Table([[Paragraph(str(i), styles["BodyStrong"])]], colWidths=[23], rowHeights=[23])
        number.setStyle(TableStyle([
            ("BACKGROUND", (0, 0), (-1, -1), colors.HexColor(GOLD if i % 2 == 0 else "#DDEEFF")),
            ("TEXTCOLOR", (0, 0), (-1, -1), colors.HexColor(NAVY if i % 2 == 0 else BLUE)),
            ("ALIGN", (0, 0), (-1, -1), "CENTER"), ("VALIGN", (0, 0), (-1, -1), "MIDDLE"),
            ("BOX", (0, 0), (-1, -1), .5, colors.HexColor(LINE)),
        ]))
        copy = Paragraph(f"<b>{title}</b><br/>{body}", styles["StepBody"])
        rows.append([number, copy])
    table = Table(rows, colWidths=[31, 466], hAlign="LEFT")
    table.setStyle(TableStyle([
        ("VALIGN", (0, 0), (-1, -1), "TOP"),
        ("TOPPADDING", (0, 0), (-1, -1), 4), ("BOTTOMPADDING", (0, 0), (-1, -1), 5),
        ("LINEBELOW", (1, 0), (1, -2), .45, colors.HexColor(LINE)),
        ("LEFTPADDING", (0, 0), (-1, -1), 2), ("RIGHTPADDING", (0, 0), (-1, -1), 5),
    ]))
    return table


def tip_box(title, body, warning=False):
    bg = PALE_GOLD if warning else PALE_BLUE
    accent = GOLD if warning else BLUE
    content = Paragraph(f"<b>{title}</b><br/>{body}", styles["Tip"])
    table = Table([[content]], colWidths=[497])
    table.setStyle(TableStyle([
        ("BACKGROUND", (0, 0), (-1, -1), colors.HexColor(bg)),
        ("BOX", (0, 0), (-1, -1), .7, colors.HexColor(accent)),
        ("LINEBEFORE", (0, 0), (0, -1), 4, colors.HexColor(accent)),
        ("LEFTPADDING", (0, 0), (-1, -1), 12), ("RIGHTPADDING", (0, 0), (-1, -1), 12),
        ("TOPPADDING", (0, 0), (-1, -1), 9), ("BOTTOMPADDING", (0, 0), (-1, -1), 9),
    ]))
    return table


def on_page(canvas, doc, manual_name):
    width, height = A4
    canvas.saveState()
    if doc.page == 1:
        canvas.setFillColor(colors.HexColor(NAVY))
        canvas.rect(0, 0, width, height, fill=1, stroke=0)
        canvas.setStrokeColor(colors.Color(237 / 255, 169 / 255, 21 / 255, alpha=.17))
        canvas.setLineWidth(28)
        canvas.circle(width + 4, height - 35, 105, fill=0, stroke=1)
        canvas.setStrokeColor(colors.Color(11 / 255, 95 / 255, 189 / 255, alpha=.22))
        canvas.circle(-12, 35, 95, fill=0, stroke=1)
    else:
        canvas.setFillColor(colors.white)
        canvas.rect(0, 0, width, height, fill=1, stroke=0)
        canvas.setFillColor(colors.HexColor(BLUE))
        canvas.rect(0, height - 7, width, 7, fill=1, stroke=0)
        canvas.drawImage(str(LOGO), 42, height - 47, width=92, height=40, preserveAspectRatio=True, mask="auto")
        canvas.setFont("RutaSans-Bold", 7.5)
        canvas.setFillColor(colors.HexColor(MUTED))
        canvas.drawRightString(width - 42, height - 29, manual_name.upper())
        canvas.setStrokeColor(colors.HexColor(LINE))
        canvas.line(42, 38, width - 42, 38)
        canvas.setFont("RutaSans", 7.5)
        canvas.setFillColor(colors.HexColor(MUTED))
        canvas.drawString(42, 24, "Ruta Docente 2026 - Guía de uso")
        canvas.drawRightString(width - 42, 24, f"Página {doc.page}")
    canvas.restoreState()


def cover_story(title, subtitle, visual):
    return [
        Spacer(1, 50),
        Image(str(LOGO), width=220, height=100),
        Spacer(1, 22),
        Paragraph(title, styles["CoverTitle"]),
        Paragraph(subtitle, styles["CoverSub"]),
        Spacer(1, 8),
        *framed_image(visual, "Interfaz referencial de Ruta Docente", 240),
        Spacer(1, 12),
        Paragraph("VERSIÓN 2026  |  RUTA DOCENTE", ParagraphStyle(name="CoverFoot", parent=styles["Kicker"], textColor=colors.HexColor("#FFE09A"))),
        PageBreak(),
    ]


def index_page(audience, sections):
    cells = []
    for number, title, desc in sections:
        cells.append([
            Paragraph(number, ParagraphStyle(name=f"N{number}", parent=styles["SectionTitle"], fontSize=16, textColor=colors.HexColor(BLUE))),
            Paragraph(f"<b>{title}</b><br/>{desc}", styles["IndexBody"]),
        ])
    table = Table(cells, colWidths=[52, 438])
    table.setStyle(TableStyle([
        ("VALIGN", (0, 0), (-1, -1), "TOP"),
        ("ALIGN", (0, 0), (0, -1), "CENTER"),
        ("BACKGROUND", (0, 0), (-1, -1), colors.white),
        ("BOX", (0, 0), (-1, -1), .7, colors.HexColor(LINE)),
        ("INNERGRID", (0, 0), (-1, -1), .45, colors.HexColor(LINE)),
        ("LEFTPADDING", (0, 0), (-1, -1), 10), ("RIGHTPADDING", (0, 0), (-1, -1), 10),
        ("TOPPADDING", (0, 0), (-1, -1), 10), ("BOTTOMPADDING", (0, 0), (-1, -1), 10),
    ]))
    return [
        Paragraph("GUÍA RÁPIDA", styles["Kicker"]),
        Paragraph(f"Todo lo necesario para usar el área {audience}.", styles["SectionTitle"]),
        Paragraph("Este manual explica cada tarea con pasos breves. Puedes consultarlo desde computador o celular y volver directamente a la sección que necesites.", styles["BodyRuta"]),
        Spacer(1, 8), table, Spacer(1, 14),
        tip_box("Antes de comenzar", "Utiliza un navegador actualizado, mantén tu conexión a Internet activa y evita compartir tu contraseña. La plataforma funciona en computador, tablet y teléfono."),
        PageBreak(),
    ]


def topic(number, title, intro, image_path, caption, steps, tip=None, warning=False):
    story = [
        Paragraph(f"SECCIÓN {number}", styles["Kicker"]),
        Paragraph(title, styles["SectionTitle"]),
        Paragraph(intro, styles["BodyRuta"]),
        Spacer(1, 5),
        *framed_image(image_path, caption, 265),
        step_table(steps),
    ]
    if tip:
        story.extend([Spacer(1, 8), tip_box("Importante" if warning else "Consejo", tip, warning)])
    story.append(PageBreak())
    return story


def finish_page(title, checks, support):
    rows = []
    for item in checks:
        rows.append([Paragraph("✓", styles["BodyStrong"]), Paragraph(item, styles["BodyRuta"])])
    table = Table(rows, colWidths=[24, 466])
    table.setStyle(TableStyle([
        ("BACKGROUND", (0, 0), (-1, -1), colors.white),
        ("BOX", (0, 0), (-1, -1), .7, colors.HexColor(LINE)),
        ("INNERGRID", (0, 0), (-1, -1), .4, colors.HexColor(LINE)),
        ("TEXTCOLOR", (0, 0), (0, -1), colors.HexColor(GREEN)),
        ("VALIGN", (0, 0), (-1, -1), "TOP"),
        ("LEFTPADDING", (0, 0), (-1, -1), 9), ("RIGHTPADDING", (0, 0), (-1, -1), 9),
        ("TOPPADDING", (0, 0), (-1, -1), 8), ("BOTTOMPADDING", (0, 0), (-1, -1), 5),
    ]))
    return [
        Paragraph("LISTA DE COMPROBACIÓN", styles["Kicker"]),
        Paragraph(title, styles["SectionTitle"]),
        Paragraph("Utiliza esta lista antes de solicitar ayuda. La mayoría de las dificultades se resuelven revisando estos puntos.", styles["BodyRuta"]),
        Spacer(1, 8), table, Spacer(1, 16),
        tip_box("Soporte Ruta Docente", support),
        Spacer(1, 18),
        Paragraph("Acceso web", styles["Kicker"]),
        Paragraph("https://rutadocente.com/login", styles["BodyStrong"]),
        Spacer(1, 10),
        Paragraph("Correo", styles["Kicker"]),
        Paragraph("aulaentretenida0@gmail.com", styles["BodyStrong"]),
        Spacer(1, 10),
        Paragraph("WhatsApp", styles["Kicker"]),
        Paragraph("+56 9 7577 8434", styles["BodyStrong"]),
    ]


def build_teacher(assets):
    out = OUT / "manual-docente-ruta-docente.pdf"
    doc = BaseDocTemplate(str(out), pagesize=A4, leftMargin=42, rightMargin=42, topMargin=60, bottomMargin=48, title="Manual del docente - Ruta Docente")
    frame = Frame(doc.leftMargin, doc.bottomMargin, doc.width, doc.height, id="normal")
    doc.addPageTemplates(PageTemplate(id="main", frames=frame, onPage=lambda c, d: on_page(c, d, "Manual del docente")))
    story = cover_story("Manual del docente", "Guía clara para ingresar, revisar tus test, utilizar tabuladores y mantener actualizado tu perfil.", assets["teacher_mobile"])
    story += index_page("docente", [
        ("01", "Ingreso", "Acceso seguro con correo y contraseña."),
        ("02", "Página principal", "Resumen de tus recursos y asignatura."),
        ("03", "Uso desde celular", "Menú, navegación y botones táctiles."),
        ("04", "Tus test", "Buscar, abrir y descargar evaluaciones."),
        ("05", "Tabuladores", "Acceder a herramientas de análisis."),
        ("06", "Mi perfil", "Actualizar datos y fotografía."),
    ])
    story += topic("01", "Ingresar a Ruta Docente", "El acceso es personal. Utiliza únicamente la cuenta asignada y cierra la sesión cuando uses un equipo compartido.", assets["login"], "Pantalla de acceso a la plataforma.", [
        ("Abrir el acceso", "Visita rutadocente.com/login desde tu navegador."),
        ("Escribir tus datos", "Ingresa el correo registrado y tu contraseña personal."),
        ("Entrar", "Presiona el botón de acceso. El sistema abrirá automáticamente tu área docente."),
    ], "Si el sistema informa que los datos son incorrectos, revisa mayúsculas, espacios y el correo utilizado.")
    dashboard = TEACHER_SCREENSHOT if TEACHER_SCREENSHOT.exists() else assets["teacher_mobile"]
    story += topic("02", "Conocer la página principal", "La portada muestra tu asignatura, la cantidad de test y tabuladores disponibles y accesos rápidos a cada módulo.", dashboard, "Vista real del área docente y sus recursos asignados.", [
        ("Revisar la asignatura", "Comprueba que el nombre mostrado corresponda a tu área."),
        ("Consultar los indicadores", "Las tarjetas informan cuántos recursos tienes disponibles."),
        ("Abrir un módulo", "Usa Ver módulo o el menú lateral para entrar a tus test o tabuladores."),
    ], "Si un módulo aparece sin recursos, el administrador todavía no ha publicado contenido para tu asignatura.")
    story += topic("03", "Usar la plataforma desde el celular", "En pantallas pequeñas el menú se abre con el botón superior. Las tarjetas y formularios se ordenan en una sola columna para facilitar la lectura.", assets["teacher_mobile"], "Distribución optimizada para teléfonos.", [
        ("Abrir el menú", "Pulsa el icono de tres líneas ubicado en la parte superior."),
        ("Elegir una sección", "Selecciona Inicio, Tus test, Tabuladores o Mi perfil."),
        ("Cerrar el menú", "Al elegir una opción el menú se cierra y muestra el contenido."),
        ("Descargar archivos", "El archivo quedará en la carpeta Descargas de tu dispositivo."),
    ], "Gira el teléfono solo si necesitas revisar una tabla o un documento muy ancho.")
    story += topic("04", "Abrir y descargar tus test", "El módulo muestra únicamente los test activos y compatibles con tu asignatura. Puedes buscar por nombre y utilizar el formato disponible.", assets["teacher_tests"], "Módulo Tus test.", [
        ("Buscar", "Escribe una palabra del nombre del test en el buscador."),
        ("Revisar la descripción", "Confirma que el contenido corresponda a la actividad que necesitas."),
        ("Abrir o descargar", "Usa Abrir para una actividad en línea o Descargar para guardar el archivo."),
    ], "No cierres la página durante una descarga. Espera a que el navegador confirme que el archivo fue guardado.")
    story += topic("05", "Trabajar con tabuladores", "Los tabuladores se organizan por grupos. Cada tarjeta indica el tipo de acceso disponible y la asignatura relacionada.", assets["teacher_tabs"], "Biblioteca de tabuladores.", [
        ("Seleccionar el grupo", "Ubica la sección que corresponde al análisis que realizarás."),
        ("Elegir la herramienta", "Lee el nombre y la descripción del tabulador."),
        ("Descargar o abrir", "Obtén la plantilla o entra al recurso en línea."),
        ("Guardar una copia", "Conserva el archivo con un nombre que identifique curso y fecha."),
    ], "No reemplaces la plantilla original. Trabaja sobre una copia para conservar el formato base.")
    story += topic("06", "Actualizar tu perfil", "En Mi perfil puedes modificar tus datos personales y agregar una fotografía que aparecerá en el encabezado del área docente.", assets["teacher_profile"], "Formulario de perfil docente.", [
        ("Abrir Mi perfil", "Selecciona la opción desde el menú lateral."),
        ("Elegir una fotografía", "Utiliza una imagen JPG, PNG o WebP de hasta 3 MB."),
        ("Actualizar tus datos", "Revisa nombre, apellido y teléfono."),
        ("Guardar", "Presiona Guardar cambios y espera la confirmación."),
    ], "El correo no se edita desde el perfil. Solicita el cambio al administrador.")
    story += finish_page("Antes de solicitar ayuda", [
        "Comprueba que tienes conexión a Internet.", "Actualiza la página y vuelve a iniciar sesión.",
        "Verifica que el archivo o enlace aparezca como disponible.", "Revisa la carpeta Descargas del teléfono o computador.",
        "Indica al soporte el nombre exacto del recurso y tu asignatura.",
    ], "Describe qué estabas intentando hacer y, si es posible, adjunta una captura sin mostrar tu contraseña.")
    doc.build(story)
    return out


def build_admin(assets):
    out = OUT / "manual-administrador-ruta-docente.pdf"
    doc = BaseDocTemplate(str(out), pagesize=A4, leftMargin=42, rightMargin=42, topMargin=60, bottomMargin=48, title="Manual del administrador - Ruta Docente")
    frame = Frame(doc.leftMargin, doc.bottomMargin, doc.width, doc.height, id="normal")
    doc.addPageTemplates(PageTemplate(id="main", frames=frame, onPage=lambda c, d: on_page(c, d, "Manual del administrador")))
    story = cover_story("Manual del administrador", "Guía operativa para administrar usuarios, contenidos, catálogos, inscripciones y configuración pública.", assets["admin_dashboard"])
    story += index_page("administrativa", [
        ("01", "Acceso y seguridad", "Ingreso seguro y protección de la cuenta."),
        ("02", "Panel de control", "Indicadores y accesos rápidos."),
        ("03", "Usuarios", "Creación, edición y permisos."),
        ("04", "Tests y tabuladores", "Publicación y asignación de recursos."),
        ("05", "Catálogos", "Roles, asignaturas, grupos y subgrupos."),
        ("06", "Formulario público", "Campos, cuenta bancaria y respuestas."),
    ])
    story += topic("01", "Acceso y seguridad", "La cuenta administrativa permite modificar información sensible. Utiliza una contraseña exclusiva y evita iniciar sesión en dispositivos públicos.", assets["login"], "Acceso al panel administrativo.", [
        ("Abrir la plataforma", "Visita rutadocente.com/login."),
        ("Ingresar la cuenta", "Escribe el correo administrativo y la contraseña vigente."),
        ("Verificar el perfil", "Comprueba que el encabezado identifique tu cuenta como Administrador."),
        ("Cerrar la sesión", "Utiliza Cerrar sesión al finalizar el trabajo."),
    ], "Cambia la contraseña inicial antes de utilizar el sistema en producción y nunca la compartas por correo o mensajería.", True)
    story += topic("02", "Interpretar el panel de control", "El resumen presenta la cantidad de usuarios, docentes, test y tabuladores registrados. Los gráficos ayudan a revisar la composición de la plataforma.", assets["admin_dashboard"], "Panel ejecutivo del administrador.", [
        ("Revisar indicadores", "Comprueba las cifras de usuarios y contenidos."),
        ("Analizar la distribución", "Observa la proporción de docentes y recursos."),
        ("Usar accesos rápidos", "Entra directamente al módulo que necesitas administrar."),
    ], "Los indicadores se actualizan con la información almacenada en la base de datos.")
    story += topic("03", "Crear y administrar usuarios", "El módulo Usuarios y docentes permite registrar cuentas, asignar roles y controlar el acceso a test y tabuladores.", assets["admin_users"], "Gestión de usuarios y permisos.", [
        ("Crear una cuenta", "Pulsa Nuevo usuario y completa nombre, correo, teléfono y contraseña."),
        ("Asignar rol y asignatura", "Elige Administrador o Docente y, cuando corresponda, una asignatura."),
        ("Habilitar módulos", "Activa los permisos de test y tabuladores necesarios."),
        ("Guardar y verificar", "Confirma el registro y revisa que el estado aparezca Activo."),
    ], "Desactiva una cuenta antes de eliminarla si necesitas conservar su historial.")
    story += topic("04", "Publicar test y tabuladores", "Ambos módulos utilizan el mismo flujo de publicación. Cada recurso puede incluir un archivo descargable, un enlace externo o ambos.", assets["admin_resources"], "Formulario referencial de recursos.", [
        ("Elegir el módulo", "Entra a Tus test o Tabuladores desde el menú."),
        ("Crear el recurso", "Completa nombre y una descripción breve y clara."),
        ("Asignar audiencia", "Selecciona asignatura y, para tabuladores, grupo o subgrupo."),
        ("Adjuntar contenido", "Sube el archivo, agrega el enlace y marca el recurso como activo."),
        ("Comprobar", "Utiliza la vista del recurso para confirmar la información publicada."),
    ], "Un docente solo verá recursos activos y compatibles con su asignatura y permisos.")
    story += topic("05", "Mantener los catálogos", "Los catálogos controlan los valores disponibles en formularios y recursos. Incluyen roles, asignaturas, grupos y subgrupos.", assets["admin_catalogs"], "Catálogos de configuración general.", [
        ("Seleccionar un catálogo", "Ubica la tarjeta del tipo de dato que deseas mantener."),
        ("Agregar o editar", "Usa el botón correspondiente y escribe un nombre único y comprensible."),
        ("Revisar dependencias", "Comprueba que el valor no esté siendo utilizado antes de eliminarlo."),
        ("Guardar", "Confirma el cambio y revisa que aparezca en el listado."),
    ], "Evita duplicar asignaturas o grupos con diferencias mínimas de escritura.", True)
    story += topic("06", "Configurar el formulario público", "Desde este módulo puedes controlar el estado de las inscripciones, el texto informativo, los campos solicitados y la cuenta bancaria.", assets["admin_form"], "Constructor del formulario público.", [
        ("Información general", "Define título, introducción, mensajes y estado abierto o cerrado."),
        ("Campos", "Crea preguntas, elige su tipo, opciones, obligatoriedad y orden."),
        ("Cuenta bancaria", "Actualiza titular, RUT, banco, tipo, número de cuenta y valor."),
        ("Consentimiento", "Redacta el texto de autorización y el mensaje de confirmación."),
        ("Guardar y probar", "Abre la página pública y realiza una revisión antes de difundirla."),
    ], "Revisa cuidadosamente los datos bancarios antes de abrir el formulario al público.", True)
    story += topic("07", "Revisar respuestas e inscripciones", "Las respuestas se muestran en el mismo módulo administrativo. Cada registro puede desplegar sus datos y archivos adjuntos.", assets["admin_form"], "Sección de respuestas del formulario.", [
        ("Abrir Respuestas", "Desplázate a la sección correspondiente dentro del módulo."),
        ("Identificar el registro", "Revisa fecha, nombre y correo del participante."),
        ("Desplegar respuestas", "Abre el detalle para consultar todos los campos enviados."),
        ("Ver comprobantes", "Abre el archivo adjunto únicamente cuando sea necesario."),
        ("Proteger la información", "No compartas datos personales ni documentos fuera del proceso."),
    ], "Los datos de inscripción deben utilizarse únicamente para gestionar la actividad informada.", True)
    story += topic("08", "Administrar desde el celular", "El panel adapta menús, indicadores, formularios y tablas para teléfonos. La tabla de usuarios se transforma en tarjetas verticales.", assets["admin_mobile"], "Panel administrativo optimizado para celular.", [
        ("Abrir navegación", "Pulsa el botón de menú en la barra superior."),
        ("Elegir el módulo", "Selecciona la opción administrativa que necesitas."),
        ("Editar con cuidado", "Revisa cada campo antes de guardar desde una pantalla pequeña."),
        ("Cerrar la sesión", "Finaliza siempre la sesión si el teléfono no es de uso personal."),
    ], "Para cargas masivas o revisión de muchas respuestas, utiliza preferentemente un computador.")
    story += finish_page("Control operativo del administrador", [
        "Cambiar la contraseña inicial y mantenerla protegida.", "Revisar periódicamente usuarios y cuentas activas.",
        "Comprobar permisos y asignaturas antes de publicar recursos.", "Validar archivos y enlaces desde una cuenta docente de prueba.",
        "Revisar datos bancarios y mensajes antes de abrir inscripciones.", "Mantener respaldos periódicos de base de datos y archivos subidos.",
    ], "Para solicitar soporte, indica el módulo, la acción realizada y el mensaje observado. No envíes contraseñas ni datos bancarios completos en una captura.")
    doc.build(story)
    return out


def main():
    OUT.mkdir(parents=True, exist_ok=True)
    assets = build_assets()
    teacher = build_teacher(assets)
    admin = build_admin(assets)
    print(teacher)
    print(admin)


if __name__ == "__main__":
    main()
