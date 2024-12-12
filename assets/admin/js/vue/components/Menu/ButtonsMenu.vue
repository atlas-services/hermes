<template>

<a type="button" class="col-6 col-sm-2 btn btn-outline-secondary hms-bg-color1 text-white " id="menu-add" :href="buttons.addpost">{{ buttons.newcontent }}</a>
<a type="button" class="col-6 col-sm-2 mx-2 btn btn-outline-secondary hms-bg-color1 text-white " id="menu-new" :href="buttons.addmenu">{{ buttons.newmenu }}</a>

</template>

<script setup>
import { computed,} from 'vue'
const props = defineProps(['buttons'])
const buttons = computed(() => {
   return props.buttons
})
</script>

<style></style>
