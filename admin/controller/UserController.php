<?php

namespace admin\controller;

use src\entity\User;

class UserController extends AdminAbstractController
{
    public function show($pageModulePath)
    {
        $pageModule = $pageModulePath;
        $templateFilePath = str_replace('user', 'adminPanelTemplate', $pageModulePath);
        $title = "User";
        require_once($templateFilePath);
    }

    public function showUpdate($pageModulePath, $id)
    {
        $title = "Update User";
        $em = $this->getEntityManager();
        $user = $em->find(User::class, $id[1]);

        if ($user) {
            $pageModule = $pageModulePath;
            $templateFilePath = str_replace('updateUser', 'adminPanelTemplate', $pageModulePath);
        } else {
            $templateFilePath = str_replace('updateUser', '404', $pageModulePath);
        }
        require_once($templateFilePath);
        if (isset($_SESSION['user_update_error'])) {
            unset($_SESSION['user_update_error']);
        }
    }

    public function update()
    {
        $em = $this->getEntityManager();
        $userRepository = $em->getRepository(User::class);
        $user = $em->find(User::class, $_POST['userId']);
        if ($user) {
            $user->setEmail($_POST['email']);
            $user->setFirstName($_POST['firstName']);
            $user->setLastName($_POST['lastName']);

            $emailCheck = $userRepository->findOneBy(['email' => $_POST['email']]);
            if ($emailCheck && $emailCheck->getId() != $_POST['userId']) {
                $_SESSION['user_update_error'] = "Email already exists";
                header('Location: /admin/user/update/' . $_POST['userId']);
                exit;
            }
            $em->flush();
            header('Location: /admin/user/update/' . $_POST['userId']);

        } else {
            $_SESSION['user_update_error'] = "User not found";
            header('Location: /admin/user');
        }
    }


    //change user status
    public function changeStatus($userId)
    {
        $em = $this->getEntityManager();
        $user = $em->find(User::class, $userId);
        if (isset($user)) {
            $user->setIsActive($user->getIsActive() == 1 ? 0 : 1);
            $em->persist($user);
            $em->flush();
        } else {
            header('Location: /admin/user');
        }
        header('Location: /admin/user');
    }

    public function delete($userId)
    {
        $em = $this->getEntityManager();
        $user = $em->find(User::class, $userId);
        if (isset($user)) {
            $em->remove($user);
            $em->flush();
        } else {
            header('Location: /admin/user');
        }
        header('Location: /admin/user');
    }

    public function userRowGenerator(): void
    {
        $userRow = '';
        $em = $this->getEntityManager();
        /* @var $userList User[] */
        $userList = $em->getRepository(User::class)->findAll();
        foreach ($userList as $user) {
            $userRow .= $this->userRow($user->getId(), $user->getFirstName(), $user->getLastName(), $user->getEmail(), $user->getCreatedAt()->format('d/m/Y H:i:s'), $user->getIsActive());
        }
        echo $userRow;
    }

    public function userRow($id, $firstName, $lastName, $email, $createdAt, $status): string
    {
        if ($status) {
            $status = "<a href='/admin/check-change-user-status/$id' class='btn btn-success btn-sm'>Activated</a>";
        } else {
            $status = "<a href='/admin/check-change-user-status/$id' class='btn btn-warning btn-sm'>Passived</a>";
        }
        $userRow = "<tr>
                        <td>$id</td>
                        <td>$firstName</td>
                        <td>$lastName</td>
                        <td>$email</td>
                        <td>$createdAt</td>
                        <td>$status</td>
                        <td>
                       <a href='/admin/user/update/$id' class='btn btn-info btn-sm'>Update</a>
                        <a href='/admin/check-delete-user/$id' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure?');\">Delete</a></td>
                    </tr>";
        return $userRow;
    }

}