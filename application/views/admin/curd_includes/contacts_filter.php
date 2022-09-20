<form class="form">
	  <div class="form-group col-md-3">
		<select name="category_id" class="form-control">
			<option> All Services</option>
		<?php 
		$this->db->order_by("name", "asc");
		$categories = $this->db->get_where("categories", ["status"=>1])->result();
		foreach($categories as $item){?>
			<option value="<?=$item->id?>" <?=$item->id==$this->input->get_post("category_id")?"selected":""?>><?=$item->name?></option>
		<?php }
		?>
		</select>
	  </div>
	  <div class="form-group col-md-3">
		<select name="city_id" class="form-control">
			<option> All Cities</option>
		<?php 
		$this->db->order_by("name", "asc");
		$categories = $this->db->get_where("cities", ["status"=>1])->result();
		foreach($categories as $item){?>
			<option value="<?=$item->id?>" <?=$item->id==$this->input->get_post("city_id")?"selected":""?>><?=$item->name?></option>
		<?php }
		?>
		</select>
	  </div>
	  <div class="form-group col-md-4">
		<input type="q" class="form-control" id="q" name="q" value="<?=$this->input->get_post('q')?>" placeholder="Search by Contact Name, City, Mobile Number, Address">
	  </div>
	  <button type="submit" class="btn btn-info">Submit</button>
</form>
<hr/>