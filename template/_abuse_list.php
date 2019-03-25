<div class="container">
	<div class="row">
    	<div class="col-md-12"> 
	     	<h1 class="page-header"><?php echo __('Abuse file list','i')?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
                <?php $res = mysqli_query($db,"SELECT *, 
				(SELECT id FROM ".$SET['db']['sql_tbl_prefix']."images WHERE short_url = ".$SET['db']['sql_tbl_prefix']."abuse.file LIMIT 1)
				as img_id FROM ".$SET['db']['sql_tbl_prefix']."abuse ORDER by date_add DESC");
if(mysqli_num_rows($res)>0){?>
	          <table class="table table-striped table-bordered">
           		<thead>
                	<tr>
                    	<th><?php echo __('File','i');?></th>
                    	<th><?php echo __('Date','i');?></th>
                        <th><?php echo __('Information','i');?></th>
                        <th><?php echo __('e-mail','i');?></th>
                        <th class="col-md-2"></th>
					</tr>                        
                </thead>
                <tbody>
                <?php while($line = mysqli_fetch_assoc($res)){?>
                	<tr class="item<?php echo $line['img_id']?>">
                    	<td><a href="/<?php echo $line['file']?>" target="_blank"><?php echo $line['file']?></a></td>
                    	<td><?php echo date('d.m.Y H:i',$line['date_add'])?></td>
                    	<td><?php echo $line['descr']?></td>         
                        <td><?php echo $line['email']?></td>  
                        <td class="text-center"><a class="btn btn-danger btn-sm del_abuse_img" id="del_abuse_img<?php echo $line['img_id']?>">Del img</a> <a href="" class="btn btn-warning btn-sm del_abuse" id="del_abuse<?php echo $line['img_id']?>">Del abuse</a></td>             
                    </tr>
                <?php }?> 
                </tbody>
           </table>
             <?php }?> 
        </div>
    </div>
</div>