<!--
Ce composant récupère l'entête et les lignes de menu (Sheet).
-->

<script setup>
import { computed, reactive } from 'vue'
import ItemsSheet from './ItemsSheet.vue';
import HeaderSheet from './HeaderSheet.vue';

const datas = reactive(document.querySelector('#vue_items_sheet'))

const items = computed(() => {
   return JSON.parse(datas.value)
})

const header = computed(() => {
   return JSON.parse(datas.dataset.global)
})

const norecord = computed(() => {
   return JSON.parse(datas.dataset.norecord)
})

</script>

<template>
    <HeaderSheet :header="header" ></HeaderSheet>
    <ItemsSheet :items="items" :norecord="norecord"></ItemsSheet>
</template>

<style>

</style>
