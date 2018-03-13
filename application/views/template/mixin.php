let btTable = {
	data (){
		return {
			table : {
				tableClass : 'table table-bordered table-striped dataTable',
				ascendingIcon : 'glyphicon glyphicon-chevron-up',
				descendingIcon : 'glyphicon glyphicon-chevron-down',
				handleIcon : 'sorting'
			},
			pagination : {
				wrapperClass : "pagination pagination-sm no-margin pull-right",
				activeClass : "btn-primary",
				loadingClass:   "overlay",
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
			perPage : 10,
			search : '',
			formModal : {
				status : false,
				style : null,
				pesan : null,
				isCreateMode : true,
				currentType : 'create'
			},
			formOptions : {
				validateAfterChanged : true
			}
		}
	},
	watch: {
		'perPage' (val, oldVal) {
			this.$nextTick(function() {
				this.refreshTable()
			})
		}
	},
	methods: {
		resetError (){
			let errorList = Object.keys(this.error)
			for(x=0; x<errorList.length; x++){
				this.error[errorList[x]] = null
			}
		},
		refreshTable (){
			this.$refs.vuetable.refresh()
		},
		reloadTable (){
			this.$refs.vuetable.reload()
		},
		resetForm(){
			let field = Object.keys(this.model)
			for(x=0;x<field.length;x++){
				this.model[field[x]] = null
			}
			this.resetError()
		},
		onValidated(isValid, errors) {
		   console.log("Validation result: ", isValid, ", Errors:", errors);
		},
		toggleFormModal (){
			this.resetForm()
			this.formModal.status = !this.formModal.status
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
		saveData(x, tipe = this.formModal.currentType){
			let method = 'post'
			let url = this.base_url
			if(tipe == 'update'){
				method = 'put'
				url = this.base_url+'/'+x[this.tablePk]
			}
			axios[method](url, x)
				.then(res=>{
					this.formModal.pesan = ''
					this.$refs.vuetable.refresh()
					this.formModal.currentType = 'create'
					this.toggleFormModal()
				})
				.catch(err=>{
					this.resetError()
					let error = err.response.data
					if(error.status_code == 422) this.error = error.errors
				})
		},
		deleteData(x){
			if(window.confirm('Hapus data dengan NIDN = '+x+' ?')){
				axios.delete(this.base_url+'/'+x)
					.then(res=>{
						this.$refs.vuetable.refresh()
					})
					.catch(err=>{
						
					})
			}
		},
		getDetailData(x){
			let field = Object.keys(this.model)
			axios.get(this.base_url+'/'+x)
				.then(res=>{
					this.formModal.currentType = 'update'
					this.toggleFormModal()
					for(x=0;x<field.length;x++){
						this.$set(this.model, field[x], res.data.data[0][field[x]])
					}
				})
				.catch(err=>{
					console.log(err)
				})
		}
	}
}
