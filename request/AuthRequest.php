<?php
namespace Request;
use System\Core\FormRequest;

class AuthRequest extends FormRequest
{
    public function __construct() {
        parent::__construct();
    }

    public function auth() {
        return true;
    }

    public function rules()
    {
        return [];
    }
}