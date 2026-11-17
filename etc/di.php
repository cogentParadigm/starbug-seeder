<?php
namespace Starbug\Seeder;

use Faker\Factory;
use Psr\Container\ContainerInterface;

use function DI\add;
use function DI\get;

return [
  "db.seeds" => add([]),
  "Starbug\Seeder\SeederInterface" => function (ContainerInterface $c) {
    $seeder = $c->get(Seeder::class);
    $c->set(SeederInterface::class, $seeder);
    foreach ($c->get("db.seeds") as $seed) {
      $seeder->addSeed($seed);
    }
    return $seeder;
  },
  "scripts.seed" => Script\Seed::class,
  "Faker\Generator" => function () {
    return Factory::create();
  }
];
