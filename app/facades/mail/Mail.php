<?php

namespace App\Facades\Mail;

use App\Facades\Http\View;
use App\Facades\Url\Url;

class Mail
{
    protected static object $mailer;
    private \Swift_Message $message;

    public static function init(array $data = []): Mail
    {
        if (empty($data)) {
            $data = app('mail');
        }

        $transport = (new \Swift_SmtpTransport($data['smtp'], $data['port'], $data['ssl']))
            ->setUsername($data['user'])
            ->setPassword($data['password']);

        self::$mailer = new \Swift_Mailer($transport);

        return new self();
    }

    public function subject(string $subject): Mail
    {
        $this->message = new \Swift_Message($subject);
        return $this;
    }

    public function from(array $from = []): Mail
    {
        if (empty($from)) {
            $from = [app['mail']['from'] => app['mail']['fromName']];
        }

        $this->message->setFrom($from);
        return $this;
    }

    public function to(array $to): Mail
    {
        $this->message->setTo($to);
        return $this;
    }

    public function html(string $template, array $data = []): Mail
    {
        $this->message->setBody(View::mail($template, $data), 'text/html');
        return $this;
    }

    public function text(string $text): Mail
    {
        $this->message->setBody($text);
        return $this;
    }

    public function send()
    {
        if (Url::isLocalhost() === false) {
            return self::$mailer->send($this->message);
        }
    }
}
