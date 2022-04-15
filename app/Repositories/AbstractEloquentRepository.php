<?php

namespace App\Repositories;

use \Exception;

abstract class AbstractEloquentRepository
{
    protected $eloquent;

    protected function model()
    {
        if (!$this->eloquent) {
            throw new Exception('No eloquent set for ' . get_class($this));
        }
        return new $this->eloquent;
    }

    public function delete(int $id)
    {
        return $this->model()->destroy($id);
    }
}
