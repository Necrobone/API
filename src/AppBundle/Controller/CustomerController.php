<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Entity\CustomerOrder;
use AppBundle\Repository\CustomerOrderRepository;
use AppBundle\Repository\CustomerRepository;
use DateTimeImmutable;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

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
     * @var CustomerOrderRepository
     */
    private $customerOrderRepository;

    /**
     * CustomerController constructor.
     *
     * @param CustomerRepository $customerRepository
     * @param CustomerOrderRepository $customerOrderRepository
     */
    public function __construct(
        CustomerRepository $customerRepository,
        CustomerOrderRepository $customerOrderRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->customerOrderRepository = $customerOrderRepository;
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
        $violations = $this->validateStore($request);

        if (0 === $violations->count()) {
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
        } else {
            $firstError = $violations->get(0);
            $message = $firstError->getPropertyPath() . ': ' . $firstError->getMessage();

            return new View($message, Response::HTTP_NOT_ACCEPTABLE);
        }
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
        } else {
            $violations = $this->validateUpdate($request);

            if (0 === $violations->count()) {
                foreach ($request->request->all() as $key => $value) {
                    $customer->changeProperty($key, $value);
                }

                $this->customerRepository->add($customer);

                return new View("Customer Updated Successfully", Response::HTTP_OK);
            } else {
                $firstError = $violations->get(0);
                $message = $firstError->getPropertyPath() . ': ' . $firstError->getMessage();

                return new View($message, Response::HTTP_NOT_ACCEPTABLE);
            }
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
            // TODO: Transactions
            /** @var CustomerOrder $customerOrder */
            foreach ($customer->customerOrders() as $customerOrder) {
                $this->customerOrderRepository->delete($customerOrder);
            }

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
    public function customerOrdersShow(int $id)
    {
        /** @var Customer $customer */
        $customer = $this->customerRepository->find($id);
        if (null === $customer) {
            return new View("Customer id not found", Response::HTTP_NOT_FOUND);
        }
        return $customer->customerOrders();
    }

    /**
     * @Rest\Post("/customers/{id}/orders")
     *
     * @param int $id
     * @param Request $request
     *
     * @return View
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function customerOrdersStore(int $id, Request $request): View
    {
        /** @var Customer $customer */
        $customer = $this->customerRepository->find($id);

        if (null === $customer) {
            return new View("Customer id not found", Response::HTTP_NOT_FOUND);
        }

        $violations = $this->validateCustomerOrder($request);

        if (0 === $violations->count()) {
            $customer->addCustomerOrder(
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $request->get('orderDate')),
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $request->get('shippedDate')),
                $request->get('status'),
                $request->get('comments')
            );

            $this->customerRepository->add($customer);

            return new View("Customer Order Added Successfully", Response::HTTP_OK);
        } else {
            $firstError = $violations->get(0);
            $message = $firstError->getPropertyPath() . ': ' . $firstError->getMessage();

            return new View($message, Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    /**
     * @param Request $request
     *
     * @return ConstraintViolationListInterface
     */
    private function validateStore(Request $request): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        $groups = new GroupSequence(['Default', 'custom']);

        $string = [
            new NotBlank(),
            new Type(['type' => 'string']),
            new Length(['max' => 255])
        ];
        $email = [
            new NotBlank(),
            new Email(),
            new Length(['max' => 255])
        ];
        $addressLine2 = [
            new Optional([
                new NotBlank(),
                new Type(['type' => 'string']),
                new Length(['max' => 255])
            ]),
        ];

        $constraint = new Collection([
            'firstName'    => $string,
            'lastName'     => $string,
            'email'        => $email,
            'phone'        => $string,
            'addressLine1' => $string,
            'addressLine2' => $addressLine2,
            'city'         => $string,
            'state'        => $string,
            'postalCode'   => $string,
            'country'      => $string,
        ]);

        return $validator->validate($request->request->all(), $constraint, $groups);
    }

    /**
     * @param Request $request
     *
     * @return ConstraintViolationListInterface
     */
    private function validateUpdate(Request $request): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        $groups = new GroupSequence(['Default', 'custom']);

        $string = [
            new Optional([
                new NotBlank(),
                new Type(['type' => 'string']),
                new Length(['max' => 255])
            ]),
        ];
        $email = [
            new Optional([
                new NotBlank(),
                new Email(),
                new Length(['max' => 255])
            ]),
        ];

        $constraint = new Collection([
            'firstName'    => $string,
            'lastName'     => $string,
            'email'        => $email,
            'phone'        => $string,
            'addressLine1' => $string,
            'addressLine2' => $string,
            'city'         => $string,
            'state'        => $string,
            'postalCode'   => $string,
            'country'      => $string,
        ]);

        return $validator->validate($request->request->all(), $constraint, $groups);
    }

    /**
     * @param Request $request
     *
     * @return ConstraintViolationListInterface
     */
    private function validateCustomerOrder(Request $request): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        $groups = new GroupSequence(['Default', 'custom']);

        $string = [
            new NotBlank(),
            new Type(['type' => 'string']),
            new Length(['max' => 255])
        ];

        $date = [
            new NotBlank(),
            new DateTime()
        ];

        $constraint = new Collection([
            'orderDate'   => $date,
            'shippedDate' => $date,
            'status'      => $string,
            'comments'    => $string,
        ]);

        return $validator->validate($request->request->all(), $constraint, $groups);
    }
}
