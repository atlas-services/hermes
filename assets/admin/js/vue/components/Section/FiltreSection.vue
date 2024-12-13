<script setup>
import {inject, ref } from 'vue'

const props = defineProps(['items'])
const myitems = ref(props.items)
const myselect = inject('myselect')

const selectMenu = (sel) => {
    myselect.value = sel
}

</script>

<template>
    <select class="form-select h-bg-success text-white" id="select_page" name="section" >
        <option class="align-middle" @click="selectMenu('all')">All</option>
        <option v-for="(item, index) in myitems" :value="item.id" class="align-middle" @click="selectMenu(item.name)">{{ item.name  }}</option>
    </select>
</template>

<style>

</style>
