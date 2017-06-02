<?php

namespace CodeBase;

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}

use SendGrid\Email;
use SendGrid\Exception as SEException;

/**
 * Class Emailer.
 */
class Emailer
{
    /**
     * @var \SendGrid\
     */
    private $sendGrid;

    /**
     * @var string
     */
    private $to;

    /**
     * @var string
     */
    private $from = '';

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $htmlBody;

    /**
     * Emailer constructor.
     * @param string $host
     * @param string $username
     * @param string $password
     */
    public function __construct($host = '', $username = '', $password = '')
    {
        $this->sendGrid = new \SendGrid(SENDGRID_API_KEY, ['turn_off_ssl_verification' => true]);
        $this->text = 'Please use an HTML supporting browser';
    }

    /**
     * @param array $messageData
     */
    public function setMessageContent($messageData)
    {
        $this->subject = $messageData['subject'];
        $this->htmlBody = $messageData['body'];
    }

    /**
     * @param string $address
     */
    public function setToAddress($address)
    {
        $this->to = $address;
    }

    /**
     * @return bool
     */
    public function sendEmail()
    {
        $email = new Email();
        $email->addTo($this->to)
                ->setFrom($this->from)
                ->setSubject($this->subject)
                ->setText($this->text)
                ->setHtml($this->htmlBody)
                ->setTemplateId(SEND_GRID_TEMPLATE_ID_1)
                ->addHeader('X-Sent-Using', 'SendGrid-API')
                ->addHeader('X-Transport', 'web');
        try {
            $this->sendGrid->send($email);

            return true;
        } catch (SEException $e) {
            return false;
        }
    }
}
