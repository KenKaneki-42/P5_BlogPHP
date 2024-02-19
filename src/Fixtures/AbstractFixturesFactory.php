<?php

namespace App\Fixtures;

use Faker\Factory as FakerFactory;
use Faker\Generator;

class AbstractFixturesFactory
{
  protected Generator $faker;
  protected array $attributes;
  protected \DateTime $publishedAt;
  protected \DateTime $createdAt;
  protected FakerFactory $fakerFactory;

  public function __construct(array $attributes = [])
  {
    $this->faker = FakerFactory::create();
    $this->attributes = $attributes;
    $this->createdAt = $this->faker->dateTimeBetween('now', '+10 days');
    $this->publishedAt = $this->faker->dateTimeBetween('+10 days', '+30 days');
  }
}
