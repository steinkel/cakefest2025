<?php

namespace App\Mcp;

use Cake\Mailer\Mailer;
use Cake\ORM\Locator\LocatorAwareTrait;
use Mcp\Capability\Attribute\McpTool;

class Customers
{
    use LocatorAwareTrait;

    /**
     * Send email to customer
     *
     * @param int $customerId
     * @param string $subject
     * @param string $message
     * @return array
     */
    #[McpTool(name: 'sendEmailToCustomer')]
    public function sendEmailToCustomer(int $customerId, string $subject, string $message): array
    {
        try {
            $customer = $this->fetchTable('Customers')->get($customerId);
            $mailer = new Mailer('default');
            $mailer->setFrom(['me@example.com' => 'My Site'])
                ->setTo($customer->email)
                ->setSubject($subject)
                ->deliver($message);
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Failed to send email to customer ' . $e->getMessage(),
            ];
        }

        return [
            'success' => true,
            'message' => 'Email sent successfully to customer ' . $customer->email,
        ];
    }
}
