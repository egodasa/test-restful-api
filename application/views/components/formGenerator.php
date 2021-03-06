<script type="text/x-template" id="formGenerator">
<div>
	<template v-for="x in fields">
		<template v-if="x.type == 'input'">
			<div class="form-group">
				<label :for="'formInput'+x.name">{{x.label}}</label>
				<input :type="x.inputType" class="form-control" :id="'formInput'+x.name" :min="x.min" :max="x.max" :placeholder="x.placeholder" v-model="model[x.name]" :disabled="x.disabled">
				<p class="help-block text-red">{{error[x.name]}}</p>
			</div>
		</template>
		<template v-else-if="x.type == 'textarea'">
			<div class="form-group">
                <label>{{x.label}}</label>
                <textarea class="form-control" :rows="x.rows" :placeholder="x.placeholder" :disabled="x.disabled" v-model="model[x.name]"></textarea>
            </div>
		</template>
		<template v-else-if="x.type == 'checkbox'">
			<div class="checkbox">
				<label>
				<input type="checkbox" v-model="model[x.name]" :disabled="x.disabled"> {{x.label}}
				</label>
			</div>
		</template>
		<template v-else-if="x.type == 'select'">
			<div class="form-group">
				<label>{{x.label}}</label>
				<select class="form-control" v-model="model[x.name]" :disabled="x.disabled">
					<option v-for="o in x.option" :value="o[x.name]">{{o[x.optionLabel]}}</option>
				</select>
			</div>
		</template>
	</template>
</div>
</script>
<script>
Vue.component('formGenerator',{
	template : "#formGenerator",
	props : {
		fields : { //Object -> name, label, hint, placeholder
			type : Array,
			required : true
		},
		model : { //Object -> berasal dari prop fields (pada name)
			type : Object,
			required : true
		},
		error : { // Sama dengan model
			type : Object,
			required : true
		}
	}
})
</script>
