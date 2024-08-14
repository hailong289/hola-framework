<?php
namespace Mails;
use Hola\Core\Mail;
class DefaultMail extends Mail {
    protected $useQueue = false;
    public function __construct()
    {
        parent::__construct();
    }
   
    public function handle()
    {
         echo "send mail";
    }

}