<?php

namespace admin\controller;

use src\entity\Brand;

class BrandController extends AdminAbstractController
{

    public function show($pageModulePath)
    {
        $pageModule = $pageModulePath;
        $templateFilePath = str_replace('brand', 'adminPanelTemplate', $pageModulePath);
        $title = 'Brand';
        require_once($templateFilePath);
    }

    public function showAdd($pageModulePath)
    {
        $pageModule = $pageModulePath;
        $templateFilePath = str_replace('addBrand', 'adminPanelTemplate', $pageModulePath);
        $title = 'Add Brand';
        require_once($templateFilePath);
        if (isset($_SESSION['brand_add_error'])) {
            unset($_SESSION['brand_add_error']);
        }
    }

    public function showUpdate($pageModulePath, $id)
    {
        $title = 'Update Brand';
        $em = $this->getEntityManager();
        $brand = $em->find(Brand::class, $id[1]);

        if ($brand) {
            $pageModule = $pageModulePath;
            $templateFilePath = str_replace('updateBrand', 'adminPanelTemplate', $pageModulePath);
        } else {
            $templateFilePath = str_replace('updateBrand', '404', $pageModulePath);
        }
        require_once($templateFilePath);
        if (isset($_SESSION['brand_update_error'])) {
            unset($_SESSION['brand_update_error']);
        }
    }

    public function add()
    {
        $brandName = $_POST['brandName'];

        $brand = new Brand();
        $brand->setName($brandName);

        $em = $this->getEntityManager();
        /** @var Brand $brandExitsQuery */
        $brandExitsQuery = $em->getRepository(Brand::class)->
        findOneBy(['name' => $brandName]);

        if (!$brandExitsQuery) {
            $em->persist($brand);
            $em->flush();
            header('location: /admin/brand');
        } else {
            $_SESSION['brand_add_error'] = $brandExitsQuery->getName() . ' brand is already exists';
            header('location: /admin/brand/add');
        }
    }

    public function getAll(): ?array
    {
        $em = $this->getEntityManager();
        /** @var Brand[] $result */
        $result = $em->getRepository(Brand::class)->findAll();
        if ($result) {
            return $result;
        } else {
            return null;
        }

    }

    public function update()
    {
        $brandId = $_POST['brandId'];
        $brandName = $_POST['brandName'];

        $em = $this->getEntityManager();
        /** @var Brand $brandExitsQuery */
        $brandExitsQuery = $em->getRepository(Brand::class)->findOneBy(['name' => $brandName]);

        if (!$brandExitsQuery) {
            $brand = $em->find(Brand::class, $brandId);
            $brand->setName($brandName);
            $em->persist($brand);
            $em->flush();
            header('location: /admin/brand');
        } else {
            $_SESSION['brand_update_error'] = $brandExitsQuery->getName() . ' brand is already exists';
            header('location: /admin/brand/update/' . $brandId);
        }
    }

    public function delete($id)
    {
        $em = $this->getEntityManager();
        /** @var Brand $brand */
        $brand = $em->find(Brand::class, $id);
        if ($brand) {
            $em->remove($brand);
            $em->flush();
            header('location: /admin/brand');
        } else {
            $page404 = '../admin/view/404.php';
            require_once($page404);
        }


    }

    public function brandTableRowGenerator()
    {

        $result = $this->getAll();
        if (!$result) {
            echo "<h3>No brands to list !</h3>";
        } else {
            $str = "";
            foreach ($result as $row) {
                $str .= self::brandTableRow($row->getId(), $row->getName());

            }
            echo $str;
        }
    }

    public function brandTableRow($id, $brandName): string
    {
        return "
        <tr>
            <td>$id</td>
            <td>$brandName</td>
            <td><a class=\"btn btn-info btn-sm\" href=\"brand/update/$id\" role=\"button\">Update</a></td>
            <td><a class=\"btn btn-danger btn-sm\" href=\"check-delete-brand/$id\" role=\"button\" onclick=\"return confirm('Are you sure delete $brandName ?');\">Delete</a></td>
        </tr>
        ";
    }

    public function brandOptionRowGenerator(int $brandIdOfProduct = null)
    {
        $result = self::getAll();
        if (!$result) {
            echo "<h3>No brands to list !</h3>";
        } else {
            $str = "";
            foreach ($result as $row) {
                if ($brandIdOfProduct != null && $row->getId() == $brandIdOfProduct) {
                    $str .= self::brandOptionRow($row->getId(), $row->getName(), $brandIdOfProduct);
                    $brandIdOfProduct = null;
                } else {
                    $str .= self::brandOptionRow($row->getId(), $row->getName());
                }
            }
            echo $str;
        }
    }

    public function brandOptionRow($id, $brandName, $brandIdOfProduct = null): string
    {
        if ($brandIdOfProduct != null && $id == $brandIdOfProduct) {
            return "<option selected value=\"$id\">$brandName</option>";
        } else {
            return "<option value=\"$id\">$brandName</option>";
        }
    }

}