<?php

namespace App\Controller;

use App\Repository\OrganizationBillingRepository;
use App\Service\StripeApi;
use App\Value\AccessingMember;
use Psr\Log\LoggerInterface;
use Stripe\Webhook;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/billing')]
class BillingController extends AbstractController
{
    #[Route('/webhook', name: 'billing_webhook', methods: ['POST'])]
    public function webhook(
        Request $request,
        OrganizationBillingRepository $organizationBillingRepository,
        LoggerInterface $logger,
    )
    {
        // Verify the webhook signature

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

        $event = Webhook::constructEvent($payload, $sig_header, 'whsec_34f2eb931743ea7f4ee635d20347e42ede2896cc238195e05656a3e86c0a1f51');
        $logger->info(json_encode($event));
        // Handle the event
        switch ($event['type']) {
            case 'customer.subscription.updated':
                $customerId = $event['data']['object']['customer'];
                $organizationBilling = $organizationBillingRepository->findOneForCustomer($customerId);
                if ($event['data']['object']['status'] !== 'active') {
                    $organizationBilling->deactivate();
                    $organizationBillingRepository->save($organizationBilling);
                }
                break;
            case 'customer.subscription.deleted':
                $customerId = $event['data']['object']['customer'];
                $organizationBilling = $organizationBillingRepository->findOneForCustomer($customerId);
                $organizationBilling->deactivate();
                $organizationBillingRepository->save($organizationBilling);
                break;
            case 'customer.subscription.created':
                // Handle failed charge

                break;
            default:
                echo 'Unknown event: ' . $event['type'];
        }

        return new JsonResponse([]);
    }

    #[Route('/on-successful-checkout', name: 'billing_on_successful_checkout', methods: ['POST'])]
    public function onSuccessfulCheckout(
        AccessingMember $accessingMember,
        OrganizationBillingRepository $organizationBillingRepository,
        StripeApi $stripeApi,
    ): JsonResponse
    {
        $organizationBilling = $organizationBillingRepository->findOneForOrganization(
            $accessingMember->membership->getOrganization()
        );
        $customerId = $organizationBilling->getCustomerId();
        $activeSubscription = $stripeApi->getActiveSubscriptionForCustomer($customerId);
        if ($activeSubscription) {
            $organizationBilling->activate();
            $organizationBillingRepository->save($organizationBilling);
        }

        return new JsonResponse([
            'success' => $activeSubscription !== null,
        ]);
    }

    #[Route('/portal-url', name: 'billing_create_portal_session', methods: ['POST'])]
    public function createProduct(
        AccessingMember $accessingMember,
        OrganizationBillingRepository $organizationBillingRepository,
        StripeApi $stripeApi,
    ): JsonResponse
    {
        $organizationBilling = $organizationBillingRepository->findOneForOrganization(
            $accessingMember->membership->getOrganization()
        );
        $customerId = $organizationBilling->getCustomerId();
        $existingSubscriptions = $stripeApi->getSubscriptionForCustomer($customerId);

        $session = count($existingSubscriptions) > 0 ?
            $stripeApi->createPortalSession($customerId) :
            $stripeApi->createCheckoutSession($customerId, 'paid_monthly');

        return new JsonResponse([
            'url' => $session->url
        ]);
    }
}
