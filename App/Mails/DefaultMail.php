<?php
namespace App\Mails;
use Hola\Mailing\MailerBuilder;

class DefaultMail extends MailerBuilder {
    public function __construct() {
        parent::__construct();
    }

    /**
     * @return string
     * If you delete this function, it will default to MAIL_FROM_ADDRESS in constant.php
     * This function can be used or not depending on you
     */
    public function mailFrom() {
        return 'abc@gmail.com';
    }

    /**
     * @return string
     * If you delete this function, it will default to MAIL_FROM_NAME in constant.php
     * This function can be used or not depending on you
     */
    public function mailFromName() {
        return 'abc';
    }

    /**
     * @return string
     * Send to the email you want
     * This function is required
     */
    public function mailTo(){
        return 'mailto@gmail.com';
    }

    /**
     * @return string
     * Send email with title
     * This function can be deleted if not used
     */
    public function title() {
        return "Test mail";
    }

    /**
     * @return string
     * Send email with content as html string
     * This function can be deleted if not used
     */
    public function view() {
        return "<h1>Test mail</h1>";
    }
   
    public function handle()
    {
        return $this->send();
    }

}