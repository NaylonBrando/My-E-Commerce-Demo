<?php

use src\entity\Brand;

class BrandController extends AdminAbstractController
{

    public function show($pageModulePath)
    {
        $pageModule = $pageModulePath;
        $templateFilePath = str_replace('brand', 'homepage', $pageModulePath);
        $title = "Brand";
        require_once($templateFilePath);
    }

    public function showAdd($pageModulePath)
    {
        $pageModule = $pageModulePath;
        $templateFilePath = str_replace('addBrand', 'homepage', $pageModulePath);
        $title = "Add Brand";
        require_once($templateFilePath);
    }

    public function showUpdate($pageModulePath, $id)
    {
        $title = "Update Brand";
        $em = $this->getEntityManager();
        $brand = $em->find(Brand::class, $id[1]);

        if ($brand) {
            $pageModule = $pageModulePath;
            $templateFilePath = str_replace('updateBrand', 'homepage', $pageModulePath);
            require_once($templateFilePath);
            if (isset($_SESSION['brand_error'])) {
                unset($_SESSION['brand_error']);
            }
        } else {
            $templateFilePath = str_replace('updateBrand', '404', $pageModulePath);
            require_once($templateFilePath);
            if (isset($_SESSION['brandUpdateError'])) {
                unset($_SESSION['brandUpdateError']);
            }
        }
    }

    public function add()
    {
        $compareResult = false;
        $brandName = $_POST['brandName'];

        $newBrand = new Brand();
        $newBrand->setName($brandName);

        $brands = $this->getAll();
        $em = $this->getEntityManager();

        if (!$brands) {

            $em->persist($newBrand);
            $em->flush();
            header('location: /admin/brand');
        } else {
            foreach ($brands as $brand) {
                if (strcmp(strtolower($brand->getName()), strtolower($newBrand->getName())) == 0) {
                    $compareResult = true;
                    break;
                }
            }
            if ($compareResult) {
                $_SESSION['brand_error'] = 'This brand is already exists';
                header('location: /admin/brand/add');
            } else {
                $em->persist($newBrand);
                $em->flush();
                if (isset($_SESSION['brand_error'])) {
                    unset($_SESSION['brand_error']);
                }
                header('location: /admin/brand');
            }
        }
    }

    public function update()
    {
        $brandId = $_POST['brandId'];
        $brandName=$_POST['brandName'];

        $em = $this->getEntityManager();
        $currentBrand = $em->find(Brand::class,$brandId);
        $brands = $this->getAll();
        $compareResult = false;

        foreach ($brands as $brand) {
            if (strcmp(strtolower($brand->getName()), strtolower($brandName)) == 0) {
                $compareResult = true;
                break;
            }
        }
        if ($compareResult) {
            $_SESSION['brandUpdateError'] = 'This brand is already exists';
            header('location: /admin/brand/update/'.$brandId);
        } else {
            $currentBrand->setName($brandName);
            $em->persist($currentBrand);
            $em->flush();
            if (isset($_SESSION['brandUpdateError'])) {
                unset($_SESSION['brandUpdateError']);
            }
            header('location: /admin/brand');
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
        }
        $page404 = __DIR__ . "/admin/viev/404.php";
        require_once($page404);

    }



    public function getAll()
    {
        $em = $this->getEntityManager();
        /** @var Brand[] $result */
        $result = $em->getRepository(Brand::class)->findAll();
        if ($result) {
            return $result;
        }
        return null;
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

    public function brandTableRow($id, $brandName)
    {
        $element = "
        <tr>
            <td>$id</td>
            <td>$brandName</td>
            <td><a class=\"btn btn-warning\" href=\"brand/update/$id\" role=\"button\">Update</a></td>
            <td><a class=\"btn btn-danger\" href=\"check-delete-brand/$id\" role=\"button\">Delete</a></td>
        </tr>
        ";
        return $element;
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

    function brandOptionRow($id, $brandName, $brandIdOfProduct = null): string
    {
        if ($brandIdOfProduct != null && $id == $brandIdOfProduct) {
            return "<option selected value=\"$id\">$brandName</option>";
        } else {
            return "<option value=\"$id\">$brandName</option>";
        }
    }

}