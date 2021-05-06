<?php

namespace App\Manager;

use App\AutoMapping;
use App\Entity\OrderEntity;
use App\Repository\OrderEntityRepository;
use App\Request\OrderCreateRequest;
use App\Request\OrderUpdateRequest;
use App\Request\OrderUpdateStateByCaptainRequest;
use App\Request\DeleteRequest;
use Doctrine\ORM\EntityManagerInterface;

class OrderManager
{
    private $autoMapping;
    private $entityManager;
    private $orderEntityRepository;

    public function __construct(AutoMapping $autoMapping, EntityManagerInterface $entityManager, OrderEntityRepository $orderEntityRepository)
    {
        $this->autoMapping = $autoMapping;
        $this->entityManager = $entityManager;
        $this->orderEntityRepository = $orderEntityRepository;
    }

    public function createOrder(OrderCreateRequest $request, $uuid, $subscribeId)
    {
        $request->setUuid($uuid);
        $request->setSubscribeId($subscribeId);
        $item = $this->autoMapping->map(OrderCreateRequest::class, OrderEntity::class, $request);

        $item->setDate($item->getDate());
        $item->setState('pending');
        
        $this->entityManager->persist($item);
        $this->entityManager->flush();
        $this->entityManager->clear();

        return $item;
    }

    public function getOrderById($orderId)
    {
        return $this->orderEntityRepository->getOrderById($orderId);
    }

    public function orderById($orderId)
    {
        return $this->orderEntityRepository->orderById($orderId);
    }

    public function getOrdersByOwnerID($userID)
    {
        return $this->orderEntityRepository->getOrdersByOwnerID($userID);
    }

    public function orderStatus($orderId)
    {
        return $this->orderEntityRepository->orderStatus($orderId);
    }

    public function closestOrders()
    {
        return $this->orderEntityRepository->closestOrders();
    }

    public function getPendingOrders()
    {
        return $this->orderEntityRepository->getPendingOrders();
    }

    public function update(OrderUpdateRequest $request)
    {
        $item = $this->orderEntityRepository->find($request->getId());
       

        if ($item) {
            $item = $this->autoMapping->mapToObject(OrderUpdateRequest::class, OrderEntity::class, $request, $item);

            $item->setUpdateDate($item->getUpdateDate());
            
            $this->entityManager->flush();
            $this->entityManager->clear();

            return $item;
        }
    }

    public function orderUpdateStateByCaptain(OrderUpdateStateByCaptainRequest $request)
    {
        $item = $this->orderEntityRepository->find($request->getId());
       
        if ($item) {
            $item = $this->autoMapping->mapToObject(OrderUpdateStateByCaptainRequest::class, OrderEntity::class, $request, $item);

            $item->setUpdateDate($item->getUpdateDate());
            
            $this->entityManager->flush();
            $this->entityManager->clear();

            return $item;
        }
    }
//مراجعة للحذف
    public function orderUpdateStateByCaptain2($orderID)
    {
        $item = $this->orderEntityRepository->find($orderID);
       

        if ($item) {
            $item->setState('on way to pick order');
            
            $this->entityManager->flush();
            $this->entityManager->clear();

            return $item;
        }
    }

    public function delete(DeleteRequest $request)
    {
        $entity = $this->orderEntityRepository->find($request->getId());
        if ($entity) {
        
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        }
        return $entity;
    }

    public function countAllOrders()
    {
        return $this->orderEntityRepository->countAllOrders();
    }

    public function countpendingOrders()
    {
        return $this->orderEntityRepository->countpendingOrders();
    }

    public function countOngoingOrders()
    {
        return $this->orderEntityRepository->countOngoingOrders();
    }

    public function countCancelledOrders()
    {
        return $this->orderEntityRepository->countCancelledOrders();
    }

    public function ongoingOrders()
    {
        return $this->orderEntityRepository->ongoingOrders();
    }
    
    public function getRecordsForCaptain($user)
    {
        return $this->orderEntityRepository->getRecordsForCaptain($user);
    }

    public function getOrders()
    {
        return $this->orderEntityRepository->getOrders();
    }

    public function countOrdersInMonthForOwner($fromDate, $toDate, $ownerId)
    {
        return $this->orderEntityRepository->countOrdersInMonthForOwner($fromDate, $toDate, $ownerId);
    }

    public function getAllOrders($fromDate, $toDate, $ownerId)
    {
        return $this->orderEntityRepository->getAllOrders($fromDate, $toDate, $ownerId);
    }

    public function getTopOwners($fromDate, $toDate)
    {
        return $this->orderEntityRepository->getTopOwners($fromDate, $toDate);
    }

    public function countOrdersInDay($ownerID, $fromDate, $toDate)
    {
        return $this->orderEntityRepository->countOrdersInDay($ownerID, $fromDate, $toDate);
    }

    public function getAcceptedOrderByCaptainId($captainID)
    {
        return $this->orderEntityRepository->getAcceptedOrderByCaptainId($captainID);
    }

    public function  countCaptainOrdersDelivered($captainId)
    {
        return $this->orderEntityRepository->countCaptainOrdersDelivered($captainId);
    }

    public function countOrdersInMonthForCaptain($fromDate, $toDate, $captainId)
    {
        return $this->orderEntityRepository->countOrdersInMonthForCaptain($fromDate, $toDate, $captainId);
    }

    public function getAcceptedOrderByCaptainIdInMonth($fromDate, $toDate, $captainId)
    {
        return $this->orderEntityRepository->getAcceptedOrderByCaptainIdInMonth($fromDate, $toDate, $captainId);
    }

    public function countCaptainOrdersInDay($captainID, $fromDate, $toDate)
    {
        return $this->orderEntityRepository->countCaptainOrdersInDay($captainID, $fromDate, $toDate);
    }
}
