<!--
Ce composant récupère l'entête et les lignes des Sous Menus menu (Menu).
-->

<script setup>
import { computed, provide, reactive } from 'vue'
import FiltreMenu from './FiltreMenu.vue';
import ItemsMenu from './ItemsMenu.vue';
import SelectMenu from './SelectMenu.vue';
import ButtonsMenu from './ButtonsMenu.vue';

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

const buttons = computed(() => {
   return JSON.parse(datas.dataset.buttons)
})

</script>

<template>
   <SelectMenu>
      <FiltreMenu :items="filtre" ></FiltreMenu>
      <ItemsMenu :header="header" :items="items" :norecord="norecord"></ItemsMenu>
    </SelectMenu>
    <ButtonsMenu :buttons="buttons" ></ButtonsMenu>

</template>

<style>

</style>
