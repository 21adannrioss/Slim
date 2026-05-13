<?php
$db = new SQLite3(__DIR__ . '/musics.db'); // Connexió a la base de dades SQLite


// Crear la taula musics
$db->exec("CREATE TABLE IF NOT EXISTS musics (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    img_url TEXT,
    estil_musica INTEGER,
    nom_music TEXT,
    albums INTEGER,
    biografia TEXT
)");

$db->exec("INSERT INTO musics (img_url, estil_musica, nom_music, albums, biografia) VALUES
('https://example.com/freddie.jpg', 1, 'Freddie Mercury', 15, 'Cantant britànic i líder de Queen, conegut per la seva veu poderosa i presència escènica única.'),
('https://example.com/shakira.jpg', 2, 'Shakira', 12, 'Cantant colombiana de pop i música llatina amb gran èxit internacional.'),
('https://example.com/miles.jpg', 3, 'Miles Davis', 50, 'Trompetista i compositor nord-americà, figura clau en la història del jazz.'),
('https://example.com/eminem.jpg', 4, 'Eminem', 11, 'Raper i productor dels Estats Units, considerat un dels millors artistes del hip-hop.'),
('https://example.com/bobmarley.jpg', 5, 'Bob Marley', 13, 'Icona jamaicana del reggae i símbol de pau i resistència cultural.'),
('https://example.com/mozart.jpg', 6, 'Wolfgang Amadeus Mozart', 600, 'Compositor austríac del període clàssic, considerat un geni de la música universal.')
");