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
			Data Tables
			<small>advanced tables</small>
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<!-- /.box -->
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Responsive Hover Table</h3>
					</div>
					<!--START OF VUE-->
					<div class="box-body">
						<div id="<?php echo $app->name; ?>" class="dataTables_wrapper form-inline dt-bootstrap">
							<div class="row">
								<div class="col-sm-6">
									<div class="dataTables_length" id="example1_length"><label>Show 
									<select name="example1_length" aria-controls="example1" class="form-control input-sm" v-model="perPage">
										<option value="10">10</option>
										<option value="25">25</option>
										<option value="50">50</option>
										<option value="100">100</option>
									</select> entries</label>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="pull-right">
										<div class="input-group">
											<input v-model="search" type="text" class="form-control" placeholder="Search" @keyup.enter="onSearch(search)">
											<div class="input-group-btn">
												<button @click="onSearch(search)" class="btn btn-default" type="submit">
													<i class="glyphicon glyphicon-search"></i>
												</button>
											</div>
											<div class="input-group-btn">
												<button @click="onResetSearch" class="btn btn-default" type="submit">
													<i class="glyphicon glyphicon-remove"></i>
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<vuetable ref="vuetable" :reactive-api-url.Boolean="true" :sort-order="sortDefault" :fields="columns" :pagination-path="paginationPath" :api-url="url" @vuetable:pagination-data="onPaginationData" :css="table" :per-page="perPage"></vuetable>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-5">
									<div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing 11 to 20 of 57 entries</div>
								</div>
								<div class="col-sm-7">
									<div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">
										<vuetable-pagination ref="pagination" @vuetable-pagination:change-page="onChangePage" :css="pagination">
										</vuetable-pagination>
									</div>
								</div>
							</div>
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
		Vue.use(Vuetable);
		new Vue({
			el: '#<?php echo $app->name; ?>',
			components: {
				'vuetable-pagination': Vuetable.VuetablePagination,
				'vuetable-pagination-info': Vuetable.VuetablePaginationInfo
			},
			data() {
				return JSON.parse('<?php echo json_encode($app->data);?>');
			},
			methods: {
				onChangePage(page) {
					this.$refs.vuetable.changePage(page)
				},
				onPaginationData(paginationData) {
					this.$refs.pagination.setPaginationData(paginationData)
				},
				onSearch(x) {
					this.url = this.url_search+'/'+x
				},
				onResetSearch(x) {
					this.url = this.base_url
					this.search = ''
				}
			},
			watch: {
				'perPage' (val, oldVal) {
					this.$nextTick(function() {
						this.$refs.vuetable.refresh()
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
