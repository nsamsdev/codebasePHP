<?php

namespace CodeBase;

use CodeBase\Models\Admins;
use CodeBase\Models\CustomQuery;

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}
if (defined('cli')) {
    require __DIR__ . '/../src/Libraries/PHPMailer/PHPMailerAutoload.php';
} else {
    require CODEBASE . 'src/Libraries/PHPMailer/PHPMailerAutoload.php';
}

//use PHPMailer\PHPMailer;
class CustomEmailer
{
    /**
     * @var \PHPMailer
     */
    private $mailerInstance;

    /**
     * @var string
     */
    private $smtpHost = '';

    /**
     * @var int
     */
    private $smtpPort = 25;

    /**
     * @var string
     */
    private $smtpUser = '';

    /**
     * @var string
     */
    private $smtpPassword = '';

    /**
     * @var string
     */
    private $from = '';

    /**
     * @var
     */
    private $to;


    private $subject;

    /**
     * @var
     */
    private $message;

    /**
     * @var bool
     */
    private $debug = MAIL_DEBUG;

    /**
     * CustomEmailer constructor.
     */
    public function __construct()
    {
        $this->mailerInstance = new \PHPMailer();
        $this->mailerInstance->isSMTP();
        $this->mailerInstance->Host = $this->smtpHost;
        $this->mailerInstance->SMTPAuth = true;
        $this->mailerInstance->Username = $this->smtpUser;
        $this->mailerInstance->Password = $this->smtpPassword;
        $this->mailerInstance->Port = $this->smtpPort;
        $this->mailerInstance->isHTML(true);
        $this->mailerInstance->SMTPDebug = 1;
        $this->mailerInstance->SMTPSecure = '';
        $this->mailerInstance->SMTPAutoTLS = false;
        $this->mailerInstance->AltBody = 'This is the body in plain text for non-HTML mail clients';
    }

    /**
     *
     */
    public function resetSendingTo()
    {
        $this->mailerInstance->ClearAllRecipients();
    }


    /**
     * @param string $fromEmail
     */
    public function setFrom(string $fromEmail = '')
    {
        $this->mailerInstance->setFrom($this->from);
    }

    /**
     * @param string $toEmail
     */
    public function setTo(string $toEmail)
    {
        $this->to = $toEmail;
        $this->mailerInstance->addAddress($toEmail);
    }


    /**
     * @param string $subject
     */
    public function setSubject(string $subject)
    {
        $this->subject = $subject;
        $this->mailerInstance->Subject = $subject;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body)
    {
        $this->mailerInstance->Body = $body;
    }

    /**
     * @param array $extraOptions
     */
    public function addExtras(array $extraOptions)
    {
        foreach ($extraOptions as $option => $value) {
            $this->mailerInstance->{$option} = $value;
        }
    }


    /**
     * @return bool
     */
    public function send()
    {
        if (!$this->mailerInstance->send()) {
            if ($this->debug == true) {
                die($this->mailerInstance->ErrorInfo);
            }
            $to = $this->to;
            $subject = $this->subject;
            (new CustomQuery())->addEmailFailed($to, $subject);
            $this->mailerInstance->setTo((new Admins())->getAdminEmail());
            $this->mailerInstance->setSubject('Failed to send an email');
            $message = "Dear Admin <br /><br /> We Failed to send an email: <br />Subject: {$subject} <br />Was Sent To: {$to} <br /><br />Ethical Charter System";
            $this->mailerInstance->setBody();
            $this->mailerInstance->send();
            return false;
        }
        return true;
    }
}
