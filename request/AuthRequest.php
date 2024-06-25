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

    /**
     * @return string
     * This function you can return view as you want, this function can be declared or not needed
     * If you want to use this function, you must declare the auth function first
     */
    public function view_auth() {
        return 'error.index';
    }

    /**
     * @return array
     * This function you can return data as you want, this function can be declared or not needed
     * If you want to use this function, you must declare the auth function first
     */
    public function data_auth() {
        return [
            'message' => 'unauthorized',
            'code' => 403
        ];
    }
}