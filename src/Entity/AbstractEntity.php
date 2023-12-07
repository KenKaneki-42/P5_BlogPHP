<?php

namespace App\Entity;

class AbstractEntity
{
  public function __construct(array $data = [])
  {
    foreach ($data as $key => $value) {
      // check if we have a method set which map
      $method = "set" . ucfirst($key);
      if (method_exists($this, $method)) {
        $this->$method($value);
      }
    }
  }
}
