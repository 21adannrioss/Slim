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

// Funció per convertir URL de YouTube a format embed (per poder afegirlo al iframe)
function youtubeEmbedUrl(string $url): string {
    $videoId = '';
    if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})/', $url, $m)) {
        $videoId = $m[1];
    } elseif (preg_match('/[?&]v=([a-zA-Z0-9_-]{11})/', $url, $m)) {
        $videoId = $m[1];
    } elseif (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/', $url, $m)) {
        $videoId = $m[1];
    }
    return $videoId ? 'https://www.youtube.com/embed/' . $videoId : '';
}

// Ruta principal: llista de musics
$app->get('/', function (Request $request, Response $response) use ($pdo) {
    $stmt = $pdo->query('SELECT id, img_url, estil_musica, nom_music FROM musics ORDER BY id ASC');
    $musics = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $estils = [1=>'Rock', 2=>'Pop', 3=>'Jazz', 4=>'Hip-Hop', 5=>'Reggae', 6=>'Clàssica'];

    $cards = '';
    foreach ($musics as $music) {
        $estilNom = htmlspecialchars($estils[$music['estil_musica']] ?? $music['estil_musica'], ENT_QUOTES, 'UTF-8');
        $imgUrl = htmlspecialchars($music['img_url'], ENT_QUOTES, 'UTF-8');
        $nom = htmlspecialchars($music['nom_music'], ENT_QUOTES, 'UTF-8');
        $id = (int) $music['id'];
        $cards .= "
            <div class='card'>
                <a href='/music/{$id}'>
                    <img src='{$imgUrl}' alt='{$nom}'>
                    <div class='card-body'>
                        <h2>{$nom}</h2>
                        <span class='badge'>{$estilNom}</span>
                    </div>
                </a>
            </div>";
    }

    if ($cards === '') {
        $cards = '<p class="empty">No hi ha musics disponibles.</p>';
    }

    $htmlContent = '
    <!DOCTYPE html>
        <html lang="ca">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>TO BE SLIM OR NOT TO BE SLIM</title>
            <link rel="stylesheet" href="/css/styles.css">
        </head>
        <body>
            <header>
                <h1>Músics</h1>
            </header>
            <div class="grid">' . $cards . '</div>
            <footer><a href="https://github.com/21adannrioss" target="_blank">21adannrioss</a> | <a href="https://github.com/Xavi-H" target="_blank">Xavi-H</a></footer>
        </body>
    </html>';

    $response->getBody()->write($htmlContent);
    return $response->withHeader('Content-Type', 'text/html; charset=UTF-8');
});

$app->run();