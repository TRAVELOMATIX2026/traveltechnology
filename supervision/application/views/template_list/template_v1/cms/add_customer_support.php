<script src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/ckeditor/ckeditor.js"></script>
<?php
// debug($sub_admin);exit;
//get sub admin table
$table = '<div>
				<table class="table table-sm">
				<tr>
					<th>Question</th>
					<th>Answer</th>
					<th>Status</th>
					<th>Action</th>
				</tr>';
	if (valid_array($sub_admin) == true) {
		foreach ($sub_admin as $key => $value) {
			$action = '<a role="button" href="'.base_url().'index.php/cms/add_customer_support/'.$value['origin'].'"><button class="btn btn-sm">Edit</button></a>';
			$action .= '<a role="button" href="'.base_url().'index.php/cms/delete_customer_support/'.$value['origin'].'"><button class="btn btn-sm">Delete</button></a>';
			if (intval($value['status']) == 1) {
				$status_label = '<span class="badge bg-success">Active</span>';
				$status_button = '<a role="button" href="'.base_url().'index.php/cms/customer_support_status/'.$value['origin'].'/D"><button class="badge bg-danger">Deactivate</button></a>'; 
			} else {
				$status_label = '<span class="badge bg-danger">Inactive</span>';
				$status_button = '<a role="button" href="'.base_url().'index.php/cms/customer_support_status/'.$value['origin'].'/A"><button class="badge bg-success">Activate</button></a>';
			} 
			$table .= '<tr>
				<td>'.$value['question'].'</td>
				<td>'.$value['answer'].'</td>
				<td>'.$status_label.'</td>
				<td>'.$action.' '.$status_button.'</td>
			</tr>';
		}
	} else {
		$table .= '<tr><td colspan="8">No Cms Page Found In The System</td></tr>';
	}
$table .= '</table></div>';
//end of table section
?>
<div class="bodyContent">
	<div class="panel <?=PANEL_WRAPPER?>"><!-- PANEL WRAP START -->
		<div class="card-header"><!-- PANEL HEAD START -->
			<div class="card-title">
				<i class="fa fa-edit"></i> Customer support
			</div>
		</div><!-- PANEL HEAD START -->
		<div class="card-body"><!-- PANEL BODY START -->

<form method="post" autocomplete="off" action="<?php echo base_url();?>index.php/cms/add_customer_support/<?php echo $ID;?>" id="profile_form">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-sm">
 <tr>
    <td>Question<span class="text-danger">*</span></td>
    <td>
    	<!-- <input type="text" name="question" value="<?php echo isset($question) ? $question : '';?>"> -->
    	<textarea class="ckeditor" id="editor" name="question" rows="2" cols="10"><?php echo isset($question) ? $question : '';?></textarea>
    <font color="red" ><?=@form_error('question')?> </font>
    </td>
  </tr>
  <tr>
  	<td>Answer<span class="text-danger">*</span></td>
    <td><textarea class="ckeditor" id="editor" name="answer" rows="10" cols="80"><?php echo isset($answer) ? $answer : '';?></textarea>
     <font color="red" ><?=@form_error('answer')?> </font>
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center"><input type="submit" class="btn btn-sm btn-success" value="Submit"/></td>
  
  </tr>
</table>
</form>
</div>
</div>
</div>
<div class="bodyContent">
	<div class="panel <?=PANEL_WRAPPER?>"><!-- PANEL WRAP START -->
		<div class="card-header"><!-- PANEL HEAD START -->
			<div class="card-title">
				<i class="fa fa-edit"></i> Customer support list
			</div>
		</div><!-- PANEL HEAD START -->
		<div class="card-body"><!-- PANEL BODY START -->
<div class="card-body">
<?php 
echo $table;
?>
</div>
</div></div></div>