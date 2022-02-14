<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link
                <?php if ($pageName == "admin-list-products-body.php" || $pageName == "admin-add-product-body.php")
                    echo "active" ?>"
                   aria-current="page" href="admin-list-products.php">
                    Ürünler
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if ($pageName == "admin-list-categories-body.php") echo "active" ?>"
                   href="admin-list-categories.php">
                    Kategoriler
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if ($pageName == "apply-comments.php") echo "active" ?>" href="#">
                    Yorumlar
                </a>
            </li>
        </ul>
    </div>
</nav>

