<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface OrderRepositoryInterface extends RepositoryInterface
{
    public function getAllWithSpecificFields(): Collection;

    /**
     * Search Order by multiple criteria
     *
     * @param  array  $criteria  Search criteria (id, idCustomer, idPlant, dispatcher, nameRecipe, dateStart, dateFinish)
     */
    public function search(array $criteria): Collection;
  
}