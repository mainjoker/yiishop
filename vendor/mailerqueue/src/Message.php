<?php
namespace mailerqueue;

use Yii;

class Message extends \yii\swiftmailer\Message
{
    public function queue()
    {
        $redis = Yii::$app->redis;
        if (empty($redis)) {
            throw new \yii\base\InvalidConfigException('redis not found in config.');
        }
        // 0 - 15  select 0 select 1
        // db => 1
        $mailer = Yii::$app->mailer;
        if (empty($mailer) || !$redis->select($mailer->db)) {
            throw new \yii\base\InvalidConfigException('db not defined.');
        }
        $message = [];
        $message['from'] = array_keys($this->from);
        $message['to'] = array_keys($this->getTo());

        //$message['cc'] = array_keys($this->getCc());
       // $message['reply_to'] = array_keys($this->getReplyTo());
        //$message['charset'] = array_keys($this->getCharset());
       // $message['subject'] = array_keys($this->getSubject());

        if (!empty($this->getCc())) {
            $message['cc'] = array_keys($this->getCc());
        }
        if (!empty($this->getBcc())) {
            $message['bcc'] = array_keys($this->getBcc());
        }

        if (!empty($this->getReplyTo())) {
           $message['reply_to'] = array_keys($this->getReplyTo());
        }

        if (!empty($this->getCharset()) ){
           $message['charset'] = array_keys($this->getCharset());
        } 

        if (!empty($this->getSubject())) {
           $message['subject'] = $this->getSubject();
        }


     /*   if (!empty($message['cc'])) {
                $messageObj->setCc($message['cc']);
        }
        if (!empty($message['bcc'])) {
            $messageObj->setBcc($message['bcc']);
        }
        if (!empty($message['reply_to'])) {
            $messageObj->setReplyTo($message['reply_to']);
        }
        if (!empty($message['charset'])) {
            $messageObj->setCharset($message['charset']);
        }
        if (!empty($message['subject'])) {
            $messageObj->setSubject($message['subject']);
        }
        if (!empty($message['html_body'])) {
            $messageObj->setHtmlBody($message['html_body']);
        }
        if (!empty($message['text_body'])) {
            $messageObj->setTextBody($message['text_body']);
        }
*/

        $parts = $this->getSwiftMessage()->getChildren();

       /* echo '<pre>';
        print_r($message);exit;
        echo '</pre>';*/
        if (!is_array($parts) || !sizeof($parts)) {
            $parts = [$this->getSwiftMessage()];
        }
        foreach ($parts as $part) {
            if (!$part instanceof \Swift_Mime_Attachment) {
                switch($part->getContentType()) {
                    case 'text/html':
                        $message['html_body'] = $part->getBody();
                        break;
                    case 'text/plain':
                        $message['text_body'] = $part->getBody();
                        break;
                }
                if (empty($message['charset'])) {
                    $message['charset'] = $part->getCharset();
                }
            }
        }
        return $redis->rpush($mailer->key, json_encode($message));
    }
}