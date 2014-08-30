<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">

            <div class="row">
                <div class="col-xs-12">
                    <form role="form" id="SearchPublicationsForm" novalidate="novalidate">
                        <div class="form-group" style="margin-bottom: 0;">
                            <div class="input-group">
                                <input type="text" class="form-control" id="search" name="search" placeholder="Eje: Laptops">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">Buscar</button>
                                </span>
                            </div>
                        </div>
                    </form>

                    <hr>

                </div>
            </div>
            <div class="row">
                <div class="col-xs-6 col-md-4">
                    <?php if(isset($categories)){ ?>
                        <?php foreach($categories as $department){ ?>
                            <div class="list-group">
                                <a href="#" class="list-group-item active">
                                    <h4 class="list-group-item-heading"><?php echo $department['Category']['name']; ?></h4>
                                </a>
                                <?php if($department['children']){ ?>
                                    <?php foreach($department['children'] as $category_general){ ?>

                                        <a href="#" class="list-group-item">
                                            <span class="badge">14</span>
                                            <h5 class="list-group-item-heading"><?php echo $category_general['Category']['name']; ?></h5>
                                        </a>

                                    <?php } ?>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    <?php } ?>

                </div>
                <div class="col-xs-12 col-sm-6 col-md-8">

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-xs-12">
                            <img src="/img/site_index/ax1200iHPB.png" alt="" class="img-thumbnail">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <img src="http://www.evga.com/products/images/gallery/12G-P4-3999-KR_XL_1.jpg" alt="" class="img-thumbnail">
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">

                    <hr>

                </div>
            </div>



        </div>
    </div>
</div>



<?php echo $this->Html->script('base.index',false); ?>