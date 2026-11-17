<?php
namespace Starbug\Seeder;

use Faker\Generator;
use Starbug\Db\DatabaseInterface;
use Starbug\Operation\OperationFactoryInterface;

/**
 * Seeds rows through an application operation rather than a direct store.
 */
abstract class OperationSeed extends Seed {
  protected $operations;
  public function __construct(DatabaseInterface $db, Generator $faker, OperationFactoryInterface $operations) {
    $this->db = $db;
    $this->faker = $faker;
    $this->operations = $operations;
  }
  protected function operation($name, $data = []) {
    $data = $this->expandBundles($data + $this->definition());
    $operation = $this->operations->get($name);
    return $operation->execute($data);
  }
}
