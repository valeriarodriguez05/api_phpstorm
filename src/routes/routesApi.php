<?php

declare(strict_types=1);

use App\Repository\ArtistRepository;
use App\Middleware\JwtMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {

    $app->group('/api', function (Group $group) {

        $group->get('/artists', function (Request $request, Response $response) {
            $repo = $this->get(ArtistRepository::class);   // PHP-DI injecte PDO tout seul
            $response->getBody()->write(json_encode($repo->findAll()));   // objets Artist → JSON
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

    })->add(new JwtMiddleware());   // toutes les routes /api sont protégées par le JWT
};