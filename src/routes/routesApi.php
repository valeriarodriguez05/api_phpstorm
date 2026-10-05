<?php

declare(strict_types=1);

use App\Repository\ArtistRepository;
use App\Repository\AlbumRepository;
use App\Repository\RatingRepository;
use App\Middleware\JwtMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {

    $app->group('/api', function (Group $group) {

        // ---------- ARTISTS ----------

        $group->get('/artists', function (Request $request, Response $response) {
            $repo = $this->get(ArtistRepository::class);   // PHP-DI injecte PDO tout seul
            $response->getBody()->write(json_encode($repo->findAll()));   // objets Artist → JSON
            return $response->withHeader('Content-Type', 'application/json');
        });

        // Routes spécifiques AVANT /artists/{id}
        $group->get('/artists/annees', function (Request $request, Response $response) {
            $repo = $this->get(ArtistRepository::class);
            $response->getBody()->write(json_encode($repo->findAllAnnees()));
            return $response->withHeader('Content-Type', 'application/json');
        });

        $group->get('/artists/villes', function (Request $request, Response $response) {
            $repo = $this->get(ArtistRepository::class);
            $response->getBody()->write(json_encode($repo->findAllVilles()));
            return $response->withHeader('Content-Type', 'application/json');
        });

        $group->get('/artists/{id}', function (Request $request, Response $response, array $args) {
            $artist = $this->get(ArtistRepository::class)->findById((int) $args['id']);
            $response->getBody()->write(json_encode($artist ?? ['error' => 'Artiste introuvable']));
            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus($artist ? 200 : 404);
        });

        $group->post('/artists', function (Request $request, Response $response) {
            $id = $this->get(ArtistRepository::class)->insert((array) $request->getParsedBody());
            $response->getBody()->write(json_encode(['idArtist' => $id]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        });

        // ---------- ALBUMS ----------

        $group->get('/albums', function (Request $request, Response $response) {
            $repo = $this->get(AlbumRepository::class);
            $response->getBody()->write(json_encode($repo->findAll()));
            return $response->withHeader('Content-Type', 'application/json');
        });

        // Spécifique AVANT /albums/{id}
        $group->get('/albums/artist/{artistId}', function (Request $request, Response $response, array $args) {
            $repo = $this->get(AlbumRepository::class);
            $response->getBody()->write(json_encode($repo->findByArtistId((int) $args['artistId'])));
            return $response->withHeader('Content-Type', 'application/json');
        });

        $group->get('/albums/{id}', function (Request $request, Response $response, array $args) {
            $album = $this->get(AlbumRepository::class)->findById((int) $args['id']);
            $response->getBody()->write(json_encode($album ?? ['error' => 'Album introuvable']));
            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus($album ? 200 : 404);
        });

        $group->post('/albums', function (Request $request, Response $response) {
            $id = $this->get(AlbumRepository::class)->insert((array) $request->getParsedBody());
            $response->getBody()->write(json_encode(['idAlbums' => $id]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        });

        // ---------- RATINGS ----------

        $group->get('/ratings', function (Request $request, Response $response) {
            $repo = $this->get(RatingRepository::class);
            $response->getBody()->write(json_encode($repo->findAll()));
            return $response->withHeader('Content-Type', 'application/json');
        });

        // Spécifique AVANT /ratings/{id}
        $group->get('/ratings/album/{albumId}', function (Request $request, Response $response, array $args) {
            $repo = $this->get(RatingRepository::class);
            $response->getBody()->write(json_encode($repo->findByAlbumId((int) $args['albumId'])));
            return $response->withHeader('Content-Type', 'application/json');
        });

        $group->get('/ratings/{id}', function (Request $request, Response $response, array $args) {
            $rating = $this->get(RatingRepository::class)->findById((int) $args['id']);
            $response->getBody()->write(json_encode($rating ?? ['error' => 'Note introuvable']));
            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus($rating ? 200 : 404);
        });

        $group->post('/ratings', function (Request $request, Response $response) {
            $id = $this->get(RatingRepository::class)->insert((array) $request->getParsedBody());
            $response->getBody()->write(json_encode(['idRatings' => $id]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        });

    })->add(new JwtMiddleware());   // toutes les routes /api sont protégées par le JWT
};