<html>
<head>
	<title>test</title>
	<link rel="stylesheet" href="<?php echo base_url();?>assets/semantic.css" media="screen" title="no title" charset="utf-8">
</head>
<body>
	<script src="<?php echo base_url();?>assets/vue.js"></script>
	<script src="<?php echo base_url();?>assets/axios.js"></script>
	<script src="<?php echo base_url();?>assets/vuetable.160.js"></script>
	<script src="<?php echo base_url();?>assets/jquery.js" charset="utf-8"></script>
    <script src="<?php echo base_url();?>assets/semantic.min.js" charset="utf-8"></script>
    <script src="<?php echo base_url();?>assets/vuejs-paginate.js" charset="utf-8"></script>
	<div id="<?php echo $app->name; ?>">
	<!--START OF APPS -->
		<vuetable 
		ref="vuetable"
		:fields="columns" 
		pagination-path="pagination" 
		:api-url="base_url"
		@vuetable:pagination-data="onPaginationData"
		></vuetable>
		<vuetable-pagination 
		ref="pagination" 
		@vuetable-pagination:change-page="onChangePage">
		</vuetable-pagination>
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
		    },
		}
	})
</script>
</body>
</html>
