<?php
$db = new SQLite3(__DIR__ . '/musics.db'); // Connexió a la base de dades SQLite


// Crear la taula musics
$db->exec("CREATE TABLE IF NOT EXISTS musics (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    img_url TEXT,
    estil_musica INTEGER,
    nom_music TEXT,
    albums INTEGER,
    biografia TEXT,
    video TEXT
)");

$db->exec("INSERT INTO musics (img_url, estil_musica, nom_music, albums, biografia, video) VALUES
('https://metalcry.com/wp-content/uploads/2021/11/Freddie-Mercury-scaled.jpg', 1, 'Freddie Mercury', 15, 'Cantant britànic i líder de Queen, conegut per la seva veu poderosa i presència escènica única.', 'https://youtu.be/vbvyNnw8Qjg?si=g1oDp0av3pHRzZGD'),
('https://upload.wikimedia.org/wikipedia/commons/0/0b/2023-11-16_Gala_de_los_Latin_Grammy%2C_03_%28cropped%2901.jpg', 2, 'Shakira', 12, 'Cantant colombiana de pop i música llatina amb gran èxit internacional.', 'https://youtu.be/PWmJhh_qTSY?si=Hzo3xhBTy26kXV_c'),
('https://i.discogs.com/UYL6EF3ktq9ROohUl0gFSrgrnrNEYVCsh2qYXaU3F3I/rs:fit/g:sm/q:40/h:300/w:300/czM6Ly9kaXNjb2dz/LWRhdGFiYXNlLWlt/YWdlcy9BLTIzNzU1/LTE3NjI4NjQzNDkt/MjU4NC5qcGVn.jpeg', 3, 'Miles Davis', 50, 'Trompetista i compositor nord-americà, figura clau en la història del jazz.', 'https://youtu.be/afiJrULYIm8?si=2mghZIjRhwcWhBlT'),
('https://cdn.shopify.com/s/files/1/0759/8840/2471/files/eminem-mm-marshall-mathers-slim-shady-2002-live-concert-stage-set-album_1024x1024.jpg?v=1698937782', 4, 'Eminem', 11, 'Raper i productor dels Estats Units, considerat un dels millors artistes del hip-hop.', 'https://youtu.be/xFYQQPAOz7Y?si=GJ6SnZI4OCn2OnSI'),
('https://mallorcamusicmagazine.com/wp-content/uploads/Bob-Marley-1024x683.webp', 5, 'Bob Marley', 13, 'Icona jamaicana del reggae i símbol de pau i resistència cultural.', 'https://youtu.be/yv5xonFSC4c?list=RDyv5xonFSC4c'),
('https://www.dallassymphony.org/wp-content/uploads/2022/04/Wolfgang-Amadeus-Mozart.jpg', 6, 'Wolfgang Amadeus Mozart', 600, 'Compositor austríac del període clàssic, considerat un geni de la música universal.', 'https://youtu.be/RdUyf6FLLTQ?list=RDRdUyf6FLLTQ')
");