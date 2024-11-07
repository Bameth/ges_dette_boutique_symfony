<?php

namespace App\EventSubscriber;

use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use App\Form\UserType;

class FormClientSubscriber implements EventSubscriberInterface
{
    public function onFormPreSubmit(PreSubmitEvent $event): void
    {
        $formData = $event->getData();
        $form = $event->getForm();
        
        if (isset($formData['toggleUser']) && $formData['toggleUser'] == "on") {
            $form->add('user', UserType::class);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'form.pre_submit' => 'onFormPreSubmit',
            'form.pre_set_data' => 'onFormPreSetData',
        ];
    }

    public function onFormPreSetData(PreSetDataEvent $event): void
    {
        // Gérer la pré-configuration des données si nécessaire
    }
}
