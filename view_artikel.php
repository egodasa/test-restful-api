<html>
<head>
	<title>test</title>
</head>
<body>
	<link rel="stylesheet" href="<?php echo base_url();?>assets/bootstrap3/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo base_url();?>assets/bootstrap3/font-awesome/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="<?php echo base_url();?>assets/bootstrap3/Ionicons/css/ionicons.min.css">
	<script src="<?php echo base_url();?>assets/bootstrap3/jquery/dist/jquery.min.js"></script>
	<script src="<?php echo base_url();?>assets/bootstrap3/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url();?>assets/vue.js"></script>
	<script src="<?php echo base_url();?>assets/axios.js"></script>
	<script src="<?php echo base_url();?>assets/vuetable.160.js"></script>
    <script src="<?php echo base_url();?>assets/vuejs-paginate.js" charset="utf-8"></script>
	<div id="<?php echo $app->name; ?>">
	<!--START OF APPS -->
	<div class="row">
		<label class="control-label col-lg-6" for="nama">Cari Data : </label>
		<div class="col-lg-12">
			<input type="search" class="form-control">
		</div>
	</div>
		<vuetable 
		ref="vuetable"
		:reactive-api-url.Boolean="true"
		:sort-order="sortDefault"
		:fields="columns" 
		:pagination-path="paginationPath" 
		:api-url="base_url"
		@vuetable:pagination-data="onPaginationData"
		:css="table"
		:per-page="perPage"
		></vuetable>
		<div class="row">
			<div class="col-lg-6">
				<div class="row">
					<label class="control-label col-lg-6" for="nama">Data perhalaman : </label>
					<div class="col-lg-6">
						<select class="form-control col-lg-6" v-model="perPage">
							<option :value="10">10</option>
							<option :value="20">20</option>
							<option :value="50">50</option>
						</select>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<vuetable-pagination 
				ref="pagination" 
				@vuetable-pagination:change-page="onChangePage"
				:css="pagination"
				>
				</vuetable-pagination>
			</div>
		</div>
	<!--END OF APPS -->
	</div>
<script>
	Vue.use(Vuetable);
	new Vue({
		el : '#<?php echo $app->name; ?>',
		components:{
		   'vuetable-pagination': Vuetable.VuetablePagination
		  },
		data (){
			return JSON.parse('<?php echo json_encode($app->data);?>');
			},
		methods : {
			onChangePage (page) {
				this.$refs.vuetable.changePage(page)
			},
			onPaginationData (paginationData) {
		      this.$refs.pagination.setPaginationData(paginationData)
		    }
		},
		watch : {
			'perPage' (val, oldVal) {
				this.$nextTick(function() {
					this.$refs.vuetable.refresh()
				})
			}
		}
	})
</script>
</body>
</html>
