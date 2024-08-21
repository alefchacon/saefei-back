<?php

namespace App\Mail;

class Mail{
  public $subject;
  public $body;

  public function __construct(
    string $subject = "subject", 
    string $body = "body"
  ){
    $this->subject = $subject;
    $this->body = $body;
  }
}