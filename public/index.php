<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$dbFile = __DIR__ . '/../dataBase/musics.db';
if (!is_dir(dirname($dbFile))) {
    mkdir(dirname($dbFile), 0755, true);
}

$pdo = new PDO('sqlite:' . $dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response) use ($pdo) {
    $stmt = $pdo->query('SELECT id, titol, data_publicacio FROM noticies ORDER BY data_publicacio DESC');
    $noticies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $items = '';
    foreach ($noticies as $noticia) {
        $items .= sprintf(
            '<li><a href="/noticia/%s">%s</a> <small>(%s)</small></li>',
            htmlspecialchars($noticia['id'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($noticia['titol'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($noticia['data_publicacio'], ENT_QUOTES, 'UTF-8')
        );
    }

    if ($items === '') {
        $items = '<li>No hi ha notícies disponibles.</li>';
    }

    $response->getBody()->write($htmlContent);
    return $response->withHeader('Content-Type', 'text/html');
});

$app->get('/noticia/{id:[0-9]+}', function (Request $request, Response $response, array $args) use ($pdo) {
    $stmt = $pdo->prepare('SELECT titol, cos, data_publicacio FROM noticies WHERE id = ?');
    $stmt->execute([$args['id']]);
    $noticia = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$noticia) {
        $response->getBody()->write('<h1>No s\'ha trobat la notícia</h1>');
        return $response->withStatus(404)->withHeader('Content-Type', 'text/html');
    }

    $htmlContent = "<!DOCTYPE html>
    <html lang='ca'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>" . htmlspecialchars($noticia['titol'], ENT_QUOTES, 'UTF-8') . "</title>
        <link rel='icon' type='image/svg+xml' href='/media/favicon.svg'>
        <link rel='stylesheet' href='/media/styles.css'>
    </head>
    <body>
        <div class='container'>
            <h1>" . htmlspecialchars($noticia['titol'], ENT_QUOTES, 'UTF-8') . "</h1>
            <p><small>Publicat el " . htmlspecialchars($noticia['data_publicacio'], ENT_QUOTES, 'UTF-8') . "</small></p>
            <div>" . nl2br(htmlspecialchars($noticia['cos'], ENT_QUOTES, 'UTF-8')) . "</div>
            <p><a href='/'>Tornar a la llista</a></p>
        </div>
    </body>
    </html>";
});