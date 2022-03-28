<?php

namespace controller;

use src\entity\User;
use src\repository\UserRepository;

class UserController extends AbstractController
{

    public function register($templateFile)
    {
        if (isset($_SESSION['user_id'])) {
            header('location: /');
        }

        require_once $templateFile;

        if (isset($_SESSION['register_error'])) {
            unset($_SESSION['register_error']);
        }

    }

    public function login($templateFile)
    {

        if (isset($_SESSION['user_id'])) {
            header('location: /');
        }

        require_once $templateFile;

        if (isset($_SESSION['login_error'])) {
            unset($_SESSION['login_error']);
        }

    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('location:/');
    }

    public function registerCheck()
    {
        $firstname = $_POST['firstName'];
        $lastname = $_POST['lastName'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['$confirmPassword'];
        $email = $_POST['email'];

        if (strcmp($password, $confirmPassword) == 0) {
            $em = $this->getEntityManager();
            $user = new User();
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setPassword(md5($password));
            $user->setEmail($email);

            /** @var src\repository\UserRepository $emailCheckQuery */
            $emailCheckQuery = $em->getRepository(User::class)
                ->findOneBy(array('email' => $user->getEmail()));

            if ($emailCheckQuery) {
                $_SESSION['register_error'] = 'This email already exists';
                header('location: /register');
            } else {
                $em->persist($user);
                $em->flush();
                if ($user->getId() > 0) {
                    if (isset($_SESSION['register_error'])) {
                        unset($_SESSION['register_error']);
                    }
                    header('location: /');
                } else {
                    $_SESSION['register_error'] = 'Something went wrong';
                    header('location: /register');
                }
            }
        } else {
            $_SESSION['register_error'] = 'Passwords doesnt match';
            header('location: /register');
        }

    }

    public function loginCheck()
    {

        $email = $_POST['email'];
        $password = $_POST['password'];
        if ($email && $password) {
            $password = md5($password);
            /** @var UserRepository $query */
            $query = $this->getEntityManager()->getRepository(User::class);
            /** @var User $result */
            $result = $query->findOneBy(array('password' => $password, 'email' => $email));

            if ($result) {
                $_SESSION['user_id'] = $result->getId();
                $_SESSION['user_name'] = $result->getFirstName();
                $_SESSION['user_last_name'] = $result->getLastName();
                if (isset($_SESSION['login_error'])) {
                    unset($_SESSION['login_error']);
                }
                header('location: /');
            } else {
                $_SESSION['login_error'] = 'Password or email is incorrect';
                header('location: /login');
            }
        }

    }


    public function profile($pageModulePath)
    {

        if (!isset($_SESSION['user_id'])) {
            header('location: /');
        } else {
            $pageModule = $pageModulePath;
            $templateFilePath = str_replace('profile', 'profileTemplate', $pageModulePath);
            $title = "Profile";
            require_once($templateFilePath);
        }
        if (isset($_SESSION['user_update_profile_error'])) {
            unset($_SESSION['user_update_profile_error']);
        }
        if (isset($_SESSION['user_update_password_error'])) {
            unset($_SESSION['user_update_password_error']);
        }

    }

    public function getById($id): User
    {
        $em = $this->getEntityManager();

        $user = $em->find(User::class, $id);

        $user->setPassword("");

        return $user;

    }

    public function update()
    {
        $id = $_POST['userId'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        //sonradan validatorler gelecek

        $em = $this->getEntityManager();
        $user = $em->find(User::class, $id);

        if (isset($user, $firstName, $lastName, $email, $password)) {
            if (strcmp($user->getPassword(), md5($password)) == 0) {
                $user->setFirstName($firstName);
                $user->setLastName($lastName);
                $user->setEmail($email);
                $em->persist($user);
                $em->flush();
                $_SESSION['user_name'] = $user->getFirstName();
                $_SESSION['user_last_name'] = $user->getLastName();
                header('location: /profile');
            } else {
                $_SESSION['user_update_profile_error'] = 'Wrong password';
                header('location: /profile');
            }
        }
    }


    public function updatePassword()
    {
        $id = $_POST['userId'];
        $currentPassword = $_POST['currentPassword'];
        $newPassword = $_POST['newPassword'];
        $confirmNewPassword = $_POST['confirmNewPassword'];

        $em = $this->getEntityManager();
        $user = $em->find(User::class, $id);

        if (isset($user, $currentPassword, $newPassword, $confirmNewPassword)) {
            if (strcmp($user->getPassword(), md5($currentPassword)) == 0) {
                if (strcmp($newPassword, $confirmNewPassword) == 0) {
                    $user->setPassword(md5($newPassword));
                    $em->persist($user);
                    $em->flush();
                    header('location: /profile');
                } else {
                    $_SESSION['user_update_password_error'] = 'Passwords doesnt match';
                    header('location: /profile');
                }

            } else {
                $_SESSION['user_update_password_error'] = 'Wrong password';
                header('location: /profile');
            }
        }

    }

}