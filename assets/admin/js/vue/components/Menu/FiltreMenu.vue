<script setup>
import {inject, ref } from 'vue'

const props = defineProps(['items'])
const myitems = ref(props.items)
const myselect = inject('myselect')

const selectSheet = (sel) => {
    myselect.value = sel
}

</script>

<template>
    <select class="form-select h-bg-success text-white" id="select_page" name="menu" >
        <option class="align-middle" @click="selectSheet('all')">All</option>
        <option v-for="(item, index) in myitems" :value="item.id" class="align-middle" @click="selectSheet(item.name)">{{ item.name  }}</option>
    </select>
</template>

<style>

</style>
