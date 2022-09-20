<table id="table_id" class="display">
	<thead>
		<tr>
			<th>S.No</th>
			<th>User Name</th>
			<th>Role</th>
			<th>Address</th>			
			<th>Logged AT</th>			
		</tr>
	</thead>
	<tbody>
		<?php $i=1; foreach($items as $item){ ?>
		<tr>
			<td><?=$i++?></td>
			<td><?=$item->username?></td>
			<td><?=$item->role?></td>
			<td><?=$item->address?></td>
			<td><?=date("d-m-Y h:i a",$item->created_at)?></td>
		</tr>
		<?php }?>
	</tbody>
</table>