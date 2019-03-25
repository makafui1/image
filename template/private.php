<?php

$img_file = 'images/image.png';
?>
<div class="container" id="content">
<div class="row">
<div class="col-md-12">
	<h1 class="page-header"><a href="" rel="nofollow" title="Download IMG_2950.JPG">IMG_2950.JPG</a> <small>(72.7 KB)</small></h1>
    <div class="row">
    	<div class="col-md-4">
            <a href="">
                <div class="thumbnail">
                    <img alt="" src="<?php echo $img_file?>">
                </div>
            </a>
        </div>
        <div class="col-md-8">
            <dl>
                <dt>MD5</dt>
                <dd><?php echo md5_file($img_file)?></dd>
                <dt>SHA1</dt>
                <dd><?php echo sha1_file($img_file)?></dd>
            </dl>
            <ul class="det">
                <li><i class="fa fa-user fa-fw"></i> anonymous</li>
                <li><i class="fa fa-clock-o fa-fw"></i> <time datetime="2015-06-22 10:28:07 +0300">June 22, 2015 10:28</time></li>
                <li><i class="fa fa-download fa-fw"></i> Downloaded 0 times </li>
                <li class="qr"><i class="fa fa-qrcode fa-fw"></i> <a href="#" class="download-helper">QR</a></li>
                <li><i class="fa fa-minus-circle fa-fw"><a href="" class="download-helper" rel="nofollow">Abuse</a></i></li>
            </ul>
            <div>
            <strong>Tags</strong>: фото
            </div>
            <hr>
            <a href="#" class="btn btn-primary" rel="nofollow" title="Download IMG_2950.JPG">Download</a>
            <a href="#" class="btn btn-success">Earn money</a>
            <a href="#" class="btn btn-primary">Edit</a>
            <a href="#" class="btn btn-danger" data-confirm="Are you sure?" data-method="delete" rel="nofollow" title="Remove">Remove</a>
        </div>
    </div>
    <hr>
    <div class="row">
    	<div class="col-md-7">
        <?php $exif_data = exif_read_data('images/image.png');
		$img_info = getimagesize('images/image.png');
		
		$size 				= $img_info[0].'x'.$img_info[1];
		$model 				= $exif_data['Model'];
		$Savetime			= $exif_data['DateTime'];
		$DateTimeOriginal  	= $exif_data['DateTimeOriginal'];
	    $DateTimeDigitized 	= $exif_data['DateTimeDigitized'];
		?>
        <table class="table table-bordered table-condensed">
            <tbody>
                <tr>
                    <td colspan="2"><h4>General</h4></td>
                </tr>
                <tr>
                    <td>Format:</td>
                    <td>JPEG (Joint Photographic Experts Group JFIF format)</td>
                </tr>
                <tr>
                    <td>Size:</td>
                    <td><?php echo $size?></td>
                </tr>
                <tr>
                    <td>Print size:</td>
                    <td><?php echo $size?></td>
                </tr>
                <tr>
                    <td>Color depth:</td>
                    <td><?php echo $img_info['bits']?>-bit</td>
                </tr>
                <tr>
                    <td>Color space:</td>
                    <td>sRGB</td>
                </tr>
                <tr>
                    <td>Palette:</td>
                    <td>True color</td>
                </tr>
                <tr>
                    <td colspan="2"><h4>EXIF</h4></td>
                </tr>
                <tr>
                    <td>Shot time:</td>
                    <td><?php echo $DateTimeOriginal?></td>
                </tr>
                <tr>
                    <td>Digitized time:</td>
                    <td><?php echo $DateTimeDigitized?></td>
                </tr>
                <tr>
                    <td>Save time:</td>
                    <td><?php echo $Savetime?></td>
                </tr>
                <tr>
                    <td>Camera model:</td>
                    <td><?php echo $model?></td>
                </tr>
            </tbody>
        </table>
        </div>
        <div class="col-md-5">
            <form action="#" class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="fake optional control-label" for="fileset_thumb_html">HTML (preview)</label>
                    <div class="controls">
                        <input class="form-control" id="thumb_html" name="thumb_html" onclick="this.select();" readonly="readonly" type="text" value="<a href=''><img src=''/></a>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="fake optional control-label" for="fileset_image_html">HTML (image)</label>
                    <div class="controls">
                        <input class="form-control" id="image_html" name="image_html" onclick="this.select();" readonly="readonly" type="text" value="<a href=''><img src=''/></a>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="fake optional control-label" for="fileset_thumb_bb">BB (preview)</label>
                    <div class="controls">
                        <input class="form-control" id="thumb_bb" name="thumb_bb" onclick="this.select();" readonly="readonly" type="text" value="[url=][img][/img][/url]">
                    </div>
                </div>
                <div class="form-group">
                    <label class="fake optional control-label" for="fileset_image_bb">BB (image)</label>
                    <div class="controls">
                        <input class="form-control" id="image_bb" name="image_bb" onclick="this.select();" readonly="readonly" type="text" value="[url=][img][/img][/url]">
                    </div>
                </div>
                <div class="form-group">
                    <label class="fake optional control-label" for="fileset_direct_link">Direct link</label>
                    <div class="controls">
                        <input class="form-control" id="direct_link" name="direct_link" onclick="this.select();" readonly="readonly" type="text" value="">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>    
</div>