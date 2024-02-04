<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class FlashMessageService
{
    public function __construct(
        protected RequestStack $requestStack,
    ) {
    }

    public function createFlashMessage(string $type, string $message): void
    {
        $request = $this->requestStack->getCurrentRequest();

        $request->getSession()->getFlashBag()->add($type, $message);
    }
}
