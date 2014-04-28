<?php

namespace MailModule\Service;

use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;

/**
 * Class MailService
 *
 * @author Lorenzo Fontana <fontanalorenzo@me.com>
 */
class MailService
{

    /**
     * @var \Zend\Mail\Message
     */
    private $message;

    /**
     * @var \Zend\Mail\Transport\TransportInterface
     */
    private $transport;

    /**
     * @var array Attachments
     */
    private $attachments = [];

    /**
     * Ctor
     *
     * @param Message $message
     * @param TransportInterface $transport
     */
    public function __construct(Message $message, TransportInterface $transport)
    {
        $this->message = $message;
        $this->transport = $transport;
    }

    /**
     * Send
     *
     * @return mixed
     */
    public function send()
    {
        return $this->transport->send($this->message);
    }

    /**
     * Get Message
     *
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Add Attachment
     *
     * @param string $attachment Attachment Path
     * @return $this
     */
    public function addAttachment($attachment)
    {
        $this->attachments[] = $attachment;
        return $this;
    }

    /**
     * Set Attachments
     *
     * @param array $attachments Array of attachment's paths
     * @return $this
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }

    /**
     * Set body
     *
     * @param string|\Zend\Mime\Message $body
     * @return $this
     */
    public function setBody($body)
    {
        switch (true) {
            case is_string($body) && preg_match("/<[^<]+>/", $body, $m) != 0:
                $bodyPart = new \Zend\Mime\Message();
                $bodyMessage = new \Zend\Mime\Part($body);
                $bodyMessage->type = 'text/html';
                $bodyPart->setParts(array($bodyMessage));
                $this->message->setBody($bodyPart);
                $this->message->setEncoding('UTF-8');
                break;
            default:
                $this->message->setBody($body);
                break;
        }

        return $this;
    }
}
