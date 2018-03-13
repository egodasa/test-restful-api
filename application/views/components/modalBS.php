<script type="text/x-template" id="modalBS">
<div class="modal fade in" :style="style">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" @click="!show">
		  <span aria-hidden="true">×</span></button>
		<h4 class="modal-title">{{title}}</h4>
		<slot name="modalHeader">
		</slot>
	  </div>
	  <div class="modal-body">
		<slot name="modalBody">
		</slot>
	  </div>
	  <div class="modal-footer">
		<slot name="modalFooter">
		</slot>
	  </div>
	</div>
  </div>
</div>
</script>
<script>
	Vue.component('modalbs',{
		template : "#modalBS",
		props : {
			show : {
				required : true,
				type : Boolean,
				default : false
			},
			title : {
				required : false,
				type : String,
				default : 'Modal'
			}
		},
		data () {
			return {
				style : null
			}
		},
		watch : {
			'show' (val, oldVal) {
				if(val == true) this.style = 'display:block;';
				else this.style = 'display:none;';
			}
		}
	})
</script>
