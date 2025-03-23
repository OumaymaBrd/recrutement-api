<?php

namespace App\Services;

use App\Models\Notification;
use App\Repositories\CandidatureRepositoryInterface;

class NotificationService
{
    protected $candidatureRepository;

    public function __construct(CandidatureRepositoryInterface $candidatureRepository)
    {
        $this->candidatureRepository = $candidatureRepository;
    }

    public function notifyCandidatureStatusChange($candidatureId, $statut)
    {
        $candidature = $this->candidatureRepository->find($candidatureId);
        
        $notification = new Notification([
            'user_id' => $candidature->user_id,
            'type' => 'statut_candidature',
            'message' => "Le statut de votre candidature a été mis à jour: $statut",
            'notifiable_id' => $candidature->id,
            'notifiable_type' => get_class($candidature),
        ]);
        
        $candidature->notifications()->save($notification);
        
        // Ici, vous pourriez également envoyer un email
        
        return $notification;
    }
}