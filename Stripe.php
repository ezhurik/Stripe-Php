<?php

if (!defined('BASEPATH'))

    exit('No direct script access allowed');

class Stripe extends CI_Controller {

    function Stripe() {
        error_reporting(E_ALL);
        parent::__construct();
    }

    function checkout()
    {
        $token=$this->input->post('token');
        $totalAmount = $this->input->post('amount');
        try{
            
            require_once('vendor/autoload.php');
            \Stripe\Stripe::setApiKey('sk_*************'); // your secret key
            $charge = \Stripe\Charge::create(array(
                'currency' => 'usd',
                'source' => $token,
                'amount' => $totalAmount * 100            
            ));

            //inserting the data to your database 
            $this->load->model('Mdl_home');
            $res= $this->Mdl_home->registerUser("dealer");
            if($res == "sent")
            {
                $code=0;
                $msg="Thanks, Your payment is recieved. PLease check your email to verify your account.";  
            }
            else
            {
                $code=1;
                $msg="Email could not be sent due to some server issues.";    
            }
            $response['code'] = $code;
            $response['msg'] = $msg;
            echo json_encode($response);
        }
        catch (Exception $e){
            $error=$e->getMessage();
            echo $error;
        }
    }
}

?>