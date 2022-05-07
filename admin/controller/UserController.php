<?php

namespace admin\controller;

use src\entity\User;
use src\repository\UserRepository;

class UserController extends AdminAbstractController
{
    public function show($pageModulePath)
    {
        $pageModule = $pageModulePath;
        $templateFilePath = str_replace('user', 'adminPanelTemplate', $pageModulePath);
        $title = "User";
        require_once($templateFilePath);
    }

    public function showUserSearch(string $pageModulePath, $parameters)
    {
        if (isset($parameters['searchTerm'])) {
            $parameters['searchTerm'] = str_replace('%20', ' ', $parameters['searchTerm']);
        }
        if (isset($parameters['pg'])) {
            (int)$parameters['pg'] == 0 ? $pageNumber = 1 : $pageNumber = (int)$parameters['pg'];
        } else {
            $parameters['pg'] = 1;
        }
        $searchTermParameters = $parameters;

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

    public function userTableRowGenerator($pageNumber): void
    {
        $userRow = '';
        $em = $this->getEntityManager();
        /* @var $userList User[] */
        $userList = $em->getRepository(User::class)->findAll();
        $em = $this->getEntityManager();
        /* @var UserRepository $userRepository */
        $userRepository = $em->getRepository(User::class);
        $users = $userRepository->findUsersWithLimit($pageNumber, 8);
        $countUsers = $userRepository->countUsers();
        if (count($users) > 0) {
            foreach ($userList as $user) {
                $userRow .= $this->userRow($user->getId(), $user->getFirstName(), $user->getLastName(), $user->getEmail(), $user->getCreatedAt()->format('d/m/Y H:i:s'), $user->getIsActive());
            }
            echo $userRow;
            $this->paginator($pageNumber, $countUsers, 8);
        } else {
            echo '<div class="row text-center">';
            echo '<h3>No users found</h3>';
            echo '<a href="/">Back to Home</a>';
            echo '</div>';
        }
        
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

    public function userTableRowGeneratorWithSearchTerm($searchTerm, $pageNumber)
    {
        $userRow = '';
        $em = $this->getEntityManager();

        /** @var UserRepository $userRepository */
        $userRepository = $em->getRepository(User::class);
        $users = $userRepository->findUsersBySearchTerm($searchTerm, $pageNumber, 8);
        
        if (count($users) > 0) {
            foreach ($users as $user) {
                $userRow .= $this->userRow($user->getId(), $user->getFirstName(), $user->getLastName(), $user->getEmail(), $user->getCreatedAt()->format('d/m/Y H:i:s'), $user->getIsActive());
            }
            echo $userRow;            
            $this->paginator($pageNumber, count($users), 8);
        } else {
            echo "<h1>No users found</h1>";
        }
    }

    public function paginator($currentPageNumber, $countUsers, $limit): void
    {
        $url = $_SERVER['REQUEST_URI'];
        if (str_contains($url, '?')) {
            if (preg_match('/\?pg=\d+/', $url)) {
                $url = preg_replace('/\?pg=\d+/', '', $url);
                $url = $url . '?pg=';
            } elseif (preg_match('/&pg=\d+/', $url)) {
                $url = preg_replace('/&pg=\d+/', '', $url);
                $url = $url . '&pg=';
            } else {
                $url = $url . '&pg=';
            }

        } else {
            $url = $url . '?pg=';
        }

        $record = 2;
        $pageCount = ceil($countUsers / $limit);
        $str = '<div class="mt-3"> <nav aria-label="Page navigation example">
                 <ul class="pagination justify-content-end">';
        if ($currentPageNumber > 1) {
            $newPage = $currentPageNumber - 1;
            $str .= '<li class="page-item"><a class="page-link" href="' . $url . $newPage . '"' . '>Geri</a></li>';
        } else {
            $str .= '<li class="page-item disabled"><a class="page-link" href="?pg=">Geri</a></li>';
        }
        for ($i = $currentPageNumber - $record; $i <= $currentPageNumber + $record; $i++) {
            if ($i == $currentPageNumber) {
                $str .= '<li class="page-item active"><a class="page-link" href="' . $url . $i . '"' . '>' . $i . '</a></li>';
            } else {
                if ($i > 0 and $i <= $pageCount) {
                    $str .= '<li class="page-item"><a class="page-link" href="' . $url . $i . '"' . '>' . $i . '</a></li>';
                }
            }
        }
        if ($currentPageNumber < $pageCount) {
            $newPage = $currentPageNumber + 1;
            $str .= '<li class="page-item"><a class="page-link" href="' . $url . $newPage . '"' . '>İleri</a></li>';
        } else {
            $str .= '<li class="page-item disabled"><a class="page-link" href="#">İleri</a></li>';
        }
        $str .= '</ul></nav></div>';
        echo $str;
    }


}