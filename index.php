<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="css/styles-index.css">
  <title>MEDLINK</title>
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg">
      <div class="container">
        <a class="navbar-brand" id="Logo">MedLink</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="index.php">Inicio</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="perfil.php">Mi Perfil</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="medicos.php">Médicos</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <main id="main-content">
    <div class="image-container">
      <img src="images/index/2.jpg" alt="Imagen de fondo del hospital" class="img-fluid">
      <div class="overlay-text">
        <h2>Cuidando tu Salud<br>Desde 1990</h2>
      </div>
    </div>
    <div class="container mt-5">
      <div class="card mb-5">
        <div class="card-body">
          <h1 class="text-center">Bienvenido a <span class="highlight">MedLink</span></h1>
          <p class="text-center">
            En MedLink, nos dedicamos a conectar a pacientes con los mejores profesionales de la salud
            en el Hospital de Jesús. Aquí encontrarás información sobre nuestros consultorios,
            podrás agendar citas fácilmente y acceder a tus estudios médicos.
          </p>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-md-4 mb-4">
          <div class="card h-100 shadow-sm card-custom">
            <div class="card-body">
              <h3 class="card-title text-center"><i class="bi bi-bullseye me-2"></i>Misión</h3>
              <p class="card-text text-center">Brindar atención médica integral y de alta calidad, con calidez humana y
                tecnología de vanguardia, a todos nuestros pacientes, promoviendo la salud y el bienestar en la
                comunidad.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card h-100 shadow-sm card-custom">
            <div class="card-body">
              <h3 class="card-title text-center"><i class="bi bi-eye me-2"></i>Visión</h3>
              <p class="card-text text-center">Ser el hospital líder en la región, reconocido por su excelencia médica,
                innovación tecnológica y compromiso con la atención centrada en el paciente.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card h-100 shadow-sm card-custom">
            <div class="card-body">
              <h3 class="card-title text-center"><i class="bi bi-heart me-2"></i>Valores</h3>
              <p class="card-text">
              <ul>
                <li><strong>Respeto:</strong> Trato digno y considerado a cada persona.</li>
                <li><strong>Calidad:</strong> Búsqueda constante de la excelencia en nuestros servicios.</li>
                <li><strong>Compromiso:</strong> Con la salud y el bienestar de la comunidad.</li>
              </ul>
              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="row justify-content-center mt-4 mb-4">
        <div class="col-md-8">
          <h2 class="text-center mb-4 section-title">Dónde Encontrarnos</h2>
          <div class="map-container">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d257.97000549705996!2d-100.83254123764456!3d20.519342686131814!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x842cbaf76a0777bf%3A0x9248d3bff76920c!2sSanatorio%20de%20Jes%C3%BAs!5e0!3m2!1sen!2smx!4v1741563501688!5m2!1sen!2smx"
              width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer class="bg-light text-center py-3 text-muted">
    <p class="mb-1">Contacto:</p>
    <p class="mb-1">Teléfono: +52 461 614 7341</p>
    <p class="mb-1">Dirección: Blvrd Adolfo López Mateos 1301, Renacimiento, 38040 Celaya, Gto.</p>
    <p class="mb-1">&copy; MedLink 2025 todos los derechos reservados</p>
    <p class="text-muted mb-0">&copy; [<a href="admin/login.php" target="_blank">INGRESAR AL SISTEMA</a>]</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
</body>

</html>