<?php
namespace App\Facades\Mail;

use App\Core\Auth;

require_once vendor_path('autoload.php');

class Mail
{
    protected static object $mailer;
    public object $message;
    
    public static function init(array $data = []): Mail
    {
        if(empty($data) === true) {
            $data = app('mail');
        }
        
        $transport = (new \Swift_SmtpTransport($data['smtp'], $data['port'], $data['ssl']))
            ->setUsername($data['user'])
            ->setPassword($data['password']);
        
        self::$mailer = new \Swift_Mailer($transport);
        
        return new self();
    }
    
    public function subject(string $subject)
    {
        $this->message = new \Swift_Message($subject);
        return $this;
    }
    
    public function from(array $from = [])
    {
        if(empty($from) === true) {
            $from = [app['mail']['from'] => app['mail']['fromName']];
        }
        
        $this->message->setFrom($from);
        return $this;
    }
    
    public function to(array $to)
    {
        $this->message->setTo($to);
        return $this;
    }
    
    public function body(string $body, bool $isTemplate = false)
    {
        $this->message->setBody($body);
        return $this;
    }
    
    public function send()
    {
        if(Auth::isLocalhost() === false)
            return self::$mailer->send($this->message);
    }
}
