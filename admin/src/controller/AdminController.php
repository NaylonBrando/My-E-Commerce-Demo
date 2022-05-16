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
        if (isset($_SESSION['admin_id'])) {
            unset($_SESSION['admin_id']);
            unset($_SESSION['admin_name']);
            unset($_SESSION['admin_last_name']);
            unset($_SESSION['admin_type']);
        }
        header('location:/admin/login');
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
            $result = $query->findOneBy(['password' => $password, 'email' => $email]);

            if ($result) {
                $_SESSION['admin_id'] = $result->getId();
                $_SESSION['admin_name'] = $result->getFirstName();
                $_SESSION['admin_last_name'] = $result->getLastName();
                $_SESSION['admin_type'] = $result->getAdminType();
                if (isset($_SESSION['admin_login_error'])) {
                    unset($_SESSION['admin_login_error']);
                }
                header('location: /admin/product');
            } else {
                $_SESSION['admin_login_error'] = 'Passwords or email incorrect';
                header('location: /admin/login');
            }
        }

    }
}