<?php

namespace admin\controller;

use src\entity\Admin;
use src\repository\AdminRepository;

class AdminController extends AdminAbstractController
{

    public function login(string $templateFile)
    {
        if (isset($_SESSION['admin_id'])) {
            header('location: /admin');
        }
        require_once($templateFile);
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('location:/');
    }

    public function loginCheck()
    {

        $email = $_POST['email'];
        $password = $_POST['password'];
        if ($email && $password) {
            $password = md5($password);
            /** @var AdminRepository $query */
            $query = $this->getEntityManager()->getRepository(Admin::class);
            /** @var Admin $result */
            $result = $query->findOneBy(array('password' => $password, 'email' => $email));

            if ($result) {
                $_SESSION['admin_id'] = $result->getId();
                $_SESSION['admin_name'] = $result->getFirstName();
                $_SESSION['admin_last_name'] = $result->getLastName();
                $_SESSION['admin_type'] = $result->getAdminType();
                if (isset($_SESSION['admin_login_error'])) {
                    unset($_SESSION['admin_login_error']);
                }
                header('location: /admin');
            } else {
                $_SESSION['admin_login_error'] = 'Passwords or email incorrect';
                header('location: /admin/login');
            }
        }

    }


}