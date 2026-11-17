<?php
namespace Starbug\Seeder;

use Exception;
use Faker\Generator;
use Starbug\Bundle\Bundle;
use Starbug\Db\DatabaseInterface;

/**
 * Seeds rows into a single table.
 *
 * Seeds run through DatabaseInterface::store, so column hooks such as slug
 * generation and validation apply exactly as they do for the admin forms.
 */
abstract class Seed implements SeedInterface {
  protected $table;
  protected $db;
  protected $faker;
  public function __construct(DatabaseInterface $db, Generator $faker) {
    $this->db = $db;
    $this->faker = $faker;
  }
  abstract public function seed();
  protected function definition() {
    return [];
  }
  protected function store($data = [], $from = "auto") {
    $data = $this->expandBundles($data + $this->definition());
    $this->db->store($this->table, $data, $from);
  }
  protected function create($match, $data = []) {
    $this->ifNotExists($match, function ($match) use ($data) {
      $this->store($match + $data, false);
    });
  }
  protected function ifNotExists($match, $callable) {
    $match = $this->expandBundles($match);
    $matched = $this->db->query($this->table)->conditions($match)->one();
    if (empty($matched)) {
      $callable($match);
    }
  }
  protected function expandBundles($values) {
    foreach ($values as $k => $v) {
      if ($v instanceof Bundle) {
        $values[$k] = $this->resolve($v);
      } elseif (is_array($v)) {
        $values[$k] = $this->expandBundles($v);
      }
    }
    return $values;
  }
  protected function resolve(Bundle $bundle) {
    $ref = $this->db->query($bundle->get("table"))->conditions($bundle->get("keys"))->one();
    if (empty($ref)) {
      throw new Exception("No seeded record to reference in ".$bundle->get("table").": ".json_encode($bundle->get("keys")));
    }
    return $ref["id"];
  }
}
