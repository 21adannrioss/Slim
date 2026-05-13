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

    // Conversió dels estils
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

    $htmlContingut = '
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

    $response->getBody()->write($htmlContingut);
    return $response->withHeader('Content-Type', 'text/html; charset=UTF-8');
});

// Ruta per al detall del music
$app->get('/music/{id:[0-9]+}', function (Request $request, Response $response, array $args) use ($pdo) {
    $stmt = $pdo->prepare('SELECT img_url, estil_musica, nom_music, albums, biografia, video FROM musics WHERE id = ?');
    $stmt->execute([$args['id']]);
    $music = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$music) {
        $response->getBody()->write("<h1>No s'ha trobat el music</h1>");
        return $response->withStatus(404)->withHeader('Content-Type', 'text/html; charset=UTF-8');
    }

    // Conversió dels estils
    $estils = [1=>'Rock', 2=>'Pop / Llatí', 3=>'Jazz', 4=>'Hip-Hop', 5=>'Reggae', 6=>'Clàssica'];

    $nom = htmlspecialchars($music['nom_music'], ENT_QUOTES, 'UTF-8');
    $img = htmlspecialchars($music['img_url'], ENT_QUOTES, 'UTF-8');
    $estil = htmlspecialchars($estils[(int)$music['estil_musica']] ?? $music['estil_musica'], ENT_QUOTES, 'UTF-8');
    $albums = htmlspecialchars($music['albums'], ENT_QUOTES, 'UTF-8');
    $bio = nl2br(htmlspecialchars($music['biografia'], ENT_QUOTES, 'UTF-8'));

    $videoBlock = '';
    if(!empty($music['video'])) {
        $embedUrl = youtubeEmbedUrl($music['video']);
        if($embedUrl) {
            $insertSegur  = htmlspecialchars($embedUrl, ENT_QUOTES, 'UTF-8');
            $videoBlock = "
    <div class='video-dest'>
        <h3>Video destacat</h3>
        <div class='iframe-container'>
            <iframe
                src='{$insertSegur}'
                title='Video de {$nom}'
                frameborder='0'
                allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture'
                allowfullscreen>
            </iframe>
        </div>
    </div>";
        }
    }

    $htmlContingut = '<!DOCTYPE html>
    <html lang="ca">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . $nom . ' - Musics</title>
        <link rel="stylesheet" href="/css/styles.css">
    </head>
    <body>
        <div class="back-bar">
            <a href="/">Tornar a la llista de musics</a>
        </div>
        <div class="container">
            <div class="hero">
                <img src="' . $img . '" alt="' . $nom . '">
                <div class="hero-info">
                    <h1>' . $nom . '</h1>
                    <span class="badge">' . $estil . '</span>
                    <div class="info-row"><strong>Albums:</strong>' . $albums . '</div>
                </div>
            </div>
            <div class="bio-section">
                <h3>&#128214; Biografia</h3>
                <p>' . $bio . '</p>
            </div>
            ' . $videoBlock . '
        </div>
    </body>
    </html>';

    $response->getBody()->write($htmlContingut);
    return $response->withHeader('Content-Type', 'text/html; charset=UTF-8');
});

$app->run();