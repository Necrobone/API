<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Repository\CustomerRepository;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CustomerController
 *
 * @package AppBundle\Controller
 */
class CustomerController extends AbstractFOSRestController
{
    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * CustomerController constructor.
     *
     * @param CustomerRepository $customerRepository
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @Rest\Get("/customers")
     *
     * @return View|array
     */
    public function index()
    {
        $customers = $this->customerRepository->findAll();
        if (true === empty($customers)) {
            return new View("Customers not found", Response::HTTP_NOT_FOUND);
        }
        return $customers;
    }

    /**
     * @Rest\Get("/customers/{id}")
     *
     * @param int $id
     *
     * @return View|object
     */
    public function show(int $id)
    {
        /** @var Customer $customer */
        $customer = $this->customerRepository->find($id);
        if (null === $customer) {
            return new View("Customer id not found", Response::HTTP_NOT_FOUND);
        }
        return $customer;
    }

    /**
     * @Rest\Post("/customers")
     *
     * @param Request $request
     *
     * @return View
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function store(Request $request): View
    {
        if (empty($request->get('firstName')) ||
            empty($request->get('lastName')) ||
            empty($request->get('email')) ||
            empty($request->get('phone')) ||
            empty($request->get('addressLine1')) ||
            empty($request->get('city')) ||
            empty($request->get('state')) ||
            empty($request->get('postalCode')) ||
            empty($request->get('country'))) {
            return new View("Missing information", Response::HTTP_NOT_ACCEPTABLE);
        }

        $customer = new Customer(
            $request->get('firstName'),
            $request->get('lastName'),
            $request->get('email'),
            $request->get('phone'),
            $request->get('addressLine1'),
            $request->get('addressLine2'),
            $request->get('city'),
            $request->get('state'),
            $request->get('postalCode'),
            $request->get('country')
        );

        $this->customerRepository->add($customer);

        return new View("Customer Added Successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/customers/{id}")
     *
     * @param int $id
     * @param Request $request
     *
     * @return View
     * @throws OptimisticLockException
     */
    public function update(int $id, Request $request): View
    {
        /** @var Customer $customer */
        $customer = $this->customerRepository->find($id);
        if (empty($customer)) {
            return new View("Customer not found", Response::HTTP_NOT_FOUND);
        } elseif ($request->get('firstName')) {
            $customer->changeFirstName($request->get('firstName'));
            $this->customerRepository->add($customer);
            return new View("Customer Updated Successfully", Response::HTTP_OK);
        } else {
            return new View("Nothing to update", Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    /**
     * @Rest\Delete("/customers/{id}")
     *
     * @param int $id
     *
     * @return View
     * @throws OptimisticLockException
     */
    public function destroy(int $id): View
    {
        /** @var Customer $customer */
        $customer = $this->customerRepository->find($id);
        if (empty($customer)) {
            return new View("Customer not found", Response::HTTP_NOT_FOUND);
        } else {
            $this->customerRepository->delete($customer);
            return new View("Customer Deleted Successfully", Response::HTTP_OK);
        }
    }

    /**
     * @Rest\Get("/customers/{id}/orders")
     *
     * @param int $id
     *
     * @return View|object
     */
    public function orders(int $id)
    {
        /** @var Customer $customer */
        $customer = $this->customerRepository->find($id);
        if (null === $customer) {
            return new View("Customer id not found", Response::HTTP_NOT_FOUND);
        }
        return $customer->customerOrders();
    }
}