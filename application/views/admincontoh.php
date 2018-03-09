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
	<!--START OF VUE-->
	<section class="content" id="<?php echo $app->name; ?>">
		<div class="row">
			<div class="col-xs-12">
				<!-- /.box -->
				<div class="box">
					<div class="box-header">
						<button type="button" class="btn btn-primary btn-sm" @click="toggleFormModal">
							<span class="glyphicon glyphicon-plus"></span> Data Baru
						</button>
						<div class="modal fade in" id="formDosen" :style="formModal.style">
				          <div class="modal-dialog">
				            <div class="modal-content">
				              <div class="modal-header">
				                <button type="button" class="close" @click="toggleFormModal">
				                  <span aria-hidden="true">×</span></button>
				                <h4 class="modal-title">Default Modal</h4>
				              </div>
				              <div class="modal-body">
				                <form @submit.prevent="saveDosen(model)">
								<vue-form-generator @validated="onValidated()" :schema="schema" :model="model" :options="formOptions"></vue-form-generator>
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="pull-left">
											<p>{{formModal.pesan}}</p>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="pull-right">
											<button type="submit" class="btn btn-primary" >Simpan</button>
											<button type="reset" class="btn btn-warning">Reset</button>
											<button type="button" class="btn btn-success" @click="toggleFormModal">Batal</button>
										</div>
									</div>
								  </div>
								</form>
				              </div>
				              <div class="modal-footer">
								
				              </div>
				            </div>
				            <!-- /.modal-content -->
				          </div>
				          <!-- /.modal-dialog -->
				        </div>
					</div>
					
					<div class="box-body">
						<div class="dataTables_wrapper form-inline dt-bootstrap">
							<div class="row">
								<div class="col-sm-6">
									<div class="dataTables_length" id="example1_length"><label>Tampilkan
									<select name="example1_length" aria-controls="example1" class="form-control input-sm" v-model="perPage">
										<option value="10">10</option>
										<option value="25">25</option>
										<option value="50">50</option>
										<option value="100">100</option>
									</select> data</label>
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
									<vuetable ref="vuetable" track-by="tablePk" table-height="450" :reactive-api-url.Boolean="true" :sort-order="sortDefault" :fields="columns" :pagination-path="paginationPath" :api-url="url" @vuetable:pagination-data="onPaginationData" :css="table" :per-page="perPage" :append-params="appendParams">
										<template slot="aksi" scope="props">
											<button data-toggle="tooltip_hapus" title="Hapus Data" @click="deleteDosen(props.rowData.nidn)" type="button" class="btn btn-default btn-xs">
												<span class="glyphicon glyphicon-remove"></span>
											</button>
											<button data-toggle="tooltip_edit" title="Ubah Data" @click="getDetailDosen(props.rowData.nidn)" type="button" class="btn btn-default btn-xs">
												<span class="glyphicon glyphicon-pencil"></span>
											</button>
										</template>
									</vuetable>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-5">
									<div class="dataTables_info" id="example1_info" role="status" aria-live="polite">
										<vuetable-pagination-info ref="paginationInfo"></vuetable-pagination-info>
									</div>
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
				'vuetable-pagination-info': Vuetable.VuetablePaginationInfo,
				"vue-form-generator": VueFormGenerator.component
			},
			data() {
				return {
					tablePk : 'nidn',
					base_url : "http://localhost/api/dosen",
					url : "http://localhost/api/dosen",
					url_search : "http://localhost/api/cari/dosen",
					columns : [
						{name: "__sequence", title:"No"},
						{name: "nm_dosen", title:"Nama Dosen", sortField : "nm_dosen"},
						{name: "nidn", title:"NIDN", sortField : "nidn"},
						{name: "__slot:aksi", title:"Aksi"}
					],
					table : {
						tableClass : 'table table-bordered table-striped dataTable',
						ascendingIcon : 'glyphicon glyphicon-chevron-up',
						descendingIcon : 'glyphicon glyphicon-chevron-down',
						handleIcon : 'sorting'
					},
					pagination : {
						wrapperClass : "pagination pagination-sm no-margin pull-right",
						activeClass : "btn-primary",
						disabledClass : "disabled",
						pageClass : "btn btn-border",
						linkClass : "btn btn-border",
						infoClass : "pull-left",
						icons : {
							'first': "glyphicon glyphicon-fast-backward",
						    'prev':"glyphicon glyphicon-backward",
						    'next':"glyphicon glyphicon-forward",
						    'last':"glyphicon glyphicon-fast-forward"
						}
					},
					appendParams : {},
					paginationPath : "pagination",
					sortDefault : [
						{field : "nm_dosen", direction:'asc'}
					],
					perPage : 10,
					search : '',
					form : false,
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
								model : "nidn",
								required : true,
								max : 15
							},
							{
								type : "input",
								label : "Nama Dosen",
								inputType : "text",
								model : "nm_dosen",
								required : true,
								max : 150
							}
						]
					},
					formOptions : {
						validateAfterChanged : true
					},
					formModal : {
						status : false,
						style : null,
						pesan : null,
						isCreateMode : true,
						currentType : 'create'
					},
				}
			},
			methods: {
				resetForm(){
					this.model.nidn = ''
					this.model.nm_dosen = ''
					if(this.formModal.currentType == 'update') this.schema.fields[0].disabled = true
					else this.schema.fields[0].disabled = false
				},
				onValidated(isValid, errors) {
				   console.log("Validation result: ", isValid, ", Errors:", errors);
				},
				toggleFormModal (){
					this.resetForm()
					this.formModal.status = !this.formModal.status
					this.formModal.status ? this.formModal.style = 'display:block;' : this.formModal.style = 'display:none;'
				},
				onChangePage(page) {
					this.$refs.vuetable.changePage(page)
				},
				onPaginationData(paginationData) {
					this.$refs.pagination.setPaginationData(paginationData)
					this.$refs.paginationInfo.setPaginationData(paginationData)
				},
				onSearch(x) {
					this.appendParams.search = x
					this.$refs.vuetable.refresh()
				},
				onResetSearch(x) {
					this.appendParams.search = undefined
					this.search = ''
					this.$refs.vuetable.refresh()
				},
				saveDosen(x, tipe = this.formModal.currentType){
					let method = 'post'
					let url = this.base_url
					if(tipe == 'update'){
						method = 'put'
						url = 'http://localhost/api/dosen/'+x[this.tablePk]
					}
					axios[method](url, x)
						.then(res=>{
							this.formModal.pesan = ''
							this.$refs.vuetable.refresh()
							this.formModal.currentType = 'create'
							this.toggleFormModal()
						})
						.catch(err=>{
							this.formModal.pesan = "Telah terjadi kesalahan pada server. Silahkan coba lagi nanti."
						})
				},
				deleteDosen(x){
					if(window.confirm('Hapus data dengan NIDN = '+x+' ?')){
						axios.delete('http://localhost/api/dosen/'+x)
							.then(res=>{
								this.$refs.vuetable.refresh()
							})
							.catch(err=>{
								
							})
					}
				},
				getDetailDosen(x){
					axios.get('http://localhost/api/dosen/'+x)
						.then(res=>{
							this.formModal.currentType = 'update'
							this.toggleFormModal()
							this.$set(this.model, 'nidn', res.data.data[0].nidn)
							this.$set(this.model, 'nm_dosen', res.data.data[0].nm_dosen)
						})
						.catch(err=>{
							console.log(err)
						})
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
