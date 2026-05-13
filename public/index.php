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
    $stmt = $pdo->query('SELECT id, img_url, estil_musica, nom_music FROM musics ORDER BY id DESC');
    $musics = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $items = '';
    foreach ($musics as $music) {
        $items .= sprintf(
            '<li><a href="/music/%s">%s</a> <small>(%s)</small></li>',
            htmlspecialchars($music['id'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($music['nom_music'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($music['estil_musica'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($music['img_url'], ENT_QUOTES, 'UTF-8')
        );
    }

    if ($items === '') {
        $items = '<li>No hi ha musics disponibles.</li>';
    }

    $response->getBody()->write($htmlContent);
    return $response->withHeader('Content-Type', 'text/html');
});

$app->get('/music/{id:[0-9]+}', function (Request $request, Response $response, array $args) use ($pdo) {
    $stmt = $pdo->prepare('SELECT img_url, estil_musica, nom_music, albums, biografia FROM musics WHERE id = ?');
    $stmt->execute([$args['id']]);
    $music = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$music) {
        $response->getBody()->write('<h1>No s\'ha trobat la música</h1>');
        return $response->withStatus(404)->withHeader('Content-Type', 'text/html');
    }

    $htmlContent = "<!DOCTYPE html>
    <html lang='ca'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>" . htmlspecialchars($music['nom_music'], ENT_QUOTES, 'UTF-8') . "</title>
        <link rel='icon' type='image/svg+xml' href='/media/favicon.svg'>
        <link rel='stylesheet' href='/media/styles.css'>
    </head>
    <body>
        <div class='container'>
            <h1>" . htmlspecialchars($music['nom_music'], ENT_QUOTES, 'UTF-8') . "</h1>
            <div><img src='" . htmlspecialchars($music['img_url'], ENT_QUOTES, 'UTF-8') . "' alt='" . htmlspecialchars($music['nom_music'], ENT_QUOTES, 'UTF-8') . "'></div>
            <p>Estil de música: " . htmlspecialchars($music['estil_musica'], ENT_QUOTES, 'UTF-8') . "</p>
            <p>Àlbums: " . htmlspecialchars($music['albums'], ENT_QUOTES, 'UTF-8') . "</p>
            <p>Biografia: " . nl2br(htmlspecialchars($music['biografia'], ENT_QUOTES, 'UTF-8')) . "</p>
            <p><a href='/'>Tornar a la llista</a></p>
        </div>
    </body>
    </html>";
});