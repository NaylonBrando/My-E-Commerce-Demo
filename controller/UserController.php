<?php
use src\entity\User;
use src\repository\UserRepository;

class UserController extends AbstractController
{

    public function register($templateFile){


        include_once $templateFile;

    }

    public function login($templateFile){

        include_once $templateFile;

    }

    public function logout(){
        session_start();
        session_destroy();
        header('location:/');;
    }

    public function registerCheck()
    {
        $firstname = $_POST['firstName'];
        $lastname = $_POST['lastName'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['$confirmPassword'];
        $email = $_POST['email'];

        if(strcmp($password, $confirmPassword) == 0){
            $em = $this->getEntityManager();
            $user = new User();
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setPassword(md5($password));
            $user->setEmail($email);

            /** @var src\repository\UserRepository $emailCheckQuery */
            $emailCheckQuery = $this->getEntityManager()->getRepository(\src\entity\User::class)
                ->findOneBy(array('email' => $user->getEmail()));

            if($emailCheckQuery){
                $_SESSION['register_error'] = 'Bu email zaten kayıtlı';
                header('location: /register');
            }else{
                $em->persist($user);
                $em->flush();
                if ($user->getId() > 0) {
                    if(isset($_SESSION['register_error'])){unset($_SESSION['register_error']);}
                    header('location: /');
                } else {
                    $_SESSION['register_error'] = 'Bir şeyler ters gitti.';
                    header('location: /register');
                }
            }
        }
        else{
            $_SESSION['register_error'] = 'Sifreler eşlemişyor';
            header('location: /register');
        }

    }

    public function loginCheck(){

        $email = $_POST['email'];
        $password = $_POST['password'];
        if ($email && $password) {
            $password = md5($password);
            /** @var \src\repository\UserRepository $query */
            $query = $this->getEntityManager()->getRepository(\src\entity\User::class);
            /** @var \src\entity\User $result */
            $result = $query->findOneBy(array('password' => $password , 'email' => $email));

            if($result) {
                $_SESSION['user_id'] = $result->getId();
                if(isset($_SESSION['login_error'])){unset($_SESSION['login_error']);}
                header('location: /');
            }
            else {
                header('location: /giris');
                $_SESSION['login_error'] = 'Şifre veya Eposta adresi yanlış';
                header('location: /giris');
            }
        }

    }




    public function profile($page, $parameters){

        include_once $page;

    }

    public function productDetail($templateFile, $id){

    }

}