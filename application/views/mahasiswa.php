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
			Data Tablesssssss
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
						<modalbs :show="formModal.status" title="Mahasiswa">
							<template slot="modalBody">
								<form @submit.prevent="saveData(model)">
									<form-generator :fields="schema.fields" :model="model" :error="error"></form-generator>
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
							</template>
						</modalbs>
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
											<input v-model="search" type="text" class="form-control" placeholder="Pencarian" @keyup.enter="onSearch(search)">
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
											<button data-toggle="tooltip_hapus" title="Hapus Data" @click="deleteData(props.rowData.nobp)" type="button" class="btn btn-default btn-xs">
												<span class="glyphicon glyphicon-remove"></span>
											</button>
											<button data-toggle="tooltip_edit" title="Ubah Data" @click="getDetailData(props.rowData.nobp)" type="button" class="btn btn-default btn-xs">
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
<?php
require "components/modalBS.php";
require "components/formGenerator.php";
?>
<script>
<?php
require "template/mixin.php";
?>
Vue.use(Vuetable);
new Vue({
	el: '#<?php echo $app->name; ?>',
	components: {
		'vuetable-pagination': Vuetable.VuetablePagination,
		'vuetable-pagination-info': Vuetable.VuetablePaginationInfo,
		"vue-form-generator": VueFormGenerator.component
	},
	mixins : [btTable],
	data() {
		return {
			tablePk : 'nobp',
			base_url : "http://localhost/api/mahasiswa",
			url : "http://localhost/api/mahasiswa",
			columns : [
				{name: "__sequence", title:"No"},
				{name: "nm_mahasiswa", title:"Nama Dosen", sortField : "nm_mahasiswa"},
				{name: "nobp", title:"NIDN", sortField : "nobp"},
				{name: "__slot:aksi", title:"Aksi"}
			],
			sortDefault : [
				{field : "nm_mahasiswa", direction:'asc'}
			],
			form : false,
			model : {
				nobp : null,
				nm_mahasiswa : null
			//	id_jk : null
			},
			error : {
				nobp : ' ',
				nm_mahasiswa : ' '
			//	id_jk : ' '
			},
			schema : {
				fields : [
					{
						type : "input",
						label : "NOBP",
						inputType : "text",
						name : "nobp",
						required : true,
						max : 15
					},
					{
						type : "textarea",
						label : "Nama Mahasiswa",
						inputType : "text",
						name : "nm_mahasiswa",
						required : true,
						max : 150
					}
				/*	{
						type : "select",
						label : "Jenis Kelamin",
						name : "id_jk",
						optionLabel : "nm_jk",
						option : [
							{id_jk : 1, nm_jk : "Laki-laki"},
							{id_jk : 2, nm_jk : "Perempuan"}
						]
					} */
				]
			}
		}
	}
})
	</script>
<!-- /.content-wrapper -->
<?php 
include "template/footer.php";
?>
<!-- ./wrapper -->
