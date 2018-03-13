<script type="text/x-template" id="fieldInputBS">
<input 
        class="form-control" 
        type="text" 
        v-model="value" 
        :disabled="disabled"
        :maxlength="schema.max"
        :placeholder="schema.placeholder"
        :readonly="schema.readonly" >
</script>
