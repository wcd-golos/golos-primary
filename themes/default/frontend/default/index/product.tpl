<div class="main">
  <div class="container">
		<ul class="breadcrumb">
            <li><a href="/">Главная</a></li>
            <li><a href="">Женщинам</a></li>
            <li class="active"><?php echo $this->product['name']?></li>
        </ul>
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
          <!-- BEGIN SIDEBAR -->
          <div class="sidebar col-md-3 col-sm-5">
            <ul class="list-group margin-bottom-25 sidebar-menu">
              
              <li class="list-group-item clearfix dropdown active">
                <a href="javascript:void(0);" class="collapsed">
                  <i class="fa fa-angle-right"></i>
                  Женщинам
                  <i class="fa fa-angle-down"></i>
                </a>
                <ul class="dropdown-menu" style="display:block;">
               	  <?php foreach ($this->products as $key=>$product):?>
                  <li class="list-group-item dropdown clearfix <?php if($key == $this->productCode):?>active<?php endif;?>">
                    <a href="<?php echo $this->url(array('module' => 'default', 'controller' => 'index', 'action' => 'product', 'name'=>$key), 'default', true); ?>"><i class="fa fa-circle"></i> <?php echo $product['name']?> </a>
                  </li>
                  <?php endforeach;?>
                </ul>
              </li>
              <li class="list-group-item clearfix"><a href="#"><i class="fa fa-angle-right"></i> Мужчинам</a></li>
              <li class="list-group-item clearfix"><a href="#"><i class="fa fa-angle-right"></i> Детям</a></li>
            </ul>

            <div class="sidebar-products clearfix">
              <h2>Лучшее Предложение</h2>
              <div class="item">
                <a href="#"><img src="/themes/default/assets/temp/products/k1.jpg" alt="Some Shoes in Animal with Cut Out"></a>
                <h3><a href="#">Платье</a></h3>
                <div class="price">$31.00</div>
              </div>
              <div class="item">
                <a href="#"><img src="/themes/default/assets/temp/products/k4.jpg" alt="Some Shoes in Animal with Cut Out"></a>
                <h3><a href="#">Платье</a></h3>
                <div class="price">$23.00</div>
              </div>
              <div class="item">
                <a href="#"><img src="/themes/default/assets/temp/products/k3.jpg" alt="Some Shoes in Animal with Cut Out"></a>
                <h3><a href="#">Платье</a></h3>
                <div class="price">$86.00</div>
              </div>
            </div>
          </div>
          <!-- END SIDEBAR -->
		<div class="col-md-9 col-sm-7">

		<div class="product-page">
          <div class="row">
            <div class="col-md-6 col-sm-6">
              <div class="product-main-image">
                <img src="/themes/default/assets/temp/products/<?php echo $this->product['image']?>" alt="Cool green dress with red bell" class="img-responsive" data-BigImgSrc="/themes/default/assets/temp/products/<?php echo $this->product['image']?>">
              </div>
              <div class="product-other-images">
                <a href="#" class="active"><img alt="Berry Lace Dress" src="/themes/default/assets/temp/products/<?php echo $this->product['image']?>"></a>
              </div>
            </div>
            <div class="col-md-6 col-sm-6">
              <h1><?php echo $this->product['name']?></h1>
              <div class="price-availability-block clearfix">
                <div class="price">
                  <strong><span>$</span><?php echo $this->product['price']?></strong>
                  <em>$<span><?php echo ($this->product['price'] + 11) ?></span></em>
                </div>
                <div class="availability">
                </div>
              </div>
              <div class="description">
                <p><?php echo $this->product['desc']?></p>
              </div>
              <div class="product-page-options">
                <div class="pull-left">
                  <label class="control-label">Размер:</label>
                  <select class="form-control input-sm">
                    <option>L</option>
                    <option>M</option>
                    <option>XL</option>
                  </select>
                </div>
                <div class="pull-left">
                  <label class="control-label">Цвет:</label>
                  <select class="form-control input-sm">
                    <option>Красный</option>
                    <option>Синий</option>
                    <option>Черный</option>
                  </select>
                </div>
              </div>
              <div class="review">
                <input type="range" value="4" step="0.25" id="backing4">
                <div class="rateit" data-rateit-backingfld="#backing4" data-rateit-resetable="false"  data-rateit-ispreset="true" data-rateit-min="0" data-rateit-max="5">
                </div>
              </div>
              <ul class="social-icons">
                <li><a class="facebook" data-original-title="facebook" href="#"></a></li>
                <li><a class="twitter" data-original-title="twitter" href="#"></a></li>
                <li><a class="googleplus" data-original-title="googleplus" href="#"></a></li>
                <li><a class="evernote" data-original-title="evernote" href="#"></a></li>
                <li><a class="tumblr" data-original-title="tumblr" href="#"></a></li>
              </ul>
            </div>

            <div class="product-page-content">
              <ul id="myTab" class="nav nav-tabs">
                <li><a href="#Description" data-toggle="tab">Описание</a></li>
                <li><a href="#Information" data-toggle="tab">Информация</a></li>
                <li class="active"><a href="#Reviews" data-toggle="tab">Отзывы (<?php echo $this->count?>)</a></li>
              </ul>
              <div id="myTabContent" class="tab-content">
                <div class="tab-pane fade" id="Description">
                  <p><?php echo $this->product['desc']?></p>
                </div>
                <div class="tab-pane fade" id="Information">
                  <table class="datasheet">
                    <tr>
                      <th colspan="2">Дополнительные возможности</th>
                    </tr>
                    <tr>
                      <td class="datasheet-features-type">Длина рукава</td>
                      <td>3/4</td>
                    </tr>
                    <tr>
                      <td class="datasheet-features-type">Покрой</td>
                      <td>приталенный</td>
                    </tr>
                    <tr>
                      <td class="datasheet-features-type">Рисунок</td>
                      <td>без рисунка</td>
                    </tr>
                    <tr>
                      <td class="datasheet-features-type">Длина юбки\платья</td>
                      <td>Миди</td>
                    </tr>
                    <tr>
                      <td class="datasheet-features-type">Пол</td>
                      <td>Женский</td>
                    </tr>
                  </table>
                </div>
                  <div class="tab-pane fade in active" id="Reviews">
                      <!--<p>There are no reviews for this product.</p>-->
                      <div id="reviewItems">
                      </div>
            		  <div class="row">
                          <div class="col-md-12 col-sm-12">
                          <!-- BEGIN FORM-->
                          <form action="<?php echo $this->url(array('module' => 'default', 'controller' => 'index', 'action' => 'post'), 'default', true); ?>" class="reviews-form" role="form">
                            <h2>Написать отзыв</h2>
                            <div class="form-group">
                              <label for="review">Отзыв <span class="require">*</span></label>
                              <textarea class="form-control" name="review"  rows="8" id="review"></textarea>
                            </div>
                            <?php /*?>
                            <div class="form-group">
                              <label for="tags">Тэги</label>
                              <input type="text" name="tags" class="form-control" id="tags" placeholder="Добавить тэги (до 5 штук)">
                            </div>
                            <?php */?>
                            <div class="padding-top-20">                  
                              <button type="submit" class="btn btn-primary">Отправить</button>
                            </div>
                          </form>
                          <!-- END FORM--> 
                          </div>
                      </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
        </div>
	</div>
	
	<!-- BEGIN SIMILAR PRODUCTS -->
    <div class="row margin-bottom-40">
      <div class="col-md-12 col-sm-12 bxslider-wrapper bxslider-wrapper-similar-products">
        <h2>Популярные Продукты</h2>
          <ul class="bxslider bxslider-similar-products" data-slides-phone="1" data-slides-tablet="2" data-slides-desktop="4" data-slide-margin="20">
            <li>
              <div class="product-item">
                <div class="pi-img-wrapper">
                  <img src="/themes/default/assets/temp/products/k4.jpg" class="img-responsive" alt="Berry Lace Dress">
                  <div>
                    <a href="/themes/default/assets/temp/products/k4.jpg" class="btn btn-default fancybox-button">Увеличить</a>
                  </div>
                </div>
                <h3><a href="#">Berry Lace Dress</a></h3>
                <div class="pi-price">$29.00</div>
              </div>
            </li>
            <li>
              <div class="product-item">
                <div class="pi-img-wrapper">
                  <img src="/themes/default/assets/temp/products/k1.jpg" class="img-responsive" alt="Berry Lace Dress">
                  <div>
                    <a href="/themes/default/assets/temp/products/k1.jpg" class="btn btn-default fancybox-button">Увеличить</a>
                  </div>
                </div>
                <h3><a href="#">Berry Lace Dress2</a></h3>
                <div class="pi-price">$29.00</div>
              </div>
            </li>
            <li>
              <div class="product-item">
                <div class="pi-img-wrapper">
                  <img src="/themes/default/assets/temp/products/k2.jpg" class="img-responsive" alt="Berry Lace Dress">
                  <div>
                    <a href="/themes/default/assets/temp/products/k2.jpg" class="btn btn-default fancybox-button">Увеличить</a>
                  </div>
                </div>
                <h3><a href="#">Berry Lace Dress3</a></h3>
                <div class="pi-price">$29.00</div>
              </div>
            </li>
            <li>
              <div class="product-item">
                <div class="pi-img-wrapper">
                  <img src="/themes/default/assets/temp/products/k3.jpg" class="img-responsive" alt="Berry Lace Dress">
                  <div>
                    <a href="/themes/default/assets/temp/products/k3.jpg" class="btn btn-default fancybox-button">Увеличить</a>
                  </div>
                </div>
                <h3><a href="#">Berry Lace Dress4</a></h3>
                <div class="pi-price">$29.00</div>
              </div>
            </li>
            <li>
              <div class="product-item">
                <div class="pi-img-wrapper">
                  <img src="/themes/default/assets/temp/products/k4.jpg" class="img-responsive" alt="Berry Lace Dress">
                  <div>
                    <a href="/themes/default/assets/temp/products/k4.jpg" class="btn btn-default fancybox-button">Увеличить</a>
                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                  </div>
                </div>
                <h3><a href="#">Berry Lace Dress5</a></h3>
                <div class="pi-price">$29.00</div>
              </div>
            </li>
            <li>
              <div class="product-item">
                <div class="pi-img-wrapper">
                  <img src="/themes/default/assets/temp/products/k1.jpg" class="img-responsive" alt="Berry Lace Dress">
                  <div>
                    <a href="/themes/default/assets/temp/products/k1.jpg" class="btn btn-default fancybox-button">Увеличить</a>
                  </div>
                </div>
                <h3><a href="#">Berry Lace Dress6</a></h3>
                <div class="pi-price">$29.00</div>
              </div>
            </li>
          </ul>
      </div>
    </div>
    <!-- END SIMILAR PRODUCTS -->
</div>
</div>
<?php 
$this->inlineScript()->prependFile($this->BaseUrl . '/themes/default/assets/scripts/golos-app.js');
$paramProtocol = 'http';
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
    $paramProtocol = 'https';
}
$productName = $this->product["name"];
$productCode = $this->productCode;
$productImage = $paramProtocol . '://' . $_SERVER['HTTP_HOST'] . '/themes/default/assets/temp/products/' . $this->product['image'];
$this->inlineScript()->captureStart();
echo <<<JS
reviewsData = $this->jsonData;
productName = '$productName';
productCode = '$productCode';
productImage = '$productImage';
jQuery(document).ready(function() {

  GolosApp.init();                  
  
});
JS;
$this->inlineScript()->captureEnd();
?>