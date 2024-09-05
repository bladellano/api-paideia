<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class StudentCreated extends Notification
{
    use Queueable;

    protected $student;

    public function __construct($student)
    {
        $this->student = $student;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Novo Estudante Criado')
            ->line('Um novo estudante foi criado com os seguintes detalhes:')
            ->line('Nome: ' . $this->student->name)
            ->line('Email: ' . $this->student->email)
            ->line('Data: ' . now()->toDateTimeString());
    }
}
