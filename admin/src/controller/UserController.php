<?php

namespace admin\controller;

use src\entity\User;
use src\repository\UserRepository;

class UserController extends AdminAbstractController
{
    public function show($pageModulePath, $parameters)
    {
        $title = 'User';
        $pageModule = $pageModulePath;
        $templateFilePath = str_replace('user', 'adminPanelTemplate', $pageModulePath);

        if (isset($parameters['pg'])) {
            (int)$parameters['pg'] == 0 ? $pageNumber = 1 : $pageNumber = (int)$parameters['pg'];
        } else {
            $parameters['pg'] = 1;
        }
        $limit = 10;
        $users = $this->getUsersByLimit($parameters['pg'], $limit);
        if ($users != null) {
            $totalUser = count($users);
        }
        require_once($templateFilePath);
    }

    /**
     * @param $pageNumber
     * @param $limit
     * @return User[]|null
     */
    public function getUsersByLimit($pageNumber, $limit): ?array
    {
        $em = $this->getEntityManager();
        
        /* @var UserRepository $userRepository */
        $userRepository = $em->getRepository(User::class);

        $users = $userRepository->findUsersWithLimit($pageNumber, $limit);
        if ($users) {
            return $users;
        } else {
            return null;
        }

    }

    public function showUserSearch(string $pageModulePath, $parameters)
    {
        $title = 'User';
        $pageModule = $pageModulePath;
        $templateFilePath = str_replace('user', 'adminPanelTemplate', $pageModulePath);

        if (isset($parameters['searchTerm'])) {
            $parameters['searchTerm'] = str_replace('%20', ' ', $parameters['searchTerm']);
        }
        if (isset($parameters['pg'])) {
            (int)$parameters['pg'] == 0 ? $pageNumber = 1 : $pageNumber = (int)$parameters['pg'];
        } else {
            $parameters['pg'] = 1;
        }
        $searchTermParameters = $parameters;

        $users = $this->getUsersBySearchTermAndLimit($parameters['searchTerm'], $parameters['pg'], 8);
        if ($users != null) {
            $totalUser = count($users);
        }

        require_once($templateFilePath);
    }

    /**
     * @param $searchTerm
     * @param $pageNumber
     * @param $limit
     * @return User[]|null
     */
    public function getUsersBySearchTermAndLimit($searchTerm, $pageNumber, $limit): ?array
    {
        $userRow = '';
        $em = $this->getEntityManager();

        /** @var UserRepository $userRepository */
        $userRepository = $em->getRepository(User::class);
        $users = $userRepository->findUsersBySearchTerm($searchTerm, $pageNumber, $limit);

        if ($users) {
            return $users;
        } else {
            return null;
        }
    }
    
    public function showUpdate($pageModulePath, $id)
    {
        $title = 'Update User';
        $pageModule = $pageModulePath;
        $em = $this->getEntityManager();
        $user = $em->find(User::class, $id[1]);

        if ($user) {
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
                $_SESSION['user_update_error'] = 'Email already exists';
                header('Location: /admin/user/update/' . $_POST['userId']);
                exit;
            }
            $em->flush();
            header('Location: /admin/user/update/' . $_POST['userId']);

        } else {
            $_SESSION['user_update_error'] = 'User not found';
            header('Location: /admin/user');
        }
    }

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

    public function userRow($id, $firstName, $lastName, $email, $createdAt, $status): string
    {
        if ($status) {
            $status = "<a href='/admin/check-change-user-status/$id' class='btn btn-success btn-sm'>Activated</a>";
        } else {
            $status = "<a href='/admin/check-change-user-status/$id' class='btn btn-warning btn-sm'>Passived</a>";
        }
        return "<tr>
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
    }
}