<?php
namespace Starbug\Seeder\Script;

use Psr\Container\ContainerInterface;
use Starbug\Seeder\SeederInterface;

class Seed {
  public function __construct(
    protected ContainerInterface $container,
    protected SeederInterface $seeder
  ) {
  }
  public function __invoke($positional = []) {
    $seeder = empty($positional) ? $this->seeder : $this->container->get($positional[0]);
    $seeder->seed();
  }
}
