<h2><?php echo __('User list','i')?></h2>
<div class="col-md-12">
<table class="table table-bordered">
	<thead>
    	<tr>
        	<th><?php echo __('User name','i');?></th>
        	<th><?php echo __('User email','i');?></th> 
            <th><?php echo __('Count image','i');?></th>            
        </tr>
    </thead>
    <tbody>
    <?php $res = mysqli_query($db,"SELECT *,
	(SELECT count(*) FROM ".$SET['db']['sql_tbl_prefix']."images WHERE id_user = ".$SET['db']['sql_tbl_prefix']."users.id LIMIT 1) as count_img
	 FROM ".$SET['db']['sql_tbl_prefix']."users ORDER by count_img DESC");
		if(mysqli_num_rows($res)>0){
			while($line = mysqli_fetch_assoc($res)){
			?>
    	<tr>
        	<td><?php echo $line['name'];?></td>
            <td><?php echo $line['email'];?></td>
            <td><?php echo $line['count_img'];?></td>
        </tr>
    <?php } }?>    
    </tbody>
</table>
</div>