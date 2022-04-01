<?php

namespace admin\controller;

use src\entity\Category;
use src\repository\CategoryRepository;

$str = "";
$categoryArray = [];

class CategoryController extends AdminAbstractController
{

    public function show($pageModulePath)
    {
        $pageModule = $pageModulePath;
        $templateFilePath = str_replace('category', 'homepage', $pageModulePath);
        $title = "Category";
        require_once($templateFilePath);
        if (isset($_SESSION['category_add_error'])) {
            unset($_SESSION['category_add_error']);
        }
    }

    public function showUpdate($pageModulePath, $id)
    {
        $title = "Update Category";
        $em = $this->getEntityManager();
        /** @var Category $category */
        $category = $em->find(Category::class, $id[1]);


        if ($category) {
            $pageModule = $pageModulePath;
            $templateFilePath = str_replace('updateCategory', 'homepage', $pageModulePath);
            require_once($templateFilePath);
        } else {
            $templateFilePath = str_replace('updateCategory', '404', $pageModulePath);
            require_once($templateFilePath);
        }
    }

    public function add()
    {
        $categoryName = $_POST['addCategoryName'];
        $parentId = $_POST['addCategoryParentId'];

        /** @var CategoryRepository $categoryExitsQuery */
        $categoryExitsQuery = $this->getEntityManager()->getRepository(Category::class)->
        findOneBy(array('name' => $categoryName));
        if (!$categoryExitsQuery) {
            $category = new Category();
            $category->setName($categoryName);
            $category->setParentId($parentId);
            $this->getEntityManager()->persist($category);
            $this->getEntityManager()->flush();

            if ($category->getId()) {
                header('Location:/admin/category');
            } else {
                $_SESSION['category_add_error'] = 'There was a problem adding the category.';
                header('location: /admin/category');
            }
        } else {
            $_SESSION['category_add_error'] = 'This category already exists';
            header('location: /admin/category');
        }

    }

    public function delete($id)
    {
        $em = $this->getEntityManager();
        $category = $em->find(Category::class, $id);
        $em->remove($category);

        /** @var Category $childCatArray */
        $childCatArray = $em->getRepository(Category::class)->findBy(array('parent_id' => $id));
        /** @var Category $childCat */
        foreach ($childCatArray as $childCat) {
            $this->delete($childCat->getId());
        }
        $em->flush();

        header('location: /admin/category');
    }

    public function update()
    {
        $categoryId = $_POST['categoryId'];
        $parentId = $_POST['parentId'];
        $categoryName = $_POST['categoryName'];

        $em = $this->getEntityManager();
        $category = $em->find(Category::class, $categoryId);
        $category->setName($categoryName);
        $category->setParentId($parentId);
        $em->persist($category);
        $em->flush();

        header('location: /admin/category');

    }

    private function getSubCategories($parentId): null|array
    {
        global $categoryArray;
        $em = $this->getEntityManager();
        $category = $em->getRepository(Category::class)->findBy(array('parent_id' => $parentId));
        foreach ($category as $cat) {
            $categoryArray[] = $cat;
            $this->getSubCategories($cat->getId());
        }
        return $categoryArray;
    }

    function categoryComponentRowGeneratorForUpdate($categoryId, $parentId)
    {
        /** @var CategoryRepository $query */
        $query = $this->getEntityManager()->getRepository(Category::class);
        /** @var Category $result */
        $result = $query->findAll();
        $resultChild = $this->getSubCategories($categoryId);

        if ($resultChild!=null) {
            foreach ($resultChild as $child) {
                foreach ($result as $key => $value) {
                    if ($child->getId() == $value->getId()) {
                        unset($result[$key]);
                    }
                }
            }
        }

        if ($result) {
            $str = "";
            /** @var Category $row */
            foreach ($result as $row) {
                if ($categoryId != $row->getId()) {
                    if ($parentId == $row->getId()) {
                        $str .= $this->categoryComponentParent($row->getId(), $row->getName());
                    } else {
                        $str .= $this->categoryComponent($row->getId(), $row->getName());
                    }
                }
            }
            echo $str;
        }

    }


    function categoryComponentParent($id, $categoryName): string
    {
        return "<option selected name='category_id' value=\"$id\">$categoryName</option>";
    }

    function categoryComponent($id, $categoryName, $categoryIdOfProduct = null): string
    {
        if ($categoryIdOfProduct != null) {
            return "<option selected value=\"$id\">$categoryName</option>";
        } else {
            return "<option value=\"$id\">$categoryName</option>";
        }
    }

    public function categoryComponentRowGenerator($categoryIdOfProduct = null)
    {
        /** @var CategoryRepository $query */
        $query = $this->getEntityManager()->getRepository(Category::class);
        /** @var Category $result */
        $result = $query->findAll();


        if ($result) {
            $str = "";
            /** @var Category $row */
            foreach ($result as $row) {
                if ($categoryIdOfProduct != null && $row->getId() == $categoryIdOfProduct) {
                    $str .= self::categoryComponent($row->getId(), $row->getName(), $categoryIdOfProduct);
                    $categoryIdOfProduct = null;
                } else {
                    $str .= self::categoryComponent($row->getId(), $row->getName());
                }
            }
            echo $str;
        }
    }

    public function categoryTree($parentId = 0): string
    {
        global $str;
        $em = $this->getEntityManager();

        /** @var Category $childCatArray */
        $childCatArray = $em->getRepository(Category::class)->findBy(array('parent_id' => $parentId));
        if (!$childCatArray && $parentId == 0) {
            return 'There is no existing a category ';
        } else {
            /** @var Category $childCat */
            foreach ($childCatArray as $childCat) {
                $str .= "<li>" . $childCat->getName() . "<ul> ";
                $str .= $this->categoryTree($childCat->getId());
                $str .= "</ul> </li>";
            }
        }
        if ($parentId == 0) {
            return $str;
        } else {
            return "";
        }
    }


}