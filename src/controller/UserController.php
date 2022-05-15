<?php

namespace src\controller;

use src\entity\Cart;
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
        if (isset($_SESSION['user_id'])) {
            unset($_SESSION['user_id']);
            unset($_SESSION['user_name']);
            unset($_SESSION['user_last_name']);
            unset($_SESSION['user_status']);
        }
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
            $user->setFirstName($firstname);
            $user->setLastName($lastname);
            $user->setPassword(md5($password));
            $user->setEmail($email);

            /** @var UserRepository $emailCheckQuery */
            $emailCheckQuery = $em->getRepository(User::class)
                ->findOneBy(['email' => $user->getEmail()]);

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
                    $cart = new Cart();
                    $cart->setUser($user);
                    $em->persist($cart);
                    $em->flush();
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
        $em = $this->getEntityManager();
        $email = $_POST['email'];
        $password = $_POST['password'];
        if (isset($email, $password) && !empty($email) && !empty($password)) {
            $password = md5($password);
            /** @var UserRepository $userRepository */
            $userRepository = $em->getRepository(User::class);
            /** @var User $user */
            $user = $userRepository->findOneBy(['password' => $password, 'email' => $email]);

            if ($user) {
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['user_name'] = $user->getFirstName();
                $_SESSION['user_last_name'] = $user->getLastName();
                $_SESSION['user_status'] = $user->getIsActive();
                $cartController = new CartController();
                $cart = $cartController->findCartByUserId($_SESSION['user_id']);
                if (!isset($cart)) {
                    $cart = new Cart();
                    $cart->setUser($user);
                    $em->persist($cart);
                    $em->flush();
                }
                if (isset($_SESSION['login_error'])) {
                    unset($_SESSION['login_error']);
                }
                $cartController = new CartController();
                $cartController->cartSessionToCartTable();
                header('location: /');
            } else {
                $_SESSION['login_error'] = 'Password or email is incorrect';
                header('location: /login');
            }
        } else {
            $_SESSION['login_error'] = 'Password or email is incorrect';
            header('location: /login');
        }

    }

    public function profile($pageModulePath)
    {

        if (!isset($_SESSION['user_id'])) {
            header('location: /');
        } else {
            $pageModule = $pageModulePath;
            $templateFilePath = str_replace('profile', 'profileTemplate', $pageModulePath);
            $title = 'Profile';
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

        $user->setPassword('');

        return $user;

    }

    public function update()
    {
        $id = $_POST['userId'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $password = $_POST['password'];

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
            } else {
                $_SESSION['user_update_profile_error'] = 'Wrong password';
            }
            header('location: /profile');
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
                } else {
                    $_SESSION['user_update_password_error'] = 'Passwords doesnt match';
                }

            } else {
                $_SESSION['user_update_password_error'] = 'Wrong password';
            }
            header('location: /profile');
        }

    }
}