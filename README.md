# 🎵 TO BE SLIM OR NOT TO BE SLIM 🎵

Web de biografies de músics construïda amb **Slim Framework (PHP)** amb un disseny fosc i atractiu.

---

## Vídeo de demostració

[![0613-A20](https://img.youtube.com/vi/UwM036UZyC0/maxresdefault.jpg)](https://www.youtube.com/watch?v=UwM036UZyC0)

---

## Descripció

Aquest projecte és un lloc web de consulta de biografies de músics famosos. Els usuaris poden:

- Veure una graella de targetes amb tots els músics disponibles
- Consultar la biografia completa de cada músic
- Veure un vídeo destacat de YouTube incrustat per a cada artista

---

## Músics inclosos

| # | Artista | Estil Musical |
|---|---------|---------------|
| 1 | Freddie Mercury | Rock |
| 2 | Shakira | Pop / Llatí |
| 3 | Miles Davis | Jazz |
| 4 | Eminem | Hip-Hop |
| 5 | Bob Marley | Reggae |
| 6 | Wolfgang Amadeus Mozart | Clàssica |

---

## Estructura del projecte

```
Slim/
├── dataBase/
│   └── musics.db          # Base de dades SQLite amb les biografies
├── public/
│   ├── index.php          # Punt d'entrada + totes les rutes Slim
│   └── css/
│       └── styles.css     # Estils (dark theme, grid, detall)
├── Slim/                  # Codi font del framework Slim
├── vendor/                # Dependències de Composer
├── composer.json
└── README.md
```

---

## Instal·lació i execució

### Passos

```bash
# 1. Clona el repositori
git clone https://github.com/21adannrioss/Slim.git
cd Slim

# 2. Instal·la les dependències
composer install

# 3. Arrenca el servidor de desenvolupament
php -S localhost:8080 -t public/
```

Obre el navegador a **http://localhost:8080** i ja pots navegar pel lloc.

---

## Rutes de l'aplicació

| Mètode | Ruta | Descripció |
|--------|------|------------|
| `GET` | `/` | Pàgina principal amb la graella de músics |
| `GET` | `/music/{id}` | Pàgina de detall d'un músic concret |

---

## Autors

| Usuari | GitHub |
|--------|--------|
| adannrioss | [@21adannrioss](https://github.com/21adannrioss) |
| Xavi-H | [@Xavi-H](https://github.com/Xavi-H) |