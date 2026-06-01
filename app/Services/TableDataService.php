<?php

namespace App\Services;

use App\Contracts\Repositories\BsuRepositoryInterface;
use App\Contracts\Repositories\CarRepositoryInterface;
use App\Contracts\Repositories\CompRepositoryInterface;
use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\DispatcherRepositoryInterface;
use App\Contracts\Repositories\DriverRepositoryInterface;
use App\Contracts\Repositories\MixtureRepositoryInterface;
use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Repositories\OrderStateRepositoryInterface;
use Illuminate\Support\Collection;

class TableDataService
{
    public function __construct(
        private CarRepositoryInterface $carRepository,
        private BsuRepositoryInterface $bsuRepository,
        private CompRepositoryInterface $compRepository,
        private CustomerRepositoryInterface $customerRepository,
        private DispatcherRepositoryInterface $dispatcherRepository,
        private DriverRepositoryInterface $driverRepository,
        private MixtureRepositoryInterface $mixtureRepository,
        private OrderRepositoryInterface $orderRepository,
        private OrderStateRepositoryInterface $orderStateRepository
    ) {}

    public function getAllTablesData(): array
    {
        return [
            'cars' => $this->getAllCars(),
            'bsu' => $this->getAllBsu(),
            'comp' => $this->getAllComp(),
            'customers' => $this->getAllCustomers(),
            'dispatchers' => $this->getAllDispatchers(),
            'drivers' => $this->getAllDrivers(),
            'mixtures' => $this->getAllMixtures(),
            'orders' => $this->getAllOrders(),
            'order_states' => $this->getAllOrderStates(),
        ];
    }

    public function getAllCars(): Collection
    {
        return $this->carRepository->all();
    }

    public function getAllBsu(): Collection
    {
        return $this->bsuRepository->all();
    }

    public function getAllComp(): Collection
    {
        return $this->compRepository->all();
    }

    public function getAllCustomers(): Collection
    {
        return $this->customerRepository->all();
    }

    public function getAllDispatchers(): Collection
    {
        return $this->dispatcherRepository->all();
    }

    public function getAllDrivers(): Collection
    {
        return $this->driverRepository->all();
    }

    public function getAllMixtures(): Collection
    {
        return $this->mixtureRepository->all();
    }

    public function getAllOrders(): Collection
    {
        return $this->orderRepository->all();
    }

    public function getAllOrderStates(): Collection
    {
        return $this->orderStateRepository->all();
    }

    public function getOrderById(int $id)
    {
        $order = $this->orderRepository->find($id);
        $order->load(['customer', 'orderStates', 'bsu']);

        return $order;
    }

    public function getCarById(int $id)
    {
        return $this->carRepository->find($id);
    }

    public function getOrdersByState(string $state)
    {
        return $this->orderRepository->getByState($state);
    }

    public function searchOrdersByDate(string $start, string $end)
    {
        return $this->orderRepository->getByDateRange($start, $end);
    }

    public function getDashboardStats(): array
    {
        return [
            'total_cars' => $this->carRepository->all()->count(),
            'total_bsu' => $this->bsuRepository->all()->count(),
            'total_customers' => $this->customerRepository->all()->count(),
            'total_drivers' => $this->driverRepository->all()->count(),
            'total_orders' => $this->orderRepository->all()->count(),
            'total_mixtures' => $this->mixtureRepository->all()->count(),
            'active_orders' => method_exists($this->orderRepository, 'getActiveOrdersCount') ? $this->orderRepository->getActiveOrdersCount() : 0,
            'recent_orders' => method_exists($this->orderRepository, 'getRecentOrders') ? $this->orderRepository->getRecentOrders(10) : [],
        ];
    }
}
