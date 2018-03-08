<?php 
include "template/head.php";
include "template/vue.php";
include "template/header.php";
include "template/sidebar.php";
?>
		<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo $title; ?>
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<!-- /.box -->
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"><?php echo $subTitle; ?></h3>
					</div>
					<!--START OF VUE-->
					<div class="box-body">
						<div id="<?php echo $app->name; ?>">
							<form>
							<vue-form-generator :schema="schema" :model="model" :options="formOptions"></vue-form-generator>
							<button type="button" @click="console.log(model)">Cek</button>
							</form>
						</div>
					</div>
					<!--//END OF VUE-->
				</div>
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>
<script>
		new Vue({
			el: '#<?php echo $app->name; ?>',
			components: {
		        "vue-form-generator": VueFormGenerator.component
		    },
			data() {
				return {
					model : {
						nohp : "",
						skill : [],
						jenis_kelamin : ""
					},
					schema : {
						fields : [
							{
								type : "input",
								label : "NOHP",
								inputType : "text",
								model : "nohp"
							},
							{
								type : "radios",
								label : "Skill",
								model : "skill",
								values : [
									{value : 1, name : "VueJS"},
									{value : 2, name : "AngularJS"},
									{value : 3, name : "NodeJS"},
									{value : 4, name : "ReactJS"},
									{value : 5, name : "PHP"},
									{value : 6, name : "Laravel"},
									{value : 7, name : "Codeigniter"},
									{value : 8, name : "Symfony"}
								]
							},
							{
								type : "select",
								label : "Jenis Kelamin",
								model : "jenis_kelamin",
								values : [
									{id : 1, name : "Laki-laki"},
									{id : 2, name : "Perempuan"}
								]
							}
						]
					},
					formOptions : {}
				}
			}
		})
	</script>
<!-- /.content-wrapper -->
<?php 
include "template/footer.php";
?>
<!-- ./wrapper -->
