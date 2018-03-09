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
							<form @submit.prevent="saveDosen(model)">
							<vue-form-generator :schema="schema" :model="model" :options="formOptions"></vue-form-generator>
							<button type="submit" class="btn btn-primary">Simpan</button>
							<button type="reset" class="btn btn-success">Reset</button>
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
						nidn : null,
						nm_dosen : null
					},
					schema : {
						fields : [
							{
								type : "input",
								label : "NIDN",
								inputType : "text",
								model : "nidn"
							},
							{
								type : "input",
								label : "Nama Dosen",
								inputType : "text",
								model : "nm_dosen"
							}
						]
					},
					formOptions : {}
				}
			},
			methods : {
				saveDosen(x){
					axios.post('http://localhost/api/dosen', x)
						.then(res=>{
							console.log('Berhasil')
							window.location = 'http://localhost/api/vueci/tabel'
						})
						.catch(err=>{
							console.log('Gagal')
						})
				}
			}
		})
	</script>
<!-- /.content-wrapper -->
<?php 
include "template/footer.php";
?>
<!-- ./wrapper -->
