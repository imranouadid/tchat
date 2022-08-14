<?php

class Pages extends Controller{

    public function __construct(){
    }

    public function index(){

        if(isLoggedIn()){
            redirect('dashboards/index');
        }

        $data = [
            'title'=>'Welcome',
            'description'=>'Free simple chat application !'
        ];

        $this->view('pages/index',$data);
    }

}





?>