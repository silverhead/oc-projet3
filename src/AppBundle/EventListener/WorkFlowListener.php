<?php

namespace AppBundle\EventListener;

use AppBundle\Service\FindBookingsInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Router;

class WorkFlowListener
{
    const HOME_PAGE = 'homepage';
    const WDT_ROUTE = '_wdt';
    const PROFILE_ROUTE = '_profiler';
    const TWIG_ERROR_TEST = '_twig_error_test';

    protected $router;
    protected $findBooking;

    public function __construct(Router $router, FindBookingsInterface $findBooking)
    {
        $this->router = $router;
        $this->findBooking = $findBooking;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }
        //get request format (json, html, etc...)
        $format = $event->getRequest()->getRequestFormat();

        //test if it'is JSON format them ok
        if('json' === $format){
            return;
        }

        //get current route
        $route = $event->getRequest()->attributes->get('_route');

        //in dev environnement display toolbar
        if(preg_match('/^'.self::PROFILE_ROUTE.'/', $route)){
            return;
        }
        //in dev environnement display toolbar
        if(preg_match('/^'.self::WDT_ROUTE.'/', $route)){
            return;
        }

        //test if the current route is the homepage, if it's the case them return (workflow start : ok)
        if (self::HOME_PAGE === $route){
            return;
        }

        //get curent booking entity
        $booking = $this->findBooking->getCurrentBooking();

        //test if the current booking has persisted, if not them the workflow has corrupted and force to redirect on
        //the homepage
        if (null === $booking->getId()){
            $event->setResponse(
                new RedirectResponse(
                    $this->router->generate(
                        self::HOME_PAGE
                    )
                )
            );
        }
    }
}