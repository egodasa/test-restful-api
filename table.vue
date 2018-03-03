<template>
	<div class="home">
	<button type="button" @click="getData()">Refresh</button>
	<v2-table :data="records" @sort-change="sortData" :total="total_rows" :pagination-info="tableFooter" @page-change="paginateData" :shown-pagination="true" border>
        <v2-table-column v-for="(r,index,key) in kolom" :label="r.label" :prop="r.field" :border="true" :key="index" :sortable="r.sortable"></v2-table-column>
    </v2-table>  
	</div>
</template>

<script>
// @ is an alias to /src
import qs from 'qs'
export default {
	name: 'tabelv2',
	data () {
		return {
			kolom : [
				{label: 'NIDN', field: 'nidn', sortable : true},
				{label: 'Nama Dosen', field: 'nm_dosen', sortable : true}
			],
			records : [],
			base_url : 'http://localhost/api/dosen',
			total_rows : 0,
			current_page : 1,
			per_page : 10,
			sort : undefined,
			tableFooter : {
				text : 'Gundulmu....'
			}
			}
	},
	created () {
		this.getData()
	},
	methods : {
		getData (url = this.base_url, limit = this.per_page , page = this.current_page, sort = this.sort) {
			let finalUrl = url+'?'+qs.stringify({
				limit : limit,
				page : page,
				sort : sort
			})
			this.$ajx.get(finalUrl)
			.then(res=>{
				let fetchData = res.data
				this.records = fetchData.data
				this.total_rows = fetchData.pagination.total_rows
				})
			},
		sortData ({prop, order}) {
			let finalOrder
			if(order == 'ascending') finalOrder = prop+'|asc'
			else if(order == 'descending') finalOrder = prop+'|desc'
			this.sort = finalOrder
			this.getData()
		},
		paginateData(page){
			this.current_page = page
			this.getData()
		}
		}
	}
</script>
