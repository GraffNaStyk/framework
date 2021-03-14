<?php

namespace App\Facades\Mail;

use App\Facades\Http\View;
use App\Facades\Url\Url;

class Mail
{
    protected static object $mailer;
    public object $message;
    
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
	
	public function body(string $body, bool $isTemplate = false): Mail
	{
		if ($isTemplate) {
			$this->message->setBody(View::mail('register'));
		} else {
			$this->message->setBody($body);
		}
		
		return $this;
	}
    
    public function send()
    {
        if (Url::isLocalhost() === false)
            return self::$mailer->send($this->message);
    }
}
