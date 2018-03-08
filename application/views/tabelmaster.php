<html>
<head>
	<title>test</title>
	<link rel="stylesheet" href="<?php echo base_url();?>assets/semantic.css" media="screen" title="no title" charset="utf-8">
</head>
<body>
	<script src="<?php echo base_url();?>/assets/vue.js"></script>
	<script src="<?php echo base_url();?>assets/axios.js"></script>
	<script src="<?php echo base_url();?>assets/vuetable.160.js"></script>
	<script src="<?php echo base_url();?>assets/jquery.js" charset="utf-8"></script>
    <script src="<?php echo base_url();?>assets/semantic.min.js" charset="utf-8"></script>
    <script src="<?php echo base_url();?>assets/vuejs-paginate.js" charset="utf-8"></script>
	<div id="app">
	 <div class="ui container">
	  <vuetable ref="vuetable"
	      api-url="https://vuetable.ratiw.net/api/users"
	      :fields="fields"
	      pagination-path=""
	      @vuetable:pagination-data="onPaginationData"
	    >
	    <template slot="actions" scope="props">
	    <div class="table-button-container">
	        <button class="ui button" @click="editRow(props.rowData)"><i class="fa fa-edit"></i> Edit</button>&nbsp;&nbsp;
	        <button class="ui basic red button" @click="deleteRow(props.rowData)"><i class="fa fa-remove"></i> Delete</button>&nbsp;&nbsp;
	    </div>
	</template>
	    </vuetable>
	    <vuetable-pagination ref="pagination"
	      @vuetable-pagination:change-page="onChangePage"
	    ></vuetable-pagination>
	    </div>
	</div>
<script>
	Vue.use(Vuetable);
new Vue({
  el: '#app',
  components:{
   'vuetable-pagination': Vuetable.VuetablePagination
  },
  data: {
  fields: ['name', 'email','birthdate','nickname','gender','__slot:actions']
  },
  computed:{
  /*httpOptions(){
    return {headers: {'Authorization': "my-token"}} //table props -> :http-options="httpOptions"
  },*/
 },
 methods: {
    onPaginationData (paginationData) {
      this.$refs.pagination.setPaginationData(paginationData)
    },
    onChangePage (page) {
      this.$refs.vuetable.changePage(page)
    },
    editRow(rowData){
      alert("You clicked edit on"+ JSON.stringify(rowData))
    },
    deleteRow(rowData){
      alert("You clicked delete on"+ JSON.stringify(rowData))
    }
  }
})
</script>
</body>
</html>
