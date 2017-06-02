<?php

namespace frontend\models;

use PHPMailer;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 * @property string $name
 * @property string $email
 * @property string $body
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $body;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'body'], 'required'],
            ['name', 'string', 'max' => 255],
            ['body', 'string', 'max' => 65535],
            // email has to be a valid email address
            ['email', 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'E-Mail'),
            'body' => Yii::t('app', 'Message'),
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @param string $bodyText
     * @param string $bodyHtml
     * @return boolean whether the email was sent
     */
    public function sendEmail($email, $bodyText = '', $bodyHtml = '')
    {
        /** @var PHPMailer $mail */
        $mail = Yii::$app->mailer;
        $mail->Subject = 'Сообщение';
        $mail->AltBody = $bodyText;
        
        $mail->setFrom(Yii::$app->params['robotEmail'], 'BeHoney.by');
        $mail->clearAddresses();
        $mail->addAddress($email);
        $mail->MsgHTML($bodyHtml);
        
        return $mail->send();
    }
}
