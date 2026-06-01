<?php

namespace App\Providers;

use App\Contracts\Repositories as RepositoryContracts;
use App\Contracts\Services as ServiceContracts;
use App\Repositories;
use App\Services;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        RepositoryContracts\BsuRepositoryInterface::class => Repositories\BsuRepository::class,
        RepositoryContracts\CarRepositoryInterface::class => Repositories\CarRepository::class,
        RepositoryContracts\ClasscomponentRepositoryInterface::class => Repositories\ClasscomponentRepository::class,
        RepositoryContracts\ClassrecipeRepositoryInterface::class => Repositories\ClassrecipeRepository::class,
        RepositoryContracts\CompmainRepositoryInterface::class => Repositories\CompmainRepository::class,
        RepositoryContracts\CompRepositoryInterface::class => Repositories\CompRepository::class,
        RepositoryContracts\ConsumptioncomponentweightRepositoryInterface::class => Repositories\ConsumptioncomponentweightRepository::class,
        RepositoryContracts\CurrentcomponentweightRepositoryInterface::class => Repositories\CurrentcomponentweightRepository::class,
        RepositoryContracts\CustomerRepositoryInterface::class => Repositories\CustomerRepository::class,
        RepositoryContracts\DispatcherRepositoryInterface::class => Repositories\DispatcherRepository::class,
        RepositoryContracts\DriverRepositoryInterface::class => Repositories\DriverRepository::class,
        RepositoryContracts\MainstateRepositoryInterface::class => Repositories\MainstateRepository::class,
        RepositoryContracts\MixtureRepositoryInterface::class => Repositories\MixtureRepository::class,
        RepositoryContracts\OrderRepositoryInterface::class => Repositories\OrderRepository::class,
        RepositoryContracts\OrderStateRepositoryInterface::class => Repositories\OrderStateRepository::class,
        RepositoryContracts\PlantRepositoryInterface::class => Repositories\PlantRepository::class,
        RepositoryContracts\ProductRepositoryInterface::class => Repositories\ProductRepository::class,
        RepositoryContracts\ReccommentRepositoryInterface::class => Repositories\ReccommentRepository::class,
        RepositoryContracts\RecfrostRepositoryInterface::class => Repositories\RecfrostRepository::class,
        RepositoryContracts\RecipeRepositoryInterface::class => Repositories\RecipeRepository::class,
        RepositoryContracts\RecipestateRepositoryInterface::class => Repositories\RecipestateRepository::class,
        RepositoryContracts\RecmarkaRepositoryInterface::class => Repositories\RecmarkaRepository::class,
        RepositoryContracts\RecmobilityRepositoryInterface::class => Repositories\RecmobilityRepository::class,
        RepositoryContracts\RecstrengthRepositoryInterface::class => Repositories\RecstrengthRepository::class,
        RepositoryContracts\RecwatRepositoryInterface::class => Repositories\RecwatRepository::class,
        RepositoryContracts\ReportcurrentloopRepositoryInterface::class => Repositories\ReportcurrentloopRepository::class,
        RepositoryContracts\ReportweightmanualRepositoryInterface::class => Repositories\ReportweightmanualRepository::class,
        RepositoryContracts\RoleRepositoryInterface::class => Repositories\RoleRepository::class,
        RepositoryContracts\SilcemRepositoryInterface::class => Repositories\SilcemRepository::class,
        RepositoryContracts\TtnRepositoryInterface::class => Repositories\TtnRepository::class,
        RepositoryContracts\TtnstateRepositoryInterface::class => Repositories\TtnstateRepository::class,
        RepositoryContracts\UserRepositoryInterface::class => Repositories\UserRepository::class,
        RepositoryContracts\WeightmanualRepositoryInterface::class => Repositories\WeightmanualRepository::class,
        RepositoryContracts\RecipedescriptionRepositoryInterface::class => Repositories\RecipedescriptionRepository::class,
        ServiceContracts\AnalizeTotalVByComponentsServiceInterface::class => Services\AnalizeTotalVByComponentsService::class,
        ServiceContracts\AnalizeTotalVServiceInterface::class => Services\AnalizeTotalVService::class,
        ServiceContracts\BsuSearchServiceInterface::class => Services\BsuSearchService::class,
        ServiceContracts\RecipeExportServiceInterface::class => Services\RecipeExportService::class,
        ServiceContracts\RecipeImportServiceInterface::class => Services\RecipeImportService::class,
    ];
}
