<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use function json_last_error;
use function json_last_error_msg;

class ControllerSubscriber implements EventSubscriberInterface
{
    /**
     * Replace data in Request's \Symfony\Component\HttpFoundation\ParameterBag when 
     * 
     * @todo add jsonable controller interfase
     * 
     * @param ControllerEvent $event
     * @return void
     * @throws BadRequestHttpException
     */
    public function onControllerEvent(ControllerEvent $event) : void
    {
        $request = $event->getRequest();
        
        if ($request->isMethod(Request::METHOD_POST)) 
        {
            if($request->getContentType() != 'json' || !$request->getContent()) 
            {
                return;
            }
            
            $data = json_decode($request->getContent(), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) 
            {
                throw new BadRequestHttpException('invalid json body: ' . json_last_error_msg());
            }
            
            $request->request->replace(is_array($data) ? $data : []);
        }
    }

    /**
     * @see EventSubscriberInterface
     * @return array
     */
    public static function getSubscribedEvents() : array
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
