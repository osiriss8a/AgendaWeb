
<?php
require_once __DIR__ . '/partes/sesion.php';
require_once __DIR__ . '/conexion.php';

// Obtener las categorías para el filtro
$resultado = $pdo->query(
    "SELECT id, nombre FROM categorias ORDER BY nombre"
);

$categorias = $resultado->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AgendaWeb - Explorar eventos</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <!-- CSS -->
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <!-- DECORACIÓN -->
    <div class="glow glow-1"></div>
    <div class="glow glow-2"></div>

    <!-- ================= SIDEBAR ================= -->
    <aside class="sidebar">

        <!-- LOGO -->
        <div class="brand">
            <div class="brand-icon">
                <i class="fa-solid fa-calendar-days"></i>
            </div>

            <div>
                <h2>
                    Agenda<span>Web</span>
                </h2>

                <p>
                    Organiza tus momentos
                </p>
            </div>
        </div>

        <!-- MENÚ -->
        <nav class="menu">

            <a href="index.php" class="menu-item">
                <i class="fa-solid fa-house"></i>
                <span>Inicio</span>
            </a>

            <a href="agenda.php" class="menu-item">
                <i class="fa-solid fa-calendar-days"></i>
                <span>Mi Agenda</span>
            </a>

            <a href="explorar.php" class="menu-item active">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>Explorar</span>
            </a>

        </nav>

        <!-- FRASE -->
        <div class="sidebar-frase">
            <i class="fa-solid fa-quote-left"></i>

            <p>
                "Organiza tu tiempo y disfruta cada momento."
            </p>

            <i class="fa-solid fa-quote-right"></i>
        </div>

        <!-- PIE DEL SIDEBAR -->
        <div class="sidebar-footer">
            <i class="fa-solid fa-heart"></i>
            <span>AgendaWeb</span>
        </div>

    </aside>

    <!-- ================= CONTENIDO PRINCIPAL ================= -->
    <main class="main">

        <!-- TOPBAR -->
        <header class="topbar">

            <div class="usuario">
                <div class="avatar">
                    <i class="fa-solid fa-user"></i>
                </div>

                <span>
                    Bienvenido,
                    <?= htmlspecialchars(
                        $_SESSION['nombre'] ?? 'Usuario',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </span>
            </div>

            <a href="logout.php" class="btn-guardar">
                <i class="fa-solid fa-right-from-bracket"></i>
                Cerrar sesión
            </a>

        </header>

        <!-- ================= EXPLORAR EVENTOS ================= -->
        <div class="inicio-contenido">

            <!-- PRESENTACIÓN -->
            <section class="inicio-presentacion">

                <div>
                    <p class="etiqueta">
                        <i class="fa-solid fa-sparkles"></i>
                        AGENDAWEB
                    </p>

                    <h2>
                        Explora tus eventos
                    </h2>

                    <p class="texto-inicio">
                        Encuentra fácilmente tus eventos registrados.
                        Busca por nombre o filtra por categoría
                        sin necesidad de recargar la página.
                    </p>
                </div>

                <div class="decoracion-calendario">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>

            </section>

            <!-- BUSCADOR -->
            <section class="formulario-card">

                <div class="formulario-titulo">

                    <div>
                        <h3>
                            Buscar eventos
                        </h3>

                        <p>
                            Escribe o selecciona una categoría para filtrar.
                        </p>
                    </div>

                    <i class="fa-solid fa-filter"></i>

                </div>

                <form
                    class="formulario-agenda"
                    id="filtros"
                >

                    <!-- BUSCAR POR TEXTO -->
                    <div class="campo">
                        <label for="q">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            Buscar evento
                        </label>

                        <input
                            type="search"
                            id="q"
                            maxlength="60"
                            placeholder="Escribe el nombre del evento..."
                        >
                    </div>

                    <!-- FILTRAR POR CATEGORÍA -->
                    <div class="campo">
                        <label for="categoria">
                            <i class="fa-solid fa-tag"></i>
                            Categoría
                        </label>

                        <select id="categoria">

                            <option value="0">
                                Todas las categorías
                            </option>

                            <?php foreach ($categorias as $cat): ?>

                                <option value="<?= (int) $cat['id'] ?>">
                                    <?= htmlspecialchars(
                                        $cat['nombre'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>
                    </div>

                </form>

                <!-- ESTADO DE LA BÚSQUEDA -->
                <p id="estado" role="status">
                    Cargando eventos...
                </p>

                <!-- LISTA DE EVENTOS -->
                <section
                    class="card-list"
                    id="lista"
                    aria-label="Eventos"
                    aria-live="polite"
                >
                </section>

            </section>

        </div>

        <!-- FOOTER -->
        <footer>
            <i class="fa-solid fa-heart"></i>
            AgendaWeb · Organiza tu tiempo
        </footer>

    </main>

    <!-- JAVASCRIPT -->
    <script src="js/explorar.js"></script>

</body>
</html>
