<?php
namespace Starbug\Seeder;

interface SeederInterface {
  public function addSeed(SeedInterface $seed);
  public function seed();
}
