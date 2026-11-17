<?php
namespace Starbug\Seeder;

class Seeder implements SeederInterface {
  protected $seeds = [];
  public function addSeed(SeedInterface $seed) {
    $this->seeds[] = $seed;
    return $this;
  }
  public function seed() {
    foreach ($this->seeds as $seed) {
      $seed->seed();
    }
  }
}
