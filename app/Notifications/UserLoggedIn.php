<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserLoggedIn extends Notification
{
    use Queueable;

    protected $user;
    protected $ip;

    public function __construct($user, $ip)
    {
        $this->user = $user;
        $this->ip = $ip;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $location = $this->getLocationFromIp($this->ip);

        return (new MailMessage)
            ->subject('Novo Login Realizado')
            ->line('O usuário ' . $this->user->name . ' acabou de fazer login.')
            ->line('Email: ' . $this->user->email)
            ->line('IP: ' . $this->ip)
            ->line('Localização aproximada: ' . $location)
            ->line('Data: ' . now()->toDateTimeString());
    }

    protected function getLocationFromIp($ip)
    {
        try {
            $response = Http::get("http://ip-api.com/json/{$ip}");
            $data = $response->json();
            
            if ($data['status'] === 'success') {
                return "{$data['city']}, {$data['regionName']}, {$data['country']}";
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return 'Não disponível';
    }
}