<?php   require_once 'header.php';
@(int)$page = $_GET['page'] ? $_GET['page'] : '1';
$param = 'list?keyword='.$_GET['keyword'].'&page='.$page;
@$order = $_GET['order'] ? $_GET['order'] : 'x';
if( @$_GET['order']){
    $param = $param.'&order='.$_GET['order'];
}
$listAry = json_decode(http_curl_get( $param ) ,true);
//var_dump($listAry);
$listNums = $listAry['Count'];
if($listAry['Count'] == 0 || $listNums == 0){
    LocationTo('404.php');
}
$is_search = true;
?>
    <div class="container-fluid content isSearch">
        <div class="header">
            <nav>
                <div class="navbar-header">
                    <a href="<?php echo DOMAIN_PATH;?>" title="<?php echo TITLE;?>"><img src="<?php echo DOMAIN_PATH;?>assets/img/logo.png" alt="<?php echo TITLE;?>" /></a>
                </div>
                <div class="collapse navbar-collapse">
                    <div class="navbar-form  search-title">
                        <input type="text" class="form-control input-md input-search square search-title-input" id="search" placeholder="搜索电影，音乐，番号，软件 . . .">
                        <button class="btn btn-md btn-success search-btn square" onclick="onSearch(search,1,'')">搜索</button>
                    </div>
                </div>

            </nav>
        </div>
        <div class="row  custom-panel">
            <div class="col-xs-6 col-md-7">
                <div class="row-fluid menu-nav">
                    <a href="<?php echo getProcessUrl('search.php?keyword='.$keyword.'&page='.$page.'&order=x');?>" id="order-x" <?php if($order == 'x' || $order == '') echo 'class=selected';?>>相关度</a>
                    <a href="<?php echo getProcessUrl('search.php?keyword='.$keyword.'&page='.$page.'&order=h');?>" id="order-h" <?php if($order == 'h') echo 'class=selected';?>>下载热度</a>
                    <a href="<?php echo getProcessUrl('search.php?keyword='.$keyword.'&page='.$page.'&order=m');?>" id="order-m" <?php if($order == 'm') echo 'class=selected';?>>文件大小</a>
                    <a href="<?php echo getProcessUrl('search.php?keyword='.$keyword.'&page='.$page.'&order=l');?>" id="order-l" <?php if($order == 'l') echo 'class=selected';?>>创建时间</a>
                </div>
                <div id="list-panel">
                    <p id="search-count">搜索到 <?php echo $listNums;?> 个相关BT资源</p>
                    </br>
                <?php foreach($listAry['Torrent'] as $k=>$v){    ?>
                    <h5><a href="<?php echo getProcessUrl('show.php?hash='.$v['Infohash']);?>" class="result-title"title="<?php echo $v['Name'];?>"><?php echo $v['Name'];?></a></h5>
                    文件大小:<span class="listinfo"><?php echo getSize($v['Length']);?></span>
                    下载热度:<span class="listinfo"><?php echo $v['Heat'];?></span>
                    文件数:<span class="listinfo"><?php echo $v['FileCount'];?></span>
                    创建时间:<span class="listinfo"><?php echo substr($v['CreateTime'],0,10);?></span>
                    <p></p><br>
                <?php }?>
                </div>
                <nav class="middle">
                    <?php 
                    $page_count = ceil($listNums / 20);
                    ?>
                    <ul class="pagination result-pagination">
    <?php if( $page > 1){
                echo '<li><a href="'.getProcessUrl('search.php?keyword='.$keyword.'&page='.($page-1).'&order='.$order).'" aria-label="Previous"><span>上一页</span></a></li>';
            }        
            $pagenav = '';
            $pagenav .= "跳到 <select name='topage' size='1' onchange=javascript:window.location=this.value>";
            for($i=1;$i<=$page_count;$i++){
                $jhref = getProcessUrl('search.php?keyword='.$keyword.'&page='.$i.'&order='.$order);
                if($i==$page) $pagenav.="<option value='$jhref' selected>$i</option>";
                else $pagenav.="<option value='".$jhref."'>$i</option>";
            }
            $pagenav .='</select>';
            echo '<li class="jjump">'.$pagenav.'</li>' ;
            
            if($page < $page_count){
                echo '<li><a href="'.getProcessUrl('search.php?keyword='.$keyword.'&page='.($page+1).'&order='.$order).'" aria-label="Next"><span>下一页</span></a></li>';
            }
            echo "<li class='jjump'>{$page} / {$page_count} , 共 {$listNums} 条</li>";
    ?>

                    </ul>
                </nav>
            </div>
        </div>
    </div>
<?php require_once 'footer.php';?>