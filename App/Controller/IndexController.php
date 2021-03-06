<?php

namespace App\Controller;

use System\Model\Login;
use System\Model\Register;
use System\View\Handler;

class IndexController extends Handler
{

    //ToDo Config: Global Config
    public function onRequest()
    {
        $this->initControllerConfig("Habbo - eine Welt voller freude!", "0", "0", "");
        $this->setPermission();

        $this->addView("index/Index");
    }

    public function checkLogin($username, $password)
    {
        if(!empty($username) AND !empty($password)){
            if(filter_var($username, FILTER_VALIDATE_EMAIL)){
                if(strlen($username) <= 30 AND strlen($username) > 3){
                    $checkData = new Login();
                    return $checkData->checkValidData($username, hash("sha256", $password));
                }
            } elseif(strlen($username) <= 14 AND strlen($username) >= 2) {
                $checkData = new Login();
                return $checkData->checkValidData($username, hash("sha256", $password));
            }
        }
    }

    public function checkRegister($username, $mail, $code, $password, $repassword)
    {
        echo "hey";
        if (!empty($username) AND !empty($password) AND !empty($repassword) AND !empty($mail)) {
            if (!preg_match("/^([0-9]+)$/", $username)) {
                if (hash("sha256", $password) == hash("sha256", $repassword)) {
                    if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                        if (strlen($username) <= 14 AND strlen($username) >= 2) {
                            if (strlen($mail) <= 30 AND strlen($mail) >= 3) {
                                $register = new Register();
                                return $register->checkValidData($username, hash("sha256", $password), $mail);
                            } else {
                                echo $this->getError()["register"]["string_mail"];
                            }
                        } else {
                            echo $this->getError()["register"]["string_username"];
                        }
                    } else {
                        echo $this->getError()["register"]["mail_notvalid"];
                    }
                } else {
                    echo $this->getError()["register"]["re_password"];
                }
            } else {
                echo $this->getError()["register"]["username_number"];
            }
        } else {
            echo $this->getError()["general"]["empty"];
        }

        return null;
    }
}