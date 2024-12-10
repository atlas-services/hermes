<!--
Ce composant récupère l'entête et les lignes des Sous Menus menu (Menu).
-->

<script setup>
import { computed, provide, reactive } from 'vue'
import FiltreMenu from './FiltreMenu.vue';
import HeaderMenu from './HeaderMenu.vue';
import ItemsMenu from './ItemsMenu.vue';
import SelectMenu from './SelectMenu.vue';

provide('myselect', false)


const datas = reactive(document.querySelector('#vue_items_menu'))

const filtre = computed(() => {
   return JSON.parse(datas.dataset.filtre)
})

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
   <SelectMenu>
      <FiltreMenu :items="filtre" ></FiltreMenu>
      <HeaderMenu :header="header" ></HeaderMenu>
      <ItemsMenu :items="items" :norecord="norecord"></ItemsMenu>
    </SelectMenu>
</template>

<style>

</style>
