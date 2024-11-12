<?php

namespace App\Service;

use Stripe\StripeClient;

class StripeApi
{
    private readonly StripeClient $client;

    public function __construct(string $stripeApiKey)
    {
        $this->client = new StripeClient($stripeApiKey);
    }

    public function createCustomer(string $name, string $email, int $organizationId)
    {
        $customer = $this->client->customers->create([
            'email' => $email,
            'name' => $name,
            'metadata' => [
                'organization_id' => "org$organizationId",
            ]
        ]);

        return $customer->id;
    }

    public function createPortalSession(string $customerId)
    {
        return $this->client->billingPortal->sessions->create([
            'customer' => $customerId,
            'return_url' => 'http://localhost:5173/app/billing?fromPortal=true',
        ]);
    }

    public function getSubscriptionForCustomer(string $customerId)
    {
        return $this->client->subscriptions->all([
            'customer' => $customerId,
        ])->data;
    }

    public function getActiveSubscriptionForCustomer(string $customerId)
    {
        $subscriptions = $this->client->subscriptions->all([
            'customer' => $customerId,
            'status' => 'active'
        ])->data;

        return count($subscriptions) === 1 ? $subscriptions[0] : null;
    }

    public function createCheckoutSession(string $customerId, string $priceLookupKey)
    {
        $prices = $this->client->prices->all([
            'lookup_keys' => [$priceLookupKey]
        ]);
        if (count($prices) === 0) {
            throw new \DomainException('Missing product with lookup_key ' . $priceLookupKey);
        }

        return $this->client->checkout->sessions->create([
            'success_url' => 'http://localhost:5173/app/billing?checkoutSuccess=true',
            'line_items' => [
                [
                    'price' => $prices->data[0]->id,
                    'quantity' => 1,
                ],
            ],
            'mode' => 'subscription',
            'customer' => $customerId,
        ]);
    }
}
